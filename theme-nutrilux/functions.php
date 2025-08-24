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
 * Product meta helper functions
 */
function nutrilux_get_meta($product_id, $meta_key) {
    return get_post_meta($product_id, $meta_key, true);
}

function nutrilux_get_multiline_meta($product_id, $meta_key) {
    $value = get_post_meta($product_id, $meta_key, true);
    if (empty($value)) {
        return array();
    }
    
    // Split by newlines and filter empty lines
    $lines = explode("\n", $value);
    $lines = array_map('trim', $lines);
    $lines = array_filter($lines);
    
    return $lines;
}

function nutrilux_get_nutritional_info($product_id) {
    return array(
        'energy_kcal' => nutrilux_get_meta($product_id, '_nutri_energy_kcal'),
        'protein_g' => nutrilux_get_meta($product_id, '_nutri_protein_g'),
        'fat_g' => nutrilux_get_meta($product_id, '_nutri_fat_g'),
        'carbs_g' => nutrilux_get_meta($product_id, '_nutri_carbs_g'),
        'fiber_g' => nutrilux_get_meta($product_id, '_nutri_fiber_g'),
        'vitamins' => nutrilux_get_meta($product_id, '_nutri_vitamins'),
        'minerals' => nutrilux_get_meta($product_id, '_nutri_minerals')
    );
}

function nutrilux_get_recipe_info($product_id) {
    $ingredients = nutrilux_get_multiline_meta($product_id, '_nutri_recipe_ingredients');
    $instructions = nutrilux_get_multiline_meta($product_id, '_nutri_recipe_instructions');
    
    return array(
        'title' => nutrilux_get_meta($product_id, '_nutri_recipe_title'),
        'ingredients' => $ingredients,
        'instructions' => $instructions
    );
}

function nutrilux_format_vitamins_minerals($value) {
    if (empty($value)) {
        return array();
    }
    
    // Split by comma and clean up
    $items = explode(',', $value);
    $items = array_map('trim', $items);
    $items = array_filter($items);
    
    return $items;
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
}
add_action('wp_enqueue_scripts', 'nutrilux_enqueue_assets');

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
        
        /* Mobile specific checkout summary improvements */
        @media (max-width: 768px) {
            .checkout-summary {
                margin-top: 32px;
                padding: 20px 18px 24px;
                border-radius: 12px;
            }
            
            .checkout-summary .section-heading {
                font-size: 1rem;
                margin-bottom: 16px;
            }
            
            .woocommerce-checkout-review-order-table th,
            .woocommerce-checkout-review-order-table td {
                padding: 8px 0;
                font-size: 0.9rem;
            }
            
            .order-total th,
            .order-total td {
                font-size: 1rem;
                padding: 12px 0;
            }
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

/* === CHECKOUT: Allowed Country (BiH only) === */
add_filter('woocommerce_countries_allowed_countries', function($countries) {
    return ['BA' => 'Bosna i Hercegovina'];
});

add_filter('woocommerce_countries_shipping_countries', function($countries) {
    return ['BA' => 'Bosna i Hercegovina'];
});

add_filter('default_checkout_billing_country', function() {
    return 'BA';
});

/* === CHECKOUT: Fields refinement === */
add_filter('woocommerce_checkout_fields', function($fields) {
    // Ukloni nepotrebna polja
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    
    // Poštanski broj neobavezan (ovo je za standardni checkout, custom override-ujemo iznad)
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

/* === Ukloni default email na checkout - multiple approaches === */
add_filter('woocommerce_checkout_get_value', function($value, $field) {
    if ($field === 'billing_email' && $value === 'dev-email@wpengine.local') {
        return '';
    }
    return $value;
}, 10, 2);

// Dodatni filter za default billing email
add_filter('default_option_woocommerce_default_customer_address', function($value) {
    if (is_array($value) && isset($value['billing_email'])) {
        $value['billing_email'] = '';
    }
    return $value;
});

// Force override bilo kojeg default email-a
add_action('wp_head', function() {
    if (is_checkout()) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            const emailField = document.getElementById('billing_email');
            if (emailField && emailField.value === 'dev-email@wpengine.local') {
                emailField.value = '';
                console.log('Default email cleared via JavaScript');
            }
        });
        </script>";
    }
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
            <input type="email" class="input-text" name="billing_email" id="billing_email" placeholder="vasa.adresa@email.com" value="<?php 
                $email_value = $checkout->get_value('billing_email');
                if ($email_value === 'dev-email@wpengine.local') {
                    $email_value = '';
                }
                echo esc_attr($email_value); 
            ?>" required />
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
        
        <p class="form-row form-row-last validate-required">
            <label for="billing_postcode">Poštanski broj <abbr class="required" title="required">*</abbr></label>
            <input type="text" class="input-text" name="billing_postcode" id="billing_postcode" placeholder="71000" value="<?php echo esc_attr($checkout->get_value('billing_postcode')); ?>" required />
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
        delete_transient('woocommerce_shipping_methods');
        wp_cache_flush();
        wc_delete_shop_order_transients();
    }
});

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

