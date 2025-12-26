@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Order Details: #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->thumbnail_url)
                                            <img src="{{ $item->product->thumbnail_url }}" class="img-thumbnail me-3" style="width: 50px;">
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end">Subtotal</td>
                                    <td class="text-end fw-bold">{{ $order->formatted_subtotal }}</td>
                                </tr>
                                @if($order->shipping_amount > 0)
                                <tr>
                                    <td colspan="3" class="text-end">Shipping</td>
                                    <td class="text-end">{{ $order->formatted_shipping_amount }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end">Grand Total</td>
                                    <td class="text-end fw-bold fs-5 text-primary">{{ $order->formatted_grand_total }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Managements -->
            <div class="row">
                <div class="col-md-4">
                     <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h6 class="fw-bold text-muted text-uppercase small ls-1 mb-0">Order Status</h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-0">
                                    <select name="status" class="form-select border-2 rounded-3 mb-3 py-2">
                                        @foreach(['pending', 'processing', 'on_hold', 'completed', 'cancelled', 'refunded', 'failed'] as $status)
                                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary rounded-pill py-2 fw-bold w-100 shadow-sm transition-all">Update Status</button>
                                </div>
                            </form>
                        </div>
                     </div>
                </div>
                <div class="col-md-4">
                     <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h6 class="fw-bold text-muted text-uppercase small ls-1 mb-0">Payment Status</h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.orders.update-payment-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-0">
                                    <select name="payment_status" class="form-select border-2 rounded-3 mb-3 py-2">
                                        @foreach(['pending', 'paid', 'partially_paid', 'refunded', 'failed'] as $status)
                                            <option value="{{ $status }}" {{ $order->payment_status === $status ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-info text-white rounded-pill py-2 fw-bold w-100 shadow-sm transition-all" style="background-color: #0dcaf0; border-color: #0dcaf0;">Update Payment</button>
                                </div>
                            </form>
                        </div>
                     </div>
                </div>
                <div class="col-md-4">
                     <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-0">
                            <h6 class="fw-bold text-muted text-uppercase small ls-1 mb-0">Shipping Status</h6>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('admin.orders.update-shipping-status', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-0">
                                    <select name="shipping_status" class="form-select border-2 rounded-3 mb-3 py-2">
                                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ $order->shipping_status === $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-warning text-dark rounded-pill py-2 fw-bold w-100 shadow-sm transition-all" style="background-color: #ffc107; border-color: #ffc107;">Update Shipping</button>
                                </div>
                            </form>
                        </div>
                     </div>
                </div>
            </div>
        </div>

        <!-- Customer & Info -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $order->user->name ?? 'Guest' }}</h6>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> {{ $order->customer_email }}</p>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $order->customer_phone ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Addresses</h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold text-muted">Shipping Address</h6>
                    @if($order->shippingAddress)
                        <p class="mb-3">
                            {{ $order->shippingAddress->address_line1 }}<br>
                            @if($order->shippingAddress->address_line2)
                                {{ $order->shippingAddress->address_line2 }}<br>
                            @endif
                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}
                        </p>
                    @else
                        <p class="text-muted">Same as billing</p>
                    @endif

                    <h6 class="fw-bold text-muted border-top pt-3">Billing Address</h6>
                    @if($order->billingAddress)
                        <p class="mb-0">
                            {{ $order->billingAddress->address_line1 }}<br>
                            @if($order->billingAddress->address_line2)
                                {{ $order->billingAddress->address_line2 }}<br>
                            @endif
                            {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} {{ $order->billingAddress->postal_code }}
                        </p>
                    @else
                        <p class="text-muted">No billing address</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
