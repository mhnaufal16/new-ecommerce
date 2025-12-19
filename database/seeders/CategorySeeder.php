<?php
// database/seeders/CategorySeeder.php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::truncate();

        // Elektronik & Gadget
        $elektronik = Category::create([
            'name' => 'Elektronik & Gadget',
            'slug' => 'elektronik-gadget',
            'description' => 'Produk elektronik dan gadget terbaru',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Sub-kategori Elektronik
        Category::create([
            'parent_id' => $elektronik->id,
            'name' => 'Smartphone',
            'slug' => 'smartphone',
            'description' => 'Smartphone terbaru dari berbagai brand',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $elektronik->id,
            'name' => 'Laptop & Komputer',
            'slug' => 'laptop-komputer',
            'description' => 'Laptop, PC, dan aksesoris komputer',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $elektronik->id,
            'name' => 'TV & Audio',
            'slug' => 'tv-audio',
            'description' => 'Televisi, sound system, dan perangkat audio',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Fashion
        $fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
            'description' => 'Pakaian dan aksesoris fashion',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Sub-kategori Fashion
        Category::create([
            'parent_id' => $fashion->id,
            'name' => 'Pria',
            'slug' => 'fashion-pria',
            'description' => 'Fashion untuk pria',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $fashion->id,
            'name' => 'Wanita',
            'slug' => 'fashion-wanita',
            'description' => 'Fashion untuk wanita',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Rumah Tangga
        $rumahTangga = Category::create([
            'name' => 'Rumah Tangga',
            'slug' => 'rumah-tangga',
            'description' => 'Perlengkapan rumah tangga',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Sub-kategori Rumah Tangga
        Category::create([
            'parent_id' => $rumahTangga->id,
            'name' => 'Dapur',
            'slug' => 'dapur',
            'description' => 'Peralatan dapur dan memasak',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Category::create([
            'parent_id' => $rumahTangga->id,
            'name' => 'Kamar Mandi',
            'slug' => 'kamar-mandi',
            'description' => 'Peralatan kamar mandi',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Kesehatan & Kecantikan
        Category::create([
            'name' => 'Kesehatan & Kecantikan',
            'slug' => 'kesehatan-kecantikan',
            'description' => 'Produk kesehatan dan kecantikan',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // Olahraga
        Category::create([
            'name' => 'Olahraga',
            'slug' => 'olahraga',
            'description' => 'Alat olahraga dan perlengkapan',
            'sort_order' => 5,
            'is_active' => true,
        ]);

        $this->command->info('✅ Categories seeded successfully!');
    }
}