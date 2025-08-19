<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="wrap">
        
        <?php if (have_posts()) : ?>
            
            <!-- Page Hero/Intro Section -->
            <section class="page-hero">
                <div class="hero-content">
                    <h1 class="page-title">
                        <?php 
                        if (is_home() && !is_front_page()) {
                            single_post_title();
                        } elseif (is_front_page()) {
                            esc_html_e('Dobrodošli u Nutrilux', 'nutrilux');
                        } else {
                            the_title();
                        }
                        ?>
                    </h1>
                    
                    <?php if (is_front_page()) : ?>
                        <p class="hero-description">
                            <?php esc_html_e('Kvalitetni dehidrirani proizvodi od jajeta i performance suplementi za vašu kuhinju i zdravlje.', 'nutrilux'); ?>
                        </p>
                        <div class="hero-actions">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">
                                <?php esc_html_e('Pogledaj proizvode', 'nutrilux'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <?php if (is_front_page()) : ?>
                <!-- Home Products Section -->
                <section class="home-products">
                    <div class="wrap">
                        <h2><?php esc_html_e('Naši proizvodi', 'nutrilux'); ?></h2>
                        <p class="section-intro"><?php esc_html_e('Kvalitetni proteinski sastojci i nutritivna rješenja.', 'nutrilux'); ?></p>
                        <ul class="hp-grid">
                            <?php
                            $q = new WP_Query([
                                'post_type' => 'product',
                                'posts_per_page' => 4,
                                'post_status' => 'publish'
                            ]);
                            if ($q->have_posts()):
                                while ($q->have_posts()): $q->the_post();
                                    wc_get_template_part('content', 'product');
                                endwhile;
                                wp_reset_postdata();
                            else:
                                if (current_user_can('manage_options')):
                                    echo '<li class="hp-grid__empty">';
                                    echo '<p>' . esc_html__('Nema proizvoda.', 'nutrilux') . '</p>';
                                    echo '<a href="' . esc_url(admin_url('edit.php?post_type=product')) . '" class="btn btn--outline">' . esc_html__('Dodaj prvi proizvod', 'nutrilux') . '</a>';
                                    echo '</li>';
                                else:
                                    echo '<li class="hp-grid__empty">' . esc_html__('Proizvodi uskoro.', 'nutrilux') . '</li>';
                                endif;
                            endif;
                            ?>
                        </ul>
                        <?php if ($q->have_posts()) : ?>
                            <div class="home-products__cta">
                                <a class="btn btn--outline" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>"><?php esc_html_e('Svi proizvodi', 'nutrilux'); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Page Content -->
            <section class="page-content">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
                        <?php if (has_post_thumbnail() && !is_front_page()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="post-content">
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Stranice:', 'nutrilux'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>
                        
                    </article>
                    
                <?php endwhile; ?>
                
            </section>
            
        <?php else : ?>
            
            <!-- No Content Found -->
            <section class="no-content">
                <h1><?php esc_html_e('Sadržaj nije pronađen', 'nutrilux'); ?></h1>
                <p><?php esc_html_e('Žao nam je, ali traženi sadržaj nije dostupan.', 'nutrilux'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Povratak na početnu', 'nutrilux'); ?>
                </a>
            </section>
            
        <?php endif; ?>
        
    </div>
</main>

<?php get_footer(); ?>
