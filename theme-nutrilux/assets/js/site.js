/**
 * Nutrilux Site JavaScript
 * Handles navigation toggle, accessibility, and interactive elements
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Navigation Toggle Functionality
    const navToggle = document.getElementById('navToggle');
    const primaryNav = document.getElementById('primaryNav');
    const body = document.body;
    
    if (navToggle && primaryNav) {
        
        // Toggle navigation
        function toggleNav() {
            const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
            const newState = !isExpanded;
            
            // Update ARIA attributes
            navToggle.setAttribute('aria-expanded', newState);
            navToggle.setAttribute('aria-label', newState ? 'Zatvori glavni meni' : 'Otvori glavni meni');
            
            // Toggle classes
            primaryNav.classList.toggle('nav-panel--open', newState);
            body.classList.toggle('nav-open', newState);
            
            // Focus management
            if (newState) {
                // Focus first link in navigation when opened
                const firstLink = primaryNav.querySelector('a');
                if (firstLink) {
                    firstLink.focus();
                }
                // Prevent body scroll
                body.style.overflow = 'hidden';
            } else {
                // Return focus to toggle button when closed
                navToggle.focus();
                // Restore body scroll
                body.style.overflow = '';
            }
        }
        
        // Close navigation
        function closeNav() {
            if (navToggle.getAttribute('aria-expanded') === 'true') {
                toggleNav();
            }
        }
        
        // Toggle button click
        navToggle.addEventListener('click', toggleNav);
        
        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closeNav();
            }
        });
        
        // Close when clicking navigation links
        const navLinks = primaryNav.querySelectorAll('a');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                // Small delay to allow navigation to complete
                setTimeout(closeNav, 100);
            });
        });
        
        // Close when clicking outside navigation (mobile)
        document.addEventListener('click', function(e) {
            const isClickInsideNav = primaryNav.contains(e.target);
            const isClickOnToggle = navToggle.contains(e.target);
            
            if (!isClickInsideNav && !isClickOnToggle && primaryNav.classList.contains('nav-panel--open')) {
                closeNav();
            }
        });
        
        // Handle window resize - close mobile nav on desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1080) {
                closeNav();
            }
        });
    }
    
    // Active Navigation Link Highlighting
    function setActiveNavLink() {
        const currentPath = window.location.pathname;
        const navLinks = document.querySelectorAll('.nav-menu a');
        
        navLinks.forEach(function(link) {
            const linkPath = new URL(link.href).pathname;
            
            // Remove existing active class
            link.classList.remove('nav-link--active');
            
            // Add active class if paths match
            if (linkPath === currentPath || 
                (currentPath.includes('/proizvodi') && linkPath.includes('/proizvodi')) ||
                (currentPath.includes('/shop') && linkPath.includes('/shop')) ||
                (currentPath === '/' && linkPath === '/')) {
                link.classList.add('nav-link--active');
            }
        });
    }
    
    // Set active link on page load
    setActiveNavLink();
    
    // Product Card Keyboard Navigation
    function initProductCardNavigation() {
        const productCards = document.querySelectorAll('.p-card');
        
        productCards.forEach(function(card) {
            card.addEventListener('keydown', function(e) {
                // Handle Enter (13) and Space (32) keys
                if (e.keyCode === 13 || e.keyCode === 32) {
                    e.preventDefault();
                    
                    // Find the product link
                    const productLink = card.querySelector('.p-card__title a') || card.querySelector('.p-card__media');
                    
                    if (productLink) {
                        // Trigger click or navigate
                        if (productLink.click) {
                            productLink.click();
                        } else {
                            window.location.href = productLink.href;
                        }
                    }
                }
            });
            
            // Add visual feedback on focus
            card.addEventListener('focus', function() {
                this.classList.add('p-card--focused');
            });
            
            card.addEventListener('blur', function() {
                this.classList.remove('p-card--focused');
            });
        });
    }
    
    // Initialize product card navigation
    initProductCardNavigation();
    
    // Re-initialize after AJAX calls (for WooCommerce)
    document.addEventListener('updated_wc_div', function() {
        initProductCardNavigation();
    });
    
    // Skip Link Enhancement
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('focus', function() {
            this.classList.remove('visually-hidden');
        });
        
        skipLink.addEventListener('blur', function() {
            this.classList.add('visually-hidden');
        });
    }
    
    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                
                // Focus the target for accessibility
                targetElement.setAttribute('tabindex', '-1');
                targetElement.focus();
            }
        });
    });
    
});

// Cart Update (for WooCommerce)
document.addEventListener('wc_fragments_refreshed', function() {
    // This event fires when WooCommerce updates cart fragments
    console.log('Cart updated');
});
