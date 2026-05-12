@props(['avgDailyFocusTime'])

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Left: Weekly Focus Time -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Focus Time</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Last 7 Days</p>
            </div>
            <span class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[10px] font-bold rounded-xl border border-emerald-100">Avg {{ $avgDailyFocusTime }}h / day</span>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="focusTimeChart"></canvas>
        </div>
    </div>

    <!-- Right: Completed Tasks -->
    <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Activity Log</h2>
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest mt-1">Completed Tasks</p>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="completedTasksChart"></canvas>
        </div>
    </div>
</div>
