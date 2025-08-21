<?php
/**
 * Custom Checkout Page Template
 * Direktno forsiranje checkout-a umjesto WooCommerce template
 */

get_header(); ?>

<section class="checkout-hero">
  <div class="wrap">
    <h1 class="checkout-title">Plaćanje</h1>
  </div>
</section>

<div class="wrap">
    <div class="checkout-layout nutrilux-checkout">
        
        <?php if (WC()->cart->is_empty()) : ?>
            <div class="woocommerce-info">
                <p>Vaša korpa je prazna. <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Idite na shop</a> da dodajte proizvode.</p>
            </div>
        <?php else : ?>
            
            <div class="checkout-main">
                <h2 class="section-heading">Podaci za dostavu i plaćanje</h2>
                
                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
                    
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    
                    <div id="customer_details" class="customer-details">
                        <div class="billing-fields">
                            <?php do_action('woocommerce_checkout_billing'); ?>
                        </div>
                    </div>
                    
                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                    
                    <div class="payment-section">
                        <h2 class="section-heading">Metod plaćanja</h2>
                        <?php do_action('woocommerce_checkout_payment'); ?>
                    </div>
                    
                </form>
            </div>

            <div class="checkout-summary" id="order_review">
                <h2 class="section-heading">Sažetak narudžbe</h2>
                
                <div class="woocommerce-checkout-review-order">
                    <table class="shop_table woocommerce-checkout-review-order-table">
                        <thead>
                            <tr>
                                <th class="product-name">Proizvod</th>
                                <th class="product-total">Ukupno</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            do_action('woocommerce_review_order_before_cart_contents');

                            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                    ?>
                                    <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                        <td class="product-name">
                                            <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?>
                                            <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">× ' . sprintf('%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); ?>
                                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                        </td>
                                        <td class="product-total">
                                            <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }

                            do_action('woocommerce_review_order_after_cart_contents');
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="cart-subtotal">
                                <th>Međuzbroj</th>
                                <td><?php wc_cart_totals_subtotal_html(); ?></td>
                            </tr>

                            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                    <th>Kupon: <?php echo esc_html(wc_cart_totals_coupon_label($coupon)); ?></th>
                                    <td>-<?php wc_cart_totals_coupon_html($coupon); ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                <?php do_action('woocommerce_review_order_before_shipping'); ?>
                                <?php wc_cart_totals_shipping_html(); ?>
                                <?php do_action('woocommerce_review_order_after_shipping'); ?>
                            <?php endif; ?>

                            <?php foreach (WC()->cart->get_fees() as $fee) : ?>
                                <tr class="fee">
                                    <th><?php echo esc_html($fee->name); ?></th>
                                    <td><?php wc_cart_totals_fee_html($fee); ?></td>
                                </tr>
                            <?php endforeach; ?>

                            <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                                <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                                    <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                                        <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                                            <th><?php echo esc_html($tax->label); ?></th>
                                            <td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr class="tax-total">
                                        <th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
                                        <td><?php wc_cart_totals_taxes_total_html(); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php do_action('woocommerce_review_order_before_order_total'); ?>

                            <tr class="order-total">
                                <th>Ukupno</th>
                                <td><?php wc_cart_totals_order_total_html(); ?></td>
                            </tr>

                            <?php do_action('woocommerce_review_order_after_order_total'); ?>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>

<style>
.checkout-hero {
  background: linear-gradient(180deg, #FFFBE7 0%, #FFFFFF 100%);
  padding: 40px 0 24px;
}

.checkout-title {
  margin: 0;
  font-size: clamp(2rem, 5vw, 2.9rem);
  font-weight: 700;
  letter-spacing: -0.5px;
  color: #1E2124;
}

.checkout-layout {
  display: flex;
  flex-direction: column;
  gap: 40px;
  padding: 32px 0 56px;
  max-width: 1260px;
}

.section-heading {
  font-size: 1.05rem;
  margin: 0 0 18px;
  font-weight: 600;
  letter-spacing: 0.2px;
  color: #2A2E33;
}

.checkout-summary {
  background: #fff;
  border: 1px solid #E9E3D9;
  border-radius: 16px;
  padding: 24px 22px 28px;
}

.woocommerce-checkout input.input-text,
.woocommerce-checkout textarea,
.woocommerce-checkout select {
  width: 100%;
  border: 1px solid #E2DCCC;
  background: #fff;
  border-radius: 10px;
  padding: 12px 14px;
  font-size: 0.95rem;
  transition: border-color 0.25s, box-shadow 0.25s;
}

.woocommerce-checkout input:focus,
.woocommerce-checkout textarea:focus,
.woocommerce-checkout select:focus {
  border-color: #F5C542;
  box-shadow: 0 0 0 3px rgba(245, 197, 66, 0.25);
  outline: none;
}

.place-order .button.alt,
.place-order button#place_order {
  background: #F5C542;
  color: #121212;
  font-weight: 600;
  border: 0;
  border-radius: 10px;
  padding: 16px 20px;
  font-size: 1rem;
  width: 100%;
  transition: background 0.25s;
}

.place-order .button.alt:hover,
.place-order button#place_order:hover {
  background: #E2B838;
}

@media (min-width: 1080px) {
  .checkout-layout {
    flex-direction: row;
    align-items: flex-start;
  }
  
  .checkout-main {
    flex: 1 1 auto;
    max-width: 760px;
  }
  
  .checkout-summary {
    flex: 0 0 380px;
    position: sticky;
    top: 90px;
  }
}
</style>

<?php get_footer(); ?>
