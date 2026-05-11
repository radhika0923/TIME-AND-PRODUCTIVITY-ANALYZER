<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Time & Productivity Analyzer' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @isset($scripts)
        {{ $scripts }}
    @endisset
    @isset($styles)
        {{ $styles }}
    @endisset
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900 text-slate-200 antialiased selection:bg-indigo-500 selection:text-white" x-data="{ sidebarOpen: false, profileOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-950">
            
            <x-navbar />

            <!-- Scrollable View -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-8 space-y-8 scroll-smooth">
                {{ $slot }}
            </div>
        </main>
    </div>

</body>
</html>
