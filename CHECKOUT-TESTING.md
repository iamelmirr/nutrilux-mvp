# NUTRILUX P8 - CHECKOUT TESTING GUIDE

## Checkout Customizations Overview

### 🎯 What's implemented:

1. **Minimal Checkout Form**
   - ✅ Removed: company, address_2, state, postcode, country, shipping fields
   - ✅ Required: first_name, last_name, address_1, city, phone, email
   - ✅ Optional: order_comments
   - ✅ Default country: Bosnia (BA)

2. **COD Payment Method**
   - ✅ Title: "Plaćanje pouzećem (brza pošta)"
   - ✅ Description: "Plaćate gotovinom kuriru prilikom preuzimanja pošiljke"

3. **Email Customizations**
   - ✅ Admin new order: "Nova narudžba (Pouzeće) #ORDER"
   - ✅ Customer processing: "Potvrda narudžbe #ORDER (Pouzeće)"
   - ✅ Additional paragraph in customer email after order table

4. **Thank You Page**
   - ✅ Custom COD notice with order number and instructions
   - ✅ Clear delivery and payment instructions

## 🧪 Testing Checklist:

### Checkout Form Test:
1. Go to `/checkout` page
2. Verify only these fields are present:
   - [x] Ime (First Name) *
   - [x] Prezime (Last Name) *
   - [x] Adresa (Address) *
   - [x] Grad (City) *
   - [x] Telefon (Phone) *
   - [x] Email *
   - [x] Napomene o narudžbi (optional)

3. Verify these fields are NOT present:
   - [ ] Company
   - [ ] Address 2
   - [ ] State/County
   - [ ] Postcode
   - [ ] Country
   - [ ] Shipping fields

### Payment Method Test:
1. Check payment methods section
2. Verify COD shows as: "Plaćanje pouzećem (brza pošta)"
3. Verify description mentions cash on delivery

### Form Validation Test:
1. Try submitting without required fields
2. Test phone validation:
   - Valid: 061234567, +387 61 234 567, 387-61-234-567
   - Invalid: abc, 123, very short numbers

### Email Test:
1. Complete an order
2. Check admin email subject: "Nova narudžba (Pouzeće) #123"
3. Check customer email:
   - Subject: "Potvrda narudžbe #123 (Pouzeće)"
   - Contains additional paragraph about COD payment
   - Contains info@nutrilux.ba contact

### Thank You Page Test:
1. Complete order successfully
2. Verify thank you page shows:
   - Order number
   - COD payment confirmation
   - Delivery timeline (1-2 days prep, 2-3 days delivery)
   - Contact email: info@nutrilux.ba

## 🔧 Development URLs:

- **Checkout page:** `/checkout`
- **Cart page:** `/cart` (add products first)
- **Test orders:** WooCommerce > Orders (admin)
- **Email templates:** WooCommerce > Settings > Emails

## 📱 Responsive Test:

### Mobile (< 720px):
- Fields full width
- Place order button full width
- Form sections stacked

### Tablet (720px+):
- Some fields side-by-side (first/last name)
- Better spacing

### Desktop (1080px+):
- Centered layout (max 800px)
- Optimal form layout

## 🎨 Visual Elements:

- Green success styling for COD notices
- Clean, minimal form design
- Consistent with Nutrilux brand colors
- Smooth animations and transitions

## ⚠️ Common Issues:

1. **Phone validation too strict?**
   - Adjust regex in `nutrilux_validate_phone_field()`

2. **Missing shipping options?**
   - We're using billing address for everything (minimal approach)

3. **Email not sending?**
   - Check WordPress email configuration
   - Use SMTP plugin if needed

4. **CSS not loading?**
   - Clear cache
   - Check functions.php enqueue

## 🚀 Quick Test Order:

1. Add product to cart
2. Go to checkout
3. Fill minimal form:
   ```
   Ime: Marko
   Prezime: Petrović
   Adresa: Kralja Petra 123
   Grad: Sarajevo
   Telefon: 061234567
   Email: test@example.com
   ```
4. Select COD payment
5. Place order
6. Check thank you page and emails

All features implemented and ready for testing! ✅
