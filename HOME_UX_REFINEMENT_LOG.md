# HOME UX REFINEMENT & CART LIVE UPDATE LOG
**Datum:** 20. august 2025  
**Zadatak:** Mobile-first refaktor, modernizacija hero sekcije, live cart update i brendiranje "Nutrilux"

---

## SECTION 1: SAŽETAK

✅ **Uspješno implementiran kompletan UX refinement:**
- **Admin bar prostor riješen** - sticky header sa CSS custom property kompenzacijom
- **Hero modernizovan** - novi dizajn sa orbs, gradient pozadina, 2 CTA dugmeta
- **Mobile-first pristup** - base (<720px), tablet (≥720px), desktop (≥1080px)
- **Live cart update** - WooCommerce fragments sa brojem artikala + ukupnom cijenom
- **Brendiranje konzistentno** - "Nutrilux" svugdje, footer poboljšan
- **Vertikalni ritam** - dosljedni section paddings i alterniranje pozadina
- **Header refaktor** - sticky pozicioniranje, cart meta, scroll efekat

---

## SECTION 2: FAJLOVI

| Fajl | Opis izmjene |
|------|-------------|
| `header.php` | Brand "Nutrilux", cart live update sa meta (count + total), čišći markup |
| `footer.php` | Brand "Nutrilux", divider dodан, centrirana kontakt kolona |
| `front-page.php` | Novi hero HTML sa orbs + 2 CTA, poboljšan tekstualni sadržaj |
| `functions.php` | WooCommerce cart fragment filter za live update |
| `assets/css/layout.css` | Kompletno mobile-first refaktor, novi CSS tokens, hero, sekcije |
| `assets/js/site.js` | Header scroll efekat, cart fragment safety re-label |

---

## SECTION 3: KLJUČNI KOD

### Header Cart Fragment (functions.php)
```php
function nutrilux_cart_fragment($fragments) {
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    $total = WC()->cart->get_cart_total();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart-button" aria-label="<?php echo esc_attr( sprintf('Korpa (%d artikala – %s)', $count, wp_strip_all_tags($total) ) ); ?>">
        <span class="cart-meta">
            <span class="cart-count" data-cart-count><?php echo esc_html($count); ?></span>
            <span class="cart-total" data-cart-total><?php echo wp_kses_post($total); ?></span>
        </span>
    </a>
    <?php
    $fragments['a.cart-button'] = ob_get_clean();
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'nutrilux_cart_fragment');
```

### Hero HTML (front-page.php)
```html
<section class="page-hero">
    <div class="hero-bg-layers">
        <span class="hero-orb hero-orb--left"></span>
        <span class="hero-orb hero-orb--right"></span>
    </div>
    <div class="wrap">
        <div class="hero-content">
            <h1 class="page-title">Premium nutritivna rješenja od jajeta</h1>
            <p class="hero-description">
                Nutrilux proizvodi kombinuju čistoću sastojaka i kontrolisan kvalitet – za profesionalce, sportiste i kućnu primjenu.
            </p>
            <div class="hero-actions">
                <a href="/shop/" class="btn btn-primary">Pogledaj proizvode</a>
                <a href="/kontakt/" class="btn btn-secondary">Kontaktiraj nas</a>
            </div>
        </div>
    </div>
</section>
```

### CSS Tokens & Sticky Header (layout.css)
```css
:root {
  --space-xs:8px; --space-sm:16px; --space-md:24px; --space-lg:40px; --space-xl:64px;
  --section-v-mobile:56px; --section-v-tablet:72px; --section-v-desktop:96px;
  --color-bg-soft:#FCFAF7; --color-bg-alt:#F8F6F2;
  --gradient-hero:linear-gradient(120deg,#FFFBE7 0%,#F8F6F2 100%);
}

body { --admin-bar-offset:0px; }
body.admin-bar { --admin-bar-offset:32px; }
@media (max-width:782px){ body.admin-bar { --admin-bar-offset:46px; } }

.site-header {
  position:sticky;
  top:var(--admin-bar-offset);
  background:#FFFFFFCC;
  backdrop-filter:blur(10px);
  border-bottom:1px solid #ECE8E0;
  z-index:1000;
  transition:box-shadow .25s, background .25s;
}
```

