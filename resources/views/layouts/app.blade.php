{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --border-radius-lg: 1.25rem;
            --shadow-premium: 0 1rem 3rem rgba(0,0,0,.08);
        }

        body {
            font-family: 'Poppins', 'Figtree', sans-serif;
            background-color: #fcfcfc;
            color: #2b2b2b;
        }

        .navbar {
            padding: 1rem 0;
            transition: all 0.3s;
        }

        /* Logo / Brand styling */
        .navbar-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.05rem;
        }

        .site-logo {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(13,110,253,1), rgba(0,67,168,1));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            box-shadow: 0 6px 18px rgba(13,110,253,0.18);
            flex-shrink: 0;
        }

        .brand-text {
            color: #111827;
            font-size: 1rem;
            letter-spacing: -0.2px;
        }

        .rounded-4 { border-radius: var(--border-radius-lg) !important; }
        .shadow-premium { box-shadow: var(--shadow-premium) !important; }
        
        .btn-primary {
            border-radius: 50rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.3);
        }

        .card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: 0 2px 15px rgba(0,0,0,0.03);
            transition: all 0.3s;
        }

        /* Feature card tweaks to match brand weight */
        .feature-card {
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
            transition: transform .25s ease, box-shadow .25s ease;
            background: #fff;
        }

        .feature-card h6 { font-weight: 700; color: #111827; }
        .feature-card p { color: #6b7280; }

        .feature-icon {
            width: 56px; height: 56px; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center;
            background: rgba(13,110,253,0.08); color: var(--primary-color); font-size: 1.4rem;
            box-shadow: 0 6px 18px rgba(13,110,253,0.06);
        }

        /* Footer improvements */
        footer {
            background: #0f1720 !important;
            border-radius: 1.5rem 1.5rem 0 0;
            margin-top: 5rem;
            color: #cbd5e1;
            padding-top: 3rem;
            padding-bottom: 2.5rem;
        }

        .footer-brand {
            display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;
        }

        .footer-brand .small { color: #94a3b8; }

        /* Ensure brand text is readable in footer */
        footer .brand-text {
            color: #e6eef8; /* light text for contrast */
            font-weight: 600;
        }

        footer .site-logo {
            width:44px; height:44px; border-radius:8px; display:inline-flex; align-items:center; justify-content:center;
            background: linear-gradient(135deg,#0d6efd,#0043a8);
            box-shadow: 0 8px 30px rgba(13,110,253,0.25);
            color: #ffffff;
        }

        .footer-links a { display: block; color: #cbd5e1; text-decoration: none; margin-bottom: 0.35rem; }
        .footer-links a:hover { color: white; }

        .footer-contact li { list-style: none; margin-bottom: 0.5rem; color: #cbd5e1; }
        .footer-contact li i { color: #93c5fd; margin-right: 0.6rem; }

        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem; margin-top: 1.25rem; }

        /* Ensure all footer text is readable against dark background */
        footer, footer * { color: #cbd5e1 !important; }
        footer h5 { color: #f8fafc !important; }
        footer .brand-text { color: #f8fafc !important; }
        footer .footer-links a { color: #cbd5e1 !important; }
        footer .footer-links a:hover { color: #ffffff !important; }

        /* Specific fix: footer small brand description should be readable */
        footer .footer-brand .small.text-muted { color: #cbd5e1 !important; opacity: 1 !important; }

        .nav-link {
            font-weight: 500;
            color: #555 !important;
            padding: 0.5rem 1rem !important;
        }

        .nav-link.active {
            color: var(--primary-color) !important;
        }
        

        .cart-count {
            position: absolute;
            top: 2px;
            right: 0;
            background: #ff4d4d;
            color: white;
            border-radius: 50%;
            min-width: 18px;
            height: 18px;
            font-size: 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .search-container {
            background: #f1f3f5;
            border-radius: 50rem;
            padding: 2px 5px;
        }

        .search-container input {
            background: transparent;
            border: none;
            box-shadow: none !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <span class="site-logo">
                    <i class="fas fa-store fa-lg"></i>
                </span>
                <span class="brand-text">{{ config('app.name', 'E-Commerce') }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}" 
                        href="{{ route('products.index') }}">
                            <i class="fas fa-box me-1"></i> Products
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.featured') ? 'active' : '' }}" 
                        href="{{ route('products.featured') }}">
                            <i class="fas fa-star me-1"></i> Featured
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.new-arrivals') ? 'active' : '' }}" 
                        href="{{ route('products.new-arrivals') }}">
                            <i class="fas fa-newspaper me-1"></i> New Arrivals
                        </a>
                    </li>
                </ul>
                <!-- Search Form -->
                <form class="d-flex mx-auto" action="{{ route('products.index') }}" method="GET">
                    <div class="search-container d-flex align-items-center" style="min-width: 350px;">
                        <input type="text" name="search" class="form-control px-3" placeholder="Apa yang Anda cari hari ini?" 
                               value="{{ request('search') }}">
                        <button class="btn btn-primary rounded-circle p-2 ms-1" type="submit" style="width: 38px; height: 38px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <ul class="navbar-nav">
                    <!-- Cart -->
                    @auth
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}" id="cart-link">
                            <i class="fas fa-shopping-cart"></i>
                            @if(auth()->user()->cart && auth()->user()->cart->total_quantity > 0)
                            <span class="cart-count">{{ auth()->user()->cart->total_quantity }}</span>
                            @endif
                        </a>
                    </li>
                    
                    <!-- Wishlist -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wishlist.index') }}">
                            <i class="far fa-heart"></i>
                        </a>
                    </li>
                    @endauth
                    
                    <!-- User Dropdown + Logout button -->
                    @auth
                    <li class="nav-item me-2 d-none d-md-block">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm rounded-pill">
                                <i class="fas fa-sign-out-alt me-1"></i> Logout
                            </button>
                        </form>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                           data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i> {{ Auth::user()->name }}
                            @if(Auth::user()->type === 'admin')
                            <span class="badge bg-danger ms-1">Admin</span>
                            @elseif(Auth::user()->type === 'vendor')
                            <span class="badge bg-warning ms-1">Vendor</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i> Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="d-md-none">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                        <div class="col-md-4">
                            <div class="footer-brand">
                                <span class="site-logo" style="width:44px;height:44px;border-radius:8px;background:linear-gradient(135deg,#0d6efd,#0043a8);display:inline-flex;align-items:center;justify-content:center;color:#fff;box-shadow:0 6px 18px rgba(13,110,253,0.18)">
                                    <i class="fas fa-store"></i>
                                </span>
                                <div>
                                    <div class="brand-text">{{ config('app.name', 'Ecommerce Store') }}</div>
                                    <div class="small text-muted">Your trusted online shopping destination.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-white">Quick Links</h5>
                            <div class="footer-links">
                                <a href="{{ route('products.index') }}">Products</a>
                                <a href="{{ route('products.featured') }}">Featured</a>
                                <a href="{{ route('products.new-arrivals') }}">New Arrivals</a>
                                @auth
                                <a href="{{ route('dashboard') }}">Dashboard</a>
                                @endauth
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h5 class="text-white">Contact Us</h5>
                            <ul class="footer-contact">
                                <li><i class="fas fa-envelope"></i> support@ecommerce.com</li>
                                <li><i class="fas fa-phone"></i> (021) 1234-5678</li>
                                <li><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</li>
                            </ul>
                        </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Update cart count dynamically
        function updateCartCount(count) {
            const cartCountEl = document.querySelector('.cart-count');
            if (cartCountEl) {
                cartCountEl.textContent = count;
                if (count <= 0) {
                    cartCountEl.style.display = 'none';
                } else {
                    cartCountEl.style.display = 'flex';
                }
            } else if (count > 0) {
                // Create cart count badge if doesn't exist
                const cartLink = document.getElementById('cart-link');
                if (cartLink) {
                    const badge = document.createElement('span');
                    badge.className = 'cart-count';
                    badge.textContent = count;
                    cartLink.appendChild(badge);
                }
            }
        }
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Toast Notification System
        function showToast(type, message) {
            const toastContainer = document.getElementById('toast-container') || createToastContainer();
            
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-bg-${type === 'error' ? 'danger' : type} border-0 mb-2`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            const bsToast = new bootstrap.Toast(toast, { delay: 3000 });
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => toast.remove());
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            container.style.zIndex = '1080';
            document.body.appendChild(container);
            return container;
        }
    </script>
    
    @stack('scripts')
</body>
</html>
