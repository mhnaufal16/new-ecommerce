{{-- resources/views/dashboard/customer.blade.php --}}
@extends('layouts.user')

@section('title', 'Dashboard Saya - ' . config('app.name'))

@section('user_content')
    <!-- Top Welcome Bar -->
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1">Halo, {{ explode(' ', $user->name)[0] }}! <span class="wave-emoji">👋</span></h2>
            <p class="text-muted mb-0">Selamat datang kembali di dashboard Anda. Berikut adalah ringkasan aktivitas belanja Anda.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <div class="bg-white d-inline-flex p-2 rounded-pill shadow-sm border px-3 align-items-center">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                <span class="small fw-bold">{{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Grid (Aligned with Admin Style) -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: white; border-left: 5px solid #0d6efd !important;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted small fw-bold mb-0">TOTAL PESANAN</h6>
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $total_orders }}</h3>
                    <div class="mt-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary x-small fw-bold">Riwayat Belanja</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: white; border-left: 5px solid #198754 !important;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted small fw-bold mb-0">PESANAN SELESAI</h6>
                        <div class="bg-success bg-opacity-10 text-success rounded-3 p-2">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $completed_orders }}</h3>
                    <div class="mt-2">
                        <span class="badge bg-success bg-opacity-10 text-success x-small fw-bold">Berhasil Diterima</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: white; border-left: 5px solid #ffc107 !important;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted small fw-bold mb-0">PESANAN AKTIF</h6>
                        <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                            <i class="fas fa-clock fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $pending_orders }}</h3>
                    <div class="mt-2">
                        <span class="badge bg-warning bg-opacity-20 text-dark x-small fw-bold">Dalam Proses</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: white; border-left: 5px solid #0dcaf0 !important;">
                <div class="card-body p-4 position-relative">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted small fw-bold mb-0">TOTAL BELANJA</h6>
                        <div class="bg-info bg-opacity-10 text-info rounded-3 p-2">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">Rp{{ number_format($total_spent, 0, ',', '.') }}</h3>
                    <div class="mt-2">
                        <span class="badge bg-info bg-opacity-10 text-info x-small fw-bold">Total Pengeluaran</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-premium rounded-4 h-100">
                <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>Pesanan Terbaru</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-muted small border">Lihat Semua</a>
                </div>
                <div class="card-body px-0 pt-0">
                    @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 custom-admin-table">
                            <thead class="bg-light text-muted x-small">
                                <tr>
                                    <th class="ps-4">NOMOR PESANAN</th>
                                    <th>TANGGAL</th>
                                    <th>STATUS</th>
                                    <th>TOTAL</th>
                                    <th class="pe-4 text-end">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td class="ps-4"><span class="fw-bold text-dark">#{{ $order->order_number }}</span></td>
                                    <td><span class="small text-muted">{{ $order->created_at->format('d M, Y') }}</span></td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'processing' => 'info',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'shipped' => 'primary',
                                                'delivered' => 'success',
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} bg-opacity-10 text-{{ $statusColors[$order->status] ?? 'secondary' }} rounded-pill px-2" style="font-size: 0.7rem;">
                                            {{ strtoupper($order->status) }}
                                        </span>
                                    </td>
                                    <td><span class="fw-bold text-dark">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span></td>
                                    <td class="pe-4 text-end">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="View Details">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-block p-4 mb-3">
                            <i class="fas fa-shopping-basket fa-3x text-muted opacity-25"></i>
                        </div>
                        <h6 class="text-muted">Anda belum memiliki pesanan.</h6>
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill mt-3 px-4">Mulai Belanja</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Action Sidebar Widgets -->
        <div class="col-lg-4">
            <div class="row g-4">
                <div class="col-12">
                    <a href="{{ route('products.index') }}" class="text-decoration-none d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card transition-all border-hover-primary h-100">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-primary bg-opacity-10 text-primary mb-3 mx-auto">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Lanjut Belanja</h6>
                                <p class="x-small text-muted mb-0">Temukan produk terbaru kami</p>
                                <div class="mt-3">
                                    <span class="btn btn-primary btn-sm rounded-pill px-4">Beli Sekarang</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('wishlist.index') }}" class="text-decoration-none d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card transition-all border-hover-success h-100">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-success bg-opacity-10 text-success mb-3 mx-auto">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Wishlist Anda</h6>
                                <p class="x-small text-muted mb-0">{{ $wishlist_count }} produk tersimpan</p>
                                <div class="mt-3">
                                    <span class="btn btn-success btn-sm rounded-pill px-4 text-white">Lihat Wishlist</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12">
                    <a href="{{ route('profile.edit') }}" class="text-decoration-none d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card transition-all border-hover-info h-100">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-info bg-opacity-10 text-info mb-3 mx-auto">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Perbarui Profil</h6>
                                <p class="x-small text-muted mb-0">Kelola informasi akun Anda</p>
                                <div class="mt-3">
                                    <span class="btn btn-info btn-sm rounded-pill px-4 text-white">Edit Profil</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
    .x-small { font-size: 0.75rem; letter-spacing: 0.5px; }
    .stats-card { transition: transform 0.3s; cursor: default; }
    .stats-card:hover { transform: translateY(-5px); }
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
    .wave-emoji { 
        display: inline-block; 
        animation: wave-animation 2.5s infinite; 
        transform-origin: 70% 70%; 
    }
    @keyframes wave-animation {
        0% { transform: rotate( 0.0deg) }
        10% { transform: rotate(14.0deg) }
        20% { transform: rotate(-8.0deg) }
        30% { transform: rotate(14.0deg) }
        40% { transform: rotate(-4.0deg) }
        50% { transform: rotate(10.0deg) }
        60% { transform: rotate( 0.0deg) }
        100% { transform: rotate( 0.0deg) }
    }
    .action-icon {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .quick-action-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1.5rem 4rem rgba(0,0,0,0.12) !important;
    }
    .border-hover-primary:hover { border-bottom: 4px solid var(--primary-color) !important; }
    .border-hover-success:hover { border-bottom: 4px solid #198754 !important; }
    .border-hover-info:hover { border-bottom: 4px solid #0dcaf0 !important; }
</style>
@endpush