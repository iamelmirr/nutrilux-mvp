<?php get_header(); ?>

<main id="main" class="site-main">
    
    <!-- ===== HERO SECTION ===== -->
    <section class="page-hero">
        <div class="hero-bg-layers">
            <span class="hero-orb hero-orb--left"></span>
            <span class="hero-orb hero-orb--right"></span>
        </div>
        <?php
        // Try to detect an uploaded hero video (wp-content/uploads/hero.mp4)
        $upload_dir = wp_get_upload_dir();
        $hero_video_path = trailingslashit($upload_dir['basedir']) . 'hero.mp4';
        $hero_video_url  = trailingslashit($upload_dir['baseurl']) . 'hero.mp4';
        if (file_exists($hero_video_path)) : ?>
            <video class="hero-video" autoplay muted loop playsinline aria-hidden="true">
                <source src="<?php echo esc_url($hero_video_url); ?>" type="video/mp4" />
            </video>
        <?php else: ?>
            <!-- Optional background slides (replace images when ready) -->
            <div class="hero-slides" aria-hidden="true">
                <div class="hero-slide is-active" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-1.svg');"></div>
                <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-2.svg');"></div>
                <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-3.svg');"></div>
            </div>
        <?php endif; ?>
        <div class="wrap">
            <div class="hero-content">
                <h1 class="page-title">Premium proteinska rješenja</h1>
                <p class="hero-description" id="motivation-rotator" aria-live="polite">
                    treniraj pametno - oporavljaj se brzo. tijelo traži kvalitetu, ti je biraš.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">Pogledaj proizvode</a>
                    <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="btn btn-secondary">Kontaktiraj nas</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURED PRODUCTS SECTION ===== -->
    <section class="featured-products section">
        <div class="wrap">
            <h2 class="section-title">Naši proizvodi</h2>
            <p class="section-subtitle">Prirodni albumin u tri varijante - odaberite prema potrebama</p>
            
            <?php
            // Get only 250g products for homepage display
            $featured_slugs = [
                'nutrilux-premium-250g',
                'nutrilux-gold-250g', 
                'nutrilux-zero-250g'
            ];
            $featured_products = [];
            
            foreach ($featured_slugs as $slug) {
                $post = get_page_by_path($slug, OBJECT, 'product');
                if ($post) {
                    $featured_products[] = wc_get_product($post->ID);
                }
            }
            
            if (!empty($featured_products)) : ?>
                <!-- Product badges above slider -->
                <div class="product-badges-header">
                    <span class="product-badge product-badge--sugar-free">Bez šećera</span>
                    <span class="product-badge product-badge--sweetener-free">Bez zaslađivača</span>
                </div>
                
                <div class="product-cards-slider">
                    <?php foreach ($featured_products as $product) : ?>
                        <div class="product-card-compact">
                            <div class="product-card-compact__image">
                                <?php if ($product->get_image()) : ?>
                                    <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                                <?php else : ?>
                                    <div class="product-placeholder">
                                        <span>📦</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="product-card-compact__content">
                                <h3 class="product-card-compact__title">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                        <?php echo esc_html($product->get_name()); ?>
                                    </a>
                                </h3>
                                
                                <div class="product-card-compact__price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                                
                                <?php if ($product->is_type('simple') && $product->is_in_stock()) : ?>
                                <button class="btn btn-primary btn--compact add-to-cart-btn" 
                                        data-product-id="<?php echo esc_attr($product->get_id()); ?>">
                                    Dodaj u korpu
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- "POGLEDAJ SVE PROIZVODE" Card -->
                    <div class="product-card-compact product-card-compact--cta">
                        <div class="product-card-compact__cta-content">
                            <div class="cta-icon">🛍️</div>
                            <h3>Pogledaj sve</h3>
                            <p>Otkrijte kompletnu liniju Nutrilux proizvoda</p>
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
                               class="btn btn-secondary btn--compact">
                                POGLEDAJ SVE PROIZVODE
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Section Ramp -->
    <div class="section-ramp"></div>

    <!-- ===== SPECIFICATIONS/BENEFITS SECTION ===== -->
    <section class="specs section section--soft">
        <div class="wrap specs-inner">
            <h2 class="specs-title">Zašto odabrati Nutrilux?</h2>
            <div class="specs-grid">
                <div class="spec-card">
                    <span class="spec-icon" aria-hidden="true">🥚</span>
                    <h3>Prirodni sastojci</h3>
                    <p>Samo jaja vrhunske kvalitete, bez aditiva i konzervansa.</p>
                </div>
                <div class="spec-card">
                    <span class="spec-icon" aria-hidden="true">🧪</span>
                    <h3>Laboratorijski testirano</h3>
                    <p>Svaka serija prolazi strogu kontrolu i testiranje kvaliteta.</p>
                </div>
                <div class="spec-card">
                    <span class="spec-icon" aria-hidden="true">⚡</span>
                    <h3>Brza apsorpcija</h3>
                    <p>Visokokvalijetni proteini koji se brzo upijaju i efikasno koriste.</p>
                </div>
                <div class="spec-card">
                    <span class="spec-icon" aria-hidden="true">🤝</span>
                    <h3>Podrška kupcima</h3>
                    <p>Otvoreni smo za upite i prilagođene ponude za firme i pojedince.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Ramp Alt -->
    <div class="section-ramp--alt"></div>

    <!-- ===== PROMO/QUOTE SECTION ===== -->
    <section class="promo-block section--alt">
        <div class="wrap">
            <blockquote class="promo-quote">
                <span class="promo-quote__icon" aria-hidden="true">"</span>
                <span>
                    Energija za trening. Oporavak za mišiće. <b>Snaga za napredak.</b> Nutrilux – vaš partner za kvalitet i sigurnost.
                </span>
            </blockquote>
        </div>
    </section>

    <!-- ===== CONTACT SECTION ===== -->
    <section class="contact-section section">
        <div class="wrap contact-inner">
            <h2 class="contact-title">Kontaktirajte nas</h2>
            <p class="contact-desc">Pošaljite upit ili poruku – javit ćemo se brzo!</p>
            <form class="contact-form" autocomplete="off" novalidate>
                <div class="form-group" id="contact-name-group">
                    <label for="contact-name">Ime i prezime / Firma*</label>
                    <input type="text" id="contact-name" name="name" required>
                </div>
                <div class="form-group" id="contact-biz-group">
                    <label for="contact-biz">Djelatnost / Svrha</label>
                    <input type="text" id="contact-biz" name="biz">
                </div>
                <div class="form-group" id="contact-email-group">
                    <label for="contact-email">Email*</label>
                    <input type="email" id="contact-email" name="email" required>
                </div>
                <div class="form-group full" id="contact-message-group">
                    <label for="contact-message">Poruka*</label>
                    <textarea id="contact-message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Pošalji poruku</button>
            </form>
        </div>
    </section>

</main>

<?php get_footer(); ?>
