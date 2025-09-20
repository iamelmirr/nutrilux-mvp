<footer class="site-footer">
    <div class="wrap">
        <div class="footer-content">
            
            <!-- Brand Column -->
            <div class="footer-brand">
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                        <span class="brand-text">Nutrilux</span>
                    </a>
                </div>
                <p class="footer-description">
                    <?php esc_html_e('Kvalitetni dehidrirani proizvodi za vašu kuhinju i zdravlje.', 'nutrilux'); ?>
                </p>
            </div>

            <!-- Quick Links Column -->
            <div class="footer-links">
                <h3 class="footer-title"><?php esc_html_e('Brze veze', 'nutrilux'); ?></h3>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => 'nutrilux_footer_fallback_menu',
                ));
                ?>
            </div>

            <!-- Contact Column -->
            <div class="footer-contact">
                <h3 class="footer-title"><?php esc_html_e('Kontakt', 'nutrilux'); ?></h3>
                <div class="contact-info">
                    
                    <!-- Email -->
                    <div class="contact-item">
                        <span class="contact-icon" aria-hidden="true">✉️</span>
                        <a href="mailto:info@nutrilux.ba">info@nutrilux.ba</a>
                    </div>
                    
                    <!-- Phone -->
                    <div class="contact-item">
                        <span class="contact-icon" aria-hidden="true">📞</span>
                        <a href="tel:+38761234567">+387 61 234 567</a>
                    </div>
                    
                    <!-- WhatsApp Direct -->
                    <div class="contact-item">
                        <span class="contact-icon" aria-hidden="true">�</span>
                        <a href="https://wa.me/38761234567" target="_blank" rel="noopener">WhatsApp poruka</a>
                    </div>
                    
                    
                    <!-- Address -->
                    <div class="contact-item">
                        <span class="contact-icon" aria-hidden="true">📍</span>
                        <span>Ul. 25 novembra 75350 Srebrenik</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-copyright">
                <p>&copy; <?php echo date('Y'); ?> Nutrilux. <?php esc_html_e('Sva prava zadržana.', 'nutrilux'); ?></p>
            </div>
            
            <!-- Payment Methods (placeholder) -->
            <div class="footer-payment">
                <span class="payment-text"><?php esc_html_e('Plaćanje pouzećem', 'nutrilux'); ?></span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
