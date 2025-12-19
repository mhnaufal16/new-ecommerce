{{-- resources/views/dashboard/customer.blade.php --}}
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
                            <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=007bff&color=fff' }}" 
                                 alt="{{ $user->name }}"
                                 class="rounded-circle shadow"
                                 width="100"
                                 height="100">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-1 border border-3 border-white">
                                <i class="fas fa-check text-white"></i>
                            </span>
                        </div>
                        <h4 class="mt-3 mb-1">{{ $user->name }}</h4>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <span class="badge bg-primary mt-2">Customer</span>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ route('dashboard') }}" 
                           class="list-group-item list-group-item-action {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-user-edit me-2"></i>Edit Profile
                        </a>
                        <a href="{{ route('orders.index') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i>My Orders
                            <span class="badge bg-primary float-end">{{ $total_orders }}</span>
                        </a>
                        <a href="{{ route('wishlist.index') }}" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-heart me-2"></i>Wishlist
                            <span class="badge bg-danger float-end">{{ $wishlist_count }}</span>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-map-marker-alt me-2"></i>Addresses
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-lock me-2"></i>Change Password
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
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
                                    <h6 class="card-title mb-0">Total Orders</h6>
                                    <h2 class="display-6 mb-0">{{ $total_orders }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-shopping-bag fa-2x text-primary"></i>
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
                                    <h6 class="card-title mb-0">Completed</h6>
                                    <h2 class="display-6 mb-0">{{ $completed_orders }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
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
                                    <h6 class="card-title mb-0">Pending</h6>
                                    <h2 class="display-6 mb-0">{{ $pending_orders }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
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
                                    <h6 class="card-title mb-0">Total Spent</h6>
                                    <h2 class="display-6 mb-0">Rp {{ number_format($total_spent, 0, ',', '.') }}</h2>
                                </div>
                                <div class="bg-white rounded-circle p-3">
                                    <i class="fas fa-wallet fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>Recent Orders
                        </h5>
                        <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recent_orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_orders as $order)
                                <tr>
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->items->count() }} items</td>
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
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                        <h4>No orders yet</h4>
                        <p class="text-muted mb-4">Start shopping to see your orders here</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-primary rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-shopping-cart fa-2x text-white"></i>
                            </div>
                            <h5>Continue Shopping</h5>
                            <p class="text-muted">Browse our latest products</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                Shop Now
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-success rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-heart fa-2x text-white"></i>
                            </div>
                            <h5>Your Wishlist</h5>
                            <p class="text-muted">{{ $wishlist_count }} items saved</p>
                            <a href="{{ route('wishlist.index') }}" class="btn btn-success">
                                View Wishlist
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <div class="bg-info rounded-circle p-3 d-inline-block mb-3">
                                <i class="fas fa-user-edit fa-2x text-white"></i>
                            </div>
                            <h5>Update Profile</h5>
                            <p class="text-muted">Keep your information current</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-info">
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
}
.list-group-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}
</style>
@endpush