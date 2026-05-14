@props(['tasks'])

<!-- Reminder Slide Panel -->
<div x-cloak x-show="modalOpen" class="fixed inset-0 z-[100] overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    <div class="absolute inset-0 overflow-hidden">
        <div x-show="modalOpen" 
             x-transition:enter="ease-in-out duration-400" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in-out duration-400" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
             @click="modalOpen = false"></div>
             
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div x-show="modalOpen" 
                 x-transition:enter="transform transition ease-in-out duration-400" 
                 x-transition:enter-start="translate-x-full" 
                 x-transition:enter-end="translate-x-0" 
                 x-transition:leave="transform transition ease-in-out duration-400" 
                 x-transition:leave-start="translate-x-0" 
                 x-transition:leave-end="translate-x-full" 
                 class="w-screen max-w-md">
                 
                <div class="h-full flex flex-col bg-white shadow-2xl border-l border-gray-100">
                    <!-- Header -->
                    <div class="px-8 py-8 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                        <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight" x-text="modalMode === 'create' ? 'New Reminder' : 'Edit Reminder'"></h2>
                        <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-900 transition-colors p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="flex-1 overflow-y-auto px-8 py-8" x-data="{ submitting: false }">
                        <form id="reminderForm" method="POST" :action="modalMode === 'create' ? '{{ route('reminders.store') }}' : '/reminders/' + modalData.id" 
                              @submit="submitting = true" class="space-y-8">
                            @csrf
                            <template x-if="modalMode === 'edit'">
                                <input type="hidden" name="_method" value="PATCH">
                            </template>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Reminder Title <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" id="title" required x-model="modalData.title"
                                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder-gray-300" 
                                       placeholder="e.g., Team Sync Meeting">
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Message (Optional)</label>
                                <textarea name="message" id="message" rows="4" x-model="modalData.message"
                                          class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder-gray-300 resize-none" 
                                          placeholder="Add some context..."></textarea>
                            </div>

                            <!-- Date & Time -->
                            <div>
                                <label for="reminder_time" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Date & Time <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" name="reminder_time" id="reminder_time" required x-model="modalData.reminder_time"
                                       class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all">
                            </div>

                            <!-- Task Link -->
                            <!-- Task Link -->
                            <div x-data="{ isNewTask: false }">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">Link to Task (Optional)</label>
                                    <button type="button" @click="isNewTask = !isNewTask; modalData.task_id = ''; modalData.new_task_title = ''" 
                                            class="text-[9px] font-extrabold text-emerald-600 hover:text-emerald-700 uppercase tracking-wider">
                                        <span x-show="!isNewTask">+ Create New Task</span>
                                        <span x-show="isNewTask">× Select Existing</span>
                                    </button>
                                </div>
                                
                                <div x-show="!isNewTask" x-transition>
                                    <div class="relative">
                                        <select name="task_id" id="task_id" x-model="modalData.task_id"
                                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all appearance-none cursor-pointer">
                                            <option value="">No linked task</option>
                                            @foreach($tasks as $task)
                                                <option value="{{ $task->id }}">{{ $task->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-5 pointer-events-none text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="isNewTask" x-transition x-cloak>
                                    <input type="text" name="new_task_title" x-model="modalData.new_task_title"
                                           class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-5 py-4 text-gray-900 font-bold focus:outline-none focus:border-emerald-500/50 focus:bg-white focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder-gray-300" 
                                           placeholder="Enter new task title...">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Footer -->
                    <div class="px-8 py-6 border-t border-gray-50 flex items-center justify-end gap-3 bg-gray-50/30">
                        <button @click="modalOpen = false" type="button" class="px-6 py-4 text-xs font-bold text-gray-400 hover:text-gray-900 transition-colors uppercase tracking-widest" :disabled="submitting">
                            Cancel
                        </button>
                        <button form="reminderForm" type="submit" 
                                class="px-8 py-4 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                                :disabled="submitting">
                            <svg x-show="submitting" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="submitting ? 'Processing...' : (modalMode === 'create' ? 'Create Reminder' : 'Save Changes')"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
