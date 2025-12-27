@extends('layouts.admin')

@section('title', 'Manajemen Produk - ' . config('app.name'))

@section('admin_content')
    <!-- Header Admin -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Manajemen Produk</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produk</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
                <i class="fas fa-plus me-2"></i>Tambah Produk Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeInDown">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fa-lg"></i>
                <div class="fw-bold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0">
            <div class="row align-items-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="mb-0 fw-bold">Daftar Semua Produk</h5>
                </div>
                <div class="col-md-6">
                    <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex gap-2 justify-content-md-end">
                        <div class="input-group bg-light rounded-pill border px-3" style="max-width: 300px;">
                            <span class="input-group-text bg-transparent border-0 opacity-50"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control bg-transparent border-0 shadow-none small" placeholder="Cari nama atau SKU..." value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle custom-admin-table mb-0">
                    <thead class="bg-light text-muted x-small">
                        <tr>
                            <th class="ps-4">GAMBAR</th>
                            <th>INFO PRODUK</th>
                            <th>SKU</th>
                            <th>HARGA</th>
                            <th>STOK</th>
                            <th>PENJUAL</th>
                            <th>STATUS</th>
                            <th class="pe-4 text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="ps-4">
                                <div class="rounded-3 border overflow-hidden bg-light" style="width: 54px; height: 54px;">
                                    <img src="{{ $product->thumbnail_url }}" alt="{{ $product->name }}" 
                                         class="w-100 h-100 object-fit-cover shadow-xs">
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $product->name }}</div>
                                <div class="x-small text-muted">{{ $product->brand->name ?? 'Tanpa Merek' }}</div>
                            </td>
                            <td><code class="x-small text-primary fw-bold">{{ $product->sku }}</code></td>
                            <td>
                                <div class="fw-bold text-dark">Rp{{ number_format($product->current_price, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($product->inventory)
                                    @php
                                        $qty = $product->inventory->quantity;
                                        $qtyColor = ($qty <= 5) ? 'danger' : (($qty <= 20) ? 'warning' : 'success');
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="bg-{{ $qtyColor }} rounded-circle me-2" style="width: 8px; height: 8px;"></div>
                                        <span class="fw-bold text-{{ $qtyColor }}">{{ $qty }}</span>
                                    </div>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>
                                @if($product->vendor)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">
                                            <i class="fas fa-store x-small"></i>
                                        </div>
                                        <span class="small fw-bold">{{ $product->vendor->name }}</span>
                                    </div>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 small fw-bold">SYSTEM</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusIcons = [
                                        'active' => ['class' => 'success', 'icon' => 'check-circle'],
                                        'draft' => ['class' => 'warning', 'icon' => 'clock'],
                                        'inactive' => ['class' => 'secondary', 'icon' => 'pause-circle'],
                                        'archived' => ['class' => 'danger', 'icon' => 'archive']
                                    ];
                                    $status = $statusIcons[$product->status] ?? ['class' => 'secondary', 'icon' => 'question-circle'];
                                @endphp
                                <span class="badge bg-{{ $status['class'] }} bg-opacity-10 text-{{ $status['class'] }} rounded-pill px-3 py-2 small fw-bold">
                                    <i class="fas fa-{{ $status['icon'] }} me-1"></i> {{ strtoupper($product->status) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Preview Toko">
                                        <i class="fas fa-external-link-alt text-muted small"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Edit Produk">
                                        <i class="fas fa-edit text-primary small"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Hapus">
                                            <i class="fas fa-trash text-danger small"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-5">
                                    <i class="fas fa-box-open fa-4x text-muted opacity-25 mb-4"></i>
                                    <h5 class="text-muted">Belum ada produk ditemukan.</h5>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary rounded-pill mt-3 px-4">Tambah Sekarang</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($products->hasPages())
            <div class="card-footer bg-white py-4 px-4 border-0 border-top">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>

<style>
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
    .x-small { font-size: 0.7rem; letter-spacing: 0.8px; font-weight: 800; }
    .custom-admin-table thead th {
        font-weight: 800;
        letter-spacing: 1px;
        border-bottom: 0;
        padding-top: 20px;
        padding-bottom: 15px;
        text-transform: uppercase;
    }
    .custom-admin-table tbody td {
        padding-top: 18px;
        padding-bottom: 18px;
        border-bottom-color: #f1f3f5;
    }
    .btn-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); background: white !important; box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; }
    .object-fit-cover { object-fit: cover; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .ls-1 { letter-spacing: 1px; }
</style>
@endsection
