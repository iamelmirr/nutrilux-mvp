<?php
/**
 * Product Card Template
 * The template for displaying product content within loops
 */

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('', $product); ?>>
    <article class="p-card" tabindex="0" role="button" aria-label="<?php echo esc_attr(sprintf(__('Pogledaj proizvod: %s', 'nutrilux'), $product->get_name())); ?>">
        
        <!-- Product Image -->
        <a class="p-card__media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
            <?php
            /**
             * Hook: woocommerce_before_shop_loop_item_title.
             */
            do_action('woocommerce_before_shop_loop_item_title');
            ?>
        </a>

        <!-- Product Title -->
        <h3 class="p-card__title">
            <a href="<?php the_permalink(); ?>" tabindex="-1">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Product Excerpt -->
        <p class="p-card__excerpt">
            <?php 
            $excerpt = get_the_excerpt();
            if ($excerpt) {
                $trimmed = wp_trim_words($excerpt, 14, '...');
                echo esc_html($trimmed);
            } else {
                esc_html_e('Kvalitetan proizvod za vašu kuhinju i zdravlje.', 'nutrilux');
            }
            ?>
        </p>

        <!-- Product Price -->
        <div class="p-card__price">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item_title.
             */
            do_action('woocommerce_after_shop_loop_item_title');
            ?>
        </div>

        <!-- Add to Cart Button -->
        <div class="p-card__actions">
            <?php
            /**
             * Hook: woocommerce_after_shop_loop_item.
             */
            do_action('woocommerce_after_shop_loop_item');
            ?>
        </div>

    </article>
</li>
