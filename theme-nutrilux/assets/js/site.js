/**
 * Nutrilux Site JavaScript
 * Handles navigation toggle, accessibility, and interactive elements
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Header Scroll Effect
    const siteHeader = document.querySelector('.site-header');
    if(siteHeader){
        const toggleScrolled = () => {
            if(window.scrollY > 10) siteHeader.classList.add('scrolled');
            else siteHeader.classList.remove('scrolled');
        };
        toggleScrolled();
        window.addEventListener('scroll', toggleScrolled, {passive:true});
    }
    
    // Navigation Toggle Functionality
    const navToggle = document.getElementById('navToggle');
    const mobileNav = document.getElementById('mobileNav');
    const navClose = document.querySelector('.nav-close');
    const body = document.body;
    
    function openNav(){
        mobileNav.classList.add('nav-panel--open');
        navToggle.setAttribute('aria-expanded','true');
        // Fokus trap start
        setTimeout(()=>mobileNav.querySelector('a,button')?.focus(),50);
        document.body.style.overflow='hidden';
    }
    
    function closeNav(){
        mobileNav.classList.remove('nav-panel--open');
        navToggle.setAttribute('aria-expanded','false');
        document.body.style.overflow='';
    }
    
    if (navToggle && mobileNav) {
        navToggle.addEventListener('click', () => {
            (mobileNav.classList.contains('nav-panel--open')?closeNav():openNav());
        });
        
        navClose?.addEventListener('click', closeNav);
        
        document.addEventListener('keydown', e=>{
            if(e.key==='Escape' && mobileNav.classList.contains('nav-panel--open')) closeNav();
        });
        
        mobileNav.addEventListener('click', e=>{
            if(e.target.matches('.mobile-menu a')) closeNav();
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
    
    // Add to Cart Success - Vanilla JS fallback
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add_to_cart_button')) {
            // Add event listener for successful add to cart
            const checkForSuccess = setInterval(function() {
                if (e.target.classList.contains('added')) {
                    clearInterval(checkForSuccess);
                    const productName = e.target.getAttribute('aria-label')?.replace('Add to cart: "', '').replace('"', '') || 'Proizvod';
                    showCartSuccessPopup(productName);
                }
            }, 100);
            
            // Clear interval after 5 seconds to prevent memory leaks
            setTimeout(() => clearInterval(checkForSuccess), 5000);
        }
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

// WooCommerce cart fragment safety re-label after refresh
if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('wc_fragments_refreshed', function(){
        const btn = document.querySelector('.cart-button');
        if(!btn) return;
        const count = btn.querySelector('[data-cart-count]')?.textContent || '0';
        const total = btn.querySelector('[data-cart-total]')?.textContent.replace(/\s+/g,' ').trim() || '';
        const totalRaw = total.replace(/<[^>]*>/g, ''); // Strip HTML tags
        btn.setAttribute('aria-label', `Korpa (${count} artikala – ${totalRaw})`);
    });
    
    // Add to Cart Success Popup
    jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
        // Get product name from button's aria-label or data attribute
        const productName = button.attr('aria-label')?.replace('Add to cart: "', '').replace('"', '') || 'Proizvod';
        
        // Create and show popup
        showCartSuccessPopup(productName);
    });
}

// Cart Success Popup Function
function showCartSuccessPopup(productName) {
    // Remove existing popup if any
    const existingPopup = document.querySelector('.cart-success-popup');
    if (existingPopup) {
        existingPopup.remove();
    }
    
    // Create new popup
    const popup = document.createElement('div');
    popup.className = 'cart-success-popup';
    popup.textContent = `${productName} dodano u korpu!`;
    
    // Add to body
    document.body.appendChild(popup);
    
    // Show popup with animation
    setTimeout(() => {
        popup.classList.add('show');
    }, 100);
    
    // Hide and remove popup after 3 seconds
    setTimeout(() => {
        popup.classList.remove('show');
        setTimeout(() => {
            if (popup.parentNode) {
                popup.parentNode.removeChild(popup);
            }
        }, 300);
    }, 3000);
}
