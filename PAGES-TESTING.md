# NUTRILUX P9 - O NAMA & KONTAKT TESTING GUIDE

## Pages Overview

### 🎯 What's implemented:

1. **O nama (About Us) Page Template**
   - ✅ Hero section sa naslovom i tagline
   - ✅ Misija i Vizija (2 kartice)
   - ✅ Vrijednosti (4 kartice u grid-u)
   - ✅ Proces (3 numerisana koraka)
   - ✅ CTA link ka kontakt stranici

2. **Kontakt (Contact) Page Template**
   - ✅ Hero section
   - ✅ Grid layout: lijevo forma, desno kontakt info
   - ✅ AJAX contact form sa validacijom
   - ✅ Success message replacement
   - ✅ Admin panel za pregled poruka

3. **AJAX Contact Form Features**
   - ✅ Required field validation (ime, email, poruka)
   - ✅ Real-time validation sa error states
   - ✅ Email format validation
   - ✅ Nonce security
   - ✅ wp_mail integration
   - ✅ Database storage opcija

## 🧪 Testing Checklist:

### O nama Page Test:
1. Create page with slug `o-nama`
2. Set page template to "O nama"
3. Verify sections display:
   - [x] Hero sa "O nama" naslovom
   - [x] Misija i Vizija kartice (side by side on desktop)
   - [x] 4 vrijednosti kartice (4 kolone na desktop)
   - [x] 3 proces koraka sa brojevima
   - [x] CTA dugme linkuje ka `/kontakt/`

### Kontakt Page Test:
1. Create page with slug `kontakt`
2. Set page template to "Kontakt"
3. Verify layout:
   - [x] Hero section
   - [x] 2-column grid (desktop): forma lijevo, info desno
   - [x] Mobile: stacked layout

### Contact Form Validation Test:
1. Try submitting empty form
2. Verify error messages:
   - "Ime je obavezno"
   - "Email je obavezan"
   - "Poruka je obavezna"

3. Test email validation:
   - Invalid: "abc", "test@", "@test.com"
   - Valid: "test@example.com"

4. Test required field indicators:
   - Fields get red border when invalid
   - aria-invalid attributes update
   - Error messages appear below fields

### AJAX Submission Test:
1. Fill form correctly:
   ```
   Ime: Marko Petrović
   Email: marko@example.com
   Poruka: Test poruka sa kontakt forme
   ```

2. Submit form and verify:
   - Loading state shows "Šalje se..."
   - Success message replaces form
   - "Pošalji novu poruku" button works

3. Check admin email was sent
4. Check admin panel: Tools > Kontakt Poruke

### Contact Info Test:
1. Verify contact information displays:
   - [x] Email: info@nutrilux.ba
   - [x] Telefon: +387 61 234 567
   - [x] Adresa: Sarajevo, BiH
   - [x] NO radnog vremena (as specified)

2. Verify contact note/tip section

## 🎨 Visual Elements Test:

### Card Component:
- White background (#fff)
- Border (1px solid)
- Border radius (var(--radius-lg))
- Shadow (var(--shadow-sm))
- Padding (24px = var(--space-xl))

### Responsive Design:
- **Mobile**: stacked layout, full-width cards
- **Tablet (720px+)**: 2-column grids
- **Desktop (1080px+)**: 4-column values, side-by-side process

### Animations:
- Cards fade in with stagger effect
- Success message slides down
- Hover effects on interactive elements

## 📱 Development URLs:

- **O nama page:** `/o-nama/`
- **Kontakt page:** `/kontakt/`
- **Contact admin:** Tools > Kontakt Poruke
- **Page templates:** Appearance > Editor

## 🔧 Admin Features:

### Contact Submissions Admin:
1. Go to Tools > Kontakt Poruke
2. View all submitted messages
3. Update status: New → Replied → Closed
4. Click contact name to see full message
5. Contact person directly via email links

### Database Table:
- Table: `wp_nutrilux_contacts`
- Stores: name, email, message, timestamp, IP, user agent
- Status tracking for contact management

## ⚠️ Common Issues:

1. **Page templates not showing?**
   - Check file names: `page-o-nama.php`, `page-kontakt.php`
   - Refresh permalinks: Settings > Permalinks > Save

2. **AJAX not working?**
   - Check admin-ajax.php URL
   - Verify nonce field in form
   - Check browser console for JS errors

3. **Email not sending?**
   - Test with SMTP plugin
   - Check WordPress email settings
   - Verify admin email in Settings > General

4. **CSS not loading?**
   - Clear cache
   - Check functions.php enqueue
   - Verify file paths

## 🚀 Quick Setup:

1. **Create O nama page:**
   ```
   Title: O nama
   Slug: o-nama
   Template: O nama
   Status: Published
   ```

2. **Create Kontakt page:**
   ```
   Title: Kontakt
   Slug: kontakt
   Template: Kontakt
   Status: Published
   ```

3. **Test contact form:**
   - Fill form with test data
   - Submit and check success message
   - Verify admin email receipt
   - Check Tools > Kontakt Poruke

Both pages are fully functional with responsive design and AJAX capabilities! ✅
