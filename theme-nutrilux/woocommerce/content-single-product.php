<?php
defined('ABSPATH') || exit;
global $product;
?>
<div class="single-product-grid">
    <!-- Product Images -->
    <div class="product-gallery">
        <?php woocommerce_show_product_images(); ?>
    </div>
  
    <!-- Product Info -->
    <div class="product-info">
        <h1 class="product-title"><?php the_title(); ?></h1>
        <div class="product-price"><?php echo $product->get_price_html(); ?></div>
        <?php
        $short_desc = $product->get_short_description();
        $long_desc = $product->get_description();
        $custom_short = '';
        $custom_long = '';
        $slug = method_exists($product, 'get_slug') ? $product->get_slug() : sanitize_title($product->get_name());
        $name = $product->get_name();
        // Tipografski opisi po proizvodu
        switch (true) {
            case (stripos($slug, 'cijelo-jaje') !== false || stripos($name, 'Cijelo jaje') !== false):
                $custom_short = 'Cijelo jaje u prahu je praktično rješenje za kuhanje i pečenje. Sadrži bjelance i žumance, bez dodataka. Prilagođeno za vegetarijance.';
                $custom_long = '✅ Sastav: Bjelance i žumance, bez dodataka.<br>✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.<br>✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.<br>✅ Upotreba: Pekarstvo, slastičarstvo, prehrambena industrija, kampovanje.<br>✅ Rehidracija: 10-12 g jaja u prahu + 30 ml vode = 1 svježe jaje.<br>1 kg- 80-100 svježih jaja, 100 g-8-10 jaja.';
                break;
            case (stripos($slug, 'zumance') !== false || stripos($name, 'Žumance') !== false):
                $custom_short = 'Žumance u prahu je idealno za majonezu, umake i slastičarstvo. Dehidrirano, bez aditiva.';
                $custom_long = '✅ Sastav: Dehidrirano žumance, bez aditiva.<br>✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.<br>✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.<br>✅ Upotreba: Majoneza, umaci, tijesta, slastičarstvo.<br>✅ Rehidracija: 10 g žumanca u prahu + 20 ml vode = 1 svježe žumance.<br>1 kg-110 svježih žumanca, 100 g-11 žumanaca.';
                break;
            case (stripos($slug, 'bjelance') !== false || stripos($name, 'Bjelance') !== false):
                $custom_short = 'Bjelance u prahu je bogato proteinima, bez masti i aditiva. Idealno za sportiste i dijetalnu prehranu.';
                $custom_long = '✅ Sastav: 100% dehidrirano bjelance, bez aditiva.<br>✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.<br>✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.<br>✅ Upotreba: Meringe, kolači, omleti, proteinski napici.<br>✅ Rehidracija: 3-4 g bjelanca u prahu + 30 ml vode = 1 svježe bjelance.<br>1 kg- 270-300 svježih bjelanaca, 100 g-27-30 bjelanaca.';
                break;
            case (stripos($slug, 'performance') !== false || stripos($name, 'Performance') !== false):
                $custom_short = 'Performance Blend je napredna proteinska formula za snagu i oporavak. Sadrži albumin, kazein, kreatin, BCAA i enzime.';
                $custom_long = '✅ Albumin, kazein, kreatin, BCAA, enzimi, vanilija.<br>✅ Brza apsorpcija, dugotrajna podrška, oporavak mišića.<br>✅ Preporučena doza: 30g + 250ml vode/biljnog mlijeka.<br>✅ Bez laktoze, pogodno za sportiste.';
                break;
        }
        ?>
        <?php if ($short_desc): ?>
            <div class="product-short-desc"><?php echo apply_filters('woocommerce_short_description', $short_desc); ?></div>
        <?php elseif ($custom_short): ?>
            <div class="product-short-desc"><?php echo $custom_short; ?></div>
        <?php endif; ?>
        <div class="product-add-to-cart">
            <?php woocommerce_template_single_add_to_cart(); ?>
        </div>
        <?php if ($long_desc): ?>
            <div class="product-long-desc"><?php echo apply_filters('the_content', $long_desc); ?></div>
        <?php elseif ($custom_long): ?>
            <div class="product-long-desc"><?php echo $custom_long; ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Nutritivna tabela i info blok (dinamički po slug/nazivu) -->
<?php get_template_part('woocommerce/parts/product-nutrition'); ?>

<!-- Footer -->
<?php get_footer(); ?>
