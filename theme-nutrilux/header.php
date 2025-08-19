<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Skip Links for Accessibility -->
<div class="skip-links">
    <a class="skip-link visually-hidden" href="#main">
        <?php esc_html_e('Preskoči na glavni sadržaj', 'nutrilux'); ?>
    </a>
    <a class="skip-link visually-hidden" href="#site-navigation">
        <?php esc_html_e('Preskoči na navigaciju', 'nutrilux'); ?>
    </a>
    <?php if (class_exists('WooCommerce') && (is_shop() || is_product_category())) : ?>
        <a class="skip-link visually-hidden" href="#shop-filters">
            <?php esc_html_e('Preskoči na filtere proizvoda', 'nutrilux'); ?>
        </a>
    <?php endif; ?>
</div>

<header class="site-header">
    <div class="wrap">
        <div class="header-inner">
            
            <!-- Logo -->
            <div class="site-branding">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
                    <?php 
                    $site_name = get_bloginfo('name');
                    if ($site_name) : ?>
                        <span class="site-title"><?php echo esc_html($site_name); ?></span>
                    <?php endif; ?>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav id="site-navigation" class="main-navigation desktop-nav" aria-label="<?php esc_attr_e('Glavna navigacija', 'nutrilux'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'fallback_cb'    => 'nutrilux_fallback_menu',
                ));
                ?>
            </nav>

            <!-- Header Actions & Mobile Toggle -->
            <div class="header-actions">
                
                <!-- WooCommerce Cart (if active) -->
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-link">
                        <span class="cart-icon" aria-hidden="true">🛒</span>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                        <span class="visually-hidden"><?php esc_html_e('Korpa', 'nutrilux'); ?></span>
                    </a>
                <?php endif; ?>

                <!-- Mobile Navigation Toggle -->
                <button 
                    id="navToggle" 
                    class="nav-toggle" 
                    aria-controls="primaryNav" 
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e('Otvori glavni meni', 'nutrilux'); ?>"
                >
                    <span class="nav-toggle-icon">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Panel -->
    <nav id="primaryNav" class="nav-panel" aria-label="<?php esc_attr_e('Glavna navigacija', 'nutrilux'); ?>">
        <div class="nav-panel-content">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_class'     => 'nav-menu mobile-menu',
                'container'      => false,
                'fallback_cb'    => 'nutrilux_fallback_menu',
            ));
            ?>
        </div>
    </nav>
</header>
