<?php
/**
 * Product Meta Fields Registration
 * Registers custom meta fields for nutritional information
 * 
 * @package Nutrilux
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register product meta fields
 */
function nutrilux_register_product_meta_fields() {
    
    // Basic nutritional information
    register_post_meta('product', '_nutri_ingredients', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Product ingredients list',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_shelf_life', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Product shelf life information',
        'default'      => '',
    ));
    
    // Nutritional values
    register_post_meta('product', '_nutri_energy_kcal', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Energy value in kcal per 100g',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_protein_g', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Protein content in grams per 100g',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_fat_g', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Fat content in grams per 100g',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_carbs_g', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Carbohydrates content in grams per 100g',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_fiber_g', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Fiber content in grams per 100g',
        'default'      => '',
    ));
    
    // Vitamins and minerals
    register_post_meta('product', '_nutri_vitamins', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Vitamins content (semicolon separated)',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_minerals', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Minerals content (semicolon separated)',
        'default'      => '',
    ));
    
    // Rehydration and serving information
    register_post_meta('product', '_nutri_rehydration_ratio', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Rehydration ratio instructions',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_serving', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Serving size instructions',
        'default'      => '',
    ));
    
    // Benefits and usage (multiline fields stored as string, split by newline)
    register_post_meta('product', '_nutri_benefits', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Product benefits (newline separated)',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_usage', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Usage recommendations (newline separated)',
        'default'      => '',
    ));
    
    // Recipe information
    register_post_meta('product', '_nutri_recipe_title', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Featured recipe title',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_recipe_ingredients', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Recipe ingredients (newline separated)',
        'default'      => '',
    ));
    
    register_post_meta('product', '_nutri_recipe_instructions', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Recipe instructions (numbered steps, newline separated)',
        'default'      => '',
    ));
    
    // Storage information
    register_post_meta('product', '_nutri_storage', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Storage instructions',
        'default'      => '',
    ));
    
    // Formula components (for Performance Blend only)
    register_post_meta('product', '_nutri_formula_components', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Formula components breakdown (newline separated or JSON)',
        'default'      => '',
    ));
    
    // Marketing message (for Performance Blend)
    register_post_meta('product', '_nutri_marketing', array(
        'type'         => 'string',
        'single'       => true,
        'show_in_rest' => true,
        'description'  => 'Marketing message for product',
        'default'      => '',
    ));
}
add_action('init', 'nutrilux_register_product_meta_fields');

/**
 * Helper function to get product meta with fallback
 * 
 * @param int    $product_id Product ID
 * @param string $meta_key   Meta field key
 * @return string Meta value or empty string
 */
function nutrilux_get_meta($product_id, $meta_key) {
    if (!$product_id || empty($meta_key)) {
        return '';
    }
    
    $value = get_post_meta($product_id, $meta_key, true);
    
    // Return empty string instead of false for consistent handling
    return $value !== false ? $value : '';
}

/**
 * Helper function to get multiline meta as array
 * Splits newline-separated content into array
 * 
 * @param int    $product_id Product ID
 * @param string $meta_key   Meta field key
 * @return array Array of lines or empty array
 */
function nutrilux_get_multiline_meta($product_id, $meta_key) {
    $content = nutrilux_get_meta($product_id, $meta_key);
    
    if (empty($content)) {
        return array();
    }
    
    // Split by newlines and remove empty lines
    $lines = array_filter(array_map('trim', explode("\n", $content)));
    
    return $lines;
}

/**
 * Helper function to get nutritional values as formatted array
 * 
 * @param int $product_id Product ID
 * @return array Nutritional information array
 */
function nutrilux_get_nutritional_info($product_id) {
    $nutrition = array(
        'energy_kcal' => nutrilux_get_meta($product_id, '_nutri_energy_kcal'),
        'protein_g'   => nutrilux_get_meta($product_id, '_nutri_protein_g'),
        'fat_g'       => nutrilux_get_meta($product_id, '_nutri_fat_g'),
        'carbs_g'     => nutrilux_get_meta($product_id, '_nutri_carbs_g'),
        'fiber_g'     => nutrilux_get_meta($product_id, '_nutri_fiber_g'),
        'vitamins'    => nutrilux_get_meta($product_id, '_nutri_vitamins'),
        'minerals'    => nutrilux_get_meta($product_id, '_nutri_minerals'),
    );
    
    // Filter out empty values
    return array_filter($nutrition, function($value) {
        return !empty($value);
    });
}

/**
 * Helper function to get recipe information
 * 
 * @param int $product_id Product ID
 * @return array Recipe information array
 */
function nutrilux_get_recipe_info($product_id) {
    $recipe_title = nutrilux_get_meta($product_id, '_nutri_recipe_title');
    
    if (empty($recipe_title)) {
        return array();
    }
    
    return array(
        'title'        => $recipe_title,
        'ingredients'  => nutrilux_get_multiline_meta($product_id, '_nutri_recipe_ingredients'),
        'instructions' => nutrilux_get_multiline_meta($product_id, '_nutri_recipe_instructions'),
    );
}

/**
 * Helper function to format vitamins/minerals display
 * 
 * @param string $content Semicolon-separated vitamins or minerals
 * @return array Array of individual vitamins/minerals
 */
function nutrilux_format_vitamins_minerals($content) {
    if (empty($content)) {
        return array();
    }
    
    // Split by semicolon and clean up
    $items = array_filter(array_map('trim', explode(';', $content)));
    
    return $items;
}

/**
 * Debug function to list all registered meta fields (for development)
 */
function nutrilux_debug_meta_fields() {
    if (!current_user_can('manage_options') || !isset($_GET['debug_meta'])) {
        return;
    }
    
    $meta_fields = array(
        '_nutri_ingredients',
        '_nutri_shelf_life',
        '_nutri_energy_kcal',
        '_nutri_protein_g',
        '_nutri_fat_g',
        '_nutri_carbs_g',
        '_nutri_fiber_g',
        '_nutri_vitamins',
        '_nutri_minerals',
        '_nutri_rehydration_ratio',
        '_nutri_benefits',
        '_nutri_usage',
        '_nutri_recipe_title',
        '_nutri_recipe_ingredients',
        '_nutri_recipe_instructions',
        '_nutri_storage',
        '_nutri_formula_components',
        '_nutri_serving',
        '_nutri_marketing',
    );
    
    echo '<pre>';
    echo 'Registered Nutrilux Meta Fields (' . count($meta_fields) . ' total):<br>';
    foreach ($meta_fields as $field) {
        echo '- ' . $field . '<br>';
    }
    echo '</pre>';
    
    // Test with a product if ID provided
    if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
        $product_id = intval($_GET['product_id']);
        echo '<h3>Meta values for Product ID: ' . $product_id . '</h3>';
        echo '<pre>';
        foreach ($meta_fields as $field) {
            $value = nutrilux_get_meta($product_id, $field);
            echo $field . ': ' . ($value ? $value : '(empty)') . '<br>';
        }
        echo '</pre>';
    }
}
