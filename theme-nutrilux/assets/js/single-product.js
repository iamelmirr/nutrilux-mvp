/**
 * Single Product Page JavaScript
 * Simple enhancements for better UX
 */

document.addEventListener('DOMContentLoaded', function() {
    // Set default quantity to 1 when quantity selector is hidden
    setDefaultQuantity();
    
    // Simple add to cart enhancement
    enhanceAddToCart();
});

/**
 * Set default quantity to 1 for hidden quantity selector
 */
function setDefaultQuantity() {
    const quantityInput = document.querySelector('.product-add-to-cart .quantity input[name="quantity"]');
    if (quantityInput) {
        quantityInput.value = 1;
    }
}

/**
 * Enhanced Add to Cart functionality
 */
function enhanceAddToCart() {
    const addToCartButton = document.querySelector('.single_add_to_cart_button');
    if (!addToCartButton) return;
    
    addToCartButton.addEventListener('click', function(e) {
        // Ensure quantity is set to 1 before submitting
        const quantityInput = document.querySelector('.product-add-to-cart .quantity input[name="quantity"]');
        if (quantityInput) {
            quantityInput.value = 1;
        }
        
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
