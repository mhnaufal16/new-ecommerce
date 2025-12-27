@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Tambah Produk Baru</h2>
                <a href="{{ route('vendor.products.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-4">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary">Informasi Dasar</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control rounded-3" name="name" value="{{ old('name') }}" placeholder="Contoh: Sepatu Lari Pro" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Deskripsi Singkat <span class="text-danger">*</span></label>
                                    <textarea class="form-control rounded-3" name="short_description" rows="2" required>{{ old('short_description') }}</textarea>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-bold">Deskripsi Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control rounded-3" name="description" rows="5" required>{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary">Harga & Inventaris</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Harga (IDR) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light rounded-start-3">Rp</span>
                                            <input type="number" class="form-control rounded-end-3" name="price" value="{{ old('price') }}" min="0" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-3" name="quantity" value="{{ old('quantity') }}" min="0" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">SKU <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-3" name="sku" value="{{ old('sku') }}" placeholder="Contoh: SEP-001" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary">Kategori & Status</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select class="form-select rounded-3" name="status" required>
                                        <option value="active">Aktif</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Brand</label>
                                    <select class="form-select rounded-3" name="brand_id">
                                        <option value="">Pilih Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <div class="border rounded-3 p-2" style="max-height: 150px; overflow-y: auto;">
                                        @foreach($categories as $category)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $category->id }}" id="cat{{ $category->id }}">
                                                <label class="form-check-label small" for="cat{{ $category->id }}">
                                                    {{ $category->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-primary">Gambar Produk</h5>
                            </div>
                            <div class="card-body">
                                <input type="file" class="form-control rounded-3" name="image" accept="image/*">
                                <p class="small text-muted mt-2 mb-0">Format: JPG, PNG, WEBP (Maks: 2MB)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow">
                        <i class="fas fa-save me-2"></i>Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
