@props(['tasks'])

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
                                    
                                    <button @click="taskToEdit = { id: {{ $task->id }}, title: '{{ addslashes($task->title) }}', description: '{{ addslashes($task->description) }}', category_id: '{{ $task->category_id ?? '' }}', due_date: '{{ $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '' }}', priority: '{{ $task->priority }}', subtasks: {{ htmlspecialchars(json_encode($task->subtasks), ENT_QUOTES, 'UTF-8') }} }; editModalOpen = true" class="text-gray-400 hover:text-emerald-600 p-1.5 hover:bg-emerald-50 rounded-lg transition-all">
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
