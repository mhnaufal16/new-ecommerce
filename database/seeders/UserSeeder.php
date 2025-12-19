<?php
// database/seeders/UserSeeder.php
namespace Database\Seeders;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        User::truncate();
        UserAddress::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@tokoecommerce.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'type' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create Vendor User
        $vendor = User::create([
            'name' => 'Vendor Handal',
            'email' => 'vendor@tokoecommerce.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567891',
            'type' => 'vendor',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create 10 Customer Users
        User::factory()->count(10)->create([
            'type' => 'customer',
            'status' => 'active',
        ]);

        // Create addresses for admin
        UserAddress::create([
            'user_id' => $admin->id,
            'label' => 'Kantor',
            'recipient_name' => 'Admin Toko',
            'phone' => '081234567890',
            'province_id' => 5, // DI Yogyakarta
            'province_name' => 'DI Yogyakarta',
            'city_id' => 501,
            'city_name' => 'Yogyakarta',
            'district' => 'Gondokusuman',
            'subdistrict' => 'Semaki',
            'postal_code' => '55221',
            'address' => 'Jl. Jendral Sudirman No. 123',
            'is_primary' => true,
        ]);

        // Create addresses for vendor
        UserAddress::create([
            'user_id' => $vendor->id,
            'label' => 'Gudang',
            'recipient_name' => 'Vendor Handal',
            'phone' => '081234567891',
            'province_id' => 6, // DKI Jakarta
            'province_name' => 'DKI Jakarta',
            'city_id' => 151,
            'city_name' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'subdistrict' => 'Senayan',
            'postal_code' => '12190',
            'address' => 'Jl. Asia Afrika No. 8',
            'is_primary' => true,
        ]);

        $this->command->info('✅ Users and addresses seeded successfully!');
    }
}