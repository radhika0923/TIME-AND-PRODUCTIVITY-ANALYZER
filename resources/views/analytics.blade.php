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

    <x-analytics.heatmap :heatmapData="$heatmapData" :maxDailySeconds="$maxDailySeconds" />
    <x-analytics.stats-grid 
        :totalTasks="$totalTasks" 
        :completedTasks="$completedTasks" 
        :totalTimeThisWeek="$totalTimeThisWeek" 
        :productivityScore="$productivityScore" 
    />
    <x-analytics.charts :avgDailyFocusTime="$avgDailyFocusTime" />


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
