<x-layouts.app title="Dashboard - Time & Productivity Analyzer">
    <!-- Welcome & Actions -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-white mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }}</h1>
                        <p class="text-slate-400 text-sm flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            You're more productive than 70% of users this week
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button class="px-5 py-2.5 text-sm font-medium text-white bg-slate-800 border border-slate-700 rounded-xl hover:bg-slate-700 hover:border-slate-600 transition-all shadow-sm">
                            Start Focus Session
                        </button>
                        <button class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                            + Add Task
                        </button>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Card 1 -->
                    <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                12%
                            </span>
                        </div>
                        <h3 class="text-3xl font-semibold text-white mb-1">{{ $totalTasks ?? 0 }}</h3>
                        <p class="text-sm text-slate-500 font-medium">Total Tasks</p>
                    </div>

                    <!-- Card 2 -->
                    <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2.5 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                8%
                            </span>
                        </div>
                        <h3 class="text-3xl font-semibold text-white mb-1">{{ $completedTasks ?? 0 }}</h3>
                        <p class="text-sm text-slate-500 font-medium">Completed Tasks</p>
                    </div>

                    <!-- Card 3 -->
                    <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-500/30 hover:shadow-xl hover:shadow-amber-500/10 transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2.5 bg-amber-500/10 rounded-xl text-amber-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-400 bg-slate-800 px-2 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path></svg>
                                0%
                            </span>
                        </div>
                        <h3 class="text-3xl font-semibold text-white mb-1">{{ $pendingTasks ?? 0 }}</h3>
                        <p class="text-sm text-slate-500 font-medium">Pending Tasks</p>
                    </div>

                    <!-- Card 4 -->
                    <div class="group bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-purple-500/30 hover:shadow-xl hover:shadow-purple-500/10 transition-all duration-300">
                        <div class="flex justify-between items-start mb-4">
                            <div class="p-2.5 bg-purple-500/10 rounded-xl text-purple-400 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                24%
                            </span>
                        </div>
                        <h3 class="text-3xl font-semibold text-white mb-1">{{ $totalTime ?? 0 }}h</h3>
                        <p class="text-sm text-slate-500 font-medium">Total Focus Time</p>
                    </div>
                </div>

                <!-- Main Grid Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left: Line Chart (70%) -->
                    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-white">Weekly Productivity</h2>
                            <select class="bg-slate-800 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                <option>This Week</option>
                                <option>Last Week</option>
                            </select>
                        </div>
                        <div class="relative h-72 w-full">
                            <canvas id="productivityChart"></canvas>
                        </div>
                    </div>

                    <!-- Right: Circular Progress (30%) -->
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm flex flex-col">
                        <h2 class="text-lg font-semibold text-white mb-6">Task Completion Rate</h2>
                        <div class="flex-1 flex flex-col items-center justify-center relative">
                            <!-- Circular Progress SVG -->
                            <?php 
                                $circumference = 502;
                                $offset = $circumference * (100 - ($completionRate ?? 0)) / 100;
                            ?>
                            <svg class="w-48 h-48 transform -rotate-90">
                                <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" class="text-slate-800"/>
                                <circle cx="96" cy="96" r="80" stroke="currentColor" stroke-width="12" fill="transparent" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" stroke-linecap="round" class="text-indigo-500 transition-all duration-1000 ease-out"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-bold text-white">{{ $completionRate ?? 0 }}%</span>
                                <span class="text-sm text-slate-500 mt-1">Completed</span>
                            </div>
                        </div>
                        <div class="mt-6 space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400">Design System</span>
                                <span class="text-white font-medium">100%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 100%"></div>
                            </div>
                            <div class="flex justify-between items-center text-sm pt-2">
                                <span class="text-slate-400">API Integration</span>
                                <span class="text-white font-medium">45%</span>
                            </div>
                            <div class="w-full bg-slate-800 rounded-full h-1.5">
                                <div class="bg-indigo-500 h-1.5 rounded-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Recent Activity & Insight -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-6">Recent Activity</h2>
                        <div class="space-y-6">
                            <!-- Item 1 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1 pb-6 border-b border-slate-800/60">
                                    <h4 class="text-sm font-medium text-slate-200">Completed <span class="text-white">‘Design Dashboard’</span></h4>
                                    <p class="text-xs text-slate-500 mt-1">2h ago</p>
                                </div>
                            </div>
                            <!-- Item 2 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0 border border-indigo-500/20">
                                    <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                </div>
                                <div class="flex-1 pb-6 border-b border-slate-800/60">
                                    <h4 class="text-sm font-medium text-slate-200">Created task <span class="text-white">‘Setup Authentication’</span></h4>
                                    <p class="text-xs text-slate-500 mt-1">5h ago</p>
                                </div>
                            </div>
                            <!-- Item 3 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0 border border-purple-500/20">
                                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="flex-1 pb-6 border-b border-slate-800/60">
                                    <h4 class="text-sm font-medium text-slate-200">Started focus session <span class="text-white">‘Deep Work’</span></h4>
                                    <p class="text-xs text-slate-500 mt-1">Yesterday</p>
                                </div>
                            </div>
                            <!-- Item 4 -->
                            <div class="flex gap-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                    <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-slate-200">Completed <span class="text-white">‘Database Schema’</span></h4>
                                    <p class="text-xs text-slate-500 mt-1">Yesterday</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Productivity Insight -->
                    <div class="bg-gradient-to-br from-indigo-900/50 via-slate-900 to-slate-900 border border-indigo-500/20 rounded-2xl p-6 flex flex-col relative overflow-hidden">
                        <!-- Decorative glow -->
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-indigo-500/10 blur-3xl rounded-full"></div>
                        
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div class="p-2 bg-indigo-500/20 rounded-lg text-indigo-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h2 class="text-lg font-semibold text-white">AI Insight</h2>
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-center relative z-10">
                            <p class="text-slate-300 leading-relaxed italic text-lg mb-6">
                                "You're completing tasks 20% faster when you schedule them in the morning. Consider moving heavy logic work to your 9 AM block."
                            </p>
                            <div class="mt-auto">
                                <button class="text-indigo-400 text-sm font-medium hover:text-indigo-300 flex items-center gap-1 transition-colors">
                                    View detailed analysis
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('productivityChart').getContext('2d');
                
                // Gradient for the line
                let gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)'); // Indigo-500
                gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Focus Hours',
                            data: [2.5, 3.8, 5.0, 4.2, 6.1, 2.0, 1.5],
                            borderColor: '#6366f1', // Indigo-500
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#1e293b',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4 // Smooth curve
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
                                    color: '#1e293b',
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: '#64748b',
                                    stepSize: 2
                                }
                            },
                            x: {
                                grid: {
                                    display: false,
                                    drawBorder: false,
                                },
                                ticks: {
                                    color: '#64748b'
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
