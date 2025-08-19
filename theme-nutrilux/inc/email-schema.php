<?php
/**
 * Email Branding & Schema JSON-LD
 * Custom email headers/footers and structured data markup
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Global flag to prevent duplicate schema output
$nutrilux_schema_added = false;

/**
 * Custom email header with Nutrilux branding
 */
function nutrilux_custom_email_header($email_heading, $email) {
    // Only customize WooCommerce emails
    if (!is_a($email, 'WC_Email')) {
        return $email_heading;
    }
    
    $header_html = '
    <div style="background: linear-gradient(135deg, #1a5d1a, #2d8f47); padding: 40px 20px; text-align: center; margin-bottom: 30px;">
        <div style="max-width: 600px; margin: 0 auto;">
            <h1 style="color: #ffffff; font-family: \'Poppins\', Arial, sans-serif; font-size: 2.5rem; font-weight: 700; margin: 0; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                Nutrilux
            </h1>
            <p style="color: #e8f5e8; font-family: \'Inter\', Arial, sans-serif; font-size: 1.1rem; margin: 10px 0 0 0; opacity: 0.9;">
                Premium rješenja od jaja u prahu
            </p>
        </div>
    </div>';
    
    if (!empty($email_heading)) {
        $header_html .= '
        <div style="max-width: 600px; margin: 0 auto 30px; padding: 0 20px;">
            <h2 style="color: #2d5a2d; font-family: \'Poppins\', Arial, sans-serif; font-size: 1.5rem; font-weight: 600; margin: 0; text-align: center;">
                ' . esc_html($email_heading) . '
            </h2>
        </div>';
    }
    
    return $header_html;
}
add_filter('woocommerce_email_header', 'nutrilux_custom_email_header', 10, 2);

/**
 * Custom email footer with Nutrilux tagline
 */
function nutrilux_custom_email_footer($email) {
    // Only customize WooCommerce emails
    if (!is_a($email, 'WC_Email')) {
        return;
    }
    
    echo '
    <div style="background: #f8f9fa; border-top: 3px solid #2d8f47; padding: 30px 20px; margin-top: 40px; text-align: center;">
        <div style="max-width: 600px; margin: 0 auto;">
            <p style="color: #2d5a2d; font-family: \'Inter\', Arial, sans-serif; font-size: 1rem; font-weight: 600; margin: 0 0 10px 0;">
                Nutrilux – premium rješenja od jaja u prahu.
            </p>
            <p style="color: #6c757d; font-family: \'Inter\', Arial, sans-serif; font-size: 0.9rem; margin: 0; line-height: 1.6;">
                Email: <a href="mailto:info@nutrilux.ba" style="color: #2d8f47; text-decoration: none;">info@nutrilux.ba</a> | 
                Telefon: <a href="tel:+38761234567" style="color: #2d8f47; text-decoration: none;">+387 61 234 567</a>
            </p>
            <p style="color: #adb5bd; font-family: \'Inter\', Arial, sans-serif; font-size: 0.8rem; margin: 15px 0 0 0;">
                © ' . date('Y') . ' Nutrilux. Sva prava zadržana.
            </p>
        </div>
    </div>';
}
add_action('woocommerce_email_footer', 'nutrilux_custom_email_footer');

/**
 * Add Organization Schema JSON-LD to homepage
 */
function nutrilux_add_organization_schema() {
    global $nutrilux_schema_added;
    
    // Only on front page and prevent duplicates
    if (!is_front_page() || $nutrilux_schema_added) {
        return;
    }
    
    $nutrilux_schema_added = true;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => 'Nutrilux',
        'url' => home_url(),
        'logo' => home_url('/wp-content/themes/theme-nutrilux/assets/images/logo.png'), // Placeholder path
        'description' => 'Premium proizvođač dehidriranih jaja u prahu i sportskih suplemenata u Bosni i Hercegovini.',
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'email' => 'info@nutrilux.ba',
            'contactType' => 'customer support',
            'areaServed' => 'BA',
            'availableLanguage' => 'bs'
        ),
        'address' => array(
            '@type' => 'PostalAddress',
            'addressCountry' => 'BA',
            'addressRegion' => 'Sarajevo'
        ),
        'sameAs' => array(
            // Add social media URLs when available
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'nutrilux_add_organization_schema');

/**
 * Add Product Schema JSON-LD to single product pages
 */
