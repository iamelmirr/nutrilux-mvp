# 🚀 Nutrilux MVP - Local Sync Guide

## 📋 Quick Commands

### **Jednom sync**
```bash
cd /Users/elmirbesirovic/Desktop/projects/nutrilux-mvp
./sync-local.sh
```

### **Kontinuirani sync (potreban fswatch)**
```bash
./watch-sync.sh
```

### **Instaliranje fswatch (ako nije instaliran)**
```bash
brew install fswatch
```

## 🎯 LocalWP putanje

- **Source**: `/Users/elmirbesirovic/Desktop/projects/nutrilux-mvp/theme-nutrilux`
- **Target**: `/Users/elmirbesirovic/Local Sites/nutrilux/app/public/wp-content/themes/theme-nutrilux`

## ✅ Što je sync-ovano danas

- [x] Nova single product template struktura
- [x] Sticky sidebar CSS
- [x] WordPress admin meta fields
- [x] Ažurirani JavaScript za cart
- [x] Fallback demo content

## 🔧 Single Product Features

- **Sticky left sidebar** (50% širine) sa slikom, nazivom, cijenom, cart formom
- **Right content area** (50% širine) sa opisima i nutritivnim vrijednostima  
- **Mobile responsive** - vertikalno slaganje na malim ekranima
- **WordPress admin meta fields** za lako uređivanje sadržaja
- **AJAX cart integration** - dodavanje u korpu bez refresh-a

## 🌐 LocalWP URL
http://nutrilux.local

Sada možeš ići u LocalWP i testirati single product stranice! 🎉