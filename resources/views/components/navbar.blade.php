<!-- Top Navbar -->
<header class="h-20 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-900/50 backdrop-blur-xl z-40 sticky top-0">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden text-slate-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="relative hidden sm:block">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" placeholder="Search tasks, sessions..." class="w-80 bg-slate-800/50 border border-slate-700/50 text-slate-200 text-sm rounded-full pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500">
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
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">Profile</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white transition-colors">Settings</a>
                <div class="h-px bg-slate-700 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-red-500/10 transition-colors">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
