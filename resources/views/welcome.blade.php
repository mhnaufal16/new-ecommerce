{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section position-relative overflow-hidden mb-5" style="background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); color: white; padding: 100px 0; border-radius: 0 0 5rem 5rem;">
    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge bg-white text-primary rounded-pill px-3 py-2 mb-3 fw-bold shadow-sm">KOLEKSI TERBARU 2025</span>
                <h1 class="display-3 fw-bold mb-4">Temukan Gaya Hidup Modern Anda Disini</h1>
                <p class="lead mb-5 opacity-75">Dapatkan produk-produk pilihan dengan kualitas terbaik dan harga yang kompetitif. Belanja aman, nyaman, dan cepat.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-white btn-lg rounded-pill px-5 fw-bold shadow">
                        Mulai Belanja <i class="fas fa-shopping-bag ms-2"></i>
                    </a>
                    <a href="#featured" class="btn btn-outline-light btn-lg rounded-pill px-4 fw-bold">
                        Lihat Promo
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="position-relative d-inline-block">
                    <img src="{{ asset('images/hero-ecommerce.png') }}" 
                         alt="Happy Shoppers" class="img-fluid rounded-4 shadow-lg mb-4" style="max-height: 500px; object-fit: cover; border: 10px solid rgba(255,255,255,0.1);">
                    <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded-4 shadow-lg text-dark m-4 d-none d-md-block">
                        <div class="d-flex align-items-center">
                            <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                                <i class="fas fa-check text-success"></i>
                            </div>
                            <div class="text-start">
                                <div class="fw-bold">100% Original</div>
                                <div class="small text-muted">Jaminan Barang Asli</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating Abstract Shapes -->
    <div class="position-absolute top-0 end-0 p-5 mt-5 opacity-25">
        <i class="fas fa-circle fa-10x text-white"></i>
    </div>
</div>

<div class="container py-5">
    <!-- Featured Categories -->
    <div class="row mb-5 gy-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white border-0 shadow-sm rounded-4 overflow-hidden h-100 p-4 position-relative">
                <div class="z-index-1">
                    <h4 class="fw-bold">Elektronik</h4>
                    <p class="opacity-75">Gadget & Perangkat Pintar</p>
                    <a href="{{ route('products.index', ['category' => 'electronics']) }}" class="btn btn-white btn-sm rounded-pill px-3 mt-3 fw-bold">Jelajahi <i class="fas fa-chevron-right ms-1 small"></i></a>
                </div>
                <i class="fas fa-laptop position-absolute bottom-0 end-0 m-3 opacity-25 fa-5x"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white border-0 shadow-sm rounded-4 overflow-hidden h-100 p-4 position-relative">
                <div class="z-index-1">
                    <h4 class="fw-bold">Fashion</h4>
                    <p class="opacity-75">Pakaian & Aksesoris</p>
                    <a href="{{ route('products.index', ['category' => 'fashion']) }}" class="btn btn-white btn-sm rounded-pill px-3 mt-3 fw-bold">Jelajahi <i class="fas fa-chevron-right ms-1 small"></i></a>
                </div>
                <i class="fas fa-tshirt position-absolute bottom-0 end-0 m-3 opacity-25 fa-5x"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-dark border-0 shadow-sm rounded-4 overflow-hidden h-100 p-4 position-relative">
                <div class="z-index-1">
                    <h4 class="fw-bold">Kecantikan</h4>
                    <p class="opacity-75">Perawatan & Kosmetik</p>
                    <a href="{{ route('products.index', ['category' => 'beauty']) }}" class="btn btn-dark btn-sm rounded-pill px-3 mt-3 fw-bold text-white">Jelajahi <i class="fas fa-chevron-right ms-1 small"></i></a>
                </div>
                <i class="fas fa-magic position-absolute bottom-0 end-0 m-3 opacity-25 fa-5x"></i>
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div id="featured" class="row mb-5 g-4 shadow-sm py-5 px-3 rounded-4 bg-white border">
        <div class="col-12 mb-2">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Produk Unggulan</h2>
                    <p class="text-muted mb-0">Rekomendasi terbaik hanya untuk Anda</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill px-4">Lihat Semua <i class="fas fa-arrow-right ms-2"></i></a>
            </div>
        </div>
        @php
            // Prefer explicitly featured products, but fall back to in-stock active products
            $featuredProducts = \App\Models\Product::active()->featured()->take(4)->get();

            if ($featuredProducts->count() < 4) {
                $needed = 4 - $featuredProducts->count();
                $fallback = \App\Models\Product::active()->inStock()
                    ->when($featuredProducts->isNotEmpty(), function ($q) use ($featuredProducts) {
                        $q->whereNotIn('id', $featuredProducts->pluck('id'));
                    })
                    ->take($needed)
                    ->get();

                $featuredProducts = $featuredProducts->concat($fallback);
            }
        @endphp
        @forelse($featuredProducts as $product)
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 product-item">
                <div class="position-absolute p-2" style="top:0; right:0; z-index: 10;">
                    <span class="badge bg-danger rounded-pill shadow-sm">HOT</span>
                </div>
                <div class="overflow-hidden rounded-t-4" style="height: 250px;">
                    <img src="{{ $product->thumbnail_url }}" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="{{ $product->name }}">
                </div>
                <div class="card-body p-3">
                    <div class="text-muted small mb-1">{{ $product->brand ? $product->brand->name : 'No Brand' }}</div>
                    <h6 class="card-title text-truncate fw-bold mb-2">
                        <a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a>
                    </h6>
                    <div class="text-primary fw-bold fs-5 mb-3">Rp {{ number_format($product->current_price, 0, ',', '.') }}</div>
                    <div class="d-grid">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-bold">Detail Produk</a>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Produk unggulan belum tersedia.</p>
            </div>
        @endforelse
    </div>

    <!-- Features Section -->
    <div class="row g-4 py-5 mb-5 text-center">
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100 transition-all hover-up">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="fas fa-truck text-primary fa-2x"></i>
                </div>
                <h6 class="fw-bold">Gratis Ongkir</h6>
                <p class="text-muted small mb-0">Untuk pesanan di atas Rp 200rb</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100 transition-all hover-up">
                <div class="bg-success bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="fas fa-shield-alt text-success fa-2x"></i>
                </div>
                <h6 class="fw-bold">Pembayaran Aman</h6>
                <p class="text-muted small mb-0">100% perlindungan transaksi</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100 transition-all hover-up">
                <div class="bg-info bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="fas fa-undo text-info fa-2x"></i>
                </div>
                <h6 class="fw-bold">Mudah Dikembalikan</h6>
                <p class="text-muted small mb-0">Garansi 7 hari uang kembali</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 rounded-4 bg-white shadow-sm border h-100 transition-all hover-up">
                <div class="bg-warning bg-opacity-10 p-3 rounded-circle d-inline-block mb-3">
                    <i class="fas fa-headset text-warning fa-2x"></i>
                </div>
                <h6 class="fw-bold">Dukungan 24/7</h6>
                <p class="text-muted small mb-0">Kami siap membantu kapanpun</p>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-white {
        background: white;
        color: var(--primary-color);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-white:hover {
        background: #f8f9fa;
        transform: translateY(-2px);
    }
    .object-fit-cover { object-fit: cover; }
    .transition-all { transition: all 0.3s ease; }
    .product-item:hover img { transform: scale(1.08); }
    .hover-up:hover { transform: translateY(-10px); }
    .z-index-1 { position: relative; z-index: 1; }
</style>
@endsection