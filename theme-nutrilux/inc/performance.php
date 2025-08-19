<?php
/**
 * Nutrilux Performance & Accessibility Optimizations
 * P11 Implementation - Font preloading, image optimization, ARIA improvements
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add font preload links in head
 * Preloads critical Poppins and Inter font files for better performance
 */
function nutrilux_preload_fonts() {
    // Preload Poppins Regular (most used)
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/poppins/v20/pxiEyp8kv8JHgFVrJJfecnFHGPc.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    
    // Preload Poppins SemiBold (headings)
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/poppins/v20/pxiByp8kv8JHgFVrLEj6Z1xlFd2JQEk.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    
    // Preload Inter Regular (body text)
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyfAZ9hiJ-Ek-_EeA.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
    
    // Preload Inter Medium (navigation, buttons)
    echo '<link rel="preload" href="https://fonts.gstatic.com/s/inter/v12/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuI6fAZ9hiJ-Ek-_EeA.woff2" as="font" type="font/woff2" crossorigin="anonymous">' . "\n";
}
add_action('wp_head', 'nutrilux_preload_fonts', 1);

/**
 * Add width and height attributes to product images
 * Improves Cumulative Layout Shift (CLS) by preventing layout reflow
 */
function nutrilux_add_image_dimensions($html, $attachment_id, $size, $icon) {
    // Only process if we have valid HTML and attachment ID
    if (empty($html) || empty($attachment_id)) {
        return $html;
    }
    
    // Get image metadata
    $image_meta = wp_get_attachment_metadata($attachment_id);
    
    if (!is_array($image_meta) || empty($image_meta['width']) || empty($image_meta['height'])) {
        return $html;
    }
    
    // Get dimensions for the specific size
    if (is_string($size) && isset($image_meta['sizes'][$size])) {
        $width = $image_meta['sizes'][$size]['width'];
        $height = $image_meta['sizes'][$size]['height'];
    } else {
        $width = $image_meta['width'];
        $height = $image_meta['height'];
    }
    
    // Add dimensions if not already present
    if (!preg_match('/\s(width|height)\s*=/', $html)) {
        $html = preg_replace('/<img/', '<img width="' . esc_attr($width) . '" height="' . esc_attr($height) . '"', $html);
    }
    
    return $html;
}
add_filter('wp_get_attachment_image', 'nutrilux_add_image_dimensions', 10, 4);

/**
 * Add lazy loading to images (fallback for older WordPress versions)
 * WordPress 5.5+ adds loading="lazy" automatically, this is a fallback
 */
function nutrilux_add_lazy_loading($html, $attachment_id, $size, $icon) {
    // Skip if WordPress already handles lazy loading
    if (function_exists('wp_lazy_loading_enabled') && wp_lazy_loading_enabled('img', 'wp_get_attachment_image')) {
        return $html;
    }
    
    // Don't add lazy loading to hero images or above-the-fold content
    if (is_shop() || is_product_category() || is_product()) {
        // Get current image position (rough check)
        global $woocommerce_loop;
        if (isset($woocommerce_loop['loop']) && $woocommerce_loop['loop'] <= 3) {
            return $html; // Skip lazy loading for first 3 products
        }
    }
    
    // Add loading="lazy" if not present
    if (!preg_match('/\sloading\s*=/', $html)) {
        $html = preg_replace('/<img/', '<img loading="lazy"', $html);
    }
    
    return $html;
}
add_filter('wp_get_attachment_image', 'nutrilux_add_lazy_loading', 15, 4);

/**
 * Enhance WooCommerce product images with performance attributes
 */
function nutrilux_woocommerce_image_attributes($html, $attachment_id, $size, $permalink, $icon, $alt) {
    // Add dimensions and lazy loading for WooCommerce images
    $html = nutrilux_add_image_dimensions($html, $attachment_id, $size, $icon);
    $html = nutrilux_add_lazy_loading($html, $attachment_id, $size, $icon);
    
    return $html;
}
add_filter('woocommerce_single_product_image_thumbnail_html', 'nutrilux_woocommerce_image_attributes', 10, 6);

/**
 * Improve accessibility for WooCommerce elements
 */
function nutrilux_woocommerce_accessibility_improvements() {
    // Add ARIA labels to quantity inputs
    add_filter('woocommerce_quantity_input_args', function($args, $product) {
        $args['input_name'] = 'quantity';
        $args['aria_label'] = __('Količina proizvoda', 'nutrilux');
        return $args;
    }, 10, 2);
    
    // Add ARIA labels to add to cart buttons
    add_filter('woocommerce_product_add_to_cart_text', function($text, $product) {
        if (is_shop() || is_product_category()) {
            return sprintf(__('Dodaj %s u korpu', 'nutrilux'), $product->get_name());
        }
        return $text;
    }, 10, 2);
}
add_action('init', 'nutrilux_woocommerce_accessibility_improvements');

/**
 * Add skip links for better keyboard navigation
 */
