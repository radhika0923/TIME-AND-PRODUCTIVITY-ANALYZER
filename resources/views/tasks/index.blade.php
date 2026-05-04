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

    $sidebarLinks = [
        ['label' => 'Dashboard', 'href' => url('/dashboard'), 'active' => false],
        ['label' => 'Tasks', 'href' => url('/tasks'), 'active' => true],
        ['label' => 'Time Tracking', 'href' => '#', 'active' => false],
        ['label' => 'Analytics', 'href' => '#', 'active' => false],
        ['label' => 'Settings', 'href' => '#', 'active' => false],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark">
    <title>Tasks | Time & Productivity Analyzer</title>
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
    <style>
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-slide-in-up {
            animation: slideInUp 0.5s ease-out;
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        .task-card {
            @apply relative overflow-hidden rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 to-slate-800 p-6 transition-all duration-300 hover:border-white/10 hover:shadow-lg hover:shadow-cyan-500/5 group;
        }
        .task-card::before {
            @apply absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-transparent to-indigo-500/0 opacity-0 transition-opacity duration-300 pointer-events-none;
            content: '';
        }
        .task-card:hover::before {
            @apply opacity-20;
        }
        .priority-badge {
            @apply inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold transition-all duration-200;
        }
        .priority-high {
            @apply bg-red-500/20 text-red-300;
        }
        .priority-medium {
            @apply bg-amber-500/20 text-amber-300;
        }
        .priority-low {
            @apply bg-emerald-500/20 text-emerald-300;
        }
        .status-badge {
            @apply inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold transition-all duration-200;
        }
        .status-completed {
            @apply bg-emerald-500/20 text-emerald-300;
        }
        .status-pending {
            @apply bg-amber-500/20 text-amber-300;
        }
        .modal-backdrop {
            @apply fixed inset-0 z-40 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-none;
        }
        .modal-backdrop.active {
            @apply opacity-100 pointer-events-auto;
        }
        .modal-content {
            @apply fixed inset-0 z-50 flex items-center justify-center p-4 opacity-0 transition-all duration-300 pointer-events-none;
        }
        .modal-content.active {
            @apply opacity-100 pointer-events-auto;
        }
        .modal-card {
            @apply w-full max-w-lg rounded-3xl border border-white/10 bg-slate-900 p-8 shadow-2xl transform transition-transform duration-300 scale-95;
        }
        .modal-content.active .modal-card {
            @apply scale-100;
        }
        .btn-primary {
            @apply inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-cyan-500 to-indigo-600 px-6 py-3 font-semibold text-white shadow-lg shadow-cyan-500/30 transition-all duration-200 hover:shadow-cyan-500/50 hover:-translate-y-0.5 active:translate-y-0;
        }
        .btn-secondary {
            @apply inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-6 py-3 font-semibold text-slate-200 transition-all duration-200 hover:bg-white/10 hover:border-white/20;
        }
        .btn-ghost {
            @apply inline-flex items-center justify-center h-10 w-10 rounded-lg text-slate-400 transition-all duration-200 hover:bg-white/5 hover:text-slate-300;
        }
        .input-field {
            @apply w-full rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-medium text-white placeholder-slate-400 transition-all duration-200 focus:border-cyan-500/50 focus:bg-white/10 focus:outline-none focus:ring-2 focus:ring-cyan-500/20;
        }
        .tab-button {
            @apply px-4 py-2 rounded-lg font-semibold transition-all duration-200 text-slate-400;
        }
        .tab-button.active {
            @apply bg-white/10 text-white;
        }
    </style>
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
                <nav class="flex flex-1 flex-col gap-2">
                    @foreach ($sidebarLinks as $link)
                        <a href="{{ $link['href'] }}" class="rounded-xl px-4 py-3 font-semibold transition @if ($link['active']) bg-white/10 text-white @else text-slate-400 hover:text-white @endif">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </nav>
                <div class="border-t border-white/5 pt-5">
                    <button type="button" id="logoutMobileBtn" class="w-full rounded-xl bg-white/5 px-4 py-3 font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Logout
                    </button>
                </div>
            </aside>
        </div>

        <aside class="fixed bottom-0 left-0 top-0 z-40 hidden w-80 border-r border-white/5 bg-gradient-to-b from-slate-950/95 to-slate-900/95 p-6 lg:flex lg:flex-col">
            <div class="mb-12 flex items-center gap-3">
                <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-indigo-600 shadow-lg shadow-cyan-500/20">
                    <span class="text-sm font-extrabold text-white">TP</span>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white">Time & Productivity</p>
                    <p class="text-xs text-slate-400">Analyzer</p>
                </div>
            </div>

            <nav class="mb-auto flex flex-col gap-2">
                @foreach ($sidebarLinks as $link)
                    <a href="{{ $link['href'] }}" class="rounded-xl px-4 py-3 font-semibold transition @if ($link['active']) bg-white/10 text-white @else text-slate-400 hover:text-white @endif">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex flex-col gap-3 border-t border-white/5 pt-5">
                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-3">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-gradient-to-br from-cyan-500 to-indigo-600 text-xs font-bold text-white">
                        {{ $initials }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="truncate text-sm font-semibold text-white">{{ $userName }}</p>
                        <p class="truncate text-xs text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full rounded-xl bg-white/5 px-4 py-3 font-semibold text-slate-300 transition hover:bg-white/10 hover:text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex flex-col gap-8 p-6 lg:ml-80 lg:p-12">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center justify-between gap-2 lg:hidden">
                    <h1 class="text-3xl font-extrabold text-white">Tasks</h1>
                    <button type="button" id="openMobileSidebar" class="grid h-10 w-10 place-items-center rounded-xl border border-white/10 bg-white/5 text-slate-300 transition hover:bg-white/10 hover:text-white">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M3 12h18M3 6h18M3 18h18"></path>
                        </svg>
                    </button>
                </div>
                <h1 class="hidden text-3xl font-extrabold text-white lg:block">Tasks</h1>
                <button type="button" id="openAddModal" class="btn-primary">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    Add Task
                </button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="task-card">
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-400">Total Tasks</p>
                        <p class="mt-2 text-3xl font-bold text-white">{{ $totalTasks }}</p>
                    </div>
                </div>
                <div class="task-card">
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-400">Completed</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-400">{{ $completedTasks }}</p>
                    </div>
                </div>
                <div class="task-card">
                    <div class="relative">
                        <p class="text-sm font-semibold text-slate-400">Pending</p>
                        <p class="mt-2 text-3xl font-bold text-amber-400">{{ $pendingTasks }}</p>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="flex-1">
                    <form method="GET" action="{{ route('tasks.index') }}" class="flex gap-4">
                        <div class="flex-1 relative">
                            <svg class="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search', '') }}" class="input-field pl-10">
                        </div>
                        <select name="sort" class="input-field">
                            <option value="due_date" @if (request('sort') === 'due_date') selected @endif>Due Date</option>
                            <option value="priority" @if (request('sort') === 'priority') selected @endif>Priority</option>
                            <option value="created_at" @if (request('sort') === 'created_at') selected @endif>Newest</option>
                        </select>
                        <button type="submit" class="btn-primary">Filter</button>
                    </form>
                </div>
            </div>

            <!-- Status Filter Tabs -->
            <div class="flex gap-2">
                <a href="{{ route('tasks.index', ['status' => 'all']) }}" class="tab-button @if (request('status') === 'all' || !request('status')) active @endif">All</a>
                <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="tab-button @if (request('status') === 'pending') active @endif">Pending</a>
                <a href="{{ route('tasks.index', ['status' => 'completed']) }}" class="tab-button @if (request('status') === 'completed') active @endif">Completed</a>
            </div>

            <!-- Tasks Grid -->
            @if ($tasks->count() > 0)
                <div class="grid gap-4 animate-slide-in-up">
                    @foreach ($tasks as $task)
                        <div class="task-card" data-task-id="{{ $task->id }}">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-3">
                                        <button type="button" class="toggle-status flex-shrink-0 h-6 w-6 rounded-lg border-2 transition-all duration-200 @if ($task->status === 'completed') border-emerald-500 bg-emerald-500 @else border-slate-600 @endif" data-task-id="{{ $task->id }}">
                                            @if ($task->status === 'completed')
                                                <svg class="h-full w-full text-white p-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round">
                                                    <path d="M20 6 9 17l-5-5"></path>
                                                </svg>
                                            @endif
                                        </button>
                                        <h3 class="flex-1 text-lg font-semibold text-white @if ($task->status === 'completed') line-through text-slate-400 @endif">
                                            {{ $task->title }}
                                        </h3>
                                    </div>
                                    @if ($task->description)
                                        <p class="mb-4 text-sm text-slate-400 line-clamp-2">{{ $task->description }}</p>
                                    @endif
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="priority-badge priority-{{ $task->priority }}">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                        <span class="status-badge status-{{ $task->status }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                        @if ($task->due_date)
                                            <span class="text-xs text-slate-400">
                                                Due: {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-shrink-0 gap-2">
                                    <button type="button" class="edit-task btn-ghost" data-task-id="{{ $task->id }}" data-title="{{ $task->title }}" data-description="{{ $task->description }}" data-priority="{{ $task->priority }}" data-due-date="{{ $task->due_date?->format('Y-m-d') }}">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                            <path d="M18.375 2.625a2.121 2.121 0 1 1 3 3L12 15l-4 1 1-4 9.375-9.375z"></path>
                                        </svg>
                                    </button>
                                    <button type="button" class="delete-task btn-ghost" data-task-id="{{ $task->id }}">
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M8 6v12a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V6"></path>
                                            <path d="M10 11v6M14 11v6"></path>
                                            <path d="M5 6l1-1h12l1 1"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($tasks->hasPages())
                    <div class="flex justify-center">
                        {{ $tasks->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-2xl border border-white/5 bg-gradient-to-br from-slate-900 to-slate-800 p-12 text-center">
                    <svg class="mx-auto mb-4 h-12 w-12 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M9 12h6m-6 4h6m3 5H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h9.172a2 2 0 0 1 1.414.586l3.828 3.828A2 2 0 0 1 21 9.828V18a2 2 0 0 1-2 2Z"></path>
                    </svg>
                    <p class="text-lg font-semibold text-slate-300">No tasks yet</p>
                    <p class="text-sm text-slate-400">Create your first task to get started</p>
                </div>
            @endif
        </main>
    </div>

    <!-- Add Task Modal -->
    <div id="addModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-card">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Add New Task</h2>
                    <button type="button" class="close-modal btn-ghost" data-modal="addModal">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M6 6l12 12M18 6 6 18"></path>
                        </svg>
                    </button>
                </div>

                <form id="addTaskForm" class="flex flex-col gap-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white">Task Title *</label>
                        <input type="text" name="title" required class="input-field" placeholder="Enter task title">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white">Description</label>
                        <textarea name="description" rows="4" class="input-field resize-none" placeholder="Enter task description..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white">Priority *</label>
                            <select name="priority" required class="input-field">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white">Due Date</label>
                            <input type="date" name="due_date" class="input-field">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="button" class="close-modal btn-secondary" data-modal="addModal">Cancel</button>
                        <button type="submit" class="btn-primary flex-1">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-card">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-white">Edit Task</h2>
                    <button type="button" class="close-modal btn-ghost" data-modal="editModal">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <path d="M6 6l12 12M18 6 6 18"></path>
                        </svg>
                    </button>
                </div>

                <form id="editTaskForm" class="flex flex-col gap-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTaskId" name="task_id">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white">Task Title *</label>
                        <input type="text" name="title" required class="input-field" placeholder="Enter task title">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-white">Description</label>
                        <textarea name="description" rows="4" class="input-field resize-none" placeholder="Enter task description..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white">Priority *</label>
                            <select name="priority" required class="input-field">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-white">Due Date</label>
                            <input type="date" name="due_date" class="input-field">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="button" class="close-modal btn-secondary" data-modal="editModal">Cancel</button>
                        <button type="submit" class="btn-primary flex-1">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-card">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-white">Delete Task?</h2>
                    <p class="mt-2 text-sm text-slate-400">This action cannot be undone.</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" class="close-modal btn-secondary flex-1" data-modal="deleteModal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn-primary flex-1 bg-red-600 hover:bg-red-700">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '{{ url('/tasks') }}';
let currentDeleteTaskId = null;

// ✅ TOAST
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    toast.textContent = message;

    toast.className = `fixed top-6 right-6 z-50 px-6 py-3 rounded-xl text-white font-semibold shadow-lg ${
        type === 'success' ? 'bg-emerald-500' : 'bg-red-500'
    }`;

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}

// ✅ MODAL
function openModal(id) {
    const modal = document.getElementById(id);
    modal.classList.add('active');
    modal.querySelector('.modal-content').classList.add('active');
}

function closeModal(id) {
    const modal = document.getElementById(id);
    modal.classList.remove('active');
    modal.querySelector('.modal-content').classList.remove('active');
}

// =======================
// ✅ ADD TASK (NO RELOAD)
// =======================
document.getElementById('addTaskForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = e.target.querySelector('button[type="submit"]');
    btn.innerHTML = "Creating...";
    btn.disabled = true;

    const formData = new FormData(e.target);

    try {
        const res = await fetch(API_BASE, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
            },
            body: formData
        });

        if (!res.ok) throw new Error();

        const task = await res.json();

        addTaskToUI(task);
        closeModal('addModal');
        showToast("Task created");

        e.target.reset();

    } catch {
        showToast("Failed to create task", "error");
    }

    btn.innerHTML = "Create Task";
    btn.disabled = false;
});

// =======================
// ✅ ADD TASK TO UI
// =======================
function addTaskToUI(task) {
    const container = document.querySelector('.grid.gap-4');

    const html = `
    <div class="task-card animate-fade-in" data-task-id="${task.id}">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <button class="toggle-status h-6 w-6 border rounded-lg"></button>
                    <h3 class="text-white font-semibold">${task.title}</h3>
                </div>
                <p class="text-sm text-slate-400">${task.description ?? ''}</p>
            </div>
        </div>
    </div>
    `;

    container.insertAdjacentHTML('afterbegin', html);
}

// =======================
// ✅ TOGGLE STATUS
// =======================
document.querySelectorAll('.toggle-status').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.taskId;

        await fetch(`${API_BASE}/${id}/toggle`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });

        const card = btn.closest('.task-card');
        const title = card.querySelector('h3');

        btn.classList.toggle('bg-emerald-500');
        title.classList.toggle('line-through');

        showToast("Status updated");
    });
});

// =======================
// ✅ DELETE
// =======================
document.querySelectorAll('.delete-task').forEach(btn => {
    btn.addEventListener('click', () => {
        currentDeleteTaskId = btn.dataset.taskId;
        openModal('deleteModal');
    });
});

document.getElementById('confirmDeleteBtn')?.addEventListener('click', async () => {
    if (!currentDeleteTaskId) return;

    try {
        await fetch(`${API_BASE}/${currentDeleteTaskId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });

        document.querySelector(`[data-task-id="${currentDeleteTaskId}"]`).remove();

        closeModal('deleteModal');
        showToast("Task deleted");

    } catch {
        showToast("Delete failed", "error");
    }
});

// =======================
// ✅ EDIT
// =======================
document.getElementById('editTaskForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.getElementById('editTaskId').value;
    const formData = new FormData(e.target);

    try {
        await fetch(`${API_BASE}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        });

        showToast("Task updated");
        closeModal('editModal');

    } catch {
        showToast("Update failed", "error");
    }
});

    </script>
</body>
</html>
