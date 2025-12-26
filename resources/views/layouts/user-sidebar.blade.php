<!-- {{-- resources/views/layouts/user-sidebar.blade.php --}} -->
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
                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2 border border-3 border-white shadow-sm" title="Verified Customer">
                    <i class="fas fa-check text-white fa-sm"></i>
                </span>
            </div>
            <h5 class="mt-3 mb-1 fw-bold text-dark">{{ $user->name }}</h5>
            <p class="text-muted small mb-3">{{ $user->email }}</p>
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 fw-bold small text-uppercase">{{ $user->type }}</span>
        </div>

        <!-- Nav Links -->
        <div class="p-3">
            <div class="list-group list-group-flush admin-nav">
                <a href="{{ route('dashboard') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-tachometer-alt"></i></div>
                    <span class="fw-bold">Dashboard</span>
                </a>
                
                <a href="{{ route('profile.edit') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('profile.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-user-edit"></i></div>
                    <span class="fw-bold">Ubah Profil</span>
                </a>

                <a href="{{ route('orders.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('orders.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-shopping-bag"></i></div>
                    <span class="fw-bold">Pesanan Saya</span>
                    @if(isset($total_orders))
                    <span class="badge bg-primary ms-auto rounded-pill px-2">{{ $total_orders }}</span>
                    @endif
                </a>

                <a href="{{ route('wishlist.index') }}" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->routeIs('wishlist.*') ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-heart"></i></div>
                    <span class="fw-bold">Wishlist</span>
                    @if(isset($wishlist_count))
                    <span class="badge bg-danger ms-auto rounded-pill px-2">{{ $wishlist_count }}</span>
                    @endif
                </a>

                @if($user->type !== 'admin')
                <a href="{{ route('profile.edit') }}#addresses" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->url() == route('profile.edit') && request()->hash == '#addresses' ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-map-marker-alt"></i></div>
                    <span class="fw-bold">Alamat</span>
                </a>
                @endif

                <a href="{{ route('profile.edit') }}#security" 
                   class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center {{ request()->url() == route('profile.edit') && request()->hash == '#security' ? 'active shadow-sm' : '' }}">
                    <div class="nav-icon-box me-3"><i class="fas fa-lock"></i></div>
                    <span class="fw-bold">Ganti Password</span>
                </a>

                <div class="my-3 border-top opacity-50"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action mb-2 rounded-3 border-0 d-flex align-items-center text-danger">
                        <div class="nav-icon-box me-3 bg-danger bg-opacity-10 text-danger"><i class="fas fa-sign-out-alt"></i></div>
                        <span class="fw-bold">Keluar</span>
                    </button>
                </form>
            </div>
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
    .shadow-premium { box-shadow: 0 1rem 3rem rgba(0,0,0,.08) !important; }
</style>
