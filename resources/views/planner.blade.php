<x-layouts.app title="Planner - Time & Productivity Analyzer">
    <x-slot:styles>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
        <style>
            .fc {
                --fc-border-color: #f1f5f9;
                --fc-daygrid-dot-event-marker-size: 8px;
                --fc-event-bg-color: #10b981;
                --fc-event-border-color: #10b981;
                --fc-page-bg-color: #ffffff;
                --fc-neutral-bg-color: #f8fafc;
                --fc-list-event-hover-bg-color: #f1f5f9;
                --fc-today-bg-color: rgba(16, 185, 129, 0.05);
                color: #475569;
                font-family: inherit;
            }
            .fc-theme-standard td, .fc-theme-standard th {
                border-color: #f1f5f9;
            }
            .fc-toolbar-title {
                font-size: 1.5rem !important;
                font-weight: 800 !important;
                color: #0f172a !important;
                letter-spacing: -0.025em !important;
            }
            .fc-button {
                background-color: #ffffff !important;
                border-color: #f1f5f9 !important;
                color: #64748b !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                font-size: 0.7rem !important;
                border-radius: 1rem !important;
                padding: 0.6rem 1.2rem !important;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
                transition: all 0.2s !important;
            }
            .fc-button:hover {
                background-color: #f8fafc !important;
                color: #0f172a !important;
            }
            .fc-button-active {
                background-color: #0f172a !important;
                border-color: #0f172a !important;
                color: white !important;
            }
            .fc-event {
                cursor: pointer;
                border-radius: 12px !important;
                padding: 4px 8px !important;
                font-size: 0.75rem !important;
                font-weight: 700 !important;
                box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1) !important;
                border: none !important;
                transition: transform 0.2s !important;
            }
            .fc-event:hover {
                transform: scale(1.02);
            }
            .fc-timegrid-slot {
                height: 4rem !important;
            }
            .fc-col-header-cell-cushion {
                padding: 12px 0 !important;
                color: #94a3b8 !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                font-size: 0.7rem !important;
            }
            .fc-timegrid-axis-cushion, .fc-timegrid-slot-label-cushion {
                font-size: 0.7rem !important;
                font-weight: 700 !important;
                color: #94a3b8 !important;
                text-transform: uppercase !important;
            }
        </style>
    </x-slot:styles>

    <div class="flex flex-col space-y-8">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Weekly Planner</h1>
            <p class="text-gray-500 text-sm font-medium">Schedule your tasks and manage your time slots with precision.</p>
        </div>

        <div class="bg-white border border-gray-100 rounded-[2.5rem] p-8 shadow-sm">
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
