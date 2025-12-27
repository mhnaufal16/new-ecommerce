@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold mb-1 text-dark">Analytics & Reports</h3>
            <p class="text-muted small mb-0"><i class="fas fa-signal me-1"></i> Pantau performa toko Anda secara real-time</p>
        </div>
        
        <!-- Period Filter -->
        <div class="bg-white p-1 rounded-3 shadow-sm border">
            <div class="btn-group">
                <a href="{{ route('admin.analytics.index', ['period' => 'today']) }}" class="btn btn-sm {{ $period == 'today' ? 'btn-primary' : 'btn-light' }} border-0 px-3">Hari Ini</a>
                <a href="{{ route('admin.analytics.index', ['period' => 'week']) }}" class="btn btn-sm {{ $period == 'week' ? 'btn-primary' : 'btn-light' }} border-0 px-3">Minggu Ini</a>
                <a href="{{ route('admin.analytics.index', ['period' => 'month']) }}" class="btn btn-sm {{ $period == 'month' ? 'btn-primary' : 'btn-light' }} border-0 px-3">Bulan Ini</a>
                <a href="{{ route('admin.analytics.index', ['period' => 'year']) }}" class="btn btn-sm {{ $period == 'year' ? 'btn-primary' : 'btn-light' }} border-0 px-3">Tahun Ini</a>
                <a href="{{ route('admin.analytics.index', ['period' => 'all']) }}" class="btn btn-sm {{ $period == 'all' ? 'btn-primary' : 'btn-light' }} border-0 px-3">Semua</a>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-4">
                            <i class="fas fa-wallet fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Total Sales</h6>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($totalSales, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="bg-primary" style="height: 4px; opacity: 0.6"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-4">
                            <i class="fas fa-shopping-bag fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Total Orders</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalOrders) }}</h4>
                    </div>
                </div>
                <div class="bg-success" style="height: 4px; opacity: 0.6"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-4">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Total Customers</h6>
                        <h4 class="fw-bold mb-0 text-dark">{{ number_format($totalCustomers) }}</h4>
                    </div>
                </div>
                <div class="bg-info" style="height: 4px; opacity: 0.6"></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 h-100 summary-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-4">
                            <i class="fas fa-chart-line fa-lg"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1 text-uppercase tracking-wider">Avg Order Value</h6>
                        <h4 class="fw-bold mb-0 text-dark">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="bg-warning" style="height: 4px; opacity: 0.6"></div>
            </div>
        </div>
    </div>

    <div class="row g-4 overflow-hidden">
        <!-- Main Content Area -->
        <div class="col-lg-8">
            <!-- Monthly Sales Trend -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-chart-area me-2 text-primary"></i>Tren Penjualan Bulanan</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-top border-bottom">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-bold small text-uppercase" style="width: 50%">Bulan</th>
                                    <th class="px-4 py-3 text-muted fw-bold small text-uppercase text-end">Total Penjualan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlySales as $sale)
                                <tr>
                                    <td class="px-4 py-3 fw-medium">
                                        {{ Carbon\Carbon::parse($sale->month)->format('M' ) }} {{ Carbon\Carbon::parse($sale->month)->format('Y') }}
                                    </td>
                                    <td class="px-4 py-3 text-end fw-bold text-primary">
                                        Rp {{ number_format($sale->total, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5">
                                        <div class="text-muted opacity-50 mb-2"><i class="fas fa-box-open fa-2x"></i></div>
                                        <span class="text-muted small">Selesaikan pesanan untuk melihat data tren</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Customers -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-crown me-2 text-warning"></i>Pelanggan Terbaik</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-top border-bottom">
                                <tr>
                                    <th class="px-4 py-3 text-muted fw-bold small text-uppercase">Pelanggan</th>
                                    <th class="px-4 py-3 text-muted fw-bold small text-uppercase text-center">Pesanan</th>
                                    <th class="px-4 py-3 text-muted fw-bold small text-uppercase text-end">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCustomers as $customer)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold me-3 shadow-sm" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $customer->name }}</div>
                                                <div class="text-muted smaller d-block mt-n1">{{ $customer->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center fw-medium">{{ $customer->order_count }}</td>
                                    <td class="px-4 py-3 text-end fw-bold text-dark">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted small">Belum ada data pelanggan terbaik</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Area -->
        <div class="col-lg-4">
            <!-- Top Selling Products -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-trophy me-2 text-warning"></i>Produk Terlaris</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-3 border-light">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded border p-1 me-3 shadow-sm" style="width: 54px; height: 54px; display: flex; align-items: center; justify-content: center; overflow: hidden">
                                        <img src="{{ $product->thumbnail_url }}" class="rounded" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                    <div style="min-width: 0">
                                        <div class="fw-bold small text-dark text-truncate d-block" style="width: 130px;">{{ $product->name }}</div>
                                        <div class="text-primary fw-bold smaller mt-1">
                                            {{ $product->activePrice ? $product->activePrice->formatted_price : 'Rp 0' }}
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill px-3 py-2 shadow-sm" style="font-size: 0.75rem;">
                                    {{ $product->total_sold }} <span class="fw-normal opacity-75">terjual</span>
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted small">
                                <i class="fas fa-ghost fa-2x mb-2 d-block opacity-25"></i>
                                Belum ada data produk terlaris
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sales by Category -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-tags me-2 text-info"></i>Penjualan per Kategori</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @php
                        $maxCategoryRevenue = $salesByCategory->max('revenue') ?: 1;
                    @endphp
                    @forelse($salesByCategory as $category)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="small fw-bold text-dark">{{ $category->name }}</span>
                            <span class="small text-muted fw-medium">Rp {{ number_format($category->revenue, 0, ',', '.') }}</span>
                        </div>
                        <div class="progress rounded-pill" style="height: 10px; background: #f0f2f5">
                            <div class="progress-bar bg-info" role="progressbar" 
                                 style="width: {{ ($category->revenue / $maxCategoryRevenue) * 100 }}%; border-radius: 50px"></div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted small">
                         <i class="fas fa-folder-open fa-2x mb-2 d-block opacity-25"></i>
                         Belum ada data penjualan kategori
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Order Status Breakdown -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-4 px-4 border-0">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-tasks me-2 text-secondary"></i>Status Pesanan</h5>
                </div>
                <div class="card-body p-4 pt-0">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'processing' => 'info',
                            'completed' => 'success',
                            'cancelled' => 'danger',
                            'refunded' => 'secondary'
                        ];
                    @endphp
                    <div class="row g-2">
                        @foreach($orderStatuses as $status => $count)
                        <div class="col-6">
                            <div class="bg-light p-3 rounded-4 border text-center h-100">
                                <h3 class="fw-bold mb-1 text-{{ $statusColors[$status] ?? 'dark' }}">{{ $count }}</h3>
                                <div class="text-muted smaller fw-bold text-uppercase tracking-wide">{{ $status }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .summary-card {
        transition: transform 0.3s ease, shadow 0.3s ease;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }
    .capitalize { text-transform: capitalize; }
    .smaller { font-size: 0.75rem; }
    .uppercase { text-transform: uppercase; }
    .tracking-wider { letter-spacing: 0.1em; }
    .tracking-wide { letter-spacing: 0.05em; }
    .btn-light { background: #f8f9fa; color: #444; border: 1px solid #eee; }
    .btn-light:hover { background: #f0f2f4; }
    
    .table thead th {
        background: #fdfdfe;
        border-bottom: 2px solid #f1f1f1;
    }
    
    .list-group-item {
        transition: all 0.2s;
        border-left: 0;
        border-right: 0;
    }
    .list-group-item:hover {
        background-color: #fcfdfe;
    }
</style>
@endpush
