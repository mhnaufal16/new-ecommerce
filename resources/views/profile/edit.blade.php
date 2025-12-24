{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Profil Saya - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h2"><i class="fas fa-user-circle me-2"></i>Pengaturan Profil</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="list-group list-group-flush rounded">
                    <a href="#info" class="list-group-item list-group-item-action active py-3">
                        <i class="fas fa-user me-2"></i>Informasi Profil
                    </a>
                    <a href="#security" class="list-group-item list-group-item-action py-3">
                        <i class="fas fa-shield-alt me-2"></i>Keamanan
                    </a>
                    <a href="#addresses" class="list-group-item list-group-item-action py-3">
                        <i class="fas fa-map-marker-alt me-2"></i>Alamat Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action py-3 text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Information Section -->
            <div id="info" class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Informasi Profil</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                            @if (session('status') === 'profile-updated')
                                <span class="text-success small"><i class="fas fa-check me-1"></i>Berhasil disimpan.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Section -->
            <div id="security" class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Perbarui Kata Sandi</h5>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="update_password_current_password" class="form-label">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" id="update_password_current_password" class="form-control">
                            @error('current_password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password" class="form-label">Kata Sandi Baru</label>
                            <input type="password" name="password" id="update_password_password" class="form-control">
                            @error('password', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="update_password_password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" id="update_password_password_confirmation" class="form-control">
                            @error('password_confirmation', 'updatePassword') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Perbarui Sandi</button>
                            @if (session('status') === 'password-updated')
                                <span class="text-success small"><i class="fas fa-check me-1"></i>Sandi berhasil diubah.</span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Addresses Section -->
            <div id="addresses" class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Alamat Pengiriman</h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-1"></i>Tambah Alamat
                    </button>
                </div>
                <div class="card-body p-4 text-dark">
                    @if($user->addresses->count() > 0)
                    <div class="row">
                        @foreach($user->addresses as $address)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 {{ $address->is_primary ? 'border-primary' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-2">{{ $address->label }}</h6>
                                        @if($address->is_primary)
                                        <span class="badge bg-primary small">Utama</span>
                                        @endif
                                    </div>
                                    <p class="small fw-bold mb-1">{{ $address->recipient_name }}</p>
                                    <p class="small text-muted mb-0">{{ $address->phone }}</p>
                                    <p class="small mb-0 mt-2">{{ $address->address }}</p>
                                    <p class="small mb-0">{{ $address->subdistrict }}, {{ $address->district }}</p>
                                    <p class="small mb-0">{{ $address->city_name }}, {{ $address->province_name }}</p>
                                    
                                    <div class="mt-3 pt-2 border-top d-flex gap-2">
                                        @if(!$address->is_primary)
                                        <form method="POST" action="{{ route('profile.address.primary', $address) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Jadikan Utama</button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('profile.address.destroy', $address) }}" onsubmit="return confirm('Hapus alamat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted">Anda belum memiliki alamat tersimpan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-dark">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('profile.address.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Label Alamat (Contoh: Rumah, Kantor)</label>
                            <input type="text" name="label" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="recipient_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Pos</label>
                            <input type="text" name="postal_code" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Provinsi</label>
                            <select name="province_id" id="province_select" class="form-control" required>
                                <option value="">Pilih Provinsi</option>
                                <option value="1" data-name="Bali">Bali</option>
                                <option value="5" data-name="DI Yogyakarta">DI Yogyakarta</option>
                                <option value="6" data-name="DKI Jakarta">DKI Jakarta</option>
                                <option value="9" data-name="Jawa Barat">Jawa Barat</option>
                                <option value="10" data-name="Jawa Tengah">Jawa Tengah</option>
                                <option value="11" data-name="Jawa Timur">Jawa Timur</option>
                            </select>
                            <input type="hidden" name="province_name" id="province_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota/Kabupaten</label>
                            <select name="city_id" id="city_select" class="form-control" required>
                                <option value="">Pilih Kota</option>
                                <!-- Mocked cities based on selected province -->
                            </select>
                            <input type="hidden" name="city_name" id="city_name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" name="district" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kelurahan</label>
                            <input type="text" name="subdistrict" class="form-control" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const provinceSelect = document.getElementById('province_select');
        const provinceNameInput = document.getElementById('province_name');
        const citySelect = document.getElementById('city_select');
        const cityNameInput = document.getElementById('city_name');

        const mockCities = {
            "5": [{id: 501, name: "Yogyakarta"}, {id: 502, name: "Sleman"}, {id: 503, name: "Bantul"}],
            "6": [{id: 151, name: "Jakarta Selatan"}, {id: 152, name: "Jakarta Pusat"}, {id: 153, name: "Jakarta Barat"}],
            "11": [{id: 444, name: "Surabaya"}, {id: 445, name: "Malang"}, {id: 446, name: "Sidoarjo"}],
            "10": [{id: 1001, name: "Semarang"}, {id: 1002, name: "Surakarta"}, {id: 1003, name: "Magelang"}],
            "9": [{id: 9001, name: "Bandung"}, {id: 9002, name: "Bekasi"}, {id: 9003, name: "Bogor"}]
        };

        function populateCitiesForProvince(provinceId) {
            // clear and set placeholder
            citySelect.innerHTML = '<option value="">Pilih Kota</option>';

            if (!provinceId) {
                cityNameInput.value = '';
                return;
            }

            // Try fetching from backend endpoint first
            fetch('/locations/cities?province_id=' + encodeURIComponent(provinceId), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(resp => {
                if (!resp.ok) throw new Error('Network response was not ok');
                return resp.json();
            })
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    // fallback to local mock list
                    const fallback = mockCities[provinceId] || [];
                    fillCityOptions(fallback);
                    return;
                }
                fillCityOptions(data);
            })
            .catch(() => {
                const fallback = mockCities[provinceId] || [];
                fillCityOptions(fallback);
            });

            function fillCityOptions(list) {
                list.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.text = city.name;
                    option.setAttribute('data-name', city.name);
                    citySelect.add(option);
                });
                cityNameInput.value = '';
                citySelect.value = '';
            }
        }

        provinceSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            provinceNameInput.value = selectedOption ? selectedOption.getAttribute('data-name') || '' : '';
            populateCitiesForProvince(this.value);
        });

        citySelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            cityNameInput.value = selectedOption ? (selectedOption.getAttribute('data-name') || '') : '';
        });

        // When modal is shown, populate cities if a province is pre-selected
        const addAddressModal = document.getElementById('addAddressModal');
        if (addAddressModal) {
            addAddressModal.addEventListener('show.bs.modal', function() {
                if (provinceSelect.value) {
                    populateCitiesForProvince(provinceSelect.value);
                }
            });
        }

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
