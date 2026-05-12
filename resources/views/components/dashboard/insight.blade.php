@props(['focusInsight'])

<!-- Productivity Insight -->
<div class="bg-gradient-to-br from-gray-900 to-black rounded-[2rem] p-6 flex flex-col relative overflow-hidden shadow-xl shadow-gray-200 dark:shadow-none">
    <!-- Decorative glow -->
    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-emerald-500/10 blur-3xl rounded-full"></div>
    
    <div class="flex items-center gap-3 mb-6 relative z-10">
        <div class="p-2 bg-white/10 rounded-lg text-emerald-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        </div>
        <h2 class="text-lg font-bold text-white">Insight</h2>
    </div>
    
    <div class="flex-1 flex flex-col justify-center relative z-10">
        <p class="text-gray-300 leading-relaxed text-lg mb-6">
            {{ $focusInsight ?? 'Use Time Tracking for focus blocks and Tasks to organize what you work on next.' }}
        </p>
        <div class="mt-auto">
            <a href="{{ route('analytics.index') }}" class="text-emerald-400 text-sm font-bold hover:text-emerald-300 inline-flex items-center gap-1 transition-colors uppercase tracking-widest">
                Open analytics
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>
