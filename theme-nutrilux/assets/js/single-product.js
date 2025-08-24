/**
 * Single Product Page JavaScript
 * Simple enhancements for better UX
 */

document.addEventListener('DOMContentLoaded', function() {
    // Simple add to cart enhancement
    enhanceAddToCart();
});

/**
 * Enhanced Add to Cart functionality
 */
function enhanceAddToCart() {
    const addToCartButton = document.querySelector('.single_add_to_cart_button');
    if (!addToCartButton) return;
    
    addToCartButton.addEventListener('click', function(e) {
        // Add loading state
        const originalText = this.textContent;
        this.textContent = 'Dodajem u korpu...';
        this.disabled = true;
        
        // Reset after a delay (WooCommerce will handle the actual submission)
        setTimeout(() => {
            this.textContent = originalText;
            this.disabled = false;
        }, 2000);
    });
}