/**
 * WooCommerce Cart Fragment for Live Updates
 */
function nutrilux_cart_fragment($fragments) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    $total = WC()->cart->get_cart_total();
    $total_raw = wp_strip_all_tags($total);
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-button" aria-label="<?php echo esc_attr(sprintf('Korpa (%d artikala – %s)', $count, $total_raw)); ?>">
        <span class="cart-icon" aria-hidden="true">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" role="img" aria-hidden="true">
                <path d="M7 6h14l-1.5 9h-11z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                <path d="M7 6L6 3H3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <circle cx="10" cy="21" r="1.5" fill="white"/>
                <circle cx="17" cy="21" r="1.5" fill="white"/>
            </svg>
        </span>
        <span class="cart-total" data-cart-total><?php echo wp_kses_post($total); ?></span>
        <span class="cart-badge" data-cart-count><?php echo esc_html($count); ?></span>
    </a>
    <?php
    $fragments['a.cart-button'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'nutrilux_cart_fragment');

/**
 * SHOP REFINEMENT: Shorten Add to Cart text
 */
add_filter('woocommerce_product_add_to_cart_text', 'nutrilux_short_add_to_cart');
add_filter('woocommerce_product_single_add_to_cart_text', 'nutrilux_short_add_to_cart');
function nutrilux_short_add_to_cart($text) {
    return 'Dodaj u korpu';
}

/**
 * SHOP REFINEMENT: Add to cart text cleanup via gettext
 */
add_filter('gettext', 'nutrilux_add_to_cart_cleanup', 10, 3);
function nutrilux_add_to_cart_cleanup($translated, $text, $domain) {
    $targets = [
        'Dodaj %s u korpu',
        'Dodaj "%s" u korpu',
        'Add to cart',
        'Add %s to cart',
        'Add "%s" to your cart'
    ];
    if (in_array($text, $targets, true)) {
        return 'Dodaj u korpu';
    }
    return $translated;
}

/**
 * Modify existing WooCommerce message instead of replacing it
 */
add_filter('wc_add_to_cart_message_html', 'nutrilux_modify_cart_message', 10, 3);
function nutrilux_modify_cart_message($message, $products, $show_qty) {
    return '<div class="woocommerce-message nutrilux-styled-message" role="alert">Dodano u korpu</div>';
}

/**
 * CSS to reposition and restyle the existing WooCommerce message
 */
add_action('wp_head', function() {
    ?>
    <style>
    /* Reposition and restyle the existing WooCommerce message */
    .woocommerce-message.nutrilux-styled-message {
        position: fixed !important;
        bottom: 20px !important;
        left: 20px !important;
        background: #28a745 !important;
        color: white !important;
        padding: 12px 20px !important;
        border-radius: 8px !important;
        font-size: 0.9rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
        z-index: 9999 !important;
        animation: slideInUp 0.3s ease-out !important;
        max-width: 200px !important;
        text-align: center !important;
        border: none !important;
        margin: 0 !important;
        width: auto !important;
        right: auto !important;
        top: auto !important;
    }
    
    /* Hide the default message styling */
    .woocommerce-message:not(.nutrilux-styled-message) {
        display: none !important;
    }
    
    /* Remove any icons or extra content */
    .woocommerce-message.nutrilux-styled-message::before,
    .woocommerce-message.nutrilux-styled-message a {
        display: none !important;
    }
    
    @keyframes slideInUp {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    @media (max-width: 768px) {
        .woocommerce-message.nutrilux-styled-message {
            bottom: 15px !important;
            left: 15px !important;
            padding: 10px 16px !important;
            font-size: 0.85rem !important;
            max-width: 180px !important;
        }
    }
    
    /* Auto-hide animation */
    .woocommerce-message.nutrilux-styled-message.fade-out {
        animation: fadeOut 0.3s ease-out forwards !important;
    }
    
    @keyframes fadeOut {
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide the message after 3 seconds
        function autoHideMessage() {
            const message = document.querySelector('.woocommerce-message.nutrilux-styled-message');
            if (message) {
                setTimeout(function() {
                    message.classList.add('fade-out');
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                }, 3000);
            }
        }
        
        // Watch for new messages being added
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.classList && node.classList.contains('nutrilux-styled-message')) {
                        autoHideMessage();
                    }
                });
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Check if message already exists
        autoHideMessage();
    });
    </script>
    <?php
});

