<?php
// woocommerce/parts/product-nutrition.php
// Prikazuje nutritivnu tabelu, info blokove i marketinšku poruku na osnovu sluga ili naziva proizvoda

global $product;
if (!$product) return;

$slug = method_exists($product, 'get_slug') ? $product->get_slug() : sanitize_title($product->get_name());
$name = $product->get_name();

switch (true) {
    case (stripos($slug, 'nutrilux-premium') !== false || stripos($name, 'NUTRILUX Premium') !== false):
        ?>
        <div class="product-nutrition section">
          <h2>Ključne informacije</h2>
          <div class="product-features-grid">
            <div class="product-feature">✅ Albumin – prirodni protein iz bjelanca jajeta.</div>
            <div class="product-feature">✅ Bez šećera i bez zaslađivača.</div>
            <div class="product-feature">✅ Okusi: vanilija, čokolada.</div>
            <div class="product-feature">✅ Pakovanja: 250g i 500g.</div>
          </div>
          <div class="product-marketing">Treniraj pametno, oporavljaj brzo. <b>Snaga za napredak.</b></div>
        </div>
        <?php
        break;
    case (stripos($slug, 'nutrilux-gold') !== false || stripos($name, 'NUTRILUX Gold') !== false):
        ?>
        <div class="product-nutrition section">
          <h2>Ključne informacije</h2>
          <div class="product-features-grid">
            <div class="product-feature">✅ 95% albumin + 5% aroma (vanilija ili čokolada).</div>
            <div class="product-feature">✅ Bez šećera i bez zaslađivača.</div>
            <div class="product-feature">✅ Prirodni protein iz bjelanca jajeta.</div>
            <div class="product-feature">✅ Pakovanja: 250g i 500g.</div>
          </div>
          <div class="product-marketing">Disciplinom do rezultata. Za rekreativce i profesionalce.</div>
        </div>
        <?php
        break;
    case (stripos($slug, 'nutrilux-zero') !== false || stripos($name, 'NUTRILUX Zero') !== false):
        ?>
        <div class="product-nutrition section">
          <h2>Ključne informacije</h2>
          <div class="product-features-grid">
            <div class="product-feature">✅ 100% albumin – čisti prirodni protein bez dodataka.</div>
            <div class="product-feature">✅ Bez šećera i bez zaslađivača.</div>
            <div class="product-feature">✅ Prirodni protein iz bjelanca jajeta.</div>
            <div class="product-feature">✅ Pakovanja: 250g i 500g.</div>
          </div>
          <div class="product-marketing">Minimalizam u sastavu, maksimum u učinku.</div>
        </div>
        <?php
        break;
    case (stripos($slug, 'cijelo-jaje') !== false || stripos($name, 'Cijelo jaje') !== false):
        // Cijelo jaje u prahu
        ?>
        <div class="product-nutrition section">
          <h2>Nutritivne vrijednosti (100g)</h2>
          <table class="nutrition-table">
            <tr><th>Energetska vrijednost</th><td>560 kcal</td></tr>
            <tr><th>Proteini</th><td>46-50 g</td></tr>
            <tr><th>Masti</th><td>35-40 g</td></tr>
            <tr><th>Ugljikohidrati</th><td>4-6 g</td></tr>
            <tr><th>Vlakna</th><td>0 g</td></tr>
            <tr><th>Minerali</th><td>Natrij, kalcij, željezo, fosfor</td></tr>
            <tr><th>Vitamini</th><td>A, D, B12, B2 (riboflavin)</td></tr>
          </table>
          <div class="product-features-grid">
            <div class="product-feature">✅ Sastav: Bjelance i žumance, bez dodataka.</div>
            <div class="product-feature">✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.</div>
            <div class="product-feature">✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.</div>
            <div class="product-feature">✅ Upotreba: Pekarstvo, slastičarstvo, prehrambena industrija, kampovanje.</div>
            <div class="product-feature">✅ Rehidracija: 10-12 g jaja u prahu + 30 ml vode = 1 svježe jaje.</div>
          </div>
          <div class="product-marketing">Energija za trening. Oporavak za mišiće. <b>Snaga za napredak.</b></div>
        </div>
        <?php
        break;
    case (stripos($slug, 'zumance') !== false || stripos($name, 'Žumance') !== false):
        // Žumance u prahu
        ?>
        <div class="product-nutrition section">
          <h2>Nutritivne vrijednosti (100g)</h2>
          <table class="nutrition-table">
            <tr><th>Energetska vrijednost</th><td>600 kcal</td></tr>
            <tr><th>Proteini</th><td>30 g</td></tr>
            <tr><th>Masti</th><td>50 g</td></tr>
            <tr><th>Ugljikohidrati</th><td>5 g</td></tr>
            <tr><th>Vitamini</th><td>A, D, E, K, B12</td></tr>
            <tr><th>Minerali</th><td>Fosfor, željezo, cink</td></tr>
          </table>
          <div class="product-features-grid">
            <div class="product-feature">✅ Sastav: Dehidrirano žumance, bez aditiva.</div>
            <div class="product-feature">✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.</div>
            <div class="product-feature">✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.</div>
            <div class="product-feature">✅ Upotreba: Majoneza, umaci, tijesta, slastičarstvo.</div>
            <div class="product-feature">✅ Rehidracija: 10 g žumanca u prahu + 20 ml vode = 1 svježe žumance.</div>
          </div>
          <div class="product-marketing">Bogato mastima i vitaminima, idealno za kremaste recepte.</div>
        </div>
        <?php
        break;
    case (stripos($slug, 'bjelance') !== false || stripos($name, 'Bjelance') !== false):
        // Bjelance u prahu
        ?>
        <div class="product-nutrition section">
          <h2>Nutritivne vrijednosti (100g)</h2>
          <table class="nutrition-table">
            <tr><th>Energetska vrijednost</th><td>380 kcal</td></tr>
            <tr><th>Proteini</th><td>80 g</td></tr>
            <tr><th>Masti</th><td>0 g</td></tr>
            <tr><th>Ugljikohidrati</th><td>5 g</td></tr>
            <tr><th>Vitamini</th><td>B2, B6, B12</td></tr>
            <tr><th>Minerali</th><td>Natrij, kalij, magnezij</td></tr>
          </table>
          <div class="product-features-grid">
            <div class="product-feature">✅ Sastav: 100% dehidrirano bjelance, bez aditiva.</div>
            <div class="product-feature">✅ Rok trajanja: Do 12 mjeseci na suhom i hladnom mjestu.</div>
            <div class="product-feature">✅ Prednosti: Dugotrajnost, jednostavna upotreba, sigurnost od bakterija.</div>
            <div class="product-feature">✅ Upotreba: Meringe, kolači, omleti, proteinski napici.</div>
            <div class="product-feature">✅ Rehidracija: 3-4 g bjelanca u prahu + 30 ml vode = 1 svježe bjelance.</div>
          </div>
          <div class="product-marketing">Niskokalorično, bogato proteinima, bez masti – idealno za sportiste i dijetalnu prehranu.</div>
        </div>
        <?php
        break;
    case (stripos($slug, 'performance') !== false || stripos($name, 'Performance') !== false):
        // Performance Blend
        ?>
        <div class="product-nutrition section">
          <h2>Nutritivne vrijednosti (porcija 30g)</h2>
          <table class="nutrition-table">
            <tr><th>Energetska vrijednost</th><td>120 kcal</td></tr>
            <tr><th>Proteini</th><td>22 g</td></tr>
            <tr><th>Masti</th><td>1.5 g</td></tr>
            <tr><th>Ugljikohidrati</th><td>3 g</td></tr>
            <tr><th>BCAA</th><td>3.5 g</td></tr>
            <tr><th>Kreatin</th><td>2 g</td></tr>
            <tr><th>Enzimi</th><td>0.6 g</td></tr>
          </table>
          <div class="product-features-grid">
            <div class="product-feature">✅ Albumin, kazein, kreatin, BCAA, enzimi, vanilija.</div>
            <div class="product-feature">✅ Brza apsorpcija, dugotrajna podrška, oporavak mišića.</div>
            <div class="product-feature">✅ Preporučena doza: 30g + 250ml vode/biljnog mlijeka.</div>
            <div class="product-feature">✅ Bez laktoze, pogodno za sportiste.</div>
          </div>
          <div class="product-marketing">Savršen balans snage i oporavka. Brzo djelovanje albumina. Dugotrajna podrška kazeina. Dodatna snaga kreatina. Potpun oporavak uz BCAA i enzime. Sve to u kremastom okusu vanilije.</div>
        </div>
        <?php
        break;
    default:
        // Default: ne prikazuj ništa
        break;
}
