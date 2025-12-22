{{-- resources/views/auth/register.blade.php --}}
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

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-4">
        <label for="name" class="form-label">Nama Lengkap</label>
        <div class="input-group">
            <span class="input-group-text border-end-0"><i class="fas fa-user"></i></span>
            <input type="text" class="form-control border-start-0 @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name') }}" 
                   placeholder="Masukkan nama lengkap Anda" required autofocus>
        </div>
        @error('name')
            <div class="invalid-feedback d-block mt-1 x-small fw-bold">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <span class="input-group-text border-end-0"><i class="fas fa-envelope"></i></span>
            <input type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email') }}" 
                   placeholder="nama@email.com" required>
        </div>
        @error('email')
            <div class="invalid-feedback d-block mt-1 x-small fw-bold">{{ $message }}</div>
        @enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <label for="password" class="form-label">Kata Sandi</label>
            <div class="input-group">
                <span class="input-group-text border-end-0"><i class="fas fa-lock"></i></span>
                <input type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                       id="password" name="password" placeholder="••••••••" required>
            </div>
            @error('password')
                <div class="invalid-feedback d-block mt-1 x-small fw-bold">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-4">
            <label for="password_confirmation" class="form-label">Konfirmasi</label>
            <div class="input-group">
                <span class="input-group-text border-end-0"><i class="fas fa-check-double"></i></span>
                <input type="password" class="form-control border-start-0" 
                       id="password_confirmation" name="password_confirmation" 
                       placeholder="••••••••" required>
            </div>
        </div>
    </div>

    <div class="mb-4 form-check">
        <input type="checkbox" class="form-check-input shadow-none" id="terms" name="terms" required>
        <label class="form-check-label text-muted small" for="terms">
            Saya menyetujui <a href="#" class="auth-link">Syarat & Ketentuan</a> yang berlaku.
        </label>
    </div>

    <div class="d-grid mb-2">
        <button type="submit" class="btn btn-primary btn-lg py-3">
            Daftar Akun <i class="fas fa-user-plus ms-2"></i>
        </button>
    </div>
</form>

<style>
    .x-small { font-size: 0.8rem; }
</style>
@endsection