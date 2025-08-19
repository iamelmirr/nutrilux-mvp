# Meta Fields Testing Guide

## Verifikacija registracije meta polja

### 1. REST API Test
Dodajte `?debug_meta=1` na bilo koju stranicu vašeg sajta kao admin:
```
http://nutrilux-mvp.local/?debug_meta=1
```

### 2. REST API Endpoint Test
Testirajte REST API za proizvode:
```
http://nutrilux-mvp.local/wp-json/wp/v2/product
```

### 3. Testiranje sa konkretnim proizvodom
```
http://nutrilux-mvp.local/?debug_meta=1&product_id=123
```

### 4. WordPress Admin Test
1. Idite u Products > Add New
2. Otvorite browser console (F12)
3. Testirajte meta polja:
```javascript
// Test helper function
wp.data.select('core/editor').getEditedPostAttribute('meta');

// Test specific field
wp.data.select('core/editor').getEditedPostAttribute('meta')['_nutri_ingredients'];
```

## Registrovana meta polja (19 ukupno)

### Osnovna nutritivna polja:
- `_nutri_ingredients` - Sastojci
- `_nutri_shelf_life` - Rok trajanja
- `_nutri_energy_kcal` - Energetska vrijednost
- `_nutri_protein_g` - Proteini (g)
- `_nutri_fat_g` - Masti (g)
- `_nutri_carbs_g` - Ugljikohidrati (g)
- `_nutri_fiber_g` - Vlakna (g)

### Vitamini i minerali:
- `_nutri_vitamins` - Vitamini (; separated)
- `_nutri_minerals` - Minerali (; separated)

### Priprema i serviranje:
- `_nutri_rehydration_ratio` - Omjer rehidracije
- `_nutri_serving` - Veličina porcije

### Multiline polja (newline separated):
- `_nutri_benefits` - Prednosti
- `_nutri_usage` - Upotreba

### Recept:
- `_nutri_recipe_title` - Naziv recepta
- `_nutri_recipe_ingredients` - Sastojci recepta (multiline)
- `_nutri_recipe_instructions` - Instrukcije recepta (multiline)

### Ostalo:
- `_nutri_storage` - Čuvanje
- `_nutri_formula_components` - Komponente formule (Performance Blend)
- `_nutri_marketing` - Marketing poruka

## Helper funkcije

### Osnovne:
- `nutrilux_get_meta($product_id, $meta_key)` - Osnovni getter
- `nutrilux_get_multiline_meta($product_id, $meta_key)` - Array od multiline
- `nutrilux_get_nutritional_info($product_id)` - Svi nutritivni podaci
- `nutrilux_get_recipe_info($product_id)` - Recept podaci
- `nutrilux_format_vitamins_minerals($content)` - Format vitamina/minerala

## Primjer korištenja

```php
$product_id = 123;

// Osnovno
$ingredients = nutrilux_get_meta($product_id, '_nutri_ingredients');

// Multiline
$benefits = nutrilux_get_multiline_meta($product_id, '_nutri_benefits');
foreach ($benefits as $benefit) {
    echo '<li>' . esc_html($benefit) . '</li>';
}

// Nutritivne vrijednosti
$nutrition = nutrilux_get_nutritional_info($product_id);
echo 'Energija: ' . $nutrition['energy_kcal'] . ' kcal';

// Recept
$recipe = nutrilux_get_recipe_info($product_id);
if (!empty($recipe)) {
    echo '<h3>' . $recipe['title'] . '</h3>';
}
```
