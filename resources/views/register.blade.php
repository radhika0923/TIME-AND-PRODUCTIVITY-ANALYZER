<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - Time & Productivity Analyzer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 antialiased selection:bg-indigo-500 selection:text-white min-h-screen flex items-center justify-center relative overflow-x-hidden py-12">

    <!-- Decorative background elements -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-lg h-96 bg-indigo-500/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-500/10 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold tracking-tight text-white mb-2">Create Your Account</h1>
            <p class="text-slate-400">Join thousands of users mastering their productivity</p>
        </div>

        <!-- Card -->
        <div class="bg-slate-900/80 backdrop-blur-xl border border-slate-800 rounded-3xl p-8 shadow-2xl shadow-black/50">
            
            <form method="POST" action="{{ route('register') }}" class="space-y-5" 
                  x-data="{ 
                      loading: false, 
                      showPassword: false, 
                      showConfirm: false,
                      password: '',
                      passwordConfirmation: '',
                      touched: false,
                      reqs: { length: false, upper: false, lower: false, num: false, special: false },
                      checkReqs() {
                          this.reqs.length = this.password.length >= 8;
                          this.reqs.upper = /[A-Z]/.test(this.password);
                          this.reqs.lower = /[a-z]/.test(this.password);
                          this.reqs.num = /[0-9]/.test(this.password);
                          this.reqs.special = /[!@#$%^&*()_+\-=\[\]{};':\'\\|,.<>\/?]/.test(this.password);
                      },
                      get passwordsMatch() {
                          if (this.passwordConfirmation.length === 0) return true;
                          return this.password === this.passwordConfirmation;
                      }
                  }" 
                  @submit="loading = true">
                @csrf

                <!-- Social Login Buttons -->
                <div class="grid grid-cols-2 gap-4 mb-2">
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-sm font-medium text-slate-300 transition-colors shadow-sm">
                        <svg class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 rounded-xl text-sm font-medium text-slate-300 transition-colors shadow-sm">
                        <svg class="w-5 h-5 text-slate-300" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.379.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.161 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                        GitHub
                    </button>
                </div>

                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-slate-800"></div>
                    <span class="flex-shrink-0 mx-4 text-slate-500 text-xs font-medium uppercase tracking-wider">Or continue with email</span>
                    <div class="flex-grow border-t border-slate-800"></div>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-400 mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-600 @error('name') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                           placeholder="John Doe">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-400 mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-600 @error('email') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-400 mb-1.5">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                               x-model="password" @input="checkReqs(); touched = true"
                               class="w-full bg-slate-950 border border-slate-800 text-slate-200 text-sm rounded-xl pl-4 pr-12 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all placeholder-slate-600 @error('password') border-red-500 focus:ring-red-500/50 focus:border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-cloak x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>

                    <!-- Live Password Requirements Box -->
                    <div x-show="touched && password.length > 0" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-2 p-3 bg-indigo-500/10 border border-indigo-500/20 rounded-lg text-xs grid grid-cols-2 gap-2">
                        
                        <div class="flex items-center gap-1.5" :class="reqs.length ? 'text-emerald-400' : 'text-slate-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.length"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3.5 h-3.5 flex-shrink-0 rounded-full border border-slate-600" x-show="!reqs.length"></div>
                            <span>8+ characters</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5" :class="reqs.upper ? 'text-emerald-400' : 'text-slate-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.upper"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3.5 h-3.5 flex-shrink-0 rounded-full border border-slate-600" x-show="!reqs.upper"></div>
                            <span>Uppercase (A-Z)</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5" :class="reqs.lower ? 'text-emerald-400' : 'text-slate-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.lower"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3.5 h-3.5 flex-shrink-0 rounded-full border border-slate-600" x-show="!reqs.lower"></div>
                            <span>Lowercase (a-z)</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5" :class="reqs.num ? 'text-emerald-400' : 'text-slate-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.num"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3.5 h-3.5 flex-shrink-0 rounded-full border border-slate-600" x-show="!reqs.num"></div>
                            <span>Number (0-9)</span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 col-span-2" :class="reqs.special ? 'text-emerald-400' : 'text-slate-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.special"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3.5 h-3.5 flex-shrink-0 rounded-full border border-slate-600" x-show="!reqs.special"></div>
                            <span>Special character (!@#$...)</span>
                        </div>
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-400 mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               x-model="passwordConfirmation"
                               class="w-full bg-slate-950 border text-slate-200 text-sm rounded-xl pl-4 pr-12 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all placeholder-slate-600"
                               :class="!passwordsMatch ? 'border-red-500 focus:border-red-500 focus:ring-red-500/50' : 'border-slate-800 focus:border-indigo-500/50'"
                               placeholder="••••••••">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-500 hover:text-slate-300 transition-colors">
                            <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-cloak x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    <p x-show="!passwordsMatch" x-cloak class="mt-1.5 text-xs text-red-400">Passwords do not match</p>
                </div>

                <!-- Terms Text -->
                <div class="text-xs text-slate-500 text-center py-2">
                    By signing up, you agree to our <a href="#" class="text-indigo-400 hover:text-indigo-300 font-medium">Terms of Service</a> and <a href="#" class="text-indigo-400 hover:text-indigo-300 font-medium">Privacy Policy</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" :disabled="loading || !passwordsMatch" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-500/25 text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all transform hover:-translate-y-0.5 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
                    <svg x-cloak x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="loading ? 'Creating account...' : 'Create Account'"></span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    Already have an account? 
                    <a href="{{ route('login.form') }}" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors">Log in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
