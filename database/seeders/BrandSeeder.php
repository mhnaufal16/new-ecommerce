<?php
// database/seeders/BrandSeeder.php
namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run()
    {
        Brand::truncate();

        $brands = [
            [
                'name' => 'Samsung',
                'slug' => 'samsung',
                'description' => 'Brand elektronik global dari Korea Selatan',
                'is_active' => true,
            ],
            [
                'name' => 'Apple',
                'slug' => 'apple',
                'description' => 'Perusahaan teknologi asal Amerika',
                'is_active' => true,
            ],
            [
                'name' => 'Xiaomi',
                'slug' => 'xiaomi',
                'description' => 'Brand smartphone dan elektronik dari China',
                'is_active' => true,
            ],
            [
                'name' => 'Nike',
                'slug' => 'nike',
                'description' => 'Brand olahraga global',
                'is_active' => true,
            ],
            [
                'name' => 'Adidas',
                'slug' => 'adidas',
                'description' => 'Brand sepatu dan pakaian olahraga',
                'is_active' => true,
            ],
            [
                'name' => 'Uniqlo',
                'slug' => 'uniqlo',
                'description' => 'Brand fashion dari Jepang',
                'is_active' => true,
            ],
            [
                'name' => 'Philips',
                'slug' => 'philips',
                'description' => 'Brand elektronik rumah tangga',
                'is_active' => true,
            ],
            [
                'name' => 'Cosmos',
                'slug' => 'cosmos',
                'description' => 'Brand elektronik lokal Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Erigo',
                'slug' => 'erigo',
                'description' => 'Brand fashion lokal Indonesia',
                'is_active' => true,
            ],
            [
                'name' => 'Local Brand',
                'slug' => 'local-brand',
                'description' => 'Brand produk lokal Indonesia',
                'is_active' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        $this->command->info('✅ Brands seeded successfully!');
    }
}