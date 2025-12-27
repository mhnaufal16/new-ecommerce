@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Manajemen Produk</h2>
            <p class="text-muted mb-0">Kelola inventaris barang dagangan Anda</p>
        </div>
        <div>
            <a href="{{ route('vendor.products.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i>Tambah Produk
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted small fw-bold">PRODUK</th>
                            <th class="py-3 text-muted small fw-bold">SKU</th>
                            <th class="py-3 text-muted small fw-bold">HARGA</th>
                            <th class="py-3 text-muted small fw-bold">STOK</th>
                            <th class="py-3 text-muted small fw-bold">STATUS</th>
                            <th class="pe-4 py-3 text-end text-muted small fw-bold">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $product->thumbnail_url }}" class="rounded-3 border me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div>
                                        <div class="fw-bold text-dark">{{ $product->name }}</div>
                                        <div class="small text-muted">{{ $product->brand->name ?? 'Tanpa Brand' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3"><code class="small text-primary fw-bold">{{ $product->sku }}</code></td>
                            <td class="py-3 fw-bold text-dark">Rp {{ number_format($product->current_price, 0, ',', '.') }}</td>
                            <td class="py-3">
                                <span class="badge bg-{{ $product->stock_quantity <= 5 ? 'danger' : 'success' }} bg-opacity-10 text-{{ $product->stock_quantity <= 5 ? 'danger' : 'success' }} rounded-pill px-2">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'warning' }} bg-opacity-10 text-{{ $product->status == 'active' ? 'success' : 'warning' }} rounded-pill px-3 py-2 small fw-bold">
                                    {{ strtoupper($product->status) }}
                                </span>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="btn-group">
                                    <a href="{{ route('vendor.products.edit', $product) }}" class="btn btn-sm btn-light border rounded-pill px-3 me-2">
                                        <i class="fas fa-edit text-primary me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fas fa-trash text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-muted opacity-25 mb-3"></i>
                                <p class="text-muted">Anda belum memiliki produk.</p>
                                <a href="{{ route('vendor.products.create') }}" class="btn btn-primary rounded-pill px-4">Tambah Sekarang</a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    @if($products->hasPages())
    <div class="mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
