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
            if ($product->get_image_id()) {
                echo $product->get_image('woocommerce_thumbnail');
            } else {
                echo '<div class="p-card__placeholder" aria-hidden="true"></div>';
            }
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
            $raw = get_the_excerpt() ?: wp_strip_all_tags(get_the_content());
            $short = wp_trim_words($raw, 14, '…');
            echo $short ? esc_html($short) : esc_html__('Kvalitetan proizvod za vašu kuhinju i zdravlje.', 'nutrilux');
            ?>
        </p>

        <!-- Product Price -->
        <div class="p-card__price">
            <?php echo $product->get_price_html(); ?>
        </div>

        <!-- Add to Cart Button -->
        <div class="p-card__actions">
            <?php
            woocommerce_template_loop_add_to_cart();
            ?>
        </div>

    </article>
</li>
