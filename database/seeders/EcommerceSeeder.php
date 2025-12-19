<?php
// database/seeders/EcommerceSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EcommerceSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductsSeeder::class,
            TaxSeeder::class,
            PaymentMethodSeeder::class,
            ShippingMethodSeeder::class,
            CouponSeeder::class,
            SettingSeeder::class,
        ]);
        
        $this->command->info('✅ E-commerce database seeded successfully!');
    }
}