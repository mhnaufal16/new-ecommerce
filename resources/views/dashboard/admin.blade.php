{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=dc3545&color=fff' }}" 
                                 alt="{{ $user->name }}"
                                 class="rounded-circle shadow"
                                 width="100"
                                 height="100">
                            <span class="position-absolute bottom-0 end-0 bg-danger rounded-circle p-1 border border-3 border-white">
                                <i class="fas fa-crown text-white"></i>
                            </span>
                        </div>
                        <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <span class="badge bg-danger mt-2">Administrator</span>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-box me-2"></i>Products
                            <span class="badge bg-primary float-end">{{ $total_products }}</span>
                        </a>
                        <a href="{{ route('admin.brands.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-tags me-2"></i>Brands
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-folder me-2"></i>Categories
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i>Orders
                            <span class="badge bg-success float-end">{{ $total_orders }}</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Customers
                            <span class="badge bg-info float-end">{{ $total_users }}</span>
                        </a>
                        <a href="{{ route('admin.reviews.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-star me-2"></i>Reviews
                            @if($pending_reviews > 0)
                            <span class="badge bg-warning float-end">{{ $pending_reviews }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.analytics.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-chart-line me-2"></i>Analytics
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i>Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Revenue</h6>
                                    <h2 class="display-6 mb-0">Rp {{ number_format($total_revenue, 0, ',', '.') }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-money-bill-wave fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Orders</h6>
                                    <h2 class="display-6 mb-0">{{ $total_orders }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-shopping-bag fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Products</h6>
                                    <h2 class="display-6 mb-0">{{ $total_products }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-box fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="card-title mb-0">Total Users</h6>
                                    <h2 class="display-6 mb-0">{{ $total_users }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-users fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts & Recent Orders -->
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>Recent Orders
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($recent_orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Customer</th>
                                            <th>Date</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recent_orders as $order)
                                        <tr>
                                            <td><strong>{{ $order->order_number }}</strong></td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
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
                                                <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="fw-semibold">
                                                Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No orders yet</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <!-- Low Stock Products -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Low Stock
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($low_stock_products->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($low_stock_products as $product)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $product->name }}</h6>
                                        <small class="text-muted">
                                            SKU: {{ $product->sku }}
                                        </small>
                                    </div>
                                    <span class="badge bg-warning">
                                        {{ $product->inventory->quantity ?? 0 }} left
                                    </span>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-warning">
                                    View All Low Stock
                                </a>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                <p class="text-muted mb-0">All products have sufficient stock</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Pending Reviews -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-star me-2 text-info"></i>Pending Reviews
                                </h5>
                                @if($pending_reviews > 0)
                                <span class="badge bg-info">{{ $pending_reviews }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($pending_reviews > 0)
                            <div class="text-center py-3">
                                <i class="fas fa-comments fa-3x text-info mb-3"></i>
                                <h4>{{ $pending_reviews }} reviews pending</h4>
                                <p class="text-muted">Need your approval</p>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-info">
                                    <i class="fas fa-check me-2"></i>Review Now
                                </a>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                                <p class="text-muted mb-0">No pending reviews</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-plus fa-2x text-white"></i>
                            </div>
                            <h5>Add Product</h5>
                            <p class="text-muted">Add new product to store</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                Add New
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-success rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-chart-bar fa-2x text-white"></i>
                            </div>
                            <h5>View Reports</h5>
                            <p class="text-muted">Sales analytics & reports</p>
                            <a href="{{ route('admin.analytics.index') }}" class="btn btn-success">
                                View Reports
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-info rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-cog fa-2x text-white"></i>
                            </div>
                            <h5>Store Settings</h5>
                            <p class="text-muted">Configure store settings</p>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-info">
                                Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection