@props(['sessionsToday'])

<!-- SIDEBAR SUMMARY -->
<div class="space-y-8">
    <!-- DAILY STATS -->
    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2.5rem] p-8 shadow-lg shadow-gray-200/40 dark:shadow-none">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Today's Summary</h2>
        
        <div class="space-y-6">
            <div class="flex items-center justify-between p-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-3xl">
                <div>
                    <span class="block text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest mb-1">Focus Time</span>
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white font-mono" x-text="formattedTodayTotal"></span>
                </div>
                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-emerald-500 dark:text-emerald-400 shadow-sm dark:shadow-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>

            <div class="flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-3xl">
                <div>
                    <span class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-widest mb-1">Total Sessions</span>
                    <span class="text-3xl font-extrabold text-gray-900 dark:text-white tabular-nums">{{ $sessionsToday }}</span>
                </div>
                <div class="w-12 h-12 bg-white dark:bg-gray-800 rounded-2xl flex items-center justify-center text-gray-400 dark:text-gray-500 shadow-sm dark:shadow-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                </div>
            </div>
        </div>
    </div>
</div>
