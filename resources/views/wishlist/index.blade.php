@extends('layouts.user')

@section('title', 'Wishlist - ' . config('app.name'))

@section('user_content')
    <div class="row mb-5 align-items-center">
        <div class="col-md-7">
            <h2 class="fw-bold mb-1">Wishlist Saya</h2>
            <p class="text-muted mb-0">Produk-produk impian yang Anda simpan untuk nanti.</p>
        </div>
    </div>

    @if($wishlists->isEmpty())
        <div class="card border-0 shadow-premium rounded-4 py-5 text-center">
            <div class="card-body py-5">
                <div class="bg-light rounded-circle d-inline-block p-4 mb-4">
                    <i class="fas fa-heart fa-4x text-muted opacity-25"></i>
                </div>
                <h5 class="fw-bold">Belum ada produk di wishlist</h5>
                <p class="text-muted mb-4">Tambahkan produk ke wishlist untuk menyimpannya nanti.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-sm">Jelajahi Produk</a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-premium rounded-4 overflow-hidden">
            <div class="card-header bg-white py-4 px-4 border-0">
                <h5 class="mb-0 fw-bold">Daftar Produk Favorit</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle custom-admin-table mb-0">
                        <thead class="bg-light text-muted x-small">
                            <tr>
                                <th class="ps-4">PRODUK</th>
                                <th>BRAND</th>
                                <th>HARGA</th>
                                <th class="pe-4 text-end">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $item)
                                <tr>
                                    <td class="ps-4">
                                        @if($item->product)
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-3 border overflow-hidden bg-light shadow-xs" style="width: 54px; height: 54px;">
                                                    <a href="{{ route('products.show', $item->product) }}">
                                                        <img src="{{ $item->product->thumbnail_url ?? $item->product->main_image_url ?? asset('images/placeholder.png') }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="w-100 h-100 object-fit-cover">
                                                    </a>
                                                </div>
                                                <a href="{{ route('products.show', $item->product) }}" class="fw-bold text-dark text-decoration-none hover-primary">{{ $item->product->name }}</a>
                                            </div>
                                        @else
                                            <span class="text-muted italic small">Produk tidak tersedia</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="small text-muted fw-bold">{{ $item->product?->brand?->name ?? '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">Rp {{ number_format($item->product?->current_price ?? 0, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($item->product)
                                                <a href="{{ route('products.show', $item->product) }}" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Lihat Produk">
                                                    <i class="fas fa-eye text-primary small"></i>
                                                </a>
                                                <form action="{{ route('wishlist.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk dari wishlist?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-light rounded-circle border shadow-xs" title="Hapus dari Wishlist">
                                                        <i class="fas fa-trash text-danger small"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
    .x-small { font-size: 0.75rem; letter-spacing: 0.5px; }
    .custom-admin-table thead th {
        font-weight: 700;
        letter-spacing: 1px;
        border-bottom: 0;
        padding-top: 20px;
        padding-bottom: 15px;
    }
    .custom-admin-table tbody td {
        padding-top: 15px;
        padding-bottom: 15px;
        border-bottom-color: #f8fafc;
    }
    .btn-icon { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; padding: 0; transition: all 0.2s; }
    .btn-icon:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; }
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .hover-primary:hover { color: var(--primary-color) !important; }
</style>
@endpush
