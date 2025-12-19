<?php
// database/seeders/TaxSeeder.php
namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run()
    {
        Tax::truncate();

        $taxes = [
            [
                'name' => 'PPN',
                'rate' => 11.00,
                'type' => 'percentage',
                'country_code' => 'ID',
                'is_active' => true,
                'priority' => 1,
            ],
            [
                'name' => 'Pajak Daerah',
                'rate' => 2.00,
                'type' => 'percentage',
                'country_code' => 'ID',
                'province_id' => 5, // DI Yogyakarta
                'is_active' => true,
                'priority' => 2,
            ],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }

        $this->command->info('✅ Taxes seeded successfully!');
    }
}