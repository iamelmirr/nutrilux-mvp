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
 * Temporary minimal seed function for products
 * TODO: Remove after products are created
 */
function nutrilux_seed_minimal() {
    if (get_option('nutrilux_seed_minimal_done')) return;
    
    $items = [
        ['Cijelo jaje u prahu', 'cijelo-jaje-u-prahu', '8.90'],
        ['Žumance u prahu', 'zumance-u-prahu', '12.50'],
        ['Bjelance u prahu', 'bjelance-u-prahu', '11.90'],
        ['Performance Blend', 'performance-blend', '24.90'],
    ];
    
    foreach ($items as $it) {
        if (get_page_by_path($it[1], OBJECT, 'product')) continue;
        
        $id = wp_insert_post([
            'post_title' => $it[0],
            'post_name' => $it[1],
            'post_type' => 'product',
            'post_status' => 'publish'
        ]);
        
        if ($id) {
            update_post_meta($id, '_regular_price', $it[2]);
            update_post_meta($id, '_price', $it[2]);
            update_post_meta($id, '_stock_status', 'instock');
            update_post_meta($id, '_manage_stock', 'no');
            update_post_meta($id, '_visibility', 'visible');
        }
    }
    
    update_option('nutrilux_seed_minimal_done', 1);
}
add_action('init', 'nutrilux_seed_minimal');

/**
 * Create basic pages (O nama, Kontakt)
 */
