<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $wishlists = $user->wishlists()->with('product')->latest()->get();
        
        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = Auth::user();
        
        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ]);
            $message = 'Produk ditambahkan ke wishlist';
        } else {
            $message = 'Produk sudah ada di wishlist';
        }

        if ($request->ajax()) {
            return response()->json([
                'message' => $message,
                'wishlist_count' => $user->wishlists()->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== Auth::id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist');
    }

    /**
     * Toggle item in wishlist.
     */
    public function toggle($productId)
    {
        $user = Auth::user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $inWishlist = true;
        }

        return response()->json([
            'in_wishlist' => $inWishlist,
            'message' => $inWishlist ? 'Produk ditambahkan ke wishlist' : 'Produk dihapus dari wishlist',
            'wishlist_count' => $user->wishlists()->count(),
        ]);
    }
}
