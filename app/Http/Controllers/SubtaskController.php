<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Task $task)
    {
        // Ensure user owns the task
        if ($task->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask = $task->subtasks()->create([
            'title' => $validated['title'],
            'is_completed' => false,
        ]);

        return response()->json(['success' => true, 'subtask' => $subtask]);
    }

    public function toggle(Request $request, Subtask $subtask)
    {
        // Ensure user owns the subtask's task
        if ($subtask->task->user_id !== $request->user()->id) {
            abort(403);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return response()->json(['success' => true, 'is_completed' => $subtask->is_completed]);
    }

    public function destroy(Request $request, Subtask $subtask)
    {
        // Ensure user owns the subtask's task
        if ($subtask->task->user_id !== $request->user()->id) {
            abort(403);
        }

        $subtask->delete();

        return response()->json(['success' => true]);
    }
}
