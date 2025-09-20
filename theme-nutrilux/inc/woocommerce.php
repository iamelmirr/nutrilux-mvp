<?php
/**
 * WooCommerce Configuration
 * Handles WooCommerce setup, customizations, and localization
 * 
 * @package Nutrilux
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create WooCommerce pages programmatically if they don't exist
 */
function nutrilux_create_woocommerce_pages() {
    
    // Only run on theme activation or if pages don't exist
    if (get_option('nutrilux_woo_pages_created')) {
        return;
    }
    
    $pages = array(
        'shop' => array(
            'title' => 'Proizvodi',
            'slug'  => 'proizvodi',
            'content' => '[woocommerce_shop]'
        ),
        'cart' => array(
            'title' => 'Korpa',
            'slug'  => 'korpa',
            'content' => '[woocommerce_cart]'
        ),
        'checkout' => array(
            'title' => 'Plaćanje',
            'slug'  => 'checkout',
            'content' => '[woocommerce_checkout]'
        ),
        'my-account' => array(
            'title' => 'Moj nalog',
            'slug'  => 'moj-nalog',
            'content' => '[woocommerce_my_account]'
        )
    );
    
    foreach ($pages as $page_key => $page_data) {
        // Check if page already exists
        $page_id = wc_get_page_id($page_key);
        
        if ($page_id <= 0) {
            // Create the page
            $page_id = wp_insert_post(array(
                'post_title'   => $page_data['title'],
                'post_name'    => $page_data['slug'],
                'post_content' => $page_data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ));
            
            if ($page_id && !is_wp_error($page_id)) {
                // Set WooCommerce page option
                update_option('woocommerce_' . str_replace('-', '_', $page_key) . '_page_id', $page_id);
            }
        }
    }
    
    // Mark as completed
    update_option('nutrilux_woo_pages_created', true);
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Hook to run after WooCommerce is loaded
add_action('woocommerce_init', 'nutrilux_create_woocommerce_pages');

/**
 * Disable WooCommerce coupons completely
 */
add_filter('woocommerce_coupons_enabled', '__return_false');

/**
 * Disable product reviews/comments
 */
function nutrilux_disable_product_reviews() {
    remove_post_type_support('product', 'comments');
}
add_action('init', 'nutrilux_disable_product_reviews');

/**
 * Alternative method to disable reviews
 */
add_filter('comments_open', function($open, $post_id) {
    $post = get_post($post_id);
    if ($post && $post->post_type === 'product') {
        return false;
    }
    return $open;
}, 10, 2);

/**
 * Rename Cash on Delivery payment method
 */
add_filter('woocommerce_gateway_title', function($title, $gateway_id) {
    if ($gateway_id === 'cod') {
        return 'Plaćanje pouzećem (brza pošta)';
    }
    return $title;
}, 10, 2);

/**
 * Localize WooCommerce strings
 */
function nutrilux_localize_woocommerce_strings($translated_text, $text, $domain) {
    
    // Only modify WooCommerce strings
    if ($domain !== 'woocommerce') {
        return $translated_text;
    }
    
    $translations = array(
        'Cart' => 'Korpa',
        'Subtotal' => 'Međuzbroj',
        'Total' => 'Ukupno',
        'Proceed to checkout' => 'Nastavi na plaćanje',
        'Checkout' => 'Plaćanje',
        'Place order' => 'Potvrdi narudžbu',
        'Your order' => 'Vaša narudžba',
        'Product' => 'Proizvod',
        'Quantity' => 'Količina',
        'Price' => 'Cijena',
        'Add to cart' => 'Dodaj u korpu',
        'View cart' => 'Pogledaj korpu',
        'Continue shopping' => 'Nastavi kupovinu',
        'Remove this item' => 'Ukloni stavku',
        'Update cart' => 'Ažuriraj korpu',
        'Apply coupon' => 'Primijeni kupon',
        'Shipping' => 'Dostava',
        'Payment method' => 'Način plaćanja',
        'Billing details' => 'Podaci za naplatu',
        'Order notes' => 'Napomene za narudžbu',
        'First name' => 'Ime',
        'Last name' => 'Prezime',
        'Email address' => 'Email adresa',
        'Phone' => 'Telefon',
        'Country / Region' => 'Zemlja / Region',
        'Street address' => 'Adresa',
        'Town / City' => 'Grad',
        'Postcode / ZIP' => 'Poštanski broj',
        'Order received' => 'Narudžba primljena',
        'Thank you. Your order has been received.' => 'Hvala vam. Vaša narudžba je primljena.',
        'Order number' => 'Broj narudžbe',
        'Date' => 'Datum',
        'Out of stock' => 'Nema na stanju',
        'In stock' => 'Na stanju',
        'Category' => 'Kategorija',
        'Description' => 'Opis',
        'Additional information' => 'Dodatne informacije',
        'Related products' => 'Srodni proizvodi',
        'You may also like&hellip;' => 'Možda će vam se svidjeti...',
        'Default sorting' => 'Osnovni redoslijed',
        'Sort by popularity' => 'Sortiraj po popularnosti',
        'Sort by average rating' => 'Sortiraj po ocjeni',
        'Sort by latest' => 'Sortiraj po najnovijim',
        'Sort by price: low to high' => 'Sortiraj po cijeni: niža do viša',
        'Sort by price: high to low' => 'Sortiraj po cijeni: viša do niža',
        'Read more' => 'Pročitaj više',
        'Select options' => 'Izaberi opcije',
        'Choose an option' => 'Odaberite opciju'
    );
    
    // Check if we have a translation for this text
    if (isset($translations[$text])) {
        return $translations[$text];
    }
    
    return $translated_text;
}
add_filter('gettext', 'nutrilux_localize_woocommerce_strings', 20, 3);

/**
 * Filter catalog ordering options - only show 4 main options
 */
function nutrilux_custom_catalog_ordering($options) {
    $custom_options = array(
        'menu_order' => esc_html__('Osnovni redoslijed', 'nutrilux'),
        'popularity' => esc_html__('Sortiraj po popularnosti', 'nutrilux'),
        'price'      => esc_html__('Sortiraj po cijeni: niža do viša', 'nutrilux'),
        'price-desc' => esc_html__('Sortiraj po cijeni: viša do niža', 'nutrilux'),
    );
    
    return $custom_options;
}
add_filter('woocommerce_catalog_orderby', 'nutrilux_custom_catalog_ordering');

/**
 * Enable Cash on Delivery payment method
 */
function nutrilux_enable_cod_payment() {
    // Get COD gateway settings
    $cod_settings = get_option('woocommerce_cod_settings', array());
    
    // Enable COD if not already enabled
    if (!isset($cod_settings['enabled']) || $cod_settings['enabled'] !== 'yes') {
        $cod_settings['enabled'] = 'yes';
        $cod_settings['title'] = 'Plaćanje pouzećem (brza pošta)';
        $cod_settings['description'] = 'Platićete kada vam dostavljač donese pakovanje.';
        $cod_settings['instructions'] = 'Platićete gotovinom prilikom preuzimanja pakovanja.';
        
        update_option('woocommerce_cod_settings', $cod_settings);
    }
}
add_action('woocommerce_init', 'nutrilux_enable_cod_payment');

/**
 * Configure default shipping settings
 */
function nutrilux_configure_shipping() {
    
    // Only run once
    if (get_option('nutrilux_shipping_configured')) {
        return;
    }
    
    // Enable shipping calculations
    update_option('woocommerce_calc_shipping', 'yes');
    
    // TODO: Shipping zone configuration via WP CLI (preferred method)
    // WP CLI commands to run manually:
    // wp wc shipping_zone create --name="Bosna i Hercegovina" --user=admin
    // wp wc shipping_zone_location create 1 --type=country --code=BA --user=admin  
    // wp wc shipping_zone_method create 1 --method_id=flat_rate --user=admin
    // wp wc shipping_zone_method update 1 1 --settings='{"title":"Brza pošta","cost":"5"}' --user=admin
    
    // Alternative: Programmatic shipping zone creation (complex)
    // This requires direct database manipulation and is not recommended
    // Better to use WP CLI commands above or manual setup
    
    update_option('nutrilux_shipping_configured', true);
}
add_action('woocommerce_init', 'nutrilux_configure_shipping');

/**
 * Customize WooCommerce settings on activation
 */
function nutrilux_customize_woocommerce_settings() {
    
    // Only run once
    if (get_option('nutrilux_woo_settings_configured')) {
        return;
    }
    
    // General settings
    update_option('woocommerce_default_country', 'BA');
    update_option('woocommerce_currency', 'BAM');
    update_option('woocommerce_currency_pos', 'right_space');
    update_option('woocommerce_price_thousand_sep', '.');
    update_option('woocommerce_price_decimal_sep', ',');
    update_option('woocommerce_price_num_decimals', 2);
    
    // Default to Bosnia and Herzegovina; allow adjustments in admin
    update_option('woocommerce_default_country', 'BA');

    // Inventory settings
    update_option('woocommerce_manage_stock', 'yes');
    update_option('woocommerce_hide_out_of_stock_items', 'yes');
    
    // Account settings
    update_option('woocommerce_enable_guest_checkout', 'yes');
    update_option('woocommerce_enable_checkout_login_reminder', 'yes');
    
    // Tax settings (disable for COD setup)
    update_option('woocommerce_calc_taxes', 'no');
    
    update_option('nutrilux_woo_settings_configured', true);
}
add_action('woocommerce_init', 'nutrilux_customize_woocommerce_settings');

/**
 * Auto-seed products if shop is empty (local/dev convenience)
 */
add_action('woocommerce_init', function(){
    if (function_exists('nutrilux_seed_new_lineup')) {
        $count = wp_count_posts('product');
        $published = isset($count->publish) ? intval($count->publish) : 0;
        if ($published === 0) {
            nutrilux_seed_new_lineup();
        }
    }
});

/**
 * Allow only Cash on Delivery at checkout
 */
add_filter('woocommerce_available_payment_gateways', function($gateways){
    if (!is_admin()) {
        foreach ($gateways as $id => $gw) {
            if ($id !== 'cod') unset($gateways[$id]);
        }
    }
    return $gateways;
});

/**
 * Remove WooCommerce breadcrumbs (we'll style our own)
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Modify WooCommerce wrapper for our theme
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'nutrilux_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'nutrilux_woocommerce_wrapper_end', 10);

function nutrilux_woocommerce_wrapper_start() {
    echo '<main id="main" class="site-main woocommerce-main"><div class="wrap">';
}

function nutrilux_woocommerce_wrapper_end() {
    echo '</div></main>';
}
