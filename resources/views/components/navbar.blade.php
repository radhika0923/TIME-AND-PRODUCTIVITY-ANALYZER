<!-- Top Navbar -->
<header class="h-20 px-6 flex items-center justify-between border-b border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900 z-40 sticky top-0 transition-colors">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="relative hidden sm:block">
            <button @click="commandPaletteOpen = true" class="w-80 bg-gray-50 border border-gray-200 text-gray-400 text-sm rounded-full pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Search or jump to...</span>
                </div>
                <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                    <kbd class="px-1.5 py-0.5 text-[10px] font-bold bg-white rounded border border-gray-200 text-gray-400">Ctrl</kbd>
                    <kbd class="px-1.5 py-0.5 text-[10px] font-bold bg-white rounded border border-gray-200 text-gray-400">K</kbd>
                </div>
            </button>
        </div>
    </div>

    <div class="flex items-center gap-5">
        @php
            $unreadReminders = auth()->check() ? auth()->user()->reminders()->where('reminder_time', '<=', now())->where('is_read', false)->orderBy('reminder_time', 'desc')->get() : collect();
            $unreadCount = $unreadReminders->count();
        @endphp
        <div x-data="{
            isDark: document.documentElement.classList.contains('dark'),
            toggleTheme() {
                this.isDark = !this.isDark;
                if (this.isDark) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
        }">
            <button @click="toggleTheme()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors mr-2">
                <!-- Sun Icon for Dark Mode (switch to light) -->
                <svg x-show="isDark" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <!-- Moon Icon for Light Mode (switch to dark) -->
                <svg x-show="!isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            </button>
        </div>

        <div class="relative" x-data="{ notificationsOpen: false }">
            <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false" class="relative text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                @if($unreadCount > 0)
                    <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 ring-2 ring-white dark:ring-gray-900 text-[9px] font-bold text-white">{{ $unreadCount }}</span>
                @endif
            </button>

            <div x-show="notificationsOpen" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden z-50">
                <div class="p-3 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                    @if($unreadCount > 0)
                        <span class="text-xs text-emerald-600">{{ $unreadCount }} unread</span>
                    @endif
                </div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse($unreadReminders as $notification)
                        <div class="p-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <form action="{{ route('reminders.read', $notification->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-xs text-emerald-600 hover:text-emerald-500">Mark read</button>
                                </form>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $notification->message }}</p>
                            <span class="text-[10px] text-gray-400 mt-2 block">{{ $notification->reminder_time->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="p-4 text-center text-sm text-gray-400">No new notifications</div>
                    @endforelse
                </div>
                <div class="p-2 bg-gray-50/50 border-t border-gray-100 text-center">
                    <a href="{{ route('reminders.index') }}" class="text-xs text-emerald-600 hover:text-emerald-500 font-medium">View all reminders</a>
                </div>
            </div>
        </div>
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-sm font-bold text-emerald-700 shadow-sm">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'User Name' }}</p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Pro Plan</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Profile</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors">Settings</a>
                <div class="h-px bg-gray-100 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-rose-500 hover:bg-rose-50 transition-colors">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
