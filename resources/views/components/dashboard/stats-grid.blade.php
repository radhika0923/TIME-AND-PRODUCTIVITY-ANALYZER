@props(['totalTasks', 'totalTime', 'todaySeconds' => 0, 'dailyGoalSeconds' => 14400])

@php
    $goalPercentage = $dailyGoalSeconds > 0 ? min(100, round(($todaySeconds / $dailyGoalSeconds) * 100)) : 0;
    $todayHours = \App\Support\Duration::toDecimalHours($todaySeconds);
    $goalHours = round($dailyGoalSeconds / 3600, 1);
@endphp

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
    </div>

    <!-- Card 2: Tracked (White Card) -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-5 border border-gray-100 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Tracked</p>
            <div class="w-7 h-7 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-3 tabular-nums">{{ $totalTime ?? 0 }}h</h3>
    </div>

    <!-- Card 3: Daily Goal -->
    <div class="group bg-white dark:bg-gray-800 rounded-[2rem] p-5 border border-gray-100 dark:border-gray-700 shadow-sm transition-all duration-300 hover:shadow-md relative overflow-hidden">
        <!-- Progress Bar Background -->
        <div class="absolute bottom-0 left-0 h-1.5 bg-gray-100 dark:bg-gray-700 w-full"></div>
        <div class="absolute bottom-0 left-0 h-1.5 bg-emerald-500 transition-all duration-1000 ease-out" style="width: {{ $goalPercentage }}%"></div>

        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daily Goal</p>
            <div class="w-7 h-7 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-500">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
        <div class="flex items-end gap-2 mb-3">
            <h3 class="text-4xl font-extrabold text-gray-900 dark:text-white tabular-nums">{{ $goalPercentage }}%</h3>
        </div>
        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-lg bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-700 text-[9px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            {{ $todayHours }}h / {{ $goalHours }}h logged
        </div>
    </div>
</div>
