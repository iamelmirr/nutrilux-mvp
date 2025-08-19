<?php
/**
 * Template Name: Kontakt
 * Page template for Contact page with AJAX form
 */

get_header(); ?>

<main id="main" class="contact-page">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            
            <!-- Hero Section -->
            <section class="contact-hero">
                <div class="container">
                    <div class="contact-hero-content">
                        <h1 class="contact-hero-title"><?php the_title(); ?></h1>
                        <p class="contact-hero-tagline">
                            Javite nam se za sva pitanja o proizvodima ili saradnji
                        </p>
                    </div>
                </div>
            </section>

            <!-- Contact Content -->
            <section class="contact-content">
                <div class="container">
                    <div class="contact-grid">
                        
                        <!-- Contact Form -->
                        <div class="contact-form-section">
                            <div class="card">
                                <h2>Pošaljite nam poruku</h2>
                                
                                <form id="contactForm" class="contact-form" novalidate>
                                    <?php wp_nonce_field('nutrilux_contact', 'contact_nonce'); ?>
                                    
                                    <div class="form-group">
                                        <label for="contact_name">Ime *</label>
                                        <input 
                                            type="text" 
                                            id="contact_name" 
                                            name="contact_name" 
                                            required 
                                            aria-required="true"
                                            autocomplete="name"
                                        >
                                        <span class="error-message" id="name-error"></span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact_email">Email *</label>
                                        <input 
                                            type="email" 
                                            id="contact_email" 
                                            name="contact_email" 
                                            required 
                                            aria-required="true"
                                            autocomplete="email"
                                        >
                                        <span class="error-message" id="email-error"></span>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact_message">Poruka *</label>
                                        <textarea 
                                            id="contact_message" 
                                            name="contact_message" 
                                            rows="6" 
                                            required 
                                            aria-required="true"
                                            placeholder="Opišite kako možemo da vam pomognemo..."
                                        ></textarea>
                                        <span class="error-message" id="message-error"></span>
                                    </div>
                                    
                                    <button type="submit" class="submit-button" id="submitButton">
                                        <span class="button-text">Pošalji poruku</span>
                                        <span class="button-loading" style="display: none;">Šalje se...</span>
                                    </button>
                                </form>
                                
                                <!-- Success Message (hidden by default) -->
                                <div id="successMessage" class="success-message" style="display: none;">
                                    <div class="success-content">
                                        <div class="success-icon">✅</div>
                                        <h3>Poruka je uspešno poslana!</h3>
                                        <p>Hvala vam što ste nas kontaktirali. Odgovoriće mo vam u najkraćem roku.</p>
                                        <button type="button" id="sendAnotherMessage" class="secondary-button">
                                            Pošalji novu poruku
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- ARIA Live Region for Contact Form Status -->
                                <div id="contactFormStatus" class="visually-hidden" aria-live="polite" aria-atomic="true"></div>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div class="contact-info-section">
                            <div class="card">
                                <h2>Kontakt informacije</h2>
                                
                                <div class="contact-info-grid">
                                    <div class="contact-info-item">
                                        <div class="contact-icon">📧</div>
                                        <div class="contact-details">
                                            <h3>Email</h3>
                                            <p>
                                                <a href="mailto:info@nutrilux.ba">info@nutrilux.ba</a>
                                            </p>
                                            <small>Odgovaramo u roku od 24 sata</small>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-icon">📱</div>
                                        <div class="contact-details">
                                            <h3>Telefon</h3>
                                            <p>
                                                <a href="tel:+38761234567">+387 61 234 567</a>
                                            </p>
                                            <small>Pozovite za hitna pitanja</small>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-icon">🏢</div>
                                        <div class="contact-details">
                                            <h3>Adresa</h3>
                                            <p>Nutrilux d.o.o.<br>Sarajevo, Bosna i Hercegovina</p>
                                            <small>Dostava brza poštom</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="contact-note">
                                    <h4>💡 Savjet</h4>
                                    <p>
                                        Za najbrži odgovor, molimo vas da u poruci navedete o kojem proizvodu 
                                        se radi ili kakvu vrstu informacije tražite.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>

        <?php endwhile; ?>
    <?php endif; ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const submitButton = document.getElementById('submitButton');
    const buttonText = submitButton.querySelector('.button-text');
    const buttonLoading = submitButton.querySelector('.button-loading');
    const successMessage = document.getElementById('successMessage');
    const sendAnotherButton = document.getElementById('sendAnotherMessage');
    const statusRegion = document.getElementById('contactFormStatus');
    
    // Form validation
    function validateField(field, errorElementId, errorMessage) {
        const errorElement = document.getElementById(errorElementId);
        
        if (!field.value.trim()) {
            field.setAttribute('aria-invalid', 'true');
            field.classList.add('error');
            errorElement.textContent = errorMessage;
            return false;
        } else {
            field.setAttribute('aria-invalid', 'false');
            field.classList.remove('error');
            errorElement.textContent = '';
            return true;
        }
    }
    
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    function validateForm() {
        const name = document.getElementById('contact_name');
        const email = document.getElementById('contact_email');
        const message = document.getElementById('contact_message');
        
        let isValid = true;
        
        // Validate name
        if (!validateField(name, 'name-error', 'Ime je obavezno')) {
            isValid = false;
        }
        
        // Validate email
        if (!email.value.trim()) {
            validateField(email, 'email-error', 'Email je obavezan');
            isValid = false;
        } else if (!validateEmail(email.value)) {
            email.setAttribute('aria-invalid', 'true');
            email.classList.add('error');
            document.getElementById('email-error').textContent = 'Unesite ispravnu email adresu';
            isValid = false;
        } else {
            email.setAttribute('aria-invalid', 'false');
            email.classList.remove('error');
            document.getElementById('email-error').textContent = '';
        }
        
        // Validate message
        if (!validateField(message, 'message-error', 'Poruka je obavezna')) {
            isValid = false;
        }
        
        return isValid;
    }
    
    // Real-time validation
    ['contact_name', 'contact_email', 'contact_message'].forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        field.addEventListener('blur', function() {
            validateForm();
        });
        
        field.addEventListener('input', function() {
            if (field.classList.contains('error')) {
                validateForm();
            }
        });
    });
    
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
        statusRegion.textContent = 'Šalje se poruka...';
        
        // Prepare form data
        const formData = new FormData(form);
        formData.append('action', 'nutrilux_contact');
        
        // Send AJAX request
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide form and show success message
                form.style.display = 'none';
                successMessage.style.display = 'block';
                statusRegion.textContent = 'Poruka je uspešno poslana!';
            } else {
                statusRegion.textContent = 'Greška: ' + (data.data || 'Došlo je do greške prilikom slanja poruke.');
                alert('Greška: ' + (data.data || 'Došlo je do greške prilikom slanja poruke.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            statusRegion.textContent = 'Došlo je do greške prilikom slanja poruke.';
            alert('Došlo je do greške prilikom slanja poruke. Molimo pokušajte ponovo.');
        })
        .finally(() => {
            // Reset loading state
            submitButton.disabled = false;
            buttonText.style.display = 'inline';
            buttonLoading.style.display = 'none';
            
            // Clear status after success
            if (statusRegion.textContent.includes('uspešno')) {
                setTimeout(() => {
                    statusRegion.textContent = '';
                }, 3000);
            }
        });
    });
    
    // Send another message button
    sendAnotherButton.addEventListener('click', function() {
        form.style.display = 'block';
        successMessage.style.display = 'none';
        form.reset();
        
        // Reset validation states
        ['contact_name', 'contact_email', 'contact_message'].forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            field.setAttribute('aria-invalid', 'false');
            field.classList.remove('error');
        });
        
        // Clear error messages
        ['name-error', 'email-error', 'message-error'].forEach(function(errorId) {
            document.getElementById(errorId).textContent = '';
        });
        
        // Clear status region
        statusRegion.textContent = '';
    });
});
</script>

<?php get_footer(); ?>
