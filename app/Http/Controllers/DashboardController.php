<?php

namespace App\Http\Controllers;

use App\Support\Duration;
use App\Support\UserTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tasksQuery = $user->tasks();

        $totalTasks = $tasksQuery->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();
        $pendingTasks = (clone $tasksQuery)->where('status', 'pending')->count();
        $completionRate = $totalTasks > 0 ? (int) round(($completedTasks / $totalTasks) * 100) : 0;

        $totalSeconds = (int) $user->timeLogs()->sum('duration');
        $runningSeconds = $user->focus_timer_started_at ? now()->diffInSeconds($user->focus_timer_started_at) : 0;
        
        $totalTime = Duration::toDecimalHours($totalSeconds + $runningSeconds);

        [$todayStart, $todayEnd] = UserTime::todayUtcRange($user);
        $todaySeconds = (int) $user->timeLogs()->whereBetween('created_at', [$todayStart, $todayEnd])->sum('duration');
        $todaySeconds += $runningSeconds; // Add current session to today's progress

        $dailyGoalSeconds = $user->daily_goal_seconds ?? 14400; // default 4 hours

        $weekOffset = max(0, min(12, (int) $request->query('week', 0)));
        $tz = UserTime::timezone($user);
        $weekEndDay = Carbon::now($tz)->startOfDay()->subDays(7 * $weekOffset);

        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = $weekEndDay->copy()->subDays($i)->format('Y-m-d');
        }

        $chartData = [];
        $todayDate = Carbon::now($tz)->format('Y-m-d');

        foreach ($last7Days as $date) {
            [$ds, $de] = UserTime::dayUtcRange($user, $date);
            $daySeconds = (int) $user->timeLogs()->whereBetween('created_at', [$ds, $de])->sum('duration');
            
            // Add running timer to chart if it belongs to today
            if ($date === $todayDate && $weekOffset === 0) {
                $daySeconds += $runningSeconds;
            }
            
            $chartData[] = round($daySeconds / 3600, 2);
        }

        $chartLabels = array_map(fn ($d) => Carbon::parse($d, $tz)->format('D'), $last7Days);

        $recentActivities = collect();

        $recentTaskItems = (clone $tasksQuery)->latest()->take(5)->get();
        foreach ($recentTaskItems as $task) {
            $isCompleted = $task->status === 'completed';
            $recentActivities->push([
                'type' => $isCompleted ? 'completed' : 'created',
                'title' => $task->title,
                'time' => $task->updated_at ?? $task->created_at,
                'time_human' => ($task->updated_at ?? $task->created_at)?->diffForHumans() ?? 'Just now',
            ]);
        }

        $recentTimeLogs = $user->timeLogs()->with('task')->latest()->take(5)->get();
        foreach ($recentTimeLogs as $log) {
            $recentActivities->push([
                'type' => 'focus',
                'title' => $log->task?->title ?? 'Untitled Task',
                'time' => $log->created_at,
                'time_human' => $log->created_at?->diffForHumans() ?? 'Just now',
                'duration_label' => Duration::format($log->duration),
            ]);
        }

        $recentActivities = $recentActivities
            ->sortByDesc('time')
            ->take(4)
            ->values()
            ->all();

        $topTasksByTime = $user->tasks()
            ->withSum('timeLogs', 'duration')
            ->get()
            ->filter(fn ($t) => ($t->time_logs_sum_duration ?? 0) > 0)
            ->sortByDesc('time_logs_sum_duration')
            ->take(3);

        $maxTimeSpent = $topTasksByTime->max('time_logs_sum_duration') ?: 1;
        $topTaskProgress = $topTasksByTime->map(function ($task) use ($maxTimeSpent) {
            $seconds = (int) ($task->time_logs_sum_duration ?? 0);

            return [
                'title' => Str::limit($task->title, 25),
                'percentage' => (int) round(($seconds / $maxTimeSpent) * 100),
                'hours' => Duration::toDecimalHours($seconds),
            ];
        })->all();

        $focusInsight = match (true) {
            $totalSeconds < 60 => 'Start a focus session from Time Tracking to populate your weekly chart and recent activity.',
            $pendingTasks > 0 && $completedTasks > 0 => "You have {$pendingTasks} pending task".($pendingTasks === 1 ? '' : 's')." and {$completedTasks} completed—short focus blocks help move pending work forward.",
            $pendingTasks > 0 => "You have {$pendingTasks} pending task".($pendingTasks === 1 ? '' : 's').'. Try pairing the next task with a timed focus session.',
            default => "You've logged {$totalTime} total hours of focus time. Open Analytics to see trends over time.",
        };

        $chartWeekLabel = $weekOffset === 0 ? 'This week' : ($weekOffset === 1 ? 'Last week' : $weekOffset.' weeks ago');

        return view('dashboard', [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'completionRate' => $completionRate,
            'totalTime' => $totalTime,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'recentActivities' => $recentActivities,
            'topTaskProgress' => $topTaskProgress,
            'focusInsight' => $focusInsight,
            'weekOffset' => $weekOffset,
            'chartWeekLabel' => $chartWeekLabel,
            'todaySeconds' => $todaySeconds,
            'dailyGoalSeconds' => $dailyGoalSeconds,
        ]);
    }
}
