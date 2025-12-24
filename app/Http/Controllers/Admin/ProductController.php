<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Inventory;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'categories', 'inventory', 'activePrice'])
            ->latest()
            ->paginate(10);
            
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
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
            'is_featured' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
            'image' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $product = new Product();
            $product->name = $validated['name'];
            $product->slug = Str::slug($validated['name']);
            $product->sku = $validated['sku'];
            $product->brand_id = $validated['brand_id'] ?: null;
            $product->short_description = $validated['short_description'];
            $product->description = $validated['description'];
            $product->status = $validated['status'];
            $product->is_featured = $request->has('is_featured');
            $product->is_new = $request->has('is_new');
            $product->type = 'simple'; // Default to simple for now
            $product->save();

            // Categories
            $product->categories()->sync($request->input('categories', []));

            // Price
            Price::create([
                'product_id' => $product->id,
                'base_price' => $validated['price'],
                'currency' => 'IDR',
                'is_active' => true
            ]);

            // Inventory
            Inventory::create([
                'product_id' => $product->id,
                'sku' => $validated['sku'],
                'quantity' => $validated['quantity'],
                'stock_status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock'
            ]);

            // Image Upload
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => true,
                    'sort_order' => 0
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product creation failed: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->all()
            ]);
            return back()->with('error', 'Error creating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product->load(['categories', 'activePrice', 'inventory']);
        
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
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
            'is_featured' => 'sometimes|boolean',
            'is_new' => 'sometimes|boolean',
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
            $product->is_featured = $request->has('is_featured');
            $product->is_new = $request->has('is_new');
            $product->save();

            // Categories
            $product->categories()->sync($request->input('categories', []));

            // Price - Update existing active price or create new one
            $currentPrice = $product->activePrice;
            if ($currentPrice) {
                $currentPrice->update(['base_price' => $validated['price']]);
            } else {
                Price::create([
                    'product_id' => $product->id,
                    'base_price' => $validated['price'],
                    'currency' => 'IDR',
                    'is_active' => true
                ]);
            }

            // Inventory
            $inventory = $product->inventory;
            if ($inventory) {
                $inventory->update([
                    'sku' => $validated['sku'],
                    'quantity' => $validated['quantity'],
                    'stock_status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock'
                ]);
            } else {
                Inventory::create([
                    'product_id' => $product->id,
                    'sku' => $validated['sku'],
                    'quantity' => $validated['quantity'],
                    'stock_status' => $validated['quantity'] > 0 ? 'in_stock' : 'out_of_stock'
                ]);
            }

            // Image Upload
            if ($request->hasFile('image')) {
                // For now, let's just add new one as main and demote others
                $product->images()->update(['is_main' => false]);
                
                $path = $request->file('image')->store('products', 'public');
                $product->images()->create([
                    'image_url' => $path,
                    'is_main' => true,
                    'sort_order' => 0
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product update failed: ' . $e->getMessage(), [
                'exception' => $e,
                'input' => $request->all()
            ]);
            return back()->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }
}
