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

        <x-command-palette />
    </div>
</body>
</html>
