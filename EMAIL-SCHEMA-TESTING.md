# NUTRILUX P10 - EMAIL BRANDING & SCHEMA TESTING GUIDE

## Overview

### 🎯 What's implemented:

1. **Custom Email Headers & Footers**
   - ✅ Branded email header sa Nutrilux logo/naziv u brand boji
   - ✅ Custom footer sa tagline "Nutrilux – premium rješenja od jaja u prahu"
   - ✅ Contact informacije u footer-u
   - ✅ Google Fonts integration za email styling

2. **JSON-LD Structured Data**
   - ✅ Organization schema (homepage only)
   - ✅ Product schema sa nutritional info (single products only)
   - ✅ Website schema sa search functionality
   - ✅ Breadcrumb schema za navigation
   - ✅ Duplicate prevention system

3. **Security & Performance**
   - ✅ Global flag za prevent duplicate schema
   - ✅ Valid JSON output (no trailing commas)
   - ✅ Conditional loading based on page type
   - ✅ Debug logging when WP_DEBUG enabled

## 🧪 Testing Checklist:

### Email Branding Test:
1. **Preview Email Template:**
   - Go to: `/wp-content/themes/theme-nutrilux/inc/email-preview.php`
   - Verify custom header styling shows Nutrilux branding
   - Check footer contains tagline and contact info

2. **Live Email Test:**
   - Place test order (use any product)
   - Check admin email: "Nova narudžba (Pouzeće) #123"
   - Check customer email: "Potvrda narudžbe #123 (Pouzeće)"
   - Verify custom header/footer appear in both emails

3. **Email Client Compatibility:**
   - Test in Gmail, Outlook, Apple Mail
   - Verify brand colors render correctly
   - Check responsive design on mobile

### Schema JSON-LD Test:

#### Homepage Schema:
1. Visit homepage (`/`)
2. View page source (Ctrl+U)
3. Search for "application/ld+json"
4. Verify these schemas appear:
   - **Organization Schema** with Nutrilux info
   - **Website Schema** with search functionality

#### Product Page Schema:
1. Visit any single product page
2. View page source (Ctrl+U)
3. Verify these schemas appear:
   - **Product Schema** with nutritional data
   - **Breadcrumb Schema** for navigation

#### Schema Validation:
1. Copy JSON-LD from page source
2. Validate at [JSONLint.com](https://jsonlint.com/)
3. Test with [Google Rich Results Test](https://search.google.com/test/rich-results)
4. Check [Schema.org Validator](https://validator.schema.org/)

## 📧 Email Template Features:

### Header Styling:
- **Background:** Linear gradient (dark green to light green)
- **Logo/Title:** "Nutrilux" u velikim slovima
- **Tagline:** "Premium rješenja od jaja u prahu"
- **Typography:** Poppins za title, Inter za text

### Footer Content:
- **Main tagline:** "Nutrilux – premium rješenja od jaja u prahu."
- **Contact info:** Email i telefon sa linkovima
- **Copyright:** Trenutna godina
- **Styling:** Light background sa brand accent color

### Email Types Customized:
- New order (admin notification)
- Customer processing order
- Customer completed order
- All other WooCommerce emails

## 🔍 Schema Implementation Details:

### Organization Schema (Homepage):
```json
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Nutrilux",
  "url": "https://your-site.com",
  "logo": "https://your-site.com/logo.png",
  "contactPoint": {
    "@type": "ContactPoint", 
    "email": "info@nutrilux.ba",
    "contactType": "customer support",
    "areaServed": "BA"
  }
}
```

### Product Schema (Single Product):
```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "Product Name",
  "brand": {"@type": "Brand", "name": "Nutrilux"},
  "nutrition": {
    "@type": "NutritionInformation",
    "calories": "560 kcal",
    "proteinContent": "48 g"
  },
  "offers": {
    "@type": "Offer",
    "price": "8.90",
    "priceCurrency": "BAM"
  }
}
```

### Additional Schemas:
- **Website Schema:** Search functionality
- **Breadcrumb Schema:** Navigation structure
- **Product properties:** Ingredients, shelf life, SKU

## 🎨 Visual Elements:

### Email Design:
- **Header gradient:** #1a5d1a → #2d8f47
- **Text colors:** White header, dark green body
- **Layout:** Centered, max-width 600px
- **Responsive:** Mobile-friendly styling

### Schema Benefits:
- **SEO:** Better search results appearance
- **Rich snippets:** Product info in Google results
- **Local SEO:** Organization contact info
- **E-commerce:** Product pricing and availability

## ⚠️ Troubleshooting:

### Email Issues:
1. **Headers not showing?**
   - Check WooCommerce email templates
   - Verify email hooks are firing
   - Test with different email clients

2. **Styling broken?**
   - Email clients strip many CSS properties
   - Use inline styles for critical elements
   - Test with email preview tools

### Schema Issues:
1. **Schema not appearing?**
   - Check page type conditions (is_front_page, is_product)
   - Verify WordPress hooks are firing
   - Check for JavaScript errors

2. **Invalid JSON?**
   - Use JSONLint to validate syntax
   - Check for PHP errors in schema generation
   - Verify all quotes and commas

3. **Duplicate schemas?**
   - Global flag should prevent duplicates
   - Check if multiple plugins add schema
   - Reset flag is called on each page load

## 🚀 Testing URLs:

- **Email preview:** `/wp-content/themes/theme-nutrilux/inc/email-preview.php`
- **Homepage schema:** `/` (view source)
- **Product schema:** `/product/any-product/` (view source)
- **Email settings:** Admin > WooCommerce > Settings > Emails
- **Rich Results Test:** https://search.google.com/test/rich-results

## 📱 Mobile Considerations:

### Email Mobile:
- Responsive email design
- Touch-friendly buttons
- Readable font sizes
- Proper viewport scaling

### Schema Mobile:
- Same schema works across all devices
- Google uses mobile-first indexing
- Schema enhances mobile search results

## ✅ Success Indicators:

1. **Email branding visible** in all WooCommerce emails
2. **Organization schema** validates without errors
3. **Product schema** includes nutritional information
4. **No duplicate schemas** on any page
5. **Valid JSON** passes all validators
6. **SEO benefits** appear in search results over time

All email branding and schema markup features are implemented and ready for production! 🎯
