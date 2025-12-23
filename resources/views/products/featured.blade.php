{{-- resources/views/products/featured.blade.php --}}
@extends('layouts.app')

@section('title', 'Featured - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-3">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Featured</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4">Produk Unggulan</h1>

    @if($products->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="fas fa-star fa-4x text-muted opacity-25 mb-4"></i>
                <h5>Belum ada produk unggulan</h5>
                <p class="text-muted mb-4">Kami akan menambahkan produk unggulan segera.</p>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-6 col-md-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 product-item overflow-hidden">
                    <div class="overflow-hidden" style="height: 160px;">
                        <img src="{{ $product->thumbnail_url }}" class="card-img-top h-100 w-100 object-fit-cover" alt="{{ $product->name }}">
                    </div>
                    <div class="card-body p-3">
                        <div class="text-muted small mb-1">{{ $product->brand?->name ?? 'No Brand' }}</div>
                        <h6 class="card-title text-truncate fw-bold mb-2"><a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none">{{ $product->name }}</a></h6>
                        <div class="text-primary fw-bold">Rp {{ number_format($product->current_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

@endsection
