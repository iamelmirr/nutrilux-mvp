<?php
/**
 * Single Product Content Template
 */

defined('ABSPATH') || exit;

global $product;

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-content', $product); ?>>

    <div class="product-layout">
        
        <!-- LEFT: Sticky column (image + title/excerpt/price/add-to-cart) -->
        <div class="product-sticky">
            <div class="product-images">
                <?php do_action('woocommerce_before_single_product_summary'); ?>
            </div>

            <header class="product-header">
                <h1 class="product-title"><?php the_title(); ?></h1>

                <?php 
                $excerpt = get_the_excerpt();
                if (empty($excerpt)) {
                    $raw = get_the_content();
                    $excerpt = wp_trim_words(strip_shortcodes($raw), 25, '...');
                }
                if (!empty($excerpt)) : ?>
                    <div class="product-excerpt">
                        <p><?php echo wp_kses_post($excerpt); ?></p>
                    </div>
                <?php endif; ?>

                <div class="product-cta">
                    <div class="product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>
                    <div class="product-add-to-cart">
                        <button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt">
                            <?php echo esc_html( $product->single_add_to_cart_text() ); ?>
                        </button>
                    </div>
                </div>
            </header>
        </div>

        <!-- RIGHT: Content sections (long description, nutrition, recipe, etc.) -->
        <div class="product-sections">
            <?php
            $product_id = get_the_ID();

            // Long Description
            $content = get_the_content();
            if (!empty($content)) : ?>
                <section class="prod-section first-section long-description">
                    <h2><?php esc_html_e('Detaljni opis', 'nutrilux'); ?></h2>
                    <div class="section-content">
                        <?php echo wp_kses_post($content); ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php
            // Nutritional Values (100 g)
            $nutrition = nutrilux_get_nutritional_info($product_id);
            $has_nutrition = false;
            foreach ($nutrition as $value) {
                if (!empty($value)) { $has_nutrition = true; break; }
            }
            if ($has_nutrition) : ?>
                <section class="prod-section nutrition-section">
                    <h2><?php esc_html_e('Nutritivne vrijednosti', 'nutrilux'); ?></h2>
                    <table class="nutrition-table">
                        <thead>
                            <tr>
                                <th scope="col"><?php esc_html_e('Nutritivne vrijednosti', 'nutrilux'); ?></th>
                                <th scope="col"><?php esc_html_e('Količina (100 g)', 'nutrilux'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($nutrition['energy_kcal'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Energetska vrijednost', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html($nutrition['energy_kcal']); ?> kcal</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['protein_g'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Proteini', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html($nutrition['protein_g']); ?> g</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['fat_g'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Masti', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html($nutrition['fat_g']); ?> g</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['carbs_g'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Ugljikohidrati', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html($nutrition['carbs_g']); ?> g</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['fiber_g'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Vlakna', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html($nutrition['fiber_g']); ?> g</td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['vitamins'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Vitamini', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html(implode(', ', nutrilux_format_vitamins_minerals($nutrition['vitamins']))); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($nutrition['minerals'])) : ?>
                                <tr>
                                    <th scope="row"><?php esc_html_e('Minerali', 'nutrilux'); ?></th>
                                    <td><?php echo esc_html(implode(', ', nutrilux_format_vitamins_minerals($nutrition['minerals']))); ?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>

            <?php
            // Formula table (for Performance Blend etc.)
            $formula_components = nutrilux_get_multiline_meta($product_id, '_nutri_formula_components');
            if (!empty($formula_components)) : ?>
                <section class="prod-section formula-section">
                    <h2><?php esc_html_e('Sastav formule (porcija 30 g)', 'nutrilux'); ?></h2>
                    <table class="nutrition-table">
                        <thead>
                            <tr>
                                <th scope="col"><?php esc_html_e('Sastojak', 'nutrilux'); ?></th>
                                <th scope="col"><?php esc_html_e('Količina', 'nutrilux'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($formula_components as $component) : ?>
                                <?php $parts = explode(':', $component, 2); ?>
                                <tr>
                                    <?php if (count($parts) === 2) : ?>
                                        <th scope="row"><?php echo esc_html(trim($parts[0])); ?></th>
                                        <td><?php echo esc_html(trim($parts[1])); ?></td>
                                    <?php else : ?>
                                        <td colspan="2"><?php echo esc_html($component); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>

            <?php
            // Recipe Section
            $recipe = nutrilux_get_recipe_info($product_id);
            if (!empty($recipe) && !empty($recipe['title'])) : ?>
                <section class="prod-section recipe-section">
                    <h2><?php esc_html_e('Recept', 'nutrilux'); ?></h2>
                    <div class="recipe-content">
                        <h3 class="recipe-title"><?php echo esc_html($recipe['title']); ?></h3>
                        <?php if (!empty($recipe['ingredients'])) : ?>
                            <div class="recipe-ingredients">
                                <h4><?php esc_html_e('Sastojci:', 'nutrilux'); ?></h4>
                                <ul>
                                    <?php foreach ($recipe['ingredients'] as $ingredient) : ?>
                                        <li><?php echo esc_html($ingredient); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($recipe['instructions'])) : ?>
                            <div class="recipe-instructions">
                                <h4><?php esc_html_e('Priprema:', 'nutrilux'); ?></h4>
                                <ol>
                                    <?php foreach ($recipe['instructions'] as $instruction) : ?>
                                        <li><?php echo esc_html($instruction); ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            </div>
                        <?php endif; ?>
                        <?php $marketing = nutrilux_get_meta($product_id, '_nutri_marketing');
                        if (!empty($marketing)) : ?>
                            <blockquote class="marketing-message"><p><?php echo esc_html($marketing); ?></p></blockquote>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </div>

</div>
