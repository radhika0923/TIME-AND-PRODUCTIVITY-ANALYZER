@props(['weekOffset'])

<!-- Left: Line Chart (70%) -->
<div class="lg:col-span-2 bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-[2rem] p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Weekly Productivity</h2>
        <form method="get" class="inline-block">
            <label for="dash-week" class="sr-only">Chart week</label>
            <select id="dash-week" name="week" onchange="this.form.submit()" class="bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-2 transition-colors">
                @for($w = 0; $w <= 12; $w++)
                    <option value="{{ $w }}" @selected(($weekOffset ?? 0) == $w)>
                        {{ $w === 0 ? 'This week' : ($w === 1 ? 'Last week' : $w.' weeks ago') }}
                    </option>
                @endfor
            </select>
        </form>
    </div>
    <div class="relative h-72 w-full">
        <canvas id="productivityChart"></canvas>
    </div>
</div>
