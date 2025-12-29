@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-plus-circle me-2 text-primary"></i>Buat Kupon Baru</h2>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light rounded-pill px-4 fw-bold shadow-sm border">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        
        <div class="row g-4">
            <!-- Main Form -->
            <div class="col-lg-8">
                <!-- Basic Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Dasar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kode Kupon <span class="text-danger">*</span></label>
                            <input type="text" name="code" class="form-control rounded-3" placeholder="Contoh: WELCOME10" value="{{ old('code') }}" style="text-transform: uppercase" required>
                            <small class="text-muted">Hanya huruf besar dan angka, tanpa spasi</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Kupon <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control rounded-3" placeholder="Contoh: Welcome Discount 10%" value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Deskripsi</label>
                            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Deskripsi singkat untuk customer">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Discount Rules -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-percent me-2 text-success"></i>Aturan Diskon</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tipe Diskon <span class="text-danger">*</span></label>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3 h-100 d-flex align-items-center position-relative">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="discount_type" id="typePercentage" value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'checked' : '' }} required>
                                            <label class="form-check-label fw-medium stretched-link ms-2" for="typePercentage">
                                                Persentase (%)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3 h-100 d-flex align-items-center position-relative">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="discount_type" id="typeFixed" value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium stretched-link ms-2" for="typeFixed">
                                                Fixed Amount (Rp)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3 h-100 d-flex align-items-center position-relative">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="radio" name="discount_type" id="typeFreeShip" value="free_shipping" {{ old('discount_type') === 'free_shipping' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium stretched-link ms-2" for="typeFreeShip">
                                                Gratis Ongkir
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Nilai Diskon <span class="text-danger">*</span></label>
                                <input type="number" name="discount_value" class="form-control rounded-3" step="0.01" min="0" value="{{ old('discount_value') }}" required>
                                <small class="text-muted">Untuk gratis ongkir, isi 0</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Maksimal Potongan (Opsional)</label>
                                <input type="number" name="max_discount_amount" class="form-control rounded-3" step="1000" min="0" value="{{ old('max_discount_amount') }}" placeholder="Kosongkan jika tidak ada batas">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Minimum Belanja (Opsional)</label>
                            <input type="number" name="min_order_amount" class="form-control rounded-3" step="1000" min="0" value="{{ old('min_order_amount') }}" placeholder="Rp 0 = tidak ada minimum">
                        </div>
                    </div>
                </div>

                <!-- Restrictions (Collapsed) -->
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white py-3 px-4 border-0">
                        <button class="btn btn-link text-decoration-none p-0 w-100 text-start fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#restrictionsCollapse">
                            <i class="fas fa-filter me-2 text-warning"></i>Pembatasan Produk/Kategori <small class="text-muted">(Opsional)</small>
                            <i class="fas fa-chevron-down float-end"></i>
                        </button>
                    </div>
                    <div class="collapse" id="restrictionsCollapse">
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kategori Khusus</label>
                                <select name="categories[]" class="form-select rounded-3" multiple size="5">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Kosongkan jika berlaku untuk semua kategori</small>
                            </div>

                            <div class="mb-0">
                                <label class="form-label fw-bold">Produk Khusus</label>
                                <select name="products[]" class="form-select rounded-3" multiple size="5">
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array($product->id, old('products', [])) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Kosongkan jika berlaku untuk semua produk</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Usage Limits -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-0">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-users me-2 text-info"></i>Batas Penggunaan</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Total Kuota</label>
                            <input type="number" name="usage_limit" class="form-control rounded-3" min="1" value="{{ old('usage_limit') }}" placeholder="Kosongkan = Unlimited">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Per Customer <span class="text-danger">*</span></label>
                            <input type="number" name="usage_per_customer" class="form-control rounded-3" min="1" value="{{ old('usage_per_customer', 1) }}" required>
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 px-4 border-0">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-calendar me-2 text-warning"></i>Periode Aktif</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mulai Dari</label>
                            <input type="datetime-local" name="starts_at" class="form-control rounded-3" value="{{ old('starts_at') }}">
                        </div>

                        <div class="mb-0">
                            <label class="form-label fw-bold">Berakhir</label>
                            <input type="datetime-local" name="expires_at" class="form-control rounded-3" value="{{ old('expires_at') }}">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="isActive">
                                Aktifkan Kupon
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm">
                    <i class="fas fa-save me-2"></i>Simpan Kupon
                </button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Auto uppercase for coupon code
    document.querySelector('input[name="code"]').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
    });
</script>
@endpush
