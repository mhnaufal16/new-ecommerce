{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Jelajahi Produk - ' . config('app.name'))

@section('content')
<div class="bg-primary bg-opacity-10 py-5 mb-5 rounded-bottom-5">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent p-0 mb-3">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Produk</li>
                    </ol>
                </nav>
                <h1 class="display-5 fw-bold mb-3">Jelajahi Koleksi Kami</h1>
                <p class="text-muted lead">Temukan ribuan produk pilihan dengan kualitas terbaik dan promo menarik setiap hari.</p>
            </div>
            <div class="col-lg-6 d-none d-lg-block text-center">
                <i class="fas fa-shopping-bag fa-10x text-primary opacity-10"></i>
            </div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <div class="row g-4">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="sticky-top" style="top: 2rem;">
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-sliders-h me-2 text-primary"></i>Filter Produk</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('products.index') }}" method="GET">
                            <!-- Category Filter -->
                            <div class="mb-4">
                                <label class="label-premium mb-3">Kategori</label>
                                <div class="list-group list-group-flush rounded-3 border">
                                    <a href="{{ url()->current() . '?' . http_build_query(request()->except('category', 'page')) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !request('category') ? 'active bg-primary border-primary' : '' }}">
                                        Semua Kategori
                                    </a>
                                    @foreach($categories as $category)
                                        <a href="{{ route('products.index', array_merge(request()->query(), ['category' => $category->slug])) }}" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $category->slug ? 'active bg-primary border-primary' : '' }}">
                                            {{ $category->name }}
                                            <span class="badge {{ request('category') == $category->slug ? 'bg-white text-primary' : 'bg-light text-muted' }} rounded-pill">{{ $category->products_count }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <label class="label-premium mb-3">Rentang Harga (Rp)</label>
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <input type="number" name="min_price" class="form-control bg-light border-0 shadow-none rounded-3" placeholder="Min" value="{{ request('min_price') }}">
                                    <span class="text-muted">-</span>
                                    <input type="number" name="max_price" class="form-control bg-light border-0 shadow-none rounded-3" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>

                            <!-- Brand Filter -->
                            @if($brands->count() > 0)
                            <div class="mb-4">
                                <label class="label-premium mb-3">Merek</label>
                                <select name="brand" class="form-select bg-light border-0 shadow-none rounded-3 py-2">
                                    <option value="">Semua Merek</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Status Filter -->
                            <div class="mb-4">
                                <label class="label-premium mb-3">Ketersediaan</label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="availability" value="in_stock" id="stockCheck" {{ request('availability') == 'in_stock' ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="stockCheck">Stok Tersedia</label>
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                                    Terapkan Filter <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill fw-bold py-2 text-muted">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Promo Banner in Sidebar -->
                <div class="card border-0 bg-info bg-opacity-10 rounded-4 p-4 text-center">
                    <i class="fas fa-gift text-info fa-3x mb-3"></i>
                    <h6 class="fw-bold">Promo Member Baru!</h6>
                    <p class="small text-muted">Dapatkan diskon 10% untuk pembelian pertama Anda.</p>
                </div>
            </div>
        </div>

        <!-- Products Listing -->
        <div class="col-lg-9">
            <!-- Toolbar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body py-2 px-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="text-muted small">Menampilkan <strong>{{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }}</strong> dari <strong>{{ $products->total() }}</strong> Produk</span>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('products.index') }}" method="GET" id="sortForm" class="d-flex justify-content-md-end align-items-center gap-2">
                                @foreach(request()->except('sort', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <span class="text-muted small text-nowrap">Urutkan:</span>
                                <select name="sort" class="form-select border-0 bg-transparent shadow-none fw-bold" style="width: auto;" onchange="this.form.submit()">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga Tertinggi</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid -->
            @if($products->isEmpty())
                <div class="card border-0 shadow-sm rounded-4 py-5 text-center">
                    <div class="card-body">
                        <i class="fas fa-search fa-4x text-muted opacity-25 mb-4"></i>
                        <h5>Maaf, Produk Tidak Ditemukan</h5>
                        <p class="text-muted mb-4">Coba ubah kata kunci pencarian atau filter yang Anda gunakan.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5">Reset Pencarian</a>
                    </div>
                </div>
            @else
                <div class="row g-4">
                    @foreach($products as $product)
                    <div class="col-6 col-md-4">
                        <div class="card h-100 border-0 shadow-sm rounded-4 product-item overflow-hidden">
                            <div class="position-absolute p-2" style="top:0; right:0; z-index: 5;">
                                @if($product->is_new)
                                    <span class="badge bg-success rounded-pill shadow-sm">NEW</span>
                                @endif
                            </div>
                            <div class="overflow-hidden" style="height: 220px;">
                                <img src="{{ $product->thumbnail_url }}" class="card-img-top h-100 w-100 object-fit-cover transition-all" alt="{{ $product->name }}">
                            </div>
                            <div class="card-body p-3">
                                <div class="text-muted small mb-1">{{ $product->brand ? $product->brand->name : 'No Brand' }}</div>
                                <h6 class="card-title text-truncate fw-bold mb-2">
                                    <a href="{{ route('products.show', $product) }}" class="text-dark text-decoration-none hover-primary">{{ $product->name }}</a>
                                </h6>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="text-warning small me-2">
                                        <i class="fas fa-star text-warning"></i>
                                        <span class="text-dark fw-bold">{{ number_format($product->average_rating ?: 0, 1) }}</span>
                                    </div>
                                    <span class="text-muted small">({{ $product->review_count }})</span>
                                </div>
                                <div class="text-primary fw-bold fs-5 mb-3">Rp {{ number_format($product->current_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="card-footer bg-white border-0 p-3 pt-0">
                                <form action="{{ route('cart.add', $product) }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-sm rounded-pill py-2 fw-bold">
                                            <i class="fas fa-cart-plus me-1"></i> Tambah
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .rounded-bottom-5 { border-radius: 0 0 3rem 3rem !important; }
    .label-premium { 
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #6c757d;
        display: block;
    }
    .hover-primary:hover { color: var(--primary-color) !important; }
    .product-item { transition: transform 0.3s; }
    .product-item:hover { transform: translateY(-8px); }
    .product-item:hover img { transform: scale(1.08); }
    .object-fit-cover { object-fit: cover; }
    .transition-all { transition: all 0.3s ease; }
    
    /* Custom pagination styles */
    .pagination { gap: 5px; }
    .page-item .page-link { 
        border: none;
        border-radius: 10px !important;
        color: #555;
        font-weight: 600;
        padding: 10px 18px;
    }
    .page-item.active .page-link {
        background-color: var(--primary-color);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // AJAX Add to cart for product listing
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = this.querySelector('button');
                const originalContent = btn.innerHTML;
                
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                
                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new FormData(this)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showToast('success', data.message);
                        if(typeof updateCartCount === 'function') updateCartCount(data.cart_count);
                    } else {
                        showToast('error', data.message);
                    }
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                });
            });
        });
    });
</script>
@endpush