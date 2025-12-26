@extends('layouts.admin')

@section('admin_content')
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
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div><strong>{{ $item->product_name }}</strong></div>
                                        @if($item->variant_name)
                                            <small class="text-muted">{{ $item->variant_name }}</small>
                                        @endif
                                    </td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->formatted_price }}</td>
                                    <td class="text-end">{{ $item->formatted_total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                    <td class="text-end">{{ $order->formatted_subtotal }}</td>
                                </tr>
                                @if($order->coupon_discount > 0)
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Discount</strong></td>
                                    <td class="text-end text-danger">-{{ $order->formatted_coupon_discount }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Shipping</strong></td>
                                    <td class="text-end">{{ $order->formatted_shipping_cost }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><h5><strong>Grand Total</strong></h5></td>
                                    <td class="text-end"><h5><strong>{{ $order->formatted_grand_total }}</strong></h5></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
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
