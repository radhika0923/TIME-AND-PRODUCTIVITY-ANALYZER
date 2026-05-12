<x-layouts.app title="Analytics - Time & Productivity Analyzer">
    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot:scripts>

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-semibold tracking-tight text-white mb-1">Analytics Overview</h1>
        <p class="text-slate-400 text-sm">Deep dive into your productivity patterns and task completion.</p>
    </div>

    <p class="text-sm text-slate-400 -mt-4 mb-2">
        Focus streak: <span class="text-white font-semibold tabular-nums">{{ $focusStreak ?? 0 }}</span> consecutive days with at least 1 minute logged (uses the timezone in Settings).
    </p>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-semibold text-white mb-1">{{ $totalTasks }}</h3>
            <p class="text-sm text-slate-500 font-medium">Total Tasks Created</p>
        </div>

        <!-- Card 2 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-semibold text-white mb-1">{{ $completedTasks }}</h3>
            <p class="text-sm text-slate-500 font-medium">Tasks Completed</p>
        </div>

        <!-- Card 3 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-purple-500/30 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-purple-500/10 rounded-xl text-purple-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-semibold text-white mb-1">{{ $totalTimeThisWeek }}h</h3>
            <p class="text-sm text-slate-500 font-medium">Focus Time This Week</p>
        </div>

        <!-- Card 4 -->
        <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/30 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2.5 bg-amber-500/10 rounded-xl text-amber-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <h3 class="text-3xl font-semibold text-white mb-1">{{ $productivityScore }}%</h3>
            <p class="text-sm text-slate-500 font-medium">Productivity Score</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Left: Weekly Focus Time -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-white">Focus Time (Last 7 Days)</h2>
                <span class="text-sm text-slate-400">Avg {{ $avgDailyFocusTime }}h / day</span>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="focusTimeChart"></canvas>
            </div>
        </div>

        <!-- Right: Completed Tasks -->
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-white">Completed Tasks</h2>
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
            gradientFocus.addColorStop(0, 'rgba(99, 102, 241, 0.2)'); 
            gradientFocus.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxFocus, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Focus Hours',
                        data: weeklyTimeData,
                        borderColor: '#6366f1',
                        backgroundColor: gradientFocus,
                        borderWidth: 3,
                        pointBackgroundColor: '#1e293b',
                        pointBorderColor: '#6366f1',
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
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) { return context.parsed.y + ' hrs'; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#1e293b', drawBorder: false },
                            ticks: { color: '#64748b' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#64748b' }
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
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                        hoverBackgroundColor: '#34d399'
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
                            bodyColor: '#cbd5e1',
                            borderColor: '#334155',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) { return context.parsed.y + ' tasks'; }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#1e293b', drawBorder: false },
                            ticks: { color: '#64748b', stepSize: 1 }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: '#64748b' }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' },
                }
            });
        });
    </script>
</x-layouts.app>
