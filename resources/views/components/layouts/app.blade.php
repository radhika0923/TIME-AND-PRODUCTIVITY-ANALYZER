<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Time & Productivity Analyzer' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @isset($scripts)
        {{ $scripts }}
    @endisset
    @isset($styles)
        {{ $styles }}
    @endisset
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.3/dist/confetti.browser.min.js"></script>
    <style>
        :root {
            --bg-main: #F3F4F6; /* Gray 100 */
            --bg-sidebar: #FFFFFF;
            --bg-card: #FFFFFF;
            --border-color: #E5E7EB; /* Gray 200 */
            --accent-green: #10B981; /* Emerald 500 */
            --accent-green-dark: #065F46; /* Emerald 800 */
        }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-main);
            color: #1F2937; /* Gray 800 */
        }
        [x-cloak] { display: none !important; }
        
        .premium-card {
            background: var(--bg-card);
            border-radius: 1.5rem;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        .premium-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }
        
        .sidebar-item-active {
            background-color: #F0FDF4; /* Emerald 50 */
            color: var(--accent-green);
            border-right: 4px solid var(--accent-green);
        }
        .premium-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5);
            transform: translateY(-2px);
        }
        
        .shimmer {
            position: relative;
            overflow: hidden;
        }
        .shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(255, 255, 255, 0.03),
                transparent
            );
            transform: rotate(45deg);
            transition: all 0.5s;
            pointer-events: none;
        }
        .group:hover .shimmer::after {
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { transform: translate(-50%, -50%) rotate(45deg); }
            100% { transform: translate(50%, 50%) rotate(45deg); }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('confetti'))
                confetti({
                    particleCount: 150,
                    spread: 70,
                    origin: { y: 0.6 },
                    colors: ['#6366f1', '#a855f7', '#10b981']
                });
            @endif
        });
    </script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased selection:bg-emerald-500 selection:text-white" x-data="{ sidebarOpen: false, profileOpen: false }">

    <div class="flex h-screen overflow-hidden" x-data="{ commandPaletteOpen: false }" @keydown.window.ctrl.k.prevent="commandPaletteOpen = true" @keydown.window.slash.prevent="if (document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') commandPaletteOpen = true">
        
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50/50">
            
            <x-navbar />

            <!-- Scrollable View -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-8 space-y-8 scroll-smooth">
                {{ $slot }}
            </div>
        </main>

        <!-- Command Palette Modal -->
        <div x-show="commandPaletteOpen" x-cloak class="fixed inset-0 z-[60] overflow-y-auto p-4 sm:p-6 md:p-20" role="dialog" aria-modal="true">
            <div x-show="commandPaletteOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/20 backdrop-blur-sm transition-opacity" @click="commandPaletteOpen = false"></div>

            <div x-show="commandPaletteOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative z-10 mx-auto max-w-xl transform-gpu divide-y divide-gray-100 overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                <div class="relative">
                    <svg class="pointer-events-none absolute left-4 top-3.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                    <input type="text" class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Search for anything... (Type 'help' for commands)" role="combobox" aria-expanded="false" aria-controls="options" autofocus @keydown.escape="commandPaletteOpen = false">
                </div>

                <ul class="max-h-96 scroll-py-3 overflow-y-auto p-3" id="options" role="listbox">
                    <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" role="option">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 w-full">
                            <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                            <span class="flex-auto truncate text-sm font-medium">Home</span>
                        </a>
                    </li>
                    <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" role="option">
                        <a href="{{ route('tasks.index') }}" class="flex items-center gap-3 w-full">
                            <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            <span class="flex-auto truncate text-sm font-medium">Tasks</span>
                        </a>
                    </li>
                    <li class="group flex cursor-default select-none items-center rounded-xl px-3 py-2 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 transition-colors" role="option">
                        <a href="{{ route('time.index') }}" class="flex items-center gap-3 w-full">
                            <svg class="h-5 w-5 flex-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="flex-auto truncate text-sm font-medium">Time Tracking</span>
                        </a>
                    </li>
                </ul>

                <div class="flex items-center justify-between px-4 py-2.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest bg-gray-50/80 border-t border-gray-100">
                    <div class="flex items-center gap-4">
                        <span><kbd class="font-sans">Esc</kbd> to close</span>
                        <span><kbd class="font-sans">↵</kbd> to select</span>
                    </div>
                    <span>Logictry Style</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
