@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Vendor Dashboard</h2>
            <p class="text-muted mb-0">Selamat datang di pusat kontrol toko Anda</p>
        </div>
        <div class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold shadow-sm">
            <i class="fas fa-store me-1"></i> Mode Vendor
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4 mb-3 d-inline-block">
                        <i class="fas fa-wallet fa-lg"></i>
                    </div>
                    <h6 class="text-muted small fw-bold mb-1">PENDAPATAN KOTOR</h6>
                    <h4 class="fw-bold mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="bg-success bg-opacity-10 text-success p-3 rounded-4 mb-3 d-inline-block">
                        <i class="fas fa-shopping-basket fa-lg"></i>
                    </div>
                    <h6 class="text-muted small fw-bold mb-1">TOTAL PESANAN</h6>
                    <h4 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="bg-info bg-opacity-10 text-info p-3 rounded-4 mb-3 d-inline-block">
                        <i class="fas fa-box fa-lg"></i>
                    </div>
                    <h6 class="text-muted small fw-bold mb-1">TOTAL PRODUK</h6>
                    <h4 class="fw-bold mb-0">{{ number_format($totalProducts) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4 mb-3 d-inline-block">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <h6 class="text-muted small fw-bold mb-1">PRODUK AKTIF</h6>
                    <h4 class="fw-bold mb-0">{{ number_format($activeProducts) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Pesanan Terbaru</h5>
                    <a href="#" class="btn btn-link btn-sm text-decoration-none text-primary fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">ID Pesanan</th>
                                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">Pelanggan</th>
                                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">Total</th>
                                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">Status</th>
                                    <th class="px-4 py-3 border-0 small text-uppercase text-muted fw-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td class="px-4 py-3 fw-bold text-dark">#{{ $order->order_number }}</td>
                                    <td class="px-4 py-3">{{ $order->user->name }}</td>
                                    <td class="px-4 py-3 fw-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="badge rounded-pill text-bg-{{ $order->status_color }} px-3">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Detail</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan masuk</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark">Aksi Cepat</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-primary rounded-pill py-2">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Produk Baru
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary rounded-pill py-2">
                            <i class="fas fa-boxes me-1"></i> Kelola Produk
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <i class="fas fa-lightbulb fa-3x opacity-50"></i>
                    </div>
                    <h5 class="fw-bold">Tips Penjualan</h5>
                    <p class="small mb-0 opacity-75">Gunakan gambar produk yang jernih dan deskripsi yang lengkap untuk menarik lebih banyak pembeli.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