/**
 * Global WooCommerce add to cart popup override (classic + block)
 */
add_action('wp_head', function() {
    ?>
    <style>
    /* Klasični WooCommerce popup */
    .woocommerce-message {
        position: fixed !important;
        bottom: 20px !important;
        left: 20px !important;
        background: #28a745 !important;
        color: #fff !important;
        border: none !important;
        border-radius: 8px !important;
        font-size: 0.95rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
        z-index: 9999 !important;
        max-width: 220px !important;
        width: auto !important;
        margin: 0 !important;
        padding: 14px 22px !important;
        text-align: center !important;
        animation: slideInUp 0.3s ease-out !important;
        right: auto !important;
        top: auto !important;
    }
    .woocommerce-message::before,
    .woocommerce-message a {
        display: none !important;
    }
    /* Block-based WooCommerce popup */
    .wc-block-components-notice-banner.is-success {
        position: fixed !important;
        bottom: 20px !important;
        left: 20px !important;
        background: #28a745 !important;
        color: #fff !important;
        border: none !important;
        border-radius: 8px !important;
        font-size: 0.95rem !important;
        font-weight: 500 !important;
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
        z-index: 9999 !important;
        max-width: 220px !important;
        width: auto !important;
        margin: 0 !important;
        padding: 14px 22px !important;
        text-align: center !important;
        animation: slideInUp 0.3s ease-out !important;
        right: auto !important;
        top: auto !important;
    }
    .wc-block-components-notice-banner__icon,
    .wc-block-components-notice-banner__action {
        display: none !important;
    }
    @keyframes slideInUp {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @media (max-width: 768px) {
        .woocommerce-message, .wc-block-components-notice-banner.is-success {
            bottom: 15px !important;
            left: 15px !important;
            padding: 10px 14px !important;
            font-size: 0.85rem !important;
            max-width: 180px !important;
        }
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Helper: set popup text for both systems
        function setPopupText() {
            // Klasični
            document.querySelectorAll('.woocommerce-message').forEach(function(el) {
                el.innerHTML = 'Dodano u korpu';
            });
            // Block
            document.querySelectorAll('.wc-block-components-notice-banner.is-success').forEach(function(el) {
                el.innerHTML = 'Dodano u korpu';
            });
        }
        // Auto-hide after 3s
        function autoHideAll() {
            document.querySelectorAll('.woocommerce-message, .wc-block-components-notice-banner.is-success').forEach(function(el) {
                setTimeout(function() {
                    el.style.transition = 'opacity 0.3s';
                    el.style.opacity = 0;
                    setTimeout(function() { el.remove(); }, 300);
                }, 3000);
            });
        }
        // Mutation observer for dynamic popups
        const observer = new MutationObserver(function(mutations) {
            setPopupText();
            autoHideAll();
        });
        observer.observe(document.body, { childList: true, subtree: true });
        // Initial run
        setPopupText();
        autoHideAll();
    });
    </script>
    <?php
});

