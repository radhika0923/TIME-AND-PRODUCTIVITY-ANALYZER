@php
    $userName = auth()->user()->name ?? 'User';
    $initials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('');

    if ($initials === '') {
        $initials = 'U';
    }

    $stats = [
        [
            'label' => 'Total Tasks',
            'value' => $totalTasks ?? 0,
            'trend' => '+12%',
            'tone' => 'indigo',
            'icon' => '<path d="M9 12h6m-6 4h6m3 4H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9.172a2 2 0 0 1 1.414.586l3.828 3.828A2 2 0 0 1 21 9.828V18a2 2 0 0 1-2 2Z" />',
        ],
        [
            'label' => 'Completed Tasks',
            'value' => $completedTasks ?? 0,
            'trend' => '+18%',
            'tone' => 'emerald',
            'icon' => '<path d="M20 6 9 17l-5-5" /><path d="M21 12a9 9 0 1 1-9-9" />',
        ],
        [
            'label' => 'Pending Tasks',
            'value' => $pendingTasks ?? 0,
            'trend' => '+9%',
            'tone' => 'amber',
            'icon' => '<path d="M12 8v5l3 2" /><circle cx="12" cy="12" r="9" />',
        ],
        [
            'label' => 'Completion Rate',
            'value' => ($completionRate ?? 0) . '%',
            'trend' => '+5%',
            'tone' => 'violet',
            'icon' => '<path d="M4 17 9.5 11.5 13 15l6-8" /><path d="M4 19h16" />',
        ],
    ];

    $activities = $recentTasks ?? [];

    $sidebarLinks = [
        ['label' => 'Dashboard', 'href' => url('/dashboard'), 'active' => true],
        ['label' => 'Tasks', 'href' => url('/tasks'), 'active' => false],
        ['label' => 'Time Tracking', 'href' => '#'],
        ['label' => 'Analytics', 'href' => '#'],
        ['label' => 'Settings', 'href' => '#'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>Dashboard | Time & Productivity Analyzer</title>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Manrope', 'ui-sans-serif', 'system-ui'],
                        mono: ['IBM Plex Mono', 'ui-monospace', 'monospace'],
                    },
                    boxShadow: {
                        soft: '0 20px 60px rgba(0, 0, 0, 0.35)',
                    },
                },
            },
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="h-full bg-slate-950 font-sans text-slate-100 antialiased selection:bg-cyan-400 selection:text-slate-950">
    <div class="relative min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.12),_transparent_28%),radial-gradient(circle_at_top_right,_rgba(99,102,241,0.12),_transparent_24%),linear-gradient(180deg,_#020617_0%,_#020617_100%)]">
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:72px_72px] opacity-20"></div>

    <div id="mobileSidebar" class="fixed inset-0 z-50 hidden lg:hidden">
        <div id="mobileSidebarBackdrop" class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm"></div>
        <aside class="absolute left-0 top-0 flex h-full w-80 max-w-[88vw] flex-col border-r border-white/5 bg-slate-950/95 p-5 shadow-soft">
            <div class="mb-8 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 shadow-lg shadow-cyan-500/20">
                        <span class="text-sm font-extrabold text-white">TP</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">Time & Productivity</p>
                        <p class="text-xs text-slate-400">Analyzer</p>
                    </div>
                </div>
                <button type="button" id="closeMobileSidebar" class="grid h-10 w-10 place-items-center rounded-2xl border border-white/5 bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Close menu">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M6 6l12 12M18 6 6 18"></path>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1.5">
                @foreach ($sidebarLinks as $link)
                    <a href="{{ $link['href'] }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ !empty($link['active']) ? 'bg-white/6 text-white ring-1 ring-white/8' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                        @if ($link['label'] === 'Dashboard')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h7V3H3v9Zm11 9h7v-9h-7v9ZM14 3v6h7V3h-7ZM3 21h7v-6H3v6Z"/></svg>
                        @elseif ($link['label'] === 'Tasks')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                        @elseif ($link['label'] === 'Time Tracking')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                        @elseif ($link['label'] === 'Analytics')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M10 19V9"/><path d="M16 19V12"/><path d="M22 19V7"/></svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6"/><path d="M10 14 20 4"/><path d="M20 14v6H4V4h6"/></svg>
                        @endif
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <form method="POST" action="{{ route('logout') }}" class="pt-4">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-400 transition hover:bg-white/5 hover:text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/></svg>
                    <span>Logout</span>
                </button>
            </form>
        </aside>
    </div>

    <aside class="fixed inset-y-0 left-0 z-40 hidden w-72 flex-col border-r border-white/5 bg-slate-950/85 px-5 py-6 backdrop-blur-xl lg:flex">
        <div class="mb-8 flex items-center gap-3 px-1">
            <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 shadow-lg shadow-cyan-500/20">
                <span class="text-sm font-extrabold text-white">TP</span>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Time & Productivity</p>
                <p class="text-xs text-slate-400">Analyzer</p>
            </div>
        </div>

        <nav class="flex-1 space-y-1.5">
            @foreach ($sidebarLinks as $link)
                <a href="{{ $link['href'] }}" class="group flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ !empty($link['active']) ? 'bg-white/6 text-white ring-1 ring-white/8' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    @if ($link['label'] === 'Dashboard')
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12h7V3H3v9Zm11 9h7v-9h-7v9ZM14 3v6h7V3h-7ZM3 21h7v-6H3v6Z"/></svg>
                    @elseif ($link['label'] === 'Tasks')
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    @elseif ($link['label'] === 'Time Tracking')
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    @elseif ($link['label'] === 'Analytics')
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19V5"/><path d="M10 19V9"/><path d="M16 19V12"/><path d="M22 19V7"/></svg>
                    @else
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6"/><path d="M10 14 20 4"/><path d="M20 14v6H4V4h6"/></svg>
                    @endif
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="mt-6 rounded-3xl border border-white/5 bg-white/[0.03] p-4 shadow-soft">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 text-sm font-bold text-white">{{ $initials }}</div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ $userName }}</p>
                    <p class="text-xs text-slate-400">Active workspace</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-4">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-2xl border border-white/5 bg-white/5 px-4 py-3 text-sm font-medium text-slate-200 transition hover:bg-white/10 hover:text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="relative lg:pl-72">
        <header class="sticky top-0 z-30 border-b border-white/5 bg-slate-950/70 backdrop-blur-xl">
            <div class="mx-auto flex h-20 max-w-7xl items-center gap-4 px-4 sm:px-6 lg:px-8">
                <button type="button" id="openMobileSidebar" class="grid h-11 w-11 place-items-center rounded-2xl border border-white/5 bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white lg:hidden" aria-label="Open menu">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <div class="min-w-0 flex-1">
                    <div class="max-w-2xl">
                        <label class="relative block">
                            <span class="sr-only">Search</span>
                            <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-slate-500">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                            </span>
                            <input type="text" placeholder="Search tasks, reports, or activity" class="h-11 w-full rounded-2xl border border-white/5 bg-white/[0.04] pl-11 pr-4 text-sm text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-400/30 focus:bg-white/[0.06] focus:ring-4 focus:ring-cyan-500/10">
                        </label>
                    </div>
                </div>

                <button type="button" class="relative grid h-11 w-11 place-items-center rounded-2xl border border-white/5 bg-white/[0.04] text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Notifications">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 0 0-5-5.9V4a1 1 0 1 0-2 0v1.1A6 6 0 0 0 6 11v3.2a2 2 0 0 1-.6 1.4L4 17h5"/><path d="M9 17a3 3 0 0 0 6 0"/></svg>
                    <span class="absolute right-3 top-3 h-2 w-2 rounded-full bg-cyan-400 ring-4 ring-slate-950"></span>
                </button>

                <div class="relative" id="profileDropdownWrapper">
                    <button type="button" id="profileDropdownButton" class="flex items-center gap-3 rounded-2xl border border-white/5 bg-white/[0.04] px-3 py-2 text-left transition hover:bg-white/10" aria-haspopup="true" aria-expanded="false">
                        <div class="grid h-9 w-9 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 text-sm font-bold text-white">{{ $initials }}</div>
                        <div class="hidden min-w-0 sm:block">
                            <p class="truncate text-sm font-semibold text-white">{{ $userName }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <svg class="hidden h-4 w-4 text-slate-400 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                    </button>

                    <div id="profileDropdownMenu" class="absolute right-0 top-full mt-3 hidden w-64 overflow-hidden rounded-3xl border border-white/5 bg-slate-950/95 p-2 shadow-soft backdrop-blur-xl">
                        <div class="border-b border-white/5 px-4 py-4">
                            <p class="text-sm font-semibold text-white">{{ $userName }}</p>
                            <p class="mt-1 text-xs text-slate-400">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                        <a href="#" class="mt-2 flex items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/5 hover:text-white">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6"/><path d="M10 14 20 4"/><path d="M20 14v6H4V4h6"/></svg>
                            Profile settings
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-sm text-slate-300 transition hover:bg-white/5 hover:text-white">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/><path d="M21 3v18"/></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-[28px] border border-white/5 bg-white/[0.03] p-6 shadow-soft transition-all duration-300 hover:border-white/10 hover:bg-white/[0.045] sm:p-7">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="inline-flex items-center gap-2 rounded-full border border-white/5 bg-white/[0.03] px-3 py-1 text-xs font-medium text-slate-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            Your productivity snapshot is live
                        </p>
                        <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-white sm:text-4xl">Welcome back, {{ $userName }}</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-400 sm:text-[15px]">A clear view of your workday, focus rhythm, and task momentum. Keep shipping with less friction and more signal.</p>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-cyan-400 px-4 py-2.5 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition duration-300 hover:-translate-y-0.5 hover:bg-cyan-300 hover:shadow-cyan-500/30 active:translate-y-0">
                                <span class="text-lg leading-none">+</span>
                                Add Task
                            </button>
                            <button type="button" class="inline-flex items-center gap-2 rounded-2xl border border-white/8 bg-white/[0.04] px-4 py-2.5 text-sm font-semibold text-white transition duration-300 hover:-translate-y-0.5 hover:border-cyan-400/25 hover:bg-white/[0.08] hover:shadow-[0_0_0_1px_rgba(34,211,238,0.08)] active:translate-y-0">
                                <svg class="h-4 w-4 text-cyan-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                                Start Focus Session
                            </button>
                        </div>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2 lg:w-[340px] lg:grid-cols-1">
                        <div class="rounded-3xl border border-cyan-400/10 bg-cyan-400/5 p-4 transition duration-300 hover:-translate-y-0.5 hover:border-cyan-400/20 hover:bg-cyan-400/7">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-cyan-300/80">Today’s focus</p>
                            <p class="mt-2 text-lg font-semibold text-white">2 deep-work blocks</p>
                            <p class="mt-1 text-sm text-slate-400">Best window: 9:00 AM - 11:30 AM</p>
                        </div>
                        <div class="rounded-3xl border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:-translate-y-0.5 hover:border-white/10 hover:bg-white/[0.05]">
                            <p class="text-xs font-medium uppercase tracking-[0.18em] text-slate-400">Productivity quote</p>
                            <p class="mt-2 text-sm leading-6 text-slate-200">“The secret of getting ahead is getting started.”</p>
                            <p class="mt-1 text-xs text-slate-500">- Mark Twain</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="group relative cursor-pointer overflow-hidden rounded-[28px] border border-white/5 bg-white/[0.03] p-6 shadow-soft transition-all duration-300 hover:-translate-y-1 hover:border-white/10 hover:bg-white/[0.05] hover:shadow-[0_26px_70px_rgba(0,0,0,0.45)]">
                        <div class="pointer-events-none absolute inset-0 opacity-0 transition duration-300 group-hover:opacity-100" style="background: radial-gradient(circle at top right, rgba(34,211,238,0.12), transparent 35%), radial-gradient(circle at bottom left, rgba(99,102,241,0.10), transparent 40%);"></div>
                        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm text-slate-400">{{ $stat['label'] }}</p>
                                <p class="mt-3 text-3xl font-extrabold tracking-tight text-white font-mono">{{ $stat['value'] }}</p>
                            </div>
                            <div class="grid h-12 w-12 place-items-center rounded-2xl border border-white/5 bg-white/[0.04] text-white shadow-inner shadow-black/20 transition duration-300 group-hover:scale-105 group-hover:border-white/10 {{ $stat['tone'] === 'emerald' ? 'text-emerald-300' : ($stat['tone'] === 'amber' ? 'text-amber-300' : ($stat['tone'] === 'violet' ? 'text-violet-300' : 'text-cyan-300')) }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $stat['icon'] !!}
                                </svg>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center gap-2 text-xs font-medium text-emerald-300 transition duration-300 group-hover:translate-x-0.5">
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 px-2.5 py-1">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 14 6-6 6 6"/></svg>
                                {{ $stat['trend'] }}
                            </span>
                            <span class="text-slate-500">{{ $stat['label'] }} growth</span>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="grid gap-5 xl:grid-cols-[1.52fr_0.98fr]">
                <article class="overflow-hidden rounded-[28px] border border-white/5 bg-white/[0.03] p-6 shadow-soft transition duration-300 hover:border-white/10 hover:bg-white/[0.045]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-white">Productivity Chart</p>
                            <p class="mt-1 text-sm text-slate-400">Weekly focus trend across your active work sessions.</p>
                        </div>
                        <div class="rounded-full border border-cyan-400/10 bg-cyan-400/5 px-3 py-1 text-xs font-medium text-cyan-300">Live preview</div>
                    </div>

                    <div class="mt-5 rounded-[24px] border border-white/5 bg-slate-950/50 p-4">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <span class="h-2 w-2 rounded-full bg-cyan-400"></span>
                                <span>Productivity score</span>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <span>Mon - Sat</span>
                                <span class="rounded-full border border-white/5 bg-white/[0.03] px-2 py-0.5 font-mono text-[10px] text-slate-400">Avg 86</span>
                            </div>
                        </div>
                        <svg viewBox="0 0 920 320" class="h-[250px] w-full">
                            <defs>
                                <linearGradient id="chartStroke" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.95" />
                                    <stop offset="100%" stop-color="#6366f1" stop-opacity="0.95" />
                                </linearGradient>
                                <linearGradient id="chartFill" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.22" />
                                    <stop offset="100%" stop-color="#22d3ee" stop-opacity="0" />
                                </linearGradient>
                                <filter id="chartGlow" x="-20%" y="-20%" width="140%" height="140%">
                                    <feGaussianBlur stdDeviation="7" result="blur" />
                                    <feMerge>
                                        <feMergeNode in="blur" />
                                        <feMergeNode in="SourceGraphic" />
                                    </feMerge>
                                </filter>
                            </defs>
                            <g stroke="rgba(255,255,255,0.08)" stroke-width="1">
                                <line x1="40" y1="40" x2="880" y2="40" />
                                <line x1="40" y1="96" x2="880" y2="96" />
                                <line x1="40" y1="152" x2="880" y2="152" />
                                <line x1="40" y1="208" x2="880" y2="208" />
                                <line x1="40" y1="264" x2="880" y2="264" />
                            </g>
                            <path d="M40 230 C 110 210, 140 160, 190 176 C 245 194, 285 96, 340 114 C 400 134, 450 80, 510 92 C 575 106, 610 58, 670 76 C 735 95, 780 54, 840 70 L 840 264 L 40 264 Z" fill="url(#chartFill)" />
                            <path d="M40 230 C 110 210, 140 160, 190 176 C 245 194, 285 96, 340 114 C 400 134, 450 80, 510 92 C 575 106, 610 58, 670 76 C 735 95, 780 54, 840 70" fill="none" stroke="url(#chartStroke)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" filter="url(#chartGlow)" />
                            <g fill="#020617" stroke="#67e8f9" stroke-width="4">
                                <circle cx="190" cy="176" r="8" />
                                <circle cx="340" cy="114" r="8" />
                                <circle cx="510" cy="92" r="8" />
                                <circle cx="670" cy="76" r="8" />
                                <circle cx="840" cy="70" r="8" />
                            </g>
                            <g>
                                <rect x="648" y="36" rx="16" ry="16" width="180" height="54" fill="rgba(2,6,23,0.88)" stroke="rgba(255,255,255,0.08)"/>
                                <circle cx="668" cy="63" r="5" fill="#22d3ee" />
                                <text x="682" y="60" fill="#cbd5e1" font-size="12" font-family="IBM Plex Mono, monospace">Peak: Fri</text>
                                <text x="682" y="76" fill="#f8fafc" font-size="18" font-weight="700" font-family="IBM Plex Mono, monospace">94 score</text>
                            </g>
                            <g class="fill-slate-500 text-[11px] font-medium" text-anchor="middle">
                                <text x="95" y="300">Mon</text>
                                <text x="205" y="300">Tue</text>
                                <text x="335" y="300">Wed</text>
                                <text x="470" y="300">Thu</text>
                                <text x="620" y="300">Fri</text>
                                <text x="750" y="300">Sat</text>
                            </g>
                        </svg>
                    </div>
                </article>

                <article class="overflow-hidden rounded-[28px] border border-white/5 bg-white/[0.03] p-6 shadow-soft transition duration-300 hover:border-white/10 hover:bg-white/[0.045]">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-white">Task Completion</p>
                            <p class="mt-1 text-sm text-slate-400">Current sprint progress and delivery health.</p>
                        </div>
                        <div class="rounded-full border border-white/5 bg-white/[0.04] px-3 py-1 text-xs font-medium text-slate-300">Sprint 14</div>
                    </div>

                    <div class="mt-5 grid place-items-center rounded-[24px] border border-white/5 bg-slate-950/50 p-5">
                        <div class="relative grid h-44 w-44 place-items-center rounded-full" style="background: conic-gradient(#22d3ee 0 78%, rgba(148,163,184,0.14) 78% 100%);">
                            <div class="grid h-36 w-36 place-items-center rounded-full border border-white/5 bg-slate-950/95 text-center shadow-inner shadow-black/30">
                                <div>
                                    <p class="text-4xl font-extrabold tracking-tight text-white">78%</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.2em] text-slate-500">Complete</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 w-full space-y-3.5">
                            @php
                                $bars = [
                                    ['label' => 'Design system', 'value' => 92, 'tone' => 'cyan'],
                                    ['label' => 'Backend integration', 'value' => 76, 'tone' => 'indigo'],
                                    ['label' => 'QA & polish', 'value' => 54, 'tone' => 'emerald'],
                                ];
                            @endphp
                            @foreach ($bars as $bar)
                                <div>
                                    <div class="mb-2 flex items-center justify-between text-sm">
                                        <span class="text-slate-300">{{ $bar['label'] }}</span>
                                        <span class="font-mono text-xs text-slate-500">{{ $bar['value'] }}%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-white/[0.05]">
                                        <div class="h-2 rounded-full {{ $bar['tone'] === 'cyan' ? 'bg-cyan-400' : ($bar['tone'] === 'indigo' ? 'bg-indigo-400' : 'bg-emerald-400') }}" style="width: {{ $bar['value'] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </article>
            </section>

            <section class="overflow-hidden rounded-[28px] border border-white/5 bg-white/[0.03] p-6 shadow-soft transition duration-300 hover:border-white/10 hover:bg-white/[0.045]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-white">Recent Activity</p>
                        <p class="mt-1 text-sm text-slate-400">Latest updates from your workspace.</p>
                    </div>
                    <a href="#" class="text-sm font-medium text-cyan-300 transition hover:text-cyan-200">View all</a>
                </div>

                <div class="mt-5 space-y-2.5">
                    @foreach ($activities as $activity)
                        <div class="flex items-start gap-4 rounded-[22px] border border-white/5 bg-white/[0.03] p-4 transition duration-300 hover:-translate-y-0.5 hover:border-white/10 hover:bg-white/[0.05]">
                            <div class="grid h-11 w-11 shrink-0 place-items-center rounded-2xl border border-white/5 bg-white/[0.04] {{ $activity['tone'] === 'emerald' ? 'text-emerald-300' : ($activity['tone'] === 'amber' ? 'text-amber-300' : ($activity['tone'] === 'violet' ? 'text-violet-300' : 'text-cyan-300')) }}">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    {!! $activity['icon'] !!}
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-medium text-white">{{ $activity['title'] }}</p>
                                <p class="mt-1 text-sm text-slate-400">{{ $activity['meta'] }}</p>
                            </div>
                            <span class="shrink-0 text-xs font-medium text-slate-500">{{ $activity['time'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>
    </div>
</div>

<script>
    const mobileSidebar = document.getElementById('mobileSidebar');
    const openMobileSidebar = document.getElementById('openMobileSidebar');
    const closeMobileSidebar = document.getElementById('closeMobileSidebar');
    const mobileSidebarBackdrop = document.getElementById('mobileSidebarBackdrop');
    const profileDropdownButton = document.getElementById('profileDropdownButton');
    const profileDropdownMenu = document.getElementById('profileDropdownMenu');

    const openSidebar = () => mobileSidebar.classList.remove('hidden');
    const closeSidebar = () => mobileSidebar.classList.add('hidden');

    openMobileSidebar?.addEventListener('click', openSidebar);
    closeMobileSidebar?.addEventListener('click', closeSidebar);
    mobileSidebarBackdrop?.addEventListener('click', closeSidebar);

    profileDropdownButton?.addEventListener('click', () => {
        profileDropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!profileDropdownMenu.contains(event.target) && !profileDropdownButton.contains(event.target)) {
            profileDropdownMenu.classList.add('hidden');
        }
    });
</script>
</body>
</html>
