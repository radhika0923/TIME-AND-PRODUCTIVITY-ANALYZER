@props(['heatmapData', 'maxDailySeconds'])

<!-- Productivity Heatmap -->
<div class="bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-4">
        <div>
            <h2 class="text-lg font-bold text-gray-900">Focus Heatmap</h2>
            <p class="text-xs text-gray-400 mt-1">Your focus intensity over the last 365 days.</p>
        </div>
        <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest">
            <span>Less</span>
            <div class="flex gap-1">
                <div class="w-3 h-3 rounded-sm bg-gray-100"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-500/20"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-500/40"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-500/70"></div>
                <div class="w-3 h-3 rounded-sm bg-emerald-500"></div>
            </div>
            <span>More</span>
        </div>
    </div>
    
    <div class="overflow-x-auto pb-2 custom-scrollbar">
        <div class="inline-grid grid-rows-7 grid-flow-col gap-1.5 min-w-max">
            @php
                $startDate = \Carbon\Carbon::now()->subDays(364)->startOfDay();
            @endphp
            @foreach($heatmapData as $date => $seconds)
                @php
                    $intensity = $maxDailySeconds > 0 ? ($seconds / $maxDailySeconds) : 0;
                    $colorClass = 'bg-gray-100';
                    if ($intensity > 0.75) $colorClass = 'bg-emerald-500';
                    elseif ($intensity > 0.5) $colorClass = 'bg-emerald-500/70';
                    elseif ($intensity > 0.25) $colorClass = 'bg-emerald-500/40';
                    elseif ($intensity > 0) $colorClass = 'bg-emerald-500/20';
                    
                    $formattedDuration = \App\Support\Duration::format($seconds);
                    $formattedDate = \Carbon\Carbon::parse($date)->format('M d, Y');
                @endphp
                <div class="w-3.5 h-3.5 rounded-sm {{ $colorClass }} transition-colors cursor-help group relative" 
                     title="{{ $formattedDate }}: {{ $formattedDuration }}">
                    <!-- Tooltip -->
                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-[10px] text-white rounded opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-10 shadow-xl">
                        {{ $formattedDate }}: {{ $formattedDuration }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="flex justify-between mt-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">
        <span>{{ \Carbon\Carbon::now()->subDays(364)->format('M Y') }}</span>
        <span>{{ \Carbon\Carbon::now()->format('M Y') }}</span>
    </div>
</div>
