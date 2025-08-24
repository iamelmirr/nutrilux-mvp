-- Product Meta Data for Nutrilux Products
-- Run this in phpMyAdmin or Local WP database tool

-- First, let's assume these are the product IDs (adjust if different):
-- Performance Blend = ID 1
-- Cijelo jaje u prahu = ID 2  
-- Žumance u prahu = ID 3
-- Bjelance u prahu = ID 4

-- Delete existing meta data to avoid duplicates
DELETE FROM wp_postmeta WHERE meta_key LIKE '_nutri_%';

-- Performance Blend (ID = 1)
INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
(1, '_nutri_energy_kcal', ''),
(1, '_nutri_protein_g', ''),
(1, '_nutri_fat_g', ''),
(1, '_nutri_carbs_g', ''),
(1, '_nutri_fiber_g', ''),
(1, '_nutri_vitamins', ''),
(1, '_nutri_minerals', ''),
(1, '_nutri_formula_components', 'Albumin: 17,7 g\nKazein: 5,7 g\nKreatin: 2,4 g\nBCAA (2:1:1): 2,1 g\nEnzimi: 1,2 g\nAroma vanilije: 0,9 g'),
(1, '_nutri_recipe_title', 'Performance Blend proteinski shake'),
(1, '_nutri_recipe_ingredients', '30 g Performance Blend formule\n250 ml vode ili biljnog mlijeka\nPo želji: banana ili jagode'),
(1, '_nutri_recipe_instructions', 'Pomiješajte Performance Blend s vodom ili mlijekom.\nPo želji dodajte voće i izmiksajte u blenderu.\nPijte odmah nakon treninga ili kao proteinsku užinu.'),
(1, '_nutri_marketing', 'Idealno za sportiste koji žele izdržljivost, energiju i rast mišića – bez laktoze i neželjenih masti.');

-- Cijelo jaje u prahu (ID = 2)
INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
(2, '_nutri_energy_kcal', '560'),
(2, '_nutri_protein_g', '46–50'),
(2, '_nutri_fat_g', '35–40'),
(2, '_nutri_carbs_g', '4–6'),
(2, '_nutri_fiber_g', '0'),
(2, '_nutri_vitamins', 'A, D, B12, B2'),
(2, '_nutri_minerals', 'Natrij, kalcij, željezo, fosfor'),
(2, '_nutri_ingredients', 'Cijelo jaje u prahu bez ikakvih dodataka'),
(2, '_nutri_shelf_life', 'Do 12 mjeseci'),
(2, '_nutri_rehydration_ratio', 'Jedna kašika praha pomiješana s malo vode = svježe jaje'),
(2, '_nutri_recipe_title', 'Palačinke sa jajetom u prahu'),
(2, '_nutri_recipe_ingredients', '20 g cijelog jajeta u prahu (≈ 2 jaja)\n60 ml vode\n200 ml mlijeka\n150 g brašna\n1 kašika šećera\nPrstohvat soli\n1 kašika ulja'),
(2, '_nutri_recipe_instructions', 'Pomiješajte jaje u prahu s vodom i izmiješajte.\nDodajte mlijeko, šećer, so i brašno, pa umutite tijesto.\nUmiješajte ulje.\nPecite tanke palačinke na tavi s malo ulja.'),
(2, '_nutri_marketing', 'Dodajte vaniliju ili cimet za još bolji ukus.'),
(2, '_nutri_benefits', 'Praktična i dugotrajna zamjena za svježa jaja\nIdealno za kuhanje, pečenje i sportsku prehranu\nBogato proteinima, mastima i vitaminima\nJednostavno za korištenje, sigurno i dugotrajno');

-- Žumance u prahu (ID = 3)
INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
(3, '_nutri_energy_kcal', '600'),
(3, '_nutri_protein_g', '30'),
(3, '_nutri_fat_g', '50'),
(3, '_nutri_carbs_g', '5'),
(3, '_nutri_fiber_g', '0'),
(3, '_nutri_vitamins', 'A, D, E, K, B12'),
(3, '_nutri_minerals', 'Fosfor, željezo, cink'),
(3, '_nutri_ingredients', '100% prirodno žumance u prahu, bez aditiva'),
(3, '_nutri_recipe_title', 'Domaća majoneza sa žumancem u prahu'),
(3, '_nutri_recipe_ingredients', '10 g žumanca u prahu\n20 ml vode\n1 kašičica senfa\n200 ml ulja\n1 kašika limunovog soka\nSo i biber po ukusu'),
(3, '_nutri_recipe_instructions', 'Pomiješajte žumance u prahu s vodom.\nDodajte senf i začine, pa počnite mutiti.\nPolako ulijevajte ulje u tankom mlazu, neprestano muteći.\nDodajte limunov sok i promiješajte.'),
(3, '_nutri_marketing', 'Za gušću majonezu koristite više žumanca u prahu.'),
(3, '_nutri_benefits', 'Savršen sastojak za kremaste umake, majonezu i deserte\nZadržava sve nutritivne vrijednosti svježeg žumanca\nTraje mnogo duže od svježeg žumanca\nKremasta tekstura i bogat okus');

-- Bjelance u prahu (ID = 4)
INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES
(4, '_nutri_energy_kcal', '380'),
(4, '_nutri_protein_g', '80'),
(4, '_nutri_fat_g', '0'),
(4, '_nutri_carbs_g', '5'),
(4, '_nutri_fiber_g', '0'),
(4, '_nutri_vitamins', 'B2, B6, B12'),
(4, '_nutri_minerals', 'Natrij, kalij, magnezij'),
(4, '_nutri_ingredients', '100% prirodno bjelance u prahu, bez aditiva'),
(4, '_nutri_recipe_title', 'Domaći šlag od bjelanca u prahu'),
(4, '_nutri_recipe_ingredients', '10 g bjelanca u prahu\n30 ml tople vode\n50 g šećera\n1 kašičica limunovog soka'),
(4, '_nutri_recipe_instructions', 'Pomiješajte bjelance s toplom vodom.\nMiksajte dok ne postane pjenasto.\nDodajte šećer i limunov sok, pa mutite dok ne dobijete čvrst šlag.\nKoristite za kolače ili voćne salate.'),
(4, '_nutri_marketing', 'Za čvršći šlag dodajte još malo bjelanca u prahu.'),
(4, '_nutri_benefits', 'Čisti proteini, bez masti\nIdealno za sportiste, kolače i lagane obroke\nNajbogatiji izvor proteina iz jajeta\nNiskokalorično i lako probavljivo\nOdlično za pripremu slatkiša poput puslica i beze kore');
