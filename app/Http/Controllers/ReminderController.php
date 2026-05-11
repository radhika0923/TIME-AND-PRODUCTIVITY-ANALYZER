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

        $upcoming = $reminders->filter(fn ($r) => $r->reminder_time > now() && !$r->is_read);
        $activeMissed = $reminders->filter(fn ($r) => $r->reminder_time <= now() && !$r->is_read);
        $completed = $reminders->filter(fn ($r) => $r->is_read);

        return view('reminders', compact('upcoming', 'activeMissed', 'completed', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'nullable|string',
            'task_id' => 'nullable|exists:tasks,id',
            'reminder_time' => 'required|date',
        ]);

        $request->user()->reminders()->create($validated);

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
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

        return back()->with('success', 'Reminder deleted.');
    }
}
