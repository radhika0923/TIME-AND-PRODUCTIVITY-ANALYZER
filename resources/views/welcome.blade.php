<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Time & Productivity Analyzer</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            html, body { height: 100%; width: 100%; }
            body {
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(to bottom, #0f172a, #020617);
                color: white;
                overflow-x: hidden;
            }
            button, a, [role="button"], [x-on\:click], [\@click] {
                cursor: pointer;
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
                background: rgba(0, 0, 0, 0.5);
                pointer-events: none;
            }
            nav {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                z-index: 20;
                background: linear-gradient(to bottom, #0f172a, transparent);
                padding: 1.5rem 1.5rem;
            }
            .nav-container {
                max-width: 80rem;
                margin: 0 auto;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .logo {
                font-size: 1.5rem;
                font-weight: 700;
            }
            .nav-links {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            .nav-links a {
                padding: 0.5rem 1.25rem;
                font-size: 0.875rem;
                text-decoration: none;
                color: #e5e7eb;
                transition: color 0.3s;
            }
            .nav-links a:hover {
                color: white;
            }
            .signup-btn {
                padding: 0.5rem 1.25rem;
                background: #2563eb;
                border-radius: 0.5rem;
                transition: background 0.3s;
                color: white;
                border: none;
                cursor: pointer;
            }
            .signup-btn:hover {
                background: #1d4ed8;
            }
            .bg-slider-container {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                z-index: 0;
                overflow: hidden;
            }
            .relative-content {
                position: relative;
                z-index: 10;
            }
            .hero {
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding-top: 5rem;
            }
            .hero-content {
                max-width: 56rem;
                margin: 0 auto;
                padding: 1.5rem;
            }
            h1 {
                font-size: clamp(2rem, 8vw, 3.75rem);
                font-weight: 700;
                margin-bottom: 1.5rem;
                line-height: 1.2;
            }
            .hero p {
                font-size: 1.125rem;
                color: #d1d5db;
                margin-bottom: 2rem;
                max-width: 42rem;
                margin-left: auto;
                margin-right: auto;
            }
            .button-group {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                justify-content: center;
            }
            @media (min-width: 640px) {
                .button-group {
                    flex-direction: row;
                }
            }
            .btn {
                display: inline-block;
                padding: 1rem 2rem;
                font-weight: 600;
                border-radius: 0.5rem;
                text-decoration: none;
                transition: all 0.3s;
                border: none;
                cursor: pointer;
                font-size: 1rem;
            }
            .btn-primary {
                background: #2563eb;
                color: white;
            }
            .btn-primary:hover {
                background: #1d4ed8;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
            }
            .btn-secondary {
                border: 2px solid #2563eb;
                color: #60a5fa;
                background: transparent;
            }
            .btn-secondary:hover {
                background: #2563eb;
                color: white;
            }
            .features {
                padding: 5rem 1.5rem;
                background: linear-gradient(to bottom, transparent, #1e293b, #0f172a);
                position: relative;
                z-index: 10;
            }
            .features-container {
                max-width: 80rem;
                margin: 0 auto;
            }
            .features h2 {
                font-size: clamp(2rem, 6vw, 3rem);
                font-weight: 700;
                text-align: center;
                margin-bottom: 3rem;
            }
            .features-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 2rem;
                margin-bottom: 2rem;
            }
            @media (min-width: 768px) {
                .features-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
            .feature-card {
                background: #1e293b;
                padding: 2rem;
                border-radius: 0.75rem;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
                transition: all 0.3s;
            }
            .feature-card:hover {
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
                transform: translateY(-0.5rem);
            }
            .feature-icon {
                font-size: 2.25rem;
                margin-bottom: 1rem;
            }
            .feature-card h3 {
                font-size: 1.5rem;
                font-weight: 700;
                margin-bottom: 0.75rem;
            }
            .feature-card p {
                color: #d1d5db;
                line-height: 1.6;
            }
            .cta {
                padding: 5rem 1.5rem;
                background: linear-gradient(to top, #1e40af, transparent);
                text-align: center;
                position: relative;
                z-index: 10;
            }
            .cta-content {
                max-width: 42rem;
                margin: 0 auto;
            }
            .cta h2 {
                font-size: clamp(1.75rem, 5vw, 2.25rem);
                font-weight: 700;
                margin-bottom: 1rem;
            }
            .cta p {
                font-size: 1.125rem;
                color: #f3f4f6;
                margin-bottom: 2rem;
            }
            .btn-white {
                background: white;
                color: #2563eb;
                font-weight: 700;
            }
            .btn-white:hover {
                background: #f3f4f6;
            }
            footer {
                padding: 3rem 1.5rem;
                background: #0f172a;
                border-top: 1px solid #1e293b;
                text-align: center;
                color: #9ca3af;
                position: relative;
                z-index: 10;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav>
            <div class="nav-container">
                <div class="logo">⏱ Time Analyzer</div>
                @if (Route::has('login'))
                    <div class="nav-links">
                        @auth
                            <a href="{{ url('/dashboard') }}">Dashboard</a>
                        @else
                            <a href="{{ route('login.form') }}">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register.form') }}" class="signup-btn">Sign up</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </nav>

        <!-- Background Image Slider -->
        <div class="bg-slider-container">
            <div id="bg-slider"></div>
            <div class="dark-overlay"></div>
        </div>

        <!-- Main Content -->
        <div class="relative-content">
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-content">
                    <h1>Master Your Time, Maximize Your Productivity</h1>
                    <p>Track your time, manage your tasks, and unlock actionable insights with AI-powered analytics. Take control of every minute.</p>
                    
                    <div class="button-group">
                        <a href="{{ route('register.form') }}" class="btn btn-primary">Get Started</a>
                        <a href="{{ route('login.form') }}" class="btn btn-secondary">Log In</a>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="features">
                <div class="features-container">
                    <h2>Powerful Features</h2>
                    
                    <div class="features-grid">
                        <!-- Feature 1: Time Tracking -->
                        <div class="feature-card">
                            <div class="feature-icon">⏱️</div>
                            <h3>Time Tracking</h3>
                            <p>Automatically track time spent on projects and tasks with precision. Get real-time insights into where your time goes.</p>
                        </div>

                        <!-- Feature 2: Task Management -->
                        <div class="feature-card">
                            <div class="feature-icon">✅</div>
                            <h3>Task Management</h3>
                            <p>Organize tasks by priority and deadlines. Break down projects and track completion rates effortlessly.</p>
                        </div>

                        <!-- Feature 3: Smart Analytics -->
                        <div class="feature-card">
                            <div class="feature-icon">📊</div>
                            <h3>Smart Analytics</h3>
                            <p>AI-driven insights reveal productivity patterns, bottlenecks, and opportunities for improvement. Make data-driven decisions.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta">
                <div class="cta-content">
                    <h2>Ready to Transform Your Productivity?</h2>
                    <p>Join thousands of professionals optimizing their time with Time & Productivity Analyzer.</p>
                    <a href="{{ route('register.form') }}" class="btn btn-white">Start Free Trial</a>
                </div>
            </section>

            <!-- Footer -->
            <footer>
                <p>&copy; 2026 Time & Productivity Analyzer. All rights reserved.</p>
            </footer>
        </div>

        <!-- Background Slider Script -->
        <script>
            const bgSlider = document.getElementById('bg-slider');
            const images = ['/images/bg1.webp', '/images/bg2.png', '/images/bg3.webp'];
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
        </script>
    </body>
</html>