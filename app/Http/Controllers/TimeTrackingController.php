<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeTrackingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $logs = $user->timeLogs()->with('task')->latest()->get();
        $tasks = $user->tasks()->where('status', 'pending')->latest()->get();
        
        // Calculate today's summary
        $todayLogs = $user->timeLogs()->whereDate('created_at', Carbon::today())->get();
        $totalTimeToday = $todayLogs->sum('duration'); // in minutes
        $sessionsToday = $todayLogs->count();

        // Check if there is an active session
        $activeSession = session('active_timer');
        $runningDuration = 0;
        if ($activeSession) {
            $startTime = Carbon::parse($activeSession['start_time']);
            $runningDuration = now()->diffInSeconds($startTime);
        }

        return view('time-tracking', compact('logs', 'tasks', 'totalTimeToday', 'sessionsToday', 'activeSession', 'runningDuration'));
    }

    public function start(Request $request)
    {
        $request->validate([
            'task_id' => 'nullable|exists:tasks,id',
        ]);

        if (session()->has('active_timer')) {
            return response()->json(['error' => 'Timer already running.'], 400);
        }

        $task = null;
        if ($request->task_id) {
            $task = Task::where('user_id', $request->user()->id)->find($request->task_id);
        }

        session([
            'active_timer' => [
                'start_time' => now()->toIso8601String(),
                'task_id' => $request->task_id,
                'task_name' => $task ? $task->title : 'Uncategorized',
            ]
        ]);

        return response()->json(['success' => true, 'message' => 'Timer started.', 'data' => session('active_timer')]);
    }

    public function stop(Request $request)
    {
        if (!session()->has('active_timer')) {
            return response()->json(['error' => 'No active timer found.'], 400);
        }

        $activeSession = session('active_timer');
        $startTime = Carbon::parse($activeSession['start_time']);
        $durationInMinutes = round($startTime->diffInMinutes(now()));

        // Create log if duration is at least 1 minute
        if ($durationInMinutes >= 1) {
            TimeLog::create([
                'user_id' => $request->user()->id,
                'task_id' => $activeSession['task_id'],
                'duration' => $durationInMinutes,
            ]);
        }

        session()->forget('active_timer');

        return response()->json(['success' => true, 'message' => 'Timer stopped.', 'duration' => $durationInMinutes]);
    }
}
