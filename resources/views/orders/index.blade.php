@extends('layouts.app')

@section('title', 'Pesanan Saya - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold mb-0">Pesanan Saya</h2>
            <p class="text-muted">Pantau status dan riwayat pesanan Anda di sini.</p>
        </div>
    </div>

    @if($orders->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="px-4 py-3 border-0">No. Pesanan</th>
                                <th class="py-3 border-0">Tanggal</th>
                                <th class="py-3 border-0">Total</th>
                                <th class="py-3 border-0">Status Pesanan</th>
                                <th class="py-3 border-0">Status Pembayaran</th>
                                <th class="px-4 py-3 border-0 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td class="px-4 py-3 border-0">
                                    <span class="fw-bold text-primary">{{ $order->order_number }}</span>
                                </td>
                                <td class="py-3 border-0 text-muted small">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 border-0 fw-bold">
                                    Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                </td>
                                <td class="py-3 border-0">
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
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-3 border-0">
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
                                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill px-3">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 border-0 text-end">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white py-3 px-4 border-0">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12 text-center py-5">
            <div class="bg-light rounded-circle d-inline-block p-4 mb-4">
                <i class="fas fa-shopping-bag fa-4x text-muted opacity-50"></i>
            </div>
            <h4 class="fw-bold">Belum Ada Pesanan</h4>
            <p class="text-muted mb-4">Sepertinya Anda belum pernah berbelanja di toko kami.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">Mulai Belanja Sekarang</a>
        </div>
    </div>
    @endif
</div>
@endsection
