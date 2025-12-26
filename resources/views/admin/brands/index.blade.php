@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Brand Management</h2>
        <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add New Brand
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Logo</th>
                            <th>Name</th>
                            <th>Products</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($brands as $brand)
                        <tr>
                            <td>
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" 
                                         class="img-thumbnail" style="width: 50px; height: 50px; object-fit: contain;">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $brand->name }}</div>
                                @if($brand->description)
                                    <small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $brand->products_count }} products</span>
                            </td>
                            <td>
                                <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this brand?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <p class="mb-0">No brands found.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $brands->links() }}
            </div>
        </div>
    </div>
@endsection
