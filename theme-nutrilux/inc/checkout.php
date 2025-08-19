<?php
/**
 * Checkout Customizations
 * Minimal checkout form with COD only + email customizations
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Customize checkout fields - make it minimal
 */
function nutrilux_customize_checkout_fields($fields) {
    // Remove unnecessary billing fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']); // Since we're Bosnia focused
    
    // Remove shipping fields (we'll use billing for everything)
    unset($fields['shipping']);
    
    // Customize remaining billing fields
    $fields['billing']['billing_first_name']['required'] = true;
    $fields['billing']['billing_last_name']['required'] = true;
    $fields['billing']['billing_address_1']['required'] = true;
    $fields['billing']['billing_city']['required'] = true;
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_email']['required'] = true;
    
    // Update field labels in Bosnian
    $fields['billing']['billing_first_name']['label'] = 'Ime *';
    $fields['billing']['billing_last_name']['label'] = 'Prezime *';
    $fields['billing']['billing_address_1']['label'] = 'Adresa *';
    $fields['billing']['billing_city']['label'] = 'Grad *';
    $fields['billing']['billing_phone']['label'] = 'Telefon *';
    $fields['billing']['billing_email']['label'] = 'Email *';
    
    // Customize order comments
    $fields['order']['order_comments']['label'] = 'Napomene o narudžbi';
    $fields['order']['order_comments']['placeholder'] = 'Dodatne napomene o narudžbi (opcionalno)';
    $fields['order']['order_comments']['required'] = false;
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'nutrilux_customize_checkout_fields');

/**
 * Remove shipping fields display since we're not using them
 */
function nutrilux_remove_shipping_checkout_fields($fields) {
    unset($fields['shipping']);
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'nutrilux_remove_shipping_checkout_fields', 20);

/**
 * Set default country to Bosnia
 */
function nutrilux_set_default_country() {
    return 'BA'; // Bosnia and Herzegovina
}
add_filter('default_checkout_billing_country', 'nutrilux_set_default_country');
add_filter('default_checkout_shipping_country', 'nutrilux_set_default_country');

/**
 * Customize COD payment method title
 */
function nutrilux_customize_cod_title($title, $payment_id) {
    if ($payment_id === 'cod') {
        return 'Plaćanje pouzećem (brza pošta)';
    }
    return $title;
}
add_filter('woocommerce_gateway_title', 'nutrilux_customize_cod_title', 10, 2);

/**
 * Customize COD payment method description
 */
function nutrilux_customize_cod_description($description, $payment_id) {
    if ($payment_id === 'cod') {
        return 'Plaćate gotovinom kuriru prilikom preuzimanja pošiljke. Sigurno i jednostavno.';
    }
    return $description;
}
add_filter('woocommerce_gateway_description', 'nutrilux_customize_cod_description', 10, 2);

/**
 * Customize email subjects
 */
function nutrilux_customize_email_subjects($subject, $order, $email) {
    // Get order number
    $order_number = $order->get_order_number();
    
    // Customize based on email type
    switch ($email->id) {
        case 'new_order':
            // Admin notification
            $subject = sprintf('Nova narudžba (Pouzeće) #%s', $order_number);
            break;
            
        case 'customer_processing_order':
            // Customer order confirmation
            $subject = sprintf('Potvrda narudžbe #%s (Pouzeće)', $order_number);
            break;
            
        case 'customer_completed_order':
            // Customer order completed
            $subject = sprintf('Narudžba #%s je završena', $order_number);
            break;
    }
    
    return $subject;
}
add_filter('woocommerce_email_subject_new_order', 'nutrilux_customize_email_subjects', 10, 3);
add_filter('woocommerce_email_subject_customer_processing_order', 'nutrilux_customize_email_subjects', 10, 3);
add_filter('woocommerce_email_subject_customer_completed_order', 'nutrilux_customize_email_subjects', 10, 3);

/**
 * Add custom paragraph to customer processing email
 */
function nutrilux_add_email_instructions($order, $sent_to_admin, $plain_text, $email) {
    // Only add to customer processing email
    if ($email->id !== 'customer_processing_order' || $sent_to_admin) {
        return;
    }
    
    $message = 'Plaćate gotovinom kuriru pri preuzimanju. Ako trebate izmjenu podataka javite se na info@nutrilux.ba';
    
    if ($plain_text) {
        echo "\n\n" . $message . "\n\n";
    } else {
        echo '<div style="margin: 20px 0; padding: 15px; background: #f8f9fa; border-left: 4px solid #28a745; font-size: 14px; line-height: 1.6;">';
        echo '<strong>Napomena:</strong> ' . esc_html($message);
        echo '</div>';
    }
}
add_action('woocommerce_email_after_order_table', 'nutrilux_add_email_instructions', 10, 4);

