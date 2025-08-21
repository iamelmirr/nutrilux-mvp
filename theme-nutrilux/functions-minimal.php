<?php
/**
 * Test minimal functions.php for debugging
 */

// Basic theme setup
function nutrilux_setup() {
    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'nutrilux_setup');

// Enqueue styles
function nutrilux_styles() {
    wp_enqueue_style('nutrilux-base', get_template_directory_uri() . '/assets/css/base.css', [], '1.0.0');
}
add_action('wp_enqueue_scripts', 'nutrilux_styles');

echo "<!-- Functions.php loaded successfully -->";
