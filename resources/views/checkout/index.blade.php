@extends('layouts.app')

@section('title', 'Finalisasi Pesanan - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-5 fw-bold mb-2">Finalisasi Pesanan</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb justify-content-center bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none text-muted">Keranjang</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary" aria-current="page">Checkout</li>
                    <li class="breadcrumb-item text-muted">Pembayaran</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row g-4">
            <!-- Left Side: Addresses & Options -->
            <div class="col-lg-8">
                <!-- 1. Shipping Address -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-map-marker-alt me-2 text-primary"></i>1. Alamat Pengiriman</h5>
                        <a href="{{ route('profile.edit') }}#addresses" class="btn btn-link text-primary text-decoration-none fw-bold small">Kelola Alamat</a>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0">
                        @if($addresses->count() > 0)
                        <div class="row g-3">
                            @foreach($addresses as $address)
                            <div class="col-md-6">
                                <label class="address-selector w-100 h-100">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" 
                                           {{ $loop->first ? 'checked' : '' }} class="d-none">
                                    <div class="card h-100 border-2 rounded-4 transition-all">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="mb-0 fw-bold">{{ $address->label }}</h6>
                                                @if($address->is_primary)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Utama</span>
                                                @endif
                                            </div>
                                            <div class="small fw-bold mb-1">{{ $address->recipient_name }}</div>
                                            <div class="small text-muted mb-2">{{ $address->phone }}</div>
                                            <div class="small mb-0 opacity-75">{{ $address->address }}</div>
                                            <div class="small mb-0 opacity-75">{{ $address->subdistrict }}, {{ $address->district }}</div>
                                            <div class="small mb-0 opacity-75">{{ $address->city_name }}, {{ $address->province_name }}</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5 bg-light rounded-4">
                            <div class="bg-white rounded-circle d-inline-block p-4 mb-3 shadow-sm">
                                <i class="fas fa-map-marked-alt fa-3x text-muted opacity-50"></i>
                            </div>
                            <p class="fw-bold mb-1">Alamat Belum Tersedia</p>
                            <p class="text-muted small mb-4">Silakan tambah alamat pengiriman untuk melanjutkan.</p>
                            <a href="{{ route('profile.edit') }}#addresses" class="btn btn-primary rounded-pill px-4">Tambah Alamat Baru</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Shipping Method -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-4 px-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-truck me-2 text-primary"></i>2. Metode Pengiriman</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0">
                        <div class="list-group rounded-4 overflow-hidden border">
                            @foreach($shippingMethods as $method)
                            <label class="list-group-item list-group-item-action d-flex align-items-center py-3 border-0 border-bottom last-border-0">
                                <input class="form-check-input me-3 shadow-none" type="radio" name="shipping_method_id" 
                                       value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }}>
                                <div class="d-flex align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold">{{ $method->name }}</h6>
                                        <p class="small text-muted mb-0">{{ $method->description }}</p>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-primary">Est. Rp 15.000</span>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 3. Payment Method -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-4 px-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-primary"></i>3. Metode Pembayaran</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0">
                        <div class="row g-3">
                            @foreach($paymentMethods as $method)
                            <div class="col-md-6">
                                <label class="payment-selector w-100 h-100">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" 
                                           {{ $loop->first ? 'checked' : '' }} class="d-none">
                                    <div class="card h-100 border-2 rounded-4 transition-all">
                                        <div class="card-body d-flex align-items-center p-3">
                                            <div class="payment-icon me-3 bg-light rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 60px; height: 50px;">
                                                @if($method->logo)
                                                <img src="{{ $method->logo }}" alt="{{ $method->name }}" style="max-width: 100%; max-height: 100%;">
                                                @else
                                                <i class="fas fa-wallet text-muted"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $method->name }}</h6>
                                                <p class="x-small text-muted mb-0">{{ $method->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 4. Notes -->
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-4 px-4 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-sticky-note me-2 text-primary"></i>4. Catatan Pesanan (Opsional)</h5>
                    </div>
                    <div class="card-body px-4 pb-4 pt-0">
                        <textarea name="notes" class="form-control rounded-4 border-light bg-light p-3 shadow-none" rows="3" placeholder="Contoh: Titip di satpam, warna cadangan biru, dll."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-lg-4">
                <div class="sticky-top" style="top: 2rem;">
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white py-4 px-4 border-0">
                            <h5 class="mb-0 fw-bold">Ringkasan Belanja</h5>
                        </div>
                        <div class="card-body px-4 pb-4 pt-0">
                            <!-- Items Mini List -->
                            <div class="mb-4 bg-light rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="small fw-bold text-muted">ITEM PESANAN ({{ $cart->total_quantity }})</span>
                                    <a href="{{ route('cart.index') }}" class="small text-primary text-decoration-none">Edit</a>
                                </div>
                                <div class="mini-cart-items">
                                    @foreach($cart->items->take(3) as $item)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="position-relative">
                                            <img src="{{ $item->product->thumbnail_url }}" alt="" class="rounded-3 border" style="width: 45px; height: 45px; object-fit: cover;">
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-dark border border-light" style="font-size: 0.6rem;">{{ $item->quantity }}</span>
                                        </div>
                                        <div class="ms-3 flex-grow-1 overflow-hidden">
                                            <p class="small fw-bold mb-0 text-truncate">{{ $item->product->name }}</p>
                                            <p class="x-small text-muted mb-0">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                    @if($cart->items->count() > 3)
                                    <p class="x-small text-center text-muted mb-0 mt-2">+ {{ $cart->items->count() - 3 }} produk lainnya</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Billing Calculations -->
                            <div class="billing-details">
                                <div class="d-flex justify-content-between mb-2 text-muted small">
                                    <span>Subtotal Produk</span>
                                    <span>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2 text-muted small">
                                    <span>Ongkos Kirim</span>
                                    <span id="shippingFee">Rp 15.000</span>
                                </div>
                                @if($cart->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success small">
                                    <span>Diskon ({{ $cart->coupon_code }})</span>
                                    <span>-Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="d-flex justify-content-between mb-3 text-muted small">
                                    <span>Pajak (Estimasi)</span>
                                    <span>Rp {{ number_format($cart->tax_amount, 0, ',', '.') }}</span>
                                </div>
                                
                                <hr class="border-dashed my-3">
                                
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h6 class="fw-bold mb-0">Total Pembayaran</h6>
                                    <h4 class="fw-bold mb-0 text-primary" id="totalBill">Rp {{ number_format($cart->grand_total + 15000, 0, ',', '.') }}</h4>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow-lg" {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                        Lanjut ke Pembayaran <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center p-3">
                        <div class="d-flex justify-content-center gap-3 opacity-50 mb-3">
                            <i class="fas fa-shield-check fa-2x text-primary"></i>
                            <div class="text-start">
                                <h6 class="mb-0 fw-bold x-small">SECURE PAYMENT</h6>
                                <p class="x-small text-muted mb-0">Your data is encrypted</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .x-small { font-size: 0.75rem; }
    .last-border-0:last-child { border-bottom: 0 !important; }
    .border-dashed { border-style: dashed !important; border-color: #dee2e6 !important; }
    
    .address-selector input:checked + .card,
    .payment-selector input:checked + .card {
        border-color: var(--primary-color) !important;
        background-color: rgba(13, 110, 253, 0.05);
        box-shadow: 0 0 0 1px var(--primary-color);
    }
    
    .address-selector .card,
    .payment-selector .card {
        cursor: pointer;
        border-color: #f1f3f5;
    }
    
    .address-selector .card:hover,
    .payment-selector .card:hover {
        border-color: #dee2e6;
    }
    
    .transition-all { transition: all 0.2s ease-in-out; }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shippingInputs = document.querySelectorAll('input[name="shipping_method_id"]');
        const shippingFeeEl = document.getElementById('shippingFee');
        const totalBillEl = document.getElementById('totalBill');
        const baseTotal = {{ $cart->grand_total }};

        shippingInputs.forEach(input => {
            input.addEventListener('change', function() {
                // Mock calculation, in real app this would come from backend/data-attrs
                let cost = 15000; 
                shippingFeeEl.textContent = 'Rp ' + cost.toLocaleString('id-ID');
                totalBillEl.textContent = 'Rp ' + (baseTotal + cost).toLocaleString('id-ID');
            });
        });
        
        // Form feedback
        document.getElementById('checkoutForm').addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Memproses...';
        });
    });
</script>
@endpush
