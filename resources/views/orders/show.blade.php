@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Header -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Pesanan Saya</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">{{ $order->order_number }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold mb-0">Pesanan {{ $order->order_number }}</h2>
            <p class="text-muted small mb-0">Ditempatkan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            @php
                $statusColors = [
                    'pending' => 'warning',
                    'processing' => 'info',
                    'shipped' => 'primary',
                    'delivered' => 'success',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    'on_hold' => 'secondary'
                ];
                $color = $statusColors[$order->status] ?? 'secondary';
            @endphp
            <div class="d-inline-flex align-items-center bg-white border rounded-pill px-4 py-2 shadow-sm">
                <span class="small fw-bold text-muted me-2">Status:</span>
                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2 fw-bold">
                    {{ strtoupper($order->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content: Items & Timeline -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Item Pesanan ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="list-group list-group-flush">
                        @foreach($order->items as $item)
                        <div class="list-group-item px-0 py-4 border-light">
                            <div class="row align-items-center">
                                <div class="col-3 col-md-2">
                                    <img src="{{ $item->product ? $item->product->thumbnail_url : 'https://via.placeholder.com/100' }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="img-fluid rounded-3 border">
                                </div>
                                <div class="col-9 col-md-6">
                                    <h6 class="fw-bold mb-1">{{ $item->product_name }}</h6>
                                    @if($item->sku)
                                    <p class="x-small text-muted mb-1">SKU: {{ $item->sku }}</p>
                                    @endif
                                    @if($item->variant_attributes)
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @foreach($item->variant_attributes as $attr)
                                            <span class="badge bg-light text-dark fw-normal rounded-pill border py-1 px-3">
                                                <span class="text-muted">{{ $attr['attribute'] }}:</span> {{ $attr['value'] }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="text-muted small mb-1">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                    <div class="fw-bold text-primary">Rp {{ number_format($item->row_total, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Informasi Pengiriman</h5>
                    @php
                        $shipColor = $statusColors[$order->shipping_status] ?? 'info';
                    @endphp
                    <span class="badge bg-{{ $shipColor }} bg-opacity-10 text-{{ $shipColor }} rounded-pill px-3">
                        <i class="fas fa-truck me-1"></i> {{ strtoupper($order->shipping_status) }}
                    </span>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="small text-muted mb-2 text-uppercase fw-bold ls-1">Alamat Penerima</label>
                            @php
                                $shippingAddress = $order->addresses->where('address_type', 'shipping')->first();
                            @endphp
                            @if($shippingAddress)
                            <div class="fw-bold fs-5 mb-1">{{ $shippingAddress->recipient_name }}</div>
                            <div class="text-muted small mb-3">{{ $shippingAddress->phone }}</div>
                            <div class="text-secondary small">
                                {{ $shippingAddress->address }}<br>
                                {{ $shippingAddress->subdistrict }}, {{ $shippingAddress->district }}<br>
                                {{ $shippingAddress->city_name }}, {{ $shippingAddress->province_name }} {{ $shippingAddress->postal_code }}
                            </div>
                            @else
                            <p class="text-muted small">Alamat pengiriman tidak tersedia.</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted mb-2 text-uppercase fw-bold ls-1">Metode Pengiriman</label>
                            <div class="d-flex align-items-start bg-light p-3 rounded-4">
                                <div class="bg-white rounded-circle p-2 me-3 shadow-sm">
                                    <i class="fas fa-shipping-fast text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-bold mb-0">Regular Shipping</div>
                                    <div class="text-muted x-small">Estimasi tiba 2-4 hari kerja</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Summary & Payment -->
        <div class="col-lg-4">
            <!-- Payment Summary -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Ringkasan Pembayaran</h5>
                </div>
                <div class="card-body px-4 pb-0 pt-0">
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-muted small">
                        <span>Ongkos Kirim</span>
                        <span>Rp {{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success small">
                        <span>Diskon</span>
                        <span>-Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-3 text-muted small">
                        <span>Pajak (Estimasi)</span>
                        <span>Rp {{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                    </div>
                    
                    <hr class="border-light my-3">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0">Total Tagihan</h6>
                        <h4 class="fw-bold mb-0 text-primary">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <!-- Payment Status Section -->
                <div class="bg-light p-4 text-center mt-2">
                    <label class="small text-muted mb-2 text-uppercase fw-bold ls-1 d-block">Status Pembayaran</label>
                    @php
                        $paymentColors = [
                            'pending' => 'warning',
                            'paid' => 'success',
                            'partially_paid' => 'info',
                            'refunded' => 'secondary',
                            'failed' => 'danger'
                        ];
                        $pColor = $paymentColors[$order->payment_status] ?? 'secondary';
                    @endphp
                    <div class="fs-4 fw-bold text-{{ $pColor }} mb-3">{{ strtoupper($order->payment_status) }}</div>
                    
                    @php
                        $payment = $order->payments()->latest()->first();
                    @endphp

                    @if($order->payment_status === 'pending')
                    <div class="alert alert-warning border-0 small mb-0 rounded-4 shadow-sm text-start">
                        <i class="fas fa-info-circle me-2"></i> Silakan selesaikan pembayaran agar pesanan Anda dapat segera kami proses.
                    </div>
                    <a href="{{ route('orders.pay', $order) }}" class="btn btn-primary w-100 rounded-pill py-3 fw-bold mt-4 shadow-sm">Bayar Sekarang</a>
                    
                    @elseif($order->payment_status === 'waiting_verification')
                    <div class="alert alert-info border-0 small mb-0 rounded-4 shadow-sm text-start">
                        <i class="fas fa-clock me-2"></i> Bukti transfer sudah kami terima dan sedang dalam antrean verifikasi Admin. Mohon ditunggu ya!
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-light w-100 rounded-pill py-2 border small" type="button" data-bs-toggle="collapse" data-bs-target="#viewProof">
                            <i class="fas fa-eye me-1"></i> Lihat Bukti Saya
                        </button>
                        <div class="collapse mt-2" id="viewProof">
                            <img src="{{ asset('storage/' . ($payment->proof_of_payment ?? '')) }}" class="img-fluid rounded-3 border">
                        </div>
                    </div>

                    @elseif($payment && $payment->verification_status === 'rejected')
                    <div class="alert alert-danger border-0 small mb-0 rounded-4 shadow-sm text-start">
                        <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2"></i>Pembayaran Ditolak</div>
                        <p class="mb-0">Alasan: {{ $payment->rejection_reason }}</p>
                    </div>
                    <a href="{{ route('orders.pay', $order) }}" class="btn btn-outline-danger w-100 rounded-pill py-3 fw-bold mt-4 shadow-sm">Re-upload Bukti Baru</a>

                    @elseif($order->payment_status === 'paid')
                    <div class="alert alert-success border-0 small mb-0 rounded-4 shadow-sm text-start">
                        <i class="fas fa-check-circle me-2"></i> Pembayaran berhasil diverifikasi pada {{ $order->updated_at->format('d M Y, H:i') }}.
                    </div>

                        @if($order->status !== 'completed' && $order->shipping_status === 'shipped')
                        <form action="{{ route('orders.mark-received', $order) }}" method="POST" onsubmit="return confirm('Konfirmasi pesanan telah diterima?')" class="mt-4">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm">
                                <i class="fas fa-box-open me-2"></i> Konfirmasi Pesanan Diterima
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="mb-0 fw-bold">Catatan Pesanan</h5>
                </div>
                <div class="card-body px-4 pb-4 pt-0">
                    <p class="text-secondary small italic mb-0">"{{ $order->notes }}"</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .x-small { font-size: 0.75rem; }
    .ls-1 { letter-spacing: 1px; }
    .ls-2 { letter-spacing: 2px; }
    .ls-3 { letter-spacing: 3px; }
    .italic { font-style: italic; }
</style>
@endpush
