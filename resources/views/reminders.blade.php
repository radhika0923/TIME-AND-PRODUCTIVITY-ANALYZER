<x-layouts.app title="Reminders - Time & Productivity Analyzer">
    <div x-data="{ 
        modalOpen: false, 
        modalMode: 'create',
        modalData: { id: '', title: '', message: '', task_id: '', reminder_time: '', new_task_title: '' },
        currentTab: 'active',
        
        openCreate() {
            this.modalMode = 'create';
            this.modalData = { id: '', title: '', message: '', task_id: '', reminder_time: '', new_task_title: '' };
            this.modalOpen = true;
        },
        openEdit(reminder) {
            this.modalMode = 'edit';
            this.modalData = { 
                id: reminder.id, 
                title: reminder.title, 
                message: reminder.message || '', 
                task_id: reminder.task_id || '', 
                reminder_time: reminder.reminder_time_iso,
                new_task_title: '' 
            };
            this.modalOpen = true;
        }
    }">
        <!-- Toast Notifications -->
        <div class="fixed top-8 right-8 z-[200] flex flex-col gap-4">
            @if(session('success'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                     class="flex items-center gap-4 p-5 text-emerald-700 dark:text-emerald-400 bg-white dark:bg-slate-900 border border-emerald-100 dark:border-emerald-900/30 rounded-[1.5rem] shadow-2xl shadow-emerald-900/10 min-w-[320px] max-w-[400px]" x-cloak>
                    <div class="p-2 bg-emerald-500 rounded-xl text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" 
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
                     class="flex items-center gap-4 p-5 text-rose-700 dark:text-rose-400 bg-white dark:bg-slate-900 border border-rose-100 dark:border-rose-900/30 rounded-[1.5rem] shadow-2xl shadow-rose-900/10 min-w-[320px] max-w-[400px]" x-cloak>
                    <div class="p-2 bg-rose-500 rounded-xl text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif
        </div>

        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Reminders</h1>
                <p class="text-gray-500 dark:text-slate-400 font-medium mt-1">Never miss a deadline again.</p>
            </div>
            <button @click="openCreate()" class="px-8 py-4 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-900/10 transition-all transform hover:-translate-y-1 flex items-center gap-3 w-full sm:w-auto justify-center uppercase tracking-widest">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create Reminder
            </button>
        </div>

        <!-- Filters/Tabs -->
        <div class="flex items-center gap-2 border-b border-gray-100 dark:border-slate-800 mt-12 overflow-x-auto no-scrollbar">
            <button @click="currentTab = 'active'" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-3 whitespace-nowrap" :class="currentTab === 'active' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-500' : 'border-transparent text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-200 dark:hover:border-slate-700'">
                Active Reminders
                @if($active->count() > 0)
                    <span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-50 dark:bg-emerald-500/10 text-[10px] font-bold text-emerald-600 dark:text-emerald-500 border border-emerald-100 dark:border-emerald-500/20 shadow-sm">{{ $active->count() }}</span>
                @endif
            </button>
            <button @click="currentTab = 'completed'" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all flex items-center gap-3 whitespace-nowrap" :class="currentTab === 'completed' ? 'border-emerald-600 text-emerald-600 dark:text-emerald-500' : 'border-transparent text-gray-400 hover:text-gray-900 dark:hover:text-white hover:border-gray-200 dark:hover:border-slate-700'">
                Completed
            </button>
        </div>

        <!-- Reminders Content -->
        <div class="mt-8">
            <!-- Active Tab -->
            <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                @if($active->isEmpty())
                    <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900/50 rounded-[3rem] shadow-sm">
                        <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-[2rem] flex items-center justify-center text-emerald-600 dark:text-emerald-500 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">All clear!</h3>
                        <p class="text-gray-500 dark:text-slate-400 font-medium">No active reminders found. Start by creating one.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($active as $reminder)
                            @php
                                $isPastDue = $reminder->reminder_time <= now();
                                $reminderData = [
                                    'id' => $reminder->id,
                                    'title' => $reminder->title,
                                    'message' => $reminder->message,
                                    'task_id' => $reminder->task_id,
                                    'reminder_time_iso' => $reminder->reminder_time->format('Y-m-d\TH:i'),
                                ];
                            @endphp
                            <div class="group flex flex-col lg:flex-row gap-6 p-6 sm:p-8 bg-white dark:bg-slate-900/60 border border-gray-100 dark:border-slate-800 rounded-[2.5rem] transition-all duration-300 hover:shadow-xl relative overflow-hidden {{ $isPastDue ? 'hover:shadow-rose-100/30 dark:hover:shadow-rose-900/20' : 'hover:shadow-emerald-900/5 dark:hover:shadow-emerald-900/10' }}">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $isPastDue ? 'bg-rose-500 animate-pulse' : 'bg-emerald-500' }}"></div>
                                
                                <div class="flex items-start gap-6 flex-1 min-w-0 pl-2">
                                    <div class="p-4 {{ $isPastDue ? 'bg-rose-50 dark:bg-rose-500/10 text-rose-600 dark:text-rose-500' : 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500' }} rounded-2xl shrink-0 shadow-sm transition-transform group-hover:scale-110">
                                        @if($isPastDue)
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight transition-colors {{ $isPastDue ? 'group-hover:text-rose-600 dark:group-hover:text-rose-500' : 'group-hover:text-emerald-600 dark:group-hover:text-emerald-500' }}">
                                            {{ $reminder->title }}
                                        </h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-gray-500 dark:text-slate-400 mb-4 leading-relaxed font-medium line-clamp-2">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-4">
                                            <span class="inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-xl border {{ $isPastDue ? 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20 shadow-sm' : 'text-gray-500 dark:text-slate-400 bg-gray-50 dark:bg-slate-800/50 border-gray-100 dark:border-slate-700' }}">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $isPastDue ? 'Missed:' : 'Due:' }} {{ $reminder->reminder_time->format('M d, Y • h:i A') }}
                                            </span>
                                            @if($reminder->task)
                                                <span class="inline-flex items-center gap-2 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-xl border {{ $isPastDue ? 'text-gray-500 dark:text-slate-400 bg-gray-50 dark:bg-slate-800/50 border-gray-100 dark:border-slate-700' : 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-100 dark:border-emerald-500/20' }}">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                                    Task: {{ $reminder->task->title }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-end pt-4 lg:pt-0 lg:border-l lg:pl-6 border-gray-50 dark:border-slate-800">
                                    <form method="POST" action="{{ route('reminders.read', $reminder->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="px-6 py-3 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 hover:bg-emerald-600 dark:hover:bg-emerald-600 hover:text-white border border-emerald-100 dark:border-emerald-500/20 rounded-2xl transition-all uppercase tracking-widest shadow-sm">
                                            Done
                                        </button>
                                    </form>
                                    <button @click="openEdit({{ json_encode($reminderData) }})" class="p-3 text-gray-400 dark:text-slate-500 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 rounded-2xl transition-all border border-transparent hover:border-emerald-100 dark:hover:border-emerald-500/20 shadow-sm" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-2xl transition-all border border-transparent hover:border-rose-100 dark:hover:border-rose-500/20 shadow-sm" title="Delete">
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
                    <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900/50 rounded-[3rem] shadow-sm">
                        <div class="w-20 h-20 bg-emerald-50 dark:bg-emerald-500/10 rounded-[2rem] flex items-center justify-center text-emerald-600 dark:text-emerald-500 mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-2 tracking-tight">No completed reminders</h3>
                        <p class="text-gray-500 dark:text-slate-400 font-medium">Your read notifications will appear here.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-6">
                        @foreach($completed as $reminder)
                            <div class="group flex flex-col lg:flex-row gap-6 p-6 sm:p-8 bg-gray-50/50 dark:bg-slate-900/40 border border-gray-100 dark:border-slate-800 rounded-[2.5rem] transition-all duration-300 opacity-60 hover:opacity-100">
                                <div class="flex items-start gap-6 flex-1 min-w-0">
                                    <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl text-gray-400 dark:text-slate-500 shrink-0 shadow-sm">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xl font-bold text-gray-400 dark:text-slate-500 mb-2 tracking-tight line-through">{{ $reminder->title }}</h3>
                                        @if($reminder->message)
                                            <p class="text-sm text-gray-400 dark:text-slate-500 mb-4 line-through">{{ $reminder->message }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-widest text-gray-400 dark:text-slate-500">
                                            <span class="inline-flex items-center gap-2">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Finished: {{ $reminder->updated_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 justify-end pt-4 lg:pt-0 lg:border-l lg:pl-6 border-gray-100/50 dark:border-slate-800">
                                    <form method="POST" action="{{ route('reminders.destroy', $reminder->id) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-400 dark:text-slate-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-2xl transition-all border border-transparent hover:border-rose-100 dark:hover:border-rose-500/20 shadow-sm" title="Delete">
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

        <x-reminders.create-modal :tasks="$tasks" />
    </div>
</x-layouts.app>