function nutrilux_add_product_schema() {
    global $nutrilux_schema_added, $product;
    
    // Only on single product pages and prevent duplicates
    if (!is_product() || $nutrilux_schema_added) {
        return;
    }
    
    $nutrilux_schema_added = true;
    
    // Get current product
    if (!$product) {
        global $woocommerce, $post;
        $product = wc_get_product($post->ID);
    }
    
    if (!$product) {
        return;
    }
    
    // Get product data
    $product_name = $product->get_name();
    $product_description = $product->get_short_description() ?: $product->get_description();
    $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'full');
    $product_url = get_permalink($product->get_id());
    $product_price = $product->get_price();
    $currency = get_woocommerce_currency();
    
    // Get nutritional meta data
    $energy = get_post_meta($product->get_id(), '_nutri_energy_kcal', true);
    $protein = get_post_meta($product->get_id(), '_nutri_protein_g', true);
    $fat = get_post_meta($product->get_id(), '_nutri_fat_g', true);
    $carbs = get_post_meta($product->get_id(), '_nutri_carbs_g', true);
    $ingredients = get_post_meta($product->get_id(), '_nutri_ingredients', true);
    $shelf_life = get_post_meta($product->get_id(), '_nutri_shelf_life', true);
    
    // Build base schema
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product_name,
        'description' => wp_strip_all_tags($product_description),
        'url' => $product_url,
        'brand' => array(
            '@type' => 'Brand',
            'name' => 'Nutrilux'
        ),
        'manufacturer' => array(
            '@type' => 'Organization',
            'name' => 'Nutrilux'
        )
    );
    
    // Add image if available
    if ($product_image && !empty($product_image[0])) {
        $schema['image'] = $product_image[0];
    }
    
    // Add price information
    if ($product_price) {
        $schema['offers'] = array(
            '@type' => 'Offer',
            'price' => $product_price,
            'priceCurrency' => $currency,
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'seller' => array(
                '@type' => 'Organization',
                'name' => 'Nutrilux'
            )
        );
    }
    
    // Add nutritional information if available
    if ($energy || $protein || $fat || $carbs) {
        $nutrition = array(
            '@type' => 'NutritionInformation'
        );
        
        if ($energy) {
            $nutrition['calories'] = $energy . ' kcal';
        }
        if ($protein) {
            $nutrition['proteinContent'] = $protein . ' g';
        }
        if ($fat) {
            $nutrition['fatContent'] = $fat . ' g';
        }
        if ($carbs) {
            $nutrition['carbohydrateContent'] = $carbs . ' g';
        }
        
        $schema['nutrition'] = $nutrition;
    }
    
    // Add additional properties
    if ($ingredients) {
        $schema['additionalProperty'] = array(
            array(
                '@type' => 'PropertyValue',
                'name' => 'Ingredients',
                'value' => $ingredients
            )
        );
        
        if ($shelf_life) {
            $schema['additionalProperty'][] = array(
                '@type' => 'PropertyValue',
                'name' => 'Shelf Life',
                'value' => $shelf_life
            );
        }
    }
    
    // Add category if available
    $product_categories = wp_get_post_terms($product->get_id(), 'product_cat');
    if (!empty($product_categories) && !is_wp_error($product_categories)) {
        $schema['category'] = $product_categories[0]->name;
    }
    
    // Add SKU (placeholder for now)
    $sku = $product->get_sku();
    if ($sku) {
        $schema['sku'] = $sku;
    } else {
        $schema['sku'] = 'NTX-' . $product->get_id(); // Generate placeholder SKU
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'nutrilux_add_product_schema');

/**
 * Reset schema flag for each page load
 */
function nutrilux_reset_schema_flag() {
    global $nutrilux_schema_added;
    $nutrilux_schema_added = false;
}
add_action('wp_head', 'nutrilux_reset_schema_flag', 1);

/**
 * Add breadcrumb schema for product pages
 */
function nutrilux_add_breadcrumb_schema() {
    if (!is_product()) {
        return;
    }
    
    global $post;
    $product = wc_get_product($post->ID);
    
    if (!$product) {
        return;
    }
    
    $breadcrumbs = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => array(
            array(
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Početna',
                'item' => home_url()
            ),
            array(
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Proizvodi',
                'item' => get_permalink(wc_get_page_id('shop'))
            ),
            array(
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->get_name(),
                'item' => get_permalink($product->get_id())
            )
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'nutrilux_add_breadcrumb_schema', 15);

/**
 * Add WebSite schema for search functionality
 */
function nutrilux_add_website_schema() {
    if (!is_front_page()) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'description' => get_bloginfo('description'),
        'inLanguage' => 'bs',
        'potentialAction' => array(
            '@type' => 'SearchAction',
            'target' => array(
                '@type' => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}')
            ),
            'query-input' => 'required name=search_term_string'
        )
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>' . "\n";
}
add_action('wp_head', 'nutrilux_add_website_schema', 20);

/**
 * Email styles for better rendering
 */
function nutrilux_email_styles() {
    echo '
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Poppins:wght@400;500;600;700&display=swap");
    </style>';
}
add_action('woocommerce_email_header', 'nutrilux_email_styles', 5);

/**
 * Log schema output for debugging
 */
function nutrilux_log_schema_output() {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $page_type = '';
        if (is_front_page()) {
            $page_type = 'front_page (Organization + Website schema)';
        } elseif (is_product()) {
            global $post;
            $page_type = 'single_product (Product + Breadcrumb schema) - ID: ' . $post->ID;
        }
        
        if ($page_type) {
            error_log('Nutrilux Schema: Added structured data for ' . $page_type);
        }
    }
}
add_action('wp_footer', 'nutrilux_log_schema_output');
