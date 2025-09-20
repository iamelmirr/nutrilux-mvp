<?php
/**
 * New Product Seed (Nutrilux Premium, Gold, Zero)
 * Creates separate simple products for each size (250g/500g)
 */
if (!defined('ABSPATH')) { exit; }

function nutrilux_seed_new_lineup() {
    if (!class_exists('WooCommerce')) {
        return array('success'=>false,'message'=>'WooCommerce not active.');
    }
    
    // Create 6 separate simple products
    $items = array(
        // Premium variants
        array(
            'slug'=>'nutrilux-premium-250g',
            'title'=>'NUTRILUX Premium 250g',
            'desc'=>"Albumin – prirodni protein dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Dostupni okusi: vanilija, čokolada. Pakovanje: 250g",
            'price' => 25,
            'sku' => 'NTX-PREM-250',
            'category' => 'premium'
        ),
        array(
            'slug'=>'nutrilux-premium-500g',
            'title'=>'NUTRILUX Premium 500g',
            'desc'=>"Albumin – prirodni protein dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Dostupni okusi: vanilija, čokolada. Pakovanje: 500g",
            'price' => 45,
            'sku' => 'NTX-PREM-500',
            'category' => 'premium'
        ),
        // Gold variants
        array(
            'slug'=>'nutrilux-gold-250g',
            'title'=>'NUTRILUX Gold 250g',
            'desc'=>"Proizvod albumin - protein u sastavu: 95% albumin + 5% aroma (vanilija ili čokolada). Albumin je prirodni sastojak dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Pakovanje: 250g",
            'price' => 30,
            'sku' => 'NTX-GOLD-250',
            'category' => 'gold'
        ),
        array(
            'slug'=>'nutrilux-gold-500g',
            'title'=>'NUTRILUX Gold 500g',
            'desc'=>"Proizvod albumin - protein u sastavu: 95% albumin + 5% aroma (vanilija ili čokolada). Albumin je prirodni sastojak dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Pakovanje: 500g",
            'price' => 55,
            'sku' => 'NTX-GOLD-500',
            'category' => 'gold'
        ),
        // Zero variants  
        array(
            'slug'=>'nutrilux-zero-250g',
            'title'=>'NUTRILUX Zero 250g',
            'desc'=>"Čisti protein - albumin 100% prirodni protein bez dodataka. Albumin je prirodni sastojak dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Pakovanje: 250g",
            'price' => 35,
            'sku' => 'NTX-ZERO-250',
            'category' => 'zero'
        ),
        array(
            'slug'=>'nutrilux-zero-500g',
            'title'=>'NUTRILUX Zero 500g',
            'desc'=>"Čisti protein - albumin 100% prirodni protein bez dodataka. Albumin je prirodni sastojak dobiven iz bjelanca jajeta. Bez šećera i bez zaslađivača. Pakovanje: 500g",
            'price' => 65,
            'sku' => 'NTX-ZERO-500',
            'category' => 'zero'
        ),
    );
    
    $created=0; $updated=0; $errors=array();

    foreach($items as $it){
        $post = get_page_by_path($it['slug'], OBJECT, 'product');
        if (!$post) {
            // Create the product if missing
            $pid = wp_insert_post(array(
                'post_title'=>$it['title'],
                'post_name'=>$it['slug'],
                'post_content'=>$it['desc'],
                'post_status'=>'publish',
                'post_type'=>'product'
            ));
            if (is_wp_error($pid)) { 
                $errors[] = "Failed to create {$it['title']}: " . $pid->get_error_message(); 
                continue; 
            }
            $created++;
        } else {
            $pid = $post->ID;
            // Update existing product
            wp_update_post(array(
                'ID' => $pid,
                'post_title' => $it['title'],
                'post_content' => $it['desc']
            ));
            $updated++;
        }

        // Set as SIMPLE product (not variable)
        wp_set_object_terms($pid, 'simple', 'product_type');
        
        // Set price
        update_post_meta($pid, '_regular_price', $it['price']);
        update_post_meta($pid, '_price', $it['price']);
        update_post_meta($pid, '_sku', $it['sku']);
        
        // Set stock status
        update_post_meta($pid, '_manage_stock', 'no');
        update_post_meta($pid, '_stock_status', 'instock');
        
        // Set virtual (no shipping needed for digital/info products)
        update_post_meta($pid, '_virtual', 'no');
        update_post_meta($pid, '_downloadable', 'no');
        
        // Set visibility
        update_post_meta($pid, '_visibility', 'visible');
        
        // Add badges
        update_post_meta($pid, '_nutri_badge_sugar_free', '1');
        update_post_meta($pid, '_nutri_badge_sweetener_free', '1');
        
        // Add category meta for grouping
        update_post_meta($pid, '_nutri_product_line', $it['category']);
        
        // Clear any existing variable product attributes
        delete_post_meta($pid, '_product_attributes');
    }

    update_option('nutrilux_seed_new_done', true);
    $msg = "Created {$created} new products, updated {$updated} existing products.";
    if (!empty($errors)) { 
        $msg .= ' Errors: '.implode('; ', array_map('esc_html', $errors)); 
    }
    return array('success'=>true,'message'=>$msg);
}

// Admin page
add_action('admin_menu', function(){
    add_management_page('Nutrilux New Lineup', 'New Lineup Seed', 'manage_options', 'nutrilux-seed-new', function(){
        if (isset($_POST['run_seed_new']) && check_admin_referer('nutrilux_seed_new')) {
            $r = nutrilux_seed_new_lineup();
            echo '<div class="notice notice-info"><p>'.esc_html($r['message']).'</p></div>';
        }
        if (isset($_POST['remove_legacy']) && check_admin_referer('nutrilux_seed_new')) {
            $slugs = array(
                'cijelo-jaje-u-prahu', 'zumance-u-prahu', 'bjelance-u-prahu', 'performance-blend',
                'nutrilux-premium', 'nutrilux-gold', 'nutrilux-zero' // Remove old variable products
            );
            $removed = 0; $skipped = 0;
            foreach ($slugs as $slug) {
                $post = get_page_by_path($slug, OBJECT, 'product');
                if ($post) {
                    wp_delete_post($post->ID, true);
                    $removed++;
                } else {
                    $skipped++;
                }
            }
            echo '<div class="notice notice-warning"><p>Legacy removed: '.intval($removed).', not found: '.intval($skipped).'</p></div>';
        }
        ?>
        <div class="wrap"><h1>Seed New Product Lineup</h1>
            <form method="post">
                <?php wp_nonce_field('nutrilux_seed_new'); ?>
                <p>Creates: 6 separate simple products (Premium/Gold/Zero in 250g/500g variants with real prices).</p>
                <p><button class="button button-primary" name="run_seed_new" value="1">Run Seeding</button></p>
            </form>
            <hr />
            <form method="post" onsubmit="return confirm('This will permanently delete legacy and old variable products. Continue?');">
                <?php wp_nonce_field('nutrilux_seed_new'); ?>
                <p>Remove legacy products including old variable products (jaje u prahu, žumance, bjelance, performance blend, old nutrilux products).</p>
                <p><button class="button button-secondary" name="remove_legacy" value="1">Remove Legacy Products</button></p>
            </form>
        </div>
        <?php
    });
});
