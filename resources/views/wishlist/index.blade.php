@extends('layouts.app')

@section('title', 'Wishlist - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-3">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
        </ol>
    </nav>

    <h1 class="h3 mb-4">Wishlist</h1>

    @if($wishlists->isEmpty())
        <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
            <div class="card-body">
                <i class="fas fa-heart fa-4x text-muted opacity-25 mb-4"></i>
                <h5>Belum ada produk di wishlist</h5>
                <p class="text-muted mb-0">Tambahkan produk ke wishlist untuk menyimpannya nanti.</p>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-4">
            <div class="list-group list-group-flush">
                @foreach($wishlists as $item)
                    <div class="list-group-item d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center gap-3">
                            @if($item->product)
                                <div style="width:72px; height:72px; overflow:hidden; border-radius:.75rem; background:#fff; box-shadow:0 0 0 1px rgba(0,0,0,0.03);">
                                    <a href="{{ route('products.show', $item->product) }}">
                                        <img src="{{ $item->product->thumbnail_url ?? $item->product->main_image_url ?? asset('images/placeholder.png') }}" alt="{{ $item->product->name }}" class="img-fluid w-100 h-100 object-fit-cover">
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('products.show', $item->product) }}" class="fw-bold text-dark text-decoration-none">{{ $item->product->name }}</a>
                                    <div class="text-muted small">{{ $item->product->brand?->name ?? '' }}</div>
                                    <div class="text-primary fw-bold mt-1">Rp {{ number_format($item->product->current_price ?? 0, 0, ',', '.') }}</div>
                                </div>
                            @else
                                <div class="text-muted">Produk tidak tersedia (telah dihapus)</div>
                            @endif
                        </div>

                        <div class="text-end">
                            @if($item->product)
                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk dari wishlist?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
