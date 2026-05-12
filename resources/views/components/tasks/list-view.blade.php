@props(['tasks'])

<!-- Tasks Content (List View) -->
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
