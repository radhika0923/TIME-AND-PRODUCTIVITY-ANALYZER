@props(['categories'])

<!-- Edit Task Slide Panel -->
<div x-cloak x-show="editModalOpen" class="fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
        <div x-show="editModalOpen" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 bg-gray-500/30 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="editModalOpen" x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-white shadow-2xl border-l border-gray-100">
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
                        get updateUrl() { return taskToEdit && taskToEdit.id ? '/tasks/' + taskToEdit.id : ''; },
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
