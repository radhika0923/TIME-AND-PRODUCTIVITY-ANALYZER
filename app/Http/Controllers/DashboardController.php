<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        // Calculate total time from time logs (convert minutes to hours)
        $totalMinutes = $user->timeLogs()->sum('duration') ?? 0;
        $totalTime = round($totalMinutes / 60, 1);

        // ── Weekly Productivity Chart (real data) ──
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }

        $weeklyTimeQuery = $user->timeLogs()
            ->selectRaw('DATE(created_at) as date, SUM(duration) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = array_map(fn($d) => Carbon::parse($d)->format('D'), $last7Days);
        $chartData = array_map(fn($d) => isset($weeklyTimeQuery[$d]) ? round($weeklyTimeQuery[$d] / 60, 2) : 0, $last7Days);

        // ── Recent Activity (real data — last 5 events) ──
        // Merge recent tasks (created/completed) and time logs into one timeline
        $recentActivities = collect();

        // Recent tasks
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

        // Recent time logs
        $recentTimeLogs = $user->timeLogs()->with('task')->latest()->take(5)->get();
        foreach ($recentTimeLogs as $log) {
            $recentActivities->push([
                'type' => 'focus',
                'title' => $log->task?->title ?? 'Untitled Task',
                'time' => $log->created_at,
                'time_human' => $log->created_at?->diffForHumans() ?? 'Just now',
                'duration' => $log->duration,
            ]);
        }

        // Sort by time descending, take 4
        $recentActivities = $recentActivities
            ->sortByDesc('time')
            ->take(4)
            ->values()
            ->all();

        // ── Top Tasks by Time Spent (for progress bars) ──
        // Fetch all tasks with their time sums, then filter/sort in PHP (SQLite-compatible)
        $topTasksByTime = $user->tasks()
            ->withSum('timeLogs', 'duration')
            ->get()
            ->filter(fn($t) => ($t->time_logs_sum_duration ?? 0) > 0)
            ->sortByDesc('time_logs_sum_duration')
            ->take(3);

        $maxTimeSpent = $topTasksByTime->max('time_logs_sum_duration') ?: 1;
        $topTaskProgress = $topTasksByTime->map(function ($task) use ($maxTimeSpent) {
            $minutes = $task->time_logs_sum_duration ?? 0;
            return [
                'title' => Str::limit($task->title, 25),
                'percentage' => (int) round(($minutes / $maxTimeSpent) * 100),
                'hours' => round($minutes / 60, 1),
            ];
        })->all();

        $focusInsight = match (true) {
            $totalMinutes < 1 => 'Start a focus session from Time Tracking to populate your weekly chart and recent activity.',
            $pendingTasks > 0 && $completedTasks > 0 => "You have {$pendingTasks} pending task".($pendingTasks === 1 ? '' : 's')." and {$completedTasks} completed—short focus blocks help move pending work forward.",
            $pendingTasks > 0 => "You have {$pendingTasks} pending task".($pendingTasks === 1 ? '' : 's').'. Try pairing the next task with a timed focus session.',
            default => "You've logged {$totalTime} total hours of focus time. Open Analytics to see trends over time.",
        };

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
        ]);
    }
}