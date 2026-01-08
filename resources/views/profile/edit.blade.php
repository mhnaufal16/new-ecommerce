@extends('layouts.user')

@section('title', 'Profil Saya - ' . config('app.name'))

@section('user_content')
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1">Pengaturan Profil</h2>
            <p class="text-muted mb-0">Atur informasi akun, keamanan, dan alamat pengiriman Anda.</p>
        </div>
    </div>

    <!-- Information Section -->
    <div id="info" class="card border-0 shadow-premium rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0">
            <h5 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2 text-primary"></i>Informasi Profil</h5>
        </div>
        <div class="card-body p-4 pt-0 text-dark">
            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold small">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold small">Email</label>
                    <input type="email" name="email" id="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="form-label fw-bold small">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" class="form-control rounded-3" value="{{ old('phone', $user->phone) }}">
                    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Simpan Perubahan</button>
                    @if (session('status') === 'profile-updated')
                        <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>Berhasil disimpan.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Security Section -->
    <div id="security" class="card border-0 shadow-premium rounded-4 mb-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0">
            <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2 text-primary"></i>Perbarui Kata Sandi</h5>
        </div>
        <div class="card-body p-4 pt-0 text-dark">
            <form method="post" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label fw-bold small">Kata Sandi Saat Ini</label>
                    <input type="password" name="current_password" id="update_password_current_password" class="form-control rounded-3">
                    @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password" class="form-label fw-bold small">Kata Sandi Baru</label>
                    <input type="password" name="password" id="update_password_password" class="form-control rounded-3">
                    @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="update_password_password_confirmation" class="form-label fw-bold small">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control rounded-3">
                    @error('password_confirmation', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex align-items-center gap-3">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Perbarui Sandi</button>
                    @if (session('status') === 'password-updated')
                        <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>Sandi berhasil diubah.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll to hash
        if (window.location.hash) {
            const el = document.querySelector(window.location.hash);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
                // Update active sidebar link
                document.querySelectorAll('.list-group-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href') === window.location.hash) {
                        item.classList.add('active');
                    }
                });
            }
        }
    });
</script>
@endpush
