@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Analytics & Reports</h2>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Total Sales</h6>
                    <h3 class="mb-0">Rp {{ number_format($totalSales, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Total Orders</h6>
                    <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Total Customers</h6>
                    <h3 class="mb-0">{{ number_format($totalCustomers) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title opacity-75">Avg Order Value</h6>
                    <h3 class="mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Sales Chart (Placeholder) -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Monthly Sales Trend</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                             <thead class="table-light">
                                 <tr>
                                     <th>Month</th>
                                     <th>Total Sales</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @forelse($monthlySales as $sale)
                                 <tr>
                                     <td>{{ $sale->month }}</td>
                                     <td>Rp {{ number_format($sale->total, 0, ',', '.') }}</td>
                                 </tr>
                                 @empty
                                 <tr>
                                     <td colspan="2" class="text-center">No sales data available.</td>
                                 </tr>
                                 @endforelse
                             </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($topProducts as $product)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->thumbnail_url }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-truncate" style="max-width: 150px;">{{ $product->name }}</div>
                                        <small class="text-muted">{{ $product->formatted_price }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $product->total_sold }} sold</span>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted">No sales yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
