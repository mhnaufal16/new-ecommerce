<?php
// app/Http\Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->type === 'admin') {
            return $this->adminDashboard($user);
        } elseif ($user->type === 'vendor') {
            return $this->vendorDashboard($user);
        } else {
            return $this->customerDashboard($user);
        }
    }
    
    private function adminDashboard($user)
    {
        $data = [
            'user' => $user,
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_revenue' => Order::where('status', 'completed')->sum('grand_total'),
            'recent_orders' => Order::with('user')->latest()->take(10)->get(),
            'low_stock_products' => Product::whereHas('inventory', function($q) {
                $q->where('quantity', '<=', 5)->where('quantity', '>', 0);
            })->take(5)->get(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'dashboard_type' => 'admin'
        ];
        
        return view('dashboard.admin', $data);
    }
    
    private function vendorDashboard($user)
    {
        $data = [
            'user' => $user,
            'dashboard_type' => 'vendor',
            'message' => 'Vendor dashboard coming soon...',
        ];
        
        return view('dashboard.vendor', $data);
    }
    
    private function customerDashboard($user)
    {
        $data = [
            'user' => $user,
            'dashboard_type' => 'customer',
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->whereIn('status', ['pending', 'processing'])->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
            'wishlist_count' => $user->wishlists()->count(),
            'recent_orders' => $user->orders()->with('items.product')->latest()->take(5)->get(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('grand_total'),
        ];
        
        return view('dashboard.customer', $data);
    }
}