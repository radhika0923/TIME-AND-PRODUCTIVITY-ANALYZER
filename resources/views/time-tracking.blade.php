<x-layouts.app title="Time Tracking - Time & Productivity Analyzer">
    <x-slot:styles>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap');
            .font-mono { font-family: 'Roboto Mono', monospace; }
        </style>
    </x-slot:styles>

    <h1 class="text-3xl font-semibold tracking-tight text-white mb-2">Time Tracking</h1>

    @if(session('time_log_status'))
        <div class="mb-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">
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
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-3xl p-8 lg:p-12 shadow-2xl relative overflow-hidden">
            
            <!-- Decorative inner glow based on state -->
            <div class="absolute inset-0 transition-opacity duration-1000 pointer-events-none" 
                 :class="isRunning ? (mode === 'pomodoro' ? 'bg-rose-500/5 opacity-100' : 'bg-indigo-500/5 opacity-100') : 'opacity-0'"></div>

            <div class="relative z-10 flex flex-col items-center justify-center h-full min-h-[320px]">
                
                <!-- Mode Selector (Only show when stopped) -->
                <div x-show="!isRunning" x-transition class="flex bg-slate-950 p-1 rounded-xl mb-8 border border-slate-800">
                    <button @click="mode = 'focus'" :class="mode === 'focus' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'" class="px-4 py-2 text-xs font-semibold rounded-lg transition-all">Focus Timer</button>
                    <button @click="mode = 'pomodoro'" :class="mode === 'pomodoro' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'" class="px-4 py-2 text-xs font-semibold rounded-lg transition-all">Pomodoro</button>
                </div>

                <!-- Task Selection (Only show when stopped) -->
                <div x-show="!isRunning" x-transition class="w-full max-w-md mx-auto mb-8">
                    <label class="block text-sm font-medium text-slate-400 mb-2 text-center">What are you working on?</label>
                    <div class="relative">
                        <select x-model="selectedTask" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all appearance-none cursor-pointer">
                            <option value="">No specific task</option>
                            @foreach($tasks as $task)
                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Pomodoro Duration Presets -->
                    <div x-show="mode === 'pomodoro'" x-transition class="mt-4 flex flex-wrap justify-center gap-2">
                        <button type="button" @click="pomoDuration = 25 * 60" :class="pomoDuration === 25*60 ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700 text-slate-400'" class="text-xs px-3 py-1.5 rounded-lg border transition-all">25m</button>
                        <button type="button" @click="pomoDuration = 50 * 60" :class="pomoDuration === 50*60 ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700 text-slate-400'" class="text-xs px-3 py-1.5 rounded-lg border transition-all">50m</button>
                        <button type="button" @click="pomoDuration = 15 * 60" :class="pomoDuration === 15*60 ? 'border-indigo-500 text-indigo-400 bg-indigo-500/10' : 'border-slate-700 text-slate-400'" class="text-xs px-3 py-1.5 rounded-lg border transition-all">15m (Short)</button>
                    </div>

                    @if($recentTasksForChips->isNotEmpty())
                        <p class="text-xs text-slate-500 text-center mt-3 mb-1" x-show="mode === 'focus'">Recent tasks</p>
                        <div class="flex flex-wrap justify-center gap-2" x-show="mode === 'focus'">
                            @foreach($recentTasksForChips as $rt)
                                <button type="button" @click="selectedTask = '{{ $rt->id }}'"
                                        class="text-xs px-3 py-1.5 rounded-full border border-slate-700 bg-slate-900 text-slate-300 hover:border-indigo-500/50 hover:text-indigo-300 transition-colors max-w-[200px] truncate"
                                        title="{{ $rt->title }}">{{ \Illuminate\Support\Str::limit($rt->title, 28) }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Current Task Label (Only show when running) -->
                <div x-show="isRunning" x-cloak x-transition class="mb-8 flex flex-col items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium" :class="mode === 'pomodoro' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20'">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="mode === 'pomodoro' ? 'bg-rose-400' : 'bg-indigo-400'"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2" :class="mode === 'pomodoro' ? 'bg-rose-500' : 'bg-indigo-500'"></span>
                        </span>
                        <span x-text="mode === 'pomodoro' ? 'Pomodoro Session' : 'Tracking Active'"></span>
                    </span>
                    <h3 class="text-xl font-medium text-slate-200" x-text="activeTaskName || 'Uncategorized Work'"></h3>
                </div>

                <!-- Digital Display -->
                <div class="font-mono text-7xl sm:text-8xl md:text-9xl font-light tracking-tight text-white mb-4 text-shadow-glow flex items-center justify-center tabular-nums" 
                     :style="mode === 'pomodoro' ? 'text-shadow: 0 0 40px rgba(244,63,94,0.2);' : 'text-shadow: 0 0 40px rgba(99,102,241,0.2);'">
                    <span role="timer" aria-live="polite" aria-atomic="true" x-text="formattedDisplayTime"></span>
                </div>
                
                <p class="text-xs text-slate-500 text-center max-w-md mb-6">
                    Only sessions of <strong class="text-slate-400">60 seconds or longer</strong> are saved. Press <kbd class="px-1.5 py-0.5 rounded bg-slate-800 border border-slate-700 text-slate-400 font-mono text-[10px]">Space</kbd> to start/stop.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <button type="button" x-show="!isRunning" @click="startTimer()"
                            :disabled="busyStart"
                            class="group relative inline-flex items-center justify-center px-8 py-4 font-semibold text-white transition-all duration-200 bg-indigo-500 rounded-full hover:bg-indigo-400 shadow-lg hover:shadow-indigo-500/30 transform hover:-translate-y-1 w-48 disabled:opacity-60 disabled:cursor-not-allowed">
                        <svg class="w-6 h-6 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!busyStart"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="inline-block w-5 h-5 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin shrink-0" x-show="busyStart" x-cloak></span>
                        <span x-text="busyStart ? 'Starting…' : 'Start Timer'"></span>
                    </button>

                    <button type="button" x-show="isRunning" x-cloak @click="stopTimer()"
                            :disabled="busyStop"
                            class="group relative inline-flex items-center justify-center px-8 py-4 font-semibold text-white transition-all duration-200 bg-rose-500 rounded-full hover:bg-rose-400 shadow-lg hover:shadow-rose-500/30 transform hover:-translate-y-1 w-48 disabled:opacity-60">
                        <svg class="w-6 h-6 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!busyStop"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                        <span class="inline-block w-5 h-5 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin shrink-0" x-show="busyStop" x-cloak></span>
                        <span x-text="busyStop ? 'Saving…' : 'Stop & Save'"></span>
                    </button>
                </div>
                
                <div x-show="message" x-cloak x-transition class="absolute bottom-6 text-sm font-medium" :class="isError ? 'text-rose-400' : 'text-emerald-400'" x-text="message"></div>
            </div>
        </div>

        <!-- Daily Summary (Right Side) -->
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 lg:p-8 flex flex-col gap-6 shadow-sm">
            <h2 class="text-lg font-semibold text-white border-b border-slate-800 pb-4">Today's Summary</h2>
            
            <div class="bg-indigo-500/5 border border-indigo-500/20 rounded-2xl p-6 flex flex-col items-center justify-center text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-indigo-500/20 blur-2xl rounded-full"></div>
                <span class="text-slate-400 text-sm font-medium mb-2">Total Focus Time</span>
                <div class="text-4xl font-bold text-white mb-1 font-mono tabular-nums">
                    <span x-text="formattedTodayTotal"></span>
                </div>
                <p x-show="isRunning" x-cloak class="text-[11px] text-indigo-400/90 mt-1">Includes this session (estimate)</p>
            </div>

            <div class="bg-slate-800/50 border border-slate-700/50 rounded-2xl p-6 flex items-center justify-between">
                <div>
                    <span class="block text-slate-400 text-sm font-medium mb-1">Sessions Today</span>
                    <span class="text-2xl font-bold text-white">{{ $sessionsToday }}</span>
                </div>
                <div class="w-12 h-12 rounded-full bg-slate-800 flex items-center justify-center text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <!-- Ambient Focus Sounds -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col gap-4 shadow-sm" x-data="{ 
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
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider text-slate-500">Ambient Sounds</h3>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="sound in sounds" :key="sound.id">
                        <button @click="toggleSound(sound.id)" 
                                :class="playing === sound.id ? 'bg-indigo-500/20 border-indigo-500/50 text-indigo-400' : 'bg-slate-800/50 border-slate-700/50 text-slate-400 hover:border-slate-600'" 
                                class="flex flex-col items-center justify-center p-3 rounded-xl border transition-all gap-2 group">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="sound.icon"></path>
                            </svg>
                            <span class="text-[10px] font-medium" x-text="sound.name"></span>
                            <audio :id="'audio-' + sound.id" :src="sound.url"></audio>
                        </button>
                    </template>
                </div>
                <div class="mt-2 flex items-center gap-3">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                    <input type="range" x-model="volume" @input="updateVolume" class="flex-1 h-1 bg-slate-800 rounded-lg appearance-none cursor-pointer accent-indigo-500">
                </div>
            </div>
        </div>
    </div>

    <!-- History Table -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl shadow-sm overflow-hidden mt-8" x-data="editSessionModal()">
        <div class="px-6 py-5 border-b border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="text-lg font-semibold text-white">Recent Sessions</h2>
            <a href="{{ route('time.export', array_filter($filters ?? [])) }}"
               class="inline-flex items-center justify-center text-sm font-medium text-indigo-400 hover:text-indigo-300 border border-indigo-500/30 rounded-xl px-4 py-2 transition-colors">
                Export CSV
            </a>
        </div>

        <form method="get" action="{{ route('time.index') }}" class="px-6 py-4 border-b border-slate-800 flex flex-col lg:flex-row lg:items-end gap-4 flex-wrap bg-slate-900/50">
            <div class="flex-1 min-w-[140px]">
                <label for="filter_from" class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">From Date</label>
                <input type="date" id="filter_from" name="from" value="{{ $filters['from'] ?? '' }}"
                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all [color-scheme:dark]">
            </div>
            <div class="flex-1 min-w-[140px]">
                <label for="filter_to" class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">To Date</label>
                <input type="date" id="filter_to" name="to" value="{{ $filters['to'] ?? '' }}"
                       class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all [color-scheme:dark]">
            </div>
            <div class="flex-1 min-w-[160px]">
                <label for="filter_slot" class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Time Slot</label>
                <select id="filter_slot" name="time_slot" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:border-indigo-500 transition-all">
                    <option value="">Any Time</option>
                    <option value="morning" @selected(($filters['time_slot'] ?? null) == 'morning')>Morning (6am-12pm)</option>
                    <option value="afternoon" @selected(($filters['time_slot'] ?? null) == 'afternoon')>Afternoon (12pm-6pm)</option>
                    <option value="evening" @selected(($filters['time_slot'] ?? null) == 'evening')>Evening (6pm-12am)</option>
                    <option value="night" @selected(($filters['time_slot'] ?? null) == 'night')>Night (12am-6am)</option>
                </select>
            </div>
            <div class="flex-[2] min-w-[200px]">
                <label for="filter_task" class="block text-xs font-medium text-slate-500 mb-1.5 uppercase tracking-wider">Task Filter</label>
                <select id="filter_task" name="task_id" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:border-indigo-500 transition-all">
                    <option value="">All tasks</option>
                    @foreach($filterTasks as $task)
                        <option value="{{ $task->id }}" @selected(($filters['task_id'] ?? null) == $task->id)>{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2 mb-0.5">
                <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/20 transition-all">Apply</button>
                <a href="{{ route('time.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-400 hover:text-white transition-colors">Reset</a>
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
                        <tr class="bg-slate-800/20 text-xs uppercase tracking-wider text-slate-500 border-b border-slate-800">
                            <th class="px-6 py-4 font-medium">Task / Description</th>
                            <th class="px-6 py-4 font-medium">Date</th>
                            <th class="px-6 py-4 font-medium text-right">Duration</th>
                            <th class="px-6 py-4 font-medium text-right w-40">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/60">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full {{ $log->task ? 'bg-indigo-500' : 'bg-slate-600' }}"></div>
                                        <span class="text-sm font-medium text-slate-200">
                                            {{ $log->task ? $log->task->title : 'Uncategorized Work' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-400">
                                    {{ $log->created_at->format('M d, Y • h:i A') }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-medium text-slate-300 font-mono">
                                    {{ \App\Support\Duration::format($log->duration) }}
                                </td>
                                <td class="px-6 py-4 text-right text-sm space-x-2 whitespace-nowrap">
                                    <button type="button"
                                            class="text-indigo-400 hover:text-indigo-300 font-medium"
                                            @click="openEdit(@js(route('time-logs.update', $log)), @js((int) $log->duration), @js($log->task_id))">Edit</button>
                                    <form method="POST" action="{{ route('time-logs.destroy', $log) }}" class="inline" onsubmit="return confirm('Delete this session?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium">Delete</button>
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

        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @keydown.escape.window="close()" role="dialog" aria-modal="true">
            <div class="bg-slate-900 border border-slate-700 rounded-2xl max-w-md w-full p-6 shadow-2xl" @click.away="close()">
                <h3 class="text-lg font-semibold text-white mb-4">Edit session</h3>
                <form method="POST" x-bind:action="updateUrl" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Duration (seconds)</label>
                        <input type="number" name="duration" x-model="duration" min="60" step="1" required
                               class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        <p class="text-[11px] text-slate-500 mt-1">Minimum 60 seconds.</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-500 mb-1">Task</label>
                        <select name="task_id" x-model="taskId" class="w-full bg-slate-950 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-200 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            <option value="">No specific task</option>
                            @foreach($editTasks as $et)
                                <option value="{{ $et->id }}">{{ $et->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="close()" class="px-4 py-2 text-sm text-slate-400 hover:text-white">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-400">Save</button>
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
