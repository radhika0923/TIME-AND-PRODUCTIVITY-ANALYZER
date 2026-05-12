@props(['totalTasks', 'totalTime'])

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <!-- Card 1: Finished (Green Card) -->
    <div class="group bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-[2rem] p-5 text-white shadow-xl shadow-emerald-900/20 transition-all duration-300 hover:scale-[1.02]">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold opacity-80 uppercase tracking-widest">Finished</p>
            <div class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold mb-3 tabular-nums">{{ $totalTasks ?? 0 }}</h3>
        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-white/10 text-[9px] font-bold uppercase tracking-wider">
            <span class="text-emerald-300">+8</span> tasks
        </div>
    </div>

    <!-- Card 2: Tracked (White Card) -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-5 border border-gray-100 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tracked</p>
            <div class="w-7 h-7 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tabular-nums">{{ $totalTime ?? 0 }}h</h3>
        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-[9px] font-bold text-rose-500 dark:text-rose-400 uppercase tracking-wider">
            <span class="text-rose-600 dark:text-rose-400">-6</span> hours
        </div>
    </div>

    <!-- Card 3: Efficiency (White Card) -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-5 border border-gray-100 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Efficiency</p>
            <div class="w-7 h-7 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tabular-nums">93%</h3>
        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
            12% <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
        </div>
    </div>
</div>
