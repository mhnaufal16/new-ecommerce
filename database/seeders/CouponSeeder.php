<?php
// database/seeders/CouponSeeder.php
namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::truncate();

        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => 'Welcome Discount 10%',
                'description' => 'Diskon 10% untuk pembeli baru',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000,
                'usage_limit' => 100,
                'usage_per_customer' => 1,
                'is_active' => true,
                'starts_at' => now()->subDays(7),
                'expires_at' => now()->addMonths(3),
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Free Shipping',
                'description' => 'Gratis ongkir untuk semua pengiriman',
                'discount_type' => 'free_shipping',
                'discount_value' => 0,
                'min_order_amount' => 150000,
                'usage_limit' => 50,
                'usage_per_customer' => 1,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addDays(30),
            ],
            [
                'code' => 'FLASH50K',
                'name' => 'Flash Sale 50K',
                'description' => 'Potongan langsung Rp 50.000',
                'discount_type' => 'fixed_amount',
                'discount_value' => 50000,
                'min_order_amount' => 250000,
                'max_discount_amount' => 50000,
                'usage_limit' => 200,
                'usage_per_customer' => 2,
                'is_active' => true,
                'starts_at' => now()->addDays(1),
                'expires_at' => now()->addDays(7),
            ],
            [
                'code' => 'ELEKTRONIK15',
                'name' => 'Diskon Elektronik 15%',
                'description' => 'Diskon khusus produk elektronik',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'min_order_amount' => 500000,
                'max_discount_amount' => 200000,
                'usage_limit' => null, // Unlimited
                'usage_per_customer' => 3,
                'is_active' => true,
                'starts_at' => now(),
                'expires_at' => now()->addMonths(2),
            ],
        ];

        foreach ($coupons as $couponData) {
            $coupon = Coupon::create($couponData);

            // Attach products/categories for specific coupons
            if ($coupon->code === 'ELEKTRONIK15') {
                $electronicsCategory = Category::where('slug', 'elektronik-gadget')->first();
                if ($electronicsCategory) {
                    $coupon->categories()->attach($electronicsCategory->id);
                }
            }
        }

        $this->command->info('✅ Coupons seeded successfully!');
    }
}