/* === ANTI-FREEZE CHECKOUT FIX === */
add_action('wp_head', function() {
    if (is_checkout()) {
        ?>
        <style>
        /* Prevent checkout freezing */
        .woocommerce-checkout {
            overflow: visible !important;
            position: relative !important;
        }
        
        .woocommerce form.checkout {
            overflow: visible !important;
            position: relative !important;
            transform: none !important;
        }
        
        /* FORCE mobile menu closed on checkout - simple approach */
        .woocommerce-checkout .nav-panel {
            display: none !important;
        }
        
        /* Prevent sticky positioning issues */
        .woocommerce-checkout .checkout-summary,
        .woocommerce-checkout .order_review,
        .woocommerce-checkout #order_review {
            position: static !important;
            top: auto !important;
        }
        
        /* Prevent viewport issues on mobile */
        @media (max-width: 768px) {
            .woocommerce-checkout {
                overflow-x: hidden !important;
                overflow-y: visible !important;
            }
            
            body.woocommerce-checkout {
                overflow-x: hidden !important;
                touch-action: pan-y !important;
            }
            
            /* Prevent input zoom on iOS */
            .woocommerce-checkout input[type="text"],
            .woocommerce-checkout input[type="email"],
            .woocommerce-checkout input[type="tel"],
            .woocommerce-checkout select {
                font-size: 16px !important;
            }
        }
        
        /* Prevent transform issues */
        .woocommerce-checkout * {
            transform: none !important;
            backface-visibility: visible !important;
        }
        </style>
        
        <script>
        // Anti-freeze JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            if (document.body.classList.contains('woocommerce-checkout')) {
                console.log('Anti-freeze checkout script loaded');
                
                // SIMPLE: Just hide mobile menu completely on checkout
                const mobileNav = document.getElementById('mobileNav');
                const navToggle = document.getElementById('navToggle');
                if (mobileNav && navToggle) {
                    // Force hide menu
                    mobileNav.style.display = 'none';
                    mobileNav.classList.remove('nav-panel--open');
                    navToggle.setAttribute('aria-expanded', 'false');
                    document.body.style.overflow = '';
                    console.log('Mobile menu completely hidden on checkout');
                    
                    // Disable toggle button on checkout
                    navToggle.style.pointerEvents = 'none';
                    navToggle.style.opacity = '0.5';
                }
                
                // Allow normal menu functionality
                document.addEventListener('click', function(e) {
                    // Always allow clicks on menu elements - more specific selectors
                    if (e.target.closest('#navToggle, .nav-toggle, .nav-close, .nav-panel, .mobile-menu, nav, header, .site-header')) {
                        console.log('Menu click allowed for:', e.target.className);
                        return true;
                    }
                }, true); // Use capture phase
                
                // Prevent viewport jumping
                let lastScrollTop = 0;
                window.addEventListener('scroll', function(e) {
                    let st = window.pageYOffset || document.documentElement.scrollTop;
                    if (Math.abs(lastScrollTop - st) > 50) {
                        // Prevent extreme scroll jumps
                        return;
                    }
                    lastScrollTop = st;
                }, { passive: true });
                
                // Prevent touch zoom that can cause freezing
                let lastTouchEnd = 0;
                document.addEventListener('touchend', function(event) {
                    // Skip if this is a menu button or close button - more specific selectors
                    if (event.target.closest('#navToggle, .nav-toggle, .nav-close, .nav-panel, .mobile-menu, nav, header')) {
                        console.log('Menu touch allowed for:', event.target.className);
                        return;
                    }
                    
                    let now = (new Date()).getTime();
                    if (now - lastTouchEnd <= 300) {
                        event.preventDefault();
                    }
                    lastTouchEnd = now;
                }, { passive: false });
                
                // Prevent double-tap zoom on form elements
                document.querySelectorAll('.woocommerce-checkout input, .woocommerce-checkout select').forEach(function(element) {
                    element.addEventListener('touchstart', function(e) {
                        // Single touch only, but allow menu interactions - more specific selectors
                        if (e.touches.length > 1 && !e.target.closest('#navToggle, .nav-toggle, .nav-close, .nav-panel, .mobile-menu, nav, header')) {
                            e.preventDefault();
                        }
                    }, { passive: false });
                    
                    // Smooth focus without jumping
                    element.addEventListener('focus', function() {
                        setTimeout(function() {
                            if (element.offsetParent !== null) {
                                element.scrollIntoView({ 
                                    behavior: 'smooth', 
                                    block: 'center',
                                    inline: 'nearest'
                                });
                            }
                        }, 100);
                    });
                });
                
                // Monitor for layout shifts that can cause freezing
                if ('ResizeObserver' in window) {
                    const resizeObserver = new ResizeObserver(function(entries) {
                        // Debounce to prevent excessive firing
                        clearTimeout(window.resizeTimeout);
                        window.resizeTimeout = setTimeout(function() {
                            console.log('Layout stable');
                        }, 100);
                    });
                    
                    const checkoutForm = document.querySelector('form.checkout');
                    if (checkoutForm) {
                        resizeObserver.observe(checkoutForm);
                    }
                }
                
                console.log('Anti-freeze measures applied');
                
                // Ensure menu functionality works
                setTimeout(function() {
                    // Re-enable touch events for menu elements - more specific selectors
                    document.querySelectorAll('#navToggle, .nav-toggle, .nav-close, .nav-panel, .mobile-menu, nav, header').forEach(function(el) {
                        if (el) {
                            el.style.touchAction = 'auto';
                            el.style.pointerEvents = 'auto';
                            console.log('Menu touch events restored for:', el.className || el.id);
                        }
                    });
                }, 500);
            }
        });
        </script>
        <?php
    }
});

