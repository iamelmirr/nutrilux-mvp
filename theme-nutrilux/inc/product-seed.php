<?php
/**
 * Product Seed Script
 * Creates 4 Nutrilux products with complete meta data
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Seeds 4 Nutrilux products with complete meta data
 * Runs only once using option flag
 */
function nutrilux_seed_products() {
    // Check if seeding already done
    if (get_option('nutrilux_seed_done')) {
        return array(
            'success' => false,
            'message' => 'Seeding already completed. Products exist.',
            'created' => 0,
            'skipped' => 4
        );
    }
    
    // Check if WooCommerce is active
    if (!class_exists('WooCommerce')) {
        return array(
            'success' => false,
            'message' => 'WooCommerce not active. Cannot create products.',
            'created' => 0,
            'skipped' => 0
        );
    }
    
    $products_data = array(
        array(
            'slug' => 'cijelo-jaje-u-prahu',
            'title' => 'Cijelo jaje u prahu',
            'description' => 'Dehidrirano cijelo jaje (bjelance i žumance) bez dodataka. Idealno za pekarstvo, slastičarstvo i kampovanje. Dugotrajna alternativa svježim jajima sa eliminisanim bakterijama.',
            'regular_price' => '8.90',
            'meta' => array(
                '_nutri_ingredients' => 'Bjelance i žumance, bez dodataka',
                '_nutri_shelf_life' => '12 mjeseci na suhom i hladnom mjestu',
                '_nutri_energy_kcal' => '560',
                '_nutri_protein_g' => '48',
                '_nutri_fat_g' => '37',
                '_nutri_carbs_g' => '5',
                '_nutri_fiber_g' => '0',
                '_nutri_vitamins' => 'A; D; B12; B2 (riboflavin)',
                '_nutri_minerals' => 'Natrij; Kalcij; Željezo; Fosfor',
                '_nutri_rehydration_ratio' => '10–12 g + 30 ml vode = 1 svježe jaje',
                '_nutri_benefits' => "Dugotrajnost\nJednostavna upotreba\nSigurnost (eliminisane bakterije)\nVišenamjenska primjena",
                '_nutri_usage' => "Pekarstvo\nSlastičarstvo\nPrehrambena industrija\nKampovanje",
                '_nutri_recipe_title' => 'Palačinke sa jajetom u prahu',
                '_nutri_recipe_ingredients' => "20 g jajeta u prahu (≈ 2 jaja)\n60 ml vode\n200 ml mlijeka\n150 g brašna\n1 kašika šećera\nPrstohvat soli\n1 kašika ulja",
                '_nutri_recipe_instructions' => "Pomiješaj jaje u prahu s vodom i izmiješaj.\nDodaj mlijeko, šećer, so i brašno; umuti tijesto.\nDodaj ulje i promiješaj.\nPrži tanke palačinke na zagrijanoj tavi.",
                '_nutri_storage' => 'Čuvati na suhom i hladnom da se mast ne užegne.',
                '_nutri_serving' => '10-12 g u 30 ml vode'
            )
        ),
        array(
            'slug' => 'zumance-u-prahu',
            'title' => 'Žumance u prahu',
            'description' => 'Dehidrirano žumance bez aditiva. Odličo za majoneze, umake, tijesta i slastičarstvo. Pruža intenzivnu emulgaciju i stabilnost u kremama.',
            'regular_price' => '12.50',
            'meta' => array(
                '_nutri_ingredients' => 'Dehidrirano žumance, bez aditiva',
                '_nutri_shelf_life' => '12 mjeseci',
                '_nutri_energy_kcal' => '600',
                '_nutri_protein_g' => '30',
                '_nutri_fat_g' => '50',
                '_nutri_carbs_g' => '5',
                '_nutri_fiber_g' => '0',
                '_nutri_vitamins' => 'A; D; E; K; B12',
                '_nutri_minerals' => 'Fosfor; Željezo; Cink',
                '_nutri_rehydration_ratio' => '10 g + 20 ml vode = 1 svježe žumance',
                '_nutri_benefits' => "Dugotrajnost\nSigurnost\nIntenzivna emulgacija\nStabilnost u kremama",
                '_nutri_usage' => "Majoneza\nUmaci\nTijesta\nSlastičarstvo",
                '_nutri_recipe_title' => 'Domaća majoneza',
                '_nutri_recipe_ingredients' => "10 g žumanca u prahu\n20 ml vode\n1 kašičica senfa\n200 ml ulja\n1 kašika limunovog soka\nSo i biber",
                '_nutri_recipe_instructions' => "Pomiješaj žumance u prahu i vodu.\nDodaj senf i začine, započni mućenje.\nPostepeno dodaj ulje u tankom mlazu (stalno muti).\nDodaj limunov sok i finalno umuti.",
                '_nutri_storage' => 'Zatvoreno, zaštititi od vlage.',
                '_nutri_serving' => '10 g u 20 ml vode'
            )
        ),
        array(
            'slug' => 'bjelance-u-prahu',
            'title' => 'Bjelance u prahu',
            'description' => '100% dehidrirano bjelance sa visokim udjelom proteina. Niskokalorično, bez masti. Idealno za sportiste, meringe, kolače i proteinske napitke.',
            'regular_price' => '11.90',
            'meta' => array(
                '_nutri_ingredients' => '100% dehidrirano bjelance',
                '_nutri_shelf_life' => '12 mjeseci',
                '_nutri_energy_kcal' => '380',
                '_nutri_protein_g' => '80',
                '_nutri_fat_g' => '0',
                '_nutri_carbs_g' => '5',
                '_nutri_fiber_g' => '0',
                '_nutri_vitamins' => 'B2; B6; B12',
                '_nutri_minerals' => 'Natrij; Kalij; Magnezij',
                '_nutri_rehydration_ratio' => '3–4 g + 30 ml vode = 1 bjelance (topla voda pomaže)',
                '_nutri_benefits' => "Visok udio proteina\nNiskokalorično\nBez masti\nIdealno za sportiste",
                '_nutri_usage' => "Meringe\nKolači\nOmleti\nProteinski napici",
                '_nutri_recipe_title' => 'Domaći šlag od bjelanca',
                '_nutri_recipe_ingredients' => "10 g bjelanca u prahu\n30 ml tople vode\n50 g šećera\n1 kašičica limunovog soka",
                '_nutri_recipe_instructions' => "Pomiješaj bjelance i toplu vodu.\nMuti dok ne zapjeni.\nPostepeno dodaj šećer i limunov sok.\nMuti do čvrstog šlaga.",
                '_nutri_storage' => 'Suho i hladno; koristiti toplu vodu za lakše otapanje.',
                '_nutri_serving' => '3-4 g u 30 ml tople vode'
            )
        ),
        array(
            'slug' => 'performance-blend',
            'title' => 'Performance Blend',
            'description' => 'Napredna proteinska formula sa albuminom, kazeinom, kreatinom i BCAA. Balans brzog i sporog proteina za optimalan oporavak i snagu. Dodatni enzimi za bolju apsorpciju.',
            'regular_price' => '24.90',
            'meta' => array(
                '_nutri_ingredients' => 'Albumin, Kazein, Kreatin, BCAA (2:1:1), Enzimi, Aroma vanilije',
                '_nutri_shelf_life' => '12 mjeseci',
                '_nutri_energy_kcal' => '400',
                '_nutri_protein_g' => '60',
                '_nutri_fat_g' => '5',
                '_nutri_carbs_g' => '10',
                '_nutri_fiber_g' => '0',
                '_nutri_vitamins' => 'B kompleks, D3',
                '_nutri_minerals' => 'Kalcij, Magnezij, Cink',
                '_nutri_rehydration_ratio' => '30 g + 250 ml vode ili biljnog mlijeka',
                '_nutri_benefits' => "Balans brzog i sporog proteina\nDodatna snaga kreatina\nOporavak uz BCAA\nEnzimi za bolju apsorpciju",
                '_nutri_usage' => "Nakon treninga\nU toku dana kao proteinski shake",
                '_nutri_recipe_title' => 'Performance Shake',
                '_nutri_recipe_ingredients' => "30 g Performance Blend\n250 ml hladne vode ili biljnog mlijeka\n1 banana (opcionalno)\nLed kockice",
                '_nutri_recipe_instructions' => "Dodaj Performance Blend u shaker.\nDodaj tekućinu i banana ako koristiš.\nIntenzivno mućkaj 30 sekundi.\nDodaj led i serviraj odmah.",
                '_nutri_storage' => 'Suho, hladno, dobro zatvoreno.',
                '_nutri_serving' => '30 g u 250 ml vode',
                '_nutri_formula_components' => "Albumin 59% – brza apsorpcija\nKazein 19% – spora podrška\nKreatin 8% – snaga i eksplozivnost\nBCAA 7% – oporavak mišića\nEnzimi 4% – bolja probava\nAroma vanilije 3%",
                '_nutri_marketing' => 'Energija za trening. Oporavak za mišiće. Snaga za napredak.'
            )
        )
    );
    
    $results = array(
        'created' => 0,
        'skipped' => 0,
        'errors' => array(),
        'meta_counts' => array()
    );
    
    foreach ($products_data as $product_data) {
        // Check if product already exists
        $existing_product = get_page_by_path($product_data['slug'], OBJECT, 'product');
        
        if ($existing_product) {
            $results['skipped']++;
            continue;
        }
        
        // Create product
        $product_args = array(
            'post_title' => $product_data['title'],
            'post_name' => $product_data['slug'],
            'post_content' => $product_data['description'],
            'post_status' => 'publish',
            'post_type' => 'product'
        );
        
        $product_id = wp_insert_post($product_args);
        
        if (is_wp_error($product_id)) {
            $results['errors'][] = 'Failed to create product: ' . $product_data['title'] . ' - ' . $product_id->get_error_message();
            continue;
        }
        
        // Set product as simple product
        wp_set_object_terms($product_id, 'simple', 'product_type');
        
        // Set regular price
        update_post_meta($product_id, '_regular_price', $product_data['regular_price']);
        update_post_meta($product_id, '_price', $product_data['regular_price']);
        
        // Set product visibility
        update_post_meta($product_id, '_visibility', 'visible');
        update_post_meta($product_id, '_stock_status', 'instock');
        update_post_meta($product_id, '_manage_stock', 'no');
        
        // Add custom meta fields
        $meta_count = 0;
        foreach ($product_data['meta'] as $meta_key => $meta_value) {
            update_post_meta($product_id, $meta_key, $meta_value);
            $meta_count++;
        }
        
        $results['created']++;
        $results['meta_counts'][$product_data['slug']] = $meta_count;
    }
    
    // Set flag that seeding is done
    update_option('nutrilux_seed_done', true);
    
    $results['success'] = true;
    $results['message'] = sprintf(
        'Seeding completed. Created: %d, Skipped: %d products.',
        $results['created'],
        $results['skipped']
    );
    
    return $results;
}

