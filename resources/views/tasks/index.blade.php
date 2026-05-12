<x-layouts.app title="Tasks - Time & Productivity Analyzer">
    <x-slot:styles>
        <style>
            .task-card {
                @apply relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/5 group;
            }
            .priority-badge {
                @apply inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider transition-all duration-200;
            }
            .priority-high {
                @apply bg-rose-50 text-rose-600 border border-rose-100;
            }
            .priority-medium {
                @apply bg-amber-50 text-amber-600 border border-amber-100;
            }
            .priority-low {
                @apply bg-emerald-50 text-emerald-600 border border-emerald-100;
            }
            .status-badge {
                @apply inline-flex items-center rounded-full px-3 py-1 text-[10px] font-bold uppercase tracking-wider transition-all duration-200;
            }
            .status-completed {
                @apply bg-emerald-100 text-emerald-700 border border-emerald-200;
            }
            .status-pending {
                @apply bg-gray-50 text-gray-500 border border-gray-200;
            }
            .modal-backdrop {
                @apply fixed inset-0 z-50 bg-gray-900/20 backdrop-blur-sm opacity-0 transition-opacity duration-300 pointer-events-none;
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
                @apply w-full max-w-lg rounded-[2.5rem] border border-gray-100 bg-white p-8 shadow-2xl transform transition-all duration-300 scale-95;
            }
            .modal-content.active .modal-card {
                @apply scale-100;
            }
            .input-field {
                @apply w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 transition-all duration-200 focus:border-emerald-500/50 focus:bg-white focus:outline-none focus:ring-4 focus:ring-emerald-500/10;
            }
            .tab-button {
                @apply px-4 py-2 rounded-xl text-sm font-bold transition-all duration-200 text-gray-400 hover:text-gray-900 hover:bg-gray-100;
            }
            .tab-button.active {
                @apply bg-emerald-50 text-emerald-600 border border-emerald-100;
            }
            .animate-fade-in {
                animation: fadeIn 0.3s ease-out;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </x-slot:styles>

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Tasks</h1>
        <button type="button" id="openAddModal" class="px-6 py-3 text-sm font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M12 5v14M5 12h14"></path>
            </svg>
            Add Task
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $totalTasks }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Tasks</p>
        </div>
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $completedTasks }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Completed</p>
        </div>
        <div class="group bg-white border border-gray-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-4xl font-extrabold text-gray-900 mb-1 tabular-nums">{{ $pendingTasks }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Pending</p>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="flex-1 flex gap-3">
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                <input type="text" name="search" placeholder="Search tasks..." value="{{ request('search', '') }}" class="input-field pl-10 h-full py-2.5 text-sm">
            </div>
            <select name="sort" class="input-field w-auto py-2.5 text-sm">
                <option value="due_date" @if (request('sort') === 'due_date') selected @endif>Due Date</option>
                <option value="priority" @if (request('sort') === 'priority') selected @endif>Priority</option>
                <option value="created_at" @if (request('sort') === 'created_at') selected @endif>Newest</option>
            </select>
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-slate-800 border border-slate-700 rounded-xl hover:bg-slate-700 transition-all shadow-sm">
                Filter
            </button>
        </form>
    </div>

    <!-- Status Filter Tabs -->
    <div class="flex gap-2">
        <a href="{{ route('tasks.index', ['status' => 'all']) }}" class="tab-button border border-transparent @if (request('status') === 'all' || !request('status')) active @endif">All</a>
        <a href="{{ route('tasks.index', ['status' => 'pending']) }}" class="tab-button border border-transparent @if (request('status') === 'pending') active @endif">Pending</a>
        <a href="{{ route('tasks.index', ['status' => 'completed']) }}" class="tab-button border border-transparent @if (request('status') === 'completed') active @endif">Completed</a>
    </div>

    <!-- Tasks List -->
    @if ($tasks->count() > 0)
        <div class="grid gap-4">
            @foreach ($tasks as $task)
                <div class="task-card flex items-start justify-between gap-4 animate-fade-in" data-task-id="{{ $task->id }}">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-3 mb-2">
                            <button type="button" class="toggle-status flex-shrink-0 h-6 w-6 rounded-lg border-2 transition-all duration-200 flex items-center justify-center @if ($task->status === 'completed') border-emerald-500 bg-emerald-500 @else border-gray-300 hover:border-emerald-500 @endif" data-task-id="{{ $task->id }}">
                                @if ($task->status === 'completed')
                                    <svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>
                                @endif
                            </button>
                            <h3 class="text-lg font-bold text-gray-900 @if ($task->status === 'completed') line-through text-gray-300 @endif">
                                {{ $task->title }}
                            </h3>
                        </div>
                        @if ($task->description)
                            <p class="mb-3 text-sm text-gray-500 pl-9 line-clamp-2 leading-relaxed">{{ $task->description }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-2 pl-9 mt-1">
                            <span class="priority-badge priority-{{ $task->priority }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                            <span class="status-badge status-{{ $task->status }}">
                                {{ ucfirst($task->status) }}
                            </span>
                            @if ($task->due_date)
                                <span class="text-xs font-medium text-slate-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $task->due_date->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button" class="edit-task p-2 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" data-task-id="{{ $task->id }}" data-title="{{ $task->title }}" data-description="{{ $task->description }}" data-priority="{{ $task->priority }}" data-due-date="{{ $task->due_date?->format('Y-m-d') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button type="button" class="delete-task p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" data-task-id="{{ $task->id }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        @endif
    @else
        <div class="bg-white border border-gray-100 rounded-[3rem] p-16 text-center flex flex-col items-center justify-center animate-fade-in shadow-sm">
            <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center mb-6 text-gray-300">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No tasks found</h3>
            <p class="text-gray-500 max-w-xs">Get started by creating a new task to stay organized and productive.</p>
        </div>
    @endif

    <!-- Add Task Modal -->
    <div id="addModal" class="modal-backdrop">
        <div class="modal-content w-full">
            <div class="modal-card">
                <div class="mb-8 flex items-center justify-between">
                    <h2 class="text-2xl font-extrabold text-gray-900">Add New Task</h2>
                    <button type="button" class="close-modal p-2 text-gray-400 hover:text-gray-900 transition-colors" data-modal="addModal">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="addTaskForm" class="flex flex-col gap-6">
                    @csrf
                    <div>
                        <label class="mb-2 block text-xs font-bold text-gray-400 uppercase tracking-widest">Task Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="title" required class="input-field" placeholder="E.g. Setup project repository">
                    </div>
                    <div>
                        <label class="mb-2 block text-xs font-bold text-gray-400 uppercase tracking-widest">Description</label>
                        <textarea name="description" rows="3" class="input-field resize-none" placeholder="Add more details..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-xs font-bold text-gray-400 uppercase tracking-widest">Priority <span class="text-rose-500">*</span></label>
                            <select name="priority" required class="input-field">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-xs font-bold text-gray-400 uppercase tracking-widest">Due Date</label>
                            <input type="date" name="due_date" class="input-field">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3">
                        <button type="button" class="close-modal flex-1 px-4 py-3 text-sm font-bold text-gray-400 bg-gray-50 hover:bg-gray-100 rounded-2xl transition-colors uppercase tracking-widest" data-modal="addModal">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-2xl transition-all shadow-xl shadow-emerald-100 uppercase tracking-widest">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editModal" class="modal-backdrop">
        <div class="modal-content w-full">
            <div class="modal-card">
                <div class="mb-6 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-white">Edit Task</h2>
                    <button type="button" class="close-modal text-slate-400 hover:text-white transition-colors" data-modal="editModal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form id="editTaskForm" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editTaskId" name="task_id">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Task Title <span class="text-red-400">*</span></label>
                        <input type="text" name="title" required class="input-field">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-300">Description</label>
                        <textarea name="description" rows="3" class="input-field resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Priority <span class="text-red-400">*</span></label>
                            <select name="priority" required class="input-field">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-medium text-slate-300">Due Date</label>
                            <input type="date" name="due_date" class="input-field">
                        </div>
                    </div>
                    <div class="mt-2 flex gap-3">
                        <button type="button" class="close-modal flex-1 px-4 py-2.5 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors" data-modal="editModal">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2.5 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 rounded-xl transition-colors shadow-lg shadow-indigo-500/25">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal-backdrop">
        <div class="modal-content w-full">
            <div class="modal-card sm:max-w-md">
                <div class="mb-6 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500 shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Delete Task</h2>
                        <p class="mt-2 text-sm text-slate-400">Are you sure you want to delete this task? This action cannot be undone.</p>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-slate-300 bg-slate-800 hover:bg-slate-700 rounded-xl transition-colors" data-modal="deleteModal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-xl transition-colors shadow-lg shadow-red-500/25">Delete Task</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="hidden fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl text-sm font-medium text-white shadow-lg transition-all animate-fade-in"></div>

    <x-slot:scripts>
        <script>
            const API_BASE = '{{ url('/tasks') }}';
            let currentDeleteTaskId = null;

            // TOAST
            function showToast(message, type = 'success') {
                const toast = document.getElementById('toast');
                toast.textContent = message;
                toast.className = `fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl text-sm font-medium text-white shadow-xl flex items-center gap-2 animate-fade-in ${
                    type === 'success' ? 'bg-emerald-500 border border-emerald-400' : 'bg-red-500 border border-red-400'
                }`;
                toast.classList.remove('hidden');
                setTimeout(() => toast.classList.add('hidden'), 3000);
            }

            // MODAL
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

            document.querySelectorAll('.close-modal').forEach(btn => {
                btn.addEventListener('click', () => closeModal(btn.dataset.modal));
            });

            document.getElementById('openAddModal')?.addEventListener('click', () => openModal('addModal'));

            // ADD TASK
            document.getElementById('addTaskForm')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = e.target.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = "Creating...";
                btn.disabled = true;

                try {
                    const res = await fetch(API_BASE, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': new FormData(e.target).get('_token') },
                        body: new FormData(e.target)
                    });
                    if (!res.ok) throw new Error();
                    // Simple refresh to re-render with new data
                    window.location.reload(); 
                } catch {
                    showToast("Failed to create task", "error");
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });

            // TOGGLE STATUS
            document.querySelectorAll('.toggle-status').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = btn.dataset.taskId;
                    try {
                        const res = await fetch(`${API_BASE}/${id}/complete`, {
                            method: 'PATCH',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        });
                        if (!res.ok) throw new Error();
                        
                        const card = btn.closest('.task-card');
                        const title = card.querySelector('h3');
                        
                        const isCompleted = btn.classList.contains('bg-emerald-500');
                        if (isCompleted) {
                            btn.classList.remove('border-emerald-500', 'bg-emerald-500');
                            btn.classList.add('border-slate-600');
                            btn.innerHTML = '';
                            title.classList.remove('line-through', 'text-slate-500');
                            title.classList.add('text-slate-200');
                        } else {
                            btn.classList.add('border-emerald-500', 'bg-emerald-500');
                            btn.classList.remove('border-slate-600');
                            btn.innerHTML = '<svg class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"></path></svg>';
                            title.classList.add('line-through', 'text-slate-500');
                            title.classList.remove('text-slate-200');
                        }
                        showToast("Status updated");
                    } catch {
                        showToast("Failed to update status", "error");
                    }
                });
            });

            // DELETE
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
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });
                    document.querySelector(`.task-card[data-task-id="${currentDeleteTaskId}"]`).remove();
                    closeModal('deleteModal');
                    showToast("Task deleted");
                } catch {
                    showToast("Delete failed", "error");
                }
            });

            // EDIT
            document.querySelectorAll('.edit-task').forEach(btn => {
                btn.addEventListener('click', () => {
                    const form = document.getElementById('editTaskForm');
                    form.querySelector('#editTaskId').value = btn.dataset.taskId;
                    form.querySelector('input[name="title"]').value = btn.dataset.title;
                    form.querySelector('textarea[name="description"]').value = btn.dataset.description;
                    form.querySelector('select[name="priority"]').value = btn.dataset.priority;
                    form.querySelector('input[name="due_date"]').value = btn.dataset.dueDate || '';
                    openModal('editModal');
                });
            });

            document.getElementById('editTaskForm')?.addEventListener('submit', async (e) => {
                e.preventDefault();
                const id = document.getElementById('editTaskId').value;
                const btn = e.target.querySelector('button[type="submit"]');
                const originalText = btn.innerHTML;
                btn.innerHTML = "Saving...";
                btn.disabled = true;

                try {
                    const formData = new FormData(e.target);
                    const res = await fetch(`${API_BASE}/${id}`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    if (!res.ok) throw new Error();
                    window.location.reload();
                } catch {
                    showToast("Update failed", "error");
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                }
            });
        </script>
    </x-slot:scripts>
</x-layouts.app>
