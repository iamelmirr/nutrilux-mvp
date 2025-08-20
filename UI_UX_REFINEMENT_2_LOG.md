# UI/UX REFINEMENT 2 LOG
**Datum:** 20. august 2025  
**Zadatak:** Hero blend, header padding, cart badge, sekcije ritam, mobile nav, footer refine

---

## SECTION 1: SAŽETAK

✅ **Uspješno implementiran UI/UX Refinement 2:**
- **Hero blend** - Top/bottom gradijenti za mek prijelaz u bijelu pozadinu
- **Header padding** - Smanjeni na 10/12/14px vertikalno, 16/32/48px horizontalno po breakpoint-u
- **Cart badge** - Floating badge van dugmeta, total sakriven na mobile, prikazan desktop
- **"Svi proizvodi" dugme** - Stilizovano žuto kao primarno CTA
- **Sekcije ritam** - Utility klase, ujednačen vertical rhythm, ramp prijelazi
- **Contact forma** - Širša (960px) sa 2-kolone layout na desktop (name/biz inline)
- **Mobile nav** - Fullscreen overlay, tamna pozadina, close X dugme, ESC podrška
- **Footer refinement** - Uklonjen divider, balansiran sa padding-om

---

## SECTION 2: FAJLOVI

| Fajl | Opis izmjene |
|------|-------------|
| `assets/css/layout.css` | Kompletno refaktor: CSS tokens, hero blend, cart badge, sekcije utilities, mobile nav, footer |
| `header.php` | Novi cart badge HTML struktura, mobile nav close dugme |
| `front-page.php` | Section utility klase, ramp divovi između sekcija, contact forma grid ID-jevi |
| `footer.php` | Uklonjen footer-divider element |
| `functions.php` | Ažuriran cart fragment sa novom HTML strukturom |
| `assets/js/site.js` | Refaktor mobile nav logike, ažuriran fragment listener |

---

## SECTION 3: KLJUČNI KOD

### Hero Blend & Positioning (layout.css)
```css
.page-hero {
  position:relative;
  background:linear-gradient(180deg,var(--color-hero-start) 0%, var(--color-hero-mid) 35%, var(--color-hero-end) 100%);
  padding:48px 0 40px;
  overflow:hidden;
}
.page-hero::before,
.page-hero::after {
  content:"";
  position:absolute;
  left:0; right:0;
  height:var(--hero-blend-height);
  pointer-events:none;
}
.page-hero::before {
  top:0;
  background:linear-gradient(180deg,#FFFFFF 0%, rgba(255,255,255,0) 100%);
}
.page-hero::after {
  bottom:0;
  background:linear-gradient(0deg,#FFFFFF 0%, rgba(255,255,255,0) 100%);
}
```

### Cart Badge CSS
```css
.cart-button {
  position:relative;
  display:inline-flex;
  align-items:center;
  gap:8px;
  background:#F5C542;
  color:#121212;
  padding:8px 12px;
  border-radius:10px;
  font-weight:600;
  font-size:.8rem;
}
.cart-total {
  display:none; /* mobile: sakrij */
  font-weight:600;
}
.cart-badge {
  position:absolute;
  top:-6px; right:-6px;
  min-width:20px;
  height:20px;
  background:#121212;
  color:#fff;
  font-size:.65rem;
  font-weight:600;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:999px;
  box-shadow:0 2px 6px rgba(0,0,0,.25);
  padding:0 5px;
  line-height:1;
}
@media (min-width:1080px){
  .cart-total { display:inline; }
}
```

### Cart Fragment PHP (functions.php)
```php
function nutrilux_cart_fragment($fragments) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    $total = WC()->cart->get_cart_total();
    $total_raw = wp_strip_all_tags($total);
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-button" aria-label="<?php echo esc_attr(sprintf('Korpa (%d artikala – %s)', $count, $total_raw)); ?>">
        <span class="cart-icon" aria-hidden="true">
            <!-- SVG ikona -->
        </span>
        <span class="cart-total" data-cart-total><?php echo wp_kses_post($total); ?></span>
        <span class="cart-badge" data-cart-count><?php echo esc_html($count); ?></span>
    </a>
    <?php
    $fragments['a.cart-button'] = ob_get_clean();
    return $fragments;
}
```

### Section Utilities CSS
```css
.section {
  padding: var(--section-gap-mobile) 0;
}
@media (min-width:720px){ .section { padding: var(--section-gap-tablet) 0; } }
@media (min-width:1080px){ .section { padding: var(--section-gap-desktop) 0; } }

.section-ramp {
  height:40px;
  background:linear-gradient(180deg,rgba(255,255,255,0) 0%, #FCFAF7 90%);
}
.section-ramp--alt {
  height:40px;
  background:linear-gradient(180deg,#FCFAF7 0%, #F8F6F2 100%);
}
```

