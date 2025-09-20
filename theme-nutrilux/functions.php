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
        delete_transient('woocommerce_shipping_methods');
        wp_cache_flush();
        wc_delete_shop_order_transients();
    }
});
