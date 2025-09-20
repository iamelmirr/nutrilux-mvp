<?php
defined('ABSPATH') || exit;
global $product;
$acf_nutrition = function_exists('get_field') ? get_field('nutritivna_tablica') : false;
$has_acf_nutrition = $acf_nutrition && is_array($acf_nutrition) && count($acf_nutrition);
?>
        <?php if ($has_acf_nutrition): ?>
            <div class="product-nutrition-table">
                <h2>Nutritivne vrijednosti (100g)</h2>
                <table>
                    <tr><th>Parametar</th><th>Vrijednost</th></tr>
                    <?php foreach ($acf_nutrition as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['parametar'] ?? ''); ?></td>
                            <td><?php echo esc_html($row['vrijednost'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php else: ?>
            <?php get_template_part('woocommerce/parts/product-nutrition'); ?>
        <?php endif; ?>
<?php
defined('ABSPATH') || exit;
global $product;
?>

<div class="single-product-grid">
    <div class="product-gallery">
        <?php woocommerce_show_product_images(); ?>
    </div>
    <div class="product-info">
        <h1 class="product-title"><?php the_title(); ?></h1>
        <?php 
        $sugar_free = get_post_meta(get_the_ID(), '_nutri_badge_sugar_free', true);
        $sweetener_free = get_post_meta(get_the_ID(), '_nutri_badge_sweetener_free', true);
        if ($sugar_free || $sweetener_free): ?>
            <div class="product-badges" aria-label="Istaknute karakteristike">
                <?php if ($sugar_free): ?><span class="badge">Bez šećera</span><?php endif; ?>
                <?php if ($sweetener_free): ?><span class="badge">Bez zaslađivača</span><?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($product->get_price_html()): ?>
            <div class="product-price-highlight">
                <span class="product-price"><?php echo $product->get_price_html(); ?></span>
            </div>
            <p class="micro-hint" style="margin:6px 0 14px; color:#666; font-size:.9rem; font-style:italic;">Prirodno. Efikasno. Pouzdano.</p>
        <?php endif; ?>
        <?php if ($product->get_short_description()): ?>
            <div class="product-short-desc"><?php echo apply_filters('woocommerce_short_description', $product->get_short_description()); ?></div>
        <?php endif; ?>
        <div class="product-add-to-cart">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
        <?php if ($product->get_description()): ?>
            <div class="product-long-desc"><?php echo apply_filters('the_content', $product->get_description()); ?></div>
        <?php endif; ?>
        <?php if ($has_acf_nutrition): ?>
            <div class="product-nutrition-table">
                <h2>Nutritivne vrijednosti (100g)</h2>
                <table>
                    <tr><th>Parametar</th><th>Vrijednost</th></tr>
                    <?php foreach ($acf_nutrition as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['parametar'] ?? ''); ?></td>
                            <td><?php echo esc_html($row['vrijednost'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
