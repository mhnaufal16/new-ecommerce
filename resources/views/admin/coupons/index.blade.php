@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fas fa-tags me-2 text-primary"></i>Kelola Kupon</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-plus me-2"></i>Buat Kupon Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Status</label>
                    <select name="status" class="form-select rounded-3">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tipe Diskon</label>
                    <select name="type" class="form-select rounded-3">
                        <option value="">Semua Tipe</option>
                        <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Persentase</option>
                        <option value="fixed_amount" {{ request('type') === 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                        <option value="free_shipping" {{ request('type') === 'free_shipping' ? 'selected' : '' }}>Gratis Ongkir</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Cari</label>
                    <input type="text" name="search" class="form-control rounded-3" placeholder="Kode atau nama kupon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary rounded-3 w-100">
                        <i class="fas fa-filter me-1"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Coupons Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Kode</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>Diskon</th>
                            <th>Min. Belanja</th>
                            <th>Penggunaan</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-dark bg-opacity-10 text-dark fw-bold px-3 py-2 rounded-pill">
                                    {{ $coupon->code }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $coupon->name }}</div>
                                @if($coupon->description)
                                <small class="text-muted">{{ Str::limit($coupon->description, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->discount_type === 'percentage')
                                    <span class="badge bg-info bg-opacity-10 text-info">Persentase</span>
                                @elseif($coupon->discount_type === 'fixed_amount')
                                    <span class="badge bg-success bg-opacity-10 text-success">Fixed</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary">Gratis Ongkir</span>
                                @endif
                            </td>
                            <td class="fw-bold text-primary">{{ $coupon->formatted_discount_value }}</td>
                            <td>{{ $coupon->formatted_min_order_amount }}</td>
                            <td>
                                @if($coupon->usage_limit)
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted me-2">{{ $coupon->usage_count }}/{{ $coupon->usage_limit }}</small>
                                        <div class="progress flex-grow-1" style="height: 6px; max-width: 60px;">
                                            <div class="progress-bar" style="width: {{ ($coupon->usage_count / $coupon->usage_limit) * 100 }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <small class="text-muted">{{ $coupon->usage_count }} / ∞</small>
                                @endif
                            </td>
                            <td>
                                @if($coupon->is_active && $coupon->isValid())
                                    <span class="badge bg-success bg-opacity-10 text-success">Aktif</span>
                                @elseif($coupon->expires_at && $coupon->expires_at < now())
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Kadaluarsa</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-primary rounded-start-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-{{ $coupon->is_active ? 'warning' : 'success' }}">
                                            <i class="fas fa-{{ $coupon->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kupon ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-end-3">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-tags fa-3x mb-3 opacity-25"></i>
                                <p>Belum ada kupon. <a href="{{ route('admin.coupons.create') }}">Buat sekarang</a></p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($coupons->hasPages())
        <div class="card-footer bg-white border-0 py-3">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
@endsection
