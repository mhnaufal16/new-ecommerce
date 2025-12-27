<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Inventory;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('vendor_id', auth()->id())
            ->with(['brand', 'categories', 'inventory', 'activePrice'])
            ->latest()
            ->paginate(10);
            
        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('vendor.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'brand_id' => 'nullable|exists:brands,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive',
            'image' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $validated['name'];
            $product->slug = Str::slug($validated['name']);
            $product->sku = $validated['sku'];
            $product->brand_id = $validated['brand_id'] ?: null;
            $product->vendor_id = auth()->id(); // Always set to current vendor
            $product->short_description = $validated['short_description'];
            $product->description = $validated['description'];
            $product->status = $validated['status'];
            $product->type = 'simple';
            $product->save();

            $product->categories()->sync($request->input('categories', []));

            Price::create([
                'product_id' => $product->id,
                'base_price' => $validated['price'],
                'currency' => 'IDR',
                'is_active' => true
            ]);

            Inventory::create([
                'product_id' => $product->id,
                'sku' => $validated['sku'],
                'quantity' => $validated['quantity'],
                'stock_status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock'
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => true,
                    'sort_order' => 0
                ]);
            }

            DB::commit();
            return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor product creation failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah produk: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        // Security check
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $categories = Category::all();
        $brands = Brand::all();
        $product->load(['categories', 'activePrice', 'inventory']);
        
        return view('vendor.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        // Security check
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'brand_id' => 'nullable|exists:brands,id',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:draft,active,inactive',
            'image' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $product->name = $validated['name'];
            $product->slug = Str::slug($validated['name']);
            $product->sku = $validated['sku'];
            $product->brand_id = $validated['brand_id'] ?: null;
            $product->short_description = $validated['short_description'];
            $product->description = $validated['description'];
            $product->status = $validated['status'];
            $product->save();

            $product->categories()->sync($request->input('categories', []));

            if ($product->activePrice) {
                $product->activePrice->update(['base_price' => $validated['price']]);
            }

            if ($product->inventory) {
                $product->inventory->update([
                    'sku' => $validated['sku'],
                    'quantity' => $validated['quantity'],
                    'stock_status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock'
                ]);
            }

            if ($request->hasFile('image')) {
                $product->images()->update(['is_main' => false]);
                $path = $request->file('image')->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => true,
                    'sort_order' => 0
                ]);
            }

            DB::commit();
            return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Vendor product update failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui produk: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'Akses ditolak.');
        }

        try {
            $product->delete();
            return redirect()->route('vendor.products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}
