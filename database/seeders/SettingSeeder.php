<?php
// database/seeders/SettingSeeder.php
namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        Setting::truncate();

        $settings = [
            // General Settings
            ['group' => 'general', 'key' => 'store_name', 'value' => 'Toko E-Commerce', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_email', 'value' => 'info@tokoecommerce.com', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_phone', 'value' => '021-12345678', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_address', 'value' => 'Jl. Sudirman No. 123, Jakarta', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_logo', 'value' => '/storage/logo.png', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_favicon', 'value' => '/storage/favicon.png', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_currency', 'value' => 'IDR', 'type' => 'string', 'is_public' => true],
            ['group' => 'general', 'key' => 'store_timezone', 'value' => 'Asia/Jakarta', 'type' => 'string', 'is_public' => false],
            
            // Order Settings
            ['group' => 'order', 'key' => 'min_order_amount', 'value' => '10000', 'type' => 'number', 'is_public' => true],
            ['group' => 'order', 'key' => 'auto_cancel_minutes', 'value' => '60', 'type' => 'number', 'is_public' => false],
            ['group' => 'order', 'key' => 'order_prefix', 'value' => 'ORD', 'type' => 'string', 'is_public' => false],
            ['group' => 'order', 'key' => 'default_order_status', 'value' => 'pending', 'type' => 'string', 'is_public' => false],
            
            // Payment Settings
            ['group' => 'payment', 'key' => 'default_payment_method', 'value' => 'bank_transfer', 'type' => 'string', 'is_public' => false],
            ['group' => 'payment', 'key' => 'bank_transfer_expiry', 'value' => '24', 'type' => 'number', 'is_public' => false],
            ['group' => 'payment', 'key' => 'qris_expiry', 'value' => '30', 'type' => 'number', 'is_public' => false],
            ['group' => 'payment', 'key' => 'cod_max_amount', 'value' => '5000000', 'type' => 'number', 'is_public' => true],
            
            // Shipping Settings
            ['group' => 'shipping', 'key' => 'default_shipping_method', 'value' => 'jne', 'type' => 'string', 'is_public' => false],
            ['group' => 'shipping', 'key' => 'free_shipping_min_amount', 'value' => '500000', 'type' => 'number', 'is_public' => true],
            ['group' => 'shipping', 'key' => 'shipping_insurance_rate', 'value' => '0.002', 'type' => 'number', 'is_public' => false], // 0.2%
            
            // Tax Settings
            ['group' => 'tax', 'key' => 'default_tax_rate', 'value' => '11', 'type' => 'number', 'is_public' => true],
            ['group' => 'tax', 'key' => 'tax_inclusive', 'value' => 'false', 'type' => 'boolean', 'is_public' => false],
            
            // Product Settings
            ['group' => 'product', 'key' => 'products_per_page', 'value' => '24', 'type' => 'number', 'is_public' => true],
            ['group' => 'product', 'key' => 'new_product_days', 'value' => '30', 'type' => 'number', 'is_public' => false],
            ['group' => 'product', 'key' => 'low_stock_threshold', 'value' => '5', 'type' => 'number', 'is_public' => false],
            
            // Review Settings
            ['group' => 'review', 'key' => 'review_approval_required', 'value' => 'true', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'review', 'key' => 'allow_anonymous_reviews', 'value' => 'false', 'type' => 'boolean', 'is_public' => true],
            ['group' => 'review', 'key' => 'min_order_for_review', 'value' => '1', 'type' => 'number', 'is_public' => true],
            
            // SEO Settings
            ['group' => 'seo', 'key' => 'meta_title', 'value' => 'Toko E-Commerce - Belanja Online Terpercaya', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_description', 'value' => 'Toko e-commerce terbaik dengan berbagai produk berkualitas', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'meta_keywords', 'value' => 'e-commerce, belanja online, toko online, produk indonesia', 'type' => 'string', 'is_public' => true],
            ['group' => 'seo', 'key' => 'google_analytics_id', 'value' => 'UA-XXXXXXXXX-X', 'type' => 'string', 'is_public' => false],
            
            // Social Media
            ['group' => 'social', 'key' => 'facebook_url', 'value' => 'https://facebook.com/tokoecommerce', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => 'https://instagram.com/tokoecommerce', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => 'https://twitter.com/tokoecommerce', 'type' => 'string', 'is_public' => true],
            ['group' => 'social', 'key' => 'whatsapp_number', 'value' => '6281234567890', 'type' => 'string', 'is_public' => true],
            
            // Maintenance
            ['group' => 'maintenance', 'key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'is_public' => false],
            ['group' => 'maintenance', 'key' => 'maintenance_message', 'value' => 'Website sedang dalam perawatan. Kami akan segera kembali.', 'type' => 'string', 'is_public' => false],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        $this->command->info('✅ Settings seeded successfully!');
    }
}