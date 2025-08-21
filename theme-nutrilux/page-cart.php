<?php
/**
 * Custom Cart Page Template
 * Template Name: Cart Page
 */

get_header(); ?>

<main class="site-main">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <?php if (WC()->cart->is_empty()) : ?>
            <div class="cart-empty" style="text-align: center; padding: 60px 20px;">
                <h1 style="font-size: clamp(1.8rem, 4vw, 2.4rem); color: #1E2124; margin-bottom: 20px; font-weight: 700;">Vaša korpa je prazna</h1>
                <p style="color: #666; margin-bottom: 30px; font-size: 1.1rem;">Dodajte proizvode u korpu da nastavite sa kupovinom.</p>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" style="background: #F5C542; color: #121212; padding: 14px 28px; text-decoration: none; border-radius: 10px; display: inline-block; font-weight: 600; transition: all 0.25s; box-shadow: 0 2px 10px -2px rgba(245,197,66,0.25);">Pogledajte proizvode</a>
            </div>
        <?php else : ?>
            
            <h1 style="font-size: clamp(2rem, 5vw, 2.6rem); color: #1E2124; margin-bottom: 40px; font-weight: 700; letter-spacing: -0.5px;">Korpa</h1>
            
            <div class="cart-layout" style="display: flex; flex-direction: column; gap: 40px;">
                
                <!-- Cart Items -->
                <div class="cart-items" style="flex: 1;">
                    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                        
                        <!-- Mobile Layout -->
                        <div class="cart-mobile" style="display: block;">
                            <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);

                                if ($_product && $_product->exists() && $cart_item['quantity'] > 0) :
                                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            ?>
                                <div class="cart-item" style="background: #fff; border: 1px solid #E8E2D8; border-radius: 14px; padding: 20px; margin-bottom: 16px; position: relative;">
                                    
                                    <!-- Remove Button -->
                                    <a href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" 
                                       class="remove-item" 
                                       style="position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; background: #F3F1EC; border-radius: 8px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #666; font-size: 18px; transition: all 0.2s;"
                                       data-product-id="<?php echo esc_attr($product_id); ?>"
                                       data-cart-key="<?php echo esc_attr($cart_item_key); ?>">×</a>
                                    
                                    <div style="display: flex; gap: 16px;">
                                        <!-- Product Image -->
                                        <div style="flex: 0 0 80px;">
                                            <?php
                                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                                            if (!$product_permalink) {
                                                echo '<div style="width: 80px; height: 80px; background: #F3F1EC; border-radius: 10px; overflow: hidden;">' . $thumbnail . '</div>';
                                            } else {
                                                printf('<a href="%s" style="display: block; width: 80px; height: 80px; background: #F3F1EC; border-radius: 10px; overflow: hidden;">%s</a>', esc_url($product_permalink), $thumbnail);
                                            }
                                            ?>
                                        </div>
                                        
                                        <!-- Product Info -->
                                        <div style="flex: 1; min-width: 0;">
                                            <h3 style="margin: 0 0 8px; font-size: 1rem; font-weight: 600; line-height: 1.3;">
                                                <?php
                                                if (!$product_permalink) {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key));
                                                } else {
                                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s" style="color: #222; text-decoration: none;">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                                }
                                                ?>
                                            </h3>
                                            
                                            <!-- Price -->
                                            <p style="margin: 0 0 12px; color: #666; font-size: 0.9rem;">
                                                Cijena: <strong><?php echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); ?></strong>
                                            </p>
                                            
                                            <!-- Quantity and Total -->
                                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                                <div class="quantity-controls" style="display: flex; align-items: center; background: #F3F1EC; border-radius: 8px; border: 1px solid #E2DCCC;">
                                                    <button type="button" class="qty-minus" style="background: none; border: none; width: 36px; height: 36px; cursor: pointer; font-size: 18px; font-weight: 600; color: #555;" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">−</button>
                                                    <input type="number" 
                                                           name="cart[<?php echo $cart_item_key; ?>][qty]" 
                                                           value="<?php echo esc_attr($cart_item['quantity']); ?>" 
                                                           class="qty-input" 
                                                           style="width: 50px; text-align: center; border: none; background: transparent; font-weight: 600; padding: 8px 4px;"
                                                           min="1"
                                                           data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                                    <button type="button" class="qty-plus" style="background: none; border: none; width: 36px; height: 36px; cursor: pointer; font-size: 18px; font-weight: 600; color: #555;" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                                </div>
                                                
                                                <div class="item-total" style="font-size: 1.1rem; font-weight: 700; color: #F5C542;">
                                                    <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                        
                        <!-- Hidden form elements for WooCommerce -->
                        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                        <input type="hidden" name="update_cart" value="1">
                    </form>
                </div>
                
                <!-- Cart Totals -->
                <div class="cart-totals" style="background: #fff; border: 1px solid #E8E2D8; border-radius: 16px; padding: 28px 24px 32px;">
                    <h2 style="font-size: 1.3rem; margin: 0 0 20px; font-weight: 600; color: #1F2225;">Ukupno narudžbe</h2>
                    
                    <div class="totals-table">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #F0F0F0;">
                            <span style="color: #666;">Međuzbroj:</span>
                            <strong><?php echo WC()->cart->get_cart_subtotal(); ?></strong>
                        </div>
                        
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #F0F0F0;">
                                <span style="color: #666;">Dostava:</span>
                                <strong>Besplatna</strong>
                            </div>
                        <?php endif; ?>
                        
                        <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-top: 16px; border-top: 1px solid #EDE8E0; font-size: 1.1rem; font-weight: 700; color: #1E2124;">
                            <span>Ukupno:</span>
                            <span style="color: #F5C542;"><?php echo WC()->cart->get_cart_total(); ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" 
                       style="display: block; width: 100%; background: #F5C542; color: #121212; text-decoration: none; text-align: center; padding: 14px 20px; border-radius: 10px; font-size: 1rem; font-weight: 600; transition: all 0.25s; box-shadow: 0 2px 10px -2px rgba(245,197,66,0.25); margin-bottom: 16px;">
                        Nastavi na plaćanje
                    </a>
                    
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" 
                       style="display: block; width: 100%; background: transparent; color: #666; text-decoration: none; text-align: center; padding: 12px; border: 1px solid #D8D2C9; border-radius: 8px; font-size: 0.9rem; transition: all 0.25s;">
                        Nastavi kupovinu
                    </a>
                </div>
                
            </div>
            
        <?php endif; ?>
    </div>
