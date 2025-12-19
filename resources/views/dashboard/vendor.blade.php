{{-- resources/views/dashboard/vendor.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-store-alt me-2"></i>Vendor Dashboard
                    </h4>
                </div>
                <div class="card-body text-center py-5">
                    <div class="display-1 text-warning mb-4">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="mb-3">Vendor Dashboard Coming Soon</h3>
                    <p class="text-muted mb-4">
                        The vendor dashboard is currently under development. 
                        You'll be able to manage your products, orders, and earnings here.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i>Browse Products
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection