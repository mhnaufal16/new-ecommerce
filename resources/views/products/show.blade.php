{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb bg-light p-3 rounded-pill shadow-sm px-4">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home me-1"></i> Beranda
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none">Produk</a>
            </li>
            @foreach($product->breadcrumb as $category)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="text-decoration-none">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;" aria-current="page">
                {{ $product->name }}
            </li>
        </ol>
    </nav>

    <div class="row g-5">
        <!-- Product Images Gallery -->
        <div class="col-lg-7">
            <div class="product-gallery">
                <div class="main-image-container mb-3 shadow-sm rounded-4 border overflow-hidden bg-white">
                    <img src="{{ $product->main_image_url }}" 
                         id="currentImage"
                         class="img-fluid w-100 h-100 object-fit-contain" 
                         alt="{{ $product->name }}"
                         style="min-height: 400px; max-height: 600px;">
                    
                    @if($product->activePrice && $product->activePrice->isSaleActive())
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 shadow-sm">
                            <i class="fas fa-bolt me-1 text-warning"></i> SALE {{ $product->activePrice->discount_percentage }}% OFF
                        </span>
                    </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                <div class="row g-3 thumbnails-scroll">
                    @foreach($product->images as $image)
                    <div class="col-auto">
                        <div class="thumbnail-item rounded-3 shadow-sm border cursor-pointer overflow-hidden {{ $loop->first ? 'active' : '' }}"
                             onclick="changeMainImage('{{ $image->url }}', this)">
                            <img src="{{ $image->thumbnail }}" 
                                 class="img-fluid"
                                 alt="Thumbnail {{ $loop->iteration }}"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-5">
            <div class="product-info-sticky">
                <div class="mb-2">
                    @if($product->brand)
                    <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" 
                       class="text-primary fw-bold text-decoration-none text-uppercase small ls-1">
                        {{ $product->brand->name }}
                    </a>
                    @endif
                </div>
                
                <h1 class="display-6 fw-bold mb-3">{{ $product->name }}</h1>
                
                <div class="d-flex align-items-center mb-4">
                    <div class="text-warning me-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <span class="text-muted small">({{ $reviewCount }} Customer Reviews)</span>
                    <span class="mx-2 text-muted">|</span>
                    <span class="badge bg-{{ $product->isInStock() ? 'success' : 'danger' }} bg-opacity-10 text-{{ $product->isInStock() ? 'success' : 'danger' }} rounded-pill border border-{{ $product->isInStock() ? 'success' : 'danger' }} px-3">
                        <i class="fas fa-{{ $product->isInStock() ? 'check-circle' : 'times-circle' }} me-1"></i>
                        {{ $product->isInStock() ? 'Tersedia: ' . $product->stock_quantity : 'Stok Habis' }}
                    </span>
                </div>

                <div class="price-container mb-4 p-4 bg-light rounded-4 border-start border-4 border-primary shadow-sm">
                    @if($product->activePrice && $product->activePrice->isSaleActive())
                        <div class="text-muted text-decoration-line-through mb-1">Rp {{ number_format($product->activePrice->base_price, 0, ',', '.') }}</div>
                        <div class="d-flex align-items-end">
                            <span class="h2 fw-bold text-primary mb-0">Rp {{ number_format($product->current_price, 0, ',', '.') }}</span>
                            <span class="badge bg-danger ms-3 mb-2 small">Hemat Rp {{ number_format($product->activePrice->base_price - $product->activePrice->sale_price, 0, ',', '.') }}</span>
                        </div>
                    @else
                        <span class="h2 fw-bold text-primary mb-0">Rp {{ number_format($product->current_price, 0, ',', '.') }}</span>
                    @endif
                </div>

                @if($product->short_description)
                <p class="text-muted border-start ps-3 mb-4">{{ $product->short_description }}</p>
                @endif

                <form id="addToCartForm" action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    
                    @if($product->hasVariants())
                    <div class="variant-selection mb-4">
                        <h6 class="fw-bold mb-3">Pilih Varian</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->getAvailableVariants() as $variant)
                            <label class="variant-label">
                                <input type="radio" name="variant_id" value="{{ $variant->id }}" required class="hide-radio">
                                <div class="variant-box p-2 border rounded-3 text-center transition-all bg-white cursor-pointer">
                                    <div class="small fw-bold">{{ $variant->attributes_text }}</div>
                                    <div class="text-muted x-small">Stok: {{ $variant->stock_quantity }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="purchase-actions d-flex gap-3 mb-4">
                        <div class="qty-selector d-flex align-items-center bg-light rounded-pill p-2 border shadow-sm">
                            <button type="button" class="btn btn-qty rounded-circle border-0 bg-transparent" id="qtyMinus">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" name="quantity" id="productQty" class="form-control text-center bg-transparent border-0 fw-bold" 
                                   value="1" min="1" max="{{ $product->stock_quantity }}" style="width: 50px;">
                            <button type="button" class="btn btn-qty rounded-circle border-0 bg-transparent" id="qtyPlus">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        
                        <button type="submit" id="addToCartBtn" class="btn btn-primary btn-lg rounded-pill flex-grow-1 fw-bold shadow-lg" {{ !$product->isInStock() ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                        </button>
                    </div>
                </form>

                <div class="d-flex gap-2">
                    <button id="wishlistBtn" class="btn btn-outline-danger rounded-pill flex-grow-1 fw-medium transition-all">
                        <i class="far fa-heart me-1"></i> Add to Wishlist
                    </button>
                    <button class="btn btn-outline-secondary rounded-circle" style="width: 48px; height: 48px;"><i class="fas fa-share-alt"></i></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Tabs -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 p-0">
                    <ul class="nav nav-tabs nav-justified border-0 premium-tabs" id="productTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active py-3 fw-bold" id="desc-tab" data-bs-toggle="tab" data-bs-target="#desc-pane" type="button">Deskripsi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs-pane" type="button">Spesifikasi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link py-3 fw-bold" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-pane" type="button">Ulasan ({{ $reviewCount }})</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content" id="productTabsContent">
                        <div class="tab-pane fade show active" id="desc-pane">
                            <div class="prose max-w-none">
                                {!! $product->description !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="specs-pane">
                            <div class="table-responsive">
                                <table class="table table-striped rounded-3">
                                    <tbody>
                                        @if($product->specifications)
                                            @foreach($product->specifications as $label => $value)
                                            <tr>
                                                <th class="w-25 border-top-0">{{ $label }}</th>
                                                <td class="border-top-0">{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="text-center text-muted">Tidak ada spesifikasi tambahan.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="reviews-pane">
                            @include('products.partials.reviews')
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
    .rounded-4 { border-radius: 1.25rem !important; }
    .ls-1 { letter-spacing: 1px; }
    .x-small { font-size: 0.7rem; }
    
    .cursor-pointer { cursor: pointer; }
    
    .thumbnail-item {
        transition: all 0.2s;
        opacity: 0.6;
    }
    .thumbnail-item:hover, .thumbnail-item.active {
        opacity: 1;
        border-color: #0d6efd !important;
        transform: scale(1.05);
    }
    
    .main-image-container img {
        transition: transform 0.3s ease;
    }
    .main-image-container:hover img {
        transform: scale(1.1);
    }

    .hide-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .variant-label input:checked + .variant-box {
        border-color: #0d6efd !important;
        background-color: rgba(13, 110, 253, 0.05) !important;
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25);
    }
    
    .variant-box:hover {
        border-color: #adb5bd;
    }
    
    .btn-qty:hover {
        background-color: #dee2e6 !important;
    }
    
    .premium-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    
    .premium-tabs .nav-link.active {
        color: #0d6efd;
        background: transparent;
        border-bottom-color: #0d6efd;
    }
    
    .premium-tabs .nav-link:hover {
        background-color: #f8f9fa;
    }
    
    .thumbnails-scroll {
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 10px;
    }
    
    .thumbnails-scroll::-webkit-scrollbar {
        height: 5px;
    }
    .thumbnails-scroll::-webkit-scrollbar-thumb {
        background: #dee2e6;
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    function changeMainImage(url, el) {
        document.getElementById('currentImage').src = url;
        document.querySelectorAll('.thumbnail-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Quantity Controls
        const qtyInput = document.getElementById('productQty');
        const plusBtn = document.getElementById('qtyPlus');
        const minusBtn = document.getElementById('qtyMinus');

        plusBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value);
            const max = parseInt(qtyInput.max) || 999;
            if (current < max) qtyInput.value = current + 1;
        });

        minusBtn.addEventListener('click', () => {
            const current = parseInt(qtyInput.value);
            if (current > 1) qtyInput.value = current - 1;
        });

        // Add to Cart Simulation
        document.getElementById('addToCartForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('addToCartBtn');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast('success', data.message);
                    if(typeof updateCartCount === 'function') updateCartCount(data.cart_count);
                } else {
                    showToast('error', data.message || 'Gagal menambahkan produk.');
                }
            })
            .catch(err => showToast('error', 'Terjadi kesalahan sistem.'))
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        });

        // Wishlist Simulation
        document.getElementById('wishlistBtn').addEventListener('click', function() {
            const productId = '{{ $product->id }}';
            fetch('/wishlist/toggle/' + productId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.in_wishlist) {
                    this.innerHTML = '<i class="fas fa-heart me-1"></i> In Wishlist';
                    this.className = 'btn btn-danger rounded-pill flex-grow-1 fw-medium';
                } else {
                    this.innerHTML = '<i class="far fa-heart me-1"></i> Add to Wishlist';
                    this.className = 'btn btn-outline-danger rounded-pill flex-grow-1 fw-medium';
                }
            });
        });
    });
</script>
@endpush