<?php
// app/Http/Controllers/ReviewController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10',
        ]);

        $product = Product::findOrFail($request->product_id);
        $userId = Auth::id();

        if (!$product->canUserReview($userId)) {
            return back()->with('error', 'Anda tidak dapat memberikan ulasan untuk produk ini.');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => false, // Require admin approval by default
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda! Ulasan Anda akan ditampilkan setelah disetujui admin.');
    }
}
