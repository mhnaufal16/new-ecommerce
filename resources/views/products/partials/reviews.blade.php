{{-- resources/views/products/partials/reviews.blade.php --}}
<div class="reviews-section">
    <div class="row">
        <!-- Rating Summary -->
        <div class="col-md-4 mb-4">
            <div class="card bg-light border-0 rounded-4">
                <div class="card-body text-center p-4">
                    <h1 class="display-3 fw-bold text-primary mb-0">{{ number_format($averageRating, 1) }}</h1>
                    <div class="text-warning mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= round($averageRating) ? 'fas' : 'far' }} fa-star fs-5"></i>
                        @endfor
                    </div>
                    <p class="text-muted mb-0">Berdasarkan {{ $reviewCount }} Ulasan</p>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="col-md-8 mb-4">
            @auth
                @if($product->canUserReview(auth()->id()))
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Tulis Ulasan Anda</h5>
                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label class="form-label d-block text-muted small fw-bold">RATING ANDA</label>
                                    <div class="rating-input d-flex gap-2">
                                        @for($i = 5; $i >= 1; $i--)
                                            <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" class="btn-check" required>
                                            <label for="star{{ $i }}" class="btn btn-outline-warning rounded-circle" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                {{ $i }}
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="comment" class="form-label text-muted small fw-bold">ULASAN ANDA</label>
                                    <textarea name="comment" id="comment" rows="3" class="form-control rounded-3 border-0 bg-light p-3" placeholder="Bagikan pengalaman Anda menggunakan produk ini..." required></textarea>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Kirim Ulasan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info border-0 rounded-4 shadow-sm p-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-1">Berikan Ulasan</h6>
                                <p class="mb-0 small opacity-75">Anda hanya dapat memberikan ulasan untuk produk yang sudah pernah Anda beli dan belum pernah diulas sebelumnya.</p>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-secondary border-0 rounded-4 shadow-sm p-4 text-center">
                    <p class="mb-3">Silakan masuk untuk memberikan ulasan produk ini.</p>
                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">Login Sekarang</a>
                </div>
            @endauth
        </div>
    </div>

    <!-- Reviews List -->
    <hr class="my-5 opacity-10">
    
    <div class="reviews-list">
        @forelse($product->approvedReviews as $review)
            <div class="review-item d-flex mb-4 pb-4 border-bottom">
                <div class="flex-shrink-0">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 50px; height: 50px;">
                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name }}</h6>
                        <span class="text-muted x-small">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="text-warning small mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-muted small mb-0">{{ $review->comment }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="far fa-comments fa-3x text-muted opacity-25 mb-3"></i>
                <h6 class="text-muted">Belum ada ulasan untuk produk ini.</h6>
                <p class="small text-muted">Jadilah yang pertama memberikan ulasan!</p>
            </div>
        @endforelse
    </div>
</div>
