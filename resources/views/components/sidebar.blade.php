<!-- Sidebar (Desktop & Mobile) -->
<!-- Sidebar (Desktop & Mobile) -->
<aside class="absolute inset-y-0 left-0 z-50 transform bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 ease-in-out lg:static lg:translate-x-0"
       :class="{
           'translate-x-0': sidebarOpen, 
           '-translate-x-full': !sidebarOpen,
           'w-64': !sidebarCollapsed,
           'lg:w-20': sidebarCollapsed
       }">
    
    <div class="flex items-center justify-between h-20 px-6 border-b border-gray-100 dark:border-gray-800" :class="sidebarCollapsed ? 'lg:px-0 lg:justify-center' : ''">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-600/20 shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="text-lg font-bold tracking-tight text-gray-900 dark:text-white" x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">Analyzer</span>
        </div>
        
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="py-6 px-3 space-y-1 overflow-y-auto">
        <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 transition-all duration-300" :class="sidebarCollapsed ? 'lg:opacity-0 lg:h-0 lg:mb-0' : ''">Menu</p>
        
        @php
            $links = [
                ['route' => 'dashboard', 'pattern' => 'dashboard', 'label' => 'Home', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>'],
                ['route' => 'tasks.index', 'pattern' => 'tasks.*', 'label' => 'Tasks', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>'],
                ['route' => 'planner.index', 'pattern' => 'planner.*', 'label' => 'Planner', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'],
                ['route' => 'time.index', 'pattern' => 'time.*', 'label' => 'Time Tracking', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'],
                ['route' => 'analytics.index', 'pattern' => 'analytics.*', 'label' => 'Analytics', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>'],
                ['route' => 'settings', 'pattern' => 'settings*', 'label' => 'Settings', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>'],
            ];
        @endphp

        @foreach($links as $link)
            <a href="{{ route($link['route']) }}" 
               class="flex items-center gap-3 px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 {{ request()->routeIs($link['pattern']) ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-gray-100' }}"
               :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
               title="{{ $link['label'] }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $link['icon'] !!}</svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0 whitespace-nowrap overflow-hidden">{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>


    <div class="absolute bottom-0 w-full p-6 border-t border-gray-100 dark:border-gray-800" :class="sidebarCollapsed ? 'lg:px-0 lg:flex lg:flex-col lg:items-center lg:gap-4' : ''">
        <!-- Collapse Toggle (Desktop) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="hidden lg:flex items-center gap-4 w-full px-4 py-3 text-[10px] font-bold uppercase tracking-widest rounded-2xl text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 border border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-all duration-300 mb-2"
                :class="sidebarCollapsed ? 'lg:justify-center lg:px-0 lg:mb-0' : ''"
                title="Toggle Sidebar">
            <svg class="w-5 h-5 shrink-0 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
            <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">Collapse</span>
        </button>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="w-full flex items-center gap-4 px-4 py-3 text-[10px] font-bold uppercase tracking-widest rounded-2xl text-gray-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 hover:text-rose-600 dark:hover:text-rose-400 border border-transparent hover:border-rose-100 dark:hover:border-rose-500/20 transition-all duration-300"
                    :class="sidebarCollapsed ? 'lg:justify-center lg:px-0' : ''"
                    title="Sign Out">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                <span x-show="!sidebarCollapsed" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0">Sign Out</span>
            </button>
        </form>
    </div>
</aside>

