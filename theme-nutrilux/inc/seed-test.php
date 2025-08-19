<?php
/**
 * Direct Seed Test Script
 * Run this script directly to test seeding functionality
 */

// Include WordPress
$wp_path = realpath(__DIR__ . '/../../../../');
if (file_exists($wp_path . '/wp-config.php')) {
    require_once $wp_path . '/wp-config.php';
    require_once $wp_path . '/wp-includes/wp-db.php';
    require_once $wp_path . '/wp-includes/pluggable.php';
}

// Include our seed functions
require_once __DIR__ . '/product-seed.php';

echo "<h1>Nutrilux Product Seeder Test</h1>\n";

// Check WordPress
if (!defined('ABSPATH')) {
    echo "<p style='color: red;'>WordPress not loaded. Check file paths.</p>\n";
    exit;
}

echo "<p><strong>WordPress:</strong> ✓ Loaded</p>\n";
echo "<p><strong>WooCommerce:</strong> " . (class_exists('WooCommerce') ? '✓ Active' : '✗ Not active') . "</p>\n";

// Check if seeding already done
$seed_done = get_option('nutrilux_seed_done');
echo "<p><strong>Seeding done:</strong> " . ($seed_done ? 'Yes ✓' : 'No') . "</p>\n";

// Count existing products
$existing_products = get_posts(array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'numberposts' => -1,
    'meta_query' => array(
        array(
            'key' => '_nutri_ingredients',
            'compare' => 'EXISTS'
        )
    )
));

echo "<p><strong>Existing Nutrilux products:</strong> " . count($existing_products) . "</p>\n";

if (!empty($existing_products)) {
    echo "<ul>\n";
    foreach ($existing_products as $product) {
        echo "<li>" . esc_html($product->post_title) . " (ID: {$product->ID})</li>\n";
    }
    echo "</ul>\n";
}

// Run seeding if requested
if (isset($_GET['run_seed']) && $_GET['run_seed'] === '1') {
    echo "<hr><h2>Running Seed Script...</h2>\n";
    
    $results = nutrilux_seed_products();
    
    echo "<p><strong>Result:</strong> " . ($results['success'] ? '✓ Success' : '✗ Failed') . "</p>\n";
    echo "<p><strong>Message:</strong> " . esc_html($results['message']) . "</p>\n";
    echo "<p><strong>Created:</strong> " . $results['created'] . "</p>\n";
    echo "<p><strong>Skipped:</strong> " . $results['skipped'] . "</p>\n";
    
    if (!empty($results['errors'])) {
        echo "<p><strong>Errors:</strong></p>\n<ul>\n";
        foreach ($results['errors'] as $error) {
            echo "<li style='color: red;'>" . esc_html($error) . "</li>\n";
        }
        echo "</ul>\n";
    }
    
    if (!empty($results['meta_counts'])) {
        echo "<p><strong>Meta fields added:</strong></p>\n<ul>\n";
        foreach ($results['meta_counts'] as $slug => $count) {
            echo "<li>{$slug}: {$count} fields</li>\n";
        }
        echo "</ul>\n";
    }
}

// Reset flag if requested
if (isset($_GET['reset_flag']) && $_GET['reset_flag'] === '1') {
    echo "<hr><h2>Resetting Seed Flag...</h2>\n";
    
    $results = nutrilux_reset_seed_flag();
    echo "<p><strong>Result:</strong> " . esc_html($results['message']) . "</p>\n";
}

echo "<hr>\n";
echo "<h2>Actions</h2>\n";

if (!$seed_done) {
    echo "<p><a href='?run_seed=1' style='background: #0073aa; color: white; padding: 10px 15px; text-decoration: none; border-radius: 3px;'>🚀 Run Seeding</a></p>\n";
} else {
    echo "<p style='color: green; font-weight: bold;'>✓ Seeding already completed</p>\n";
}

echo "<p><a href='?reset_flag=1' style='background: #666; color: white; padding: 8px 12px; text-decoration: none; border-radius: 3px;'>🔄 Reset Flag</a></p>\n";

echo "<hr>\n";
echo "<h2>Product Data Preview</h2>\n";

// Show first product data structure
echo "<h3>Cijelo jaje u prahu - Meta Fields Preview:</h3>\n";
$sample_meta = array(
    '_nutri_ingredients' => 'Bjelance i žumance, bez dodataka',
    '_nutri_energy_kcal' => '560',
    '_nutri_protein_g' => '48',
    '_nutri_benefits' => "Dugotrajnost\nJednostavna upotreba\nSigurnost (eliminisane bakterije)\nVišenamjenska primjena",
    '_nutri_recipe_title' => 'Palačinke sa jajetom u prahu'
);

echo "<ul>\n";
foreach ($sample_meta as $key => $value) {
    $display_value = (strlen($value) > 50) ? substr($value, 0, 50) . '...' : $value;
    echo "<li><strong>{$key}:</strong> " . esc_html(str_replace("\n", ' | ', $display_value)) . "</li>\n";
}
echo "</ul>\n";

echo "<p><small>Total meta fields per product: 18-20 fields</small></p>\n";
?>
