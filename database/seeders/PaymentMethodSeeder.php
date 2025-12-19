<?php
// database/seeders/PaymentMethodSeeder.php
namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        PaymentMethod::truncate();

        $paymentMethods = [
            [
                'code' => 'bank_transfer',
                'name' => 'Transfer Bank',
                'description' => 'Transfer melalui bank lokal Indonesia',
                'logo' => 'payment/bank-transfer.png',
                'is_active' => true,
                'sort_order' => 1,
                'config' => json_encode([
                    'banks' => ['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI'],
                    'instruction' => 'Transfer ke rekening virtual yang akan diberikan',
                    'expiry_hours' => 24,
                ]),
            ],
            [
                'code' => 'qris',
                'name' => 'QRIS',
                'description' => 'Bayar dengan scan QR code',
                'logo' => 'payment/qris.png',
                'is_active' => true,
                'sort_order' => 2,
                'config' => json_encode([
                    'merchant_name' => 'Toko E-Commerce',
                    'expiry_minutes' => 30,
                ]),
            ],
            [
                'code' => 'gopay',
                'name' => 'GoPay',
                'description' => 'Pembayaran melalui GoPay',
                'logo' => 'payment/gopay.png',
                'is_active' => true,
                'sort_order' => 3,
                'config' => json_encode([
                    'merchant_id' => 'GOPAY123456',
                    'expiry_minutes' => 15,
                ]),
            ],
            [
                'code' => 'ovo',
                'name' => 'OVO',
                'description' => 'Pembayaran melalui OVO',
                'logo' => 'payment/ovo.png',
                'is_active' => true,
                'sort_order' => 4,
                'config' => json_encode([
                    'merchant_id' => 'OVO789012',
                    'expiry_minutes' => 15,
                ]),
            ],
            [
                'code' => 'dana',
                'name' => 'DANA',
                'description' => 'Pembayaran melalui DANA',
                'logo' => 'payment/dana.png',
                'is_active' => true,
                'sort_order' => 5,
                'config' => json_encode([
                    'merchant_id' => 'DANA345678',
                    'expiry_minutes' => 15,
                ]),
            ],
            [
                'code' => 'credit_card',
                'name' => 'Kartu Kredit',
                'description' => 'Pembayaran dengan kartu kredit',
                'logo' => 'payment/credit-card.png',
                'is_active' => true,
                'sort_order' => 6,
                'config' => json_encode([
                    'supported_cards' => ['Visa', 'MasterCard', 'JCB'],
                    'installment' => true,
                ]),
            ],
            [
                'code' => 'alfamart',
                'name' => 'Alfamart',
                'description' => 'Bayar di gerai Alfamart terdekat',
                'logo' => 'payment/alfamart.png',
                'is_active' => true,
                'sort_order' => 7,
                'config' => json_encode([
                    'expiry_hours' => 24,
                    'instruction' => 'Tunjukkan kode pembayaran di kasir',
                ]),
            ],
            [
                'code' => 'indomaret',
                'name' => 'Indomaret',
                'description' => 'Bayar di gerai Indomaret terdekat',
                'logo' => 'payment/indomaret.png',
                'is_active' => true,
                'sort_order' => 8,
                'config' => json_encode([
                    'expiry_hours' => 24,
                    'instruction' => 'Tunjukkan kode pembayaran di kasir',
                ]),
            ],
            [
                'code' => 'cod',
                'name' => 'Cash on Delivery',
                'description' => 'Bayar ketika barang diterima',
                'logo' => 'payment/cod.png',
                'is_active' => true,
                'sort_order' => 9,
                'config' => json_encode([
                    'max_amount' => 5000000,
                    'areas' => ['Jabodetabek', 'Surabaya', 'Bandung', 'Medan'],
                ]),
            ],
        ];

        foreach ($paymentMethods as $method) {
            PaymentMethod::create($method);
        }

        $this->command->info('✅ Payment methods seeded successfully!');
    }
}