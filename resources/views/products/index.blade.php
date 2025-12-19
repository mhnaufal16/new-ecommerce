{{-- resources/views/products/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Products - ' . config('app.name'))

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filters
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search Form -->
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Search</label>
                            <div class="input-group">
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search products..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Categories</label>
                            <div class="list-group">
                                <a href="{{ route('products.index') }}" 
                                   class="list-group-item list-group-item-action {{ !request('category') ? 'active' : '' }}">
                                    All Categories
                                </a>
                                @foreach($categories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $category->slug ? 'active' : '' }}">
                                        {{ $category->name }}
                                        <span class="badge bg-secondary rounded-pill">{{ $category->products_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        @if($brands->count() > 0)
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Brands</label>
                            <div class="list-group">
                                <a href="{{ route('products.index') }}" 
                                   class="list-group-item list-group-item-action {{ !request('brand') ? 'active' : '' }}">
                                    All Brands
                                </a>
                                @foreach($brands as $brand)
                                    <a href="{{ route('products.index', ['brand' => $brand->slug]) }}" 
                                       class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('brand') == $brand->slug ? 'active' : '' }}">
                                        {{ $brand->name }}
                                        <span class="badge bg-secondary rounded-pill">{{ $brand->products_count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Price Range</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" 
                                           name="min_price" 
                                           class="form-control" 
                                           placeholder="Min" 
                                           value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" 
                                           name="max_price" 
                                           class="form-control" 
                                           placeholder="Max" 
                                           value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Availability</label>
                            <select name="availability" class="form-select">
                                <option value="">All</option>
                                <option value="in_stock" {{ request('availability') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                <option value="out_of_stock" {{ request('availability') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            </select>
                        </div>

                        <!-- Sort By -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Sort By</label>
                            <select name="sort_by" class="form-select">
                                <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="price_low" {{ request('sort_by') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort_by') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
                            </select>
                        </div>

                        <!-- Apply Filters Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Apply Filters
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-2">
                                <i class="fas fa-times me-2"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Featured Products -->
            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0">
                        <i class="fas fa-star me-2"></i>Featured Products
                    </h6>
                </div>
                <div class="card-body p-0">
                    @php
                        $featured = \App\Models\Product::featured()
                            ->active()
                            ->with(['mainImage', 'activePrice'])
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @foreach($featured as $product)
                    <div class="p-3 border-bottom">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <img src="{{ $product->thumbnail_url }}" 
                                     alt="{{ $product->name }}"
                                     class="rounded" 
                                     width="60" 
                                     height="60"
                                     style="object-fit: cover;">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">
                                    <a href="{{ route('products.show', $product) }}" 
                                       class="text-decoration-none text-dark">
                                        {{ \Illuminate\Support\Str::limit($product->name, 30) }}
                                    </a>
                                </h6>
                                <p class="mb-1 text-primary fw-semibold">
                                    Rp {{ number_format($product->current_price, 0, ',', '.') }}
                                </p>
                                @if($product->isInStock())
                                    <span class="badge bg-success">In Stock</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">Products</h1>
                    <p class="text-muted mb-0">
                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <!-- View Toggle -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" id="gridView">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="listView">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                    <!-- Per Page Selector -->
                    <select class="form-select w-auto" id="perPageSelect">
                        <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12 per page</option>
                        <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24 per page</option>
                        <option value="36" {{ request('per_page', 12) == 36 ? 'selected' : '' }}>36 per page</option>
                        <option value="48" {{ request('per_page', 12) == 48 ? 'selected' : '' }}>48 per page</option>
                    </select>
                </div>
            </div>

            <!-- Active Filters -->
            @if(request()->anyFilled(['search', 'category', 'brand', 'min_price', 'max_price', 'availability']))
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-2">
                    <span class="text-muted">Active filters:</span>
                    
                    @if(request('search'))
                    <span class="badge bg-primary d-flex align-items-center">
                        Search: {{ request('search') }}
                        <a href="{{ route('products.index', request()->except('search')) }}" 
                           class="text-white ms-2" style="text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('category'))
                    @php $category = $categories->firstWhere('slug', request('category')); @endphp
                    <span class="badge bg-info d-flex align-items-center">
                        Category: {{ $category->name ?? request('category') }}
                        <a href="{{ route('products.index', request()->except('category')) }}" 
                           class="text-white ms-2" style="text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('brand'))
                    @php $brand = $brands->firstWhere('slug', request('brand')); @endphp
                    <span class="badge bg-warning d-flex align-items-center">
                        Brand: {{ $brand->name ?? request('brand') }}
                        <a href="{{ route('products.index', request()->except('brand')) }}" 
                           class="text-dark ms-2" style="text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('min_price') || request('max_price'))
                    <span class="badge bg-success d-flex align-items-center">
                        Price: 
                        @if(request('min_price'))Rp {{ number_format(request('min_price'), 0, ',', '.') }}@endif
                        @if(request('min_price') && request('max_price')) - @endif
                        @if(request('max_price'))Rp {{ number_format(request('max_price'), 0, ',', '.') }}@endif
                        <a href="{{ route('products.index', request()->except(['min_price', 'max_price'])) }}" 
                           class="text-white ms-2" style="text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif

                    @if(request('availability'))
                    <span class="badge bg-dark d-flex align-items-center">
                        {{ ucfirst(str_replace('_', ' ', request('availability'))) }}
                        <a href="{{ route('products.index', request()->except('availability')) }}" 
                           class="text-white ms-2" style="text-decoration: none;">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    @endif

                    <a href="{{ route('products.index') }}" class="btn btn-outline-danger btn-sm">
                        Clear All
                    </a>
                </div>
            </div>
            @endif

            <!-- Products Grid -->
            <div id="productsGrid" class="row">
                @forelse($products as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-card-col">
                    <div class="card product-card h-100">
                        <!-- Product Badges -->
                        <div class="product-badges">
                            @if($product->is_featured)
                                <span class="badge bg-warning">Featured</span>
                            @endif
                            @if($product->is_new)
                                <span class="badge bg-success">New</span>
                            @endif
                            @if($product->activePrice && $product->activePrice->isSaleActive())
                                <span class="badge bg-danger">Sale</span>
                            @endif
                        </div>

                        <!-- Product Image -->
                        <div class="product-image position-relative">
                            <a href="{{ route('products.show', $product) }}">
                                <img src="{{ $product->thumbnail_url ?: asset('images/default-product.jpg') }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     loading="lazy">
                            </a>
                            
                            <!-- Quick Actions -->
                            <div class="product-actions">
                                <button class="btn btn-sm btn-light wishlist-btn" 
                                        data-product-id="{{ $product->id }}"
                                        title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="btn btn-sm btn-light compare-btn" 
                                        data-product-id="{{ $product->id }}"
                                        title="Compare">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                            </div>

                            <!-- Add to Cart Button -->
                            <div class="product-add-to-cart">
                                @if($product->isInStock())
                                <button class="btn btn-primary btn-sm add-to-cart-btn" 
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}">
                                    <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                </button>
                                @else
                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="fas fa-ban me-1"></i> Out of Stock
                                </button>
                                @endif
                            </div>
                        </div>

                        <!-- Product Body -->
                        <div class="card-body d-flex flex-column">
                            <!-- Category -->
                            <div class="mb-2">
                                @if($product->primaryCategory)
                                <a href="{{ route('products.index', ['category' => $product->primaryCategory->slug]) }}" 
                                   class="text-muted text-decoration-none small">
                                    {{ $product->primaryCategory->name }}
                                </a>
                                @endif
                            </div>

                            <!-- Product Name -->
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $product) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ \Illuminate\Support\Str::limit($product->name, 50) }}
                                </a>
                            </h5>

                            <!-- Brand -->
                            @if($product->brand)
                            <p class="small text-muted mb-2">
                                <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" 
                                   class="text-decoration-none">
                                    {{ $product->brand->name }}
                                </a>
                            </p>
                            @endif

                            <!-- Rating -->
                            <div class="mb-2">
                                @php
                                    $avgRating = $product->approved_reviews_avg_rating ?? 0;
                                    $reviewCount = $product->approved_reviews_count ?? 0;
                                @endphp
                                <div class="d-flex align-items-center">
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($avgRating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $avgRating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted ms-2">({{ $reviewCount }})</small>
                                </div>
                            </div>

                            <!-- Price -->
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->activePrice && $product->activePrice->isSaleActive())
                                            <span class="text-danger h5 mb-0">
                                                Rp {{ number_format($product->activePrice->sale_price, 0, ',', '.') }}
                                            </span>
                                            <small class="text-muted text-decoration-line-through ms-2">
                                                Rp {{ number_format($product->activePrice->base_price, 0, ',', '.') }}
                                            </small>
                                            <div class="badge bg-danger mt-1">
                                                -{{ $product->activePrice->discount_percentage }}%
                                            </div>
                                        @else
                                            <span class="text-primary h5 mb-0">
                                                Rp {{ number_format($product->current_price, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Stock Status -->
                                    <div>
                                        @if($product->isInStock())
                                            <span class="badge bg-success">In Stock</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                            <h3>No products found</h3>
                            <p class="text-muted">Try adjusting your search or filter to find what you're looking for.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">
                                <i class="fas fa-redo me-2"></i>Reset Filters
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
            <div class="mt-4">
                {{ $products->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    overflow: hidden;
}
.product-card:hover {
    border-color: #007bff;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    transform: translateY(-5px);
}
.product-badges {
    position: absolute;
    top: 10px;
    left: 10px;
    z-index: 2;
}
.product-badges .badge {
    margin-right: 5px;
}
.product-image {
    overflow: hidden;
    position: relative;
    height: 200px;
}
.product-image img {
    height: 100%;
    width: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.product-card:hover .product-image img {
    transform: scale(1.05);
}
.product-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}
.product-card:hover .product-actions {
    opacity: 1;
}
.product-add-to-cart {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    padding: 10px;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}
.product-card:hover .product-add-to-cart {
    transform: translateY(0);
}
.list-view .product-card-col {
    flex: 0 0 100%;
    max-width: 100%;
}
.list-view .product-card {
    flex-direction: row;
    height: auto;
}
.list-view .product-image {
    flex: 0 0 200px;
    height: 200px;
}
.list-view .card-body {
    flex: 1;
}
</style>
@endpush

@push('scripts')
<script>
// View Toggle
document.getElementById('gridView').addEventListener('click', function() {
    document.getElementById('productsGrid').classList.remove('list-view');
    this.classList.add('active');
    document.getElementById('listView').classList.remove('active');
});

document.getElementById('listView').addEventListener('click', function() {
    document.getElementById('productsGrid').classList.add('list-view');
    this.classList.add('active');
    document.getElementById('gridView').classList.remove('active');
});

// Per Page Selector
document.getElementById('perPageSelect').addEventListener('change', function() {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', this.value);
    window.location.href = url.toString();
});

// Add to Cart
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const productName = this.dataset.productName;
        
        // Show loading state
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        this.disabled = true;
        
        // Send AJAX request
        fetch('/cart/add/' + productId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showToast('success', `"${productName}" added to cart!`);
                
                // Update cart count
                updateCartCount(data.cart_count);
            } else {
                showToast('error', data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            showToast('error', 'An error occurred');
            console.error('Error:', error);
        })
        .finally(() => {
            // Restore button state
            this.innerHTML = originalText;
            this.disabled = false;
        });
    });
});

// Wishlist button
document.querySelectorAll('.wishlist-btn').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        
        fetch('/wishlist/toggle/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.in_wishlist) {
                this.innerHTML = '<i class="fas fa-heart text-danger"></i>';
                showToast('success', 'Added to wishlist');
            } else {
                this.innerHTML = '<i class="far fa-heart"></i>';
                showToast('info', 'Removed from wishlist');
            }
        });
    });
});

// Helper functions
function showToast(type, message) {
    // Implement toast notification
    alert(message); // For now, use alert. Replace with proper toast later
}

function updateCartCount(count) {
    const cartCountEl = document.querySelector('.cart-count');
    if (cartCountEl) {
        cartCountEl.textContent = count;
    }
}
</script>
@endpush