<?php get_header(); ?>

<main id="main" class="site-main">
    
    <!-- ===== HERO SECTION ===== -->
    <section class="page-hero">
        <div class="hero-bg-layers">
            <span class="hero-orb hero-orb--left"></span>
            <span class="hero-orb hero-orb--right"></span>
        </div>
        <div class="wrap">
            <div class="hero-content">
                <h1 class="page-title">Premium nutritivna rješenja od jajeta</h1>
                <p class="hero-description">
                    Nutrilux proizvodi kombinuju čistoću sastojaka i kontrolisan kvalitet – za profesionalce, sportiste i kućnu primjenu.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">Pogledaj proizvode</a>
                    <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="btn btn-secondary">Kontaktiraj nas</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PRODUCTS SECTION ===== -->
    <section class="home-products">
        <div class="wrap">
            <h2><?php esc_html_e('Naši proizvodi', 'nutrilux'); ?></h2>
            <p class="section-intro"><?php esc_html_e('Kvalitetni proteinski sastojci i nutritivna rješenja za sve potrebe.', 'nutrilux'); ?></p>
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
                        echo '<li class="product-empty">' . esc_html__('Proizvodi uskoro dostupni.', 'nutrilux') . '</li>';
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

    <!-- ===== SPECIFICATIONS/BENEFITS SECTION ===== -->
    <section class="specs">
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
                    <span class="spec-icon" aria-hidden="true">🚚</span>
                    <h3>Brza isporuka</h3>
                    <p>Dostava širom BiH brzom poštom, plaćanje pouzećem.</p>
                </div>
                <div class="spec-card">
                    <span class="spec-icon" aria-hidden="true">🤝</span>
                    <h3>Podrška kupcima</h3>
                    <p>Otvoreni smo za upite i prilagođene ponude za firme i pojedince.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== PROMO/QUOTE SECTION ===== -->
    <section class="promo-block">
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
    <section class="contact-section">
        <div class="wrap contact-inner">
            <h2 class="contact-title">Kontaktirajte nas</h2>
            <p class="contact-desc">Pošaljite upit ili poruku – javit ćemo se brzo!</p>
            <form class="contact-form" autocomplete="off" novalidate>
                <div class="form-group">
                    <label for="contact-name">Ime i prezime / Firma*</label>
                    <input type="text" id="contact-name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="contact-biz">Djelatnost / Svrha</label>
                    <input type="text" id="contact-biz" name="biz">
                </div>
                <div class="form-group">
                    <label for="contact-email">Email*</label>
                    <input type="email" id="contact-email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contact-message">Poruka*</label>
                    <textarea id="contact-message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Pošalji poruku</button>
            </form>
        </div>
    </section>

</main>

<?php get_footer(); ?>
