<?php
/**
 * Single Product Content Template
 */

defined('ABSPATH') || exit;

global $product;

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-content', $product); ?>>

    <div class="product-layout">
        
        <!-- Product Info Column -->
        <div class="product-info">
            
            <!-- Product Header -->
            <header class="product-header">
                <h1 class="product-title"><?php the_title(); ?></h1>
                
                <!-- Product Excerpt -->
                <?php 
                $excerpt = get_the_excerpt();
                if (empty($excerpt)) {
                    $content = get_the_content();
                    $excerpt = wp_trim_words(strip_shortcodes($content), 25, '...');
                }
                
                if (!empty($excerpt)) : ?>
                    <div class="product-excerpt">
                        <p><?php echo wp_kses_post($excerpt); ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Product Price -->
                <div class="product-price">
                    <?php echo $product->get_price_html(); ?>
                </div>
                
                <!-- Add to Cart Form -->
                <div class="product-add-to-cart">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
                
            </header>
            
        </div>
        
        <!-- Product Images Column -->
        <div class="product-images">
            <?php
            /**
             * Hook: woocommerce_before_single_product_summary
             * Outputs the product image gallery
             */
            do_action('woocommerce_before_single_product_summary');
            ?>
        </div>
        
    </div>

    <!-- Product Sections -->
    <div class="product-sections">
        
        <?php
        $product_id = get_the_ID();
        $section_count = 0;
        
        // Basic Information Section
        $ingredients = nutrilux_get_meta($product_id, '_nutri_ingredients');
        $shelf_life = nutrilux_get_meta($product_id, '_nutri_shelf_life');
        
        if (!empty($ingredients) || !empty($shelf_life)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Osnovne informacije', 'nutrilux'); ?></h2>
                
                <?php if (!empty($ingredients)) : ?>
                    <div class="info-item">
                        <h3><?php esc_html_e('Sastojci:', 'nutrilux'); ?></h3>
                        <p><?php echo esc_html($ingredients); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($shelf_life)) : ?>
                    <div class="info-item">
                        <h3><?php esc_html_e('Rok trajanja:', 'nutrilux'); ?></h3>
                        <p><?php echo esc_html($shelf_life); ?></p>
                    </div>
                <?php endif; ?>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Nutritional Values Section
        $nutrition = nutrilux_get_nutritional_info($product_id);
        
        if (!empty($nutrition)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Nutritivne vrijednosti', 'nutrilux'); ?></h2>
                
                <table class="nutrition-table">
                    <caption><?php esc_html_e('Nutritivne vrijednosti (100 g)', 'nutrilux'); ?></caption>
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Parametar', 'nutrilux'); ?></th>
                            <th scope="col"><?php esc_html_e('Vrijednost', 'nutrilux'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($nutrition['energy_kcal'])) : ?>
                            <tr>
                                <th scope="row"><?php esc_html_e('Energija', 'nutrilux'); ?></th>
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
                                <td>
                                    <?php 
                                    $vitamins = nutrilux_format_vitamins_minerals($nutrition['vitamins']);
                                    echo esc_html(implode(', ', $vitamins));
                                    ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php if (!empty($nutrition['minerals'])) : ?>
                            <tr>
                                <th scope="row"><?php esc_html_e('Minerali', 'nutrilux'); ?></th>
                                <td>
                                    <?php 
                                    $minerals = nutrilux_format_vitamins_minerals($nutrition['minerals']);
                                    echo esc_html(implode(', ', $minerals));
                                    ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Rehydration Section
        $rehydration = nutrilux_get_meta($product_id, '_nutri_rehydration_ratio');
        $serving = nutrilux_get_meta($product_id, '_nutri_serving');
        
        if (!empty($rehydration) || !empty($serving)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Priprema i serviranje', 'nutrilux'); ?></h2>
                
                <?php if (!empty($rehydration)) : ?>
                    <div class="info-item">
                        <h3><?php esc_html_e('Rehidracija:', 'nutrilux'); ?></h3>
                        <p><?php echo esc_html($rehydration); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($serving)) : ?>
                    <div class="info-item">
                        <h3><?php esc_html_e('Preporučena porcija:', 'nutrilux'); ?></h3>
                        <p><?php echo esc_html($serving); ?></p>
                    </div>
                <?php endif; ?>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Benefits Section
        $benefits = nutrilux_get_multiline_meta($product_id, '_nutri_benefits');
        
        if (!empty($benefits)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Prednosti', 'nutrilux'); ?></h2>
                
                <ul class="benefits-list">
                    <?php foreach ($benefits as $benefit) : ?>
                        <li><?php echo esc_html($benefit); ?></li>
                    <?php endforeach; ?>
                </ul>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Usage Section
        $usage_list = nutrilux_get_multiline_meta($product_id, '_nutri_usage');
        
        if (!empty($usage_list)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Upotreba', 'nutrilux'); ?></h2>
                
                <ul class="usage-list">
                    <?php foreach ($usage_list as $usage) : ?>
                        <li><?php echo esc_html($usage); ?></li>
                    <?php endforeach; ?>
                </ul>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Recipe Section
        $recipe = nutrilux_get_recipe_info($product_id);
        
        if (!empty($recipe) && !empty($recipe['title'])) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Preporučeni recept', 'nutrilux'); ?></h2>
                
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
                            <h4><?php esc_html_e('Instrukcije:', 'nutrilux'); ?></h4>
                            <ol>
                                <?php foreach ($recipe['instructions'] as $instruction) : ?>
                                    <li><?php echo esc_html($instruction); ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </div>
                    <?php endif; ?>
                </div>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Formula Components Section (Performance Blend only)
        $formula_components = nutrilux_get_multiline_meta($product_id, '_nutri_formula_components');
        
        if (!empty($formula_components)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Komponente formule', 'nutrilux'); ?></h2>
                
                <ul class="formula-list">
                    <?php foreach ($formula_components as $component) : ?>
                        <li><?php echo esc_html($component); ?></li>
                    <?php endforeach; ?>
                </ul>
                
                <?php 
                $marketing = nutrilux_get_meta($product_id, '_nutri_marketing');
                if (!empty($marketing)) : ?>
                    <blockquote class="marketing-message">
                        <p><?php echo esc_html($marketing); ?></p>
                    </blockquote>
                <?php endif; ?>
                
            </section>
        <?php endif; ?>
        
        <?php
        // Storage Section
        $storage = nutrilux_get_meta($product_id, '_nutri_storage');
        
        if (!empty($storage)) : 
            $section_count++; ?>
            <section class="prod-section<?php echo $section_count === 1 ? ' first-section' : ''; ?>">
                <h2><?php esc_html_e('Napomena o čuvanju', 'nutrilux'); ?></h2>
                
                <div class="storage-info">
                    <p><?php echo esc_html($storage); ?></p>
                </div>
                
            </section>
        <?php endif; ?>
        
    </div>

</div>