/**
 * Product Custom Fields - Admin Meta Boxes
 */
function nutrilux_add_product_meta_boxes() {
    add_meta_box(
        'nutrilux_product_details',
        'Detalji Proizvoda',
        'nutrilux_product_details_callback',
        'product',
        'normal',
        'high'
    );
    
    add_meta_box(
        'nutrilux_nutrition_facts',
        'Nutritivne Vrijednosti',
        'nutrilux_nutrition_facts_callback',
        'product',
        'normal',
        'high'
    );
    
    add_meta_box(
        'nutrilux_recipe_info',
        'Recept',
        'nutrilux_recipe_info_callback',
        'product',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'nutrilux_add_product_meta_boxes');

function nutrilux_product_details_callback($post) {
    wp_nonce_field('nutrilux_product_details_nonce', 'nutrilux_product_details_nonce');
    
    $ingredients = get_post_meta($post->ID, '_nutri_ingredients', true);
    $shelf_life = get_post_meta($post->ID, '_nutri_shelf_life', true);
    $rehydration = get_post_meta($post->ID, '_nutri_rehydration_ratio', true);
    $serving = get_post_meta($post->ID, '_nutri_serving', true);
    $storage = get_post_meta($post->ID, '_nutri_storage', true);
    $benefits = get_post_meta($post->ID, '_nutri_benefits', true);
    $usage = get_post_meta($post->ID, '_nutri_usage', true);
    $formula_components = get_post_meta($post->ID, '_nutri_formula_components', true);
    $marketing = get_post_meta($post->ID, '_nutri_marketing', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="nutri_ingredients">Sastojci</label></th>
            <td><input type="text" id="nutri_ingredients" name="nutri_ingredients" value="<?php echo esc_attr($ingredients); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_shelf_life">Rok trajanja</label></th>
            <td><input type="text" id="nutri_shelf_life" name="nutri_shelf_life" value="<?php echo esc_attr($shelf_life); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_rehydration_ratio">Rehidracija</label></th>
            <td><input type="text" id="nutri_rehydration_ratio" name="nutri_rehydration_ratio" value="<?php echo esc_attr($rehydration); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_serving">Preporučena porcija</label></th>
            <td><input type="text" id="nutri_serving" name="nutri_serving" value="<?php echo esc_attr($serving); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_storage">Čuvanje</label></th>
            <td><input type="text" id="nutri_storage" name="nutri_storage" value="<?php echo esc_attr($storage); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_benefits">Prednosti (svaki red nova stavka)</label></th>
            <td><textarea id="nutri_benefits" name="nutri_benefits" rows="5" class="large-text"><?php echo esc_textarea($benefits); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="nutri_usage">Upotreba (svaki red nova stavka)</label></th>
            <td><textarea id="nutri_usage" name="nutri_usage" rows="5" class="large-text"><?php echo esc_textarea($usage); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="nutri_formula_components">Komponente formule (svaki red nova stavka)</label></th>
            <td><textarea id="nutri_formula_components" name="nutri_formula_components" rows="5" class="large-text"><?php echo esc_textarea($formula_components); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="nutri_marketing">Marketing poruka</label></th>
            <td><textarea id="nutri_marketing" name="nutri_marketing" rows="3" class="large-text"><?php echo esc_textarea($marketing); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function nutrilux_nutrition_facts_callback($post) {
    wp_nonce_field('nutrilux_nutrition_facts_nonce', 'nutrilux_nutrition_facts_nonce');
    
    $energy_kcal = get_post_meta($post->ID, '_nutri_energy_kcal', true);
    $protein_g = get_post_meta($post->ID, '_nutri_protein_g', true);
    $fat_g = get_post_meta($post->ID, '_nutri_fat_g', true);
    $carbs_g = get_post_meta($post->ID, '_nutri_carbs_g', true);
    $fiber_g = get_post_meta($post->ID, '_nutri_fiber_g', true);
    $vitamins = get_post_meta($post->ID, '_nutri_vitamins', true);
    $minerals = get_post_meta($post->ID, '_nutri_minerals', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="nutri_energy_kcal">Energija (kcal na 100g)</label></th>
            <td><input type="text" id="nutri_energy_kcal" name="nutri_energy_kcal" value="<?php echo esc_attr($energy_kcal); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_protein_g">Proteini (g na 100g)</label></th>
            <td><input type="text" id="nutri_protein_g" name="nutri_protein_g" value="<?php echo esc_attr($protein_g); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_fat_g">Masti (g na 100g)</label></th>
            <td><input type="text" id="nutri_fat_g" name="nutri_fat_g" value="<?php echo esc_attr($fat_g); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_carbs_g">Ugljikohidrati (g na 100g)</label></th>
            <td><input type="text" id="nutri_carbs_g" name="nutri_carbs_g" value="<?php echo esc_attr($carbs_g); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_fiber_g">Vlakna (g na 100g)</label></th>
            <td><input type="text" id="nutri_fiber_g" name="nutri_fiber_g" value="<?php echo esc_attr($fiber_g); ?>" class="regular-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_vitamins">Vitamini (odvojeni zarezom)</label></th>
            <td><input type="text" id="nutri_vitamins" name="nutri_vitamins" value="<?php echo esc_attr($vitamins); ?>" class="large-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_minerals">Minerali (odvojeni zarezom)</label></th>
            <td><input type="text" id="nutri_minerals" name="nutri_minerals" value="<?php echo esc_attr($minerals); ?>" class="large-text" /></td>
        </tr>
    </table>
    <?php
}

function nutrilux_recipe_info_callback($post) {
    wp_nonce_field('nutrilux_recipe_info_nonce', 'nutrilux_recipe_info_nonce');
    
    $recipe_title = get_post_meta($post->ID, '_nutri_recipe_title', true);
    $recipe_ingredients = get_post_meta($post->ID, '_nutri_recipe_ingredients', true);
    $recipe_instructions = get_post_meta($post->ID, '_nutri_recipe_instructions', true);
    
    ?>
    <table class="form-table">
        <tr>
            <th><label for="nutri_recipe_title">Naziv recepta</label></th>
            <td><input type="text" id="nutri_recipe_title" name="nutri_recipe_title" value="<?php echo esc_attr($recipe_title); ?>" class="large-text" /></td>
        </tr>
        <tr>
            <th><label for="nutri_recipe_ingredients">Sastojci (svaki red nova stavka)</label></th>
            <td><textarea id="nutri_recipe_ingredients" name="nutri_recipe_ingredients" rows="8" class="large-text"><?php echo esc_textarea($recipe_ingredients); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="nutri_recipe_instructions">Instrukcije (svaki red novi korak)</label></th>
            <td><textarea id="nutri_recipe_instructions" name="nutri_recipe_instructions" rows="8" class="large-text"><?php echo esc_textarea($recipe_instructions); ?></textarea></td>
        </tr>
    </table>
    <?php
}

function nutrilux_save_product_meta($post_id) {
    // Check nonce
    if (!isset($_POST['nutrilux_product_details_nonce']) || 
        !wp_verify_nonce($_POST['nutrilux_product_details_nonce'], 'nutrilux_product_details_nonce')) {
        return;
    }
    
    // Check if it's an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save product details
    $product_fields = array(
        'nutri_ingredients' => '_nutri_ingredients',
        'nutri_shelf_life' => '_nutri_shelf_life',
        'nutri_rehydration_ratio' => '_nutri_rehydration_ratio',
        'nutri_serving' => '_nutri_serving',
        'nutri_storage' => '_nutri_storage',
        'nutri_benefits' => '_nutri_benefits',
        'nutri_usage' => '_nutri_usage',
        'nutri_formula_components' => '_nutri_formula_components',
        'nutri_marketing' => '_nutri_marketing'
    );
    
    foreach ($product_fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$field]));
        }
    }
    
    // Save nutrition facts
    $nutrition_fields = array(
        'nutri_energy_kcal' => '_nutri_energy_kcal',
        'nutri_protein_g' => '_nutri_protein_g',
        'nutri_fat_g' => '_nutri_fat_g',
        'nutri_carbs_g' => '_nutri_carbs_g',
        'nutri_fiber_g' => '_nutri_fiber_g',
        'nutri_vitamins' => '_nutri_vitamins',
        'nutri_minerals' => '_nutri_minerals'
    );
    
    foreach ($nutrition_fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Save recipe info
    $recipe_fields = array(
        'nutri_recipe_title' => '_nutri_recipe_title',
        'nutri_recipe_ingredients' => '_nutri_recipe_ingredients',
        'nutri_recipe_instructions' => '_nutri_recipe_instructions'
    );
    
    foreach ($recipe_fields as $field => $meta_key) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $meta_key, sanitize_textarea_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'nutrilux_save_product_meta');
