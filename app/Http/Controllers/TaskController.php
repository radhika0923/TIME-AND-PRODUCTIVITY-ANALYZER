<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->tasks()->with('category')->latest();
        
        if ($request->has('filter')) {
            if ($request->filter === 'completed') {
                $query->where('status', 'completed');
            } elseif ($request->filter === 'pending') {
                $query->where('status', 'pending');
            }
        }

        // Category filter
        if ($request->filled('category')) {
            if ($request->category === 'uncategorized') {
                $query->whereNull('category_id');
            } else {
                $query->where('category_id', $request->category);
            }
        }

        $tasks = $query->get();
        $categories = $request->user()->categories()->withCount('tasks')->orderBy('name')->get();

        return view('tasks', compact('tasks', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $request->user()->tasks()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Task added successfully.');
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $task->update($validated);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->findOrFail($id);
        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }

    public function markComplete(Request $request, $id)
    {
        $task = Task::where('user_id', $request->user()->id)->findOrFail($id);
        
        $task->update([
            'status' => $task->status === 'completed' ? 'pending' : 'completed'
        ]);

        return redirect()->back()->with('success', 'Task status updated.');
    }
}
