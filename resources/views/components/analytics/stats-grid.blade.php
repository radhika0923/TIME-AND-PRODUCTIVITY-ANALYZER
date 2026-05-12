@props(['totalTasks', 'completedTasks', 'totalTimeThisWeek', 'productivityScore'])

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Card 1 -->
    <div class="group bg-white border border-gray-100 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $totalTasks }}</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tasks Created</p>
    </div>

    <!-- Card 2 -->
    <div class="group bg-white border border-gray-100 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $completedTasks }}</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tasks Completed</p>
    </div>

    <!-- Card 3 -->
    <div class="group bg-white border border-gray-100 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $totalTimeThisWeek }}h</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Time This Week</p>
    </div>

    <!-- Card 4 -->
    <div class="group bg-white border border-gray-100 rounded-3xl p-5 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-4">
            <div class="p-2.5 bg-emerald-50 rounded-xl text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
        <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $productivityScore }}%</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Success Rate</p>
    </div>
</div>
