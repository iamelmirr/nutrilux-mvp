<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @version     3.5.0
 * @package     WooCommerce\Templates
 * @author      WooThemes
 */

defined('ABSPATH') || exit;

do_action( 'woocommerce_before_checkout_form', $checkout );

// If registration disabled & not logged-in:
if ( !$checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo '<p>' . esc_html__( 'Morate biti prijavljeni da biste izvršili plaćanje.', 'woocommerce' ) . '</p>';
    return;
}
?>

<section class="checkout-hero">
  <div class="wrap">
        <h1 class="checkout-title">Plaćanje</h1>
  </div>
</section>

<form name="checkout" method="post" class="checkout woocommerce-checkout nutrilux-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
  <div class="checkout-layout wrap">
    <div class="checkout-main">
      <?php if ( $checkout->get_checkout_fields() ) : ?>
        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        <div id="customer_details" class="customer-details">
          <div class="billing-fields">
            <h2 class="section-heading">Podaci za dostavu i plaćanje</h2>
            <?php do_action( 'woocommerce_checkout_billing' ); ?>
          </div>
          <div class="order-notes">
            <?php do_action( 'woocommerce_checkout_shipping' ); // shipping not used, but hook kept ?>
            <?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
            <?php if ( apply_filters('nutrilux_show_order_notes', true) ) : ?>
              <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
            <?php endif; ?>
          </div>
        </div>
        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
      <?php endif; ?>

      <div class="payment-section">
        <h2 class="section-heading">Metod plaćanja</h2>
        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
        <?php do_action( 'woocommerce_checkout_payment' ); ?>
      </div>
    </div>

    <div class="checkout-summary" id="order_review">
      <h2 class="section-heading">Sažetak narudžbe</h2>
      <?php do_action('woocommerce_checkout_order_review'); ?>
    </div>
  </div>
</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