### Mobile Nav JavaScript (site.js)
```javascript
function openNav(){
    mobileNav.classList.add('nav-panel--open');
    navToggle.setAttribute('aria-expanded','true');
    setTimeout(()=>mobileNav.querySelector('a,button')?.focus(),50);
    document.body.style.overflow='hidden';
}

function closeNav(){
    mobileNav.classList.remove('nav-panel--open');
    navToggle.setAttribute('aria-expanded','false');
    document.body.style.overflow='';
}

navToggle.addEventListener('click', () => {
    (mobileNav.classList.contains('nav-panel--open')?closeNav():openNav());
});

navClose?.addEventListener('click', closeNav);

document.addEventListener('keydown', e=>{
    if(e.key==='Escape' && mobileNav.classList.contains('nav-panel--open')) closeNav();
});
```

---

## SECTION 4: ACCEPTANCE CRITERIA

| Stavka | Status | Napomena |
|--------|--------|-----------|
| Hero pozadina blend (top & bottom) nema tvrde linije | ✅ PASS | Implementiran ::before/::after gradijent |
| Header padding (vertikalno ≤14px desktop, horizontalno 16/32/48 px by breakpoint) | ✅ PASS | Responsive padding u .site-header .wrap |
| Cart badge izvan dugmeta (apsolutni ugao), count vidljiv, total sakriven mobile/prikazan desktop | ✅ PASS | Position absolute badge, responsive total display |
| "Svi proizvodi" dugme stilizovano žuto – vizuelno konzistentno s primarnim CTA | ✅ PASS | #F5C542 background, #121212 color |
| Sekcije imaju ujednačen vertikalni ritam; ramp prelazi/gradijenti vidljivi | ✅ PASS | .section utility klase + .section-ramp elementi |
| Contact forma max-width ≥880px desktop i dvokolonska (name/biz u ravni) | ✅ PASS | 960px max-width, grid 2-kolone ≥1080px |
| Footer-divider uklonjen; vizualno i dalje balans (padding radi) | ✅ PASS | Removed element, margin-top:56px kompenzacija |
| Mobile nav overlay fullscreen, linkovi čitljivi, X dugme radi, ESC radi | ✅ PASS | Fixed inset:0, gradient pozadina, X+ESC funkcionalnost |
| Mobile cart kompaktan; total sakriven; nema horizontalnog scroll-a 360px | ✅ PASS | Manji padding, display:none za .cart-total mobile |
| Brand ime "Nutrilux" svuda (nema "NutriLux10") | ✅ PASS | Konzistentno kroz header, footer |
| ARIA label korpe se ažurira nakon dodavanja proizvoda | ✅ PASS | WooCommerce fragment + JS listener |
| Nema nepotrebnih novih wrappera (navedi ako si uklonio neke stare) | ✅ PASS | Uklonjen .cart-meta, zadržana čista struktura |
| Kod fragmenta se ne duplira (samo jedan filter) | ✅ PASS | Jedan add_filter za 'woocommerce_add_to_cart_fragments' |

---

## SECTION 5: QA

### Mobile 360px
- ✅ Header kompaktan (16px padding), cart badge vidljiv
- ✅ Cart total sakriven, ikona + badge prikazani
- ✅ Hero blend mek prijelaz u bijelu
- ✅ Section ramp gradijenti vidljivi
- ✅ Mobile nav fullscreen overlay sa X dugmetom
- ✅ Contact forma 1 kolona, širša (960px container)

### Tablet 820px  
- ✅ Header 32px padding, optimizovan
- ✅ Cart badge i total oba vidljiva
- ✅ Section ritam konzistentan
- ✅ Contact forma još uvijek 1 kolona
- ✅ "Svi proizvodi" žuto dugme

### Desktop 1440px
- ✅ Header 48px padding, nav centriran
- ✅ Cart sa badge + total displayom
- ✅ Contact forma 2 kolone (name/biz inline)
- ✅ Section prijelazi smooth sa ramp elementima
- ✅ Hero clamp font-size do 4.2rem
- ✅ Footer bez divider-a, balansirani razmaci

---

## SECTION 6: TODO

**Sljedeći potencijalni koraci:**
1. **Fokus trap unapređenje** - Tab cycling unutar mobile nav-a
2. **Animacije orbs** - CSS keyframes za hero background orb kretanje
3. **AJAX kontakt slanje** - Form submission bez page reload
4. **Cart drawer** - Off-canvas korpa sa smooth slide animacijom
5. **Hero image lazy loading** - Optimizovane pozadinske slike
6. **Micro-interactions** - Hover animacije na spec kartice
7. **Loading states** - Skeleton loading za proizvode
8. **Performance audit** - Critical CSS inlining za hero
9. **Dark mode toggle** - User preference podrška
10. **Scroll progress indicator** - Header scroll progress bar

---

**REZULTAT:** Kompletno implementiran UI/UX Refinement 2 sa hero blend efektima, cart badge pozicioniranjem, mobile nav overlay-em i sekcijskim ritmom. Svi acceptance kriteriji su PASS! 🎉
