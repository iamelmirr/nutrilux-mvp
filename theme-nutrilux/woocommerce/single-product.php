<?php
/**
 * Single Product Template
 */

get_header(); ?>


<main id="main" class="site-main woocommerce-main single-product-main">
    <?php while (have_posts()) : ?>
        <?php the_post(); ?>
        <?php wc_get_template_part('content', 'single-product'); ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
