@props(['completionRate', 'topTaskProgress'])

<!-- Right: Circular Progress (30%) -->
<div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm flex flex-col">
    <h2 class="text-lg font-bold text-gray-900 mb-6">Task Completion Rate</h2>
    <div class="flex-1 flex flex-col items-center justify-center relative">
        <!-- Circular Progress SVG -->
        <?php 
            $circumference = 502;
            $offset = $circumference * (100 - ($completionRate ?? 0)) / 100;
        ?>
        <svg class="w-48 h-48 transform -rotate-90">
            <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" class="text-gray-100"/>
            <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" stroke-linecap="round" class="text-emerald-500 transition-all duration-1000 ease-out"/>
        </svg>
        <div class="absolute inset-0 flex flex-col items-center justify-center">
            <span class="text-4xl font-extrabold text-gray-900">{{ $completionRate ?? 0 }}%</span>
            <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider mt-1">Goal Met</span>
        </div>
    </div>
    <div class="mt-6 space-y-3">
        @forelse($topTaskProgress as $taskProg)
            <div class="flex justify-between items-center text-sm @if(!$loop->first) pt-2 @endif">
                <span class="text-gray-500 font-medium">{{ $taskProg['title'] }}</span>
                <span class="text-gray-900 font-bold">{{ $taskProg['hours'] }}h</span>
            </div>
            <div class="w-full bg-gray-50 rounded-full h-1.5">
                <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-700 shadow-[0_0_8px_rgba(16,185,129,0.3)]" style="width: {{ $taskProg['percentage'] }}%"></div>
            </div>
        @empty
            <p class="text-xs text-gray-400 italic text-center">No time tracked yet</p>
        @endforelse
    </div>
</div>
