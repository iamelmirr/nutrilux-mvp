<?php
/**
 * Script to add product meta data to Nutrilux products
 * Run this file once by accessing it via browser: http://nutrilux10.local/wp-content/themes/nutrilux/add_product_data.php
 */

// WordPress bootstrap
require_once('../../../wp-load.php');

// Check if user is admin
if (!current_user_can('manage_options')) {
    die('Access denied. Please login as administrator.');
}

// Product data array
$products_data = array(
    'Performance Blend' => array(
        'formula_components' => "Albumin: 17,7 g\nKazein: 5,7 g\nKreatin: 2,4 g\nBCAA (2:1:1): 2,1 g\nEnzimi: 1,2 g\nAroma vanilije: 0,9 g",
        'recipe_title' => 'Performance Blend proteinski shake',
        'recipe_ingredients' => "30 g Performance Blend formule\n250 ml vode ili biljnog mlijeka\nPo želji: banana ili jagode",
        'recipe_instructions' => "Pomiješajte Performance Blend s vodom ili mlijekom.\nPo želji dodajte voće i izmiksajte u blenderu.\nPijte odmah nakon treninga ili kao proteinsku užinu.",
        'marketing' => 'Idealno za sportiste koji žele izdržljivost, energiju i rast mišića – bez laktoze i neželjenih masti.'
    ),
    
    'Cijelo jaje u prahu' => array(
        'energy_kcal' => '560',
        'protein_g' => '46–50',
        'fat_g' => '35–40',
        'carbs_g' => '4–6',
        'fiber_g' => '0',
        'vitamins' => 'A, D, B12, B2',
        'minerals' => 'Natrij, kalcij, željezo, fosfor',
        'ingredients' => 'Cijelo jaje u prahu bez ikakvih dodataka',
        'shelf_life' => 'Do 12 mjeseci',
        'rehydration_ratio' => 'Jedna kašika praha pomiješana s malo vode = svježe jaje',
        'recipe_title' => 'Palačinke sa jajetom u prahu',
        'recipe_ingredients' => "20 g cijelog jajeta u prahu (≈ 2 jaja)\n60 ml vode\n200 ml mlijeka\n150 g brašna\n1 kašika šećera\nPrstohvat soli\n1 kašika ulja",
        'recipe_instructions' => "Pomiješajte jaje u prahu s vodom i izmiješajte.\nDodajte mlijeko, šećer, so i brašno, pa umutite tijesto.\nUmiješajte ulje.\nPecite tanke palačinke na tavi s malo ulja.",
        'marketing' => 'Dodajte vaniliju ili cimet za još bolji ukus.',
        'benefits' => "Praktična i dugotrajna zamjena za svježa jaja\nIdealno za kuhanje, pečenje i sportsku prehranu\nBogato proteinima, mastima i vitaminima\nJednostavno za korištenje, sigurno i dugotrajno"
    ),
    
    'Žumance u prahu' => array(
        'energy_kcal' => '600',
        'protein_g' => '30',
        'fat_g' => '50',
        'carbs_g' => '5',
        'fiber_g' => '0',
        'vitamins' => 'A, D, E, K, B12',
        'minerals' => 'Fosfor, željezo, cink',
        'ingredients' => '100% prirodno žumance u prahu, bez aditiva',
        'recipe_title' => 'Domaća majoneza sa žumancem u prahu',
        'recipe_ingredients' => "10 g žumanca u prahu\n20 ml vode\n1 kašičica senfa\n200 ml ulja\n1 kašika limunovog soka\nSo i biber po ukusu",
        'recipe_instructions' => "Pomiješajte žumance u prahu s vodom.\nDodajte senf i začine, pa počnite mutiti.\nPolako ulijevajte ulje u tankom mlazu, neprestano muteći.\nDodajte limunov sok i promiješajte.",
        'marketing' => 'Za gušću majonezu koristite više žumanca u prahu.',
        'benefits' => "Savršen sastojak za kremaste umake, majonezu i deserte\nZadržava sve nutritivne vrijednosti svježeg žumanca\nTraje mnogo duže od svježeg žumanca\nKremasta tekstura i bogat okus"
    ),
    
    'Bjelance u prahu' => array(
        'energy_kcal' => '380',
        'protein_g' => '80',
        'fat_g' => '0',
        'carbs_g' => '5',
        'fiber_g' => '0',
        'vitamins' => 'B2, B6, B12',
        'minerals' => 'Natrij, kalij, magnezij',
        'ingredients' => '100% prirodno bjelance u prahu, bez aditiva',
        'recipe_title' => 'Domaći šlag od bjelanca u prahu',
        'recipe_ingredients' => "10 g bjelanca u prahu\n30 ml tople vode\n50 g šećera\n1 kašičica limunovog soka",
        'recipe_instructions' => "Pomiješajte bjelance s toplom vodom.\nMiksajte dok ne postane pjenasto.\nDodajte šećer i limunov sok, pa mutite dok ne dobijete čvrst šlag.\nKoristite za kolače ili voćne salate.",
        'marketing' => 'Za čvršći šlag dodajte još malo bjelanca u prahu.',
        'benefits' => "Čisti proteini, bez masti\nIdealno za sportiste, kolače i lagane obroke\nNajbogatiji izvor proteina iz jajeta\nNiskokalorično i lako probavljivo\nOdlično za pripremu slatkiša poput puslica i beze kore"
    )
);

