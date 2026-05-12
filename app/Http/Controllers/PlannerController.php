<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class PlannerController extends Controller
{
    public function index()
    {
        return view('planner');
    }

    /**
     * Get tasks as calendar events.
     */
    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $tasks = Task::where('user_id', auth()->id())
            ->whereNotNull('due_date')
            ->when($start, fn($q) => $q->where('due_date', '>=', $start))
            ->when($end, fn($q) => $q->where('due_date', '<=', $end))
            ->with('category')
            ->get();

        $events = $tasks->map(function ($task) {
            $end = $task->due_date->copy()->addMinutes($task->scheduled_duration_minutes ?? 60);
            
            return [
                'id' => $task->id,
                'title' => $task->title,
                'start' => $task->due_date->toIso8601String(),
                'end' => $end->toIso8601String(),
                'backgroundColor' => $task->category?->color ?? '#6366f1',
                'borderColor' => $task->category?->color ?? '#6366f1',
                'extendedProps' => [
                    'description' => $task->description,
                    'priority' => $task->priority,
                    'status' => $task->status,
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Update task schedule via drag and drop.
     */
    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        
        $request->validate([
            'start' => 'required|date',
            'end' => 'nullable|date|after:start',
        ]);

        $start = new \DateTime($request->start);
        $task->due_date = $start;
        $task->is_scheduled = true;

        if ($request->end) {
            $end = new \DateTime($request->end);
            $duration = ($end->getTimestamp() - $start->getTimestamp()) / 60;
            $task->scheduled_duration_minutes = (int)$duration;
        }

        $task->save();

        return response()->json(['success' => true]);
    }
}
