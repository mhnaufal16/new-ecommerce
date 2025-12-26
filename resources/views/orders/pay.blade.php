@extends('layouts.app')

@section('title', 'Pembayaran Pesanan #' . $order->order_number . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <nav aria-label="breadcrumb" class="d-inline-block">
                    <ol class="breadcrumb bg-transparent p-0 mb-3 justify-content-center">
                        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('orders.show', $order) }}" class="text-decoration-none">{{ $order->order_number }}</a></li>
                        <li class="breadcrumb-item active fw-bold" aria-current="page">Pembayaran</li>
                    </ol>
                </nav>
                <h2 class="fw-bold mb-2">Selesaikan Pembayaran</h2>
                <p class="text-muted">Silakan lakukan pembayaran sebesar <span class="fw-bold text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></p>
            </div>

            <!-- Payment Method Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="mb-4">
                        @php
                            $logoUrl = null;
                            if ($paymentMethod && $paymentMethod->logo) {
                                if (filter_var($paymentMethod->logo, FILTER_VALIDATE_URL)) {
                                    $logoUrl = $paymentMethod->logo;
                                } else {
                                    $logoUrl = asset('storage/' . ltrim($paymentMethod->logo, '/'));
                                }
                            }
                        @endphp
                        @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ $payment->payment_method }}" style="max-height: 60px;">
                        @else
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-wallet fa-2x text-primary"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-uppercase">{{ $payment->payment_method_label }}</h4>
                        @endif
                    </div>

                    @if($payment->payment_method === 'bank_transfer')
                        <div class="alert alert-primary border-0 rounded-4 py-3 px-4 mb-4">
                            <h6 class="fw-bold mb-3"><i class="fas fa-university me-2"></i>Instruksi Transfer Bank</h6>
                            <div class="text-start small">
                                <p class="mb-2">1. Lakukan transfer ke salah satu rekening berikut:</p>
                                <ul class="mb-3">
                                    <li><strong>BCA:</strong> 1234567890 a/n Toko Ecommerce</li>
                                    <li><strong>Mandiri:</strong> 0987654321 a/n Toko Ecommerce</li>
                                </ul>
                                <p class="mb-0">2. Pastikan nominal transfer sesuai sampai 3 digit terakhir jika ada.</p>
                            </div>
                        </div>
                    @elseif($payment->payment_method === 'qris')
                        <div class="mb-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=example_qris_payload" alt="QRIS" class="img-fluid border rounded-4 p-3 shadow-sm">
                            <p class="text-muted small mt-3 italic">Scan kode QR di atas menggunakan aplikasi e-wallet Anda.</p>
                        </div>
                    @else
                        <div class="alert alert-info border-0 rounded-4 py-3 px-4 mb-4">
                            <p class="mb-0 small"><i class="fas fa-info-circle me-2"></i>Ikuti petunjuk pembayaran {{ $payment->payment_method_label }} pada aplikasi Anda.</p>
                        </div>
                    @endif

                    <div class="bg-light p-4 rounded-4 mb-4">
                        <p class="small text-muted mb-2 text-uppercase fw-bold ls-1">Batas Waktu Pembayaran</p>
                        <h4 class="fw-bold mb-0 text-danger" id="countdown">23:59:59</h4>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary w-100 rounded-pill py-3 fw-bold">Lihat Detail Pesanan</a>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary w-100 rounded-pill py-3 fw-bold shadow-sm" onclick="alert('Ini adalah simulasi. Di sistem nyata, ini akan mengarahkan ke gateway pembayaran atau mengonfirmasi pembayaran.')">Konfirmasi Pembayaran</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Need Help -->
            <div class="text-center mt-4">
                <p class="text-muted">Butuh bantuan? <a href="#" class="text-primary text-decoration-none fw-bold">Hubungi Customer Service</a></p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Simple countdown timer simulation
    let time = 24 * 60 * 60;
    const display = document.querySelector('#countdown');
    
    setInterval(() => {
        time--;
        const hours = Math.floor(time / 3600);
        const minutes = Math.floor((time % 3600) / 60);
        const seconds = time % 60;
        display.textContent = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }, 1000);
</script>
@endpush
@endsection
