{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.guest')

@section('content')
@if ($errors->any())
    <div class="alert alert-premium animate__animated animate__shakeX">
        <ul class="mb-0 list-unstyled">
            @foreach ($errors->all() as $error)
                <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-4">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text border-end-0"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" 
                   placeholder="nama@email.com" required autofocus>
        </div>
        @error('email')
            <div class="invalid-feedback d-block mt-1 x-small fw-bold">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label for="password" class="form-label mb-0">Kata Sandi</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link x-small" style="font-size: 0.75rem;">
                    Lupa Kata Sandi?
                </a>
            @endif
        </div>
        <div class="input-group">
            <span class="input-group-text border-end-0"><i class="fas fa-lock"></i></span>
            <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                   id="password" name="password" placeholder="••••••••" required>
        </div>
        @error('password')
            <div class="invalid-feedback d-block mt-1 x-small fw-bold">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input shadow-none" id="remember" name="remember">
        <label class="form-check-label text-muted small" for="remember">Ingat saya di perangkat ini</label>
    </div>

    <div class="d-grid mb-2">
        <button type="submit" class="btn btn-primary btn-lg py-3">
            Masuk Sekarang <i class="fas fa-arrow-right ms-2 pulse-icon"></i>
        </button>
    </div>
</form>

<style>
    .pulse-icon {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: translateX(0); }
        50% { transform: translateX(5px); }
        100% { transform: translateX(0); }
    }
    .x-small { font-size: 0.8rem; }
</style>
@endsection