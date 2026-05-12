@props(['tasks', 'activeSession'])

<!-- MAIN TIMER SECTION -->
<div class="lg:col-span-2 bg-white border border-gray-100 rounded-[3rem] p-8 lg:p-12 shadow-xl shadow-gray-200/50 relative overflow-hidden flex flex-col items-center justify-start min-h-[600px]">
    
    <!-- Decorative Background Glow -->
    <div class="absolute inset-0 transition-opacity duration-1000 pointer-events-none" 
         :class="isRunning && !isPaused ? (mode === 'pomodoro' && pomodoroPhase !== 'work' ? 'bg-sky-500/[0.03] opacity-100' : 'bg-emerald-500/[0.03] opacity-100') : 'opacity-0'"></div>

    <!-- Header Controls -->
    <div x-show="!isRunning" x-transition class="flex bg-gray-50 p-1.5 rounded-2xl border border-gray-100 z-20 mb-12">
        <button @click="setMode('focus')" :class="mode === 'focus' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 text-[10px] font-bold uppercase tracking-[0.2em] rounded-xl transition-all border border-transparent">Focus Mode</button>
        <button @click="setMode('pomodoro')" :class="mode === 'pomodoro' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-400 hover:text-gray-600'" class="px-6 py-2.5 text-[10px] font-bold uppercase tracking-[0.2em] rounded-xl transition-all border border-transparent">Pomodoro</button>
    </div>

    <!-- Task Label -->
    <div class="relative z-10 text-center mb-8" x-show="isRunning" x-transition>
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-4" :class="mode === 'pomodoro' && pomodoroPhase !== 'work' ? 'bg-sky-50 text-sky-600 border-sky-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100'">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="mode === 'pomodoro' && pomodoroPhase !== 'work' ? 'bg-sky-400' : 'bg-emerald-400'" x-show="!isPaused"></span>
                <span class="relative inline-flex rounded-full h-2 w-2" :class="mode === 'pomodoro' && pomodoroPhase !== 'work' ? 'bg-sky-500' : 'bg-emerald-500'"></span>
            </span>
            <span class="text-[10px] font-bold uppercase tracking-widest" x-text="isPaused ? 'Session Paused' : (mode === 'pomodoro' ? (pomodoroPhase === 'work' ? 'Pomodoro Focus' : (pomodoroPhase === 'short_break' ? 'Short Break' : 'Long Break')) : 'Focus Active')"></span>
        </div>
        <h3 class="text-2xl font-bold text-gray-900" x-text="activeTaskName || 'Uncategorized Session'">{{ $activeSession['task_name'] ?? 'Ready to Focus' }}</h3>
    </div>

    <!-- WATCH FACE -->
    <div class="relative flex items-center justify-center mb-10 group">
        <!-- Progress Ring -->
        <svg class="w-64 h-64 sm:w-80 sm:h-80" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" fill="none" stroke="#F3F4F6" stroke-width="2" />
            <circle cx="50" cy="50" r="45" fill="none" stroke="currentColor" stroke-width="2.5" 
                    class="timer-ring transition-colors"
                    :class="mode === 'pomodoro' && pomodoroPhase !== 'work' ? 'text-sky-500' : 'text-emerald-500'"
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
