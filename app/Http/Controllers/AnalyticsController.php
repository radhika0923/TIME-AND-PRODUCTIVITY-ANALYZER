<?php

namespace App\Http\Controllers;

use App\Support\Duration;
use App\Support\UserTime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $tz = UserTime::timezone($user);

        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $totalSecondsAll = (int) $user->timeLogs()->sum('duration');

        $productivityScore = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        $last7Days = [];
        $anchorDay = Carbon::now($tz)->startOfDay();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = $anchorDay->copy()->subDays($i)->format('Y-m-d');
        }

        $weeklyTimeData = [];
        foreach ($last7Days as $date) {
            [$ds, $de] = UserTime::dayUtcRange($user, $date);
            $sec = (int) $user->timeLogs()->whereBetween('created_at', [$ds, $de])->sum('duration');
            $weeklyTimeData[] = round($sec / 3600, 2);
        }

        $completedTasksPerDay = [];
        $completedTasksQuery = $user->tasks()
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as total')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::now($tz)->subDays(6)->startOfDay()->utc())
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        foreach ($last7Days as $date) {
            $completedTasksPerDay[] = (int) ($completedTasksQuery[$date] ?? 0);
        }

        $totalTimeThisWeek = array_sum($weeklyTimeData);
        $avgDailyFocusTime = round($totalTimeThisWeek / 7, 2);

        $chartLabels = array_map(function ($date) use ($tz) {
            return Carbon::parse($date, $tz)->format('D');
        }, $last7Days);

        $focusStreak = 0;
        for ($i = 0; $i < 365; $i++) {
            $d = Carbon::now($tz)->startOfDay()->subDays($i)->format('Y-m-d');
            [$ds, $de] = UserTime::dayUtcRange($user, $d);
            $sec = (int) $user->timeLogs()->whereBetween('created_at', [$ds, $de])->sum('duration');
            if ($sec < 60) {
                break;
            }
            $focusStreak++;
        }

        // Heatmap Data (Last 365 days)
        $heatmapData = [];
        $startDate = Carbon::now($tz)->subDays(364)->startOfDay();
        $endDate = Carbon::now($tz)->endOfDay();

        $logs = $user->timeLogs()
            ->whereBetween('created_at', [$startDate->copy()->utc(), $endDate->copy()->utc()])
            ->selectRaw('DATE(created_at) as date, SUM(duration) as total_seconds')
            ->groupBy('date')
            ->pluck('total_seconds', 'date')
            ->toArray();

        for ($i = 0; $i < 365; $i++) {
            $currentDate = $startDate->copy()->addDays($i)->format('Y-m-d');
            $heatmapData[$currentDate] = (int) ($logs[$currentDate] ?? 0);
        }

        $maxDailySeconds = !empty($heatmapData) ? max($heatmapData) : 1;
        if ($maxDailySeconds < 3600) $maxDailySeconds = 3600; // Min scale 1 hour

        $totalTimeLifetimeLabel = Duration::format($totalSecondsAll);

        return view('analytics', compact(
            'totalTasks',
            'completedTasks',
            'totalTimeLifetimeLabel',
            'productivityScore',
            'weeklyTimeData',
            'completedTasksPerDay',
            'totalTimeThisWeek',
            'avgDailyFocusTime',
            'chartLabels',
            'focusStreak',
            'heatmapData',
            'maxDailySeconds'
        ));
    }
}
