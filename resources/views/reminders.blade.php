<x-layouts.app title="Reminders - Time & Productivity Analyzer">
    <div x-data="{ addModalOpen: false, currentTab: 'active' }">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-6 text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-[1.5rem] shadow-sm" role="alert">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-emerald-500 rounded-lg text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Reminders</h1>
            <button @click="addModalOpen = true" class="px-8 py-3.5 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 flex items-center gap-3 w-full sm:w-auto justify-center uppercase tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Reminder
            </button>
        </div>

        <!-- Filters/Tabs -->
        <div class="flex items-center gap-2 border-b border-gray-100 mt-10">
            <button @click="currentTab = 'active'" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-2" :class="currentTab === 'active' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200'">
                Active & Missed
                @if($activeMissed->count() > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-rose-50 text-[10px] font-bold text-rose-600 border border-rose-100 shadow-sm">{{ $activeMissed->count() }}</span>
                @endif
            </button>
            <button @click="currentTab = 'upcoming'" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-2" :class="currentTab === 'upcoming' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200'">
                Upcoming
                @if($upcoming->count() > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-gray-100 text-[10px] font-bold text-gray-500 border border-gray-200 shadow-sm">{{ $upcoming->count() }}</span>
                @endif
            </button>
            <button @click="currentTab = 'completed'" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-2" :class="currentTab === 'completed' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200'">
                Completed
            </button>
        </div>

        <!-- Reminders Content -->
        <div class="mt-6">
            <!-- Active & Missed Tab -->
            <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                @if($activeMissed->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 bg-white rounded-[2.5rem] shadow-sm">
                        <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">You're all caught up!</h3>
                        <p class="text-gray-500 font-medium">No active or missed reminders found.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($activeMissed as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-6 p-8 bg-white border border-gray-100 rounded-[2.5rem] transition-all duration-300 hover:shadow-xl hover:shadow-rose-100 relative overflow-hidden">
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-rose-500"></div>
                                <div class="flex items-start gap-6 flex-1 min-w-0 pl-2">
                                    <div class="p-4 bg-rose-50 rounded-2xl text-rose-600 shrink-0 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-gray-500 mb-4 leading-relaxed font-medium">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-6 text-[10px] font-bold uppercase tracking-widest">
                                            <span class="inline-flex items-center gap-2 text-rose-600 bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                            @if($reminder->task)
                                                <span class="inline-flex items-center gap-2 text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    Task: {{ $reminder->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-end sm:justify-start">
                                    <form method="POST" action="{{ route('reminders.read', $reminder->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-6 py-3 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 rounded-2xl transition-all uppercase tracking-widest shadow-sm">
                                            Done
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100 shadow-sm" title="Delete">
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
                    <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 bg-white rounded-[2.5rem] shadow-sm">
                        <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-400 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">No upcoming reminders</h3>
                        <p class="text-gray-500 font-medium">Create a new reminder to stay organized.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($upcoming as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-6 p-8 bg-white border border-gray-100 rounded-[2.5rem] transition-all duration-300 hover:shadow-xl hover:shadow-emerald-50">
                                <div class="flex items-start gap-6 flex-1 min-w-0">
                                    <div class="p-4 bg-emerald-50 rounded-2xl text-emerald-600 shrink-0 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-gray-500 mb-4 leading-relaxed font-medium">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-6 text-[10px] font-bold uppercase tracking-widest">
                                            <span class="inline-flex items-center gap-2 text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                            @if($reminder->task)
                                                <span class="inline-flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    Task: {{ $reminder->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-end sm:justify-start">
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100 shadow-sm" title="Delete">
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
                    <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 bg-white rounded-[2.5rem] shadow-sm">
                        <div class="w-20 h-20 bg-emerald-50 rounded-[2rem] flex items-center justify-center text-emerald-600 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 mb-2 tracking-tight">No completed reminders</h3>
                        <p class="text-gray-500 font-medium">Your read notifications will appear here.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($completed as $reminder)
                            <div class="group flex flex-col sm:flex-row gap-6 p-8 bg-gray-50/50 border border-gray-100 rounded-[2.5rem] transition-all duration-300 opacity-60 hover:opacity-100">
                                <div class="flex items-start gap-6 flex-1 min-w-0">
                                    <div class="p-4 bg-gray-100 rounded-2xl text-gray-400 shrink-0">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-bold text-gray-400 mb-2 tracking-tight line-through">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-gray-400 mb-4 line-through">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Due: {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-end sm:justify-start">
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100 shadow-sm" title="Delete">
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
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-[100] overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="addModalOpen" x-transition:enter="ease-in-out duration-400" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-400" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="addModalOpen" x-transition:enter="transform transition ease-in-out duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white shadow-2xl border-l border-gray-100">
                            <div class="px-8 py-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">New Reminder</h2>
                                <button @click="addModalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            <div class="flex-1 overflow-y-auto px-8 py-8">
                                <form id="addReminderForm" method="POST" action="{{ route('reminders.store') }}" class="space-y-8">
                                    @csrf
                                    <div>
                                        <label for="title" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Reminder Title <span class="text-rose-500">*</span></label>
                                        <input type="text" name="title" id="title" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder-gray-300" placeholder="e.g., Team Sync Meeting">
                                    </div>
                                    <div>
                                        <label for="message" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Message (Optional)</label>
                                        <textarea name="message" id="message" rows="4" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder-gray-300 resize-none" placeholder="Add some context..."></textarea>
                                    </div>
                                    <div>
                                        <label for="reminder_time" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Date & Time <span class="text-rose-500">*</span></label>
                                        <input type="datetime-local" name="reminder_time" id="reminder_time" required class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all">
                                    </div>
                                    <div>
                                        <label for="task_id" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Link to Task (Optional)</label>
                                        <div class="relative">
                                            <select name="task_id" id="task_id" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                                                <option value="">No linked task</option>
                                                @foreach($tasks as $task)
                                                    <option value="{{ $task->id }}">{{ $task->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center px-5 pointer-events-none text-gray-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="px-8 py-6 border-t border-gray-50 flex items-center justify-end gap-3 bg-gray-50/30">
                                <button @click="addModalOpen = false" type="button" class="px-6 py-4 text-xs font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">
                                    Cancel
                                </button>
                                <button form="addReminderForm" type="submit" class="px-8 py-4 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest">
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
