@props(['logs', 'tasks' => [], 'filters' => []])

<!-- LOGS SECTION -->
<div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2.5rem] shadow-xl shadow-gray-200/40 dark:shadow-none overflow-hidden mt-12" x-data="editSessionModal()">
    <div class="px-8 py-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Sessions</h2>
        <div class="flex items-center gap-3">
            <button @click="openManual = true" class="text-[10px] font-bold uppercase tracking-widest text-white bg-emerald-600 px-4 py-2 rounded-xl shadow-lg shadow-emerald-100">Add Session</button>
            <a href="{{ route('time.export', array_filter($filters)) }}" class="text-[10px] font-bold uppercase tracking-widest text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-2 rounded-xl border border-emerald-100 dark:border-emerald-500/20">Export CSV</a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50/50 dark:bg-gray-900/50 text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                    <th class="px-8 py-4">Task</th>
                    <th class="px-8 py-4">Date & Time</th>
                    <th class="px-8 py-4 text-right">Duration</th>
                    <th class="px-8 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 dark:divide-gray-700">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/30 dark:hover:bg-gray-700/30 transition-colors">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full {{ $log->task ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->task ? $log->task->title : 'Uncategorized' }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-xs text-gray-500 dark:text-gray-400 font-medium">
                            {{ $log->created_at->format('M d, Y • h:i A') }}
                        </td>
                        <td class="px-8 py-5 text-sm text-right font-bold text-gray-900 dark:text-white font-mono">
                            {{ \App\Support\Duration::format($log->duration) }}
                        </td>
                        <td class="px-8 py-5 text-right space-x-3">
                            <button @click="openEdit(@js(route('time-logs.update', $log)), @js((int) $log->duration), @js($log->task_id))" class="text-emerald-600 dark:text-emerald-400 font-bold uppercase text-[10px]">Edit</button>
                            <button @click="confirmDelete('{{ route('time-logs.destroy', $log) }}')" class="text-rose-500 dark:text-rose-400 font-bold uppercase text-[10px]">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-12 text-center text-gray-400 dark:text-gray-500 text-sm">No recorded sessions for this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
        <div class="px-8 py-4 border-t border-gray-50 dark:border-gray-700">
            {{ $logs->links() }}
        </div>
    @endif

    <!-- Manual Entry Modal -->
    <div x-show="openManual" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/20 backdrop-blur-sm" x-transition @keydown.escape.window="openManual = false">
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2rem] max-w-md w-full p-8 shadow-2xl dark:shadow-none" @click.away="openManual = false">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Log Time Manually</h3>
            <form method="POST" action="{{ route('time-logs.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-4 uppercase text-center tracking-widest">Duration</label>
                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[1.5rem] px-8 py-6">
                        <div class="flex-1 flex flex-col items-center">
                            <input type="number" name="duration_hours" min="0" max="24" placeholder="0" class="w-full bg-transparent text-center text-2xl font-bold text-gray-900 dark:text-white focus:outline-none">
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Hrs</span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600 text-2xl font-light">:</span>
                        <div class="flex-1 flex flex-col items-center">
                            <input type="number" name="duration_minutes" min="0" max="59" placeholder="0" class="w-full bg-transparent text-center text-2xl font-bold text-gray-900 dark:text-white focus:outline-none">
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Mins</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Linked Task (Optional)</label>
                    <select name="task_id" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-xl px-4 py-3 text-sm font-medium text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all">
                        <option value="">No task / General Focus</option>
                        @foreach($tasks as $task)
                            <option value="{{ $task->id }}">{{ $task->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="openManual = false" class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-100 uppercase tracking-widest hover:bg-emerald-700 transition-colors">Log Session</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/20 backdrop-blur-sm" x-transition>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2rem] max-w-md w-full p-8 shadow-2xl dark:shadow-none" @click.away="close()">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Edit Session</h3>
            <form method="POST" :action="updateUrl" class="space-y-6">
                @csrf @method('PATCH')
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 mb-4 uppercase text-center tracking-widest">Adjust Duration</label>
                    <div class="flex items-center gap-4 bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-800 rounded-[1.5rem] px-8 py-6">
                        <div class="flex-1 flex flex-col items-center">
                            <input type="number" x-model="hrs" min="0" class="w-full bg-transparent text-center text-2xl font-bold text-gray-900 dark:text-white focus:outline-none">
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Hrs</span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600 text-2xl font-light">:</span>
                        <div class="flex-1 flex flex-col items-center">
                            <input type="number" x-model="mins" min="0" max="59" class="w-full bg-transparent text-center text-2xl font-bold text-gray-900 dark:text-white focus:outline-none">
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Mins</span>
                        </div>
                        <span class="text-gray-300 dark:text-gray-600 text-2xl font-light">:</span>
                        <div class="flex-1 flex flex-col items-center">
                            <input type="number" x-model="secs" min="0" max="59" class="w-full bg-transparent text-center text-2xl font-bold text-gray-900 dark:text-white focus:outline-none">
                            <span class="text-[8px] font-bold text-gray-400 uppercase mt-1 tracking-widest">Secs</span>
                        </div>
                    </div>
                    <input type="hidden" name="duration" :value="totalSeconds">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="close()" class="px-6 py-3 text-xs font-bold text-gray-400 uppercase tracking-widest">Cancel</button>
                    <button type="submit" class="px-8 py-3 text-xs font-bold text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-100 uppercase tracking-widest">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-md" x-transition>
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2.5rem] max-w-sm w-full p-10 shadow-2xl dark:shadow-none text-center" @click.away="deleteModalOpen = false">
            <div class="w-20 h-20 bg-rose-50 dark:bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete Session?</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8 leading-relaxed">Are you sure you want to permanently delete this session?</p>
            <div class="flex flex-col gap-3">
                <button @click="executeDelete()" class="w-full py-4 bg-rose-600 text-white rounded-2xl font-bold text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-100 uppercase tracking-widest">Delete</button>
                <button @click="deleteModalOpen = false" class="w-full py-4 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400 rounded-2xl font-bold text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-all uppercase tracking-widest">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    <div class="fixed bottom-8 right-8 z-[120] flex flex-col gap-3">
        <template x-for="t in toasts" :key="t.id">
            <div x-show="!t.hidden" x-transition class="bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center justify-between gap-8 min-w-[300px] border border-white/10 backdrop-blur-xl">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-sm font-bold tracking-wide" x-text="t.message"></span>
                </div>
                <button @click="undoDelete(t.id)" class="text-emerald-400 font-extrabold text-[10px] uppercase tracking-[0.2em] hover:text-emerald-300 transition-colors">Undo</button>
            </div>
        </template>
    </div>
</div>
