<x-layouts.app title="Dashboard - Time & Productivity Analyzer">
    <!-- Welcome & Actions -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <h1 class="text-3xl font-semibold tracking-tight text-white mb-1">Welcome back, {{ auth()->user()->name ?? 'User' }}</h1>
                        <p class="text-slate-400 text-sm flex items-center gap-2">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            Your tasks, focus time, and weekly chart in one place
                        </p>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('time.index') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-slate-800 border border-slate-700 rounded-xl hover:bg-slate-700 hover:border-slate-600 transition-all shadow-sm inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Start Focus Session
                        </a>
                        <a href="{{ route('tasks.index') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            + Add Task
                        </a>
                    </div>
                </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Finished (Green Card) -->
        <div class="group bg-gradient-to-br from-emerald-700 to-emerald-900 rounded-[2rem] p-6 text-white shadow-xl shadow-emerald-900/20 transition-all duration-300 hover:scale-[1.02]">
            <div class="flex justify-between items-start mb-6">
                <p class="text-sm font-medium opacity-90 uppercase tracking-widest">Finished</p>
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </div>
            </div>
            <h3 class="text-5xl font-bold mb-4 tabular-nums">{{ $totalTasks ?? 0 }}</h3>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/10 text-[10px] font-bold uppercase tracking-wider">
                <span class="text-emerald-300">+8</span> tasks
            </div>
        </div>

        <!-- Card 2: Tracked (White Card) -->
        <div class="group bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex justify-between items-start mb-6">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-widest">Tracked</p>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-5xl font-bold text-gray-900 mb-4 tabular-nums">{{ $totalTime ?? 0 }}h</h3>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-[10px] font-bold text-rose-500 uppercase tracking-wider">
                <span class="text-rose-600">-6</span> hours
            </div>
        </div>

        <!-- Card 3: Efficiency (White Card) -->
        <div class="group bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm transition-all duration-300 hover:shadow-md">
            <div class="flex justify-between items-start mb-6">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider tracking-widest">Efficiency</p>
                <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <h3 class="text-5xl font-bold text-gray-900 mb-4 tabular-nums">93%</h3>
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-[10px] font-bold text-emerald-600 uppercase tracking-wider">
                12% <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            </div>
        </div>

        <!-- Card 4: Team/Profile (Simplified for now) -->
        <div class="group bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="relative mb-3">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'User' }}&background=10B981&color=fff" class="w-16 h-16 rounded-full border-4 border-gray-50 shadow-sm" alt="Avatar">
                <div class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full"></div>
            </div>
            <h4 class="text-lg font-bold text-gray-900">{{ auth()->user()->name ?? 'User' }}</h4>
            <p class="text-xs text-gray-400 mb-4">{{ auth()->user()->email ?? 'user@example.com' }}</p>
            <div class="flex gap-2">
                <button class="p-2 rounded-full bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></button>
                <button class="p-2 rounded-full bg-gray-50 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></button>
            </div>
        </div>
    </div>
                <!-- Main Grid Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left: Line Chart (70%) -->
                    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold text-white">Weekly Productivity</h2>
                            <form method="get" class="inline-block">
                                <label for="dash-week" class="sr-only">Chart week</label>
                                <select id="dash-week" name="week" onchange="this.form.submit()" class="bg-slate-800 border border-slate-700 text-slate-300 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 p-2">
                                    @for($w = 0; $w <= 12; $w++)
                                        <option value="{{ $w }}" @selected(($weekOffset ?? 0) == $w)>
                                            {{ $w === 0 ? 'This week' : ($w === 1 ? 'Last week' : $w.' weeks ago') }}
                                        </option>
                                    @endfor
                                </select>
                            </form>
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
                            @forelse($topTaskProgress as $taskProg)
                                <div class="flex justify-between items-center text-sm @if(!$loop->first) pt-2 @endif">
                                    <span class="text-slate-400">{{ $taskProg['title'] }}</span>
                                    <span class="text-white font-medium">{{ $taskProg['hours'] }}h</span>
                                </div>
                                <div class="w-full bg-slate-800 rounded-full h-1.5">
                                    <div class="bg-indigo-500 h-1.5 rounded-full transition-all duration-700" style="width: {{ $taskProg['percentage'] }}%"></div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 italic">No time tracked on tasks yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Bottom Section: Recent Activity & Insight -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-white mb-6">Recent Activity</h2>
                        <div class="space-y-6">
                            @forelse($recentActivities as $activity)
                                <div class="flex gap-4">
                                    @if($activity['type'] === 'completed')
                                        <div class="w-10 h-10 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 border border-emerald-500/20">
                                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($activity['type'] === 'focus')
                                        <div class="w-10 h-10 rounded-full bg-purple-500/10 flex items-center justify-center shrink-0 border border-purple-500/20">
                                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-indigo-500/10 flex items-center justify-center shrink-0 border border-indigo-500/20">
                                            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 {{ !$loop->last ? 'pb-6 border-b border-slate-800/60' : '' }}">
                                        @if($activity['type'] === 'completed')
                                            <h4 class="text-sm font-medium text-slate-200">Completed <span class="text-white">'{{ $activity['title'] }}'</span></h4>
                                        @elseif($activity['type'] === 'focus')
                                            <h4 class="text-sm font-medium text-slate-200">Focus session on <span class="text-white">'{{ $activity['title'] }}'</span> <span class="text-slate-500">({{ $activity['duration_label'] ?? '' }})</span></h4>
                                        @else
                                            <h4 class="text-sm font-medium text-slate-200">Created task <span class="text-white">'{{ $activity['title'] }}'</span></h4>
                                        @endif
                                        <p class="text-xs text-slate-500 mt-1">{{ $activity['time_human'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-8 text-center">
                                    <svg class="w-12 h-12 text-slate-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <p class="text-sm text-slate-500">No recent activity yet</p>
                                    <p class="text-xs text-slate-600 mt-1">Create a task or start a focus session to get started</p>
                                </div>
                            @endforelse
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
                            <h2 class="text-lg font-semibold text-white">Insight</h2>
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-center relative z-10">
                            <p class="text-slate-300 leading-relaxed text-lg mb-6">
                                {{ $focusInsight ?? 'Use Time Tracking for focus blocks and Tasks to organize what you work on next.' }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('analytics.index') }}" class="text-indigo-400 text-sm font-medium hover:text-indigo-300 inline-flex items-center gap-1 transition-colors">
                                    Open analytics
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
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
                        labels: @json($chartLabels),
                        datasets: [{
                            label: 'Focus Hours',
                            data: @json($chartData),
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
