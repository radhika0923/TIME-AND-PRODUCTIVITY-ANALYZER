<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - Time & Productivity Analyzer</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            html, body {
                height: 100%;
                width: 100%;
            }

            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(to bottom, #0f172a, #020617);
                color: #1f2937;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                overflow-x: hidden;
            }

            .bg-slider-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 0;
                overflow: hidden;
            }

            #bg-slider {
                width: 100%;
                height: 100%;
                background-size: cover;
                background-position: center;
                transition: opacity 0.75s ease-in-out;
            }

            .dark-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.6);
                pointer-events: none;
            }

            .container {
                position: relative;
                z-index: 10;
                width: 100%;
                max-width: 420px;
                padding: 1.5rem;
                animation: slideUp 0.6s ease-out;
            }

            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .card {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border-radius: 1rem;
                padding: 2.5rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .logo {
                font-size: 2rem;
                margin-bottom: 1rem;
            }

            .header h1 {
                font-size: 1.875rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 0.5rem;
            }

            .header p {
                color: #6b7280;
                font-size: 0.875rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            label {
                display: block;
                margin-bottom: 0.5rem;
                color: #374151;
                font-weight: 500;
                font-size: 0.875rem;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 0.75rem 1rem;
                border: 1.5px solid #e5e7eb;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-family: 'Instrument Sans', sans-serif;
                transition: all 0.3s ease;
                background: #f9fafb;
            }

            input[type="email"]:focus,
            input[type="password"]:focus {
                outline: none;
                border-color: #2563eb;
                background: white;
                box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            }

            input::placeholder {
                color: #d1d5db;
            }

            .form-group.error input {
                border-color: #dc2626;
                background: #fef2f2;
            }

            .error-message {
                color: #dc2626;
                font-size: 0.75rem;
                margin-top: 0.25rem;
                display: none;
            }

            .form-group.error .error-message {
                display: block;
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                margin-bottom: 1.5rem;
            }

            input[type="checkbox"] {
                width: 1rem;
                height: 1rem;
                margin-right: 0.5rem;
                cursor: pointer;
                accent-color: #2563eb;
            }

            .checkbox-group label {
                margin-bottom: 0;
                cursor: pointer;
                color: #4b5563;
            }

            .button-group {
                margin-bottom: 1.5rem;
            }

            button, .btn {
                width: 100%;
                padding: 0.875rem 1.5rem;
                font-size: 1rem;
                font-weight: 600;
                border: none;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: all 0.3s ease;
                font-family: 'Instrument Sans', sans-serif;
                text-decoration: none;
                display: inline-block;
                text-align: center;
            }

            .btn-primary {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: white;
                border: none;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #1d4ed8, #1e40af);
                box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.3);
                transform: translateY(-2px);
            }

            .btn-primary:active {
                transform: translateY(0);
            }

            .divider {
                display: flex;
                align-items: center;
                margin: 1.5rem 0;
                gap: 1rem;
            }

            .divider::before,
            .divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: #e5e7eb;
            }

            .divider span {
                color: #9ca3af;
                font-size: 0.75rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .social-buttons {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 1rem;
                margin-bottom: 1.5rem;
            }

            .btn-social {
                padding: 0.75rem;
                border: 1.5px solid #e5e7eb;
                background: white;
                color: #374151;
                font-size: 0.875rem;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-social:hover {
                border-color: #2563eb;
                background: #eff6ff;
                transform: translateY(-1px);
            }

            .footer-text {
                text-align: center;
                margin-bottom: 1rem;
            }

            .footer-text p {
                color: #6b7280;
                font-size: 0.875rem;
            }

            .footer-text a {
                color: #2563eb;
                text-decoration: none;
                font-weight: 600;
                transition: color 0.3s;
            }

            .footer-text a:hover {
                color: #1d4ed8;
                text-decoration: underline;
            }

            .loading-spinner {
                display: none;
                width: 1rem;
                height: 1rem;
                border: 2px solid #e5e7eb;
                border-top-color: #2563eb;
                border-radius: 50%;
                animation: spin 0.6s linear infinite;
                margin: 0 auto;
            }

            @keyframes spin {
                to { transform: rotate(360deg); }
            }

            button:disabled {
                opacity: 0.7;
                cursor: not-allowed;
            }

            button:disabled .loading-spinner {
                display: inline-block;
            }

            button:disabled span {
                display: none;
            }

            .password-wrapper {
                position: relative;
                width: 100%;
            }

            .password-wrapper input[type="password"],
            .password-wrapper input[type="text"] {
                padding-right: 2.75rem;
            }

            .password-toggle {
                position: absolute;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                cursor: pointer;
                color: #9ca3af;
                padding: 0.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1rem;
                z-index: 5;
            }

            .password-toggle:hover {
                color: #6b7280;
            }

            .password-toggle:focus {
                outline: none;
            }
        </style>
    </head>
    <body>
        <div class="bg-slider-container">
            <div id="bg-slider"></div>
            <div class="dark-overlay"></div>
        </div>

        <div class="container">
            <div class="card">
                <div class="header">
                    <div class="logo">⏱</div>
                    <h1>Welcome Back</h1>
                    <p>Log in to your account</p>
                </div>

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Social Login Buttons (Optional) -->
                    <div class="social-buttons">
                        <button type="button" class="btn-social" title="Login with Google">
                            <span>🔵 Google</span>
                        </button>
                        <button type="button" class="btn-social" title="Login with GitHub">
                            <span>⭐ GitHub</span>
                        </button>
                    </div>

                    <div class="divider">
                        <span>Or continue with email</span>
                    </div>

                    <!-- Email Field -->
                    <div class="form-group @error('email') error @enderror">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="you@example.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                        >
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group @error('password') error @enderror">
                        <label for="password">Password</label>
                        <div class="password-wrapper">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                            >
                            <button
                                type="button"
                                class="password-toggle"
                                onclick="togglePasswordVisibility(); document.getElementById('password').focus();"
                                title="Toggle password visibility"
                                tabindex="-1"
                            >
                                <span id="toggleIcon">👁️</span>
                            </button>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="checkbox-group">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                        >
                        <label for="remember">Remember me</label>
                    </div>

                    <!-- Login Button -->
                    <div class="button-group">
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span>Sign In</span>
                            <div class="loading-spinner"></div>
                        </button>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="footer-text">
                        <p>
                            Don't have an account?
                            <a href="{{ route('register.form') }}">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const bgSlider = document.getElementById('bg-slider');
            const images = ['/images/bg1.jpg', '/images/bg2.jpg', '/images/bg3.jpg'];
            let currentIndex = 0;

            // Fallback gradients if images don't load
            const gradients = [
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
            ];

            function rotateBackground() {
                currentIndex = (currentIndex + 1) % images.length;
                
                // Try to load image, fall back to gradient
                const img = new Image();
                img.onload = function() {
                    bgSlider.style.backgroundImage = `url('${images[currentIndex]}')`;
                };
                img.onerror = function() {
                    bgSlider.style.backgroundImage = gradients[currentIndex];
                };
                img.src = images[currentIndex];
            }

            // Initialize with first image or gradient
            const img = new Image();
            img.onload = function() {
                bgSlider.style.backgroundImage = `url('${images[0]}')`;
            };
            img.onerror = function() {
                bgSlider.style.backgroundImage = gradients[0];
            };
            img.src = images[0];

            // Change background every 3.5 seconds
            setInterval(rotateBackground, 3500);

            function togglePasswordVisibility() {
                const passwordInput = document.getElementById('password');
                const toggleIcon = document.getElementById('toggleIcon');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.textContent = '🙈';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.textContent = '👁️';
                }
            }

            document.getElementById('loginForm').addEventListener('submit', function() {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
            });

            // Auto-hide error messages after form interaction
            const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.closest('.form-group').classList.remove('error');
                });
            });
        </script>
    </body>
</html>