function nutrilux_create_pages() {
    // Force recreation for testing
    // if (get_option('nutrilux_pages_created')) return;
    
    $pages = [
        [
            'title' => 'O nama',
            'slug' => 'o-nama',
            'template' => 'page-o-nama.php',
            'content' => '<p>Saznajte više o našoj kompaniji, misiji i vrijednostima.</p>'
        ],
        [
            'title' => 'Kontakt',
            'slug' => 'kontakt', 
            'template' => 'page-kontakt.php',
            'content' => '<p>Kontaktirajte nas za sva pitanja o proizvodima ili saradnji.</p>'
        ]
    ];
    
    foreach ($pages as $page) {
        // Check if page already exists
        $existing = get_page_by_path($page['slug']);
        if ($existing) {
            // Update template if page exists
            update_post_meta($existing->ID, '_wp_page_template', $page['template']);
            continue;
        }
        
        $page_id = wp_insert_post([
            'post_title' => $page['title'],
            'post_name' => $page['slug'],
            'post_content' => $page['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1
        ]);
        
        if ($page_id && !is_wp_error($page_id)) {
            // Set page template
            update_post_meta($page_id, '_wp_page_template', $page['template']);
        }
    }
    
    update_option('nutrilux_pages_created', 1);
}
add_action('init', 'nutrilux_create_pages');

/**
 * Add body classes for active navigation
 */
function nutrilux_body_classes($classes) {
    global $post;
    
    if (is_front_page()) {
        $classes[] = 'nav-home-active';
    } elseif (is_shop() || is_product_category() || is_product_tag()) {
        $classes[] = 'nav-shop-active';
    } elseif (is_page()) {
        if ($post && $post->post_name === 'o-nama') {
            $classes[] = 'nav-about-active';
        } elseif ($post && $post->post_name === 'kontakt') {
            $classes[] = 'nav-contact-active';
        }
    }
    
    return $classes;
}
add_filter('body_class', 'nutrilux_body_classes');

/**
 * Development Live Reload (only for local development)
 * DISABLE ALL CACHING FOR DEVELOPMENT
 */
function nutrilux_disable_cache_dev() {
    if (strpos($_SERVER['HTTP_HOST'], 'nutrilux10.local') !== false) {
        // Disable all caching
        if (!defined('WP_CACHE')) define('WP_CACHE', false);
        
        // Send no-cache headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Disable WordPress object cache
        wp_cache_flush();
    }
}
add_action('init', 'nutrilux_disable_cache_dev');

function nutrilux_live_reload() {
    // Only on local development - check if site is nutrilux10.local
    if (strpos($_SERVER['HTTP_HOST'], 'nutrilux10.local') !== false || 
        strpos($_SERVER['HTTP_HOST'], 'localhost') !== false ||
        strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
        ?>
        <div style="position:fixed;top:10px;right:10px;background:#333;color:#fff;padding:8px 12px;border-radius:4px;font-size:12px;z-index:9999;">
            🔥 DEV MODE - Pritisni F5 za refresh
        </div>
        <script>
        // SIMPLE NOTIFICATION SYSTEM
        console.log('🔥 Nutrilux DEV MODE - Manual refresh required');
        
        // Shortcut za refresh
        document.addEventListener('keydown', function(e) {
            if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
                console.log('🔄 Manual refresh triggered');
            }
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'nutrilux_live_reload');

/**
 * Enqueue scripts and styles
 */
function nutrilux_enqueue_assets() {
    // Development versioning - force cache refresh
    $dev_version = (strpos($_SERVER['HTTP_HOST'], 'nutrilux10.local') !== false) ? 
                   time() : wp_get_theme()->get('Version');
    
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
        $dev_version
    );
    
    // Enqueue layout CSS file
    wp_enqueue_style(
        'nutrilux-layout',
        get_template_directory_uri() . '/assets/css/layout.css',
        array('nutrilux-base'),
        $dev_version
    );
    
    // Enqueue WooCommerce CSS file (if WooCommerce is active)
    if (class_exists('WooCommerce')) {
        wp_enqueue_style(
            'nutrilux-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            array('nutrilux-layout'),
            $dev_version
        );
        
        // Single product page styles
        if (is_product()) {
            wp_enqueue_style(
                'nutrilux-single-product',
                get_template_directory_uri() . '/assets/css/single-product.css',
                array('nutrilux-layout'),
                $dev_version
            );
        }
        
        // Checkout page styles
        if (is_checkout()) {
            wp_enqueue_style(
                'nutrilux-checkout',
                get_template_directory_uri() . '/assets/css/checkout.css',
                array('nutrilux-layout'),
                $dev_version
            );
        }
        
        // About and Contact pages styles
        if (is_page(array('o-nama', 'kontakt')) || is_page_template(array('page-o-nama.php', 'page-kontakt.php'))) {
            wp_enqueue_style(
                'nutrilux-pages',
                get_template_directory_uri() . '/assets/css/pages.css',
                array('nutrilux-layout'),
                $dev_version
            );
        }
    }
    
    // Enqueue main stylesheet
    wp_enqueue_style(
        'nutrilux-style',
        get_stylesheet_uri(),
        array('nutrilux-layout', 'nutrilux-woocommerce'),
        $dev_version
    );
    
    // Enqueue JavaScript
    wp_enqueue_script(
        'nutrilux-site',
        get_template_directory_uri() . '/assets/js/site.js',
        array(),
        $dev_version,
        true
    );
}
add_action('wp_enqueue_scripts', 'nutrilux_enqueue_assets');

/**
 * Fallback menu for primary navigation
 */
function nutrilux_fallback_menu() {
    $current_url = home_url($_SERVER['REQUEST_URI']);
    
    echo '<ul class="nav-menu">';
    
    // Home link
    $home_active = is_front_page() ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/')) . '"' . $home_active . '>' . esc_html__('Početna', 'nutrilux') . '</a></li>';
    
    // Shop link
    if (class_exists('WooCommerce')) {
        $shop_active = (is_shop() || is_product_category() || is_product_tag()) ? ' aria-current="page"' : '';
        echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '"' . $shop_active . '>' . esc_html__('Proizvodi', 'nutrilux') . '</a></li>';
    }
    
    // About page
    $about_active = is_page('o-nama') ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/o-nama/')) . '"' . $about_active . '>' . esc_html__('O nama', 'nutrilux') . '</a></li>';
    
    // Contact page
    $contact_active = is_page('kontakt') ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/kontakt/')) . '"' . $contact_active . '>' . esc_html__('Kontakt', 'nutrilux') . '</a></li>';
    
    echo '</ul>';
}

/**
 * Fallback menu for footer navigation
 */
function nutrilux_footer_fallback_menu() {
    echo '<ul class="footer-menu">';
    
    // Home link
    $home_active = is_front_page() ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/')) . '"' . $home_active . '>' . esc_html__('Početna', 'nutrilux') . '</a></li>';
    
    // Shop link
    if (class_exists('WooCommerce')) {
        $shop_active = (is_shop() || is_product_category() || is_product_tag()) ? ' aria-current="page"' : '';
        echo '<li><a href="' . esc_url(get_permalink(wc_get_page_id('shop'))) . '"' . $shop_active . '>' . esc_html__('Proizvodi', 'nutrilux') . '</a></li>';
    }
    
    // About page
    $about_active = is_page('o-nama') ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/o-nama/')) . '"' . $about_active . '>' . esc_html__('O nama', 'nutrilux') . '</a></li>';
    
    // Contact page
    $contact_active = is_page('kontakt') ? ' aria-current="page"' : '';
    echo '<li><a href="' . esc_url(home_url('/kontakt/')) . '"' . $contact_active . '>' . esc_html__('Kontakt', 'nutrilux') . '</a></li>';
    
    echo '</ul>';
}