/**
 * Reset seed flag - useful for development
 */
function nutrilux_reset_seed_flag() {
    delete_option('nutrilux_seed_done');
    return array(
        'success' => true,
        'message' => 'Seed flag reset. Can run seeding again.'
    );
}

/**
 * Admin interface for running seed script
 */
function nutrilux_seed_admin_menu() {
    add_management_page(
        'Nutrilux Product Seeder',
        'Product Seeder',
        'manage_options',
        'nutrilux-seed',
        'nutrilux_seed_admin_page'
    );
}
add_action('admin_menu', 'nutrilux_seed_admin_menu');

/**
 * Admin page for seed script
 */
function nutrilux_seed_admin_page() {
    $message = '';
    $message_type = '';
    
    if (isset($_POST['run_seed']) && wp_verify_nonce($_POST['seed_nonce'], 'nutrilux_seed')) {
        $results = nutrilux_seed_products();
        $message = $results['message'];
        $message_type = $results['success'] ? 'notice-success' : 'notice-error';
        
        if (!empty($results['errors'])) {
            $message .= '<br><strong>Errors:</strong><br>' . implode('<br>', $results['errors']);
        }
        
        if (!empty($results['meta_counts'])) {
            $message .= '<br><strong>Meta fields added:</strong><br>';
            foreach ($results['meta_counts'] as $slug => $count) {
                $message .= "- {$slug}: {$count} fields<br>";
            }
        }
    }
    
    if (isset($_POST['reset_flag']) && wp_verify_nonce($_POST['reset_nonce'], 'nutrilux_reset')) {
        $results = nutrilux_reset_seed_flag();
        $message = $results['message'];
        $message_type = 'notice-info';
    }
    
    $seed_done = get_option('nutrilux_seed_done');
    ?>
    <div class="wrap">
        <h1>Nutrilux Product Seeder</h1>
        
        <?php if ($message): ?>
            <div class="notice <?php echo esc_attr($message_type); ?>">
                <p><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <h2>Seed Status</h2>
            <p><strong>Seeding completed:</strong> <?php echo $seed_done ? 'Yes ✓' : 'No'; ?></p>
            <p><strong>WooCommerce active:</strong> <?php echo class_exists('WooCommerce') ? 'Yes ✓' : 'No ✗'; ?></p>
        </div>
        
        <div class="card">
            <h2>Products to Create</h2>
            <ul>
                <li><strong>Cijelo jaje u prahu</strong> - 8.90 BAM (19 meta fields)</li>
                <li><strong>Žumance u prahu</strong> - 12.50 BAM (18 meta fields)</li>
                <li><strong>Bjelance u prahu</strong> - 11.90 BAM (18 meta fields)</li>
                <li><strong>Performance Blend</strong> - 24.90 BAM (20 meta fields)</li>
            </ul>
        </div>
        
        <div class="card">
            <h2>Actions</h2>
            
            <?php if (!$seed_done): ?>
                <form method="post" style="margin-bottom: 20px;">
                    <?php wp_nonce_field('nutrilux_seed', 'seed_nonce'); ?>
                    <input type="submit" name="run_seed" class="button button-primary" value="Run Product Seeding" 
                           onclick="return confirm('This will create 4 products with complete meta data. Continue?')">
                </form>
            <?php else: ?>
                <p style="color: #46b450; font-weight: bold;">✓ Seeding already completed</p>
            <?php endif; ?>
            
            <form method="post">
                <?php wp_nonce_field('nutrilux_reset', 'reset_nonce'); ?>
                <input type="submit" name="reset_flag" class="button button-secondary" value="Reset Seed Flag" 
                       onclick="return confirm('This will allow seeding to run again. Continue?')">
            </form>
        </div>
    </div>
    <?php
}
