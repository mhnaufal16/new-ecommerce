<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .welcome-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .welcome-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        .welcome-body {
            padding: 40px;
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 20px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="welcome-card">
                    <!-- Header -->
                    <div class="welcome-header">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-store me-2"></i>{{ config('app.name', 'E-Commerce Store') }}
                        </h1>
                        <p class="lead mb-0">Your complete e-commerce solution built with Laravel</p>
                    </div>

                    <!-- Body -->
                    <div class="welcome-body">
                        <div class="row mb-5">
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <h4>Product Catalog</h4>
                                    <p class="text-muted">Browse thousands of products with advanced filtering</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                    <h4>Secure Checkout</h4>
                                    <p class="text-muted">Multiple payment methods with secure transactions</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <h4>Fast Shipping</h4>
                                    <p class="text-muted">Integrated with major shipping providers in Indonesia</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h4>Admin Dashboard</h4>
                                    <p class="text-muted">Complete management system for store owners</p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="text-center">
                            <div class="d-grid gap-3 d-md-block">
                                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg me-md-3 mb-3">
                                    <i class="fas fa-shopping-bag me-2"></i>Browse Products
                                </a>
                                
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg mb-3">
                                        <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg me-md-3 mb-3">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-outline-success btn-lg mb-3">
                                        <i class="fas fa-user-plus me-2"></i>Register
                                    </a>
                                @endauth
                            </div>
                            
                            <!-- Demo Credentials -->
                            @guest
                            <div class="mt-4 p-3 bg-light rounded">
                                <p class="mb-2 text-muted">
                                    <strong>Demo Credentials:</strong>
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-user-shield me-2 text-primary"></i>
                                            Admin: <code>admin@tokoecommerce.com</code>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1">
                                            <i class="fas fa-key me-2 text-primary"></i>
                                            Password: <code>password123</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endguest
                        </div>

                        <!-- Quick Stats -->
                        <div class="row mt-5 pt-4 border-top">
                            @php
                                use App\Models\Product;
                                use App\Models\User;
                                use App\Models\Order;
                                
                                $totalProducts = Product::count();
                                $totalUsers = User::count();
                                $totalOrders = Order::count();
                            @endphp
                            <div class="col-md-4 text-center">
                                <h2 class="text-primary">{{ $totalProducts }}</h2>
                                <p class="text-muted">Products Available</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h2 class="text-success">{{ $totalUsers }}</h2>
                                <p class="text-muted">Registered Users</p>
                            </div>
                            <div class="col-md-4 text-center">
                                <h2 class="text-warning">{{ $totalOrders }}</h2>
                                <p class="text-muted">Orders Processed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-4">
                    <p class="text-white">
                        &copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce Store') }}. 
                        Built with <i class="fas fa-heart text-danger"></i> using Laravel.
                    </p>
                    <div class="mt-2">
                        <a href="#" class="text-white me-3"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>