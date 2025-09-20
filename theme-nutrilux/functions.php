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

// Prevod WooCommerce stringova na single product page
add_filter('gettext', function($translated, $text, $domain) {
    if ($domain === 'woocommerce') {
        switch ($text) {
            case 'Add to cart':
                return 'Dodaj u korpu';
            case 'Description':
                return 'Opis proizvoda';
            case 'Additional information':
                return 'Dodatne informacije';
            case 'Reviews':
                return 'Recenzije';
            case 'Related products':
                return 'Povezani proizvodi';
        }
    }
    return $translated;
}, 10, 3);

// Admin tools: new product lineup seeder (Premium, Gold, Zero)
require_once get_template_directory() . '/inc/product-seed-nx.php';
// WooCommerce configuration and helpers
if (file_exists(get_template_directory() . '/inc/woocommerce.php')) {
    require_once get_template_directory() . '/inc/woocommerce.php';
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
}
add_action('after_setup_theme', 'nutrilux_theme_setup');

/* === Auto-seed new lineup on theme activation (one-time) === */
add_action('after_switch_theme', function(){
    if (function_exists('nutrilux_seed_new_lineup') && class_exists('WooCommerce')) {
        if (!get_option('nutrilux_auto_seed_done')) {
            nutrilux_seed_new_lineup();
            update_option('nutrilux_auto_seed_done', true);
        }
    }
    // Ensure About and Contact pages exist and use our templates
    nutrilux_ensure_info_pages();
});

// Also run on init in case pages are missing
add_action('init', function() {
    static $run_once = false;
    if (!$run_once) {
        nutrilux_ensure_info_pages();
        $run_once = true;
    }
});

/**
 * Ensure "O nama" and "Kontakt" pages exist and are using the theme templates.
 */
function nutrilux_ensure_info_pages() {
    $pages_to_create = [
        [
            'title' => 'O nama',
            'slug'  => 'o-nama',
            'template' => 'page-o-nama.php',
            'content' => 'Stranica o nama će biti generirana automatski preko template fajla.',
        ],
        [
            'title' => 'Kontakt',
            'slug'  => 'kontakt',
            'template' => 'page-kontakt.php',
            'content' => 'Kontakt stranica će biti generirana automatski preko template fajla.',
        ],
    ];

    $created_ids = [];
    foreach ($pages_to_create as $cfg) {
        $page = get_page_by_path($cfg['slug']);
        if (!$page) {
            $pid = wp_insert_post([
                'post_title'   => $cfg['title'],
                'post_name'    => $cfg['slug'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_content' => $cfg['content'],
                'post_author'  => 1,
            ]);
            if (!is_wp_error($pid)) {
                // For slug-based templates, we don't need to set _wp_page_template
                // WordPress will automatically use page-{slug}.php if it exists
                $created_ids[] = $pid;
                
                // Debug log
                error_log("Nutrilux: Created page '{$cfg['title']}' with ID {$pid} and slug '{$cfg['slug']}'");
            } else {
                error_log("Nutrilux: Failed to create page '{$cfg['title']}': " . $pid->get_error_message());
            }
        } else {
            // Page exists, ensure it has some content
            if (empty($page->post_content)) {
                wp_update_post([
                    'ID' => $page->ID,
                    'post_content' => $cfg['content']
                ]);
            }
            error_log("Nutrilux: Page '{$cfg['title']}' already exists with ID {$page->ID}");
        }
    }

    // Add to primary menu if assigned
    $locations = get_nav_menu_locations();
    if (!empty($locations['primary'])) {
        $menu_id = $locations['primary'];
        foreach ($pages_to_create as $cfg) {
            $page = get_page_by_path($cfg['slug']);
            if ($page) {
                // Avoid duplicate menu items
                $items = wp_get_nav_menu_items($menu_id);
                $exists = false;
                if ($items) {
                    foreach ($items as $it) { if (intval($it->object_id) === intval($page->ID)) { $exists = true; break; } }
                }
                if (!$exists) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title'  => $cfg['title'],
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $page->ID,
                        'menu-item-type'   => 'post_type',
                        'menu-item-status' => 'publish',
                    ]);
                }
            }
        }
    }
}

