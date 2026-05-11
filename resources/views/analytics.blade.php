<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics - Time & Productivity Analyzer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.3/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false, profileOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar (Desktop & Mobile) -->
        <aside class="absolute inset-y-0 left-0 z-50 w-64 transform bg-slate-900/50 backdrop-blur-xl border-r border-slate-800 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            
            <div class="flex items-center justify-between h-20 px-6 border-b border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="text-lg font-semibold tracking-tight text-white">Analyzer</span>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <nav class="p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Tasks
                </a>
                <a href="{{ route('time.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Time Tracking
                </a>
                <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    Analytics
                </a>
                <a href="{{ route('reminders.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    Reminders
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
            </nav>

            <div class="absolute bottom-0 w-full p-4 border-t border-slate-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
            
            <!-- Top Navbar -->
            <header class="h-20 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-900/50 backdrop-blur-xl z-40 sticky top-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <div class="relative hidden sm:block">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        <input type="text" placeholder="Search analytics..." class="w-80 bg-slate-800/50 border border-slate-700/50 text-slate-200 text-sm rounded-full pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500">
                    </div>
                </div>

                <div class="flex items-center gap-5">
                    @php
                        $unreadReminders = auth()->check() ? auth()->user()->reminders()->where('reminder_time', '<=', now())->where('is_read', false)->orderBy('reminder_time', 'desc')->get() : collect();
                        $unreadCount = $unreadReminders->count();
                    @endphp
                    <div class="relative" x-data="{ notificationsOpen: false }">
                        <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false" class="relative text-slate-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            @if($unreadCount > 0)
                                <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 ring-2 ring-slate-900 text-[9px] font-bold text-white">{{ $unreadCount }}</span>
                            @endif
                        </button>

                        <div x-show="notificationsOpen" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-80 bg-slate-800 rounded-2xl shadow-xl shadow-black/50 border border-slate-700 overflow-hidden z-50">
                            <div class="p-3 border-b border-slate-700 flex justify-between items-center bg-slate-800/80">
                                <h3 class="text-sm font-semibold text-white">Notifications</h3>
                                @if($unreadCount > 0)
                                    <span class="text-xs text-indigo-400">{{ $unreadCount }} unread</span>
                                @endif
                            </div>
                            <div class="max-h-80 overflow-y-auto">
                                @forelse($unreadReminders as $notification)
                                    <div class="p-3 border-b border-slate-700 hover:bg-slate-700/50 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm font-medium text-white">{{ $notification->title }}</p>
                                            <form action="{{ route('reminders.read', $notification->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs text-indigo-400 hover:text-indigo-300">Mark read</button>
                                            </form>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $notification->message }}</p>
                                        <span class="text-[10px] text-slate-500 mt-2 block">{{ $notification->reminder_time->diffForHumans() }}</span>
                                    </div>
                                @empty
                                    <div class="p-4 text-center text-sm text-slate-500">No new notifications</div>
                                @endforelse
                            </div>
                            <div class="p-2 bg-slate-800/80 border-t border-slate-700 text-center">
                                <a href="{{ route('reminders.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-medium">View all reminders</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-sm font-medium text-white shadow-md shadow-indigo-500/20">
                                {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-sm font-medium text-slate-200">{{ auth()->user()->name ?? 'User Name' }}</p>
                                <p class="text-xs text-slate-500">Pro Plan</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-48 bg-slate-800 rounded-2xl shadow-xl shadow-black/50 border border-slate-700 py-2 z-50">
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">Profile</a>
                            <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">Billing</a>
                            <div class="h-px bg-slate-700 my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">Sign out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable View -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-8 space-y-8 scroll-smooth">
                
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

            </div>
        </main>
    </div>

    <!-- Chart.js Init -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart Data from Backend
            const chartLabels = {!! json_encode($chartLabels) !!};
            const weeklyTimeData = {!! json_encode($weeklyTimeData) !!};
            const completedTasksPerDay = {!! json_encode($completedTasksPerDay) !!};

            // Focus Time Line Chart
            const ctxFocus = document.getElementById('focusTimeChart').getContext('2d');
            let gradientFocus = ctxFocus.createLinearGradient(0, 0, 0, 300);
            gradientFocus.addColorStop(0, 'rgba(99, 102, 241, 0.2)'); // Indigo-500
            gradientFocus.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxFocus, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Focus Hours',
                        data: weeklyTimeData,
                        borderColor: '#6366f1', // Indigo-500
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
                                color: '#64748b'
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

            // Completed Tasks Bar Chart
            const ctxTasks = document.getElementById('completedTasksChart').getContext('2d');
            
            new Chart(ctxTasks, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Tasks Completed',
                        data: completedTasksPerDay,
                        backgroundColor: '#10b981', // Emerald-500
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
                                label: function(context) {
                                    return context.parsed.y + ' tasks';
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
                                stepSize: 1
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
</body>
</html>
