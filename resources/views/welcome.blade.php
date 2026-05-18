<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Zenovo - Master your time, maximize your productivity</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        .heading-font {
            font-family: 'Space Grotesk', sans-serif;
        }
    </style>
</head>
<body class="bg-[#030712] text-slate-100 antialiased overflow-x-hidden relative min-h-screen selection:bg-emerald-500 selection:text-slate-950">
    
    <!-- Glowing Orbs & Mesh Grid Background -->
    <div class="absolute inset-0 z-0 pointer-events-none overflow-hidden">
        <!-- Mesh Grid -->
        <div class="absolute inset-0 opacity-40" style="background-size: 50px 50px; background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);"></div>
        
        <!-- Large Glowing Spots -->
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[140px]"></div>
        <div class="absolute top-[20%] right-[-10%] w-[500px] h-[500px] bg-emerald-500/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-[600px] h-[600px] bg-emerald-500/5 rounded-full blur-[140px]"></div>
    </div>

    <!-- Sticky Glassmorphic Navbar -->
    <nav class="sticky top-0 z-50 backdrop-blur-md bg-[#030712]/75 border-b border-slate-900/80 px-6 py-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-500 to-[#00d26a] flex items-center justify-center shadow-lg shadow-emerald-500/20">
                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="font-bold text-xl heading-font tracking-tight text-white">Zenovo</span>
            </div>
            
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10 text-sm flex items-center gap-2 active:scale-95">
                        Go to Dashboard
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                    </a>
                @else
                    <a href="{{ route('login.form') }}" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 border border-slate-800 text-white font-semibold rounded-xl transition-all text-sm active:scale-95">
                        Log in
                    </a>
                    <a href="{{ route('register.form') }}" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-lg shadow-emerald-500/10 text-sm active:scale-95 hidden sm:block">
                        Sign up free
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Hero Section (Split Layout) -->
    <main class="relative z-10 max-w-7xl mx-auto px-6 pt-16 pb-24 grid grid-cols-1 lg:grid-cols-12 gap-16 items-center min-h-[80vh]">
        
        <!-- Left Side: Hero Text & CTA -->
        <div class="lg:col-span-7 text-left space-y-8">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-emerald-500/20 bg-emerald-950/20 text-xs font-bold text-emerald-400 tracking-wide uppercase">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Next-Gen Time Intelligence
            </div>
            
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[4rem] font-extrabold heading-font tracking-tight text-white leading-[1.05]">
                Master your time, <br />
                <span class="bg-gradient-to-r from-emerald-400 via-green-500 to-[#00d26a] bg-clip-text text-transparent">maximize productivity.</span>
            </h1>
            
            <p class="text-slate-400 text-lg md:text-xl font-medium leading-relaxed max-w-2xl">
                Zenovo elevates your flow state. Seamlessly track time, structure high-impact tasks, and leverage deep AI insights to optimize your daily focus.
            </p>
            
            <!-- CTAs -->
            <div class="flex flex-wrap items-center gap-4 pt-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-7 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-xl shadow-emerald-500/20 text-base active:scale-95">
                        Access Dashboard
                    </a>
                @else
                    <a href="{{ route('register.form') }}" class="px-7 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-xl shadow-emerald-500/20 text-base active:scale-95">
                        Get Started Free
                    </a>
                    <a href="{{ route('login.form') }}" class="px-7 py-4 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-300 hover:text-white font-bold rounded-xl transition-all text-base active:scale-95">
                        Try Live Demo
                    </a>
                @endauth
            </div>

            <!-- Mini stats ribbon -->
            <div class="flex items-center gap-8 pt-8 border-t border-slate-900/80">
                <div>
                    <h4 class="text-2xl font-bold text-white heading-font">99%</h4>
                    <p class="text-xs text-slate-500 font-semibold uppercase mt-0.5 tracking-wider">User Satisfaction</p>
                </div>
                <div class="w-px h-8 bg-slate-800"></div>
                <div>
                    <h4 class="text-2xl font-bold text-white heading-font">2.4h</h4>
                    <p class="text-xs text-slate-500 font-semibold uppercase mt-0.5 tracking-wider">Saved Daily Avg.</p>
                </div>
                <div class="w-px h-8 bg-slate-800"></div>
                <div>
                    <h4 class="text-2xl font-bold text-white heading-font">1.2M+</h4>
                    <p class="text-xs text-slate-500 font-semibold uppercase mt-0.5 tracking-wider">Sessions Logged</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Beautiful Interactive Widget -->
        <div class="lg:col-span-5 flex justify-center lg:justify-end">
            <div x-data="{
                duration: 25 * 60,
                timeLeft: 25 * 60,
                isRunning: false,
                taskName: '',
                timerId: null,
                toggleTimer() {
                    if (this.isRunning) {
                        clearInterval(this.timerId);
                        this.isRunning = false;
                    } else {
                        this.isRunning = true;
                        this.timerId = setInterval(() => {
                            if (this.timeLeft > 0) {
                                this.timeLeft--;
                            } else {
                                clearInterval(this.timerId);
                                this.isRunning = false;
                                alert('Focus session complete! Time to take a break.');
                                this.timeLeft = this.duration;
                            }
                        }, 1000);
                    }
                },
                resetTimer() {
                    clearInterval(this.timerId);
                    this.isRunning = false;
                    this.timeLeft = this.duration;
                }
            }" class="w-full max-w-[380px] bg-slate-950/40 backdrop-blur-xl border border-slate-800/80 p-6 rounded-3xl shadow-2xl shadow-emerald-950/20 text-left relative overflow-hidden group hover:border-emerald-500/20 transition-all duration-500">
                <!-- Inner glow -->
                <div class="absolute -top-12 -right-12 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:bg-emerald-500/20 transition-all duration-500 pointer-events-none"></div>
                
                <!-- Widget Top -->
                <div class="flex items-center justify-between mb-6">
                    <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-950/30 px-3 py-1 rounded-full border border-emerald-900/40">Try It Live</span>
                    <div class="flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Interactive Widget</span>
                    </div>
                </div>

                <!-- Timer Circle -->
                <div class="flex flex-col items-center justify-center py-6 relative">
                    <div class="relative w-44 h-44 flex items-center justify-center">
                        <!-- SVG Progress Ring -->
                        <svg class="w-full h-full transform -rotate-90">
                            <circle cx="88" cy="88" r="76" stroke="currentColor" class="text-slate-900/80" stroke-width="6" fill="transparent" />
                            <circle cx="88" cy="88" r="76" stroke="currentColor" class="text-emerald-500 transition-all duration-300" stroke-width="6" fill="transparent"
                                    :stroke-dasharray="477"
                                    :stroke-dashoffset="477 - (477 * (duration - timeLeft) / duration)"
                                    stroke-linecap="round" />
                        </svg>
                        <div class="absolute flex flex-col items-center justify-center">
                            <span class="text-4xl font-extrabold font-mono tracking-tight text-white select-none" x-text="Math.floor(timeLeft / 60).toString().padStart(2, '0') + ':' + (timeLeft % 60).toString().padStart(2, '0')">25:00</span>
                            <span class="text-[10px] font-bold text-slate-500 mt-1 uppercase tracking-widest select-none" x-text="isRunning ? 'Focusing' : 'Ready'">Ready</span>
                        </div>
                    </div>
                </div>

                <!-- Current Task Input -->
                <div class="mt-2 mb-6">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2">Focus Objective</label>
                    <input type="text" x-model="taskName" placeholder="What are you mastering today?" class="w-full bg-slate-900/60 border border-slate-800 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 transition-colors placeholder-slate-600 font-medium">
                </div>

                <!-- Controls -->
                <div class="flex gap-3">
                    <button @click="toggleTimer()" class="flex-1 py-3.5 rounded-xl font-bold text-xs transition-all flex items-center justify-center gap-1.5 active:scale-95"
                            :class="isRunning ? 'bg-slate-800 text-slate-200 hover:bg-slate-700' : 'bg-emerald-500 hover:bg-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/20'">
                        <svg x-show="!isRunning" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                        <svg x-show="isRunning" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                        <span x-text="isRunning ? 'Pause Session' : 'Start Focus'">Start Focus</span>
                    </button>
                    <button @click="resetTimer()" class="px-4 py-3.5 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white rounded-xl font-bold text-xs transition-colors">
                        Reset
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Bento Grid Features Section -->
    <section class="relative z-10 max-w-7xl mx-auto px-6 py-20 border-t border-slate-900/60">
        <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
            <h2 class="text-3xl sm:text-4xl font-extrabold heading-font text-white">Engineered for absolute focus.</h2>
            <p class="text-slate-400 text-base font-medium">Zenovo eliminates friction, bringing structure, automated flow cycles, and clean metrics to your daily flow.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            
            <!-- Bento Box 1: Focus States (Large) -->
            <div class="md:col-span-8 bg-slate-950/20 backdrop-blur-xl border border-slate-900/80 p-8 rounded-3xl hover:border-emerald-500/20 transition-all duration-300 flex flex-col justify-between group overflow-hidden relative min-h-[320px]">
                <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all"></div>
                <div class="space-y-4 max-w-md">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold heading-font text-white">High-Impact Focus Cycles</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Say goodbye to random timers. Zenovo syncs task lists with automated Pomodoro cycles, intelligently structuring periods of intense work and rich recovery.
                    </p>
                </div>
                
                <!-- Visual Mockup inside card -->
                <div class="mt-6 flex items-center gap-3 bg-slate-900/40 p-4 border border-slate-800/60 rounded-2xl max-w-sm">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-xs">P1</div>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-white">Refactoring UI Layout</span>
                            <span class="text-[10px] text-emerald-400 font-extrabold uppercase">18m left</span>
                        </div>
                        <div class="w-full bg-slate-950 h-1.5 rounded-full mt-2 overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento Box 2: Smart Drag & Drop Tasks (Small) -->
            <div class="md:col-span-4 bg-slate-950/20 backdrop-blur-xl border border-slate-900/80 p-8 rounded-3xl hover:border-emerald-500/20 transition-all duration-300 flex flex-col justify-between group min-h-[320px]">
                <div class="space-y-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold heading-font text-white">Smart Task Sorter</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Effortlessly assign time budgets, drag tasks across status boards, and prioritize work in a beautifully streamlined list.
                    </p>
                </div>
                
                <div class="space-y-2 mt-4">
                    <div class="bg-slate-900/60 border border-slate-800/80 px-3 py-2 rounded-xl text-xs font-semibold text-slate-300 flex items-center justify-between">
                        <span>Write marketing brief</span>
                        <span class="px-2 py-0.5 bg-slate-950 rounded text-[9px] text-slate-400 border border-slate-800">45 min</span>
                    </div>
                    <div class="bg-emerald-950/20 border border-emerald-900/40 px-3 py-2 rounded-xl text-xs font-bold text-emerald-400 flex items-center justify-between">
                        <span>✨ Complete homepage redesign</span>
                        <span class="px-2 py-0.5 bg-emerald-950/50 rounded text-[9px] text-emerald-300 border border-emerald-900/30">2 hours</span>
                    </div>
                </div>
            </div>

            <!-- Bento Box 3: Precision Insights (Full Width) -->
            <div class="md:col-span-12 bg-slate-950/20 backdrop-blur-xl border border-slate-900/80 p-8 rounded-3xl hover:border-emerald-500/20 transition-all duration-300 flex flex-col md:flex-row justify-between items-stretch md:items-center gap-8 group overflow-hidden relative min-h-[300px]">
                <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-emerald-500/5 rounded-full blur-3xl group-hover:bg-emerald-500/10 transition-all"></div>
                
                <!-- Info Section -->
                <div class="space-y-4 max-w-xl">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold heading-font text-white">Precision Insights & Time Auditing</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Dive deep into detailed daily, weekly, and monthly reports. Zenovo provides elegant, clear breakdown charts displaying exactly how your time is allocated and where your productivity peaks.
                    </p>
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="px-2.5 py-1 rounded-md bg-slate-900/60 border border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wide">Category Breakdown</span>
                        <span class="px-2.5 py-1 rounded-md bg-slate-900/60 border border-slate-800 text-[10px] font-bold text-slate-400 uppercase tracking-wide">Weekly Audits</span>
                        <span class="px-2.5 py-1 rounded-md bg-emerald-950/30 border border-emerald-900/30 text-[10px] font-bold text-emerald-400 uppercase tracking-wide">CSV Exports</span>
                    </div>
                </div>
                
                <!-- Expanded Interactive Mock Chart -->
                <div class="flex-1 max-w-lg bg-slate-900/30 p-6 border border-slate-800/60 rounded-2xl relative h-40 flex flex-col justify-between overflow-hidden">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Weekly Audited Hours</span>
                        <span class="text-[9px] font-bold text-emerald-400 bg-emerald-950/40 px-2 py-0.5 rounded border border-emerald-900/30">Efficiency Up +14%</span>
                    </div>
                    <!-- Chart Bars -->
                    <div class="flex items-end justify-between h-20 px-2 relative">
                        <!-- Monday to Sunday bars -->
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-slate-800 hover:bg-emerald-500/40 transition-all rounded-t" style="height: 35px;"></div>
                            <span class="text-[9px] font-bold text-slate-600">M</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-slate-800 hover:bg-emerald-500/40 transition-all rounded-t" style="height: 55px;"></div>
                            <span class="text-[9px] font-bold text-slate-600">T</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-emerald-500 rounded-t shadow-lg shadow-emerald-500/10" style="height: 75px;"></div>
                            <span class="text-[9px] font-bold text-emerald-400">W</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-slate-800 hover:bg-emerald-500/40 transition-all rounded-t" style="height: 48px;"></div>
                            <span class="text-[9px] font-bold text-slate-600">T</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-slate-800 hover:bg-emerald-500/40 transition-all rounded-t" style="height: 38px;"></div>
                            <span class="text-[9px] font-bold text-slate-600">F</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-emerald-500/60 rounded-t" style="height: 65px;"></div>
                            <span class="text-[9px] font-bold text-emerald-400/80">S</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 w-1/7">
                            <div class="w-5 bg-slate-800 hover:bg-emerald-500/40 transition-all rounded-t" style="height: 25px;"></div>
                            <span class="text-[9px] font-bold text-slate-600">S</span>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Ultimate CTA Section -->
    <section class="relative z-10 max-w-5xl mx-auto px-6 py-24 text-center">
        <div class="bg-gradient-to-tr from-slate-950/80 via-slate-900/40 to-slate-950/80 border border-slate-800/80 p-12 md:p-16 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-emerald-500/5 blur-[80px] rounded-full pointer-events-none -top-12 -left-12"></div>
            
            <div class="relative z-10 space-y-6 max-w-2xl mx-auto">
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold heading-font text-white leading-tight">
                    Ready to reclaim <br class="hidden sm:inline">your flow state?
                </h2>
                <p class="text-slate-400 text-base md:text-lg font-medium leading-relaxed">
                    Join high performers who leverage Zenovo to eliminate daily friction, keep work focused, and unlock clear cognitive insights.
                </p>
                <div class="pt-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-xl shadow-emerald-500/10 text-base active:scale-95 inline-block">
                            Enter Workspace
                        </a>
                    @else
                        <a href="{{ route('register.form') }}" class="px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl transition-all shadow-xl shadow-emerald-500/10 text-base active:scale-95 inline-block">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="relative z-10 max-w-7xl mx-auto px-6 py-12 border-t border-slate-900/60 text-center flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="flex items-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-gradient-to-tr from-emerald-500 to-[#00d26a] flex items-center justify-center shadow-lg">
                <svg class="w-3.5 h-3.5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <span class="font-bold text-sm heading-font tracking-tight text-white">Zenovo</span>
        </div>
        <p class="text-xs font-semibold text-slate-500">&copy; {{ date('Y') }} Zenovo. All rights reserved. Precision time engineering.</p>
    </footer>

    <!-- Interactive widget implementation fully compiled inline above -->
</body>
</html>