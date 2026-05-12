<x-layouts.app title="Dashboard - Time & Productivity Analyzer">
    <!-- Welcome & Actions -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Welcome back, {{ explode(' ', auth()->user()->name ?? 'User')[0] }}</h1>
            <div class="flex items-center gap-2 text-gray-500 font-medium">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span>
                Your productivity engine is ready.
            </div>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('time.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-black transition-all shadow-lg shadow-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Focus Session
            </a>
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Task
            </a>
        </div>
    </div>

    <x-dashboard.stats-grid :totalTasks="$totalTasks" :totalTime="$totalTime" />

    <div class="flex flex-col gap-6">
        <!-- Main Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <x-dashboard.productivity-chart :weekOffset="$weekOffset ?? 0" />
            <x-dashboard.completion-rate :completionRate="$completionRate ?? 0" :topTaskProgress="$topTaskProgress" />
        </div>

        <!-- Bottom Section: Recent Activity & Insight -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <x-dashboard.recent-activity :recentActivities="$recentActivities" />
            <x-dashboard.insight :focusInsight="$focusInsight ?? null" />
        </div>
    </div>
    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('productivityChart').getContext('2d');
                
                // Gradient for the line
                let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)'); // Emerald-500
                gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Focus Hours',
                            data: @json($chartData),
                            borderColor: '#10b981', // Emerald-500
                            backgroundColor: gradient,
                            borderWidth: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 8,
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
                                backgroundColor: '#111827',
                                titleColor: '#fff',
                                bodyColor: '#fff',
                                borderColor: '#374151',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        return context.parsed.y + ' hrs';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f3f4f6',
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: '#9ca3af',
                                    stepSize: 2
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: '#9ca3af'
                                }
                            }
                        },
                        interaction: {
                            intersect: false,
                            mode: 'index',
                        },
                    }
                });
            });
        </script>
    </x-slot:scripts>
</x-layouts.app>