echo "<h1>Adding Product Meta Data...</h1>";

// Process each product
foreach ($products_data as $product_title => $meta_data) {
    // Find product by title
    $product = get_page_by_title($product_title, OBJECT, 'product');
    
    if (!$product) {
        echo "<p style='color: red;'>Product '$product_title' not found!</p>";
        continue;
    }
    
    $product_id = $product->ID;
    echo "<h3>Processing: $product_title (ID: $product_id)</h3>";
    
    // Add meta data
    foreach ($meta_data as $key => $value) {
        $meta_key = '_nutri_' . $key;
        update_post_meta($product_id, $meta_key, $value);
        echo "<p>✓ Added: $meta_key</p>";
    }
}

echo "<h2 style='color: green;'>✅ All product data has been added successfully!</h2>";
echo "<p><a href='" . admin_url('edit.php?post_type=product') . "'>Go to Products Admin</a></p>";
echo "<p><a href='" . home_url() . "'>Go to Homepage</a></p>";

// Optional: Update excerpt and content for products
$product_contents = array(
    'Performance Blend' => array(
        'excerpt' => 'Performance Blend – savršen miks albumina, kazeina, kreatina i BCAA za maksimalnu snagu i brži oporavak.',
        'content' => 'Performance Blend donosi jedinstven spoj proteina i dodataka: albumin za brzi oporavak, kazein za dugotrajnu podršku, kreatin za snagu i BCAA za regeneraciju. Dodani enzimi poboljšavaju probavu, a ukus vanilije čini konzumaciju užitkom. Idealno za sportiste koji žele izdržljivost, energiju i rast mišića – bez laktoze i neželjenih masti.'
    ),
    'Cijelo jaje u prahu' => array(
        'excerpt' => 'Prirodno cijelo jaje u prahu – praktična i dugotrajna zamjena za svježa jaja. Idealno za kuhanje, pečenje i sportsku prehranu.',
        'content' => 'Cijelo jaje u prahu spaja bjelance i žumance bez ikakvih dodataka. Savršeno je rješenje za profesionalce i domaćinstva – jednostavno za korištenje, sigurno i dugotrajno. Rok trajanja do 12 mjeseci znači da uvijek imate svježa jaja pri ruci, bez brige o kvarenju. Jedna kašika praha pomiješana s malo vode pretvara se u svježe jaje – spremno za palačinke, kolače, hljeb, tjestenine i brojne druge recepte. Bogato proteinima, mastima i vitaminima, ovo je proizvod koji kombinuje zdravlje i praktičnost.'
    ),
    'Žumance u prahu' => array(
        'excerpt' => 'Žumance u prahu – savršen sastojak za kremaste umake, majonezu i deserte.',
        'content' => 'Žumance u prahu je 100% prirodno, bez aditiva. Zadržava sve nutritivne vrijednosti svježeg žumanca, ali traje mnogo duže. Pogodno je za pravljenje domaće majoneze, umaka, slatkih krema i biskvita. Svojom kremastom teksturom i bogatim okusom čini razliku u svakoj kuhinji. Lako za upotrebu i čuvanje, bez brige o kvarenju.'
    ),
    'Bjelance u prahu' => array(
        'excerpt' => 'Bjelance u prahu – čisti proteini, bez masti. Idealno za sportiste, kolače i lagane obroke.',
        'content' => 'Bjelance u prahu je 100% prirodno, bez aditiva. Najbogatiji izvor proteina iz jajeta – sa čak 80 g proteina na 100 g proizvoda. Gotovo bez masti, niskokalorično i lako probavljivo. Odlično za pripremu slatkiša poput puslica i beze kore, proteinskih napitaka i fitnes obroka. Praktično rješenje za sve koji žele visok unos proteina uz minimum kalorija.'
    )
);

echo "<h3>Updating Product Descriptions...</h3>";

foreach ($product_contents as $product_title => $content_data) {
    $product = get_page_by_title($product_title, OBJECT, 'product');
    
    if ($product) {
        wp_update_post(array(
            'ID' => $product->ID,
            'post_excerpt' => $content_data['excerpt'],
            'post_content' => $content_data['content']
        ));
        echo "<p>✓ Updated descriptions for: $product_title</p>";
    }
}

echo "<h2 style='color: blue;'>🎉 Everything is complete! You can now view the product pages.</h2>";
?>
