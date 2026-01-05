{{-- resources/views/layouts/admin-sidebar.blade.php --}}
<div class="card border-0 shadow-premium rounded-4 overflow-hidden sticky-top" style="top: 2rem;">
    <div class="card-body p-0">
        <!-- Profile Section -->
        <div class="text-center py-5 bg-light mb-2">
            <div class="position-relative d-inline-block">
                @php
                    $user = auth()->user();
                @endphp
                <img src="{{ $user->avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff' }}" 
                     alt="{{ $user->name }}"
                     class="rounded-circle shadow-sm border border-4 border-white"
                     width="110"
                     height="110"
                     style="object-fit: cover;">
                <span class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 border border-3 border-white shadow-sm" title="Administrator">
                    <i class="fas fa-crown text-white fa-sm"></i>
                </span>
            </div>
            <h5 class="mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->email }}</p>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold small">ADMINISTRATOR</span>
        </div>

        <!-- Nav Links -->
        <div class="p-3">
            <div class="list-group list-group-flush admin-nav">
                <a href="{{ route('dashboard') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-tachometer-alt"></i></div>
                    <span class="fw-bold">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.products.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-box"></i></div>
                    <span class="fw-bold">Produk</span>
                    @if(isset($total_products))
                    <span class="badge bg-primary ms-auto rounded-pill px-2">{{ $total_products }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.brands.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.brands.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-tags"></i></div>
                    <span class="fw-bold">Merek</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.categories.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-folder"></i></div>
                    <span class="fw-bold">Kategori</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.coupons.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-ticket-alt"></i></div>
                    <span class="fw-bold">Kupon</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.orders.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-shopping-bag"></i></div>
                    <span class="fw-bold">Pesanan</span>
                    @if(isset($total_orders))
                    <span class="badge bg-success ms-auto rounded-pill px-2">{{ $total_orders }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.users.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-users"></i></div>
                    <span class="fw-bold">Pelanggan</span>
                    @if(isset($total_users))
                    <span class="badge bg-info ms-auto rounded-pill px-2 text-white">{{ $total_users }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.reviews.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.reviews.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-comment-dots"></i></div>
                    <span class="fw-bold">Ulasan</span>
                    @if(isset($pending_reviews) && $pending_reviews > 0)
                    <span class="badge bg-warning ms-auto rounded-pill px-2 text-white">{{ $pending_reviews }}</span>
                    @endif
                </a>
                <div class="my-3 border-top opacity-50"></div>
                <a href="{{ route('admin.analytics.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.analytics.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-chart-line"></i></div>
                    <span class="fw-bold">Analitik</span>
                </a>
                <a href="{{ route('admin.settings.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('admin.settings.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-sliders-h"></i></div>
                    <span class="fw-bold">Pengaturan</span>
                </a>
            </div>
                
                <div class="my-3 border-top opacity-50"></div>
                
                <a href="{{ route('logout') }}" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center text-danger bg-transparent w-100">
                    <div class="nav-icon-box me-3 bg-danger bg-opacity-10 text-danger"><i class="fas fa-sign-out-alt"></i></div>
                    <span class="fw-bold">Keluar</span>
                </a>
            </div>
    </div>
</div>

<style>
    .nav-icon-box {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 12px;
        color: #adb5bd;
        transition: all 0.2s;
    }
    .admin-nav .active .nav-icon-box {
        background: rgba(255,255,255,0.2) !important;
        color: white !important;
    }
    .admin-nav .list-group-item.active {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: white !important;
    }
    .admin-nav .list-group-item:not(.active):hover {
        background-color: #f8fafc;
        transform: translateX(5px);
    }
    .admin-nav .list-group-item:not(.active):hover .nav-icon-box {
        background-color: var(--primary-color);
        color: white;
    }
    .ls-1 { letter-spacing: 1px; }
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
</style>
