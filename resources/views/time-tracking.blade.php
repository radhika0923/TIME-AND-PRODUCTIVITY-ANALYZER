<x-layouts.app title="Time Tracking - Time & Productivity Analyzer">
    <x-slot:styles>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;700&display=swap');
            .font-mono { font-family: 'Roboto Mono', monospace; }
            
            .timer-ring {
                transition: stroke-dashoffset 0.5s ease;
                transform: rotate(-90deg);
                transform-origin: 50% 50%;
            }
            
            @keyframes pulse-emerald {
                0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
                70% { box-shadow: 0 0 0 15px rgba(16, 185, 129, 0); }
                100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
            }
            .pulse-active {
                animation: pulse-emerald 2s infinite;
            }
        </style>
    </x-slot:styles>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Time Tracking</h1>
            <p class="text-gray-500 mt-1">Monitor your productivity sessions in real-time.</p>
        </div>
        
        @if(session('time_log_status'))
            <div class="rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-3 text-sm font-bold text-emerald-700 shadow-sm animate-bounce">
                {{ session('time_log_status') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
         x-data="timerApp(window.timerConfig)"
         @keydown.window="handleGlobalKey($event)">
        
        <!-- MAIN TIMER SECTION -->
        <div class="lg:col-span-2 bg-white border border-gray-100 rounded-[3rem] p-8 lg:p-12 shadow-xl shadow-gray-200/50 relative overflow-hidden flex flex-col items-center justify-center min-h-[500px]">
            
            <!-- Decorative Background Glow -->
            <div class="absolute inset-0 transition-opacity duration-1000 pointer-events-none" 
                 :class="isRunning && !isPaused ? 'bg-emerald-500/[0.03] opacity-100' : 'opacity-0'"></div>

            <!-- Header Controls -->
            <div x-show="!isRunning" x-transition class="absolute top-8 flex bg-gray-50 p-1.5 rounded-2xl border border-gray-100 z-20">
                <button @click="setMode('focus')" :class="mode === 'focus' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 text-[10px] font-bold uppercase tracking-[0.2em] rounded-xl transition-all border border-transparent">Focus Mode</button>
                <button @click="setMode('pomodoro')" :class="mode === 'pomodoro' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 text-[10px] font-bold uppercase tracking-[0.2em] rounded-xl transition-all border border-transparent">Pomodoro</button>
            </div>

            <!-- Task Label -->
            <div class="relative z-10 text-center mb-8" x-show="isRunning" x-transition>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 mb-4">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75" x-show="!isPaused"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] font-bold uppercase tracking-widest" x-text="isPaused ? 'Session Paused' : (mode === 'pomodoro' ? 'Pomodoro Active' : 'Focus Active')"></span>
                </div>
                <h3 class="text-2xl font-bold text-gray-900" x-text="activeTaskName || 'Uncategorized Session'">{{ $activeSession['task_name'] ?? 'Ready to Focus' }}</h3>
            </div>

            <!-- WATCH FACE -->
            <div class="relative flex items-center justify-center mb-10 group">
                <!-- Progress Ring -->
                <svg class="w-64 h-64 sm:w-80 sm:h-80" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="45" fill="none" stroke="#F3F4F6" stroke-width="2" />
                    <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2.5" 
                            class="timer-ring text-emerald-500"
                            stroke-linecap="round"
                            :stroke-dasharray="2 * Math.PI * 45"
                            :stroke-dashoffset="progressOffset" />
                </svg>

                <!-- Digital Time -->
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-mono text-6xl sm:text-7xl font-bold tracking-tighter text-gray-900 tabular-nums" x-text="formattedDisplayTime">00:00</span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.3em] mt-2" x-text="mode === 'pomodoro' ? 'Remaining' : 'Elapsed'">Elapsed</span>
                </div>
            </div>

            <!-- CONFIGURATION (When Stopped) -->
            <div x-show="!isRunning" x-transition class="w-full max-w-sm mx-auto mb-10 space-y-6 z-20">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 text-center">What's the goal?</label>
                    <select x-model="selectedTask" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer">
                        <option value="">General Productivity</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between px-2">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Duration</span>
                        <div class="flex items-center gap-2">
                            <input type="number" x-model="configHrs" min="0" max="23" class="w-12 bg-transparent text-center font-bold text-gray-900 focus:outline-none">
                            <span class="text-gray-300">h</span>
                            <input type="number" x-model="configMins" min="0" max="59" class="w-12 bg-transparent text-center font-bold text-gray-900 focus:outline-none">
                            <span class="text-gray-300">m</span>
                        </div>
                    </div>
                    <div class="flex flex-wrap justify-center gap-2">
                        <button type="button" @click="setDuration(25)" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border border-gray-100 bg-white text-gray-500 hover:border-emerald-500/50 hover:text-emerald-600 transition-all">25m</button>
                        <button type="button" @click="setDuration(50)" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border border-gray-100 bg-white text-gray-500 hover:border-emerald-500/50 hover:text-emerald-600 transition-all">50m</button>
                        <button type="button" @click="setDuration(80)" class="text-[10px] font-bold uppercase tracking-widest px-4 py-2 rounded-xl border border-gray-100 bg-white text-gray-500 hover:border-emerald-500/50 hover:text-emerald-600 transition-all">1h 20m</button>
                    </div>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div class="flex items-center gap-6 z-20">
                <!-- START BUTTON -->
                <button type="button" x-show="!isRunning" @click="startTimer()"
                        :disabled="busyStart"
                        class="pulse-active w-20 h-20 flex items-center justify-center bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition-all transform hover:scale-105 shadow-xl shadow-emerald-200 disabled:opacity-50">
                    <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>

                <!-- PAUSE / RESUME BUTTON -->
                <button type="button" x-show="isRunning" @click="togglePause()"
                        class="w-16 h-16 flex items-center justify-center bg-gray-100 text-gray-600 rounded-full hover:bg-gray-200 transition-all shadow-md">
                    <svg x-show="!isPaused" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                    <svg x-show="isPaused" class="w-6 h-6 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                </button>

                <!-- STOP BUTTON -->
                <button type="button" x-show="isRunning" @click="stopTimer()"
                        :disabled="busyStop"
                        class="w-20 h-20 flex items-center justify-center bg-rose-600 text-white rounded-full hover:bg-rose-700 transition-all transform hover:scale-105 shadow-xl shadow-rose-200 disabled:opacity-50">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"><path d="M6 6h12v12H6z"/></svg>
                </button>
            </div>

            <div x-show="message" x-cloak x-transition class="absolute bottom-8 text-sm font-bold text-center" :class="isError ? 'text-rose-500' : 'text-emerald-500'" x-text="message"></div>
        </div>

        <!-- SIDEBAR SUMMARY -->
        <div class="space-y-8">
            <!-- DAILY STATS -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-lg shadow-gray-200/40">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Today's Summary</h2>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between p-6 bg-emerald-50 border border-emerald-100 rounded-3xl">
                        <div>
                            <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-widest mb-1">Focus Time</span>
                            <span class="text-3xl font-extrabold text-gray-900 font-mono" x-text="formattedTodayTotal"></span>
                        </div>
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-emerald-500 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-6 bg-gray-50 border border-gray-100 rounded-3xl">
                        <div>
                            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Sessions</span>
                            <span class="text-3xl font-extrabold text-gray-900 tabular-nums">{{ $sessionsToday }}</span>
                        </div>
                        <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-gray-400 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AMBIENT SOUNDS -->
            <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-lg shadow-gray-200/40" x-data="{ 
                playing: null,
                volume: 50,
                sounds: [
                    { id: 'rain', name: 'Rain', icon: 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z', url: 'https://www.soundjay.com/nature/rain-01.mp3' },
                    { id: 'forest', name: 'Forest', icon: 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', url: 'https://www.soundjay.com/nature/forest-birds-01.mp3' },
                    { id: 'white', name: 'Focus', icon: 'M13 10V3L4 14h7v7l9-11h-7z', url: 'https://www.soundjay.com/misc/sounds/white-noise-01.mp3' }
                ],
                toggle(id) {
                    if (this.playing === id) {
                        document.getElementById('a-'+id).pause();
                        this.playing = null;
                    } else {
                        if (this.playing) document.getElementById('a-'+this.playing).pause();
                        const a = document.getElementById('a-'+id);
                        a.loop = true;
                        a.volume = this.volume / 100;
                        a.play();
                        this.playing = id;
                    }
                },
                setVol() {
                    if (this.playing) document.getElementById('a-'+this.playing).volume = this.volume/100;
                }
            }">
                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Focus Ambience</h3>
                <div class="grid grid-cols-3 gap-3">
                    <template x-for="s in sounds" :key="s.id">
                        <button @click="toggle(s.id)" 
                                :class="playing === s.id ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-100 border-emerald-600' : 'bg-gray-50 border-gray-100 text-gray-400 hover:bg-white'" 
                                class="flex flex-col items-center justify-center p-4 rounded-2xl border transition-all gap-2 group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="s.icon"></path></svg>
                            <span class="text-[8px] font-bold uppercase" x-text="s.name"></span>
                            <audio :id="'a-'+s.id" :src="s.url"></audio>
                        </button>
                    </template>
                </div>
                <input type="range" x-model="volume" @input="setVol" class="w-full h-1 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-emerald-600 mt-6">
            </div>
        </div>
    </div>

    <!-- LOGS SECTION -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden mt-12" x-data="editSessionModal()">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-900">Recent Sessions</h2>
            <a href="{{ route('time.export', array_filter($filters ?? [])) }}" class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100">Export CSV</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50/50 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        <th class="px-8 py-4">Task</th>
                        <th class="px-8 py-4">Date & Time</th>
                        <th class="px-8 py-4 text-right">Duration</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/30 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $log->task ? 'bg-emerald-500' : 'bg-gray-300' }}"></div>
                                    <span class="text-sm font-bold text-gray-900">{{ $log->task ? $log->task->title : 'Uncategorized' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-xs text-gray-500 font-medium">
                                {{ $log->created_at->format('M d, Y • h:i A') }}
                            </td>
                            <td class="px-8 py-5 text-sm text-right font-bold text-gray-900 font-mono">
                                {{ \App\Support\Duration::format($log->duration) }}
                            </td>
                            <td class="px-8 py-5 text-right space-x-3">
                                <button @click="openEdit(@js(route('time-logs.update', $log)), @js((int) $log->duration), @js($log->task_id))" class="text-emerald-600 font-bold uppercase text-[10px]">Edit</button>
                                <form method="POST" action="{{ route('time-logs.destroy', $log) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-rose-500 font-bold uppercase text-[10px]" onclick="return confirm('Delete this session?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center text-gray-400 text-sm">No recorded sessions for this period.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-8 py-4 border-t border-gray-50">
                {{ $logs->links() }}
            </div>
        @endif

        <!-- Edit Modal (Simplified for brevity) -->
        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/20 backdrop-blur-sm">
            <div class="bg-white border border-gray-100 rounded-[2rem] max-w-md w-full p-8 shadow-2xl" @click.away="close()">
                <h3 class="text-xl font-bold text-gray-900 mb-6">Edit Session</h3>
                <form method="POST" :action="updateUrl" class="space-y-6">
                    @csrf @method('PATCH')
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase">Duration (Seconds)</label>
                        <input type="number" name="duration" x-model="duration" min="60" class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 font-bold text-gray-900">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="close()" class="px-6 py-3 text-xs font-bold text-gray-400 uppercase">Cancel</button>
                        <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-100 uppercase">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            // Define configuration globally from server-side variables
            window.timerConfig = {
                initialSeconds: {{ (int) $runningDuration }},
                initialRunning: {{ $activeSession ? 'true' : 'false' }},
                initialTaskName: @json($activeSession['task_name'] ?? ''),
                totalTodaySeconds: {{ (int) $totalTimeTodaySeconds }},
                urlStart: @json(route('time.start')),
                urlStop: @json(route('time.stop'))
            };

            // Define globally to ensure Alpine can find it regardless of event timing
            window.timerApp = function(config) {
                // Ensure config is at least an empty object
                config = config || {};
                return {
                    seconds: Number(config.initialSeconds) || 0,
                    isRunning: Boolean(config.initialRunning),
                    isPaused: false,
                    mode: 'focus',
                    configHrs: 0,
                    configMins: 25,
                    targetSeconds: 25 * 60,
                    activeTaskName: config.initialTaskName || '',
                    totalTodaySeconds: Number(config.totalTodaySeconds) || 0,
                    urlStart: config.urlStart,
                    urlStop: config.urlStop,
                    selectedTask: '',
                    interval: null,
                    message: '',
                    isError: false,
                    busyStart: false,
                    busyStop: false,

                    get csrf() {
                        const meta = document.querySelector('meta[name="csrf-token"]');
                        return meta ? meta.getAttribute('content') : '';
                    },

                    get formattedDisplayTime() {
                        try {
                            let s = this.mode === 'pomodoro' ? Math.max(0, this.targetSeconds - this.seconds) : this.seconds;
                            const h = Math.floor(s / 3600);
                            const m = Math.floor((s % 3600) / 60);
                            const sc = s % 60;
                            if (h > 0) {
                                return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${sc.toString().padStart(2, '0')}`;
                            }
                            return `${m.toString().padStart(2, '0')}:${sc.toString().padStart(2, '0')}`;
                        } catch (e) {
                            console.error('Timer display error:', e);
                            return '00:00';
                        }
                    },

                    get formattedTodayTotal() {
                        const s = this.totalTodaySeconds + (this.isRunning ? this.seconds : 0);
                        const h = Math.floor(s / 3600);
                        const m = Math.floor((s % 3600) / 60);
                        return h > 0 ? `${h}h ${m}m` : `${m}m`;
                    },

                    get progressOffset() {
                        if (this.mode === 'focus') return 0;
                        const total = this.targetSeconds || 1;
                        const ratio = Math.min(this.seconds / total, 1);
                        return (2 * Math.PI * 45) * ratio;
                    },

                    init() { 
                        if (this.isRunning) this.startTick(); 
                        console.log('Timer App Initialized Successfully');
                    },

                    setMode(m) { this.mode = m; this.updateTarget(); },
                    setDuration(m) { 
                        this.configHrs = Math.floor(m / 60); 
                        this.configMins = m % 60; 
                        this.updateTarget(); 
                    },
                    updateTarget() { 
                        this.targetSeconds = (Number(this.configHrs) * 3600) + (Number(this.configMins) * 60); 
                    },

                    startTick() {
                        if (this.interval) clearInterval(this.interval);
                        this.interval = setInterval(() => {
                            if (!this.isPaused) {
                                this.seconds++;
                                if (this.mode === 'pomodoro' && this.seconds >= this.targetSeconds) {
                                    this.onComplete();
                                }
                            }
                        }, 1000);
                    },

                    onComplete() {
                        this.stopTimer();
                        alert('Session Complete!');
                    },

                    togglePause() { this.isPaused = !this.isPaused; },

                    async startTimer() {
                        if (this.isRunning || this.busyStart) return;
                        this.updateTarget();
                        this.busyStart = true;
                        try {
                            const res = await fetch(this.urlStart, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' },
                                body: JSON.stringify({ task_id: this.selectedTask || null })
                            });
                            const data = await res.json();
                            if (res.ok) {
                                this.isRunning = true; 
                                this.isPaused = false; 
                                this.seconds = 0;
                                this.activeTaskName = data?.data?.task_name || 'Uncategorized';
                                this.startTick();
                                this.msg('Timer started.');
                            } else { this.msg(data?.message || 'Error', true); }
                        } catch (e) { 
                            console.error('Start error:', e);
                            this.msg('Connection error', true); 
                        } finally { this.busyStart = false; }
                    },

                    async stopTimer() {
                        if (!this.isRunning || this.busyStop) return;
                        this.busyStop = true;
                        try {
                            const res = await fetch(this.urlStop, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' }
                            });
                            const data = await res.json();
                            if (res.ok) {
                                this.isRunning = false; 
                                clearInterval(this.interval);
                                if (data?.logged) window.location.reload();
                                else this.msg('Session under 60s not saved.');
                            } else { this.msg(data?.message || 'Error', true); }
                        } catch (e) { 
                            console.error('Stop error:', e);
                            this.msg('Connection error', true); 
                        } finally { this.busyStop = false; }
                    },

                    msg(m, isE = false) { 
                        this.message = m; 
                        this.isError = isE; 
                        setTimeout(() => this.message = '', 4000); 
                    },
                    handleGlobalKey(e) {
                        if (e.code === 'Space' && !['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) {
                            e.preventDefault();
                            this.isRunning ? this.togglePause() : this.startTimer();
                        }
                    }
                };
            };

            window.editSessionModal = function() {
                return {
                    open: false, 
                    updateUrl: '', 
                    duration: 0, 
                    taskId: '',
                    openEdit(url, dur, tid) { 
                        this.updateUrl = url; 
                        this.duration = dur; 
                        this.taskId = tid; 
                        this.open = true; 
                    },
                    close() { this.open = false; }
                };
            };
        </script>
    </x-slot:scripts>
</x-layouts.app>
