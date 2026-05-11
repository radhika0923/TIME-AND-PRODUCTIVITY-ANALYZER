<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tasks - Time & Productivity Analyzer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false, addModalOpen: false, editModalOpen: false, taskToEdit: null }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
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
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Tasks
                </a>
                <a href="{{ route('time.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Time Tracking
                </a>
                <a href="{{ route('analytics.index') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-all duration-200">
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
                        <input type="text" placeholder="Search tasks..." class="w-80 bg-slate-800/50 border border-slate-700/50 text-slate-200 text-sm rounded-full pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500">
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
                            </div>
                        </button>

                        <div x-show="open" x-cloak class="absolute right-0 mt-3 w-48 bg-slate-800 rounded-2xl shadow-xl shadow-black/50 border border-slate-700 py-2 z-50">
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
                
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl" role="alert">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-400 hover:text-emerald-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif

                <!-- Header & Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <h1 class="text-3xl font-semibold tracking-tight text-white">Tasks</h1>
                    <button @click="addModalOpen = true" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Task
                    </button>
                </div>

                <!-- Filters -->
                <div class="flex items-center gap-2 border-b border-slate-800 pb-px">
                    <a href="{{ route('tasks.index') }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ !request()->has('filter') || request()->filter == 'all' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                        All
                    </a>
                    <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->filter == 'pending' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                        Pending
                    </a>
                    <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->filter == 'completed' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                        Completed
                    </a>
                </div>

                <!-- Category Filter Pills -->
                <div class="flex items-center gap-3 flex-wrap">
                    <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Categories:</span>
                    <a href="{{ route('tasks.index', array_merge(request()->except('category'), [])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ !request()->has('category') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                        All
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 {{ request('category') == $cat->id ? 'border border-indigo-500/20 bg-indigo-500/10 text-indigo-400' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $cat->color }}"></span>
                            {{ $cat->name }}
                            <span class="text-slate-600">({{ $cat->tasks_count }})</span>
                        </a>
                    @endforeach
                    <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => 'uncategorized'])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request('category') === 'uncategorized' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                        Uncategorized
                    </a>
                </div>

                <!-- Tasks List -->
                @if($tasks->isEmpty())
                    <div class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed border-slate-800 rounded-2xl">
                        <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500 mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <h3 class="text-lg font-medium text-slate-200 mb-1">No tasks found</h3>
                        <p class="text-sm text-slate-500 mb-6">Get started by creating a new task.</p>
                        <button @click="addModalOpen = true" class="px-4 py-2 text-sm font-medium text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 rounded-xl transition-colors">
                            + Add your first task
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($tasks as $task)
                            <div class="group flex flex-col sm:flex-row gap-4 p-5 bg-slate-900 border {{ $task->status === 'completed' ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-slate-800 hover:border-indigo-500/30' }} rounded-2xl transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5">
                                
                                <div class="flex items-start gap-4 flex-1 min-w-0">
                                    <!-- Status Toggle -->
                                    <form method="POST" action="{{ route('tasks.complete', $task->id) }}" class="shrink-0 mt-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $task->status === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-600 hover:border-indigo-500 text-transparent hover:text-indigo-500' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-medium truncate mb-1 {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-200' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->description)
                                            <p class="text-sm text-slate-500 line-clamp-2">{{ $task->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-3 text-xs font-medium flex-wrap">
                                            @if($task->category)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md" style="background-color: {{ $task->category->color }}15; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}33;">
                                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->category->color }}"></span>
                                                    {{ $task->category->name }}
                                                </span>
                                            @endif
                                            @if($task->status === 'completed')
                                                <span class="inline-flex items-center gap-1 text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-md">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-md">
                                                    Pending
                                                </span>
                                            @endif
                                            <span class="text-slate-600 flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $task->created_at->format('M d, Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 sm:opacity-0 group-hover:opacity-100 transition-opacity justify-end sm:justify-start">
                                    <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}' }; editModalOpen = true" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors" title="Edit Task">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    
                                    <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete Task">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- Add Task Slide Panel -->
    <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <!-- Backdrop -->
            <div x-show="addModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
            
            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <!-- Panel -->
                <div x-show="addModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-slate-900 shadow-2xl border-l border-slate-800">
                        <div class="px-6 py-6 border-b border-slate-800 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white">Create New Task</h2>
                            <button @click="addModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto px-6 py-6">
                            <form id="addTaskForm" method="POST" action="{{ route('tasks.store') }}" class="space-y-5">
                                @csrf
                                <div>
                                    <label for="title" class="block text-sm font-medium text-slate-300 mb-1.5">Task Title <span class="text-red-400">*</span></label>
                                    <input type="text" name="title" id="title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="e.g., Design new landing page">
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-slate-300 mb-1.5">Description (Optional)</label>
                                    <textarea name="description" id="description" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="Add any extra details here..."></textarea>
                                </div>

                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-slate-300 mb-1.5">Category</label>
                                    <select name="category_id" id="category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                        <option value="">No Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" style="color: {{ $cat->color }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>

                            <!-- Quick Create Category (outside the task form) -->
                            <div x-data="{ showNewCat: false }" class="border-t border-slate-800 pt-4 mt-5">
                                <button type="button" @click="showNewCat = !showNewCat" class="text-xs text-indigo-400 hover:text-indigo-300 flex items-center gap-1 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Create new category
                                </button>
                                <div x-show="showNewCat" x-cloak x-transition class="mt-3 p-3 bg-slate-800/50 rounded-xl border border-slate-700/50 space-y-3">
                                    <form method="POST" action="{{ route('categories.store') }}" class="space-y-3">
                                        @csrf
                                        <input type="text" name="name" required placeholder="Category name" class="w-full bg-slate-950 border border-slate-800 rounded-lg px-3 py-2 text-sm text-slate-200 focus:outline-none focus:border-indigo-500 transition-all placeholder-slate-600">
                                        <div class="flex items-center gap-3">
                                            <label class="text-xs text-slate-400">Color:</label>
                                            <input type="color" name="color" value="#6366f1" class="w-8 h-8 rounded-lg border border-slate-700 cursor-pointer bg-transparent">
                                            <button type="submit" class="ml-auto px-3 py-1.5 text-xs font-medium text-white bg-indigo-500 rounded-lg hover:bg-indigo-600 transition-colors">Add</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-end gap-3 bg-slate-900/50">
                            <button @click="addModalOpen = false" type="button" class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button form="addTaskForm" type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all">
                                Save Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Task Slide Panel -->
    <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 overflow-hidden">
            <div x-show="editModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
            
            <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                <div x-show="editModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-slate-900 shadow-2xl border-l border-slate-800">
                        <div class="px-6 py-6 border-b border-slate-800 flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white">Edit Task</h2>
                            <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto px-6 py-6" x-data="{
                            get updateUrl() { return taskToEdit ? '/tasks/' + taskToEdit.id : ''; }
                        }">
                            <form id="editTaskForm" method="POST" :action="updateUrl" class="space-y-5">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label for="edit_title" class="block text-sm font-medium text-slate-300 mb-1.5">Task Title <span class="text-red-400">*</span></label>
                                    <input type="text" name="title" id="edit_title" x-model="taskToEdit.title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600">
                                </div>
                                
                                <div>
                                    <label for="edit_description" class="block text-sm font-medium text-slate-300 mb-1.5">Description (Optional)</label>
                                    <textarea name="description" id="edit_description" x-model="taskToEdit.description" rows="4" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600"></textarea>
                                </div>

                                <div>
                                    <label for="edit_category_id" class="block text-sm font-medium text-slate-300 mb-1.5">Category</label>
                                    <select name="category_id" id="edit_category_id" x-model="taskToEdit.category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all">
                                        <option value="">No Category</option>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        
                        <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-end gap-3 bg-slate-900/50">
                            <button @click="editModalOpen = false" type="button" class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                Cancel
                            </button>
                            <button form="editTaskForm" type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all">
                                Update Task
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
