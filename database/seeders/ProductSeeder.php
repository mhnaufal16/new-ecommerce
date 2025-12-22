<?php
// database/seeders/ProductsSeeder.php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Price;
use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Clear existing data using delete() instead of truncate() to avoid FK issues
        \App\Models\Product::query()->delete();
        \App\Models\ProductImage::query()->delete();
        \App\Models\Price::query()->delete();
        \App\Models\Inventory::query()->delete();
        \App\Models\ProductVariant::query()->delete();
        \App\Models\ProductAttribute::query()->delete();
        \App\Models\ProductAttributeValue::query()->delete();
        \Illuminate\Support\Facades\DB::table('product_categories')->delete();
        \Illuminate\Support\Facades\DB::table('product_variant_attribute')->delete();

        // Re-enable foreign key checks
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create product attributes
        $colorAttribute = ProductAttribute::create([
            'name' => 'Warna',
            'code' => 'color',
            'type' => 'color',
            'is_filterable' => true,
            'is_visible' => true,
            'sort_order' => 1,
        ]);

        $sizeAttribute = ProductAttribute::create([
            'name' => 'Ukuran',
            'code' => 'size',
            'type' => 'select',
            'is_filterable' => true,
            'is_visible' => true,
            'sort_order' => 2,
        ]);

        // Create attribute values
        $colorValues = [
            ['value' => 'Hitam', 'color_code' => '#000000'],
            ['value' => 'Putih', 'color_code' => '#FFFFFF'],
            ['value' => 'Merah', 'color_code' => '#FF0000'],
            ['value' => 'Biru', 'color_code' => '#0000FF'],
            ['value' => 'Hijau', 'color_code' => '#00FF00'],
        ];

        $sizeValues = ['S', 'M', 'L', 'XL', 'XXL'];

        $colorValueIds = [];
        foreach ($colorValues as $color) {
            $colorValue = ProductAttributeValue::create(array_merge($color, [
                'attribute_id' => $colorAttribute->id,
                'sort_order' => 1,
            ]));
            $colorValueIds[] = $colorValue->id;
        }

        $sizeValueIds = [];
        foreach ($sizeValues as $index => $size) {
            $sizeValue = ProductAttributeValue::create([
                'attribute_id' => $sizeAttribute->id,
                'value' => $size,
                'sort_order' => $index + 1,
            ]);
            $sizeValueIds[] = $sizeValue->id;
        }

        // Sample products data
        $products = [
            [
                'name' => 'Smartphone Samsung Galaxy S23',
                'slug' => 'samsung-galaxy-s23',
                'brand_id' => 1, // Samsung
                'short_description' => 'Smartphone flagship Samsung dengan kamera terbaik',
                'description' => '<p>Smartphone Samsung Galaxy S23 dengan spesifikasi tinggi.</p><ul><li>Layar 6.1" Dynamic AMOLED</li><li>Prosesor Snapdragon 8 Gen 2</li><li>Kamera 50MP + 12MP + 10MP</li><li>Baterai 3900mAh</li><li>Android 13</li></ul>',
                'specifications' => json_encode([
                    'layar' => '6.1" Dynamic AMOLED',
                    'prosesor' => 'Snapdragon 8 Gen 2',
                    'ram' => '8GB',
                    'storage' => '256GB',
                    'kamera_depan' => '12MP',
                    'kamera_belakang' => '50MP + 12MP + 10MP',
                    'baterai' => '3900mAh',
                    'os' => 'Android 13',
                ]),
                'type' => 'simple',
                'status' => 'active',
                'is_featured' => true,
                'is_new' => true,
            ],
            [
                'name' => 'Kaos Erigo Original',
                'slug' => 'kaos-erigo-original',
                'brand_id' => 9, // Erigo
                'short_description' => 'Kaos premium dari Erigo dengan bahan katun combed',
                'description' => '<p>Kaos Erigo dengan kualitas terbaik.</p><ul><li>Bahan: Cotton Combed 30s</li><li>Sablon: Plastisol</li><li>Lengan pendek</li><li>Neck Rib</li><li>Made in Indonesia</li></ul>',
                'specifications' => json_encode([
                    'bahan' => 'Cotton Combed 30s',
                    'jenis_sablon' => 'Plastisol',
                    'panjang_lengan' => 'Pendek',
                    'leher' => 'Rib',
                    'asal_produksi' => 'Indonesia',
                ]),
                'type' => 'configurable',
                'status' => 'active',
                'is_featured' => true,
            ],
            [
                'name' => 'Laptop ASUS VivoBook 15',
                'slug' => 'laptop-asus-vivobook-15',
                'brand_id' => null, // No brand
                'short_description' => 'Laptop tipis dan ringan untuk produktivitas',
                'description' => '<p>Laptop ASUS VivoBook 15 ideal untuk kerja dan studi.</p>',
                'type' => 'simple',
                'status' => 'active',
            ],
            [
                'name' => 'Sepatu Nike Air Max',
                'slug' => 'sepatu-nike-air-max',
                'brand_id' => 4, // Nike
                'short_description' => 'Sepatu olahraga dengan teknologi Air Max',
                'description' => '<p>Sepatu Nike Air Max untuk kenyamanan maksimal.</p>',
                'type' => 'configurable',
                'status' => 'active',
            ],
            [
                'name' => 'Televisi LED 43 Inch',
                'slug' => 'televisi-led-43-inch',
                'brand_id' => 7, // Philips
                'short_description' => 'TV LED dengan resolusi Full HD',
                'description' => '<p>Televisi LED 43 inch untuk pengalaman menonton terbaik.</p>',
                'type' => 'simple',
                'status' => 'active',
            ],
        ];

        foreach ($products as $index => $productData) {
            $sku = 'PROD' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
            
            $product = Product::create(array_merge($productData, [
                'sku' => $sku,
            ]));

            // Attach to categories
            if ($index == 0) {
                $product->categories()->attach([1, 2]); // Elektronik & Smartphone
            } elseif ($index == 1) {
                $product->categories()->attach([6, 7]); // Fashion Pria/Wanita
            } elseif ($index == 2) {
                $product->categories()->attach([3]); // Laptop & Komputer
            }

            // Create product images
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => 'https://picsum.photos/800/800?random=' . $index,
                'thumbnail_url' => 'https://picsum.photos/400/400?random=' . $index,
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_main' => true,
            ]);

            // Create price
            $basePrice = [15000000, 150000, 8000000, 1200000, 3500000][$index];
            
            Price::create([
                'product_id' => $product->id,
                'base_price' => $basePrice,
                'sale_price' => $index == 0 ? $basePrice * 0.9 : null, // Discount 10% for first product
                'sale_start_date' => $index == 0 ? now() : null,
                'sale_end_date' => $index == 0 ? now()->addDays(30) : null,
                'currency' => 'IDR',
                'is_active' => true,
            ]);

            // Create inventory
            Inventory::create([
                'product_id' => $product->id,
                'sku' => $sku,
                'quantity' => rand(10, 100),
                'low_stock_threshold' => 5,
                'stock_status' => 'in_stock',
            ]);

            // Create variants for configurable products
            if ($product->type === 'configurable') {
                $this->createVariants($product, $colorAttribute, $sizeAttribute, $colorValueIds, $sizeValueIds);
            }
        }

        $this->command->info('✅ Products seeded successfully!');
    }

    private function createVariants($product, $colorAttribute, $sizeAttribute, $colorValueIds, $sizeValueIds)
    {
        $variantCount = 0;
        
        // Create color variants
        foreach ($colorValueIds as $colorValueId) {
            $colorValue = ProductAttributeValue::find($colorValueId);
            
            $variantSku = $product->sku . '-COL-' . $variantCount;
            
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantSku,
                'price' => $product->activePrice->base_price,
                'special_price' => $product->activePrice->sale_price,
                'special_price_from' => $product->activePrice->sale_start_date,
                'special_price_to' => $product->activePrice->sale_end_date,
            ]);

            // Attach color attribute
            $variant->attributeValues()->attach($colorValueId, ['attribute_id' => $colorAttribute->id]);

            // Create variant inventory
            Inventory::create([
                'variant_id' => $variant->id,
                'sku' => $variantSku,
                'quantity' => rand(5, 20),
                'low_stock_threshold' => 3,
                'stock_status' => 'in_stock',
            ]);

            $variantCount++;
        }

        // For fashion products, also create size variants
        if (str_contains(strtolower($product->name), 'kaos') || str_contains(strtolower($product->name), 'sepatu')) {
            foreach ($sizeValueIds as $sizeValueId) {
                $sizeValue = ProductAttributeValue::find($sizeValueId);
                
                $variantSku = $product->sku . '-SIZE-' . $variantCount;
                
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $variantSku,
                    'price' => $product->activePrice->base_price + (rand(-50000, 50000)), // Slight price variation
                ]);

                // Attach size attribute
                $variant->attributeValues()->attach($sizeValueId, ['attribute_id' => $sizeAttribute->id]);

                // Create variant inventory
                Inventory::create([
                    'variant_id' => $variant->id,
                    'sku' => $variantSku,
                    'quantity' => rand(5, 15),
                    'low_stock_threshold' => 3,
                    'stock_status' => 'in_stock',
                ]);

                $variantCount++;
            }
        }
    }
}