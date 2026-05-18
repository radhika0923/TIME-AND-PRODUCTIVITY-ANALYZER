<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zenovo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#fcfcfc] text-slate-800 antialiased overflow-x-hidden relative">
    <!-- Subtle Background Grid -->
    <div class="absolute inset-0 z-0 opacity-[0.04] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 32px 32px;"></div>
    <!-- Navbar -->
    <nav class="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto relative z-20">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-slate-900 text-white rounded flex items-center justify-center font-bold text-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="font-bold text-lg hidden sm:block">Zenovo</span>
        </div>
        <div class="flex items-center gap-6 text-sm font-medium">
            @auth
                <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-900 rounded-lg hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-sm"></div>
                    Dashboard
                </a>
            @else
                <a href="{{ route('login.form') }}" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-900 rounded-lg hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                    <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-sm"></div>
                    Log in
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="pt-16 pb-12 text-center max-w-5xl mx-auto px-4 relative z-10 flex flex-col justify-center min-h-[65vh]">

        <!-- Headline -->
        <h1 class="text-5xl md:text-[5.5rem] font-bold tracking-tight text-[#1a1a1a] mb-6 max-w-5xl mx-auto leading-[1.1]">
            Master your time, <br />
            <span class="text-[#00d26a]">maximize your productivity.</span>
        </h1>

        <!-- Subheading -->
        <p class="text-lg text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
            Track your time, manage your tasks, and unlock actionable insights with AI-powered analytics in a lightning-fast experience.
        </p>

        <!-- CTA Buttons -->
        <div class="flex items-center justify-center gap-4 mb-16">
            <a href="#" class="px-6 py-3.5 bg-white border border-slate-200 rounded-xl font-medium text-slate-900 hover:bg-slate-50 transition-colors shadow-sm flex items-center gap-2">
                <div class="w-4 h-4 bg-gradient-to-r from-blue-500 to-purple-500 rounded-sm"></div>
                Preview
            </a>
            @auth
                <a href="{{ url('/dashboard') }}" class="px-6 py-3.5 bg-[#00d26a] text-white rounded-xl font-medium hover:bg-[#00b85c] transition-colors shadow-sm shadow-[#00d26a]/20 flex items-center gap-2">
                    Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @endauth
        </div>

        <!-- Features Bar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 border-y border-slate-200 py-12 mb-8 max-w-5xl mx-auto px-6 relative z-10 bg-[#fcfcfc]">
            <div class="flex items-center gap-4 text-left">
                <div class="w-12 h-12 rounded-2xl bg-[#e6faef] text-[#00d26a] flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#1a1a1a] text-sm">Precision Time Tracking</h3>
                    <p class="text-slate-500 text-xs mt-1 font-medium">Track every minute with ease.</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-left md:border-l md:border-r border-slate-200 md:px-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#1a1a1a] text-sm">Smart Task Management</h3>
                    <p class="text-slate-500 text-xs mt-1 font-medium">Organize and prioritize effectively.</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-left md:pl-8">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-[#1a1a1a] text-sm">AI-Powered Analytics</h3>
                    <p class="text-slate-500 text-xs mt-1 font-medium">Unlock insights & boost efficiency.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Dark Section Container -->
    <div class="relative bg-[#1c1c1c] text-white pt-20 pb-24 mt-8 md:rounded-t-[3rem] px-4 mx-0 md:mx-4 overflow-hidden z-20">
        
        <!-- Grid Pattern Background -->
        <div class="absolute inset-0 opacity-10" style="background-image: linear-gradient(#ffffff 1px, transparent 1px), linear-gradient(90deg, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <!-- Statistics Content -->
        <div class="max-w-5xl mx-auto text-center md:text-left px-4 relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-white/10 bg-white/5 text-xs font-semibold text-white/70 mb-8">
                <svg class="w-4 h-4 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Statistics
            </div>
            
            <div class="flex flex-col md:flex-row items-end justify-between gap-12 text-left mb-16">
                <h2 class="text-4xl md:text-[2.75rem] font-bold leading-tight max-w-xl text-white">
                    Realize how comprehensive Zenovo is!
                </h2>
                <div class="max-w-sm mb-2">
                    <p class="text-[#a1a1aa] text-base mb-4 font-medium leading-relaxed">
                        Here's a closer look at the numbers that define our tool. See how we measure up!
                    </p>
                    <a href="#" class="text-[#84cc16] text-sm font-semibold inline-flex items-center gap-1 hover:text-[#a3e635] transition-colors">
                        Constantly expanding <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 border-t border-white/10 rounded-2xl overflow-hidden bg-white/5">
                <div class="p-8 text-center border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 mb-6 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <p class="text-[#a1a1aa] text-xs font-semibold mb-2">Active Users</p>
                    <p class="text-3xl font-bold text-white">10k+</p>
                </div>
                <div class="p-8 text-center border-b md:border-b-0 md:border-r border-white/10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 mb-6 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-[#a1a1aa] text-xs font-semibold mb-2">Hours Tracked</p>
                    <p class="text-3xl font-bold text-white">1M+</p>
                </div>
                <div class="p-8 text-center border-r border-white/10">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 mb-6 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </div>
                    <p class="text-[#a1a1aa] text-xs font-semibold mb-2">Widgets & Views</p>
                    <p class="text-3xl font-bold text-white">50+</p>
                </div>
                <div class="p-8 text-center">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white/60 mb-6 mx-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <p class="text-[#a1a1aa] text-xs font-semibold mb-2">Productivity Boost</p>
                    <p class="text-3xl font-bold text-white">99%</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Next Section (Light green/grey as in bottom of image) -->
    <div class="bg-[#f2f7f4] pt-24 pb-32">
        <div class="max-w-5xl mx-auto px-4 text-center">
             <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 bg-white text-sm font-semibold text-slate-600 shadow-sm mb-8">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                How it works?
            </div>
            <h2 class="text-4xl font-bold text-[#1a1a1a] mb-4">Significant reasons why we</h2>
            <p class="text-[#00d26a] font-medium text-lg flex items-center justify-center gap-2">
                Top tier design quality
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </p>
        </div>
    </div>
</body>
</html>