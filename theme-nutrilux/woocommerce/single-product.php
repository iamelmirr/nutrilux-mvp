<?php
/**
 * Single Product Template
 */

get_header(); ?>


<main id="main" class="site-main woocommerce-main single-product-main single-product-page">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <div class="single-product-wrapper">
            <?php wc_get_template_part('content', 'single-product'); ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