### Cart Button CSS
```css
.cart-button {
  display:inline-flex;
  align-items:center;
  gap:10px;
  background:var(--color-accent,#F5C542);
  color:#fff;
  padding:8px 16px;
  border-radius:10px;
  text-decoration:none;
  font-weight:600;
  transition:background .25s, transform .2s;
}
.cart-meta { display:flex; gap:8px; align-items:center; }
.cart-count {
  background:rgba(255,255,255,.2);
  padding:4px 10px;
  border-radius:999px;
  font-size:.75rem;
  font-weight:600;
}
.cart-total {
  background:#fff;
  color:#1d1d1d;
  padding:4px 10px;
  border-radius:999px;
  font-weight:600;
}
```

---

## SECTION 4: ACCEPTANCE CRITERIA

| Stavka | Status | Napomena |
|--------|--------|-----------|
| Header nema više prevelik prazan prostor; hero počinje odmah ispod | ✅ PASS | Sticky header + CSS custom property riješio problem |
| Hero novi dizajn (dvije orbs, gradient, dva CTA) | ✅ PASS | Implementiran sa blur orbs + "Kontaktiraj nas" dugme |
| Dodatno dugme "Kontaktiraj nas" prisutno i radi | ✅ PASS | Outline stil, stack mobile → inline desktop |
| Cart dugme prikazuje count + total i osvježava se nakon dodavanja | ✅ PASS | Live fragment update sa brojem i ukupom |
| Brand name svuda "Nutrilux" | ✅ PASS | Header, footer i funkcije ažurirane |
| Mobile header: logo lijevo, cart + toggle desno | ✅ PASS | Grid layout sa justify-content:flex-end |
| Scroll: sticky header bez bijelog "praznog" pojasa | ✅ PASS | Admin bar offset + backdrop-filter |
| Sekcije imaju dosljedan vertikalni ritam | ✅ PASS | CSS tokens --section-v-* + alterniranje pozadina |
| Footer linkovi čitljivi (kontrast AA) i kontakt kolona centrirana | ✅ PASS | #E8E7E4 boja + text-align:center |
| Promo citat i specs kartice prelaze s 1 → 2 → 4 kolone | ✅ PASS | Grid responzivan layout |
| Form polja vizualno konzistentna i fokus stil jasan | ✅ PASS | Border + box-shadow na focus |
| Nema horizontalnog scroll-a na 360px širini | ✅ PASS | Mobile-first optimizovan |
| P-card hover efekat radi | ✅ PASS | Transform + box-shadow animacija |
| Kod bez suvišnih novih wrappera | ✅ PASS | Postojeća struktura zadržana |

---

## SECTION 5: QA

### Mobile 360px
- ✅ Logo lijevo, cart + toggle desno
- ✅ Hero text i CTA stack vertikalno  
- ✅ Sekcije bez horizontalnog overflow-a
- ✅ Footer 1 kolona layout
- ✅ Cart meta responsive (count + total)

### Tablet 820px  
- ✅ Hero CTA inline (flex-row)
- ✅ Footer 2 kolone
- ✅ Specs grid 2x2
- ✅ Promo quote veći font

### Desktop 1440px
- ✅ Nav centriran sa hover efektima
- ✅ Footer 3 kolone
- ✅ Hero maksimalna širina
- ✅ Specs grid 1x4
- ✅ Backdrop blur header efekat

---

## SECTION 6: TODO

**Sljedeći potencijalni koraci:**
1. **Animacija orbs** - CSS keyframes za subtilno kretanje 
2. **Lazy hero image** - Dodati hero background sliku sa lazy loading
3. **Form AJAX** - Contact form bez page reload
4. **Cart drawer** - Off-canvas korpa umjesto redirect
5. **Product quick view** - Modal preview proizvoda
6. **Search functionality** - Header search toggle
7. **Newsletter signup** - Footer email subscription
8. **Performance optimizacija** - Critical CSS inlining

---

**REZULTAT:** Kompletno implementiran UX refinement sa live cart update-om, modernim hero dizajnom i mobile-first pristupom. Svi acceptance kriteriji ispunjeni!
