<!-- Top Navbar -->
<header class="h-20 px-6 flex items-center justify-between border-b border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900 z-40 sticky top-0 transition-colors">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden text-gray-400 hover:text-gray-600 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="relative hidden sm:block">
            <button @click="commandPaletteOpen = true" class="w-80 bg-gray-50 dark:bg-slate-800/50 border border-gray-200 dark:border-slate-700 text-gray-400 dark:text-slate-500 text-sm rounded-full pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all flex items-center justify-between group">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>Search or jump to...</span>
                </div>
                <div class="flex items-center gap-1 opacity-60 group-hover:opacity-100 transition-opacity">
                    <kbd class="px-1.5 py-0.5 text-[10px] font-bold bg-white dark:bg-slate-700 rounded border border-gray-200 dark:border-slate-600 text-gray-400">Ctrl</kbd>
                    <kbd class="px-1.5 py-0.5 text-[10px] font-bold bg-white dark:bg-slate-700 rounded border border-gray-200 dark:border-slate-600 text-gray-400">K</kbd>
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
                    <span class="absolute top-0 right-0 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 text-[9px] font-bold text-white">{{ $unreadCount }}</span>
                @endif
            </button>

            <div x-show="notificationsOpen" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 overflow-hidden z-50">
                <div class="p-4 border-b border-gray-100 dark:border-slate-800 flex justify-between items-center bg-gray-50/50 dark:bg-slate-800/50">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Notifications</h3>
                    @if($unreadCount > 0)
                        <span class="px-2 py-0.5 text-[10px] font-extrabold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 rounded-full border border-emerald-100 dark:border-emerald-500/20">{{ $unreadCount }} new</span>
                    @endif
                </div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse($unreadReminders as $notification)
                        <div class="p-4 border-b border-gray-100 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors relative">
                            <div class="flex justify-between items-start gap-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $notification->title }}</p>
                                <form action="{{ route('reminders.read', $notification->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="text-[10px] font-extrabold text-emerald-600 dark:text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-400 uppercase tracking-wider">Mark read</button>
                                </form>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 line-clamp-2 leading-relaxed">{{ $notification->message }}</p>
                            <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 mt-2 block flex items-center gap-1.5 uppercase tracking-tighter">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $notification->reminder_time->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <div class="p-8 text-center flex flex-col items-center justify-center gap-3">
                            <div class="p-3 bg-emerald-50 dark:bg-emerald-500/10 rounded-2xl text-emerald-600 dark:text-emerald-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">No new notifications</p>
                        </div>
                    @endforelse
                </div>
                <div class="p-3 bg-gray-50/50 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-800 text-center">
                    <a href="{{ route('reminders.index') }}" class="text-xs font-extrabold text-emerald-600 dark:text-emerald-500 hover:text-emerald-500 dark:hover:text-emerald-400 uppercase tracking-widest">View all reminders</a>
                </div>
            </div>
        </div>
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
                <div class="w-9 h-9 rounded-full bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-sm font-bold text-emerald-700 dark:text-emerald-500 shadow-sm border border-emerald-200 dark:border-emerald-500/20">
                    {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ auth()->user()->name ?? 'User Name' }}</p>
                    <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-500 uppercase tracking-wider">Pro Plan</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 dark:text-slate-600 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-3 w-48 bg-white dark:bg-slate-900 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-800 py-2 z-50">
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors">Profile</a>
                <a href="{{ route('settings') }}" class="block px-4 py-2 text-sm font-medium text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800 hover:text-gray-900 dark:hover:text-white transition-colors">Settings</a>
                <div class="h-px bg-gray-100 dark:bg-slate-800 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm font-bold text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors uppercase tracking-widest">Sign out</button>
                </form>
            </div>
        </div>
    </div>
</header>
