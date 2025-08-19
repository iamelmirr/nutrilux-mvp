<?php
/**
 * Nutrilux Theme Functions
 * 
 * @package Nutrilux
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function nutrilux_theme_setup() {
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add WooCommerce support
    add_theme_support('woocommerce');
    
    // Add support for WooCommerce features
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Glavna navigacija', 'nutrilux'),
        'footer'  => esc_html__('Footer navigacija', 'nutrilux'),
    ));
    
    // Add support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
}
add_action('after_setup_theme', 'nutrilux_theme_setup');

/**
 * Include WooCommerce customizations
 */
if (class_exists('WooCommerce')) {
    require_once get_template_directory() . '/inc/woocommerce.php';
}

/**
 * Include product meta fields registration
 */
require_once get_template_directory() . '/inc/product-meta.php';

/**
 * Include product seed script (for development)
 */
require_once get_template_directory() . '/inc/product-seed.php';

/**
 * Include checkout customizations
 */
require_once get_template_directory() . '/inc/checkout.php';

/**
 * Include contact form AJAX handler
 */
require_once get_template_directory() . '/inc/contact-ajax.php';

/**
 * Include email branding and schema markup
 */
require_once get_template_directory() . '/inc/email-schema.php';

/**
 * Include performance and accessibility optimizations (P11)
 */
require_once get_template_directory() . '/inc/performance.php';

/**
 * Enqueue scripts and styles
 */
function nutrilux_enqueue_assets() {
    // Google Fonts - Poppins and Inter
    // TODO: Consider self-hosting fonts for better performance and GDPR compliance
    wp_enqueue_style(
        'nutrilux-google-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap',
        array(),
        null
    );
    
    // Enqueue base CSS file
    wp_enqueue_style(
        'nutrilux-base',
        get_template_directory_uri() . '/assets/css/base.css',
        array('nutrilux-google-fonts'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue layout CSS file
    wp_enqueue_style(
        'nutrilux-layout',
        get_template_directory_uri() . '/assets/css/layout.css',
        array('nutrilux-base'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue WooCommerce CSS file (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        wp_enqueue_style(
            'nutrilux-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            array('nutrilux-layout'),
            wp_get_theme()->get('Version')
        );
        
        // Single product page styles
        if (is_product()) {
            wp_enqueue_style(
                'nutrilux-single-product',
                get_template_directory_uri() . '/assets/css/single-product.css',
                array('nutrilux-layout'),
                wp_get_theme()->get('Version')
            );
        }
        
        // Checkout page styles
        if (is_checkout()) {
            wp_enqueue_style(
                'nutrilux-checkout',
                get_template_directory_uri() . '/assets/css/checkout.css',
                array('nutrilux-layout'),
                wp_get_theme()->get('Version')
            );
        }
        
        // About and Contact pages styles
        if (is_page(array('o-nama', 'kontakt')) || is_page_template(array('page-o-nama.php', 'page-kontakt.php'))) {
            wp_enqueue_style(
                'nutrilux-pages',
                get_template_directory_uri() . '/assets/css/pages.css',
                array('nutrilux-layout'),
                wp_get_theme()->get('Version')
            );
        }
    }
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'nutrilux-style',
        get_stylesheet_uri(),
        array('nutrilux-layout', 'nutrilux-woocommerce'),
        wp_get_theme()->get('Version')
    );
    
    // Enqueue JavaScript
    wp_enqueue_script(
        'nutrilux-site',
        get_template_directory_uri() . '/assets/js/site.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'nutrilux_enqueue_assets');

/**
 * Fallback menu for primary navigation
 */
function nutrilux_fallback_menu() {
    echo '<ul class="nav-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Početna', 'nutrilux') . '</a></li>';
    
    // WooCommerce shop link
    if (class_exists('WooCommerce')) {
        echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">' . esc_html__('Proizvodi', 'nutrilux') . '</a></li>';
    }
    
    // Check for specific pages
    $about_page = get_page_by_path('o-nama');
    if ($about_page) {
        echo '<li><a href="' . esc_url(get_permalink($about_page->ID)) . '">' . esc_html__('O nama', 'nutrilux') . '</a></li>';
    }
    
    $contact_page = get_page_by_path('kontakt');
    if ($contact_page) {
        echo '<li><a href="' . esc_url(get_permalink($contact_page->ID)) . '">' . esc_html__('Kontakt', 'nutrilux') . '</a></li>';
    }
    
    echo '</ul>';
}

/**
 * Fallback menu for footer navigation
 */
function nutrilux_footer_fallback_menu() {
    echo '<ul class="footer-menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Početna', 'nutrilux') . '</a></li>';
    
    if (class_exists('WooCommerce')) {
        echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '">' . esc_html__('Proizvodi', 'nutrilux') . '</a></li>';
    }
    
    $about_page = get_page_by_path('o-nama');
    if ($about_page) {
        echo '<li><a href="' . esc_url(get_permalink($about_page->ID)) . '">' . esc_html__('O nama', 'nutrilux') . '</a></li>';
    }
    
    $contact_page = get_page_by_path('kontakt');
    if ($contact_page) {
        echo '<li><a href="' . esc_url(get_permalink($contact_page->ID)) . '">' . esc_html__('Kontakt', 'nutrilux') . '</a></li>';
    }
    
    echo '</ul>';
}
