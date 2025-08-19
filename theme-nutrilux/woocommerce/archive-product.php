<?php
/**
 * WooCommerce Shop Page Template
 */

get_header(); ?>

<!-- Shop Hero Section -->
<section class="shop-hero">
    <div class="wrap">
        <h1><?php esc_html_e('Naši proizvodi', 'nutrilux'); ?></h1>
        <p><?php esc_html_e('Kvalitetni proteini na bazi jaja u prahu za industriju, pekare i restorane.', 'nutrilux'); ?></p>
        <div class="shop-sorting">
            <?php woocommerce_catalog_ordering(); ?>
        </div>
    </div>
</section>

<main id="main" class="site-main woocommerce-main">
    <div class="wrap">
        
        <?php
        if (woocommerce_product_loop()) {

            /**
             * Hook: woocommerce_before_shop_loop
             */
            do_action('woocommerce_before_shop_loop');

            woocommerce_product_loop_start();

            if (wc_get_loop_prop('is_shortcode')) {
                $columns = wc_get_loop_prop('columns');
            } else {
                $columns = wc_get_default_products_per_row();
            }

            while (have_posts()) {
                the_post();

                /**
                 * Hook: woocommerce_shop_loop
                 */
                do_action('woocommerce_shop_loop');

                wc_get_template_part('content', 'product');
            }

            woocommerce_product_loop_end();

            /**
             * Hook: woocommerce_after_shop_loop
             */
            do_action('woocommerce_after_shop_loop');
        } else {
            /**
             * Hook: woocommerce_no_products_found
             */
            do_action('woocommerce_no_products_found');
        }
        ?>

    </div>
</main>

<?php get_footer(); ?>
