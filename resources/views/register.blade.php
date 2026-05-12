<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up - Time & Productivity Analyzer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --bg-main: #F3F4F6;
            --accent-green: #10B981;
            --accent-green-dark: #059669;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); }
        [x-cloak] { display: none !important; }
        
        .premium-card {
            background: #FFFFFF;
            border-radius: 1.5rem;
            border: 1px solid #E5E7EB;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="text-gray-900 antialiased selection:bg-emerald-500 selection:text-white min-h-screen flex items-center justify-center relative overflow-x-hidden py-6 md:py-12">

    <!-- Decorative background elements -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-96 bg-emerald-500/5 blur-[100px] rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-500/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white shadow-xl shadow-emerald-500/10 mb-4 border border-emerald-50/50">
                <svg class="w-9 h-9 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-2">Create Your Account</h1>
            <p class="text-gray-500">Join thousands of users mastering their productivity</p>
        </div>

        <!-- Card -->
        <div class="premium-card p-6 md:p-8">
            
            <form method="POST" action="{{ route('register') }}" class="space-y-4" 
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
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 transition-all shadow-sm active:scale-[0.98]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 transition-all shadow-sm active:scale-[0.98]">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.379.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.161 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                        GitHub
                    </button>
                </div>

                <div class="relative flex items-center py-2">
                    <div class="flex-grow border-t border-gray-100"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">Or continue with email</span>
                    <div class="flex-grow border-t border-gray-100"></div>
                </div>

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-gray-400 @error('name') border-red-500 focus:ring-red-500/10 focus:border-red-500 @enderror"
                           placeholder="John Doe">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-gray-400 @error('email') border-red-500 focus:ring-red-500/10 focus:border-red-500 @enderror"
                           placeholder="you@example.com">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required autocomplete="new-password"
                               x-model="password" @input="checkReqs(); touched = true"
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl pl-4 pr-12 py-2.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder-gray-400 @error('password') border-red-500 focus:ring-red-500/10 focus:border-red-500 @enderror"
                               placeholder="••••••••">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-500 transition-colors">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-cloak x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>

                    <!-- Live Password Requirements Box -->
                    <div x-show="touched && password.length > 0" x-cloak 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-3 p-4 bg-emerald-50/50 border border-emerald-100 rounded-xl text-[11px] grid grid-cols-2 gap-y-2 gap-x-4">
                        
                        <div class="flex items-center gap-2" :class="reqs.length ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.length"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3 h-3 flex-shrink-0 rounded-full border border-gray-300" x-show="!reqs.length"></div>
                            <span class="font-medium">8+ characters</span>
                        </div>
                        
                        <div class="flex items-center gap-2" :class="reqs.upper ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.upper"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3 h-3 flex-shrink-0 rounded-full border border-gray-300" x-show="!reqs.upper"></div>
                            <span class="font-medium">Uppercase</span>
                        </div>
                        
                        <div class="flex items-center gap-2" :class="reqs.lower ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.lower"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3 h-3 flex-shrink-0 rounded-full border border-gray-300" x-show="!reqs.lower"></div>
                            <span class="font-medium">Lowercase</span>
                        </div>
                        
                        <div class="flex items-center gap-2" :class="reqs.num ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.num"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3 h-3 flex-shrink-0 rounded-full border border-gray-300" x-show="!reqs.num"></div>
                            <span class="font-medium">Number</span>
                        </div>
                        
                        <div class="flex items-center gap-2 col-span-2" :class="reqs.special ? 'text-emerald-600' : 'text-gray-400'">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="reqs.special"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <div class="w-3 h-3 flex-shrink-0 rounded-full border border-gray-300" x-show="!reqs.special"></div>
                            <span class="font-medium">Special character (!@#$...)</span>
                        </div>
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                               x-model="passwordConfirmation"
                               class="w-full bg-gray-50 border text-gray-900 text-sm rounded-xl pl-4 pr-12 py-2.5 focus:outline-none focus:ring-4 transition-all placeholder-gray-400"
                               :class="!passwordsMatch ? 'border-red-500 focus:border-red-500 focus:ring-red-500/10' : 'border-gray-200 focus:border-emerald-500 focus:ring-emerald-500/10'"
                               placeholder="••••••••">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-500 transition-colors">
                            <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-cloak x-show="showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                    <p x-show="!passwordsMatch" x-cloak class="mt-1.5 text-xs text-red-500 font-medium">Passwords do not match</p>
                </div>

                <!-- Terms Text -->
                <div class="text-[11px] text-gray-400 text-center py-2 leading-relaxed">
                    By signing up, you agree to our <a href="#" class="text-emerald-600 hover:text-emerald-700 font-bold">Terms of Service</a> and <a href="#" class="text-emerald-600 hover:text-emerald-700 font-bold">Privacy Policy</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" :disabled="loading || !passwordsMatch" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-500/20 text-sm font-semibold text-white bg-emerald-500 hover:bg-emerald-600 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 transition-all transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none">
                    <svg x-cloak x-show="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="loading ? 'Creating account...' : 'Create Account'"></span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500 font-medium">
                    Already have an account? 
                    <a href="{{ route('login.form') }}" class="text-emerald-600 hover:text-emerald-700 font-bold transition-colors">Log in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
