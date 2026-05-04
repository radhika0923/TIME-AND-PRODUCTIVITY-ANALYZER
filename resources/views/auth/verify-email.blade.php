<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Verify Email - Time & Productivity Analyzer</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&display=swap" rel="stylesheet" />
        <style>
            * { box-sizing: border-box; }
            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                font-family: 'Instrument Sans', sans-serif;
                background: linear-gradient(135deg, #0f172a, #020617);
                color: #e5e7eb;
                padding: 1.5rem;
            }
            .card {
                width: 100%;
                max-width: 520px;
                background: rgba(15, 23, 42, 0.9);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 1rem;
                padding: 2rem;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
                text-align: center;
            }
            h1 { margin: 0 0 0.75rem; font-size: 2rem; color: #fff; }
            p { margin: 0.5rem 0; line-height: 1.6; color: #cbd5e1; }
            .notice { margin-top: 1rem; font-weight: 600; color: #93c5fd; }
            .status {
                margin: 1rem 0 0;
                padding: 0.75rem 1rem;
                border-radius: 0.75rem;
                background: rgba(37, 99, 235, 0.15);
                color: #bfdbfe;
            }
            .btn {
                display: inline-block;
                width: 100%;
                margin-top: 1rem;
                padding: 0.875rem 1.25rem;
                border: 0;
                border-radius: 0.75rem;
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                color: #fff;
                font: inherit;
                font-weight: 600;
                text-decoration: none;
                cursor: pointer;
            }
            .link {
                display: inline-block;
                margin-top: 1rem;
                color: #93c5fd;
                text-decoration: none;
            }
        </style>
    </head>
    <body>
        <div class="card">
            <h1>Verify your email</h1>
            <p>Please verify your email before accessing dashboard.</p>

            @if (session('status') === 'verification-link-sent')
                <div class="status">
                    A new verification link has been sent to your email address.
                    Check <strong>storage/logs/laravel.log</strong> because mail is set to <strong>MAIL_MAILER=log</strong>.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="link" style="background:none;">Logout</button>
            </form>
        </div>
    </body>
</html>