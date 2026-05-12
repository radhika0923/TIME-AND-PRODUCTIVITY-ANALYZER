<!-- Task Detail Modal (Simple) -->
<div x-data="{ 
    open: false, 
    task: {},
    init() {
        window.addEventListener('open-task-modal', (e) => {
            this.task = e.detail;
            this.open = true;
        });
    }
}" x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm" @click="open = false"></div>
    <div class="relative bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden animate-fade-in">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight" x-text="task.title"></h3>
            <button @click="open = false" class="text-gray-400 hover:text-gray-900 transition-colors p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-8 space-y-6">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-2">Description</span>
                <p class="text-gray-600 leading-relaxed font-medium" x-text="task.extendedProps?.description || 'No description provided for this task.'"></p>
            </div>
            <div class="flex gap-8">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-2">Priority</span>
                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest" 
                          :class="{
                              'bg-rose-50 text-rose-600 border border-rose-100': task.extendedProps?.priority === 'high',
                              'bg-amber-50 text-amber-600 border border-amber-100': task.extendedProps?.priority === 'medium',
                              'bg-emerald-50 text-emerald-600 border border-emerald-100': task.extendedProps?.priority === 'low'
                          }"
                          x-text="task.extendedProps?.priority"></span>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 block mb-2">Status</span>
                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-widest bg-gray-100 text-gray-500 border border-gray-200" x-text="task.extendedProps?.status"></span>
                </div>
            </div>
        </div>
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
            <button @click="open = false" class="px-6 py-3 text-xs font-bold text-gray-400 hover:text-gray-900 uppercase tracking-widest transition-colors">Close</button>
            <a :href="'/tasks/'" class="px-8 py-3 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 uppercase tracking-widest transition-all">Edit Details</a>
        </div>
    </div>
</div>
