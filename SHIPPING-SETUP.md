# WooCommerce Shipping Configuration

## Automatska konfiguracija (WP CLI)

Za potpunu automatizaciju shipping zona koristite WP CLI komande:

```bash
# 1. Kreiraj shipping zonu za Bosnu i Hercegovinu
wp wc shipping_zone create --name="Bosna i Hercegovina" --user=admin

# 2. Dodaj lokaciju (Bosna i Hercegovina)
wp wc shipping_zone_location create 1 --type=country --code=BA --user=admin

# 3. Dodaj flat rate shipping metodu
wp wc shipping_zone_method create 1 --method_id=flat_rate --user=admin

# 4. Konfiguriši shipping metodu
wp wc shipping_zone_method update 1 1 --settings='{"title":"Brza pošta","cost":"5"}' --user=admin

# 5. Kreiraj "Ostalo" zonu za ostatak svijeta
wp wc shipping_zone create --name="Ostalo" --user=admin

# 6. Dodaj flat rate za ostale zemlje
wp wc shipping_zone_method create 2 --method_id=flat_rate --user=admin
wp wc shipping_zone_method update 2 1 --settings='{"title":"Međunarodna dostava","cost":"15"}' --user=admin
```

## Manualna konfiguracija (WordPress Admin)

Ako nemate WP CLI, ručno postavite u WP Admin:

### 1. Idite na WooCommerce > Settings > Shipping

### 2. Dodaj shipping zonu:
   - **Naziv**: "Bosna i Hercegovina"
   - **Regioni**: Bosna i Hercegovina
   
### 3. Dodaj shipping metodu:
   - **Metoda**: Flat rate
   - **Naziv**: "Brza pošta"
   - **Cijena**: 5.00 BAM

### 4. Kreiraj drugu zonu:
   - **Naziv**: "Ostalo"
   - **Regioni**: Locations not covered by your other zones
   - **Metoda**: Flat rate
   - **Naziv**: "Međunarodna dostava"  
   - **Cijena**: 15.00 BAM

## Verifikacija

Provjerite da li je sve podešeno:
- Idite na bilo koji proizvod
- Kliknite "Add to cart"
- Idite u korpu
- Trebalo bi da vidite shipping kalkulaciju

## Troubleshooting

Ako shipping ne radi:
1. Provjerite da li je "Enable shipping calculations" uključeno
2. Provjerite da li postoje shipping zone
3. Provjerite da li proizvodi imaju postavljenu težinu
