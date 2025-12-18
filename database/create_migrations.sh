# create_migrations.sh
#!/bin/bash

# Buat array dengan nama-nama migration
migrations=(
    "create_users_table"
    "create_user_addresses_table"
    "create_categories_table"
    "create_brands_table"
    "create_products_table"
    "create_product_categories_table"
    "create_product_images_table"
    "create_product_attributes_table"
    "create_product_attribute_values_table"
    "create_product_variants_table"
    "create_product_variant_attributes_table"
    "create_inventories_table"
    "create_inventory_logs_table"
    "create_prices_table"
    "create_taxes_table"
    "create_product_taxes_table"
    "create_carts_table"
    "create_cart_items_table"
    "create_orders_table"
    "create_order_addresses_table"
    "create_order_items_table"
    "create_order_shipments_table"
    "create_payments_table"
    "create_payment_methods_table"
    "create_shipping_methods_table"
    "create_shipping_zones_table"
    "create_shipping_rates_table"
    "create_coupons_table"
    "create_coupon_products_table"
    "create_coupon_categories_table"
    "create_coupon_usage_table"
    "create_reviews_table"
    "create_wishlists_table"
    "create_settings_table"
    "create_activities_table"
    "create_notifications_table"
)

# Loop untuk membuat semua migration
counter=1
for migration in "${migrations[@]}"; do
    timestamp=$(date +"%Y_%m_%d_%H%M%S")
    php artisan make:migration ${migration} --create=${migration#create_}
    echo "Created: ${migration}"
    ((counter++))
done

echo "All migrations created successfully!"