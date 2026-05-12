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
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-3xl font-semibold tracking-tight text-white">Tasks</h1>
            <div class="flex items-center gap-3">
                <!-- View Toggle -->
                <div class="flex bg-slate-800 p-1 rounded-xl mr-2">
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'" class="p-1.5 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    </button>
                    <button @click="viewMode = 'board'" :class="viewMode === 'board' ? 'bg-indigo-500 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200'" class="p-1.5 rounded-lg transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path></svg>
                    </button>
                </div>

                <a href="{{ route('planner.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-xl hover:bg-slate-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Planner View
                </a>
                <button @click="addModalOpen = true" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 w-full sm:w-auto justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Task
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex items-center gap-2 border-b border-slate-800 pb-px mt-6">
            <a href="{{ route('tasks.index') }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ !request()->has('filter') || request()->filter == 'all' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                All
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->filter == 'pending' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                New
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'in_progress']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->filter == 'in_progress' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                In Progress
            </a>
            <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-4 py-3 text-sm font-medium border-b-2 transition-colors {{ request()->filter == 'completed' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-300 hover:border-slate-700' }}">
                Completed
            </a>
        </div>

        <!-- Category Filter Pills -->
        <div class="flex items-center gap-3 flex-wrap mt-6">
            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Categories:</span>
            <a href="{{ route('tasks.index', array_merge(request()->except('category'), [])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ !request()->has('category') ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                All
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => $cat->id])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all flex items-center gap-1.5 {{ request('category') == $cat->id ? 'border border-indigo-500/20 bg-indigo-500/10 text-indigo-400' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                    <span class="w-2 h-2 rounded-full" style="background-color: {{ $cat->color }}"></span>
                    {{ $cat->name }}
                    <span class="text-slate-600">({{ $cat->tasks_count }})</span>
                </a>
            @endforeach
            <a href="{{ route('tasks.index', array_merge(request()->except('category'), ['category' => 'uncategorized'])) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg transition-all {{ request('category') === 'uncategorized' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 bg-slate-800/50 border border-slate-700/50 hover:border-slate-600' }}">
                Uncategorized
            </a>
        </div>

        <!-- Tasks Content -->
        <div class="mt-6" x-show="viewMode === 'list'" x-transition>
            @if($tasks->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center border-2 border-dashed border-slate-800 rounded-2xl mt-6">
                    <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center text-slate-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-200 mb-1">No tasks found</h3>
                    <p class="text-sm text-slate-500 mb-6">Get started by creating a new task.</p>
                    <button @click="addModalOpen = true" class="px-4 py-2 text-sm font-medium text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 rounded-xl transition-colors">
                        + Add your first task
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 gap-4 mt-6">
                    @foreach($tasks as $task)
                        <div class="group flex flex-col sm:flex-row gap-4 p-5 bg-slate-900 border {{ $task->status === 'completed' ? 'border-emerald-500/20 bg-emerald-500/5' : 'border-slate-800 hover:border-indigo-500/30' }} rounded-2xl transition-all duration-300 hover:shadow-xl hover:shadow-indigo-500/5">
                            <div class="flex items-start gap-4 flex-1 min-w-0">
                                <!-- Status Toggle -->
                                <form method="POST" action="{{ route('tasks.complete', $task->id) }}" class="shrink-0 mt-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors {{ $task->status === 'completed' ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-600 hover:border-indigo-500 text-transparent hover:text-indigo-500' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h3 class="text-base font-medium truncate {{ $task->status === 'completed' ? 'text-slate-400 line-through' : 'text-slate-200' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->priority === 'high')
                                            <span class="w-2 h-2 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)]" title="High Priority"></span>
                                        @endif
                                    </div>
                                    @if($task->description)
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $task->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 text-xs font-medium flex-wrap">
                                        @if($task->category)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md" style="background-color: {{ $task->category->color }}15; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}33;">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $task->category->color }}"></span>
                                                {{ $task->category->name }}
                                            </span>
                                        @endif

                                        @if($task->due_date)
                                            <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-800 text-slate-300 border border-slate-700">
                                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $task->due_date->format('M d, h:i A') }}
                                            </span>
                                        @endif

                                        @if($task->status === 'completed')
                                            <span class="inline-flex items-center gap-1 text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-md border border-emerald-500/20">
                                                Completed
                                            </span>
                                        @endif
                                        <span class="text-slate-600 flex items-center gap-1">
                                            Added {{ $task->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 sm:opacity-0 group-hover:opacity-100 transition-opacity justify-end sm:justify-start">
                                <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}', due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}', priority: '{{ $task->priority }}' }; editModalOpen = true" class="p-2 text-slate-400 hover:text-indigo-400 hover:bg-indigo-500/10 rounded-lg transition-colors" title="Edit Task">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete Task">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Kanban Board Content -->
        <div class="mt-8 overflow-x-auto pb-6" x-show="viewMode === 'board'" x-cloak x-transition>
            <div class="flex gap-6 min-w-[1000px] h-[calc(100vh-320px)]">
                @foreach(['pending' => 'To Do', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $status => $label)
                    <div class="flex-1 flex flex-col bg-slate-900/40 rounded-3xl border border-slate-800/60 p-4">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $status === 'pending' ? 'bg-slate-500' : ($status === 'in_progress' ? 'bg-indigo-500' : 'bg-emerald-500') }}"></span>
                                <h2 class="text-sm font-semibold text-white uppercase tracking-wider">{{ $label }}</h2>
                            </div>
                            <span class="text-xs font-bold text-slate-500 bg-slate-800/50 px-2 py-0.5 rounded-full">
                                {{ $tasks->where('status', $status)->count() }}
                            </span>
                        </div>

                        <div class="flex-1 overflow-y-auto space-y-3 px-1 custom-scrollbar kanban-column" data-status="{{ $status }}">
                            @foreach($tasks->where('status', $status) as $task)
                                <div class="kanban-item group bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-sm hover:border-indigo-500/40 hover:shadow-lg hover:shadow-indigo-500/5 transition-all cursor-grab active:cursor-grabbing" data-id="{{ $task->id }}">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-start justify-between gap-2">
                                            <h3 class="text-sm font-medium text-slate-200 group-hover:text-white transition-colors {{ $task->status === 'completed' ? 'line-through text-slate-500' : '' }}">
                                                {{ $task->title }}
                                            </h3>
                                            @if($task->priority === 'high')
                                                <span class="shrink-0 w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_8px_rgba(239,68,68,0.5)] mt-1.5"></span>
                                            @endif
                                        </div>
                                        
                                        @if($task->description)
                                            <p class="text-xs text-slate-500 line-clamp-2">{{ $task->description }}</p>
                                        @endif

                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex items-center gap-2">
                                                @if($task->category)
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="background-color: {{ $task->category->color }}15; color: {{ $task->category->color }}; border: 1px solid {{ $task->category->color }}33;">
                                                        {{ $task->category->name }}
                                                    </span>
                                                @endif
                                                @if($task->due_date)
                                                    <span class="text-[10px] text-slate-500 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ $task->due_date->format('M d') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}', due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}', priority: '{{ $task->priority }}' }; editModalOpen = true" class="text-slate-600 hover:text-white transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Add Task Slide Panel -->
        <div x-cloak x-show="addModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="addModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="addModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-slate-900 shadow-2xl border-l border-slate-800">
                            <div class="px-6 py-6 border-b border-slate-800 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-white">Create New Task</h2>
                                <button @click="addModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto px-6 py-6" x-data="{ 
                                dueDate: '',
                                setTime(hour) {
                                    let now = new Date();
                                    if (this.dueDate) {
                                        now = new Date(this.dueDate);
                                    }
                                    now.setHours(hour, 0, 0, 0);
                                    const tzOffset = now.getTimezoneOffset() * 60000;
                                    const localISOTime = (new Date(now.getTime() - tzOffset)).toISOString().slice(0, 16);
                                    this.dueDate = localISOTime;
                                }
                            }">
                                <form id="addTaskForm" method="POST" action="{{ route('tasks.store') }}" class="space-y-6">
                                    @csrf
                                    <div>
                                        <label for="title" class="block text-sm font-medium text-slate-300 mb-1.5">Task Title <span class="text-red-400">*</span></label>
                                        <input type="text" name="title" id="title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="e.g., Design new landing page">
                                    </div>
                                    
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-slate-300 mb-1.5">Description (Optional)</label>
                                        <textarea name="description" id="description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder-slate-600" placeholder="Add details..."></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="category_id" class="block text-sm font-medium text-slate-300 mb-1.5">Category</label>
                                            <select name="category_id" id="category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
                                                <option value="">None</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="priority" class="block text-sm font-medium text-slate-300 mb-1.5">Priority</label>
                                            <select name="priority" id="priority" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
                                                <option value="low">Low</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-950/50 rounded-2xl border border-slate-800 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label for="due_date" class="text-sm font-medium text-slate-300">Schedule from Calendar</label>
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="datetime-local" name="due_date" id="due_date" x-model="dueDate" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all [color-scheme:dark]">
                                        
                                        <div>
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block mb-2">Quick Time Slots</span>
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach(['09', '10', '11', '12', '14', '15', '16', '17'] as $h)
                                                    <button type="button" @click="setTime({{ $h }})" class="py-2 text-xs font-medium bg-slate-800 border border-slate-700 rounded-lg text-slate-400 hover:bg-indigo-500/10 hover:text-indigo-400 hover:border-indigo-500/30 transition-all">
                                                        {{ $h }}:00
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-end gap-3 bg-slate-900/50">
                                <button @click="addModalOpen = false" type="button" class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                    Cancel
                                </button>
                                <button form="addTaskForm" type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all">
                                    Save Task
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Task Slide Panel -->
        <div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="editModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="editModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-slate-900 shadow-2xl border-l border-slate-800">
                            <div class="px-6 py-6 border-b border-slate-800 flex items-center justify-between">
                                <h2 class="text-lg font-semibold text-white">Edit Task</h2>
                                <button @click="editModalOpen = false" class="text-slate-400 hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto px-6 py-6" x-data="{
                                get updateUrl() { return taskToEdit ? '/tasks/' + taskToEdit.id : ''; },
                                setTime(hour) {
                                    let now = new Date();
                                    if (taskToEdit.due_date) {
                                        now = new Date(taskToEdit.due_date);
                                    }
                                    now.setHours(hour, 0, 0, 0);
                                    const tzOffset = now.getTimezoneOffset() * 60000;
                                    const localISOTime = (new Date(now.getTime() - tzOffset)).toISOString().slice(0, 16);
                                    taskToEdit.due_date = localISOTime;
                                }
                            }">
                                <form id="editTaskForm" method="POST" :action="updateUrl" class="space-y-6">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label for="edit_title" class="block text-sm font-medium text-slate-300 mb-1.5">Task Title <span class="text-red-400">*</span></label>
                                        <input type="text" name="title" id="edit_title" x-model="taskToEdit.title" required class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
                                    </div>
                                    
                                    <div>
                                        <label for="edit_description" class="block text-sm font-medium text-slate-300 mb-1.5">Description (Optional)</label>
                                        <textarea name="description" id="edit_description" x-model="taskToEdit.description" rows="3" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all"></textarea>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="edit_category_id" class="block text-sm font-medium text-slate-300 mb-1.5">Category</label>
                                            <select name="category_id" id="edit_category_id" x-model="taskToEdit.category_id" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
                                                <option value="">None</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label for="edit_priority" class="block text-sm font-medium text-slate-300 mb-1.5">Priority</label>
                                            <select name="priority" id="edit_priority" x-model="taskToEdit.priority" class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="p-4 bg-slate-950/50 rounded-2xl border border-slate-800 space-y-4">
                                        <div class="flex items-center justify-between">
                                            <label for="edit_due_date" class="text-sm font-medium text-slate-300">Reschedule from Calendar</label>
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="datetime-local" name="due_date" id="edit_due_date" x-model="taskToEdit.due_date" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-2.5 text-slate-200 focus:outline-none focus:border-indigo-500 transition-all [color-scheme:dark]">
                                        
                                        <div>
                                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider block mb-2">Change Time Slot</span>
                                            <div class="grid grid-cols-4 gap-2">
                                                @foreach(['09', '10', '11', '12', '14', '15', '16', '17'] as $h)
                                                    <button type="button" @click="setTime({{ $h }})" class="py-2 text-xs font-medium bg-slate-800 border border-slate-700 rounded-lg text-slate-400 hover:bg-indigo-500/10 hover:text-indigo-400 transition-all">
                                                        {{ $h }}:00
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="px-6 py-4 border-t border-slate-800 flex items-center justify-end gap-3 bg-slate-900/50">
                                <button @click="editModalOpen = false" type="button" class="px-4 py-2.5 text-sm font-medium text-slate-300 hover:text-white transition-colors">
                                    Cancel
                                </button>
                                <button form="editTaskForm" type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all">
                                    Update Task
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
