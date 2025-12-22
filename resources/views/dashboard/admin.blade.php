{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('title', 'Admin Dashboard - ' . config('app.name'))

@section('content')
<div class="container-fluid py-5 px-lg-5">
    <div class="row g-4">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-premium rounded-4 overflow-hidden sticky-top" style="top: 2rem;">
                <div class="card-body p-0">
                    <!-- Profile Section -->
                    <div class="text-center py-5 bg-light mb-2">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff' }}" 
                                 alt="{{ $user->name }}"
                                 class="rounded-circle shadow-sm border border-4 border-white"
                                 width="110"
                                 height="110"
                                 style="object-fit: cover;">
                            <span class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white shadow-sm" title="Administrator">
                                <i class="fas fa-crown text-white fa-sm"></i>
                            </span>
                        </div>
                        <h5 class="mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h5>
                        <p class="text-muted small mb-3">{{ $user->email }}</p>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold small">ADMINISTRATOR</span>
                    </div>

                    <!-- Nav Links -->
                    <div class="p-3">
                        <div class="list-group list-group-flush admin-nav">
                            <a href="{{ route('dashboard') }}" 
                               class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active shadow-sm' : '' }}">
                                <div class="nav-icon-box me-3"><i class="fas fa-grid-2"></i></div>
                                <span class="fw-bold">Dashboard</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-box"></i></div>
                                <span class="fw-bold">Produk</span>
                                <span class="badge bg-primary ms-auto rounded-pill px-2">{{ $total_products }}</span>
                            </a>
                            <a href="{{ route('admin.brands.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-tags"></i></div>
                                <span class="fw-bold">Merek</span>
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-folder"></i></div>
                                <span class="fw-bold">Kategori</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-shopping-bag"></i></div>
                                <span class="fw-bold">Pesanan</span>
                                <span class="badge bg-success ms-auto rounded-pill px-2">{{ $total_orders }}</span>
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-users"></i></div>
                                <span class="fw-bold">Pelanggan</span>
                                <span class="badge bg-info ms-auto rounded-pill px-2 text-white">{{ $total_users }}</span>
                            </a>
                            <a href="{{ route('admin.reviews.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-comment-dots"></i></div>
                                <span class="fw-bold">Ulasan</span>
                                @if($pending_reviews > 0)
                                <span class="badge bg-warning ms-auto rounded-pill px-2 text-white">{{ $pending_reviews }}</span>
                                @endif
                            </a>
                            <div class="my-3 border-top opacity-50"></div>
                            <a href="{{ route('admin.analytics.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-chart-line"></i></div>
                                <span class="fw-bold">Analitik</span>
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center">
                                <div class="nav-icon-box me-3"><i class="fas fa-sliders-h"></i></div>
                                <span class="fw-bold">Pengaturan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Content -->
        <div class="col-lg-9">
            <!-- Top Welcome Bar -->
            <div class="row mb-5 align-items-center">
                <div class="col-md-7">
                    <h2 class="fw-bold mb-1">Halo, {{ explode(' ', $user->name)[0] }}! <span class="wave-emoji">👋</span></h2>
                    <p class="text-muted mb-0">Inilah ringkasan performa toko Anda hari ini.</p>
                </div>
                <div class="col-md-5 text-md-end mt-3 mt-md-0">
                    <div class="bg-white d-inline-flex p-2 rounded-pill shadow-sm border px-3 align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <span class="small fw-bold">{{ date('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Stats Grid -->
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: linear-gradient(135deg, #0d6efd 0%, #0056b3 100%);">
                        <div class="card-body p-4 text-white position-relative">
                            <div class="opacity-10 position-absolute end-0 bottom-0 mb-n3 me-n2">
                                <i class="fas fa-money-bill-wave fa-6x"></i>
                            </div>
                            <h6 class="text-white text-opacity-75 small fw-bold mb-3">TOTAL PENDAPATAN</h6>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%);">
                        <div class="card-body p-4 text-white position-relative">
                            <div class="opacity-10 position-absolute end-0 bottom-0 mb-n3 me-n2">
                                <i class="fas fa-shopping-cart fa-6x"></i>
                            </div>
                            <h6 class="text-white text-opacity-75 small fw-bold mb-3">TOTAL PESANAN</h6>
                            <h3 class="fw-bold mb-0">{{ $total_orders }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);">
                        <div class="card-body p-4 text-white position-relative">
                            <div class="opacity-10 position-absolute end-0 bottom-0 mb-n3 me-n2">
                                <i class="fas fa-box fa-6x"></i>
                            </div>
                            <h6 class="text-white text-opacity-75 small fw-bold mb-3">TOTAL PRODUK</h6>
                            <h3 class="fw-bold mb-0">{{ $total_products }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-premium stats-card h-100 overflow-hidden" style="background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);">
                        <div class="card-body p-4 text-white position-relative">
                            <div class="opacity-10 position-absolute end-0 bottom-0 mb-n3 me-n2">
                                <i class="fas fa-users fa-6x"></i>
                            </div>
                            <h6 class="text-white text-opacity-75 small fw-bold mb-3">PELANGGAN</h6>
                            <h3 class="fw-bold mb-0">{{ $total_users }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Central Content Area -->
            <div class="row g-4">
                <!-- Recent Orders Table -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-premium rounded-4 h-100">
                        <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-0">
                            <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-primary"></i>Pesanan Terbaru</h5>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-muted small border">Lihat Semua</a>
                        </div>
                        <div class="card-body px-0 pt-0">
                            @if($recent_orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 custom-admin-table">
                                    <thead class="bg-light text-muted x-small">
                                        <tr>
                                            <th class="ps-4">NOMOR PESANAN</th>
                                            <th>PELANGGAN</th>
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
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-circle p-2 me-2 text-center" style="width: 32px; height: 32px; font-size: 0.7rem;">
                                                        {{ strtoupper(substr($order->user->name ?? 'G', 0, 1)) }}
                                                    </div>
                                                    <span class="small fw-medium">{{ $order->user->name ?? 'Guest' }}</span>
                                                </div>
                                            </td>
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
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="View Details">
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
                                <h6 class="text-muted">Belum ada pesanan masuk.</h6>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Side Widgets -->
                <div class="col-lg-4">
                    <!-- Low Stock Alert -->
                    <div class="card border-0 shadow-premium rounded-4 mb-4">
                        <div class="card-header bg-white py-4 px-4 border-0">
                            <h5 class="mb-0 fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Stok Menipis</h5>
                        </div>
                        <div class="card-body px-4 pt-0 pb-4">
                            @if($low_stock_products->count() > 0)
                            <div class="list-group list-group-flush admin-list">
                                @foreach($low_stock_products as $product)
                                <div class="list-group-item px-0 py-3 border-0 border-bottom border-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="overflow-hidden">
                                            <h6 class="mb-1 fw-bold text-truncate small">{{ $product->name }}</h6>
                                            <p class="x-small text-muted mb-0">SKU: {{ $product->sku }}</p>
                                        </div>
                                        <div class="ms-2">
                                            <span class="badge bg-danger rounded-pill px-2">{{ $product->inventory->quantity ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-grid mt-3">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-danger btn-sm rounded-pill fw-bold">Update Stok</a>
                            </div>
                            @else
                            <div class="text-center py-4 bg-light rounded-4">
                                <i class="fas fa-check-circle fa-2x text-success mb-3 opacity-50"></i>
                                <p class="text-muted small mb-0 fw-bold">Stok aman terkendali</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Reviews Widget -->
                    <div class="card border-0 shadow-premium rounded-4 bg-primary text-white position-relative overflow-hidden">
                        <div class="card-body p-4 position-relative z-index-1">
                            <h5 class="fw-bold mb-3"><i class="fas fa-star me-2 fw-normal opacity-75"></i>Ulasan Baru</h5>
                            @if($pending_reviews > 0)
                                <div class="display-5 fw-bold mb-1">{{ $pending_reviews }}</div>
                                <p class="small text-white text-opacity-75 mb-4">Ulasan pembeli butuh persetujuan Anda.</p>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-white btn-sm rounded-pill px-4 fw-bold shadow-sm">Review Sekarang</a>
                            @else
                                <div class="bg-white bg-opacity-20 rounded-circle p-3 d-inline-block mb-3">
                                    <i class="fas fa-check-circle fa-2x"></i>
                                </div>
                                <p class="small mb-0 fw-bold">Tidak ada ulasan tertunda</p>
                            @endif
                        </div>
                        <div class="position-absolute end-0 bottom-0 mb-n4 me-n3 opacity-10">
                            <i class="fas fa-comments fa-10x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Quick Actions -->
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <a href="{{ route('admin.products.create') }}" class="text-decoration-none h-100 d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card h-100 transition-all border-hover-primary">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-primary bg-opacity-10 text-primary mb-3 mx-auto">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Tambah Produk</h6>
                                <p class="x-small text-muted mb-0">Ugah koleksi produk baru Anda</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.analytics.index') }}" class="text-decoration-none h-100 d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card h-100 transition-all border-hover-success">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-success bg-opacity-10 text-success mb-3 mx-auto">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Laporan Penjualan</h6>
                                <p class="x-small text-muted mb-0">Lihat grafik performa toko</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.settings.index') }}" class="text-decoration-none h-100 d-block">
                        <div class="card border-0 shadow-premium rounded-4 quick-action-card h-100 transition-all border-hover-info">
                            <div class="card-body p-4 text-center">
                                <div class="action-icon bg-info bg-opacity-10 text-info mb-3 mx-auto">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Pengaturan Toko</h6>
                                <p class="x-small text-muted mb-0">Konfigurasi alamat & kurir</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
    .x-small { font-size: 0.75rem; letter-spacing: 0.5px; }
    .nav-icon-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 12px;
        color: #adb5bd;
        transition: all 0.2s;
    }
    .admin-nav .active .nav-icon-box {
        background: rgba(255,255,255,0.2) !important;
        color: white !important;
    }
    .admin-nav .list-group-item.active {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .admin-nav .list-group-item:not(.active):hover {
        background-color: #f8fafc;
        transform: translateX(5px);
    }
    .admin-nav .list-group-item:not(.active):hover .nav-icon-box {
        background-color: var(--primary-color);
        color: white;
    }
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
    .btn-white { background: white; color: var(--primary-color); }
    .btn-white:hover { background: #f8f9fa; transform: translateY(-2px); }
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
@endsection