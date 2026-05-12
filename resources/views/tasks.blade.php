<x-layouts.app title="Tasks - Time & Productivity Analyzer">
    <div x-data="taskPage()">
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="flex items-center justify-between p-4 mb-4 text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-xl" role="alert">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        @endif

        <!-- Header & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">Tasks</h1>
            <div class="flex items-center gap-4">
                <!-- View Toggle -->
                <div class="flex bg-gray-100 p-1 rounded-2xl mr-2">
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-white text-gray-900 shadow-xl' : 'text-gray-400 hover:text-gray-900'" class="p-2 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </button>
                    <button @click="viewMode = 'board'" :class="viewMode === 'board' ? 'bg-white text-gray-900 shadow-xl' : 'text-gray-400 hover:text-gray-900'" class="p-2 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                    </button>
                </div>

                <a href="{{ route('planner.index') }}" class="px-6 py-3.5 text-xs font-bold text-gray-500 bg-white border border-gray-100 rounded-2xl hover:bg-gray-50 transition-all flex items-center gap-3 shadow-sm uppercase tracking-widest">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Planner View
                </a>
                <button @click="addModalOpen = true" class="px-8 py-3.5 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 flex items-center gap-3 w-full sm:w-auto justify-center uppercase tracking-widest">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Task
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-2 border-b border-gray-100 pb-px mt-10">
            <a href="{{ route('tasks.index') }}" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all {{ !request()->has('filter') || request()->filter == 'all' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200' }}">
                All Tasks
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all {{ request()->filter == 'pending' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200' }}">
                New
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'in_progress']) }}" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all {{ request()->filter == 'in_progress' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200' }}">
                Active
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-6 py-4 text-[10px] font-bold uppercase tracking-widest border-b-2 transition-all {{ request()->filter == 'completed' ? 'border-emerald-600 text-emerald-600' : 'border-transparent text-gray-400 hover:text-gray-900 hover:border-gray-200' }}">
                Finished
            </a>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-4 flex-wrap mt-8">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sort by:</span>
            <a href="{{ route('tasks.index', array_merge(request()->except('category'), [])) }}" class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all {{ !request()->has('category') ? 'bg-gray-900 text-white shadow-xl shadow-gray-200' : 'text-gray-500 bg-white border border-gray-100 hover:bg-gray-50 shadow-sm' }}">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all flex items-center gap-2 {{ request('category') == $cat->id ? 'bg-gray-900 text-white shadow-xl shadow-gray-200' : 'text-gray-500 bg-white border border-gray-100 hover:bg-gray-50 shadow-sm' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $cat->color }}"></span>
                    {{ $cat->name }}
                    <span class="opacity-50">({{ $cat->tasks_count }})</span>
                </a>
            @endforeach
            <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => 'uncategorized'])) }}" class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest rounded-xl transition-all {{ request('category') === 'uncategorized' ? 'bg-gray-900 text-white shadow-xl shadow-gray-200' : 'text-gray-500 bg-white border border-gray-100 hover:bg-gray-50 shadow-sm' }}">
                Uncategorized
            </a>
        </div>

        <x-tasks.list-view :tasks="$tasks" />
        <x-tasks.kanban-board :tasks="$tasks" />
        <x-tasks.add-modal :categories="$categories" />
        <x-tasks.edit-modal :categories="$categories" />
    </div>

    <x-slot:scripts>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('taskPage', () => ({
                    viewMode: 'list', // or 'board'
                    addModalOpen: false,
                    editModalOpen: false,
                    taskToEdit: { title: '', description: '', category_id: '', due_date: '', priority: 'medium' },

                    init() {
                        this.$watch('viewMode', value => {
                            if (value === 'board') {
                                setTimeout(() => this.initSortable(), 100);
                            }
                        });
                        if (this.viewMode === 'board') this.initSortable();
                    },

                    initSortable() {
                        const columns = document.querySelectorAll('.kanban-column');
                        columns.forEach(column => {
                            new Sortable(column, {
                                group: 'tasks',
                                animation: 150,
                                ghostClass: 'opacity-40',
                                dragClass: 'rotate-2',
                                onEnd: async (evt) => {
                                    const taskId = evt.item.dataset.id;
                                    const newStatus = evt.to.dataset.status;
                                    
                                    if (evt.from === evt.to) return;

                                    try {
                                        const response = await fetch(`/tasks/${taskId}/status`, {
                                            method: 'PATCH',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ status: newStatus })
                                        });

                                        if (!response.ok) {
                                            throw new Error('Failed to update status');
                                        }
                                    } catch (error) {
                                        console.error(error);
                                        alert('Failed to update task status. Please refresh.');
                                        window.location.reload();
                                    }
                                }
                            });
                        });
                    }
                }));
            });
        </script>
    </x-slot:scripts>
</x-layouts.app>
