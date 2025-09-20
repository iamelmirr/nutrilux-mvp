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
    
    // Homepage hero: rotating motivational messages
    const rotatorEl = document.getElementById('motivation-rotator');
    if (rotatorEl) {
        const messages = [
            'Snaga za napredak.',
            'Treniraj pametno, oporavljaj brzo.',
            'Za rekreativce i profesionalce.',
            'Prirodni protein za atletski uspjeh.',
            'Svaki trening je korak dalje.',
            'Tijelo traži kvalitetu - ti je daješ.',
            'Oporavak koji osjetiš.',
            'Bez kompromisa, samo rezultati.'
        ];
        let idx = 0;
        const swap = () => {
            idx = (idx + 1) % messages.length;
            rotatorEl.style.opacity = '0';
            setTimeout(() => {
                rotatorEl.textContent = messages[idx];
                rotatorEl.style.opacity = '1';
            }, 200);
        };
        rotatorEl.style.transition = 'opacity 220ms ease';
        setInterval(swap, 3000);
    }
    
    // Homepage hero: background slide crossfade (optional images)
    const slidesWrap = document.querySelector('.hero-slides');
    if (slidesWrap) {
        const slides = slidesWrap.querySelectorAll('.hero-slide');
        if (slides.length > 1) {
            let si = 0;
            setInterval(() => {
                slides[si].classList.remove('is-active');
                si = (si + 1) % slides.length;
                slides[si].classList.add('is-active');
            }, 4500);
        }
    }
    
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

// Cart Quantity Controls Enhancement
document.addEventListener('click', function(e) {
    const btn = e.target.closest('.quantity .plus, .quantity .minus');
    if (!btn) return;
    
    const qtyInput = btn.parentElement.querySelector('.qty');
    if (!qtyInput) return;
    
    let val = parseInt(qtyInput.value, 10) || 0;
    
    if (btn.classList.contains('plus')) {
        val++;
    } else if (btn.classList.contains('minus') && val > 1) {
        val--;
    }
    
    qtyInput.value = val;
    
    // Enable update cart button
    const form = btn.closest('form.woocommerce-cart-form');
    if (form) {
        const updateBtn = form.querySelector('button[name="update_cart"]');
        if (updateBtn) {
            updateBtn.disabled = false;
        }
    }
    
    // Trigger change event for WooCommerce compatibility
    qtyInput.dispatchEvent(new Event('change', { bubbles: true }));
});

// Cart Update Button State Management
document.addEventListener('DOMContentLoaded', function() {
    const cartForm = document.querySelector('form.woocommerce-cart-form');
    if (!cartForm) return;
    
    const qtyInputs = cartForm.querySelectorAll('.qty');
    const updateBtn = cartForm.querySelector('button[name="update_cart"]');
    
    if (!updateBtn) return;
    
    // Store initial values
    const initialValues = {};
    qtyInputs.forEach(function(input, index) {
        initialValues[index] = input.value;
    });
    
    // Monitor changes
    qtyInputs.forEach(function(input, index) {
        input.addEventListener('input', function() {
            const hasChanges = Array.from(qtyInputs).some(function(inp, idx) {
                return inp.value !== initialValues[idx];
            });
            
            updateBtn.disabled = !hasChanges;
            
            if (hasChanges) {
                updateBtn.style.background = '#F5C542';
                updateBtn.style.color = '#121212';
                updateBtn.style.borderColor = '#F5C542';
            } else {
                updateBtn.style.background = '';
                updateBtn.style.color = '';
                updateBtn.style.borderColor = '';
            }
        });
    });
});

// Quick Add to Cart for Simple Products
document.addEventListener('DOMContentLoaded', function() {
    // Handle add to cart buttons (both cards and single product)
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.add-to-cart-btn, .single_add_to_cart_button, .add-to-cart-button')) return;
        
        e.preventDefault();
        const btn = e.target;
        let productId, quantity = 1;
        
        // Handle different button types
        if (btn.matches('.single_add_to_cart_button, .add-to-cart-button')) {
            // Single product page
            productId = btn.value || btn.dataset.productId;
            const quantityInput = btn.closest('form').querySelector('input[name="quantity"]');
            quantity = quantityInput ? quantityInput.value : 1;
        } else {
            // Product card
            productId = btn.dataset.productId;
        }
        
        if (!productId) return;
        
        // Disable button and show loading
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.textContent = btn.matches('.single_add_to_cart_button, .add-to-cart-button') ? 'DODAJEM...' : 'Dodajem...';
        btn.style.opacity = '0.7';
        
        // Add simple product to cart
        fetch('/wp-admin/admin-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                'action': 'nutrilux_add_to_cart',
                'product_id': productId,
                'quantity': quantity,
                'nonce': nutrilux_ajax.nonce
            })
        })
        .then(response => {
            console.log('AJAX Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('AJAX Response data:', data);
            if (data.success) {
                // Success feedback
                btn.textContent = btn.matches('.single_add_to_cart_button, .add-to-cart-button') ? '✓ DODANO!' : '✓ Dodano!';
                btn.style.background = '#4CAF50';
                btn.style.borderColor = '#4CAF50';
                
                // Update cart using WooCommerce fragments if available
                if (data.fragments && data.fragments['a.cart-button']) {
                    const cartButton = document.querySelector('a.cart-button');
                    if (cartButton) {
                        cartButton.outerHTML = data.fragments['a.cart-button'];
                        console.log('Cart updated using fragments');
                    }
                } else {
                    // Fallback: Update cart count and total manually
                    const cartCount = document.querySelector('.cart-badge[data-cart-count], .cart-count');
                    const cartTotal = document.querySelector('.cart-total[data-cart-total]');
                    
                    console.log('Using fallback update. Elements found:', { cartCount, cartTotal });
                    
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                        console.log('Updated cart count to:', data.cart_count);
                    }
                    
                    if (cartTotal && data.cart_total) {
                        cartTotal.innerHTML = data.cart_total;
                        console.log('Updated cart total to:', data.cart_total);
                    }
                }
                
                // Animation for cart button
                const cartButton = document.querySelector('.cart-button');
                if (cartButton) {
                    cartButton.style.transform = 'scale(1.1)';
                    cartButton.style.background = 'rgba(76, 175, 80, 0.1)';
                    setTimeout(() => {
                        cartButton.style.transform = '';
                        cartButton.style.background = '';
                    }, 300);
                }
                
                // Show success message
                showCartSuccessPopup(data.product_name || 'Proizvod');
                
                // Reset button after delay
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.disabled = false;
                    btn.style.opacity = '';
                }, 2500);
            } else {
                // Error feedback
                btn.textContent = 'Greška';
                btn.style.background = '#f44336';
                btn.style.borderColor = '#f44336';
                
                showCartSuccessPopup('Greška prilikom dodavanja');
                
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '';
                    btn.style.borderColor = '';
                    btn.disabled = false;
                    btn.style.opacity = '';
                }, 2000);
            }
        })
        .catch(error => {
            console.error('Ajax error:', error);
            btn.textContent = 'Greška';
            btn.style.background = '#f44336';
            
            showCartSuccessPopup('Greška prilikom dodavanja');
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.style.background = '';
                btn.disabled = false;
                btn.style.opacity = '';
            }, 2000);
        });
    });
});

// Cart notification helper
function showCartNotification(message, type = 'success') {
    // Remove existing notification
    const existing = document.querySelector('.cart-notification');
    if (existing) existing.remove();
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `cart-notification cart-notification--${type}`;
    notification.textContent = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Show with animation
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Hide after delay
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
