<x-layouts.app title="Planner - Time & Productivity Analyzer">
    <x-slot:styles>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <style>
            .fc {
                --fc-border-color: #1e293b;
                --fc-daygrid-dot-event-marker-size: 8px;
                --fc-event-bg-color: #6366f1;
                --fc-event-border-color: #6366f1;
                --fc-page-bg-color: transparent;
                --fc-neutral-bg-color: #0f172a;
                --fc-list-event-hover-bg-color: #1e293b;
                --fc-today-bg-color: rgba(99, 102, 241, 0.05);
                color: #cbd5e1;
            }
            .fc-theme-standard td, .fc-theme-standard th {
                border-color: #1e293b;
            }
            .fc-toolbar-title {
                font-size: 1.25rem !important;
                font-weight: 600 !important;
                color: #f8fafc !important;
            }
            .fc-button {
                background-color: #1e293b !important;
                border-color: #334155 !important;
                color: #cbd5e1 !important;
                font-weight: 500 !important;
                text-transform: capitalize !important;
                border-radius: 0.75rem !important;
                padding: 0.5rem 1rem !important;
            }
            .fc-button-active {
                background-color: #6366f1 !important;
                border-color: #6366f1 !important;
                color: white !important;
            }
            .fc-event {
                cursor: pointer;
                border-radius: 6px !important;
                padding: 2px 4px !important;
                font-size: 0.85rem !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }
            .fc-timegrid-slot {
                height: 3rem !important;
            }
            .fc-col-header-cell-cushion {
                padding: 10px 0 !important;
                color: #94a3b8 !important;
                font-weight: 500 !important;
            }
        </style>
    </x-slot:styles>

    <div class="flex flex-col space-y-6">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-white mb-1">Weekly Planner</h1>
            <p class="text-slate-400 text-sm">Schedule your tasks and manage your time slots.</p>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-sm">
            <div id="calendar"></div>
        </div>
    </div>

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
    }" x-show="open" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="open = false"></div>
        <div class="relative bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="p-6 border-b border-slate-800 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-white" x-text="task.title"></h3>
                <button @click="open = false" class="text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span class="text-xs font-medium uppercase tracking-wider text-slate-500 block mb-1">Description</span>
                    <p class="text-slate-300" x-text="task.extendedProps?.description || 'No description'"></p>
                </div>
                <div class="flex gap-6">
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wider text-slate-500 block mb-1">Priority</span>
                        <span class="px-2 py-1 rounded-lg text-xs font-bold uppercase" 
                              :class="{
                                  'bg-red-500/10 text-red-400': task.extendedProps?.priority === 'high',
                                  'bg-amber-500/10 text-amber-400': task.extendedProps?.priority === 'medium',
                                  'bg-blue-500/10 text-blue-400': task.extendedProps?.priority === 'low'
                              }"
                              x-text="task.extendedProps?.priority"></span>
                    </div>
                    <div>
                        <span class="text-xs font-medium uppercase tracking-wider text-slate-500 block mb-1">Status</span>
                        <span class="px-2 py-1 rounded-lg text-xs font-bold uppercase bg-slate-800 text-slate-300" x-text="task.extendedProps?.status"></span>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-slate-800/50 border-t border-slate-800 flex justify-end gap-3">
                <button @click="open = false" class="px-4 py-2 text-sm font-medium text-slate-300 hover:text-white">Close</button>
                <a :href="'/tasks/'" class="px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 transition-colors">Edit Full Task</a>
            </div>
        </div>
    </div>

    <x-slot:scripts>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const calendarEl = document.getElementById('calendar');
                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    height: 'auto',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    editable: true,
                    selectable: true,
                    nowIndicator: true,
                    slotMinTime: '00:00:00',
                    slotMaxTime: '24:00:00',
                    allDaySlot: false,
                    events: '/planner/events',
                    
                    eventClick: function(info) {
                        window.dispatchEvent(new CustomEvent('open-task-modal', { 
                            detail: {
                                id: info.event.id,
                                title: info.event.title,
                                extendedProps: info.event.extendedProps
                            }
                        }));
                    },

                    eventDrop: function(info) {
                        updateTaskSchedule(info.event);
                    },

                    eventResize: function(info) {
                        updateTaskSchedule(info.event);
                    },

                    select: function(info) {
                        // Option to create task at this slot
                        // We can redirect to tasks page with pre-filled date
                        const start = info.startStr;
                        window.location.href = `/tasks?create=true&due_date=${encodeURIComponent(start)}`;
                    }
                });

                calendar.render();

                function updateTaskSchedule(event) {
                    fetch(`/planner/tasks/${event.id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            start: event.start.toISOString(),
                            end: event.end ? event.end.toISOString() : null
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Failed to update task schedule.');
                        }
                    });
                }
            });
        </script>
    </x-slot:scripts>
</x-layouts.app>