/**
 * Customize thank you page for COD orders
 */
function nutrilux_customize_thankyou_cod($order_id) {
    $order = wc_get_order($order_id);
    
    if (!$order || $order->get_payment_method() !== 'cod') {
        return;
    }
    
    echo '<div class="woocommerce-thankyou-cod-notice" style="margin: 20px 0; padding: 20px; background: #e8f5e8; border: 1px solid #28a745; border-radius: 5px;">';
    echo '<h3 style="margin-top: 0; color: #28a745;">Hvala na narudžbi!</h3>';
    echo '<p><strong>Broj narudžbe:</strong> #' . esc_html($order->get_order_number()) . '</p>';
    echo '<p><strong>Način plaćanja:</strong> Plaćanje pouzećem (brza pošta)</p>';
    
    echo '<div style="margin: 15px 0;">';
    echo '<h4 style="margin-bottom: 10px;">Instrukcije:</h4>';
    echo '<ul style="margin: 0; padding-left: 20px;">';
    echo '<li>Vaša narudžba će biti poslana brza poštom</li>';
    echo '<li>Plaćate gotovinom kuriru pri preuzimanju</li>';
    echo '<li>Priprema i slanje: 1-2 radna dana</li>';
    echo '<li>Dostava: 2-3 radna dana</li>';
    echo '</ul>';
    echo '</div>';
    
    echo '<p style="margin-bottom: 0;"><strong>Kontakt:</strong> Za bilo kakva pitanja pišite na <a href="mailto:info@nutrilux.ba">info@nutrilux.ba</a></p>';
    echo '</div>';
}
add_action('woocommerce_thankyou_cod', 'nutrilux_customize_thankyou_cod');

/**
 * Add custom validation for phone field
 */
function nutrilux_validate_phone_field($fields, $errors) {
    if (empty($_POST['billing_phone'])) {
        $errors->add('billing_phone_required', 'Telefon je obavezan za kontakt prilikom dostave.');
        return;
    }
    
    $phone = sanitize_text_field($_POST['billing_phone']);
    
    // Basic phone validation for Bosnia numbers
    if (!preg_match('/^(\+387|387|0)?[0-9\s\-\(\)]{8,15}$/', $phone)) {
        $errors->add('billing_phone_invalid', 'Molimo unesite ispravan broj telefona (npr. 061234567).');
    }
}
add_action('woocommerce_checkout_process', 'nutrilux_validate_phone_field');

/**
 * Remove checkout coupon form (simplify checkout)
 */
function nutrilux_remove_checkout_coupon_form() {
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
}
add_action('init', 'nutrilux_remove_checkout_coupon_form');

/**
 * Customize order received text
 */
function nutrilux_customize_order_received_text($text, $order) {
    if ($order && $order->get_payment_method() === 'cod') {
        return 'Vaša narudžba je uspešno primljena i biće poslana pouzećem.';
    }
    return $text;
}
add_filter('woocommerce_thankyou_order_received_text', 'nutrilux_customize_order_received_text', 10, 2);

/**
 * Add custom CSS for checkout form
 */
function nutrilux_checkout_custom_css() {
    if (is_checkout()) {
        ?>
        <style>
        .woocommerce-checkout .form-row {
            margin-bottom: 1rem;
        }
        
        .woocommerce-checkout .form-row label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .woocommerce-checkout .form-row input[type="text"],
        .woocommerce-checkout .form-row input[type="email"],
        .woocommerce-checkout .form-row input[type="tel"],
        .woocommerce-checkout .form-row textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-sm);
            font-size: 1rem;
        }
        
        .woocommerce-checkout .form-row input:focus,
        .woocommerce-checkout .form-row textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(var(--color-primary-rgb), 0.1);
        }
        
        .woocommerce-checkout .payment_methods {
            margin: 2rem 0;
        }
        
        .woocommerce-checkout #place_order {
            background: var(--color-primary);
            color: var(--color-secondary);
            border: none;
            padding: 1rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 1.1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .woocommerce-checkout #place_order:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        .woocommerce-thankyou-cod-notice {
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        </style>
        <?php
    }
}
add_action('wp_head', 'nutrilux_checkout_custom_css');

/**
 * Log checkout customizations for debugging
 */
function nutrilux_log_checkout_info($order_id) {
    $order = wc_get_order($order_id);
    
    if ($order) {
        error_log(sprintf(
            'Nutrilux Checkout: Order #%s created with COD payment method. Customer: %s %s, Phone: %s',
            $order->get_order_number(),
            $order->get_billing_first_name(),
            $order->get_billing_last_name(),
            $order->get_billing_phone()
        ));
    }
}
add_action('woocommerce_checkout_order_processed', 'nutrilux_log_checkout_info');
