<x-layouts.app title="Reminders - Time & Productivity Analyzer">
    <div x-data="{ addModalOpen: false, currentTab: 'active' }">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-white">Reminders & Notifications</h1>
            <button @click="addModalOpen = true" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 w-full sm:w-auto justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Reminder
            </button>
        </div>

        <!-- Filters/Tabs -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-px mt-6">
            <button @click="currentTab = 'active'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2" :class="currentTab === 'active' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700'">
                Active & Missed
                @if($activeMissed->count() > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500/20 text-[10px] font-bold text-red-400">{{ $activeMissed->count() }}</span>
                @endif
            </button>
            <button @click="currentTab = 'upcoming'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2" :class="currentTab === 'upcoming' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700'">
                Upcoming
                @if($upcoming->count() > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-slate-800 text-[10px] font-bold text-slate-300">{{ $upcoming->count() }}</span>
                @endif
            </button>
            <button @click="currentTab = 'completed'" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2" :class="currentTab === 'completed' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700'">
                Completed
            </button>
        </div>

        <!-- Reminders Content -->
        <div class="mt-6">
            <!-- Active & Missed Tab -->
            <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                @if($activeMissed->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-full flex items-center justify-center text-emerald-500 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-200 mb-1">You're all caught up!</h3>
                        <p class="text-sm text-slate-500">No active or missed reminders.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($activeMissed as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-4 p-5 bg-slate-900 border border-red-500/30 rounded-2xl transition-all duration-300 hover:shadow-xl hover:shadow-red-500/5 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                                <div class="flex items-start gap-4 flex-1 min-w-0 pl-2">
                                    <div class="p-2.5 bg-red-500/10 rounded-xl text-red-400 shrink-0 mt-1">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-semibold text-white mb-1">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-slate-400 mb-3">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-xs font-medium">
                                            <span class="inline-flex items-center gap-1.5 text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }} ({{ $reminder->reminder_time->diffForHumans() }})
                                            </span>
                                            @if($reminder->task)
                                                <span class="inline-flex items-center gap-1.5 text-indigo-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    {{ $reminder->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 justify-end sm:justify-start">
                                    <form method="POST" action="{{ route('reminders.read', $reminder->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 rounded-xl transition-colors">
                                            Mark as Read
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Upcoming Tab -->
            <div x-show="currentTab === 'upcoming'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                @if($upcoming->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-200 mb-1">No upcoming reminders</h3>
                        <p class="text-sm text-slate-500">Create a new reminder to stay organized.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($upcoming as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-4 p-5 bg-slate-900 border border-slate-800 rounded-2xl transition-all duration-300 hover:border-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/5">
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-400 shrink-0 mt-1">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-medium text-slate-200 mb-1">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-slate-400 mb-3">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-xs font-medium">
                                            <span class="inline-flex items-center gap-1.5 text-slate-400">
                                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                            @if($reminder->task)
                                                <span class="inline-flex items-center gap-1.5 text-indigo-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    {{ $reminder->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 justify-end sm:justify-start opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Completed Tab -->
            <div x-show="currentTab === 'completed'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                @if($completed->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-200 mb-1">No completed reminders</h3>
                        <p class="text-sm text-slate-500">Your read notifications will appear here.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($completed as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-4 p-5 bg-slate-900 border border-slate-800/50 rounded-2xl transition-all duration-300 opacity-60 hover:opacity-100">
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-500 shrink-0 mt-1">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-medium text-slate-400 mb-1 line-through">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-slate-500 mb-3 line-through">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-xs font-medium">
                                            <span class="inline-flex items-center gap-1.5 text-slate-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 justify-end sm:justify-start opacity-0 group-hover:opacity-100 transition-opacity">
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Create Reminder Slide Panel -->
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="addModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="addModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-slate-900 shadow-2xl border-l border-slate-800">
                            <div class="px-6 py-6 border-b border-slate-800 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-white">Create New Reminder</h2>
                                <button @click="addModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                <form id="addReminderForm" method="POST" action="{{ route('reminders.store') }}" class="space-y-5">
                                    @csrf
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-slate-300 mb-1.5">Reminder Title <span class="text-red-400">*</span></label>
                                        <input type="text" name="title" id="title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="e.g., Review weekly analytics">
                                    </div>
                                    <div>
                                        <label for="message" class="block text-sm font-medium text-slate-300 mb-1.5">Message (Optional)</label>
                                        <textarea name="message" id="message" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="Add any details..."></textarea>
                                    </div>
                                    <div>
                                        <label for="reminder_time" class="block text-sm font-medium text-slate-300 mb-1.5">Date & Time <span class="text-red-400">*</span></label>
                                        <input type="datetime-local" name="reminder_time" id="reminder_time" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all [color-scheme:dark]">
                                    </div>
                                    <div>
                                        <label for="task_id" class="block text-sm font-medium text-slate-300 mb-1.5">Link to Task (Optional)</label>
                                        <div class="relative">
                                            <select name="task_id" id="task_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all appearance-none cursor-pointer">
                                                <option value="">No linked task</option>
                                                @foreach($tasks as $task)
                                                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-end gap-3 bg-slate-900/50">
                                <button @click="addModalOpen = false" type="button" class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                    Cancel
                                </button>
                                <button form="addReminderForm" type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all">
                                    Create Reminder
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
