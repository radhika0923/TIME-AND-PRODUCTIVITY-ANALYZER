<x-layouts.app title="Settings - Time & Productivity Analyzer">

    <!-- Page Header -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-semibold tracking-tight text-white mb-1">Settings</h1>
            <p class="text-slate-400 text-sm">Manage your account, profile, and preferences</p>
        </div>
    </div>

    <!-- Settings Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left: Navigation -->
        <div class="lg:col-span-1">
            <nav class="bg-slate-900 border border-slate-800 rounded-2xl p-2 space-y-1 sticky top-28" x-data="{ activeTab: 'profile' }">
                <button @click="activeTab = 'profile'; document.getElementById('section-profile').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'profile' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile
                </button>
                <button @click="activeTab = 'password'; document.getElementById('section-password').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'password' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Password
                </button>
                <button @click="activeTab = 'danger'; document.getElementById('section-danger').scrollIntoView({ behavior: 'smooth', block: 'start' })"
                        :class="activeTab === 'danger' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200 border border-transparent'"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                    Danger Zone
                </button>
            </nav>
        </div>

        <!-- Right: Settings Panels -->
        <div class="lg:col-span-2 space-y-8">

            {{-- ─── Profile Section ─── --}}
            <section id="section-profile" class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-white">Profile Information</h2>
                    <p class="text-sm text-slate-500 mt-1">Update your name and email address. Changing your email will require re-verification.</p>
                </div>

                <form method="POST" action="{{ route('settings.profile') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar Preview --}}
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-2xl font-bold text-white shadow-lg shadow-indigo-500/20">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $user->email }}</p>
                            @if($user->hasVerifiedEmail())
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded-full mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-400 bg-amber-400/10 px-2 py-0.5 rounded-full mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01"></path></svg>
                                    Unverified
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                               class="w-full bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500">
                        @error('name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                               class="w-full bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500">
                        @error('email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                            Save Changes
                        </button>
                        @if(session('profile_status'))
                            <p class="text-sm text-emerald-400 flex items-center gap-1.5 animate-pulse">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ session('profile_status') }}
                            </p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- ─── Password Section ─── --}}
            <section id="section-password" class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-white">Change Password</h2>
                    <p class="text-sm text-slate-500 mt-1">Use a strong password with at least 8 characters.</p>
                </div>

                <form method="POST" action="{{ route('settings.password') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-300 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="current_password" required
                               class="w-full bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500"
                               placeholder="Enter your current password">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">New Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500"
                               placeholder="At least 8 characters">
                        @error('password')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm New Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="w-full bg-slate-800/50 border border-slate-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-500"
                               placeholder="Re-enter new password">
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-indigo-500 rounded-xl hover:bg-indigo-600 shadow-lg shadow-indigo-500/25 transition-all transform hover:-translate-y-0.5">
                            Update Password
                        </button>
                        @if(session('password_status'))
                            <p class="text-sm text-emerald-400 flex items-center gap-1.5 animate-pulse">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                {{ session('password_status') }}
                            </p>
                        @endif
                    </div>
                </form>
            </section>

            {{-- ─── Danger Zone ─── --}}
            <section id="section-danger" class="bg-slate-900 border border-red-500/20 rounded-2xl overflow-hidden" x-data="{ confirmDelete: false }">
                <div class="px-6 py-5 border-b border-red-500/10">
                    <h2 class="text-lg font-semibold text-red-400">Danger Zone</h2>
                    <p class="text-sm text-slate-500 mt-1">Irreversible actions. Proceed with extreme caution.</p>
                </div>

                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 bg-red-500/5 border border-red-500/10 rounded-xl">
                        <div>
                            <h3 class="text-sm font-medium text-white">Delete Account</h3>
                            <p class="text-xs text-slate-500 mt-1">Permanently delete your account and all associated data including tasks, time logs, and reminders.</p>
                        </div>
                        <button @click="confirmDelete = true"
                                class="px-5 py-2.5 text-sm font-medium text-red-400 bg-red-500/10 border border-red-500/20 rounded-xl hover:bg-red-500/20 hover:text-red-300 transition-all shrink-0">
                            Delete Account
                        </button>
                    </div>

                    {{-- Delete Confirmation Modal --}}
                    <div x-show="confirmDelete" x-cloak
                         class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0">
                        <div @click.away="confirmDelete = false"
                             class="w-full max-w-md bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl shadow-black/50 p-6"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="transform scale-95 opacity-0"
                             x-transition:enter-end="transform scale-100 opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="transform scale-100 opacity-100"
                             x-transition:leave-end="transform scale-95 opacity-0">

                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-red-500/10 rounded-xl">
                                    <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Are you absolutely sure?</h3>
                            </div>

                            <p class="text-sm text-slate-400 mb-6">
                                This action <span class="text-red-400 font-medium">cannot be undone</span>. This will permanently delete your account, all tasks, time logs, reminders, and remove all associated data.
                            </p>

                            <form method="POST" action="{{ route('settings.destroy') }}" class="space-y-4">
                                @csrf
                                @method('DELETE')

                                <div>
                                    <label for="delete_password" class="block text-sm font-medium text-slate-300 mb-2">Enter your password to confirm</label>
                                    <input type="password" name="delete_password" id="delete_password" required
                                           class="w-full bg-slate-800/50 border border-red-500/30 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition-all placeholder-slate-500"
                                           placeholder="Your current password">
                                    @error('delete_password')
                                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center gap-3 pt-2">
                                    <button type="submit"
                                            class="px-5 py-2.5 text-sm font-medium text-white bg-red-600 rounded-xl hover:bg-red-700 shadow-lg shadow-red-500/25 transition-all">
                                        Yes, Delete My Account
                                    </button>
                                    <button type="button" @click="confirmDelete = false"
                                            class="px-5 py-2.5 text-sm font-medium text-slate-300 bg-slate-800 border border-slate-700 rounded-xl hover:bg-slate-700 transition-all">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ─── Account Info Card ─── --}}
            <section class="bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-800">
                    <h2 class="text-lg font-semibold text-white">Account Overview</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-medium mb-1">Member Since</p>
                            <p class="text-sm text-white font-medium">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-medium mb-1">Total Tasks</p>
                            <p class="text-sm text-white font-medium">{{ $user->tasks()->count() }}</p>
                        </div>
                        <div class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-4">
                            <p class="text-xs text-slate-500 uppercase tracking-wider font-medium mb-1">Focus Hours</p>
                            <p class="text-sm text-white font-medium">{{ round(($user->timeLogs()->sum('duration') ?? 0) / 60, 1) }}h</p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

</x-layouts.app>
