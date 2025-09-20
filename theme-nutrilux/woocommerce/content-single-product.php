<?php
/**
 * Single Product Content - Clean Layout
 */

if (!defined('ABSPATH')) {
    exit;
}

global $product;

// Get custom meta data with fallbacks
$short_desc = get_post_meta(get_the_ID(), '_nutrilux_short_description', true);
$detailed_desc = get_post_meta(get_the_ID(), '_nutrilux_detailed_description', true);
$nutrition_facts = get_post_meta(get_the_ID(), '_nutrilux_nutrition_facts', true);

// Fallback content for demo
if (empty($short_desc)) {
    $short_desc = 'Vrhunski protein suplement za optimalne rezultate. Brza resorpcija i maksimalna efikasnost.';
}
if (empty($detailed_desc)) {
    $detailed_desc = 'Naš protein je kreiran koristeći najkvalitetnije sastojke i napredne tehnologije proizvodnje. Idealan za sportiste i sve koji žele postići najbolje rezultate u treningu i oporavku. Sadrži sve esencijalne aminokiseline potrebne za brzi oporavak mišića.';
}
if (empty($nutrition_facts)) {
    $nutrition_facts = "Energijska vrijednost|380 kcal\nProteini|85g\nUgljeni hidrati|2g\nMasti|6g\nSol|0.5g";
}
?>

<div class="single-product-layout">
    <!-- Left Side - Sticky -->
    <div class="product-left">
        <!-- Product Image -->
        <div class="product-image">
            <?php if (has_post_thumbnail()): ?>
                <?php echo get_the_post_thumbnail(get_the_ID(), 'large'); ?>
            <?php endif; ?>
        </div>
        
        <!-- Product Name -->
        <h1 class="product-name"><?php echo get_the_title(); ?></h1>
        
        <!-- Price -->
        <div class="product-price">
            <?php echo $product->get_price_html(); ?>
        </div>
        
        <!-- Quantity & Add to Cart -->
        <form class="cart-form" method="post" enctype='multipart/form-data'>
            <div class="quantity-wrapper">
                <label for="quantity">Količina:</label>
                <input type="number" 
                       id="quantity" 
                       name="quantity" 
                       value="1" 
                       min="1" 
                       step="1">
            </div>
            
            <button type="submit" 
                    name="add-to-cart" 
                    value="<?php echo esc_attr($product->get_id()); ?>" 
                    class="add-to-cart-button">
                DODAJ U KORPU
            </button>
        </form>
    </div>
    
    <!-- Right Side - Content -->
    <div class="product-right">
        <!-- Short Description -->
        <div class="short-description">
            <h2>Kratki opis</h2>
            <p><?php echo nl2br(esc_html($short_desc)); ?></p>
        </div>
        
        <!-- Detailed Description -->
        <div class="detailed-description">
            <h2>Detaljni opis</h2>
            <p><?php echo nl2br(esc_html($detailed_desc)); ?></p>
        </div>
        
        <!-- Nutrition Table -->
        <div class="nutrition-table">
            <h2>Nutritivne vrijednosti</h2>
            <table>
                <tbody>
                    <?php
                    $facts = explode("\n", $nutrition_facts);
                    foreach ($facts as $fact) {
                        $fact = trim($fact);
                        if (!empty($fact) && strpos($fact, '|') !== false) {
                            $parts = explode('|', $fact, 2);
                            echo '<tr>';
                            echo '<td>' . esc_html(trim($parts[0])) . '</td>';
                            echo '<td>' . esc_html(trim($parts[1])) . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>