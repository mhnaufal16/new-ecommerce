{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 me-3">
                    <i class="fas fa-shopping-cart fa-2x text-primary"></i>
                </div>
                <div>
                    <h1 class="h2 fw-bold mb-0">Keranjang Belanja</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    @if($cart && $cart->items->count() > 0)
    <div class="row g-4">
        <!-- Cart Items List -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0 fw-bold">Daftar Produk ({{ $cart->total_quantity }})</h5>
                        </div>
                        <div class="col-auto">
                            <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan keranjang belanja?')">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 small">
                                    <i class="fas fa-trash-alt me-1"></i>Kosongkan Keranjang
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4 py-3 border-0">Produk</th>
                                    <th class="py-3 border-0 text-center">Harga</th>
                                    <th class="py-3 border-0 text-center">Jumlah</th>
                                    <th class="py-3 border-0 text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($cart->items as $item)
                                <tr>
                                    <td class="ps-4 py-4 border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative">
                                                <img src="{{ $item->product->thumbnail_url }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded-3 shadow-sm border" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                                <form action="{{ route('cart.remove', $item) }}" method="POST" class="position-absolute translate-middle" style="top: 0; left: 0;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle p-0" style="width: 24px; height: 24px;" title="Hapus">
                                                        <i class="fas fa-times" style="font-size: 10px;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="ms-3">
                                                <h6 class="mb-1 fw-bold">
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none hover-primary">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h6>
                                                @if($item->variant)
                                                <div class="mb-1">
                                                    <span class="badge bg-light text-dark fw-normal border">{{ $item->variant->attributes_text }}</span>
                                                </div>
                                                @endif
                                                <div class="text-muted small">SKU: {{ $item->variant ? $item->variant->sku : $item->product->sku }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center border-0">
                                        <span class="text-muted small d-block d-md-none">Harga</span>
                                        <span class="fw-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="border-0">
                                        <div class="d-flex justify-content-center">
                                            <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-update-form">
                                                @csrf
                                                <div class="qty-selector d-flex align-items-center bg-light rounded-pill p-1">
                                                    <button type="button" class="btn btn-qty btn-minus rounded-circle">
                                                        <i class="fas fa-minus small"></i>
                                                    </button>
                                                    <input type="number" name="quantity" class="form-control text-center quantity-input bg-transparent border-0 fw-bold" 
                                                           value="{{ $item->quantity }}" min="1" max="{{ $item->available_stock }}" 
                                                           style="width: 45px; pointer-events: none;">
                                                    <button type="button" class="btn btn-qty btn-plus rounded-circle">
                                                        <i class="fas fa-plus small"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="text-end pe-4 border-0">
                                        <span class="text-muted small d-block d-md-none">Total</span>
                                        <span class="text-primary fw-bold fs-5">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4 py-2 text-muted fw-medium shadow-sm border">
                <i class="fas fa-long-arrow-alt-left me-2"></i>Lanjut Belanja
            </a>
        </div>

        <!-- Order Summary Side -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Subtotal ({{ $cart->total_quantity }} item)</span>
                            <span>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($cart->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-3 text-success">
                            <span>Diskon ({{ $cart->coupon_code }})</span>
                            <span>-Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mb-4 text-muted">
                            <span>Pajak (Estimasi)</span>
                            <span>Rp {{ number_format($cart->tax_amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="border-dashed mb-4">
                        
                        <div class="d-flex justify-content-between mb-4 align-items-end">
                            <span class="h6 fw-bold mb-0 text-dark">Total Keseluruhan</span>
                            <span class="h4 fw-bold mb-0 text-primary">Rp {{ number_format($cart->grand_total, 0, ',', '.') }}</span>
                        </div>

                        <!-- Coupon Input -->
                        <div class="mb-4">
                            @if(!$cart->coupon_code)
                            <form action="{{ route('cart.apply-coupon') }}" method="POST">
                                @csrf
                                <div class="input-group">
                                    <input type="text" name="coupon_code" class="form-control bg-light border-0 px-3 py-2" placeholder="Kode Kupon" required>
                                    <button type="submit" class="btn btn-primary px-3">Pasang</button>
                                </div>
                            </form>
                            @else
                            <div class="d-flex justify-content-between align-items-center bg-success bg-opacity-10 p-3 rounded-3 border border-success border-opacity-25">
                                <div>
                                    <i class="fas fa-tag text-success me-2"></i>
                                    <span class="text-success small fw-bold">Kupon {{ $cart->coupon_code }} Aktif</span>
                                </div>
                                <form action="{{ route('cart.remove-coupon') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-link btn-sm text-danger p-0 text-decoration-none fw-bold">Hapus</button>
                                </form>
                            </div>
                            @endif
                        </div>

                        <div class="d-grid mb-3">
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-lg @if($cart->validateStock()) disabled @endif">
                                Proceed to Checkout<i class="fas fa-arrow-right ms-2 pulse-icon"></i>
                            </a>
                        </div>
                        
                        @if($cart->validateStock())
                        <div class="mt-3">
                            @foreach($cart->validateStock() as $error)
                            <div class="alert alert-danger py-2 small mb-2 border-0 rounded-3 bg-opacity-75">
                                <i class="fas fa-exclamation-triangle me-2"></i>{{ $error }}
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Methods Info -->
                <div class="card border-0 rounded-4 shadow-sm bg-light">
                    <div class="card-body p-4 text-center">
                        <h6 class="fw-bold mb-3"><i class="fas fa-shield-check text-primary me-2"></i>Jaminan Keamanan</h6>
                        <div class="d-flex justify-content-center gap-3 mb-3">
                            <i class="fab fa-cc-visa fa-2x text-muted opacity-50"></i>
                            <i class="fab fa-cc-mastercard fa-2x text-muted opacity-50"></i>
                            <i class="fas fa-wallet fa-2x text-muted opacity-50"></i>
                            <i class="fas fa-university fa-2x text-muted opacity-50"></i>
                        </div>
                        <p class="small text-muted mb-0">Transaksi Anda dilindungi dengan enkripsi tingkat bank yang aman.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row min-vh-50 align-items-center text-center">
        <div class="col-12 py-5">
            <div class="mb-5 position-relative">
                <div class="cart-empty-circle mx-auto">
                    <i class="fas fa-shopping-basket fa-4x text-primary opacity-25"></i>
                </div>
            </div>
            <h2 class="fw-bold">Yah, Keranjangnya Kosong</h2>
            <p class="text-muted mb-5 mx-auto" style="max-width: 400px;">Sepertinya Anda belum menemukan produk yang cocok hari ini. Yuk, intip koleksi terbaru kami!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg">
                <i class="fas fa-search me-2"></i>Jelajahi Produk
            </a>
        </div>
    </div>
    @endif

    <!-- Recommended Products Section -->
    <div class="row mt-5 pt-5 g-4 shadow-sm">
        <div class="col-12 mb-2">
            <div class="d-flex justify-content-between align-items-end">
                <h3 class="fw-bold mb-0">Rekomendasi Spesial</h3>
                <a href="{{ route('products.index') }}" class="text-primary text-decoration-none fw-bold">Lihat Semua <i class="fas fa-chevron-right ms-1 small"></i></a>
            </div>
        </div>
        @php
            $featured = \App\Models\Product::active()->inStock()->take(4)->get();
        @endphp
        @foreach($featured as $product)
        <div class="col-6 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 product-item">
                <div class="position-absolute p-2" style="top:0; right:0;">
                    <span class="badge bg-white shadow-sm text-primary rounded-pill">In Stock</span>
                </div>
                <img src="{{ $product->thumbnail_url }}" class="card-img-top rounded-t-4" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                <div class="card-body p-3">
                    <h6 class="card-title text-truncate fw-bold mb-2">{{ $product->name }}</h6>
                    <div class="text-primary fw-bold fs-5 mb-3">Rp {{ number_format($product->current_price, 0, ',', '.') }}</div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm rounded-pill w-100 py-2">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('styles')
<style>
    .rounded-4 { border-radius: 1.25rem !important; }
    .bg-light { background-color: #f8f9fa !important; }
    
    .hover-primary:hover { color: #0d6efd !important; }
    
    .qty-selector {
        width: fit-content;
        min-width: 110px;
    }
    
    .btn-qty {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #dee2e6;
        background: white;
        transition: all 0.2s;
        padding: 0;
    }
    
    .btn-qty:hover {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .border-dashed { border-style: dashed !important; }
    
    .pulse-icon {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { transform: translateX(0); }
        50% { transform: translateX(5px); }
        100% { transform: translateX(0); }
    }
    
    .cart-empty-circle {
        width: 150px;
        height: 150px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
    }
    
    .product-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .product-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity Buttons Logic
        document.querySelectorAll('.qty-selector').forEach(selector => {
            const minusBtn = selector.querySelector('.btn-minus');
            const plusBtn = selector.querySelector('.btn-plus');
            const input = selector.querySelector('.quantity-input');
            const form = selector.closest('form');
            
            minusBtn.addEventListener('click', () => {
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    form.submit();
                }
            });
            
            plusBtn.addEventListener('click', () => {
                if (parseInt(input.value) < parseInt(input.max)) {
                    input.value = parseInt(input.value) + 1;
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
