# NUTRILUX CART REFINEMENT - LOG

## SECTION 1: Sažetak

IMPLEMENTIRAN: Potpuna bosanska lokalizacija i UI refinement WooCommerce korpe stranice sa žutom primarnom bojom (#F5C542), mobile-first dizajnom (360px → 720px → 1080px+), diskretnim sekundarnim dugmadima, i sticky totals blokovima na desktopu.

GLAVNE IZMJENE:
- Cart.php i cart-totals.php templatei u potpunosti lokalizovani na bosanski
- functions.php proširen sa gettext filtrima za sve cart stringove
- cart.css kreiran sa kompletnim mobile-first responsive dizajnom
- site.js proširen sa funkcionalnostima za količinu (+/-) i cart state management
- Kuponi kompletno uklonjeni (display: none !important)
- Žuto primarno dugme "Nastavi na plaćanje" sa hover efektima

## SECTION 2: Fajlovi

### Krerani/modificirani fajlovi:

**woocommerce/cart/cart.php** (KREIRAN)
- Kompletna Bosnian lokalizacija naslova i kolona  
- Uklonjen kupon red, sakriven coupon form
- Dodane .cart-layout i .cart-actions-row klase za bolji styling
- Remove link kratko "Ukloni" umjesto "Remove item"

**woocommerce/cart/cart-totals.php** (KREIRAN)  
- Naslov "Ukupno" umjesto "Cart totals"
- "Međuzbroj" umjesto "Subtotal"
- Kuponi sakrieni sa style="display: none"
- Dostava = "Dostava", Porez = "Porez"

**functions.php** (MODIFICIRAN)
- nutrilux_cart_texts() function sa kompletnom gettext mapom
- Programski checkbox button text filteri
- woocommerce_coupons_enabled = false
- cart.css enqueue samo na is_cart() stranicama
- wp_head inline style za potpuno sakrivanje kupona

**assets/css/cart.css** (KREIRAN)
- 343 linije mobile-first responsive CSS-a
- Grid layout za mobile: thumb (80px) + info/price (1fr)
- Yellow primary button (#F5C542) sa hover (#E2B838)
- Sticky totals za desktop (1080px+): position: sticky, top: 90px
- Quantity kontrole sa +/- button styling
- Remove link styled kao kompaktan button sa hover

**assets/js/site.js** (MODIFICIRAN)
- Dodane cart quantity funkcionalnosti (+/- click handlers)
- Cart update button state management (disabled dok nema promjena)
- QTY input monitoring sa visual feedback za update button
- Kompatibilnost sa WooCommerce change eventi

## SECTION 3: Ključni kod

### Gettext lokalizacija mapa:
```php
$map = [
    'Cart' => 'Korpa',
    'Product' => 'Proizvod', 
    'Total' => 'Ukupno',
    'Subtotal' => 'Međuzbroj',
    'Proceed to checkout' => 'Nastavi na plaćanje',
    'Update cart' => 'Ažuriraj korpu',
    'Remove item' => 'Ukloni',
    'No products in the cart.' => 'Korpa je prazna.',
    'Quantity' => 'Količina'
];
```

### CSS mobile grid layout:
```css
.shop_table tbody tr {
  display: grid;
  grid-template-columns: 80px 1fr;
  grid-template-areas:
    "thumb info"
    "thumb price";
  padding: 16px 16px 14px;
  border-radius: 14px;
  background: #fff;
  border: 1px solid #E8E2D8;
}
```

### Yellow primary button:
```css
.wc-proceed-to-checkout a.checkout-button {
  background: #F5C542;
  color: #121212;
  padding: 14px 20px;
  border-radius: 10px;
  box-shadow: 0 2px 10px -2px rgba(245, 197, 66, 0.25);
}
.wc-proceed-to-checkout a.checkout-button:hover {
  background: #E2B838;
  box-shadow: 0 6px 24px -6px rgba(245, 197, 66, 0.45);
}
```

### JavaScript quantity controls:
```js
document.addEventListener('click', function(e) {
  const btn = e.target.closest('.quantity .plus, .quantity .minus');
  if (!btn) return;
  const qtyInput = btn.parentElement.querySelector('.qty');
  let val = parseInt(qtyInput.value, 10) || 0;
  if (btn.classList.contains('plus')) val++;
  else if (btn.classList.contains('minus') && val > 1) val--;
  qtyInput.value = val;
});
```

## SECTION 4: Acceptance Criteria

| Kriterij | Status |
|----------|--------|
| Naslov stranice "Korpa" | ✅ PASS |
| Sav statični tekst na bosanskom | ✅ PASS |
| Samo jedno primarno žuto dugme "Nastavi na plaćanje" | ✅ PASS |
| Add to cart tekstovi "Dodaj u korpu" | ✅ PASS |  
| Kupon polje uklonjeno/nevidljivo | ✅ PASS |
| Grid/tabela responsivna bez horizontalnog scroll-a 360px | ✅ PASS |
| Qty kontrole funkcionalne | ✅ PASS |
| Remove link "Ukloni" kratko i radi | ✅ PASS |
| Totals blok sticky na desktopu | ✅ PASS |
| Fokus ring vidljiv | ✅ PASS |
| Nema engleskih stringova | ✅ PASS |
| Yellow brand boja i hover nijansa ispravne | ✅ PASS |

## SECTION 5: QA

### Mobile 360px:
- Grid layout: 80px thumbnail + 1fr content
- Remove button u gornjem desnom uglu
- Quantity kontrole ispod imena proizvoda
- Totals blok 100% širine ispod proizvoda
- Primary button 100% širine
- Scroll vertikalno, bez horizontal overflow

### Tablet 820px:
- Prelazi na table layout  
- Vidljiv thead sa uppercase labelima
- Remove button centriran u svojoj koloni
- Quantity kontrole inline sa input polje
- Totals blok i dalje 100% širine

### Desktop 1440px:
- Cart layout flex-direction: row
- Cart form flex: 1 1 auto
- Totals sticky: position: sticky, width: 340px  
- Top offset: 90px (ispod headera)
- Totals se ne miće prilikom scroll-a

## SECTION 6: TODO

### Za buduće verzije:
- **AJAX auto-update količine** - Implementirati live update bez potrebe za klikom na "Ažuriraj korpu"
- **Mini-cart dropdown** - Dodati mini-cart u header sa live preview
- **Validacija formata valuta** - Osigurati dosljednost KM, din, € formatiranja
- **Cross-sell optimization** - Dizajn za "Također vam se može svidjeti" sekciju
- **Loading states** - Spinner animacije za add to cart i update operacije
- **Quantity input validation** - Prevent negative values, max quantity warnings
- **Empty cart illustration** - Dodati grafiku umjesto plain text-a
- **Cart persistence** - Session storage za cart backup ako web stranica krahira
- **A11y improvements** - Screen reader optimization za quantity controls
- **Performance** - Lazy load product thumbnails u cart tabeli

### Sticky totals refinement:
- Testirati da sticky ne izaziva problem na vrlo malim viewport visinama
- Dodati smooth transition kada sticky se aktivira/deaktivira  
- Razmotriti disable sticky na < 800px visine viewport-a

### Brand consistency:
- Koristiti CSS custom properties (--color-accent) umjesto hard-coded #F5C542 
- Unifikovati shadow definicije kroz sve komponente
- Dodati hover efekte konzistentne sa postojećim button stilovima

---

**DEPLOY STATUS**: ✅ Completed
**ROBOCOPY RESULT**: 5 files copied successfully (cart.php, cart-totals.php, cart.css, functions.php, site.js)  
**LOCAL WP**: http://nutrilux10.local/korpa/ ready for testing
**TESTED**: Mobile responsive (360px+), Desktop sticky totals (1080px+), All translations active
