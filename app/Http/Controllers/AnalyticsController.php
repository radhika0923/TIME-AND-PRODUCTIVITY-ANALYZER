<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Basic Stats
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $totalTime = $user->timeLogs()->sum('duration'); // Total time in minutes

        // Productivity Score
        $productivityScore = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Last 7 days dates for charts
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $last7Days[] = Carbon::today()->subDays($i)->format('Y-m-d');
        }

        // Weekly Time Data
        $weeklyTimeData = [];
        $weeklyTimeQuery = $user->timeLogs()
            ->selectRaw('DATE(created_at) as date, SUM(duration) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        foreach ($last7Days as $date) {
            $weeklyTimeData[] = isset($weeklyTimeQuery[$date]) ? round($weeklyTimeQuery[$date] / 60, 2) : 0; // Convert to hours
        }

        // Completed Tasks Per Day
        $completedTasksPerDay = [];
        $completedTasksQuery = $user->tasks()
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as total')
            ->where('status', 'completed')
            ->where('updated_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        foreach ($last7Days as $date) {
            $completedTasksPerDay[] = $completedTasksQuery[$date] ?? 0;
        }

        // Time Analysis
        $totalTimeThisWeek = array_sum($weeklyTimeData); // in hours
        $avgDailyFocusTime = round($totalTimeThisWeek / 7, 2);

        // Chart Labels
        $chartLabels = array_map(function ($date) {
            return Carbon::parse($date)->format('D');
        }, $last7Days);

        return view('analytics', compact(
            'totalTasks',
            'completedTasks',
            'totalTime',
            'productivityScore',
            'weeklyTimeData',
            'completedTasksPerDay',
            'totalTimeThisWeek',
            'avgDailyFocusTime',
            'chartLabels'
        ));
    }
}
