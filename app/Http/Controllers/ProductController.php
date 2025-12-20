<?php
// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters
     */
    public function index(Request $request): View|JsonResponse
    {
        // Build query with eager loading
        $query = Product::with(['brand', 'categories', 'mainImage', 'inventory'])
                       ->active()
                       ->withAvg('approvedReviews', 'rating')
                       ->withCount('approvedReviews');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Apply brand filter
        if ($request->has('brand') && $request->brand) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        // Apply price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->whereHas('activePrice', function($q) use ($request) {
                $q->where('base_price', '>=', $request->min_price);
            });
        }
        
        if ($request->has('max_price') && $request->max_price) {
            $query->whereHas('activePrice', function($q) use ($request) {
                $q->where('base_price', '<=', $request->max_price);
            });
        }

        // Apply availability filter
        if ($request->has('availability')) {
            switch ($request->availability) {
                case 'in_stock':
                    $query->inStock();
                    break;
                case 'out_of_stock':
                    $query->whereHas('inventory', function($q) {
                        $q->where('stock_status', 'out_of_stock');
                    });
                    break;
                case 'pre_order':
                    $query->whereHas('inventory', function($q) {
                        $q->where('stock_status', 'pre_order');
                    });
                    break;
            }
        }

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $sortOptions = [
            'latest' => ['created_at', 'desc'],
            'price_low' => ['current_price', 'asc'],
            'price_high' => ['current_price', 'desc'],
            'name_asc' => ['name', 'asc'],
            'name_desc' => ['name', 'desc'],
            'popular' => ['view_count', 'desc'],
        ];

        if (array_key_exists($sortBy, $sortOptions)) {
            [$sortField, $sortDirection] = $sortOptions[$sortBy];
            
            if ($sortField === 'current_price') {
                // Custom sorting for current price
                $query->join('prices', function($join) {
                    $join->on('products.id', '=', 'prices.product_id')
                         ->where('prices.is_active', true)
                         ->whereNull('prices.variant_id');
                })
                ->select('products.*')
                ->orderByRaw('CASE WHEN prices.sale_price IS NOT NULL AND NOW() BETWEEN COALESCE(prices.sale_start_date, NOW()) AND COALESCE(prices.sale_end_date, NOW()) THEN prices.sale_price ELSE prices.base_price END ' . $sortDirection);
            } elseif ($sortField === 'view_count') {
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            $query->latest();
        }

        // Get paginated results
        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        // Get filter data
        $categories = Category::active()->withCount(['products' => function($query) {
            $query->active();
        }])->get();
        $brands = Brand::active()->withCount(['products' => function($query) {
            $query->active();
        }])->get();
        
        $priceRange = [
            'min' => Product::join('prices', function($join) {
                    $join->on('products.id', '=', 'prices.product_id')
                         ->where('prices.is_active', true)
                         ->whereNull('prices.variant_id');
                })
                ->min('prices.base_price'),
            'max' => Product::join('prices', function($join) {
                    $join->on('products.id', '=', 'prices.product_id')
                         ->where('prices.is_active', true)
                         ->whereNull('prices.variant_id');
                })
                ->max('prices.base_price'),
        ];

        // If API request, return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $products,
                'filters' => [
                    'categories' => $categories,
                    'brands' => $brands,
                    'price_range' => $priceRange,
                ],
                'meta' => [
                    'total' => $products->total(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                ]
            ]);
        }

        // Return view for web
        return view('products.index', compact('products', 'categories', 'brands', 'priceRange'));
    }

    /**
     * Display the specified product
     */
    public function show(Request $request, Product $product): View|JsonResponse
    {
        // Increment view count
        $product->increment('view_count');
        
        // Load related data with eager loading
        $product->load([
            'brand',
            'categories',
            'images' => function($query) {
                $query->orderBy('sort_order')->orderBy('id');
            },
            'variants' => function($query) {
                $query->with(['inventory', 'attributeValues.attribute']);
            },
            'approvedReviews' => function($query) {
                $query->with('user')->latest()->take(10);
            },
            'activePrice',
            'inventory',
            'variants.activePrice',
            'variants.inventory',
        ]);

        // Get average rating
        $averageRating = $product->approvedReviews()->avg('rating');
        $reviewCount = $product->approvedReviews()->count();

        // Get related products
        $relatedProducts = Product::whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->with(['mainImage', 'activePrice'])
            ->take(4)
            ->get();

        // Get available variants with attributes
        $availableVariants = $product->variants->filter(function($variant) {
            return $variant->isInStock();
        });

        // Group variants by attributes for configurable products
        $variantAttributes = [];
        if ($product->type === 'configurable' && $availableVariants->isNotEmpty()) {
            $attributeGroups = [];
            
            foreach ($availableVariants as $variant) {
                foreach ($variant->attributeValues as $attributeValue) {
                    $attributeCode = $attributeValue->attribute->code;
                    $attributeName = $attributeValue->attribute->name;
                    
                    if (!isset($attributeGroups[$attributeCode])) {
                        $attributeGroups[$attributeCode] = [
                            'name' => $attributeName,
                            'code' => $attributeCode,
                            'values' => [],
                        ];
                    }
                    
                    if (!in_array($attributeValue->id, $attributeGroups[$attributeCode]['values'])) {
                        $attributeGroups[$attributeCode]['values'][] = [
                            'id' => $attributeValue->id,
                            'value' => $attributeValue->value,
                            'color_code' => $attributeValue->color_code,
                            'image_url' => $attributeValue->image_url,
                        ];
                    }
                }
            }
            
            $variantAttributes = array_values($attributeGroups);
        }

        // If API request, return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'data' => $product,
                'average_rating' => $averageRating,
                'review_count' => $reviewCount,
                'related_products' => $relatedProducts,
                'variant_attributes' => $variantAttributes,
                'available_variants' => $availableVariants,
            ]);
        }

        return view('products.show', compact(
            'product', 
            'averageRating', 
            'reviewCount', 
            'relatedProducts',
            'variantAttributes',
            'availableVariants'
        ));
    }

    /**
     * Get product attributes for configurable products
     */
    public function getAttributes(Request $request, Product $product): JsonResponse
    {
        if ($product->type !== 'configurable') {
            return response()->json([
                'message' => 'Product is not configurable',
                'data' => []
            ]);
        }

        $attributes = $product->variants()
            ->with(['attributeValues.attribute'])
            ->get()
            ->flatMap(function ($variant) {
                return $variant->attributeValues->map(function ($value) {
                    return [
                        'attribute_id' => $value->attribute->id,
                        'attribute_name' => $value->attribute->name,
                        'attribute_code' => $value->attribute->code,
                        'value_id' => $value->id,
                        'value' => $value->value,
                        'color_code' => $value->color_code,
                        'image_url' => $value->image_url,
                    ];
                });
            })
            ->unique('value_id')
            ->values()
            ->groupBy('attribute_code')
            ->map(function ($values, $code) {
                return [
                    'code' => $code,
                    'name' => $values->first()['attribute_name'],
                    'values' => $values->map(function ($value) {
                        return [
                            'id' => $value['value_id'],
                            'value' => $value['value'],
                            'color_code' => $value['color_code'],
                            'image_url' => $value['image_url'],
                        ];
                    })->values()
                ];
            })
            ->values();

        return response()->json([
            'data' => $attributes
        ]);
    }

    /**
     * Get variant by selected attributes
     */
    public function getVariant(Request $request, Product $product): JsonResponse
    {
        $attributeValueIds = $request->input('attribute_values', []);
        
        $variant = $product->variants()
            ->whereHas('attributeValues', function ($query) use ($attributeValueIds) {
                $query->whereIn('product_attribute_values.id', $attributeValueIds);
            }, '=', count($attributeValueIds))
            ->with(['inventory', 'activePrice'])
            ->first();

        if (!$variant) {
            return response()->json([
                'message' => 'Variant not found for selected attributes',
                'data' => null
            ], 404);
        }

        return response()->json([
            'data' => $variant
        ]);
    }

    /**
     * Get products by category
     */
    public function byCategory(Category $category, Request $request): View|JsonResponse
    {
        $query = $category->products()
            ->active()
            ->with(['brand', 'mainImage', 'activePrice', 'inventory']);

        // Apply sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['created_at', 'name', 'price'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $products,
                'category' => $category,
                'meta' => [
                    'total' => $products->total(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                ]
            ]);
        }

        return view('products.category', compact('products', 'category'));
    }

    /**
     * Get featured products
     */
    public function featured(Request $request): View|JsonResponse
    {
        $products = Product::featured()
            ->active()
            ->inStock()
            ->with(['brand', 'mainImage', 'activePrice'])
            ->latest()
            ->take(8)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $products
            ]);
        }

        return view('products.featured', compact('products'));
    }

    /**
     * Get new arrival products
     */
    public function newArrivals(Request $request): View|JsonResponse
    {
        $products = Product::new()
            ->active()
            ->with(['brand', 'mainImage', 'activePrice'])
            ->latest()
            ->take(8)
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $products
            ]);
        }

        return view('products.new-arrivals', compact('products'));
    }

    /**
     * Search products with autocomplete
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('short_description', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->active()
            ->with(['mainImage', 'activePrice'])
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->current_price,
                    'formatted_price' => $product->activePrice?->formatted_price ?? 'Rp 0',
                    'image' => $product->thumbnail_url,
                    'in_stock' => $product->isInStock(),
                    'url' => route('products.show', $product),
                ];
            });

        return response()->json([
            'data' => $products
        ]);
    }
}