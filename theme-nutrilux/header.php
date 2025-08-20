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
            <nav class="main-nav" aria-label="<?php esc_attr_e('Glavna navigacija', 'nutrilux'); ?>">
                <ul class="nav-menu">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Početna', 'nutrilux'); ?></a></li>
                    <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" <?php echo (is_shop() || is_product_category() || is_product_tag()) ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Proizvodi', 'nutrilux'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/o-nama/')); ?>" <?php echo is_page('o-nama') ? 'aria-current="page"' : ''; ?>><?php esc_html_e('O nama', 'nutrilux'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>" <?php echo is_page('kontakt') ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Kontakt', 'nutrilux'); ?></a></li>
                </ul>
            </nav>

            <!-- Header Actions & Mobile Toggle -->
            <div class="header-actions">
                
                <!-- WooCommerce Cart (if active) -->
                <?php if (class_exists('WooCommerce')) : ?>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-button" aria-label="<?php echo sprintf(esc_attr__('Korpa (%d proizvoda)', 'nutrilux'), WC()->cart->get_cart_contents_count()); ?>">
                        <span class="cart-icon" aria-hidden="true">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" role="img" aria-hidden="true">
                                <path d="M7 6h14l-1.5 9h-11z" stroke="white" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M7 6L6 3H3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                <circle cx="10" cy="21" r="1.5" fill="white"/>
                                <circle cx="17" cy="21" r="1.5" fill="white"/>
                            </svg>
                        </span>
                        <span class="cart-count" data-cart-count><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    </a>
                <?php endif; ?>

                <!-- Mobile Navigation Toggle -->
                <button 
                    id="navToggle" 
                    class="nav-toggle" 
                    aria-controls="mobileNav" 
                    aria-expanded="false"
                    aria-label="<?php esc_attr_e('Otvori meni', 'nutrilux'); ?>"
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
    <nav id="mobileNav" class="nav-panel" aria-label="<?php esc_attr_e('Glavna navigacija', 'nutrilux'); ?>">
        <div class="nav-panel-content">
            <ul class="nav-menu mobile-menu">
                <li><a href="<?php echo esc_url(home_url('/')); ?>" <?php echo is_front_page() ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Početna', 'nutrilux'); ?></a></li>
                <li><a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" <?php echo (is_shop() || is_product_category() || is_product_tag()) ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Proizvodi', 'nutrilux'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/o-nama/')); ?>" <?php echo is_page('o-nama') ? 'aria-current="page"' : ''; ?>><?php esc_html_e('O nama', 'nutrilux'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/kontakt/')); ?>" <?php echo is_page('kontakt') ? 'aria-current="page"' : ''; ?>><?php esc_html_e('Kontakt', 'nutrilux'); ?></a></li>
            </ul>
        </div>
    </nav>
</header>
