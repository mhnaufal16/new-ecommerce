@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Reviews Management</h2>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Customer</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                        <tr>
                            <td>
                                <a href="{{ route('products.show', $review->product) }}" class="text-decoration-none">
                                    {{ $review->product->name }}
                                </a>
                            </td>
                            <td>{{ $review->user->name }}</td>
                            <td>
                                <span class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                </span>
                            </td>
                            <td>{{ $review->comment }}</td>
                            <td>
                                @if($review->is_approved)
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                @if(!$review->is_approved)
                                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Delete this review?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No reviews found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection
