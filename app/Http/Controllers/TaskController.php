<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = $user->tasks();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'due_date');
        $sortDirection = $request->get('direction', 'asc');

        if ($sortBy === 'priority') {
            $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
            $query->orderByRaw('CASE 
                WHEN priority = "high" THEN 1
                WHEN priority = "medium" THEN 2
                WHEN priority = "low" THEN 3
                ELSE 4 END ' . $sortDirection);
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }

        $tasks = $query->paginate(12);

        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->count();
        $pendingTasks = $user->tasks()->where('status', 'pending')->count();

        return view('tasks.index', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'priority' => 'sometimes|required|in:low,medium,high',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,completed',
        ]);

        $task->update($validated);

        return response()->json($task);
    }

    public function destroy(Request $request, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    public function toggleStatus(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $newStatus = $task->status === 'completed' ? 'pending' : 'completed';
        $task->update(['status' => $newStatus]);

        return response()->json(['status' => $newStatus]);
    }
}
