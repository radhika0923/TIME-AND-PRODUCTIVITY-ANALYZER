<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TimeTrackingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load('focusTimerTask');

        $logs = $user->timeLogs()->with('task')->latest()->paginate(15);
        $tasks = $user->tasks()->where('status', 'pending')->latest()->get();

        // Calculate today's summary
        $todayLogs = $user->timeLogs()->whereDate('created_at', Carbon::today())->get();
        $totalTimeToday = $todayLogs->sum('duration'); // in minutes
        $sessionsToday = $todayLogs->count();

        $activeSession = null;
        $runningDuration = 0;
        if ($user->focus_timer_started_at) {
            $activeSession = [
                'start_time' => $user->focus_timer_started_at->toIso8601String(),
                'task_id' => $user->focus_timer_task_id,
                'task_name' => $user->focusTimerTask?->title ?? 'Uncategorized',
            ];
            $runningDuration = now()->diffInSeconds($user->focus_timer_started_at);
        }

        return view('time-tracking', compact('logs', 'tasks', 'totalTimeToday', 'sessionsToday', 'activeSession', 'runningDuration'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = User::query()->whereKey($request->user()->id)->lockForUpdate()->firstOrFail();

            if ($user->focus_timer_started_at !== null) {
                return response()->json(['error' => 'Timer already running.'], 400);
            }

            $task = null;
            if ($request->task_id) {
                $task = Task::where('user_id', $user->id)->find($request->task_id);
            }

            $user->forceFill([
                'focus_timer_started_at' => now(),
                'focus_timer_task_id' => $task?->id,
            ])->save();

            $taskName = $task ? $task->title : 'Uncategorized';

            return response()->json([
                'success' => true,
                'message' => 'Timer started.',
                'data' => [
                    'start_time' => $user->focus_timer_started_at->toIso8601String(),
                    'task_id' => $user->focus_timer_task_id,
                    'task_name' => $taskName,
                ],
            ]);
        });
    }

    public function stop(Request $request)
    {
        return DB::transaction(function () use ($request) {
            /** @var User $user */
            $user = User::query()->whereKey($request->user()->id)->lockForUpdate()->firstOrFail();

            if ($user->focus_timer_started_at === null) {
                return response()->json(['error' => 'No active timer found.'], 400);
            }

            $startTime = $user->focus_timer_started_at;
            $taskId = $user->focus_timer_task_id;
            $durationInMinutes = max(0, (int) round($startTime->diffInMinutes(now())));

            $logged = $durationInMinutes >= 1;
            if ($logged) {
                TimeLog::create([
                    'user_id' => $user->id,
                    'task_id' => $taskId,
                    'duration' => $durationInMinutes,
                ]);
            }

            $user->forceFill([
                'focus_timer_started_at' => null,
                'focus_timer_task_id' => null,
            ])->save();

            return response()->json([
                'success' => true,
                'message' => $logged
                    ? 'Timer stopped and session saved.'
                    : 'Session was under 1 minute and was not saved.',
                'logged' => $logged,
                'duration' => $durationInMinutes,
            ]);
        });
    }
}
