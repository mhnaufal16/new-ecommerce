{{-- resources/views/cart/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Keranjang Belanja - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h2"><i class="fas fa-shopping-cart me-2"></i>Keranjang Belanja</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($cart && $cart->items->count() > 0)
    <div class="row">
        <!-- Cart Items List -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">Produk</th>
                                    <th class="py-3">Harga</th>
                                    <th class="py-3" style="width: 150px;">Jumlah</th>
                                    <th class="py-3">Total</th>
                                    <th class="py-3 text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                <tr>
                                    <td class="ps-4 py-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->thumbnail_url }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 class="rounded border" 
                                                 style="width: 70px; height: 70px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('products.show', $item->product) }}" class="text-dark text-decoration-none">
                                                        {{ $item->product->name }}
                                                    </a>
                                                </h6>
                                                @if($item->variant)
                                                <p class="text-muted small mb-0">
                                                    {{ $item->variant->attributes_text }}
                                                </p>
                                                @endif
                                                <small class="text-muted">SKU: {{ $item->variant ? $item->variant->sku : $item->product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-update-form">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <button type="button" class="btn btn-outline-secondary btn-minus">-</button>
                                                <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                                       value="{{ $item->quantity }}" min="1" max="{{ $item->available_stock }}">
                                                <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold">Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="{{ route('cart.remove', $item) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?')">
                                            @csrf
                                            <button type="submit" class="btn btn-link text-danger p-0">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Kosongkan keranjang belanja?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Kosongkan Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            Subtotal
                            <span>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                        </li>
                        @if($cart->discount_amount > 0)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent text-success">
                            Diskon ({{ $cart->coupon_code }})
                            <span>-Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                        </li>
                        @endif
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent">
                            Pajak
                            <span>Rp {{ number_format($cart->tax_amount, 0, ',', '.') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-top-0 pt-3">
                            <h5 class="mb-0">Total</h5>
                            <h5 class="mb-0 text-primary">Rp {{ number_format($cart->grand_total, 0, ',', '.') }}</h5>
                        </li>
                    </ul>

                    <!-- Coupon Area -->
                    <div class="mb-4">
                        @if(!$cart->coupon_code)
                        <form action="{{ route('cart.apply-coupon') }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="coupon_code" class="form-control" placeholder="Kode Kupon" required>
                                <button type="submit" class="btn btn-outline-primary">Terapkan</button>
                            </div>
                        </form>
                        @else
                        <div class="d-flex justify-content-between align-items-center bg-light p-2 rounded">
                            <span class="text-success small fw-medium">
                                <i class="fas fa-tag me-1"></i>Kupon <strong>{{ $cart->coupon_code }}</strong> Terpasang
                            </span>
                            <form action="{{ route('cart.remove-coupon') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link btn-sm text-danger p-0 text-decoration-none">Hapus</button>
                            </form>
                        </div>
                        @endif
                    </div>

                    <div class="d-grid">
                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg @if($cart->validateStock()) disabled @endif">
                            Checkout Sekarang<i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    </div>
                    
                    @if($cart->validateStock())
                    <div class="mt-3">
                        @foreach($cart->validateStock() as $error)
                        <div class="alert alert-danger py-2 small mb-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $error }}
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Guarantee Box -->
            <div class="card bg-light border-0">
                <div class="card-body p-4 text-center">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h6>Belanja Aman & Terpercaya</h6>
                    <p class="small text-muted mb-0">Setiap transaksi di {{ config('app.name') }} dijamin aman dengan proteksi enkripsi SSL.</p>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm py-5">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-shopping-basket fa-5x text-muted opacity-25"></i>
                    </div>
                    <h4>Keranjang Anda Kosong</h4>
                    <p class="text-muted mb-4">Sepertinya Anda belum menambahkan produk apapun ke keranjang belanja Anda.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Recommended Products Section -->
    <div class="row mt-5 pt-4">
        <div class="col-12">
            <h3 class="mb-4">Mungkin Anda Suka</h3>
        </div>
        @php
            $featured = \App\Models\Product::active()->inStock()->take(4)->get();
        @endphp
        @foreach($featured as $product)
        <div class="col-md-3 mb-4">
            <div class="card h-100 shadow-sm border-0 product-card">
                <img src="{{ $product->thumbnail_url }}" class="card-img-top" alt="{{ $product->name }}" style="height: 180px; object-fit: cover;">
                <div class="card-body">
                    <h6 class="card-title text-truncate">{{ $product->name }}</h6>
                    <p class="text-primary fw-bold mb-3">Rp {{ number_format($product->current_price, 0, ',', '.') }}</p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100">Detail Produk</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity Buttons
        const forms = document.querySelectorAll('.cart-update-form');
        
        forms.forEach(form => {
            const minusBtn = form.querySelector('.btn-minus');
            const plusBtn = form.querySelector('.btn-plus');
            const input = form.querySelector('.quantity-input');
            
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

            input.addEventListener('change', () => {
                if (parseInt(input.value) < 1) input.value = 1;
                if (parseInt(input.value) > parseInt(input.max)) input.value = input.max;
                form.submit();
            });
        });
    });
</script>
<style>
    .product-card {
        transition: transform 0.2s;
    }
    .product-card:hover {
        transform: translateY(-5px);
    }
    .breadcrumb-item + .breadcrumb-item::before {
        content: "\f105";
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        font-size: 0.8rem;
    }
</style>
@endpush
