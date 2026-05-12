<x-layouts.app title="Analytics - Time & Productivity Analyzer">
    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot:scripts>

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-1">Analytics Overview</h1>
        <p class="text-gray-500 text-sm">Deep dive into your productivity patterns and task completion.</p>
    </div>

    <p class="text-sm text-gray-500 -mt-4 mb-2">
        Focus streak: <span class="text-emerald-600 font-bold tabular-nums">{{ $focusStreak ?? 0 }}</span> consecutive days with at least 1 minute logged (uses the timezone in Settings).
    </p>

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

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $totalTasks }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tasks Created</p>
        </div>

        <!-- Card 2 -->
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $completedTasks }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tasks Completed</p>
        </div>

        <!-- Card 3 -->
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $totalTimeThisWeek }}h</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Time This Week</p>
        </div>

        <!-- Card 4 -->
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $productivityScore }}%</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Success Rate</p>
        </div>
    </div>

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


    <!-- Chart.js Init -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartLabels = {!! json_encode($chartLabels) !!};
            const weeklyTimeData = {!! json_encode($weeklyTimeData) !!};
            const completedTasksPerDay = {!! json_encode($completedTasksPerDay) !!};

            const ctxFocus = document.getElementById('focusTimeChart').getContext('2d');
            let gradientFocus = ctxFocus.createLinearGradient(0, 0, 0, 300);
            gradientFocus.addColorStop(0, 'rgba(16, 185, 129, 0.1)'); 
            gradientFocus.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctxFocus, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Focus Hours',
                        data: weeklyTimeData,
                        borderColor: '#10b981',
                        backgroundColor: gradientFocus,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#10b981',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fff',
                            bodyColor: '#f1f5f9',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) { return context.parsed.y + ' hrs logged'; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { weight: '700', size: 10 }
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { weight: '700', size: 10 }
                            }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' },
                }
            });

            const ctxTasks = document.getElementById('completedTasksChart').getContext('2d');
            new Chart(ctxTasks, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Tasks Completed',
                        data: completedTasksPerDay,
                        backgroundColor: '#10b981',
                        borderRadius: 12,
                        borderSkipped: false,
                        barPercentage: 0.5,
                        hoverBackgroundColor: '#059669'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleColor: '#fff',
                            bodyColor: '#f1f5f9',
                            borderColor: '#1e293b',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) { return context.parsed.y + ' tasks finished'; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { weight: '700', size: 10 },
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { 
                                color: '#94a3b8',
                                font: { weight: '700', size: 10 }
                            }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' },
                }
            });
        });
    </script>
</x-layouts.app>
