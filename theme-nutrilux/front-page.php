<?php get_header(); ?>

<main id="main" class="site-main">
    
    <!-- ===== HERO SECTION ===== -->
    <section class="page-hero">
        <div class="hero-bg-layers">
            <span class="hero-orb hero-orb--left"></span>
            <span class="hero-orb hero-orb--right"></span>
        </div>
        <!-- Optional background slides (replace images when ready) -->
        <div class="hero-slides" aria-hidden="true">
            <div class="hero-slide is-active" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-1.svg');"></div>
            <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-2.svg');"></div>
            <div class="hero-slide" style="background-image:url('<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/hero-3.svg');"></div>
        </div>
        <div class="wrap">
            <div class="hero-content">
                <h1 class="page-title">Premium proteinska rješenja</h1>
                <p class="hero-description" id="motivation-rotator" aria-live="polite">
                    Snaga za napredak.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="btn btn-primary">Pogledaj proizvode</a>
                    <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="btn btn-secondary">Kontaktiraj nas</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== DISCOVER CTA SECTION (Products on next click) ===== -->
    <section class="section section--soft">
        <div class="wrap">
            <div class="discover-cta">
                <p class="discover-text">Spremni? Proizvodi su istaknuti u sljedećem koraku.</p>
                <a class="btn btn--outline" href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>">Otvorite shop</a>
            </div>
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
