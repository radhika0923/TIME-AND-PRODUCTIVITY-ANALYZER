@props(['sessionsToday'])

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
</div>
