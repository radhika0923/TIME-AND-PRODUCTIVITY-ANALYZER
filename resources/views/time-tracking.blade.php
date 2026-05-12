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
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">Time Tracking</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Monitor your productivity sessions in real-time.</p>
        </div>
        
        @if(session('time_log_status'))
            <div class="rounded-2xl border border-emerald-100 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 px-6 py-3 text-sm font-bold text-emerald-700 dark:text-emerald-400 shadow-sm animate-bounce">
                {{ session('time_log_status') }}
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8"
         x-data="timerApp(window.timerConfig)"
         @keydown.window="handleGlobalKey($event)">
        
        <x-time-tracking.timer-display :tasks="$tasks" :activeSession="$activeSession" />
        <x-time-tracking.summary-sidebar :sessionsToday="$sessionsToday" />
    </div>

    <x-time-tracking.logs-table :logs="$logs" :filters="$filters ?? []" />

    <x-slot:scripts>
        <script>
            window.timerConfig = {
                initialSeconds: {{ (int) $runningDuration }},
                initialRunning: {{ $activeSession ? 'true' : 'false' }},
                initialTaskName: @json($activeSession['task_name'] ?? ''),
                totalTodaySeconds: {{ (int) $totalTimeTodaySeconds }},
                urlStart: @json(route('time.start')),
                urlStop: @json(route('time.stop')),
                pomodoroWork: {{ $pomodoroWork ?? 25 }},
                pomodoroBreak: {{ $pomodoroBreak ?? 5 }}
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
                        const sc = s % 60;
                        return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${sc.toString().padStart(2, '0')}`;
                    },

                    get progressOffset() {
                        if (this.mode === 'focus') return 0;
                        const total = this.targetSeconds || 1;
                        const ratio = Math.min(this.seconds / total, 1);
                        return (2 * Math.PI * 45) * ratio;
                    },

                    pomodoroPhase: 'work',
                    pomodoroCount: 0,

                    init() { 
                        if (this.isRunning) {
                            const saved = localStorage.getItem('timerState');
                            if (saved) {
                                try {
                                    const parsed = JSON.parse(saved);
                                    this.mode = parsed.mode || 'focus';
                                    this.targetSeconds = parsed.targetSeconds || (25 * 60);
                                    this.isPaused = parsed.isPaused || false;
                                    this.pomodoroPhase = parsed.pomodoroPhase || 'work';
                                    this.pomodoroCount = parsed.pomodoroCount || 0;
                                    
                                    if (this.isPaused) {
                                        this.seconds = parsed.seconds;
                                    } else {
                                        const elapsed = Math.floor((Date.now() - parsed.lastTick) / 1000);
                                        this.seconds = parsed.seconds + (elapsed > 0 ? elapsed : 0);
                                    }
                                } catch (e) {
                                    console.error('Failed to parse timer state', e);
                                }
                            }
                            if (!this.isPaused) this.startTick(); 
                        } else {
                            localStorage.removeItem('timerState');
                        }
                        console.log('Timer App Initialized Successfully');
                    },

                    saveState() {
                        if (!this.isRunning) return;
                        localStorage.setItem('timerState', JSON.stringify({
                            mode: this.mode,
                            targetSeconds: this.targetSeconds,
                            isPaused: this.isPaused,
                            seconds: this.seconds,
                            pomodoroPhase: this.pomodoroPhase,
                            pomodoroCount: this.pomodoroCount,
                            lastTick: Date.now()
                        }));
                    },

                    setMode(m) { 
                        this.mode = m; 
                        if (m === 'pomodoro') {
                            this.pomodoroPhase = 'work';
                            this.setDuration(config.pomodoroWork || 25);
                        } else {
                            this.updateTarget();
                        }
                    },
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
                                this.saveState();
                                if (this.mode === 'pomodoro' && this.seconds >= this.targetSeconds) {
                                    this.onComplete();
                                }
                            }
                        }, 1000);
                    },

                    async onComplete() {
                        await this.stopTimer(true);
                        
                        if (this.pomodoroPhase === 'work') {
                            this.pomodoroCount++;
                            if (this.pomodoroCount % 4 === 0) {
                                this.pomodoroPhase = 'long_break';
                                this.setDuration((config.pomodoroBreak || 5) * 3); // Long break is 3x short break
                                this.msg('Time for a long break!');
                            } else {
                                this.pomodoroPhase = 'short_break';
                                this.setDuration(config.pomodoroBreak || 5);
                                this.msg('Time for a short break!');
                            }
                        } else {
                            this.pomodoroPhase = 'work';
                            this.setDuration(config.pomodoroWork || 25);
                            this.msg('Back to work!');
                        }
                        
                        // Wait briefly before starting the next phase
                        setTimeout(() => {
                            this.startTimer();
                        }, 2000);
                    },

                    togglePause() { 
                        this.isPaused = !this.isPaused; 
                        this.saveState();
                        if (!this.isPaused) this.startTick();
                        else clearInterval(this.interval);
                    },

                    async startTimer() {
                        if (this.isRunning || this.busyStart) return;
                        this.updateTarget();
                        this.busyStart = true;
                        
                        // We don't need to log break times to the server, so we skip API calls for breaks
                        if (this.mode === 'pomodoro' && (this.pomodoroPhase === 'short_break' || this.pomodoroPhase === 'long_break')) {
                            this.isRunning = true; 
                            this.isPaused = false; 
                            this.seconds = 0;
                            this.activeTaskName = 'Break Time';
                            this.saveState();
                            this.startTick();
                            this.busyStart = false;
                            return;
                        }

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
                                this.saveState();
                                this.startTick();
                                this.msg('Timer started.');
                            } else { this.msg(data?.message || 'Error', true); }
                        } catch (e) { 
                            console.error('Start error:', e);
                            this.msg('Connection error', true); 
                        } finally { this.busyStart = false; }
                    },

                    async stopTimer(preventReload = false) {
                        if (!this.isRunning || this.busyStop) return;
                        this.busyStop = true;
                        
                        // If it's a break, no need to call API to save
                        if (this.mode === 'pomodoro' && (this.pomodoroPhase === 'short_break' || this.pomodoroPhase === 'long_break')) {
                            this.isRunning = false; 
                            clearInterval(this.interval);
                            localStorage.removeItem('timerState');
                            this.seconds = 0;
                            this.busyStop = false;
                            return;
                        }

                        try {
                            const res = await fetch(this.urlStop, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrf, 'Accept': 'application/json' },
                                body: JSON.stringify({ duration: this.seconds })
                            });
                            const data = await res.json();
                            if (res.ok) {
                                this.isRunning = false; 
                                clearInterval(this.interval);
                                localStorage.removeItem('timerState');
                                if (data?.logged) {
                                    if (!preventReload) window.location.reload();
                                } else {
                                    this.seconds = 0;
                                    this.msg('Session under 60s not saved.');
                                }
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
                    hrs: 0,
                    mins: 0,
                    secs: 0,
                    
                    deleteModalOpen: false,
                    pendingDeleteUrl: '',
                    toasts: [],

                    openEdit(url, dur, tid) { 
                        this.updateUrl = url; 
                        this.duration = dur; 
                        this.taskId = tid; 
                        
                        this.hrs = Math.floor(dur / 3600);
                        this.mins = Math.floor((dur % 3600) / 60);
                        this.secs = dur % 60;
                        
                        this.open = true; 
                    },
                    
                    confirmDelete(url) {
                        this.pendingDeleteUrl = url;
                        this.deleteModalOpen = true;
                    },

                    async executeDelete() {
                        this.deleteModalOpen = false;
                        const url = this.pendingDeleteUrl;
                        const id = Date.now();
                        
                        const toast = {
                            id: id,
                            message: 'Session deleted',
                            cancelled: false,
                            hidden: false
                        };
                        
                        this.toasts.push(toast);

                        // Countdown before actual deletion
                        setTimeout(async () => {
                            const currentToast = this.toasts.find(t => t.id === id);
                            if (currentToast && !currentToast.cancelled) {
                                // Actually perform delete
                                const response = await fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'X-HTTP-Method-Override': 'DELETE',
                                        'Accept': 'application/json'
                                    }
                                });
                                if (response.ok) {
                                    window.location.reload();
                                }
                            }
                        }, 5000);
                    },

                    undoDelete(id) {
                        const toast = this.toasts.find(t => t.id === id);
                        if (toast) {
                            toast.cancelled = true;
                            toast.hidden = true;
                            // Optionally show a confirmation toast
                        }
                    },

                    get totalSeconds() {
                        return (Number(this.hrs) * 3600) + (Number(this.mins) * 60) + Number(this.secs);
                    },
                    close() { this.open = false; }
                };
            };
        </script>
    </x-slot:scripts>
</x-layouts.app>
