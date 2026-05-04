<?php

namespace App\Http\Controllers;

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

        $recentTasks = (clone $tasksQuery)
            ->latest()
            ->take(4)
            ->get()
            ->map(function ($task) {
                $statusTone = $task->status === 'completed' ? 'emerald' : 'amber';
                $statusIcon = $task->status === 'completed'
                    ? '<path d="M20 6 9 17l-5-5" /><path d="M21 12a9 9 0 1 1-9-9" />'
                    : '<path d="M12 8v5l3 2" /><circle cx="12" cy="12" r="9" />';

                return [
                    'title' => $task->title,
                    'time' => $task->created_at?->diffForHumans() ?? 'Just now',
                    'meta' => Str::limit($task->description ?: ucfirst($task->status), 52),
                    'tone' => $statusTone,
                    'icon' => $statusIcon,
                ];
            })
            ->all();

        return view('dashboard', [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'pendingTasks' => $pendingTasks,
            'completionRate' => $completionRate,
            'recentTasks' => $recentTasks,
        ]);
    }
}