</main>

<style>
/* Desktop styles */
@media (min-width: 768px) {
    .cart-layout {
        flex-direction: row !important;
        align-items: flex-start !important;
    }
    
    .cart-items {
        flex: 1 1 auto !important;
        margin-right: 40px !important;
    }
    
    .cart-totals {
        flex: 0 0 380px !important;
        position: sticky !important;
        top: 100px !important;
    }
    
    .cart-item {
        padding: 24px !important;
    }
    
    .cart-item > div {
        gap: 24px !important;
    }
    
    .cart-item > div > div:first-child {
        flex: 0 0 100px !important;
    }
}

/* Remove button hover */
.remove-item:hover {
    background: #E2B838 !important;
    color: #121212 !important;
}

/* Quantity controls hover */
.qty-minus:hover, .qty-plus:hover {
    background: #E8E2D8 !important;
}

/* Checkout button hover */
a[href*="checkout"]:hover {
    background: #E2B838 !important;
    box-shadow: 0 6px 24px -6px rgba(245,197,66,0.45) !important;
}

/* Continue shopping hover */
a[href*="shop"]:hover {
    background: #FFF7E2 !important;
    border-color: #E2B838 !important;
}

/* Product images responsive */
.cart-item img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

/* Smooth transitions */
.cart-item, .quantity-controls, .remove-item {
    transition: all 0.2s ease !important;
}

/* Loading state */
.cart-loading {
    opacity: 0.6;
    pointer-events: none;
}

.qty-input:focus {
    outline: 2px solid rgba(245, 197, 66, 0.5) !important;
    outline-offset: 2px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-update cart functionality
    let updateTimeout;
    const form = document.querySelector('.woocommerce-cart-form');
    
    function updateCart() {
        if (!form) return;
        
        // Add loading state
        document.querySelector('.cart-items').classList.add('cart-loading');
        
        // Submit form with AJAX
        const formData = new FormData(form);
        formData.append('update_cart', '1');
        
        fetch(form.action, {
            method: 'POST',
            body: formData
        }).then(() => {
            // Reload page to show updated cart
            window.location.reload();
        }).catch(() => {
            document.querySelector('.cart-items').classList.remove('cart-loading');
        });
    }
    
    // Quantity controls
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('qty-plus') || e.target.classList.contains('qty-minus')) {
            e.preventDefault();
            
            const isPlus = e.target.classList.contains('qty-plus');
            const cartKey = e.target.dataset.cartKey;
            const input = document.querySelector(`input[name="cart[${cartKey}][qty]"]`);
            
            if (input) {
                let value = parseInt(input.value) || 1;
                
                if (isPlus) {
                    value++;
                } else if (value > 1) {
                    value--;
                }
                
                input.value = value;
                
                // Auto-update with debounce
                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(updateCart, 800);
            }
        }
    });
    
    // Input change
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('qty-input')) {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(updateCart, 800);
        }
    });
    
    // Remove item without confirmation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item')) {
            e.preventDefault();
            
            // Add loading state to item
            const item = e.target.closest('.cart-item');
            if (item) {
                item.style.opacity = '0.6';
                item.style.pointerEvents = 'none';
            }
            
            // Navigate to remove URL
            window.location.href = e.target.href;
        }
    });
});
</script>

<?php get_footer(); ?>
