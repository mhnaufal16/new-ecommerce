{{-- resources/views/layouts/guest.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    @php
        $favicon = \App\Models\Setting::getValue('general', 'store_favicon', '/favicon.png');
        $logo = \App\Models\Setting::getValue('general', 'store_logo', '/images/logo.png');
    @endphp
    <link rel="icon" type="image/png" href="{{ asset($favicon) }}?v=1">
    <link rel="shortcut icon" href="{{ asset($favicon) }}?v=1">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-hover: #0056b3;
            --secondary-color: #6c757d;
            --bg-color: #f8faff;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(13, 110, 253, 0.05) 0, transparent 50%), 
                radial-gradient(at 100% 100%, rgba(13, 110, 253, 0.05) 0, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Figtree', sans-serif;
            padding: 20px;
        }

        .auth-card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 1rem 3rem rgba(0,0,0,.08);
            padding: 3rem;
            width: 100%;
            max-width: 480px;
            border: 1px solid rgba(13, 110, 253, 0.05);
            transition: all 0.3s ease;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .auth-icon-box {
            width: 64px;
            height: 64px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--primary-color);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem;
        }

        .auth-title {
            color: #1e293b;
            font-weight: 800;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
        }

        .auth-subtitle {
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .input-group-text {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            color: #94a3b8;
            padding-left: 1.25rem;
            border-radius: 0.75rem 0 0 0.75rem;
        }

        .form-control {
            border-color: #e2e8f0;
            padding: 0.75rem 1.25rem;
            border-radius: 0.75rem;
            background-color: #f8fafc;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        /* Input group focus within */
        .input-group:focus-within .input-group-text {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.875rem;
            font-weight: 700;
            border-radius: 0.75rem;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3);
        }

        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            color: #64748b;
            font-size: 0.9rem;
        }

        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .alert-premium {
            border-radius: 0.75rem;
            border: none;
            background-color: #fef2f2;
            color: #991b1b;
            padding: 1rem;
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon-box" style="overflow: hidden; background: transparent;">
                <img src="{{ asset($logo) }}" alt="{{ config('app.name') }} Logo" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h2 class="auth-title">{{ config('app.name') }}</h2>
            <p class="auth-subtitle">
                @if (Request::is('login'))
                    Selamat datang kembali! Silakan masuk.
                @elseif (Request::is('register'))
                    Mulai petualangan belanja Anda di sini.
                @elseif (Request::is('password/reset'))
                    Atur ulang kata sandi Anda.
                @else
                    Akses akun Anda.
                @endif
            </p>
        </div>

        @yield('content')

        <div class="auth-footer border-top pt-4">
            <p class="mb-2">
                @if (Request::is('login'))
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="auth-link">Daftar Sekarang</a>
                @elseif (Request::is('register'))
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="auth-link">Masuk di Sini</a>
                @endif
            </p>
            <p class="mb-0 x-small text-muted">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>