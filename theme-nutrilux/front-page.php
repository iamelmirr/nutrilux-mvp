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
    <section class="home-products section">
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
            <form class="contact-form" id="homepageContactForm" autocomplete="off" novalidate>
                <?php wp_nonce_field('nutrilux_contact', 'contact_nonce'); ?>
                <div class="form-group" id="contact-name-group">
                    <label for="contact-name">Ime i prezime / Firma*</label>
                    <input type="text" id="contact-name" name="contact_name" required>
                </div>
                <div class="form-group" id="contact-biz-group">
                    <label for="contact-biz">Djelatnost / Svrha</label>
                    <input type="text" id="contact-biz" name="contact_biz">
                </div>
                <div class="form-group" id="contact-email-group">
                    <label for="contact-email">Email*</label>
                    <input type="email" id="contact-email" name="contact_email" required>
                </div>
                <div class="form-group" id="contact-phone-group">
                    <label for="contact-phone">Broj telefona*</label>
                    <input type="tel" id="contact-phone" name="contact_phone" placeholder="npr. +387 61 000 000" required>
                </div>
                <div class="form-group full" id="contact-message-group">
                    <label for="contact-message">Poruka*</label>
                    <textarea id="contact-message" name="contact_message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" id="homepageSubmitButton">
                    <span class="button-text">Pošalji poruku</span>
                    <span class="button-loading" style="display: none;">Šalje se...</span>
                </button>
            </form>
            
            <!-- Success Message -->
            <div id="homepageSuccessMessage" class="success-message" style="display: none;">
                <div class="success-content">
                    <div class="success-icon">✅</div>
                    <h3>Poruka je uspešno poslana!</h3>
                    <p>Hvala vam što ste nas kontaktirali. Odgovoriće mo vam u najkraćem roku.</p>
                    <button type="button" id="sendAnotherHomepageMessage" class="secondary-button">
                        Pošalji novu poruku
                    </button>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('homepageContactForm');
    const submitButton = document.getElementById('homepageSubmitButton');
    const buttonText = submitButton.querySelector('.button-text');
    const buttonLoading = submitButton.querySelector('.button-loading');
    const successMessage = document.getElementById('homepageSuccessMessage');
    const sendAnotherButton = document.getElementById('sendAnotherHomepageMessage');
    
    if (!form) return;
    
    // Form validation
    function validateField(field, errorMessage) {
        if (!field.value.trim()) {
            field.classList.add('error');
            return false;
        } else {
            field.classList.remove('error');
            return true;
        }
    }
    
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function validateForm() {
        const name = document.getElementById('contact-name');
        const email = document.getElementById('contact-email');
        const phone = document.getElementById('contact-phone');
        const message = document.getElementById('contact-message');
        
        let isValid = true;
        
        if (!validateField(name, 'Ime je obavezno')) {
            isValid = false;
        }
        
        if (!email.value.trim()) {
            email.classList.add('error');
            isValid = false;
        } else if (!validateEmail(email.value)) {
            email.classList.add('error');
            isValid = false;
        } else {
            email.classList.remove('error');
        }
        
        if (!validateField(phone, 'Broj telefona je obavezan')) {
            isValid = false;
        }
        
        if (!validateField(message, 'Poruka je obavezna')) {
            isValid = false;
        }
        
        return isValid;
    }
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            return;
        }
        
        // Show loading state
        submitButton.disabled = true;
        buttonText.style.display = 'none';
        buttonLoading.style.display = 'inline';
        
        // Prepare form data
        const formData = new FormData(form);
        formData.append('action', 'nutrilux_contact');
        
        // Debug log
        console.log('Sending form data:', Array.from(formData.entries()));
        
        // Send AJAX request
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text(); // First get as text to see raw response
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    // Show success message
                    form.style.display = 'none';
                    successMessage.style.display = 'block';
                } else {
                    alert('Greška: ' + (data.data || 'Došlo je do greške prilikom slanja poruke.'));
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                alert('Server je vratio neispravnu odpoveđ: ' + text);
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert('Došlo je do greške prilikom slanja poruke. Molimo pokušajte ponovo.');
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            buttonText.style.display = 'inline';
            buttonLoading.style.display = 'none';
        });
    });
    
    // Send another message
    sendAnotherButton.addEventListener('click', function() {
        successMessage.style.display = 'none';
        form.style.display = 'block';
        form.reset();
        
        // Clear any error states
        form.querySelectorAll('.error').forEach(field => {
            field.classList.remove('error');
        });
    });
});
</script>

<?php get_footer(); ?>
