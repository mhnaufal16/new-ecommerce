<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'user' => $user,
            'total_products' => Product::count(),
            'total_orders' => $user->type === 'admin' 
                ? Order::count() 
                : $user->orders()->count(),
            'recent_orders' => $user->type === 'admin'
                ? Order::latest()->take(5)->get()
                : $user->orders()->latest()->take(5)->get(),
            'total_users' => User::count(),
        ];
        
        return view('dashboard', $data);
    }
}