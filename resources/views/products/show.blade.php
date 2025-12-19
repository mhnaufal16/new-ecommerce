{{-- resources/views/products/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    Products
                </a>
            </li>
            @foreach($product->breadcrumb as $category)
                <li class="breadcrumb-item">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       class="text-decoration-none">
                        {{ $category->name }}
                    </a>
                </li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">
                {{ \Illuminate\Support\Str::limit($product->name, 30) }}
            </li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">
            <!-- Main Image -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div id="mainImage" class="text-center">
                        <img src="{{ $product->mainImage->image_url ?? asset('images/default-product.jpg') }}" 
                             id="currentImage"
                             class="img-fluid rounded" 
                             alt="{{ $product->name }}"
                             style="max-height: 500px; object-fit: contain;">
                    </div>
                </div>
            </div>

            <!-- Thumbnail Images -->
            @if($product->images->count() > 1)
            <div class="row g-2">
                @foreach($product->images as $image)
                <div class="col-3">
                    <div class="thumbnail-image cursor-pointer border rounded p-1 
                                {{ $loop->first ? 'border-primary' : 'border-light' }}"
                         onclick="changeMainImage('{{ $image->image_url }}', this)">
                        <img src="{{ $image->thumbnail_url ?? $image->image_url }}" 
                             class="img-fluid rounded"
                             alt="{{ $product->name }} - Image {{ $loop->iteration }}"
                             style="height: 80px; width: 100%; object-fit: cover;">
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <!-- Product Badges -->
                    <div class="mb-3">
                        @if($product->is_featured)
                            <span class="badge bg-warning">Featured</span>
                        @endif
                        @if($product->is_new)
                            <span class="badge bg-success">New</span>
                        @endif
                        @if($product->activePrice && $product->activePrice->isSaleActive())
                            <span class="badge bg-danger">Sale {{ $product->activePrice->discount_percentage }}% OFF</span>
                        @endif
                        @if(!$product->isInStock())
                            <span class="badge bg-secondary">Out of Stock</span>
                        @endif
                    </div>

                    <!-- Product Name -->
                    <h1 class="h2 mb-2">{{ $product->name }}</h1>

                    <!-- Brand -->
                    @if($product->brand)
                    <p class="text-muted mb-3">
                        Brand: 
                        <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}" 
                           class="text-decoration-none">
                            {{ $product->brand->name }}
                        </a>
                    </p>
                    @endif

                    <!-- Rating -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fas fa-star text-warning"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt text-warning"></i>
                                @else
                                    <i class="far fa-star text-warning"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="ms-2">
                            {{ number_format($averageRating, 1) }} 
                            ({{ $reviewCount }} reviews)
                        </span>
                        <span class="ms-3">
                            <i class="fas fa-eye text-muted"></i> 
                            {{ $product->view_count ?? 0 }} views
                        </span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        @if($product->activePrice && $product->activePrice->isSaleActive())
                            <div class="d-flex align-items-center">
                                <span class="h2 text-danger me-3">
                                    Rp {{ number_format($product->activePrice->sale_price, 0, ',', '.') }}
                                </span>
                                <span class="h4 text-muted text-decoration-line-through">
                                    Rp {{ number_format($product->activePrice->base_price, 0, ',', '.') }}
                                </span>
                                <span class="badge bg-danger ms-2">
                                    Save Rp {{ number_format($product->activePrice->discount_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            @if($product->activePrice->sale_end_date)
                            <div class="mt-2">
                                <small class="text-muted">
                                    Sale ends: {{ $product->activePrice->sale_end_date->format('d M Y') }}
                                </small>
                            </div>
                            @endif
                        @else
                            <span class="h2 text-primary">
                                Rp {{ number_format($product->current_price, 0, ',', '.') }}
                            </span>
                        @endif
                    </div>

                    <!-- Short Description -->
                    @if($product->short_description)
                    <div class="mb-4">
                        <p>{{ $product->short_description }}</p>
                    </div>
                    @endif

                    <!-- Variant Selection -->
                    @if($product->type === 'configurable' && $variantAttributes)
                    <div class="mb-4">
                        <h5 class="mb-3">Select Options:</h5>
                        
                        <form id="variantForm">
                            @foreach($variantAttributes as $attribute)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    {{ $attribute['name'] }}:
                                    <span id="selected_{{ $attribute['code'] }}" class="text-primary"></span>
                                </label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($attribute['values'] as $value)
                                    <div class="variant-option" 
                                         data-attribute="{{ $attribute['code'] }}"
                                         data-value-id="{{ $value['id'] }}"
                                         data-value="{{ $value['value'] }}"
                                         onclick="selectVariantOption(this, '{{ $attribute['code'] }}')">
                                        
                                        @if($value['color_code'])
                                        <div class="color-swatch" 
                                             style="background-color: {{ $value['color_code'] }};"
                                             title="{{ $value['value'] }}">
                                        </div>
                                        <small class="d-block text-center mt-1">{{ $value['value'] }}</small>
                                        @elseif($value['image_url'])
                                        <img src="{{ $value['image_url'] }}" 
                                             alt="{{ $value['value'] }}"
                                             class="img-fluid rounded border"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                        <small class="d-block text-center mt-1">{{ $value['value'] }}</small>
                                        @else
                                        <button type="button" class="btn btn-outline-secondary">
                                            {{ $value['value'] }}
                                        </button>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </form>

                        <!-- Selected Variant Info -->
                        <div id="variantInfo" class="d-none mt-3 p-3 bg-light rounded">
                            <div class="row">
                                <div class="col">
                                    <strong>Selected Variant:</strong>
                                    <span id="selectedVariantName"></span>
                                </div>
                                <div class="col">
                                    <strong>Price:</strong>
                                    <span id="selectedVariantPrice" class="text-primary"></span>
                                </div>
                                <div class="col">
                                    <strong>Stock:</strong>
                                    <span id="selectedVariantStock"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Stock Status -->
                    <div class="mb-4">
                        @if($product->isInStock())
                            <div class="d-flex align-items-center text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <span>In Stock</span>
                                @if($product->inventory)
                                <small class="ms-2 text-muted">
                                    ({{ $product->inventory->quantity }} available)
                                </small>
                                @endif
                            </div>
                        @else
                            <div class="d-flex align-items-center text-danger">
                                <i class="fas fa-times-circle me-2"></i>
                                <span>Out of Stock</span>
                            </div>
                        @endif
                    </div>

                    <!-- Add to Cart Section -->
                    <div class="mb-4">
                        <form id="addToCartForm" action="{{ route('cart.add', $product) }}" method="POST">
                            @csrf
                            <input type="hidden" name="variant_id" id="selectedVariantId">
                            
                            <div class="row g-3">
                                <!-- Quantity -->
                                <div class="col-auto">
                                    <label class="form-label">Quantity</label>
                                    <div class="input-group" style="width: 140px;">
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="decreaseQuantity()">-</button>
                                        <input type="number" 
                                               name="quantity" 
                                               id="quantity" 
                                               class="form-control text-center" 
                                               value="1" 
                                               min="1" 
                                               max="{{ $product->inventory->quantity ?? 10 }}">
                                        <button type="button" class="btn btn-outline-secondary" 
                                                onclick="increaseQuantity()">+</button>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="col">
                                    <div class="d-grid gap-2">
                                        @if($product->isInStock())
                                        <button type="submit" class="btn btn-primary btn-lg" id="addToCartBtn">
                                            <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-lg" disabled>
                                            <i class="fas fa-ban me-2"></i> Out of Stock
                                        </button>
                                        @endif
                                        
                                        <button type="button" class="btn btn-outline-secondary" id="wishlistBtn">
                                            <i class="far fa-heart me-2"></i> Add to Wishlist
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Product Meta -->
                    <div class="row border-top pt-3">
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-shipping-fast fa-2x text-primary mb-2"></i>
                                <h6>Free Shipping</h6>
                                <small class="text-muted">On orders over Rp 500.000</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-undo fa-2x text-primary mb-2"></i>
                                <h6>30-Day Returns</h6>
                                <small class="text-muted">Money back guarantee</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                                <h6>Secure Payment</h6>
                                <small class="text-muted">100% secure payment</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Tabs -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" 
                                    id="description-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#description" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-file-alt me-2"></i>Description
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="specifications-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#specifications" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-list-ul me-2"></i>Specifications
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="reviews-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#reviews" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-star me-2"></i>Reviews ({{ $reviewCount }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" 
                                    id="shipping-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#shipping" 
                                    type="button" 
                                    role="tab">
                                <i class="fas fa-truck me-2"></i>Shipping & Returns
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="productTabsContent">
                        <!-- Description Tab -->
                        <div class="tab-pane fade show active" 
                             id="description" 
                             role="tabpanel">
                            <div class="p-3">
                                @if($product->description)
                                    {!! $product->description !!}
                                @else
                                    <p class="text-muted">No description available.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Specifications Tab -->
                        <div class="tab-pane fade" 
                             id="specifications" 
                             role="tabpanel">
                            <div class="p-3">
                                @if($product->specifications && count($product->specifications) > 0)
                                    <table class="table table-striped">
                                        <tbody>
                                            @foreach($product->specifications as $key => $value)
                                            <tr>
                                                <th style="width: 30%;">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No specifications available.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Reviews Tab -->
                        <div class="tab-pane fade" 
                             id="reviews" 
                             role="tabpanel">
                            <div class="p-3">
                                @if($reviewCount > 0)
                                    <!-- Average Rating -->
                                    <div class="text-center mb-4">
                                        <div class="display-4 text-primary mb-2">
                                            {{ number_format($averageRating, 1) }}
                                        </div>
                                        <div class="rating-stars mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($averageRating))
                                                    <i class="fas fa-star text-warning fa-2x"></i>
                                                @elseif($i - 0.5 <= $averageRating)
                                                    <i class="fas fa-star-half-alt text-warning fa-2x"></i>
                                                @else
                                                    <i class="far fa-star text-warning fa-2x"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <p class="text-muted">Based on {{ $reviewCount }} reviews</p>
                                    </div>

                                    <!-- Reviews List -->
                                    <div class="reviews-list">
                                        @foreach($product->approvedReviews as $review)
                                        <div class="review-item border-bottom pb-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    {{ $review->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            @if($review->title)
                                            <h6 class="mt-2">{{ $review->title }}</h6>
                                            @endif
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                        <h4>No reviews yet</h4>
                                        <p class="text-muted">Be the first to review this product!</p>
                                    </div>
                                @endif

                                <!-- Add Review Form (for users who purchased) -->
                                @if(auth()->check() && $product->canUserReview(auth()->id()))
                                <div class="mt-4">
                                    <h5>Write a Review</h5>
                                    <form action="{{ route('reviews.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <div class="rating-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" 
                                                       id="star{{ $i }}" 
                                                       name="rating" 
                                                       value="{{ $i }}" 
                                                       {{ $i == 5 ? 'checked' : '' }}>
                                                <label for="star{{ $i }}">
                                                    <i class="far fa-star"></i>
                                                </label>
                                                @endfor
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="reviewTitle" class="form-label">Title</label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="reviewTitle" 
                                                   name="title" 
                                                   placeholder="Summarize your review">
                                        </div>

                                        <div class="mb-3">
                                            <label for="reviewComment" class="form-label">Review</label>
                                            <textarea class="form-control" 
                                                      id="reviewComment" 
                                                      name="comment" 
                                                      rows="4" 
                                                      placeholder="Share your experience with this product"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Submit Review</button>
                                    </form>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Shipping Tab -->
                        <div class="tab-pane fade" 
                             id="shipping" 
                             role="tabpanel">
                            <div class="p-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-shipping-fast me-2"></i>Shipping Information</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Free shipping on orders over Rp 500.000
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Estimated delivery: 3-7 business days
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Shipping to all regions in Indonesia
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Real-time tracking available
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5><i class="fas fa-undo me-2"></i>Return Policy</h5>
                                        <ul class="list-unstyled">
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                30-day return policy
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Items must be in original condition
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Free returns for defective items
                                            </li>
                                            <li class="mb-2">
                                                <i class="fas fa-check text-success me-2"></i>
                                                Refund processed within 3-5 business days
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <h3 class="mb-4">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <div class="card h-100">
                        <img src="{{ $relatedProduct->thumbnail_url }}" 
                             class="card-img-top" 
                             alt="{{ $relatedProduct->name }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('products.show', $relatedProduct) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ \Illuminate\Support\Str::limit($relatedProduct->name, 40) }}
                                </a>
                            </h5>
                            <p class="card-text text-primary fw-bold">
                                Rp {{ number_format($relatedProduct->current_price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('products.show', $relatedProduct) }}" 
                               class="btn btn-outline-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.thumbnail-image {
    cursor: pointer;
    transition: all 0.3s ease;
}
.thumbnail-image:hover {
    border-color: #007bff !important;
    transform: scale(1.05);
}
.variant-option {
    cursor: pointer;
    transition: all 0.3s ease;
}
.variant-option:hover {
    transform: translateY(-2px);
}
.variant-option.selected {
    border-color: #007bff !important;
    background-color: rgba(0, 123, 255, 0.1);
}
.color-swatch {
    width: 40px;
    height: 40px;
    border-radius: 4px;
    border: 2px solid #dee2e6;
    cursor: pointer;
}
.color-swatch:hover {
    border-color: #007bff;
}
.rating-input {
    direction: rtl;
    unicode-bidi: bidi-override;
    text-align: left;
}
.rating-input input {
    display: none;
}
.rating-input label {
    font-size: 1.5rem;
    color: #ddd;
    cursor: pointer;
    padding: 0 2px;
}
.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input:checked ~ label {
    color: #ffc107;
}
</style>
@endpush

@push('scripts')
<script>
// Image Gallery
function changeMainImage(imageUrl, element) {
    document.getElementById('currentImage').src = imageUrl;
    
    // Remove selected class from all thumbnails
    document.querySelectorAll('.thumbnail-image').forEach(img => {
        img.classList.remove('border-primary');
        img.classList.add('border-light');
    });
    
    // Add selected class to clicked thumbnail
    element.classList.remove('border-light');
    element.classList.add('border-primary');
}

// Quantity Controls
function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const max = parseInt(quantityInput.max);
    let current = parseInt(quantityInput.value);
    
    if (current < max) {
        quantityInput.value = current + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const min = parseInt(quantityInput.min);
    let current = parseInt(quantityInput.value);
    
    if (current > min) {
        quantityInput.value = current - 1;
    }
}

// Variant Selection
let selectedAttributes = {};

function selectVariantOption(element, attributeCode) {
    const valueId = element.dataset.valueId;
    const value = element.dataset.value;
    
    // Update selected attributes
    selectedAttributes[attributeCode] = {
        valueId: valueId,
        value: value
    };
    
    // Update UI
    document.querySelectorAll(`[data-attribute="${attributeCode}"]`).forEach(opt => {
        opt.classList.remove('selected');
    });
    element.classList.add('selected');
    
    // Update selected text
    document.getElementById(`selected_${attributeCode}`).textContent = value;
    
    // Check if all attributes are selected
    const allAttributesSelected = Object.keys(selectedAttributes).length === {{ count($variantAttributes ?? []) }};
    
    if (allAttributesSelected) {
        // Find matching variant
        findVariant();
    } else {
        // Hide variant info
        document.getElementById('variantInfo').classList.add('d-none');
    }
}

function findVariant() {
    const attributeValueIds = Object.values(selectedAttributes).map(attr => attr.valueId);
    
    fetch(`/products/{{ $product->id }}/variant`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            attribute_values: attributeValueIds
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.data) {
            const variant = data.data;
            
            // Update variant info
            document.getElementById('selectedVariantId').value = variant.id;
            
            let variantName = '';
            Object.values(selectedAttributes).forEach((attr, index) => {
                variantName += attr.value;
                if (index < Object.keys(selectedAttributes).length - 1) {
                    variantName += ' / ';
                }
            });
            
            document.getElementById('selectedVariantName').textContent = variantName;
            document.getElementById('selectedVariantPrice').textContent = 
                'Rp ' + new Intl.NumberFormat('id-ID').format(variant.current_price);
            
            const stockStatus = variant.inventory && variant.inventory.quantity > 0 ? 
                variant.inventory.quantity + ' in stock' : 'Out of stock';
            document.getElementById('selectedVariantStock').textContent = stockStatus;
            
            // Update quantity max
            const quantityInput = document.getElementById('quantity');
            if (variant.inventory) {
                quantityInput.max = variant.inventory.quantity;
                if (parseInt(quantityInput.value) > variant.inventory.quantity) {
                    quantityInput.value = variant.inventory.quantity;
                }
            }
            
            // Update add to cart button
            const addToCartBtn = document.getElementById('addToCartBtn');
            if (variant.inventory && variant.inventory.quantity > 0) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = '<i class="fas fa-shopping-cart me-2"></i> Add to Cart';
            } else {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<i class="fas fa-ban me-2"></i> Out of Stock';
            }
            
            // Show variant info
            document.getElementById('variantInfo').classList.remove('d-none');
        } else {
            // Variant not found
            document.getElementById('variantInfo').classList.add('d-none');
            alert('Selected combination is not available');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Add to Cart Form Submission
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const addToCartBtn = document.getElementById('addToCartBtn');
    const originalText = addToCartBtn.innerHTML;
    
    // Show loading state
    addToCartBtn.disabled = true;
    addToCartBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Adding...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('success', 'Product added to cart!');
            
            // Update cart count
            updateCartCount(data.cart_count);
            
            // Optionally redirect to cart page
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        } else {
            showToast('error', data.message || 'Failed to add to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred');
    })
    .finally(() => {
        // Restore button state
        addToCartBtn.disabled = false;
        addToCartBtn.innerHTML = originalText;
    });
});

// Wishlist Button
document.getElementById('wishlistBtn').addEventListener('click', function() {
    const btn = this;
    const originalText = btn.innerHTML;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
    
    fetch('/wishlist/toggle/{{ $product->id }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.in_wishlist) {
            btn.innerHTML = '<i class="fas fa-heart me-2 text-danger"></i> In Wishlist';
            showToast('success', 'Added to wishlist!');
        } else {
            btn.innerHTML = '<i class="far fa-heart me-2"></i> Add to Wishlist';
            showToast('info', 'Removed from wishlist');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('error', 'An error occurred');
    })
    .finally(() => {
        btn.disabled = false;
    });
});

// Helper Functions
function showToast(type, message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    // Add to page
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    toastContainer.appendChild(toast);
    
    // Initialize and show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove after hide
    toast.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    document.body.appendChild(container);
    return container;
}

function updateCartCount(count) {
    const cartCountEl = document.querySelector('.cart-count');
    if (cartCountEl) {
        cartCountEl.textContent = count;
    }
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush