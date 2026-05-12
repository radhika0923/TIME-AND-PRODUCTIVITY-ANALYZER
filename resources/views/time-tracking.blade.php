<x-layouts.app title="Time Tracking - Time & Productivity Analyzer">
    <x-slot:styles>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap');
            .font-mono { font-family: 'Roboto Mono', monospace; }
        </style>
    </x-slot:styles>

    <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-6">Time Tracking</h1>

    @if(session('time_log_status'))
        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-700 shadow-sm">
            {{ session('time_log_status') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6"
         x-data="timerApp({
            initialSeconds: {{ (int) $runningDuration }},
            initialRunning: {{ $activeSession ? 'true' : 'false' }},
            initialTaskName: @json($activeSession['task_name'] ?? ''),
            totalTodaySeconds: {{ (int) $totalTimeTodaySeconds }},
            urlStart: @json(route('time.start')),
            urlStop: @json(route('time.stop'))
         })"
         @keydown.window="handleGlobalKey($event)">
        
        <!-- TIMER SECTION (Main Focus) -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-[2.5rem] p-8 lg:p-12 shadow-sm relative overflow-hidden">
            
            <!-- Decorative inner glow based on state -->
            <div class="absolute inset-0 transition-opacity duration-1000 pointer-events-none" 
                 :class="isRunning ? (mode === 'pomodoro' ? 'bg-rose-500/5 opacity-100' : 'bg-emerald-500/5 opacity-100') : 'opacity-0'"></div>

            <div class="relative z-10 flex flex-col items-center justify-center h-full min-h-[320px]">
                
                <!-- Mode Selector (Only show when stopped) -->
                <div x-show="!isRunning" x-transition class="flex bg-gray-50 p-1 rounded-2xl mb-10 border border-gray-100">
                    <button @click="mode = 'focus'" :class="mode === 'focus' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-200' : 'text-gray-400 hover:text-gray-900'" class="px-6 py-2.5 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">Focus Timer</button>
                    <button @click="mode = 'pomodoro'" :class="mode === 'pomodoro' ? 'bg-emerald-600 text-white shadow-xl shadow-emerald-200' : 'text-gray-400 hover:text-gray-900'" class="px-6 py-2.5 text-xs font-bold uppercase tracking-widest rounded-xl transition-all">Pomodoro</button>
                </div>

                <!-- Task Selection (Only show when stopped) -->
                <div x-show="!isRunning" x-transition class="w-full max-w-md mx-auto mb-10">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 text-center">What are you working on?</label>
                    <div class="relative">
                        <select x-model="selectedTask" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                            <option value="">No specific task</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Pomodoro Duration Presets -->
                    <div x-show="mode === 'pomodoro'" x-transition class="mt-4 flex flex-wrap justify-center gap-2">
                        <button type="button" @click="pomoDuration = 25 * 60" :class="pomoDuration === 25*60 ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-gray-200 text-gray-400 bg-white'" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border transition-all">25m</button>
                        <button type="button" @click="pomoDuration = 50 * 60" :class="pomoDuration === 50*60 ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-gray-200 text-gray-400 bg-white'" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border transition-all">50m</button>
                        <button type="button" @click="pomoDuration = 15 * 60" :class="pomoDuration === 15*60 ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-gray-200 text-gray-400 bg-white'" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border transition-all">15m (Short)</button>
                    </div>

                    @if($recentTasksForChips->isNotEmpty())
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center mt-5 mb-2" x-show="mode === 'focus'">Recent tasks</p>
                        <div class="flex flex-wrap justify-center gap-2" x-show="mode === 'focus'">
                            @foreach($recentTasksForChips as $rt)
                                <button type="button" @click="selectedTask = '{{ $rt->id }}'"
                                        class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-full border border-gray-100 bg-gray-50 text-gray-500 hover:border-emerald-500/50 hover:text-emerald-600 transition-all max-w-[200px] truncate shadow-sm"
                                        title="{{ $rt->title }}">{{ \Illuminate\Support\Str::limit($rt->title, 28) }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Current Task Label (Only show when running) -->
                <div x-show="isRunning" x-cloak x-transition class="mb-10 flex flex-col items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-widest" :class="mode === 'pomodoro' ? 'bg-rose-50 text-rose-600 border border-rose-100' : 'bg-emerald-50 text-emerald-600 border border-emerald-100'">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="mode === 'pomodoro' ? 'bg-rose-400' : 'bg-emerald-400'"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2" :class="mode === 'pomodoro' ? 'bg-rose-500' : 'bg-emerald-500'"></span>
                        </span>
                        <span x-text="mode === 'pomodoro' ? 'Pomodoro Active' : 'Focus Mode On'"></span>
                    </span>
                    <h3 class="text-2xl font-bold text-gray-900" x-text="activeTaskName || 'Uncategorized Work'"></h3>
                </div>

                <!-- Digital Display -->
                <div class="font-mono text-7xl sm:text-8xl md:text-9xl font-extrabold tracking-tighter text-gray-900 mb-6 flex items-center justify-center tabular-nums" 
                     :style="mode === 'pomodoro' ? 'text-shadow: 0 0 60px rgba(244,63,94,0.1);' : 'text-shadow: 0 0 60px rgba(16,185,129,0.1);'">
                    <span role="timer" aria-live="polite" aria-atomic="true" x-text="formattedDisplayTime"></span>
                </div>
                
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center max-w-md mb-8">
                    Minimum <strong class="text-gray-600">60s</strong> required. <kbd class="px-2 py-1 rounded-lg bg-gray-100 border border-gray-200 text-gray-500 font-mono">Space</kbd> toggle start/stop.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button type="button" x-show="!isRunning" @click="startTimer()"
                            :disabled="busyStart"
                            class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-300 bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-200 transform hover:-translate-y-1 w-56 disabled:opacity-60 disabled:cursor-not-allowed uppercase tracking-widest text-sm">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!busyStart"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="inline-block w-5 h-5 mr-3 border-2 border-white/30 border-t-white rounded-full animate-spin shrink-0" x-show="busyStart" x-cloak></span>
                        <span x-text="busyStart ? 'Starting…' : 'Start Focus'"></span>
                    </button>

                    <button type="button" x-show="isRunning" x-cloak @click="stopTimer()"
                            :disabled="busyStop"
                            class="group relative inline-flex items-center justify-center px-10 py-4 font-bold text-white transition-all duration-300 bg-rose-600 rounded-2xl hover:bg-rose-700 shadow-xl shadow-rose-200 transform hover:-translate-y-1 w-56 disabled:opacity-60 uppercase tracking-widest text-sm">
                        <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!busyStop"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                        <span class="inline-block w-5 h-5 mr-3 border-2 border-white/30 border-t-white rounded-full animate-spin shrink-0" x-show="busyStop" x-cloak></span>
                        <span x-text="busyStop ? 'Saving…' : 'Stop Session'"></span>
                    </button>
                </div>
                
                <div x-show="message" x-cloak x-transition class="absolute bottom-6 text-sm font-medium" :class="isError ? 'text-rose-400' : 'text-emerald-400'" x-text="message"></div>
            </div>
        </div>

        <!-- Daily Summary (Right Side) -->
        <div class="bg-white border border-gray-100 rounded-[2rem] p-6 lg:p-8 flex flex-col gap-6 shadow-sm">
            <h2 class="text-lg font-bold text-gray-900 border-b border-gray-50 pb-4">Daily Summary</h2>
            
            <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-8 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-emerald-500/10 blur-2xl rounded-full"></div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-3">Total Focus Time</span>
                <div class="text-5xl font-extrabold text-gray-900 mb-1 font-mono tabular-nums tracking-tighter">
                    <span x-text="formattedTodayTotal"></span>
                </div>
                <p x-show="isRunning" x-cloak class="text-[10px] font-bold text-emerald-600/70 mt-2 uppercase tracking-widest">Live session included</p>
            </div>

            <div class="bg-gray-50 border border-gray-100 rounded-[2rem] p-8 flex items-center justify-between">
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Sessions Today</span>
                    <span class="text-4xl font-extrabold text-gray-900 tabular-nums">{{ $sessionsToday }}</span>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 shadow-sm">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Ambient Focus Sounds -->
            <div class="bg-white border border-gray-100 rounded-[2rem] p-8 flex flex-col gap-6 shadow-sm" x-data="{ 
                playing: null,
                volume: 50,
                sounds: [
                    { id: 'rain', name: 'Rain', icon: 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z', url: 'https://www.soundjay.com/nature/rain-01.mp3' },
                    { id: 'forest', name: 'Forest', icon: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', url: 'https://www.soundjay.com/nature/forest-birds-01.mp3' },
                    { id: 'noise', name: 'White Noise', icon: 'M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', url: 'https://www.soundjay.com/misc/sounds/white-noise-01.mp3' }
                ],
                toggleSound(id) {
                    const audio = document.getElementById('audio-' + id);
                    if (this.playing === id) {
                        audio.pause();
                        this.playing = null;
                    } else {
                        if (this.playing) document.getElementById('audio-' + this.playing).pause();
                        audio.play();
                        audio.loop = true;
                        this.playing = id;
                    }
                },
                updateVolume() {
                    this.sounds.forEach(s => {
                        const audio = document.getElementById('audio-' + s.id);
                        if (audio) audio.volume = this.volume / 100;
                    });
                }
            }">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Ambient Sounds</h3>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="sound in sounds" :key="sound.id">
                        <button @click="toggleSound(sound.id)" 
                                :class="playing === sound.id ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-200 border-emerald-600' : 'bg-gray-50 border-gray-100 text-gray-500 hover:border-emerald-500/50 hover:bg-white'" 
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border transition-all gap-3 group">
                            <svg class="w-6 h-6 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sound.icon"></path>
                            </svg>
                            <span class="text-[9px] font-bold uppercase tracking-widest" x-text="sound.name"></span>
                            <audio :id="'audio-' + sound.id" :src="sound.url"></audio>
                        </button>
                    </template>
                </div>
                <div class="mt-2 flex items-center gap-4">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    <input type="range" x-model="volume" @input="updateVolume" class="flex-1 h-1.5 bg-gray-100 rounded-full appearance-none cursor-pointer accent-emerald-600">
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-sm overflow-hidden mt-8" x-data="editSessionModal()">
        <div class="px-8 py-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-xl font-bold text-gray-900">Recent Sessions</h2>
            <a href="{{ route('time.export', array_filter($filters ?? [])) }}"
               class="inline-flex items-center justify-center text-[10px] font-bold uppercase tracking-widest text-emerald-600 hover:text-emerald-700 border border-emerald-100 bg-emerald-50 rounded-xl px-5 py-2.5 transition-all shadow-sm">
                Export CSV
            </a>
        </div>

        <form method="get" action="{{ route('time.index') }}" class="px-8 py-6 border-b border-gray-50 flex flex-col lg:flex-row lg:items-end gap-6 flex-wrap bg-gray-50/50">
            <div class="flex-1 min-w-[140px]">
                <label for="filter_from" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">From Date</label>
                <input type="date" id="filter_from" name="from" value="{{ $filters['from'] ?? '' }}"
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all">
            </div>
            <div class="flex-1 min-w-[140px]">
                <label for="filter_to" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">To Date</label>
                <input type="date" id="filter_to" name="to" value="{{ $filters['to'] ?? '' }}"
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all">
            </div>
            <div class="flex-1 min-w-[160px]">
                <label for="filter_slot" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Time Slot</label>
                <select id="filter_slot" name="time_slot" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                    <option value="">Any Time</option>
                    <option value="morning" @selected(($filters['time_slot'] ?? null) == 'morning')>Morning (6am-12pm)</option>
                    <option value="afternoon" @selected(($filters['time_slot'] ?? null) == 'afternoon')>Afternoon (12pm-6pm)</option>
                    <option value="evening" @selected(($filters['time_slot'] ?? null) == 'evening')>Evening (6pm-12am)</option>
                    <option value="night" @selected(($filters['time_slot'] ?? null) == 'night')>Night (12am-6am)</option>
                </select>
            </div>
            <div class="flex-[2] min-w-[200px]">
                <label for="filter_task" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Task Filter</label>
                <select id="filter_task" name="task_id" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-900 focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                    <option value="">All tasks</option>
                    @foreach($filterTasks as $task)
                        <option value="{{ $task->id }}" @selected(($filters['task_id'] ?? null) == $task->id)>{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-gray-900 rounded-xl hover:bg-black shadow-lg shadow-gray-200 transition-all uppercase tracking-widest">Apply</button>
                <a href="{{ route('time.index') }}" class="px-4 py-3 text-xs font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest">Reset</a>
            </div>
        </form>
        
        @if($logs->total() === 0)
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500 mb-4 mx-auto">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-slate-300 mb-1">No sessions yet</h3>
                <p class="text-sm text-slate-500">Start the timer above to log your first focus session.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-b border-gray-100">
                            <th class="px-8 py-5 font-bold">Task / Description</th>
                            <th class="px-8 py-5 font-bold">Date</th>
                            <th class="px-8 py-5 font-bold text-right">Duration</th>
                            <th class="px-8 py-5 font-bold text-right w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full {{ $log->task ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]' : 'bg-gray-300' }}"></div>
                                        <span class="text-sm font-bold text-gray-900">
                                            {{ $log->task ? $log->task->title : 'Uncategorized Work' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-500 font-medium">
                                    {{ $log->created_at->format('M d, Y • h:i A') }}
                                </td>
                                <td class="px-8 py-5 text-sm text-right font-bold text-gray-900 font-mono">
                                    {{ \App\Support\Duration::format($log->duration) }}
                                </td>
                                <td class="px-8 py-5 text-right text-sm space-x-4 whitespace-nowrap">
                                    <button type="button"
                                            class="text-emerald-600 hover:text-emerald-700 font-bold uppercase tracking-widest text-[10px]"
                                            @click="openEdit(@js(route('time-logs.update', $log)), @js((int) $log->duration), @js($log->task_id))">Edit</button>
                                    <form method="POST" action="{{ route('time-logs.destroy', $log) }}" class="inline" onsubmit="return confirm('Delete this session?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-600 font-bold uppercase tracking-widest text-[10px]">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm text-slate-400">
                    <p>Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}</p>
                    <div class="flex items-center gap-4">
                        @if($logs->onFirstPage())
                            <span class="opacity-40 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $logs->previousPageUrl() }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Previous</a>
                        @endif
                        @if($logs->hasMorePages())
                            <a href="{{ $logs->nextPageUrl() }}" class="text-indigo-400 hover:text-indigo-300 font-medium">Next</a>
                        @else
                            <span class="opacity-40 cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif

        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm" @keydown.escape.window="close()" role="dialog" aria-modal="true">
            <div class="bg-white border border-gray-100 rounded-[2rem] max-w-md w-full p-8 shadow-2xl" @click.away="close()">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Session</h3>
                <form method="POST" x-bind:action="updateUrl" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Duration (seconds)</label>
                        <input type="number" name="duration" x-model="duration" min="60" step="1" required
                               class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all">
                        <p class="text-[10px] text-gray-400 mt-2 font-medium">Minimum 60 seconds required.</p>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Task</label>
                        <select name="task_id" x-model="taskId" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                            <option value="">No specific task</option>
                            @foreach($editTasks as $et)
                                <option value="{{ $et->id }}">{{ $et->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="close()" class="px-6 py-3 text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest transition-all">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('editSessionModal', () => ({
                    open: false,
                    updateUrl: '',
                    duration: 60,
                    taskId: '',
                    openEdit(url, duration, taskId) {
                        this.updateUrl = url;
                        this.duration = duration;
                        this.taskId = taskId === null || taskId === undefined ? '' : String(taskId);
                        this.open = true;
                    },
                    close() {
                        this.open = false;
                    },
                }));

                Alpine.data('timerApp', (config) => ({
                    seconds: Number(config.initialSeconds) || 0,
                    isRunning: Boolean(config.initialRunning),
                    activeTaskName: config.initialTaskName || '',
                    totalTodaySeconds: Number(config.totalTodaySeconds) || 0,
                    urlStart: config.urlStart,
                    urlStop: config.urlStop,
                    selectedTask: '',
                    timerInterval: null,
                    message: '',
                    isError: false,
                    busyStart: false,
                    busyStop: false,
                    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                    // Pomodoro Extensions
                    mode: 'focus', // 'focus' or 'pomodoro'
                    pomoDuration: 25 * 60, // Default 25 mins

                    get formattedDisplayTime() {
                        let s = this.seconds;
                        if (this.mode === 'pomodoro') {
                            s = Math.max(0, this.pomoDuration - this.seconds);
                        }
                        const hrs = Math.floor(s / 3600);
                        const mins = Math.floor((s % 3600) / 60);
                        const secs = s % 60;
                        if (hrs > 0) {
                            return `${hrs.toString().padStart(2, '0')}:${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }
                        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    },

                    get formattedTodayTotal() {
                        const extra = this.isRunning ? this.seconds : 0;
                        const s = this.totalTodaySeconds + extra;
                        const h = Math.floor(s / 3600);
                        const m = Math.floor((s % 3600) / 60);
                        const rem = s % 60;
                        if (h > 0) return `${h}h ${m}m`;
                        if (m > 0) return rem > 0 ? `${m}m ${rem}s` : `${m}m`;
                        return `${rem}s`;
                    },

                    init() {
                        if (this.isRunning) {
                            this.startTick();
                        }
                    },

                    handleGlobalKey(e) {
                        if (e.code !== 'Space') return;
                        const t = e.target;
                        if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable)) return;
                        e.preventDefault();
                        if (this.busyStart || this.busyStop) return;
                        this.isRunning ? this.stopTimer() : this.startTimer();
                    },

                    startTick() {
                        if (this.timerInterval) clearInterval(this.timerInterval);
                        this.timerInterval = setInterval(() => {
                            this.seconds++;
                            if (this.mode === 'pomodoro' && this.seconds >= this.pomoDuration) {
                                this.onPomodoroComplete();
                            }
                        }, 1000);
                    },

                    onPomodoroComplete() {
                        this.stopTick();
                        // Optional: play sound
                        if (Notification.permission === 'granted') {
                            new Notification('Pomodoro Finished!', { body: 'Time for a break!' });
                        } else if (Notification.permission !== 'denied') {
                            Notification.requestPermission();
                        }
                        alert('Pomodoro Finished! Take a break.');
                        this.stopTimer();
                    },

                    stopTick() {
                        if (this.timerInterval) {
                            clearInterval(this.timerInterval);
                            this.timerInterval = null;
                        }
                    },

                    async startTimer() {
                        if (this.isRunning || this.busyStart) return;
                        this.busyStart = true;
                        try {
                            const response = await fetch(this.urlStart, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ task_id: this.selectedTask || null })
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.isRunning = true;
                                this.seconds = 0;
                                this.activeTaskName = data?.data?.task_name || 'Uncategorized';
                                this.startTick();
                                this.showMessage(this.mode === 'pomodoro' ? 'Pomodoro started.' : 'Timer started.', false);
                            } else {
                                this.showMessage(data?.message || 'Failed to start.', true);
                            }
                        } catch {
                            this.showMessage('Connection error.', true);
                        } finally {
                            this.busyStart = false;
                        }
                    },

                    async stopTimer() {
                        if (!this.isRunning || this.busyStop) return;
                        if (this.mode === 'focus' && !window.confirm('Stop and save this session?')) return;
                        this.busyStop = true;
                        try {
                            const response = await fetch(this.urlStop, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': this.csrfToken,
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await response.json();
                            if (response.ok) {
                                this.isRunning = false;
                                this.stopTick();
                                this.seconds = 0;
                                if (data?.logged) window.location.reload();
                                else this.showMessage(data?.message || 'Session too short.', false);
                            } else {
                                this.showMessage(data?.message || 'Failed to stop.', true);
                            }
                        } catch {
                            this.showMessage('Connection error.', true);
                        } finally {
                            this.busyStop = false;
                        }
                    },

                    showMessage(msg, isErr) {
                        this.message = msg;
                        this.isError = isErr;
                        setTimeout(() => this.message = '', 4000);
                    }
                }));
            });
        </script>
    </x-slot:scripts>
</x-layouts.app>
