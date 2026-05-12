<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - Time & Productivity Analyzer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-main: #F3F4F6;
            --accent-green: #10B981;
        }
        body { font-family: 'Inter', sans-serif; background-color: var(--bg-main); }
        button, a, [role="button"], [x-on\:click], [\@click] {
            cursor: pointer;
        }
        .premium-card {
            background: #FFFFFF;
            border-radius: 1.5rem;
            border: 1px solid #E5E7EB;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="text-gray-900 antialiased min-h-screen flex items-center justify-center p-6 relative overflow-y-auto">
    
    <!-- Decorative background elements -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-2xl h-96 bg-emerald-500/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md">
        <div class="premium-card p-8 md:p-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white shadow-xl shadow-emerald-500/10 mb-6 border border-emerald-50/50">
                <svg class="w-9 h-9 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 mb-4">Verify your email</h1>
            <p class="text-gray-500 mb-6">Please verify your email before accessing your dashboard.</p>

            @if (session('status') === 'verification-link-sent')
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-sm text-emerald-700 font-medium">
                    A new verification link has been sent to your email address.
                    <div class="mt-2 text-[11px] opacity-75">Check storage/logs/laravel.log (MAIL_MAILER=log)</div>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                @csrf
                <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-500/20 text-sm font-semibold text-white bg-emerald-500 hover:bg-emerald-600 transition-all active:scale-[0.98]">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-bold text-gray-400 hover:text-emerald-600 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>