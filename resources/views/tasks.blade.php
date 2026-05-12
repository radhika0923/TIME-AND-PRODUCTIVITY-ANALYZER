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

        <!-- Tasks Content -->
        <div class="mt-8" x-show="viewMode === 'list'" x-transition>
            @if($tasks->isEmpty())
                <div class="flex flex-col items-center justify-center py-24 text-center border border-gray-100 bg-white rounded-[2.5rem] shadow-sm mt-8">
                    <div class="w-20 h-20 bg-gray-50 rounded-[2rem] flex items-center justify-center text-gray-400 mb-6">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-gray-900 mb-2 tracking-tight">No tasks found</h3>
                    <p class="text-sm text-gray-500 font-medium mb-8">Ready to start something new? Create your first task.</p>
                    <button @click="addModalOpen = true" class="px-8 py-4 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest transition-all transform hover:-translate-y-1">
                        + Create First Task
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 mt-8">
                    @foreach($tasks as $task)
                        <div class="group flex flex-col sm:flex-row gap-6 p-8 bg-white border {{ $task->status === 'completed' ? 'border-emerald-100 bg-emerald-50/20' : 'border-gray-100' }} rounded-[2.5rem] transition-all duration-300 hover:shadow-xl hover:shadow-emerald-50">
                            <div class="flex items-start gap-6 flex-1 min-w-0">
                                <!-- Status Toggle -->
                                <form method="POST" action="{{ route('tasks.complete', $task->id) }}" class="shrink-0">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-2xl border-2 flex items-center justify-center transition-all {{ $task->status === 'completed' ? 'bg-emerald-600 border-emerald-600 text-white shadow-lg shadow-emerald-200' : 'border-gray-200 bg-white hover:border-emerald-500 text-transparent hover:text-emerald-500' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
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
                                        @if($task->priority === 'high')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-xl bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-widest border border-rose-100 shadow-sm">
                                                High Priority
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-sm text-gray-500 mb-6 line-clamp-2 font-medium leading-relaxed {{ $task->status === 'completed' ? 'line-through opacity-50' : '' }}">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-6 text-[10px] font-bold uppercase tracking-widest">
                                        @if($task->due_date)
                                            <span class="inline-flex items-center gap-2 text-gray-500 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Due: {{ $task->due_date->format('M d, Y') }}
                                            </span>
                                        @endif
                                        
                                        @php
                                            $totalSeconds = $task->timeLogs->sum('duration');
                                            $formattedTime = \App\Support\Duration::format($totalSeconds);
                                        @endphp
                                        <span class="inline-flex items-center gap-2 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Logged: {{ $formattedTime }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 justify-end sm:justify-start">
                                <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}', due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}', priority: '{{ $task->priority }}' }; editModalOpen = true" class="p-3 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-2xl transition-all border border-transparent hover:border-emerald-100 shadow-sm" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Delete this task?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-3 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-2xl transition-all border border-transparent hover:border-rose-100 shadow-sm" title="Delete">
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
        <div class="mt-8 overflow-x-auto pb-12" x-show="viewMode === 'board'" x-cloak x-transition>
            <div class="flex gap-8 min-w-[1200px] items-start">
                @foreach(['pending' => 'Backlog', 'in_progress' => 'Active', 'completed' => 'Finished'] as $status => $label)
                    <div class="flex-1 flex flex-col bg-gray-50/50 rounded-[2.5rem] border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-6 px-4">
                            <div class="flex items-center gap-3">
                                <span class="w-3 h-3 rounded-full {{ $status === 'pending' ? 'bg-gray-300' : ($status === 'in_progress' ? 'bg-emerald-500' : 'bg-emerald-700') }} shadow-sm"></span>
                                <h2 class="text-xs font-bold text-gray-900 uppercase tracking-widest">{{ $label }}</h2>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 bg-white border border-gray-100 px-3 py-1 rounded-xl shadow-sm">
                                {{ $tasks->where('status', $status)->count() }}
                            </span>
                        </div>

                        <div class="flex-1 space-y-4 px-2 kanban-column" data-status="{{ $status }}">
                            @foreach($tasks->where('status', $status) as $task)
                                <div class="kanban-item group bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-emerald-50 hover:border-emerald-100 transition-all cursor-grab active:cursor-grabbing" data-id="{{ $task->id }}">
                                    <div class="flex flex-col gap-4">
                                        <div class="flex items-start justify-between gap-4">
                                            <h3 class="text-sm font-bold text-gray-900 leading-snug {{ $task->status === 'completed' ? 'line-through opacity-50' : '' }}">
                                                {{ $task->title }}
                                            </h3>
                                            @if($task->priority === 'high')
                                                <span class="shrink-0 w-2 h-2 rounded-full bg-rose-500 shadow-lg shadow-rose-200 mt-1.5"></span>
                                            @endif
                                        </div>
                                        
                                        @if($task->description)
                                            <p class="text-[11px] text-gray-500 line-clamp-2 font-medium leading-relaxed">{{ $task->description }}</p>
                                        @endif

                                        <div class="flex items-center justify-between mt-2">
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($task->category)
                                                    <span class="text-[9px] font-bold px-2 py-1 rounded-lg border border-gray-100 uppercase tracking-widest shadow-sm" style="color: {{ $task->category->color }}; background-color: {{ $task->category->color }}10">
                                                        {{ $task->category->name }}
                                                    </span>
                                                @endif
                                                @if($task->due_date)
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest flex items-center gap-1.5">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        {{ $task->due_date->format('M d') }}
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}', due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}', priority: '{{ $task->priority }}' }; editModalOpen = true" class="text-gray-400 hover:text-emerald-600 p-1.5 hover:bg-emerald-50 rounded-lg transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
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
                <div x-show="addModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="addModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                        <div class="h-full flex flex-col bg-white shadow-2xl border-l border-gray-100">
                            <div class="px-8 py-8 border-b border-gray-100 flex items-center justify-between bg-white">
                                <div>
                                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">New Task</h2>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Add to your schedule</p>
                                </div>
                                <button @click="addModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto px-8 py-8 custom-scrollbar">
                                <form id="addTaskForm" action="{{ route('tasks.store') }}" method="POST" class="space-y-10">
                                    @csrf
                                    <div class="space-y-6">
                                        <div>
                                            <label for="title" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Task Title <span class="text-rose-500">*</span></label>
                                            <input type="text" name="title" id="title" required placeholder="What needs to be done?" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all placeholder:text-gray-300 shadow-sm">
                                        </div>
                                        
                                        <div>
                                            <label for="description" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Notes (Optional)</label>
                                            <textarea name="description" id="description" rows="4" placeholder="Add some context..." class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-medium focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all placeholder:text-gray-300 shadow-sm"></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label for="category_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Category</label>
                                                <div class="relative">
                                                    <select name="category_id" id="category_id" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer shadow-sm">
                                                        <option value="">None</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="priority" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Priority</label>
                                                <div class="relative">
                                                    <select name="priority" id="priority" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer shadow-sm">
                                                        <option value="low">Low</option>
                                                        <option value="medium" selected>Medium</option>
                                                        <option value="high">High</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 space-y-6" x-data="{
                                            setTime(hour) {
                                                let now = new Date();
                                                if (document.getElementById('due_date').value) {
                                                    now = new Date(document.getElementById('due_date').value);
                                                }
                                                now.setHours(hour, 0, 0, 0);
                                                const tzOffset = now.getTimezoneOffset() * 60000;
                                                const localISOTime = (new Date(now.getTime() - tzOffset)).toISOString().slice(0, 16);
                                                document.getElementById('due_date').value = localISOTime;
                                                this.$dispatch('input', localISOTime);
                                            }
                                        }">
                                            <div class="flex items-center justify-between mb-2">
                                                <label for="due_date" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Schedule Task</label>
                                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            </div>
                                            <input type="datetime-local" name="due_date" id="due_date" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all shadow-sm">
                                            
                                            <div class="pt-4 border-t border-gray-200/50">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Quick Select Time</span>
                                                <div class="grid grid-cols-4 gap-3">
                                                    @foreach(['09', '11', '14', '16'] as $h)
                                                        <button type="button" @click="setTime({{ $h }})" class="py-2.5 text-[10px] font-bold bg-white border border-gray-200 rounded-xl text-gray-500 hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all uppercase tracking-widest shadow-sm">
                                                            {{ $h }}:00
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="px-8 py-6 border-t border-gray-100 flex items-center justify-end gap-4 bg-gray-50/50">
                                <button @click="addModalOpen = false" type="button" class="px-6 py-3.5 text-xs font-bold text-gray-500 hover:text-gray-900 uppercase tracking-widest transition-colors">
                                    Cancel
                                </button>
                                <button form="addTaskForm" type="submit" class="px-8 py-3.5 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest transition-all transform hover:-translate-y-1">
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
                <div x-show="editModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
                    <div x-show="editModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                            <div class="px-8 py-8 border-b border-gray-100 flex items-center justify-between bg-white">
                                <div>
                                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Edit Task</h2>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Refine your plans</p>
                                </div>
                                <button @click="editModalOpen = false" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto px-8 py-8 custom-scrollbar" x-data="{
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
                                <form id="editTaskForm" method="POST" :action="updateUrl" class="space-y-10">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-6">
                                        <div>
                                            <label for="edit_title" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Task Title <span class="text-rose-500">*</span></label>
                                            <input type="text" name="title" id="edit_title" x-model="taskToEdit.title" required class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all shadow-sm">
                                        </div>
                                        
                                        <div>
                                            <label for="edit_description" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Notes (Optional)</label>
                                            <textarea name="description" id="edit_description" x-model="taskToEdit.description" rows="4" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-medium focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all shadow-sm"></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-6">
                                            <div>
                                                <label for="edit_category_id" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Category</label>
                                                <div class="relative">
                                                    <select name="category_id" id="edit_category_id" x-model="taskToEdit.category_id" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer shadow-sm">
                                                        <option value="">None</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label for="edit_priority" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Priority</label>
                                                <div class="relative">
                                                    <select name="priority" id="edit_priority" x-model="taskToEdit.priority" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all appearance-none cursor-pointer shadow-sm">
                                                        <option value="low">Low</option>
                                                        <option value="medium">Medium</option>
                                                        <option value="high">High</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-8 bg-gray-50 rounded-[2.5rem] border border-gray-100 space-y-6">
                                            <div class="flex items-center justify-between mb-2">
                                                <label for="edit_due_date" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Reschedule Task</label>
                                                <div class="p-2 bg-emerald-100 rounded-lg text-emerald-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            </div>
                                            <input type="datetime-local" name="due_date" id="edit_due_date" x-model="taskToEdit.due_date" class="w-full bg-white border border-gray-200 rounded-xl px-5 py-3.5 text-gray-900 font-bold focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/5 transition-all shadow-sm">
                                            
                                            <div class="pt-4 border-t border-gray-200/50">
                                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-4">Quick Select Time</span>
                                                <div class="grid grid-cols-4 gap-3">
                                                    @foreach(['09', '11', '14', '16'] as $h)
                                                        <button type="button" @click="setTime({{ $h }})" class="py-2.5 text-[10px] font-bold bg-white border border-gray-200 rounded-xl text-gray-500 hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-all uppercase tracking-widest shadow-sm">
                                                            {{ $h }}:00
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="px-8 py-6 border-t border-gray-100 flex items-center justify-end gap-4 bg-gray-50/50">
                                <button @click="editModalOpen = false" type="button" class="px-6 py-3.5 text-xs font-bold text-gray-500 hover:text-gray-900 uppercase tracking-widest transition-colors">
                                    Cancel
                                </button>
                                <button form="editTaskForm" type="submit" class="px-8 py-3.5 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest transition-all transform hover:-translate-y-1">
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
