@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-5">
        <h2 class="fw-bold mb-1">Daftar Pesanan</h2>
        <p class="text-muted mb-0">Pantau pesanan yang masuk untuk produk Anda</p>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-bold">ID PESANAN</th>
                            <th class="py-3 text-muted small fw-bold">PELANGGAN</th>
                            <th class="py-3 text-muted small fw-bold">TANGGAL</th>
                            <th class="py-3 text-muted small fw-bold">TOTAL PESANAN</th>
                            <th class="py-3 text-muted small fw-bold">STATUS</th>
                            <th class="pe-4 py-3 text-end text-muted small fw-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 py-3 fw-bold">#{{ $order->order_number }}</td>
                            <td class="py-3">
                                <div class="fw-bold text-dark">{{ $order->user->name }}</div>
                                <div class="small text-muted">{{ $order->user->email }}</div>
                            </td>
                            <td class="py-3 small">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-3 fw-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="badge rounded-pill text-bg-{{ $order->status_color }} px-3">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="{{ route('vendor.orders.show', $order) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                    <i class="fas fa-eye text-primary me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-receipt fa-3x text-muted opacity-25 mb-3"></i>
                                <p class="text-muted">Belum ada pesanan masuk.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($orders->hasPages())
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
