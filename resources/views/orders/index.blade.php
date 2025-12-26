@extends('layouts.user')

@section('title', 'Pesanan Saya - ' . config('app.name'))

@section('user_content')
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1">Pesanan Saya</h2>
            <p class="text-muted mb-0">Pantau status dan riwayat pesanan Anda di sini.</p>
        </div>
    </div>

    @if($orders->count() > 0)
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0">
            <h5 class="mb-0 fw-bold">Daftar Semua Pesanan</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-admin-table mb-0">
                    <thead class="bg-light text-muted x-small">
                        <tr>
                            <th class="ps-4">NOMOR PESANAN</th>
                            <th>TANGGAL</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                            <th>PEMBAYARAN</th>
                            <th class="pe-4 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $order->created_at->format('d M Y, H:i') }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                            <td>
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
                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ strtoupper($order->payment_status) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($order->status !== 'completed' && $order->shipping_status === 'shipped')
                                    <form action="{{ route('orders.mark-received', $order) }}" method="POST" onsubmit="return confirm('Konfirmasi pesanan telah diterima?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold small">
                                            <i class="fas fa-check me-1"></i> Diterima
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Lihat Detail">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($orders->links())
            <div class="card-footer bg-white py-4 px-4 border-0 border-top">
                {{ $orders->links() }}
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="card border-0 shadow-premium rounded-4 overflow-hidden py-5">
        <div class="card-body text-center py-5">
            <div class="bg-light rounded-circle d-inline-block p-4 mb-4">
                <i class="fas fa-shopping-bag fa-4x text-muted opacity-25"></i>
            </div>
            <h4 class="fw-bold">Belum Ada Pesanan</h4>
            <p class="text-muted mb-4">Sepertinya Anda belum pernah berbelanja di toko kami.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">Mulai Belanja Sekarang</a>
        </div>
    </div>
    @endif
@endsection

@push('styles')
<style>
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
    .x-small { font-size: 0.75rem; letter-spacing: 0.5px; }
    .custom-admin-table thead th {
        font-weight: 700;
        letter-spacing: 1px;
        border-bottom: 0;
        padding-top: 20px;
        padding-bottom: 15px;
    }
    .custom-admin-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom-color: #f8fafc;
    }
    .btn-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; padding: 0; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>
@endpush