function nutrilux_add_skip_links() {
    ?>
    <div class="skip-links">
        <a class="skip-link visually-hidden" href="#main"><?php esc_html_e('Preskoči na glavni sadržaj', 'nutrilux'); ?></a>
        <a class="skip-link visually-hidden" href="#site-navigation"><?php esc_html_e('Preskoči na navigaciju', 'nutrilux'); ?></a>
        <?php if (class_exists('WooCommerce') && (is_shop() || is_product_category())) : ?>
            <a class="skip-link visually-hidden" href="#shop-filters"><?php esc_html_e('Preskoči na filtere', 'nutrilux'); ?></a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Convert hex color to RGB array
 */
function nutrilux_hex_to_rgb($hex) {
    $hex = str_replace('#', '', $hex);
    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2))
    ];
}

/**
 * Calculate relative luminance of RGB color
 */
function nutrilux_get_luminance($rgb) {
    $srgb = array_map(function($c) {
        $c = $c / 255;
        return $c <= 0.03928 ? $c / 12.92 : pow(($c + 0.055) / 1.055, 2.4);
    }, $rgb);
    
    return 0.2126 * $srgb['r'] + 0.7152 * $srgb['g'] + 0.0722 * $srgb['b'];
}

/**
 * Calculate contrast ratio between two colors
 */
function nutrilux_calculate_contrast_ratio($color1, $color2) {
    $rgb1 = nutrilux_hex_to_rgb($color1);
    $rgb2 = nutrilux_hex_to_rgb($color2);
    
    $lum1 = nutrilux_get_luminance($rgb1);
    $lum2 = nutrilux_get_luminance($rgb2);
    
    $lighter = max($lum1, $lum2);
    $darker = min($lum1, $lum2);
    
    return ($lighter + 0.05) / ($darker + 0.05);
}

/**
 * Enhance contrast for better accessibility
 * Check if current colors meet WCAG AA standards
 */
function nutrilux_check_color_contrast() {
    
    // Current theme colors
    $primary = '#f5c542';
    $secondary = '#2c3e50';
    $white = '#ffffff';
    $gray_800 = '#343a40';
    
    $contrasts = [
        'primary_on_white' => nutrilux_calculate_contrast_ratio($primary, $white),
        'secondary_on_white' => nutrilux_calculate_contrast_ratio($secondary, $white),
        'gray_on_white' => nutrilux_calculate_contrast_ratio($gray_800, $white),
        'white_on_secondary' => nutrilux_calculate_contrast_ratio($white, $secondary)
    ];
    
    // Store results for potential CSS overrides
    return $contrasts;
}

/**
 * Add CSS custom properties for enhanced contrast if needed
 */
function nutrilux_enhance_contrast_css() {
    $contrasts = nutrilux_check_color_contrast();
    
    // If primary on white doesn't meet AA standard (4.5:1), enhance it
    if ($contrasts['primary_on_white'] < 4.5) {
        ?>
        <style>
        :root {
            --color-primary-accessible: #e6b632; /* Darker primary for better contrast */
            --color-neutral-darker: #2B2B2F; /* Enhanced dark neutral */
        }
        
        /* Enhanced contrast for hero text */
        .hero-title,
        .contact-hero-title,
        .about-hero-title {
            color: var(--color-neutral-darker, #2B2B2F) !important;
        }
        
        /* Enhanced contrast for buttons with primary background */
        .primary-button,
        .woocommerce-Button.button.alt {
            background-color: var(--color-primary-accessible, #e6b632);
        }
        
        /* Enhanced contrast for links */
        a:not(.button):not(.nav-link) {
            color: var(--color-secondary-dark, #1a252f);
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'nutrilux_enhance_contrast_css', 20);

/**
 * Add performance monitoring and optimization hints
 */
function nutrilux_add_performance_hints() {
    // DNS prefetch for external resources
    echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
    echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
    
    // Preconnect to Google Fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin="anonymous">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">' . "\n";
}
add_action('wp_head', 'nutrilux_add_performance_hints', 1);

/**
 * Optimize resource loading order
 */
function nutrilux_optimize_resource_loading() {
    // Move jQuery to footer for better performance
    if (!is_admin()) {
        wp_scripts()->add_data('jquery', 'group', 1);
        wp_scripts()->add_data('jquery-core', 'group', 1);
        wp_scripts()->add_data('jquery-migrate', 'group', 1);
    }
}
add_action('wp_enqueue_scripts', 'nutrilux_optimize_resource_loading', 100);

/**
 * Add structured data for better SEO and accessibility
 */
function nutrilux_add_accessibility_schema() {
    if (is_page()) {
        $page_title = get_the_title();
        $site_name = get_bloginfo('name');
        
        ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebPage",
            "name": "<?php echo esc_js($page_title); ?>",
            "isPartOf": {
                "@type": "WebSite",
                "name": "<?php echo esc_js($site_name); ?>",
                "url": "<?php echo esc_url(home_url('/')); ?>"
            },
            "accessibilityFeature": [
                "skipLinks",
                "keyboardNavigation",
                "highContrast",
                "structuredNavigation"
            ]
        }
        </script>
        <?php
    }
}
add_action('wp_head', 'nutrilux_add_accessibility_schema', 25);
