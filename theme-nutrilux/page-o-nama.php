<?php
/**
 * Template Name: O nama
 * Page template for About Us page
 */

get_header(); ?>

<main id="main" class="about-page">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero Section -->
            <section class="page-hero">
                <div class="wrap page-hero__inner">
                    <h1 class="page-hero__title"><?php the_title(); ?></h1>
                    <p class="page-hero__desc">
                        Prirodni proizvodi za modernu kuhinju i sportske potrebe
                    </p>
                </div>
            </section>

            <!-- Misija i Vizija Section -->
            <section class="about-mission-vision">
                <div class="wrap">
                    <div class="mission-vision-grid">
                        <div class="card mission-card">
                            <h2>Naša Misija</h2>
                            <p>
                                Pružamo visokokvalitetne dehidrirane proizvode koji omogućavaju dugotrajno čuvanje 
                                bez gubitka nutritivnih vrijednosti. Naša misija je da učinimo zdravu ishranu 
                                dostupnijom i praktičnijom za sve.
                            </p>
                        </div>
                        
                        <div class="card vision-card">
                            <h2>Naša Vizija</h2>
                            <p>
                                Težimo da postanemo vodeći brend u regionalnoj proizvodnji dehydriranih proteina 
                                i sportskih suplemenata, prepoznati po inovacijama, kvalitetu i pouzdanosti 
                                naših proizvoda.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vrijednosti Section -->
            <section class="about-values">
                <div class="wrap">
                    <h2 class="section-title">Naše Vrijednosti</h2>
                    <div class="values-grid">
                        <div class="card value-card">
                            <div class="value-icon">🏆</div>
                            <h3>Kvalitet</h3>
                            <p>
                                Koristimo samo najbolje sirovine i najsavremeniju tehnologiju dehidratacije 
                                kako bismo zadržali sve nutritivne vrijednosti.
                            </p>
                        </div>
                        
                        <div class="card value-card">
                            <div class="value-icon">🔬</div>
                            <h3>Inovacije</h3>
                            <p>
                                Konstantno istražujemo nove načine poboljšanja naših proizvoda i razvijamo 
                                formule prilagođene potrebama naših kupaca.
                            </p>
                        </div>
                        
                        <div class="card value-card">
                            <div class="value-icon">🌱</div>
                            <h3>Prirodnost</h3>
                            <p>
                                Naši proizvodi su bez umjetnih dodadaka, konzervanasa i štetnih hemijskih 
                                supstanci. Čista priroda u svakom gramu.
                            </p>
                        </div>
                        
                        <div class="card value-card">
                            <div class="value-icon">✨</div>
                            <h3>Pouzdanost</h3>
                            <p>
                                Gradimo dugotrajne odnose sa kupcima kroz konzistentnu dostavu kvalitetnih 
                                proizvoda i odličnu korisničku podršku.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Proces Section -->
            <section class="about-process">
                <div class="wrap">
                    <h2 class="section-title">Naš Proces</h2>
                    <div class="process-steps">
                        <div class="process-step">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Selekcija Sirovina</h3>
                                <p>
                                    Pažljivo biramo najkvalitetnije sirovine od pouzdanih dobavljača. 
                                    Svaka šarža prolazi kroz rigoroznu kontrolu kvaliteta prije obrade.
                                </p>
                            </div>
                        </div>
                        
                        <div class="process-step">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Dehidratacija</h3>
                                <p>
                                    Koristimo naprednu tehnologiju dehidratacije koja čuva sve nutritivne 
                                    vrijednosti dok uklanja vlagu, osiguravajući dugotrajnost proizvoda.
                                </p>
                            </div>
                        </div>
                        
                        <div class="process-step">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Pakovanje i Dostava</h3>
                                <p>
                                    Finalni proizvodi se pakuju u zaštićenim uvjetima i šalju direktno 
                                    našim kupcima sa garancijom svježine i kvaliteta.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="about-cta">
                <div class="wrap">
                    <div class="cta-content">
                        <h2>Imate pitanja o našim proizvodima?</h2>
                        <p>Kontaktirajte nas za sve informacije o proizvodima, narudžbama ili saradnji.</p>
                        <a href="<?php echo esc_url(home_url('/kontakt/')); ?>" class="cta-button">
                            Kontaktiraj nas
                        </a>
                    </div>
                </div>
            </section>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<?php get_footer(); ?>
