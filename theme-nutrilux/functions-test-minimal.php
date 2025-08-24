<?php
/**
 * MINIMALNI FUNCTIONS.PHP ZA TESTIRANJE
 * Samo osnove da vidimo da li tema radi
 */

// Osnovna tema setup
function nutrilux_setup() {
    add_theme_support('woocommerce');
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
}
add_action('after_setup_theme', 'nutrilux_setup');

// Osnovni stilovi
function nutrilux_styles() {
    wp_enqueue_style('nutrilux-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'nutrilux_styles');

// Test da vidimo da li se učitava
error_log('NUTRILUX: Functions.php se učitava uspješno');
