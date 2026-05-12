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
            <button class="flex items-center gap-2 px-5 py-2.5 bg-gray-900 text-white rounded-2xl font-bold text-sm hover:bg-black transition-all shadow-lg shadow-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Focus Session
            </button>
            <a href="{{ route('tasks.index') }}" class="flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                New Task
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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

    </div>
                <!-- Main Grid Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left: Line Chart (70%) -->
                    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-bold text-gray-900">Weekly Productivity</h2>
                            <form method="get" class="inline-block">
                                <label for="dash-week" class="sr-only">Chart week</label>
                                <select id="dash-week" name="week" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-2">
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
                </div>

                <!-- Bottom Section: Recent Activity & Insight -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Recent Activity -->
                    <div class="lg:col-span-2 bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-gray-900 mb-6">Recent Activity</h2>
                        <div class="space-y-6">
                            @forelse($recentActivities as $activity)
                                <div class="flex gap-4">
                                    @if($activity['type'] === 'completed')
                                        <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                    @elseif($activity['type'] === 'focus')
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center shrink-0 border border-indigo-100">
                                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center shrink-0 border border-gray-100">
                                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                        </div>
                                    @endif
                                    <div class="flex-1 {{ !$loop->last ? 'pb-6 border-b border-gray-50' : '' }}">
                                        @if($activity['type'] === 'completed')
                                            <h4 class="text-sm font-semibold text-gray-900">Completed <span class="text-emerald-600">'{{ $activity['title'] }}'</span></h4>
                                        @elseif($activity['type'] === 'focus')
                                            <h4 class="text-sm font-semibold text-gray-900">Focused on <span class="text-indigo-600">'{{ $activity['title'] }}'</span> <span class="text-gray-400 font-normal">({{ $activity['duration_label'] ?? '' }})</span></h4>
                                        @else
                                            <h4 class="text-sm font-semibold text-gray-900">Created <span class="text-gray-600">'{{ $activity['title'] }}'</span></h4>
                                        @endif
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-1">{{ $activity['time_human'] }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-12 text-center bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">No activity</p>
                                    <p class="text-xs text-gray-400 mt-1">Activities will appear here as you work.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Productivity Insight -->
                    <div class="bg-gradient-to-br from-gray-900 to-black rounded-[2rem] p-6 flex flex-col relative overflow-hidden shadow-xl shadow-gray-200">
                        <!-- Decorative glow -->
                        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-emerald-500/10 blur-3xl rounded-full"></div>
                        
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div class="p-2 bg-white/10 rounded-lg text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <h2 class="text-lg font-bold text-white">Insight</h2>
                        </div>
                        
                        <div class="flex-1 flex flex-col justify-center relative z-10">
                            <p class="text-gray-300 leading-relaxed text-lg mb-6">
                                {{ $focusInsight ?? 'Use Time Tracking for focus blocks and Tasks to organize what you work on next.' }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('analytics.index') }}" class="text-emerald-400 text-sm font-bold hover:text-emerald-300 inline-flex items-center gap-1 transition-colors uppercase tracking-widest">
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
