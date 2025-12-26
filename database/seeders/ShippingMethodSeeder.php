<?php
// database/seeders/ShippingMethodSeeder.php
namespace Database\Seeders;

use App\Models\ShippingMethod;
use App\Models\ShippingZone;
use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run()
    {
        // Use delete() instead of truncate() to avoid foreign key constraint issues on MySQL
        ShippingRate::query()->delete();
        ShippingZone::query()->delete();
        ShippingMethod::query()->delete();

        // Create shipping methods
        $jne = ShippingMethod::create([
            'code' => 'jne',
            'name' => 'JNE',
            'description' => 'Pengiriman melalui JNE',
            'logo' => 'shipping/jne.png',
            'is_active' => true,
            'is_cod_supported' => true,
            'sort_order' => 1,
            'config' => json_encode([
                'tracking_url' => 'https://www.jne.co.id/id/tracking/trace',
                'customer_service' => '0800-1-888-888',
            ]),
        ]);

        $tiki = ShippingMethod::create([
            'code' => 'tiki',
            'name' => 'TIKI',
            'description' => 'Pengiriman melalui TIKI',
            'logo' => 'shipping/tiki.png',
            'is_active' => true,
            'is_cod_supported' => true,
            'sort_order' => 2,
            'config' => json_encode([
                'tracking_url' => 'https://www.tiki.id/id/tracking',
                'customer_service' => '0800-1-900-900',
            ]),
        ]);

        $pos = ShippingMethod::create([
            'code' => 'pos',
            'name' => 'POS Indonesia',
            'description' => 'Pengiriman melalui POS Indonesia',
            'logo' => 'shipping/pos.png',
            'is_active' => true,
            'is_cod_supported' => true,
            'sort_order' => 3,
            'config' => json_encode([
                'tracking_url' => 'https://www.posindonesia.co.id/id/tracking',
                'customer_service' => '0800-1-600-600',
            ]),
        ]);

        $sicepat = ShippingMethod::create([
            'code' => 'sicepat',
            'name' => 'SiCepat',
            'description' => 'Pengiriman melalui SiCepat',
            'logo' => 'shipping/sicepat.png',
            'is_active' => true,
            'is_cod_supported' => false,
            'sort_order' => 4,
            'config' => json_encode([
                'tracking_url' => 'https://www.sicepat.com/check-awb',
                'customer_service' => '0800-1-111-111',
            ]),
        ]);

        // Create shipping zones
        $javaZone = ShippingZone::create([
            'name' => 'Pulau Jawa',
            'country_code' => 'ID',
            'province_ids' => json_encode([5, 6, 9, 10, 11, 12]), // DI Yogyakarta, DKI Jakarta, Jawa Barat, Jawa Tengah, Jawa Timur, Banten
            'is_active' => true,
        ]);

        $sumatraZone = ShippingZone::create([
            'name' => 'Pulau Sumatera',
            'country_code' => 'ID',
            'province_ids' => json_encode([1, 2, 3, 4, 7, 8]), // Aceh, Sumatera Utara, Sumatera Barat, Riau, Bengkulu, Sumatera Selatan
            'is_active' => true,
        ]);

        $globalZone = ShippingZone::create([
            'name' => 'Seluruh Indonesia',
            'country_code' => 'ID',
            'province_ids' => null, // Global zone
            'is_active' => true,
        ]);

        // Create shipping rates for JNE
        ShippingRate::create([
            'shipping_method_id' => $jne->id,
            'shipping_zone_id' => $javaZone->id,
            'name' => 'REG',
            'description' => 'Reguler (3-5 hari)',
            'price_type' => 'flat',
            'flat_price' => 15000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ShippingRate::create([
            'shipping_method_id' => $jne->id,
            'shipping_zone_id' => $javaZone->id,
            'name' => 'YES',
            'description' => 'Yakin Esok Sampai',
            'price_type' => 'flat',
            'flat_price' => 25000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        ShippingRate::create([
            'shipping_method_id' => $jne->id,
            'shipping_zone_id' => $sumatraZone->id,
            'name' => 'REG',
            'description' => 'Reguler (5-7 hari)',
            'price_type' => 'flat',
            'flat_price' => 25000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Create shipping rates for TIKI
        ShippingRate::create([
            'shipping_method_id' => $tiki->id,
            'shipping_zone_id' => $javaZone->id,
            'name' => 'REG',
            'description' => 'Reguler',
            'price_type' => 'flat',
            'flat_price' => 18000,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        ShippingRate::create([
            'shipping_method_id' => $tiki->id,
            'shipping_zone_id' => $globalZone->id,
            'name' => 'ONS',
            'description' => 'Overnight Service',
            'price_type' => 'weight_based',
            'min_weight' => 0,
            'max_weight' => 10,
            'flat_price' => 50000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Shipping methods seeded successfully!');
    }
}