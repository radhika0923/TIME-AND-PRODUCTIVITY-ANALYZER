<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use App\Models\User;
use App\Support\UserTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TimeTrackingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['focusTimerTask']);

        $filterValidator = Validator::make($request->only(['from', 'to', 'task_id']), [
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
        ]);

        $filters = $filterValidator->fails()
            ? []
            : array_filter(
                $filterValidator->validated(),
                fn ($v) => $v !== null && $v !== ''
            );

        $logsQuery = $user->timeLogs()->with('task')->latest();

        if (! empty($filters['from'])) {
            [$fromStart] = UserTime::dayUtcRange($user, $filters['from']);
            $logsQuery->where('created_at', '>=', $fromStart);
        }
        if (! empty($filters['to'])) {
            [, $toEnd] = UserTime::dayUtcRange($user, $filters['to']);
            $logsQuery->where('created_at', '<=', $toEnd);
        }
        if (! empty($filters['task_id'])) {
            $owns = Task::where('user_id', $user->id)->whereKey($filters['task_id'])->exists();
            if ($owns) {
                $logsQuery->where('task_id', $filters['task_id']);
            }
        }

        if ($request->filled('time_slot')) {
            $slot = $request->time_slot;
            $logsQuery->where(function ($q) use ($slot) {
                // SQLite uses strftime. MySQL uses HOUR(). 
                // We'll use a more general approach or just handle the current DB.
                $dbDriver = DB::getDriverName();
                
                if ($dbDriver === 'sqlite') {
                    if ($slot === 'morning') $q->whereRaw("strftime('%H', created_at) BETWEEN '06' AND '11'");
                    elseif ($slot === 'afternoon') $q->whereRaw("strftime('%H', created_at) BETWEEN '12' AND '17'");
                    elseif ($slot === 'evening') $q->whereRaw("strftime('%H', created_at) BETWEEN '18' AND '23'");
                    elseif ($slot === 'night') $q->whereRaw("strftime('%H', created_at) BETWEEN '00' AND '05'");
                } else {
                    if ($slot === 'morning') $q->whereRaw('HOUR(created_at) BETWEEN 6 AND 11');
                    elseif ($slot === 'afternoon') $q->whereRaw('HOUR(created_at) BETWEEN 12 AND 17');
                    elseif ($slot === 'evening') $q->whereRaw('HOUR(created_at) BETWEEN 18 AND 23');
                    elseif ($slot === 'night') $q->whereRaw('HOUR(created_at) BETWEEN 0 AND 5');
                }
            });
            $filters['time_slot'] = $slot;
        }

        $logs = $logsQuery->paginate(15)->withQueryString();

        $tasks = $user->tasks()->where('status', 'pending')->latest()->get();
        $filterTasks = $user->tasks()->orderBy('title')->get();

        [$todayStart, $todayEnd] = UserTime::todayUtcRange($user);
        $todayLogs = $user->timeLogs()->whereBetween('created_at', [$todayStart, $todayEnd])->get();
        $totalTimeTodaySeconds = (int) $todayLogs->sum('duration');
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

        $recentTaskIds = $user->timeLogs()
            ->whereNotNull('task_id')
            ->latest()
            ->limit(40)
            ->pluck('task_id')
            ->unique()
            ->values()
            ->take(8)
            ->all();

        $recentTasksForChips = collect($recentTaskIds)
            ->map(fn ($id) => Task::query()->where('user_id', $user->id)->whereKey($id)->first())
            ->filter()
            ->values();

        $editTasks = $user->tasks()->orderBy('title')->get();

        return view('time-tracking', compact(
            'logs',
            'tasks',
            'filterTasks',
            'editTasks',
            'totalTimeTodaySeconds',
            'sessionsToday',
            'activeSession',
            'runningDuration',
            'filters',
            'recentTasksForChips'
        ));
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
            $maxDurationSeconds = max(0, (int) $startTime->diffInSeconds(now()));
            
            // Allow frontend to specify exact duration (to account for pauses)
            $frontendDuration = $request->input('duration');
            $durationSeconds = $maxDurationSeconds;
            
            if (is_numeric($frontendDuration) && $frontendDuration >= 0 && $frontendDuration <= $maxDurationSeconds) {
                $durationSeconds = (int) $frontendDuration;
            }

            $logged = $durationSeconds >= 60;
            if ($logged) {
                TimeLog::create([
                    'user_id' => $user->id,
                    'task_id' => $taskId,
                    'duration' => $durationSeconds,
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
                'duration_seconds' => $durationSeconds,
            ]);
        });
    }
}
