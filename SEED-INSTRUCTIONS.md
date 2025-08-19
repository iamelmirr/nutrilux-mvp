# NUTRILUX P7 - SEED PRODUCTS INSTRUKCIJE

## Kako pokrenuti seed script:

### Metoda 1: WordPress Admin Panel (Preporučeno)
1. Idite u WordPress admin
2. Navigirajte na: **Tools > Product Seeder**
3. Kliknite **"Run Product Seeding"**
4. Potvrdite kreiranje proizvoda

### Metoda 2: Direktan test script
1. Otvorite browser
2. Idite na: `your-site.com/wp-content/themes/theme-nutrilux/inc/seed-test.php`
3. Kliknite **"🚀 Run Seeding"**

### Metoda 3: Programski poziv
```php
// U bilo kojem WordPress fajlu
$results = nutrilux_seed_products();
var_dump($results);
```

## Šta script radi:

### 1. Kreira 4 proizvoda:
- **Cijelo jaje u prahu** - 8.90 BAM
- **Žumance u prahu** - 12.50 BAM  
- **Bjelance u prahu** - 11.90 BAM
- **Performance Blend** - 24.90 BAM

### 2. Za svaki proizvod dodaje:
- Naziv i opis
- Cijenu (regular_price)
- 18-20 custom meta fields sa nutritivnim podacima
- WooCommerce product settings (simple, instock, visible)

### 3. Sigurnosne provjere:
- ✅ Pokreće se samo jednom (flag sistem)
- ✅ Preskače postojeće proizvode
- ✅ Proverava WooCommerce aktivnost
- ✅ Error handling i logging

## Rezultat:
Svi proizvodi će biti vidljivi u:
- **WooCommerce > Products** (admin)
- **Shop stranica** (frontend)
- **Single product stranice** sa kompletnim meta podacima

## Reset za testiranje:
- U admin panelu: **"Reset Seed Flag"**
- Ili direktno: `seed-test.php?reset_flag=1`

## Debugging:
Svi rezultati se loguju sa brojem:
- Kreiranih proizvoda
- Preskočenih proizvoda  
- Meta fields po proizvodu
- Error poruke

## Meta Fields koji se dodaju:
```
_nutri_ingredients          _nutri_usage
_nutri_shelf_life          _nutri_recipe_title  
_nutri_energy_kcal         _nutri_recipe_ingredients
_nutri_protein_g           _nutri_recipe_instructions
_nutri_fat_g               _nutri_storage
_nutri_carbs_g             _nutri_serving
_nutri_fiber_g             _nutri_formula_components*
_nutri_vitamins            _nutri_marketing*
_nutri_minerals            
_nutri_rehydration_ratio   
_nutri_benefits            

* = samo za Performance Blend
```

Script je spreman za produkciju! 🚀