/**
 * Force template selection for our pages
 */
add_filter('template_include', function($template) {
    if (is_page()) {
        global $post;
        $page_slug = $post->post_name;
        
        // Check if we have a specific template for this page slug
        $custom_template = get_template_directory() . '/page-' . $page_slug . '.php';
        
        if (file_exists($custom_template)) {
            error_log("Nutrilux: Using custom template for page '{$page_slug}': {$custom_template}");
            return $custom_template;
        }
        
        error_log("Nutrilux: No custom template found for page '{$page_slug}', using default: {$template}");
    }
    
    return $template;
});

/**
 * Debug template loading
 */
add_action('wp_head', function() {
    if (is_page() && current_user_can('manage_options')) {
        global $post;
        echo "<!-- NUTRILUX DEBUG: Page ID: {$post->ID}, Slug: {$post->post_name}, Template: " . get_page_template_slug($post->ID) . " -->";
    }
});

/**
 * Enqueue scripts and styles
 */
function nutrilux_enqueue_assets() {
    // Main stylesheet
    wp_enqueue_style('nutrilux-style', get_stylesheet_uri(), [], '1.0.0');
    
    // Base CSS
    wp_enqueue_style(
        'nutrilux-base',
        get_template_directory_uri() . '/assets/css/base.css',
        [],
        '1.0.0'
    );
    
    // Layout CSS
    wp_enqueue_style(
        'nutrilux-layout',
        get_template_directory_uri() . '/assets/css/layout.css',
        ['nutrilux-base'],
        '1.0.0'
    );
    
    // Checkout CSS
    wp_enqueue_style(
        'nutrilux-checkout',
        get_template_directory_uri() . '/assets/css/checkout.css',
        ['nutrilux-layout'],
        '1.0.0'
    );
    
    // WooCommerce CSS
    if (class_exists('WooCommerce')) {
        wp_enqueue_style(
            'nutrilux-woocommerce',
            get_template_directory_uri() . '/assets/css/woocommerce.css',
            ['nutrilux-layout'],
            '1.0.0'
        );
    }
    
    // Main JavaScript
    wp_enqueue_script(
        'nutrilux-site',
        get_template_directory_uri() . '/assets/js/site.js',
        ['jquery'],
        '1.0.0',
        true
    );
    
    // Localize script for AJAX functionality
    wp_localize_script('nutrilux-site', 'nutrilux_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('nutrilux_ajax_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'nutrilux_enqueue_assets');

/* === Show placeholder price text when missing === */
add_filter('woocommerce_get_price_html', function($price, $product){
    if ($price === '' || $price === null) {
        return '<span class="price-soon">Cijena uskoro</span>';
    }
    return $price;
}, 10, 2);

/* === Prevent purchase when no price set (keeps products visible) === */
add_filter('woocommerce_is_purchasable', function($purchasable, $product){
    // Simple products with no price
    $p = $product->get_price();
    if ($p === '' || $p === null) {
        return false;
    }
    // Variable products: require at least one variation with price
    if ($product->is_type('variable')) {
        $children = $product->get_children();
        $has_priced = false;
        foreach ($children as $vid) {
            $v = wc_get_product($vid);
            if ($v && $v->get_price() !== '' && $v->get_price() !== null) { $has_priced = true; break; }
        }
        return $has_priced;
    }
    return $purchasable;
}, 10, 2);

/* === CHECKOUT CSS DIREKTNO U HEAD === */
add_action('wp_head', function() {
    if (is_checkout()) {
        ?>
        <style>
        .checkout-hero {
            background: linear-gradient(180deg, #FFFBE7 0%, #FFFFFF 100%);
            padding: 40px 0 24px;
        }
        .checkout-title {
            margin: 0;
            font-size: clamp(2rem, 5vw, 2.9rem);
            font-weight: 700;
            color: #1E2124;
            text-align: center;
        }
        .section-heading {
            font-size: 1.2rem;
            margin: 0 0 20px;
            font-weight: 600;
            color: #2A2E33;
        }
        .checkout-layout {
            display: flex;
            flex-direction: column;
            gap: 40px;
            padding: 32px 0 56px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .checkout-summary {
            background: #fff;
            border: 1px solid #E9E3D9;
            border-radius: 16px;
            padding: 24px;
        }
        .woocommerce-checkout input.input-text,
        .woocommerce-checkout select {
            width: 100%;
            border: 1px solid #E2DCCC;
            background: #fff;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }
        .woocommerce-checkout input:focus,
        .woocommerce-checkout select:focus {
            border-color: #F5C542;
            box-shadow: 0 0 0 3px rgba(245, 197, 66, 0.25);
            outline: none;
        }
        .woocommerce-checkout label {
            font-weight: 500;
            color: #2A2E33;
            margin-bottom: 6px;
            display: block;
        }
        #place_order {
            background: #F5C542 !important;
            color: #121212 !important;
            font-weight: 600;
            border: 0;
            border-radius: 10px;
            padding: 16px 20px;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
        }
        #place_order:hover {
            background: #E2B838 !important;
        }
        .woocommerce-checkout-review-order-table th,
        .woocommerce-checkout-review-order-table td {
            padding: 10px 0;
            border-bottom: 1px solid #EEE5DA;
        }
        .order-total th,
        .order-total td {
            font-weight: 700;
            font-size: 1.1rem;
            color: #1E2124;
        }
        @media (min-width: 1080px) {
            .checkout-layout {
                flex-direction: row;
                align-items: flex-start;
            }
            .checkout-main {
                flex: 1 1 auto;
                max-width: 700px;
            }
            .checkout-summary {
                flex: 0 0 350px;
                position: sticky;
                top: 90px;
            }
        }
        </style>
        <?php
    }
});

/* === CUSTOM CHECKOUT PAGE CONTENT === */
add_filter('the_content', function($content) {
    if (is_checkout() && in_the_loop() && is_main_query()) {
        ob_start();
        ?>
        <section class="checkout-hero">
            <div class="wrap">
                <h1 class="checkout-title">Plaćanje</h1>
            </div>
        </section>

        <div class="checkout-layout wrap">
            <div class="checkout-main">
                <h2 class="section-heading">Podaci za dostavu i plaćanje</h2>
                <?php woocommerce_checkout(); ?>
            </div>
            
            <div class="checkout-summary">
                <h2 class="section-heading">Sažetak narudžbe</h2>
                <div id="order_review">
                    <?php woocommerce_order_review(); ?>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    return $content;
});

/* ========================================================================
   CHECKOUT REFINEMENT - BOSANSKA LOKALIZACIJA, BIH ONLY, COD ONLY
   ======================================================================== */

/* === CHECKOUT: Default Country (BA) without hard restriction === */

add_filter('default_checkout_billing_country', function() {
    return 'BA';
});

/* === CHECKOUT: Fields refinement === */
add_filter('woocommerce_checkout_fields', function($fields) {
    // Ukloni nepotrebna polja
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    
    // Poštanski broj neobavezan
    if(isset($fields['billing']['billing_postcode'])) {
        $fields['billing']['billing_postcode']['required'] = false;
        $fields['billing']['billing_postcode']['label'] = 'Poštanski broj (opcionalno)';
        $fields['billing']['billing_postcode']['placeholder'] = '71000';
        $fields['billing']['billing_postcode']['priority'] = 70;
    }
    
    // Telefonski obavezan
    if(isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['required'] = true;
        $fields['billing']['billing_phone']['label'] = 'Telefon *';
        $fields['billing']['billing_phone']['placeholder'] = 'npr. +387 61 000 000';
        $fields['billing']['billing_phone']['priority'] = 60;
    }
    
    // Ime / Prezime
    if(isset($fields['billing']['billing_first_name'])) {
        $fields['billing']['billing_first_name']['label'] = 'Ime *';
        $fields['billing']['billing_first_name']['placeholder'] = 'Vaše ime';
        $fields['billing']['billing_first_name']['priority'] = 10;
    }
    
    if(isset($fields['billing']['billing_last_name'])) {
        $fields['billing']['billing_last_name']['label'] = 'Prezime *';
        $fields['billing']['billing_last_name']['placeholder'] = 'Vaše prezime';
        $fields['billing']['billing_last_name']['priority'] = 20;
    }
    
    if(isset($fields['billing']['billing_address_1'])) {
        $fields['billing']['billing_address_1']['label'] = 'Adresa *';
        $fields['billing']['billing_address_1']['placeholder'] = 'Ulica i broj';
        $fields['billing']['billing_address_1']['priority'] = 40;
    }
    
    if(isset($fields['billing']['billing_city'])) {
        $fields['billing']['billing_city']['label'] = 'Grad *';
        $fields['billing']['billing_city']['placeholder'] = 'Vaš grad';
        $fields['billing']['billing_city']['priority'] = 50;
    }
    
    if(isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['label'] = 'Email *';
        $fields['billing']['billing_email']['placeholder'] = 'vasa.adresa@email.com';
        $fields['billing']['billing_email']['priority'] = 30;
    }
    
    if(isset($fields['billing']['billing_country'])) {
        $fields['billing']['billing_country']['label'] = 'Država';
        $fields['billing']['billing_country']['priority'] = 80;
    }
    
    // Notes
    if(isset($fields['order']['order_comments'])) {
        $fields['order']['order_comments']['label'] = 'Napomena uz narudžbu (opcionalno)';
        $fields['order']['order_comments']['placeholder'] = 'Dodatne upute za dostavu...';
    }
    
    return $fields;
});

/* === CHECKOUT: Only COD (Cash on Delivery) === */
add_filter('woocommerce_available_payment_gateways', function($gateways) {
    foreach($gateways as $id => $gateway) {
        if($id !== 'cod') {
            unset($gateways[$id]);
        } else {
            $gateways[$id]->title = 'Plaćanje pouzećem (brza pošta)';
            $gateways[$id]->description = 'Plaćate gotovinom pri preuzimanju paketa.';
        }
    }
    return $gateways;
});

/* === GETTEXT Lokalizacija dodatna (fallback ako još negdje ostane EN) === */
add_filter('gettext', 'nutrilux_checkout_texts', 10, 3);
function nutrilux_checkout_texts($translated, $original, $domain) {
    $map = [
        'Checkout' => 'Plaćanje',
        'Place order' => 'Potvrdi narudžbu',
        'Billing details' => 'Podaci za naplatu',
        'Billing details.' => 'Podaci za naplatu',
        'Order notes (optional)' => 'Napomena uz narudžbu (opcionalno)',
        'Additional information' => 'Dodatne informacije',
        'Your order' => 'Sažetak narudžbe',
        'Subtotal' => 'Međuzbroj',
        'Total' => 'Ukupno',
        'Phone' => 'Telefon',
        'Email address' => 'Email',
        'First name' => 'Ime',
        'Last name' => 'Prezime',
        'Address' => 'Adresa',
        'Postcode' => 'Poštanski broj',
        'City' => 'Grad',
        'Country / Region' => 'Država',
        'Country' => 'Država',
        'I have read and agree to the website terms and conditions' => 'Slažem se s uslovima korištenja',
        'Cash on delivery' => 'Plaćanje pouzećem',
        'Pay with cash upon delivery.' => 'Plaćate gotovinom pri preuzimanju paketa.',
        'Payment method' => 'Metod plaćanja',
        'Review your order' => 'Pregledajte narudžbu',
        'Product' => 'Proizvod',
        'Quantity' => 'Količina',
        'Price' => 'Cijena',
        'Order total' => 'Ukupno za plaćanje',
        'Shipping' => 'Dostava',
        'Free' => 'Besplatna'
    ];
    
    if(isset($map[$original])) {
        return $map[$original];
    }
    
    return $translated;
}

/* === ADMIN OPTION: Order notification email === */
add_action('admin_init', function() {
    register_setting('general', 'nutrilux_order_notification_email', [
        'type' => 'string',
        'sanitize_callback' => 'sanitize_email',
        'default' => '90minutesenjoy@gmail.com'
    ]);
    
    add_settings_field(
        'nutrilux_order_notification_email',
        'Nutrilux Order Email',
        function() {
            $val = esc_attr(get_option('nutrilux_order_notification_email', '90minutesenjoy@gmail.com'));
            echo '<input type="email" name="nutrilux_order_notification_email" value="' . $val . '" class="regular-text" />';
            echo '<p class="description">Email na koji dolaze obavijesti o novim narudžbama (trenutno test).</p>';
        },
        'general'
    );
});

/* === Override New Order recipient === */
add_filter('woocommerce_email_recipient_new_order', function($recipient, $order) {
    $custom = get_option('nutrilux_order_notification_email');
    if($custom) {
        return $custom; // zamijeni primatelja
    }
    return $recipient;
}, 10, 2);

/* === PLACE ORDER BUTTON TEXT (sigurni fallback) === */
add_filter('woocommerce_order_button_text', function() {
    return 'Potvrdi narudžbu';
});

/* === Force phone field validation server-side === */
add_action('woocommerce_checkout_process', function() {
    if(empty($_POST['billing_phone'])) {
        wc_add_notice('Molimo unesite broj telefona.', 'error');
    }
});

/* === Disable shipping (we only do local delivery) === */
add_filter('woocommerce_cart_needs_shipping', '__return_false');

/* === Hide shipping fields completely === */
add_filter('woocommerce_checkout_fields', function($fields) {
    unset($fields['shipping']);
    return $fields;
}, 20);

/* === KOMPLETNO PREPISIVANJE CHECKOUT STRANICE === */
add_action('wp', function() {
    if (is_checkout() && !is_wc_endpoint_url()) {
        remove_all_actions('woocommerce_checkout_order_review');
        remove_all_actions('woocommerce_checkout_billing');
        remove_all_actions('woocommerce_checkout_payment');
        
        // Dodaj naše custom akcije
        add_action('woocommerce_checkout_billing', 'nutrilux_custom_billing_fields');
        add_action('woocommerce_checkout_payment', 'nutrilux_custom_payment_section');
        add_action('woocommerce_checkout_order_review', 'nutrilux_custom_order_review');
    }
}, 5);

function nutrilux_custom_billing_fields() {
    $checkout = WC()->checkout();
    ?>
    <div class="woocommerce-billing-fields">
        <p class="form-row form-row-first validate-required">
            <label for="billing_first_name">Ime <abbr class="required" title="required">*</abbr></label>
            <input type="text" class="input-text" name="billing_first_name" id="billing_first_name" placeholder="Vaše ime" value="<?php echo esc_attr($checkout->get_value('billing_first_name')); ?>" required />
        </p>
        
        <p class="form-row form-row-last validate-required">
            <label for="billing_last_name">Prezime <abbr class="required" title="required">*</abbr></label>
            <input type="text" class="input-text" name="billing_last_name" id="billing_last_name" placeholder="Vaše prezime" value="<?php echo esc_attr($checkout->get_value('billing_last_name')); ?>" required />
        </p>
        
        <p class="form-row form-row-wide validate-required validate-email">
            <label for="billing_email">Email <abbr class="required" title="required">*</abbr></label>
            <input type="email" class="input-text" name="billing_email" id="billing_email" placeholder="vasa.adresa@email.com" value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>" required />
        </p>
        
        <p class="form-row form-row-wide validate-required validate-phone">
            <label for="billing_phone">Telefon <abbr class="required" title="required">*</abbr></label>
            <input type="tel" class="input-text" name="billing_phone" id="billing_phone" placeholder="npr. +387 61 000 000" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>" required />
        </p>
        
        <p class="form-row form-row-wide validate-required">
            <label for="billing_address_1">Adresa <abbr class="required" title="required">*</abbr></label>
            <input type="text" class="input-text" name="billing_address_1" id="billing_address_1" placeholder="Ulica i broj" value="<?php echo esc_attr($checkout->get_value('billing_address_1')); ?>" required />
        </p>
        
        <p class="form-row form-row-first validate-required">
            <label for="billing_city">Grad <abbr class="required" title="required">*</abbr></label>
            <input type="text" class="input-text" name="billing_city" id="billing_city" placeholder="Vaš grad" value="<?php echo esc_attr($checkout->get_value('billing_city')); ?>" required />
        </p>
        
        <p class="form-row form-row-last">
            <label for="billing_postcode">Poštanski broj (opcionalno)</label>
            <input type="text" class="input-text" name="billing_postcode" id="billing_postcode" placeholder="71000" value="<?php echo esc_attr($checkout->get_value('billing_postcode')); ?>" />
        </p>
        
        <input type="hidden" name="billing_country" value="BA" />
    </div>
    <?php
}

function nutrilux_custom_payment_section() {
    ?>
    <div id="payment" class="woocommerce-checkout-payment">
        <ul class="wc_payment_methods payment_methods methods">
            <li class="wc_payment_method payment_method_cod">
                <input id="payment_method_cod" type="radio" class="input-radio" name="payment_method" value="cod" checked="checked" />
                <label for="payment_method_cod">Plaćanje pouzećem (brza pošta)</label>
                <div class="payment_box payment_method_cod">
                    <p>Plaćate gotovinom pri preuzimanju paketa.</p>
                </div>
            </li>
        </ul>
        
        <div class="form-row place-order">
            <noscript>
                Since your browser does not support JavaScript, or it is disabled, please ensure you click the <em>Update Totals</em> button before placing your order.
            </noscript>
            
            <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
            
            <button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="Potvrdi narudžbu" data-value="Potvrdi narudžbu">Potvrdi narudžbu</button>
        </div>
    </div>
    <?php
}

function nutrilux_custom_order_review() {
    ?>
    <table class="shop_table woocommerce-checkout-review-order-table">
        <thead>
            <tr>
                <th class="product-name">Proizvod</th>
                <th class="product-total">Ukupno</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                if ($_product && $_product->exists() && $cart_item['quantity'] > 0) {
                    ?>
                    <tr class="cart_item">
                        <td class="product-name">
                            <?php echo wp_kses_post($_product->get_name()); ?>&nbsp;
                            <strong class="product-quantity">× <?php echo $cart_item['quantity']; ?></strong>
                        </td>
                        <td class="product-total">
                            <?php echo WC()->cart->get_product_subtotal($_product, $cart_item['quantity']); ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="cart-subtotal">
                <th>Međuzbroj</th>
                <td><?php wc_cart_totals_subtotal_html(); ?></td>
            </tr>
            
            <tr class="order-total">
                <th>Ukupno</th>
                <td><?php wc_cart_totals_order_total_html(); ?></td>
            </tr>
        </tfoot>
    </table>
    <?php
}

/* ========================================================================
   PACK SIZE SELECTOR (250g / 500g) – simple, no variations needed
   ======================================================================== */

// Render a size select on single product pages
add_action('woocommerce_before_add_to_cart_button', function(){
    // Only for purchasable SIMPLE products; variable products will use variations UI
    global $product;
    if (!$product || !$product->is_purchasable()) return;
    if (!$product->is_type('simple')) return;
    $sizes = [ '250g' => '250g', '500g' => '500g' ];
    echo '<div class="nutri-pack-field" style="margin:10px 0 8px">';
    echo '<label for="nutri_pack_size" style="display:block; font-weight:600; margin-bottom:6px;">Pakovanje</label>';
    echo '<select name="nutri_pack_size" id="nutri_pack_size" style="min-width:180px; padding:8px 10px; border:1px solid #E2DCCC; border-radius:8px;">';
    foreach($sizes as $val => $label){
        $selected = ($val==='250g') ? ' selected' : '';
        echo '<option value="'.esc_attr($val).'"'.$selected.'>'.esc_html($label).'</option>';
    }
    echo '</select>';
    echo '</div>';
});

// Save the selected pack size into the cart item
add_filter('woocommerce_add_cart_item_data', function($cart_item_data, $product_id, $variation_id){
    if (isset($_POST['nutri_pack_size']) && $_POST['nutri_pack_size'] !== '') {
        $cart_item_data['nutri_pack_size'] = sanitize_text_field($_POST['nutri_pack_size']);
    }
    return $cart_item_data;
}, 10, 3);

// Show pack size in cart/checkout line items (frontend)
add_filter('woocommerce_get_item_data', function($item_data, $cart_item){
    if (!empty($cart_item['nutri_pack_size'])){
        $item_data[] = array(
            'name'  => 'Pakovanje',
            'value' => $cart_item['nutri_pack_size']
        );
    }
    return $item_data;
}, 10, 2);

// Persist pack size into the order items
add_action('woocommerce_checkout_create_order_line_item', function($item, $cart_item_key, $values, $order){
    if (!empty($values['nutri_pack_size'])){
        $item->add_meta_data('Pakovanje', $values['nutri_pack_size'], true);
    }
}, 10, 4);

/* === DIREKTNO FORSIRANJE CHECKOUT TEMPLATE === */
add_action('template_redirect', function() {
    if (is_checkout() && !is_wc_endpoint_url()) {
        // Forsiranje našeg page-checkout.php umjesto WooCommerce template
        add_filter('template_include', function($template) {
            $custom_checkout = get_template_directory() . '/page-checkout.php';
            if (file_exists($custom_checkout)) {
                return $custom_checkout;
            }
            return $template;
        }, 99);
    }
});

/* === Alternative: Hook into checkout form directly === */
remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
add_action('woocommerce_checkout_order_review', function() {
    echo '<div class="custom-checkout-refinement">';
    echo '<h2 class="section-heading">Sažetak narudžbe</h2>';
    woocommerce_order_review();
    echo '</div>';
}, 10);

add_action('woocommerce_checkout_before_order_review', function() {
    echo '<div class="payment-section">';
    echo '<h2 class="section-heading">Metod plaćanja</h2>';
    woocommerce_checkout_payment();
    echo '</div>';
}, 5);

/* === Forsiranje checkout page title === */
add_filter('the_title', function($title, $id) {
    if (is_checkout() && in_the_loop()) {
        return 'Plaćanje';
    }
    return $title;
}, 10, 2);

/* === Force template override === */
add_filter('woocommerce_locate_template', function($template, $template_name, $template_path) {
    if ($template_name === 'checkout/form-checkout.php') {
        $custom_template = get_template_directory() . '/woocommerce/checkout/form-checkout.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }
    return $template;
}, 10, 3);

/* === Debug checkout template === */
add_action('wp_head', function() {
    if (is_checkout()) {
        echo '<!-- NUTRILUX CHECKOUT REFINEMENT ACTIVE -->';
        echo '<!-- Template: ' . get_template_directory() . '/woocommerce/checkout/form-checkout.php -->';
        if (file_exists(get_template_directory() . '/woocommerce/checkout/form-checkout.php')) {
            echo '<!-- Custom checkout template EXISTS -->';
        } else {
            echo '<!-- Custom checkout template NOT FOUND -->';
        }
    }
});

/* === Clear WooCommerce cache === */
add_action('init', function() {
    if (isset($_GET['clear_woo_cache']) && current_user_can('manage_options')) {
        wc_delete_shop_order_transients();
        wc_delete_product_transients();
        wp_redirect(home_url());
        exit;
    }
});

/* === Add to Cart AJAX Handler for Simple Products === */
add_action('wp_ajax_nutrilux_add_to_cart', 'nutrilux_handle_add_to_cart');
add_action('wp_ajax_nopriv_nutrilux_add_to_cart', 'nutrilux_handle_add_to_cart');

function nutrilux_handle_add_to_cart() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'nutrilux_ajax_nonce')) {
        wp_die('Security check failed');
    }
    
    $product_id = absint($_POST['product_id']);
    $quantity = absint($_POST['quantity']) ?: 1;
    
    if (!$product_id) {
        wp_send_json_error('Invalid product ID');
    }
    
    $product = wc_get_product($product_id);
    if (!$product || !$product->exists()) {
        wp_send_json_error('Product not found');
    }
    
    // Check if product is in stock
    if (!$product->is_in_stock()) {
        wp_send_json_error('Product out of stock');
    }
    
    // Add simple product to cart
    $result = WC()->cart->add_to_cart($product_id, $quantity);
    
    if ($result) {
        // Get cart fragments for mini cart update
        wc_add_to_cart_message(array($product_id => $quantity), true);
        
        wp_send_json_success(array(
            'message' => 'Product added to cart',
            'cart_count' => WC()->cart->get_cart_contents_count(),
            'cart_total' => WC()->cart->get_cart_total(),
            'product_name' => $product->get_name(),
            'fragments' => apply_filters('woocommerce_add_to_cart_fragments', array())
        ));
    } else {
        wp_send_json_error('Failed to add product to cart');
    }
}

/**
 * Admin debug tool for pages
 */
add_action('admin_menu', function(){
    add_management_page('Nutrilux Pages Debug', 'Pages Debug', 'manage_options', 'nutrilux-pages-debug', function(){
        if (isset($_POST['create_pages']) && check_admin_referer('nutrilux_pages_debug')) {
            nutrilux_ensure_info_pages();
            echo '<div class="notice notice-success"><p>Pages creation function called!</p></div>';
        }
        
        if (isset($_POST['check_templates']) && check_admin_referer('nutrilux_pages_debug')) {
            $template_dir = get_template_directory();
            $kontakt_exists = file_exists($template_dir . '/page-kontakt.php');
            $o_nama_exists = file_exists($template_dir . '/page-o-nama.php');
            
            echo '<div class="notice notice-info">';
            echo '<p>Template directory: ' . $template_dir . '</p>';
            echo '<p>page-kontakt.php exists: ' . ($kontakt_exists ? 'YES' : 'NO') . '</p>';
            echo '<p>page-o-nama.php exists: ' . ($o_nama_exists ? 'YES' : 'NO') . '</p>';
            echo '</div>';
        }
        
        ?>
        <div class="wrap">
            <h1>Nutrilux Pages Debug</h1>
            
            <h2>Check Pages Status</h2>
            <?php
            $kontakt_page = get_page_by_path('kontakt');
            $o_nama_page = get_page_by_path('o-nama');
            ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Exists</th>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Template</th>
                        <th>URL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kontakt</td>
                        <td><?php echo $kontakt_page ? 'YES' : 'NO'; ?></td>
                        <td><?php echo $kontakt_page ? $kontakt_page->ID : '-'; ?></td>
                        <td><?php echo $kontakt_page ? $kontakt_page->post_status : '-'; ?></td>
                        <td><?php echo $kontakt_page ? get_page_template_slug($kontakt_page->ID) : '-'; ?></td>
                        <td><?php echo $kontakt_page ? get_permalink($kontakt_page->ID) : '-'; ?></td>
                    </tr>
                    <tr>
                        <td>O nama</td>
                        <td><?php echo $o_nama_page ? 'YES' : 'NO'; ?></td>
                        <td><?php echo $o_nama_page ? $o_nama_page->ID : '-'; ?></td>
                        <td><?php echo $o_nama_page ? $o_nama_page->post_status : '-'; ?></td>
                        <td><?php echo $o_nama_page ? get_page_template_slug($o_nama_page->ID) : '-'; ?></td>
                        <td><?php echo $o_nama_page ? get_permalink($o_nama_page->ID) : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
            
            <h2>Actions</h2>
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('nutrilux_pages_debug'); ?>
                <button class="button button-primary" name="create_pages" value="1">Create/Update Pages</button>
            </form>
            
            <form method="post">
                <?php wp_nonce_field('nutrilux_pages_debug'); ?>
                <button class="button button-secondary" name="check_templates" value="1">Check Templates</button>
            </form>
        </div>
        <?php
    });
});
