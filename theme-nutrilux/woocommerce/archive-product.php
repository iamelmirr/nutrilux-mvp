<?php
/**
 * WooCommerce Shop Page Template
 */

get_header(); ?>

<!-- Shop Hero Section -->
<section class="shop-hero">
  <div class="wrap shop-hero__inner">
    <h1 class="shop-hero__title">Naši proizvodi</h1>
  <p class="shop-hero__desc">Kvalitetni prirodni proteini za vašu snagu i energiju. Tri jedinstvene formule, dva praktična pakovanja.</p>
    <form class="woocommerce-ordering shop-hero__ordering" method="get">
      <?php 
        // Održimo postojeće name="orderby" polje
        woocommerce_catalog_ordering();
        // Reproduciraj hidden polja (npr. paginacija)
        foreach ( $_GET as $key => $val ) {
          if ( in_array( $key, ['orderby','submit','paged'] ) ) continue;
          if ( is_array($val) ) continue;
          echo '<input type="hidden" name="'.esc_attr($key).'" value="'.esc_attr($val).'">';
        }
      ?>
    </form>
  </div>
</section>

<main id="main" class="site-main woocommerce-main">
    <div class="wrap">
        
        <!-- Product badges -->
        <div class="shop-product-badges-header">
            <span class="product-badge product-badge--sugar-free">Bez šećera</span>
            <span class="product-badge product-badge--sweetener-free">Bez zaslađivača</span>
        </div>
        
        <?php
        if (woocommerce_product_loop()) {

            ?>
            <div style="margin:14px 0;">
              <p class="micro-hint" style="margin:0 0 10px; color:#666; font-size:.9rem; text-align:center; font-style:italic;">Otkrijte snagu prirodnih proteina</p>
              <hr style="border:0;border-top:1px solid #EEE5DA;" />
            </div>
            <?php
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
