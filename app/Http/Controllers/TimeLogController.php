<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TimeLog;
use App\Support\Duration;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TimeLogController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'duration_hours' => ['required_without:duration_minutes', 'nullable', 'integer', 'min:0', 'max:24'],
            'duration_minutes' => ['required_without:duration_hours', 'nullable', 'integer', 'min:0', 'max:59'],
            'task_id' => ['nullable', 'exists:tasks,id'],
        ]);

        $hours = (int) ($validated['duration_hours'] ?? 0);
        $minutes = (int) ($validated['duration_minutes'] ?? 0);
        $seconds = ($hours * 3600) + ($minutes * 60);

        if ($seconds <= 0) {
            return back()->withErrors(['duration_hours' => 'Please enter a valid duration.'])->withInput();
        }

        $request->user()->timeLogs()->create([
            'task_id' => $validated['task_id'] ?? null,
            'duration' => $seconds,
        ]);

        return back()->with('time_log_status', 'Session logged successfully.');
    }

    public function update(Request $request, TimeLog $time_log): RedirectResponse
    {
        $this->authorize('update', $time_log);

        if ($request->input('task_id') === '') {
            $request->merge(['task_id' => null]);
        }

        $validated = $request->validate([
            'duration' => ['required', 'integer', 'min:60', 'max:864000'],
            'task_id' => ['nullable', 'exists:tasks,id'],
        ]);

        $taskId = null;
        if (! empty($validated['task_id'])) {
            $task = Task::where('user_id', $request->user()->id)->find($validated['task_id']);
            if (! $task) {
                return back()->withErrors(['task_id' => 'Invalid task.'])->withInput();
            }
            $taskId = $task->id;
        }

        $time_log->update([
            'duration' => $validated['duration'],
            'task_id' => $taskId,
        ]);

        return back()->with('time_log_status', 'Session updated.');
    }

    public function destroy(Request $request, TimeLog $time_log): RedirectResponse
    {
        $this->authorize('delete', $time_log);

        $time_log->delete();

        return back()->with('time_log_status', 'Session deleted.');
    }

    public function export(Request $request): StreamedResponse|Response
    {
        $this->authorize('export', TimeLog::class);

        $user = $request->user();
        $validated = $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
        ]);

        $tz = $user->timezone ?: (string) config('app.timezone');
        $from = $validated['from'] ?? Carbon::now($tz)->subDays(30)->format('Y-m-d');
        $to = $validated['to'] ?? Carbon::now($tz)->format('Y-m-d');

        $startUtc = Carbon::createFromFormat('Y-m-d', $from, $tz)->startOfDay()->utc();
        $endUtc = Carbon::createFromFormat('Y-m-d', $to, $tz)->endOfDay()->utc();

        $filename = 'focus-sessions-'.$from.'-to-'.$to.'.csv';

        return response()->streamDownload(function () use ($user, $startUtc, $endUtc): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['id', 'created_at_utc', 'task_id', 'task_title', 'duration_seconds', 'duration_human']);

            $user->timeLogs()
                ->with('task')
                ->whereBetween('created_at', [$startUtc, $endUtc])
                ->orderBy('created_at')
                ->chunk(200, function ($logs) use ($handle): void {
                    foreach ($logs as $log) {
                        fputcsv($handle, [
                            $log->id,
                            $log->created_at->toIso8601String(),
                            $log->task_id ?? '',
                            $log->task?->title ?? '',
                            $log->duration,
                            Duration::format($log->duration),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
