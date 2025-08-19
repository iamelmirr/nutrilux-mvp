<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="wrap">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                
                <!-- Page Header -->
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <!-- Page Content -->
                <div class="page-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Stranice:', 'nutrilux'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
                
                <!-- Comments (if enabled for pages) -->
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div class="page-comments">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
                
            </article>
            
        <?php endwhile; ?>
        
    </div>
</main>

<?php get_footer(); ?>
