@extends('layouts.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="h2">Checkout</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" id="checkoutForm">
        @csrf
        <div class="row">
            <!-- Left Side: Addresses & Options -->
            <div class="col-lg-8">
                <!-- 1. Shipping Address -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary"><i class="fas fa-map-marker-alt me-2"></i>1. Alamat Pengiriman</h5>
                        <a href="{{ route('profile.edit') }}#addresses" class="btn btn-sm btn-outline-primary">Kelola Alamat</a>
                    </div>
                    <div class="card-body">
                        @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <label class="address-selector w-100">
                                    <input type="radio" name="address_id" value="{{ $address->id }}" 
                                           {{ $loop->first ? 'checked' : '' }} class="d-none">
                                    <div class="card h-100 border-2">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <h6 class="mb-2">{{ $address->label }}</h6>
                                                @if($address->is_primary)
                                                <span class="badge bg-success small">Utama</span>
                                                @endif
                                            </div>
                                            <p class="small fw-bold mb-1">{{ $address->recipient_name }}</p>
                                            <p class="small text-muted mb-0">{{ $address->phone }}</p>
                                            <p class="small mb-0 mt-2">{{ $address->address }}</p>
                                            <p class="small mb-0">{{ $address->subdistrict }}, {{ $address->district }}</p>
                                            <p class="small mb-0">{{ $address->city_name }}, {{ $address->province_name }}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                            <p>Anda belum memiliki alamat tersimpan.</p>
                            <a href="{{ route('profile.edit') }}#addresses" class="btn btn-primary">Tambah Alamat Baru</a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- 2. Shipping Method -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="fas fa-truck me-2"></i>2. Metode Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($shippingMethods as $method)
                            <label class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                <input class="form-check-input me-3" type="radio" name="shipping_method_id" 
                                       value="{{ $method->id }}" {{ $loop->first ? 'checked' : '' }}>
                                <div class="d-flex align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $method->name }}</h6>
                                        <small class="text-muted">{{ $method->description }}</small>
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="fas fa-credit-card me-2"></i>3. Metode Pembayaran</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($paymentMethods as $method)
                            <div class="col-md-6 mb-3">
                                <label class="payment-selector w-100">
                                    <input type="radio" name="payment_method_id" value="{{ $method->id }}" 
                                           {{ $loop->first ? 'checked' : '' }} class="d-none">
                                    <div class="card h-100 border-2">
                                        <div class="card-body d-flex align-items-center">
                                            <div class="payment-icon me-3">
                                                @if($method->logo)
                                                <img src="{{ $method->logo }}" alt="{{ $method->name }}" style="height: 30px;">
                                                @else
                                                <i class="fas fa-wallet fa-2x text-muted"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $method->name }}</h6>
                                                <small class="text-muted">{{ $method->description }}</small>
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
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="fas fa-sticky-note me-2"></i>4. Catatan Pesanan (Opsional)</h5>
                    </div>
                    <div class="card-body">
                        <textarea name="notes" class="form-control" rows="3" placeholder="Contoh: Titip di satpam, warna cadangan biru, dll."></textarea>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Ringkasan Belanja</h5>
                    </div>
                    <div class="card-body">
                        <!-- Items Brief -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-2 text-muted">
                                <span>Total Item ({{ $cart->total_quantity }})</span>
                                <a href="{{ route('cart.index') }}" class="text-decoration-none">Lihat Detail</a>
                            </div>
                            @foreach($cart->items->take(3) as $item)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $item->product->thumbnail_url }}" alt="" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="ms-2 flex-grow-1">
                                    <p class="small mb-0 text-truncate" style="max-width: 200px;">{{ $item->product->name }}</p>
                                    <small class="text-muted">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</small>
                                </div>
                            </div>
                            @endforeach
                            @if($cart->items->count() > 3)
                            <p class="small text-center text-muted mt-2">+ {{ $cart->items->count() - 3 }} produk lainnya</p>
                            @endif
                        </div>

                        <hr>

                        <!-- Calculations -->
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-0 pt-0">
                                total Harga ({{ $cart->total_quantity }} Barang)
                                <span>Rp {{ number_format($cart->subtotal, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-0">
                                Total Ongkos Kirim
                                <span id="shippingFee">Rp 15.000</span>
                            </li>
                            @if($cart->discount_amount > 0)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-0 text-success">
                                Total Diskon
                                <span>-Rp {{ number_format($cart->discount_amount, 0, ',', '.') }}</span>
                            </li>
                            @endif
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-0">
                                Pajak
                                <span>Rp {{ number_format($cart->tax_amount, 0, ',', '.') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent border-top mt-2 pt-3">
                                <h5 class="mb-0">Total Tagihan</h5>
                                <h5 class="mb-0 text-primary" id="totalBill">Rp {{ number_format($cart->grand_total + 15000, 0, ',', '.') }}</h5>
                            </li>
                        </ul>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold" {{ $addresses->count() == 0 ? 'disabled' : '' }}>
                                Bayar Sekarang
                            </button>
                        </div>
                        
                        <p class="small text-muted text-center mb-0">
                            Dengan membayar, Anda menyetujui <a href="#">Syarat & Ketentuan</a> yang berlaku.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .address-selector input:checked + .card,
    .payment-selector input:checked + .card {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05);
    }
    .address-selector .card,
    .payment-selector .card {
        cursor: pointer;
        transition: all 0.2s;
    }
    .address-selector .card:hover,
    .payment-selector .card:hover {
        border-color: #adb5bd;
    }
    .payment-icon {
        width: 60px;
        text-align: center;
    }
</style>
@endpush

@push('scripts')
<script>
    // Simple logic to simulate dynamic totals
    document.addEventListener('DOMContentLoaded', function() {
        const shippingInputs = document.querySelectorAll('input[name="shipping_method_id"]');
        const shippingFeeEl = document.getElementById('shippingFee');
        const totalBillEl = document.getElementById('totalBill');
        const baseTotal = {{ $cart->grand_total }};

        shippingInputs.forEach(input => {
            input.addEventListener('change', function() {
                // In real app, you'd fetch shipping cost via AJAX or had it in data attributes
                let cost = 15000;
                shippingFeeEl.textContent = 'Rp ' + cost.toLocaleString('id-ID');
                totalBillEl.textContent = 'Rp ' + (baseTotal + cost).toLocaleString('id-ID');
            });
        });
    });
</script>
@endpush
