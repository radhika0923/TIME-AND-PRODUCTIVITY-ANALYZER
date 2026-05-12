@props(['weekOffset'])

<!-- Left: Line Chart (70%) -->
<div class="lg:col-span-2 bg-white border border-gray-100 rounded-[2rem] p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-gray-900">Weekly Productivity</h2>
        <form method="get" class="inline-block">
            <label for="dash-week" class="sr-only">Chart week</label>
            <select id="dash-week" name="week" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-600 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 p-2">
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
