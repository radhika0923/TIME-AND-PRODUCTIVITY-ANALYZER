<x-layouts.app title="Settings - Time & Productivity Analyzer">

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 mb-2">Settings</h1>
            <p class="text-gray-500 text-sm font-medium">Manage your account, profile, and focus preferences.</p>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Navigation -->
        <div class="lg:col-span-1">
            <nav class="bg-white border border-gray-100 rounded-[2.5rem] p-3 space-y-1 sticky top-28 shadow-sm" x-data="{ activeTab: 'profile' }">
                <button @click="activeTab = 'profile'; document.getElementById('section-profile').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'profile' ? 'bg-black text-white shadow-xl shadow-gray-200' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
                        class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-bold uppercase tracking-widest rounded-2xl transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                </button>
                <button @click="activeTab = 'password'; document.getElementById('section-password').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'password' ? 'bg-black text-white shadow-xl shadow-gray-200' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
                        class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-bold uppercase tracking-widest rounded-2xl transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Password
                </button>
                <button @click="activeTab = 'danger'; document.getElementById('section-danger').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'danger' ? 'bg-rose-50 text-rose-600 border border-rose-100 shadow-sm' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-900 border border-transparent'"
                        class="w-full flex items-center gap-4 px-6 py-4 text-[10px] font-bold uppercase tracking-widest rounded-2xl transition-all duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    Danger Zone
                </button>
            </nav>
        </div>

        <!-- Right: Settings Panels -->
        <div class="lg:col-span-2 space-y-8">

            {{-- ─── Profile Section ─── --}}
            <section id="section-profile" class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="px-8 py-8 border-b border-gray-50 bg-gray-50/30">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Profile & Timezone</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Update your personal details and localized experience settings.</p>
                </div>

                <form method="POST" action="{{ route('settings.profile') }}" class="p-8 space-y-8">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar Preview --}}
                    <div class="flex items-center gap-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                        <div class="w-20 h-20 rounded-[1.5rem] bg-emerald-600 flex items-center justify-center text-3xl font-extrabold text-white shadow-xl shadow-emerald-100">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xl font-extrabold text-gray-900 tracking-tight">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500 font-medium">{{ $user->email }}</p>
                            @if($user->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-emerald-600 bg-emerald-50 border border-emerald-100 px-3 py-1.5 rounded-xl mt-3">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Verified Account
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-amber-600 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-xl mt-3">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                                    Unverified Account
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm">
                        @error('name')
                            <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm">
                        @error('email')
                            <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="timezone" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Timezone</label>
                        <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $user->timezone ?? config('app.timezone')) }}"
                               placeholder="e.g. America/New_York"
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm">
                        <p class="mt-3 text-[10px] text-gray-400 font-bold uppercase tracking-widest">Used for accurate charting and daily resets.</p>
                        @error('timezone')
                            <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-6 pt-4">
                        <button type="submit"
                                class="px-10 py-4 text-xs font-bold text-white bg-emerald-600 rounded-2xl hover:bg-emerald-700 shadow-xl shadow-emerald-100 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                            Update Profile
                        </button>
                        @if(session('profile_status'))
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-2 animate-pulse">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ session('profile_status') }}
                            </p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- ─── Password Section ─── --}}
            <section id="section-password" class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="px-8 py-8 border-b border-gray-50 bg-gray-50/30">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Security</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Ensure your account remains secure by using a robust password.</p>
                </div>

                <form method="POST" action="{{ route('settings.password') }}" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm"
                               placeholder="••••••••">
                        @error('current_password')
                            <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">New Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm"
                               placeholder="Min. 8 characters">
                        @error('password')
                            <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full bg-white border border-gray-200 text-gray-900 font-bold text-sm rounded-xl px-5 py-3.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/5 focus:border-emerald-500 transition-all placeholder-gray-300 shadow-sm"
                               placeholder="••••••••">
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-6 pt-4">
                        <button type="submit"
                                class="px-10 py-4 text-xs font-bold text-white bg-black rounded-2xl hover:bg-gray-900 shadow-xl shadow-gray-200 transition-all transform hover:-translate-y-1 uppercase tracking-widest">
                            Update Security
                        </button>
                        @if(session('password_status'))
                            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest flex items-center gap-2 animate-pulse">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ session('password_status') }}
                            </p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- ─── Danger Zone ─── --}}
            <section id="section-danger" class="bg-white border border-rose-100 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="px-8 py-8 border-b border-rose-50 bg-rose-50/30">
                    <h2 class="text-2xl font-extrabold text-rose-600 tracking-tight">Danger Zone</h2>
                    <p class="text-sm text-rose-500 font-medium mt-1">Irreversible actions. Please proceed with extreme caution.</p>
                </div>

                <div class="p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 bg-rose-50 rounded-3xl border border-rose-100">
                        <div>
                            <h3 class="text-lg font-extrabold text-rose-600 tracking-tight">Delete Account</h3>
                            <p class="text-sm text-rose-500 font-medium mt-1">Permanently erase your entire digital footprint and data.</p>
                        </div>
                        <button @click="confirmDelete = true"
                                class="px-8 py-4 text-xs font-bold text-rose-600 bg-white border border-rose-200 rounded-2xl hover:bg-rose-600 hover:text-white transition-all transform hover:-translate-y-1 uppercase tracking-widest shadow-sm">
                            Delete My Account
                        </button>
                    </div>

                    {{-- Delete Confirmation Modal --}}
                    <div x-show="confirmDelete" x-cloak
                         class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/40 backdrop-blur-sm p-4"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div @click.away="confirmDelete = false"
                             class="w-full max-w-md bg-white border border-gray-100 rounded-[2.5rem] shadow-2xl p-8"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="transform scale-95 opacity-0"
                             x-transition:enter-end="transform scale-100 opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="transform scale-100 opacity-100"
                             x-transition:leave-end="transform scale-95 opacity-0">

                            <div class="flex items-center gap-4 mb-6">
                                <div class="p-4 bg-rose-50 rounded-2xl text-rose-600 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                </div>
                                <h3 class="text-2xl font-extrabold text-gray-900 tracking-tight">Are you sure?</h3>
                            </div>

                            <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">
                                This action <span class="text-rose-600 font-bold">cannot be undone</span>. All your tasks, time logs, and personal settings will be permanently destroyed.
                            </p>

                            <form method="POST" action="{{ route('settings.destroy') }}" class="space-y-6">
                                @csrf
                                @method('DELETE')

                                <div>
                                    <label for="delete_password" class="block text-[10px] font-bold text-gray-400 mb-2 uppercase tracking-widest">Password Confirmation</label>
                                    <input type="password" name="delete_password" id="delete_password" required
                                           class="w-full bg-gray-50 border border-rose-100 text-gray-900 font-bold text-sm rounded-2xl px-5 py-4 focus:outline-none focus:ring-4 focus:ring-rose-500/10 focus:border-rose-500/50 transition-all placeholder-gray-300"
                                           placeholder="Enter password to confirm">
                                    @error('delete_password')
                                        <p class="mt-2 text-xs font-bold text-rose-500 uppercase tracking-widest">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col gap-3 pt-4">
                                    <button type="submit"
                                            class="w-full px-8 py-4 text-xs font-bold text-white bg-rose-600 rounded-2xl hover:bg-rose-700 shadow-xl shadow-rose-100 transition-all uppercase tracking-widest">
                                        Permanently Delete Account
                                    </button>
                                    <button type="button" @click="confirmDelete = false"
                                            class="w-full px-8 py-4 text-xs font-bold text-gray-400 hover:text-gray-900 transition-all uppercase tracking-widest">
                                        Cancel & Go Back
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ─── Account Info Card ─── --}}
            <section class="bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="px-8 py-8 border-b border-gray-50 bg-gray-50/30">
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">Account Overview</h2>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Member Since</p>
                            <p class="text-lg text-gray-900 font-extrabold tracking-tight">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Total Tasks</p>
                            <p class="text-lg text-gray-900 font-extrabold tracking-tight">{{ $user->tasks()->count() }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 shadow-sm">
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-2">Focus Hours</p>
                            <p class="text-lg text-gray-900 font-extrabold tracking-tight">{{ \App\Support\Duration::toDecimalHours((int) ($user->timeLogs()->sum('duration') ?? 0)) }}h</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

</x-layouts.app>
