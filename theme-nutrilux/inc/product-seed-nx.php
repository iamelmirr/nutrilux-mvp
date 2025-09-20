<?php
/**
 * New Product Seed (Nutrilux Premium, Gold, Zero)
 * Creates 3 products aligned with new lineup
 */
if (!defined('ABSPATH')) { exit; }

function nutrilux_seed_new_lineup() {
    if (!class_exists('WooCommerce')) {
        return array('success'=>false,'message'=>'WooCommerce not active.');
    }
    $items = array(
        array(
            'slug'=>'nutrilux-premium',
            'title'=>'NUTRILUX Premium',
            'desc'=>"Albumin – prirodni protein iz bjelanca jajeta. Bez šećera i bez zaslađivača. Dostupni okusi: vanilija, čokolada.",
        ),
        array(
            'slug'=>'nutrilux-gold',
            'title'=>'NUTRILUX Gold',
            'desc'=>"Albumin 95% + 5% aroma (vanilija ili čokolada). Bez šećera i bez zaslađivača.",
        ),
        array(
            'slug'=>'nutrilux-zero',
            'title'=>'NUTRILUX Zero',
            'desc'=>"100% albumin – čisti prirodni protein bez dodataka. Bez šećera i bez zaslađivača.",
        ),
    );
    $created=0; $touched=0; $errors=array();

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
            if (is_wp_error($pid)) { $errors[] = $pid->get_error_message(); continue; }
            $created++;
        } else {
            $pid = $post->ID;
        }

        // Ensure badges
        update_post_meta($pid, '_nutri_badge_sugar_free', '1');
        update_post_meta($pid, '_nutri_badge_sweetener_free', '1');

        // Ensure VARIABLE type and attribute/variations
        wp_set_object_terms($pid, 'variable', 'product_type');

        // Define custom attribute 'Pakovanje' (non-taxonomy) with two options
        $attr_key = 'pakovanje';
        $attr_label = 'Pakovanje';
        $options_str = '250g | 500g';
        $product_attributes = get_post_meta($pid, '_product_attributes', true);
        if (!is_array($product_attributes)) { $product_attributes = array(); }
        $product_attributes[$attr_key] = array(
            'name'         => $attr_label,
            'value'        => $options_str,
            'position'     => 0,
            'is_visible'   => 1,
            'is_variation' => 1,
            'is_taxonomy'  => 0,
        );
        update_post_meta($pid, '_product_attributes', $product_attributes);

        // Remove parent price (variable products derive price from variations)
        delete_post_meta($pid, '_regular_price');
        delete_post_meta($pid, '_sale_price');
        delete_post_meta($pid, '_price');

        // Ensure two variations exist (250g and 500g)
        $sizes = array('250g','500g');
        foreach ($sizes as $size) {
            $existing_var = null;
            $children = get_children(array(
                'post_parent' => $pid,
                'post_type'   => 'product_variation',
                'numberposts' => -1,
                'post_status' => array('publish','private')
            ));
            if ($children) {
                foreach ($children as $child) {
                    $val = get_post_meta($child->ID, 'attribute_'.$attr_key, true);
                    if ($val === $size) { $existing_var = $child->ID; break; }
                }
            }
            if (!$existing_var) {
                $vid = wp_insert_post(array(
                    'post_title'  => $it['title'].' - '.$size,
                    'post_name'   => sanitize_title($it['slug'].'-'.$size),
                    'post_status' => 'publish',
                    'post_parent' => $pid,
                    'post_type'   => 'product_variation',
                    'menu_order'  => ($size === '250g') ? 0 : 1,
                ));
                if (!is_wp_error($vid)) {
                    update_post_meta($vid, 'attribute_'.$attr_key, $size);
                    update_post_meta($vid, '_manage_stock', 'no');
                    update_post_meta($vid, '_stock_status', 'instock');
                    // Prices left empty intentionally until provided
                    delete_post_meta($vid, '_regular_price');
                    delete_post_meta($vid, '_sale_price');
                    delete_post_meta($vid, '_price');
                } else {
                    $errors[] = $vid->get_error_message();
                }
            }
        }

        // Mark touched
        $touched++;
    }

    update_option('nutrilux_seed_new_done', true);
    $msg = "Created {$created}, updated {$touched}.";
    if (!empty($errors)) { $msg .= ' Errors: '.implode('; ', array_map('esc_html', $errors)); }
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
            $slugs = array('cijelo-jaje-u-prahu', 'zumance-u-prahu', 'bjelance-u-prahu', 'performance-blend');
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
                <p>Creates: NUTRILUX Premium, Gold, Zero (no prices yet; pack sizes 250g/500g).</p>
                <p><button class="button button-primary" name="run_seed_new" value="1">Run Seeding</button></p>
            </form>
            <hr />
            <form method="post" onsubmit="return confirm('This will permanently delete legacy seeded products. Continue?');">
                <?php wp_nonce_field('nutrilux_seed_new'); ?>
                <p>Remove legacy products (jaje u prahu, žumance, bjelance, performance blend).</p>
                <p><button class="button button-secondary" name="remove_legacy" value="1">Remove Legacy Products</button></p>
            </form>
        </div>
        <?php
    });
});
