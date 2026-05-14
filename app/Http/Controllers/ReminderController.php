<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReminderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $reminders = $user->reminders()
            ->with('task')
            ->orderBy('reminder_time', 'asc')
            ->get();

        $tasks = $user->tasks()->where('status', '!=', 'completed')->get();

        // New Logic: "Active" includes both upcoming and missed reminders as long as they aren't marked as read.
        $active = $reminders->filter(fn ($r) => !$r->is_read);
        $completed = $reminders->filter(fn ($r) => $r->is_read);

        return view('reminders', compact('active', 'completed', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'task_id' => 'nullable|exists:tasks,id',
            'new_task_title' => 'nullable|string|max:255',
            'reminder_time' => 'required|date',
        ]);
        
        $taskId = $validated['task_id'];
        
        if (!empty($validated['new_task_title'])) {
            $task = $request->user()->tasks()->create([
                'title' => $validated['new_task_title'],
                'status' => 'pending'
            ]);
            $taskId = $task->id;
        }

        $request->user()->reminders()->create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'task_id' => $taskId,
            'reminder_time' => $validated['reminder_time'],
        ]);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function update(Request $request, $id)
    {
        $reminder = $request->user()->reminders()->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'task_id' => 'nullable|exists:tasks,id',
            'new_task_title' => 'nullable|string|max:255',
            'reminder_time' => 'required|date',
        ]);

        $taskId = $validated['task_id'];
        
        if (!empty($validated['new_task_title'])) {
            $task = $request->user()->tasks()->create([
                'title' => $validated['new_task_title'],
                'status' => 'pending'
            ]);
            $taskId = $task->id;
        }

        $reminder->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'task_id' => $taskId,
            'reminder_time' => $validated['reminder_time'],
        ]);

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
    }

    public function markAsRead(Request $request, $id)
    {
        $reminder = $request->user()->reminders()->findOrFail($id);
        
        $reminder->update(['is_read' => true]);

        return back()->with('success', 'Reminder marked as read.');
    }

    public function destroy(Request $request, $id)
    {
        $reminder = $request->user()->reminders()->findOrFail($id);
        $reminder->delete();

        return back()->with('error', 'Reminder deleted.');
    }
}
