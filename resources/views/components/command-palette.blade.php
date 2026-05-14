<!-- Command Palette Modal -->
<div x-show="commandPaletteOpen" x-cloak 
     class="fixed inset-0 z-[60] overflow-y-auto p-4 sm:p-6 md:p-20" role="dialog" aria-modal="true"
     @keydown.window.escape="commandPaletteOpen = false">
     
    <div x-show="commandPaletteOpen" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-gray-500/20 dark:bg-black/60 backdrop-blur-sm transition-opacity" @click="commandPaletteOpen = false"></div>

    <div x-show="commandPaletteOpen" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" 
         class="relative z-10 mx-auto max-w-xl transform-gpu divide-y divide-gray-100 dark:divide-slate-800 overflow-hidden rounded-2xl bg-white dark:bg-slate-900 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
        
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
            <input type="text" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 dark:text-white placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search for anything... (Type 'help' for commands)" role="combobox" aria-expanded="false" aria-controls="options" autofocus>
        </div>

        <ul class="max-h-96 scroll-py-3 overflow-y-auto p-3" id="options" role="listbox">
            <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors" role="option">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                    <span class="flex-auto truncate text-sm font-medium">Home</span>
                </a>
            </li>
            <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors" role="option">
                <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 w-full">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    <span class="flex-auto truncate text-sm font-medium">Tasks</span>
                </a>
            </li>
            <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 dark:text-slate-400 hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-500 transition-colors" role="option">
                <a href="{{ route('time.index') }}" class="flex items-center gap-3 w-full">
                    <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="flex-auto truncate text-sm font-medium">Time Tracking</span>
                </a>
            </li>
        </ul>

        <div class="flex items-center justify-between px-4 py-2.5 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest bg-gray-50/80 dark:bg-slate-800/50 border-t border-gray-100 dark:border-slate-800">
            <div class="flex items-center gap-4">
                <span><kbd class="font-sans">Esc</kbd> to close</span>
                <span><kbd class="font-sans">↵</kbd> to select</span>
            </div>
        </div>
    </div>
</div>
