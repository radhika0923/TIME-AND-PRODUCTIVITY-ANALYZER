@props(['recentActivities'])

<!-- Recent Activity -->
<div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2rem] p-6 shadow-sm">
    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Recent Activity</h2>
    <div class="space-y-6">
        @forelse($recentActivities as $activity)
            <div class="flex gap-4">
                @if($activity['type'] === 'completed')
                    <div class="w-10 h-10 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center shrink-0 border border-emerald-100 dark:border-emerald-500/20">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                @elseif($activity['type'] === 'focus')
                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center shrink-0 border border-indigo-100 dark:border-indigo-500/20">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                @else
                    <div class="w-10 h-10 rounded-full bg-gray-50 dark:bg-gray-700 flex items-center justify-center shrink-0 border border-gray-100 dark:border-gray-600">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                @endif
                <div class="flex-1 {{ !$loop->last ? 'pb-6 border-b border-gray-50 dark:border-gray-700' : '' }}">
                    @if($activity['type'] === 'completed')
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Completed <span class="text-emerald-600 dark:text-emerald-400">'{{ $activity['title'] }}'</span></h4>
                    @elseif($activity['type'] === 'focus')
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Focused on <span class="text-indigo-600 dark:text-indigo-400">'{{ $activity['title'] }}'</span> <span class="text-gray-400 dark:text-gray-500 font-normal">({{ $activity['duration_label'] ?? '' }})</span></h4>
                    @else
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Created <span class="text-gray-600 dark:text-gray-400">'{{ $activity['title'] }}'</span></h4>
                    @endif
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">{{ $activity['time_human'] }}</p>
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-12 text-center bg-gray-50/50 dark:bg-gray-900/50 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700">
                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No activity</p>
                <p class="text-xs text-gray-400 mt-1">Activities will appear here as you work.</p>
            </div>
        @endforelse
    </div>
</div>
