<?php
/**
 * EKSTREMNO JEDNOSTAVAN TEST - functions.php
 */

// Osnovna tema setup
function nutrilux_setup() {
    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'nutrilux_setup');

// Osnovni stilovi
function nutrilux_styles() {
    wp_enqueue_style('nutrilux-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'nutrilux_styles');

// NAJJEDNOSTAVNIJI TEST - VELIKA CRVENA PORUKA NA CHECKOUT
add_action('wp_head', function() {
    if (is_checkout()) {
        echo '<style>body { background: red !important; }</style>';
        echo '<script>console.log("NUTRILUX CHECKOUT REFINEMENT RADI!");</script>';
    }
});

// Test da li se checkout uopće detektuje
add_action('wp_footer', function() {
    if (is_checkout()) {
        echo '<div style="position:fixed; top:0; left:0; background:red; color:white; padding:20px; z-index:9999; font-size:24px; font-weight:bold;">NUTRILUX CHECKOUT REFINEMENT AKTIVAN!</div>';
    }
});

// Forsiranje bosanskog teksta na dugmetu
add_filter('woocommerce_order_button_text', function() {
    return 'POTVRDI NARUDŽBU - TEST';
});

// Samo BiH država
add_filter('woocommerce_countries_allowed_countries', function($countries) {
    return ['BA' => 'Bosna i Hercegovina - TEST'];
});

// Forsiranje COD-a
add_filter('woocommerce_available_payment_gateways', function($gateways) {
    foreach($gateways as $id => $gateway) {
        if($id !== 'cod') {
            unset($gateways[$id]);
        } else {
            $gateways[$id]->title = 'PLAĆANJE POUZEĆEM - TEST';
        }
    }
    return $gateways;
});

// Log u error log da vidimo da li se učitava
error_log('NUTRILUX FUNCTIONS.PHP SE UČITAVA - ' . date('Y-m-d H:i:s'));
if (is_checkout()) {
    error_log('CHECKOUT STRANICA DETEKTOVANA - ' . date('Y-m-d H:i:s'));
}
