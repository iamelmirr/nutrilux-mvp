# Nutrilux Local Development - Troubleshooting

## Česti problemi i rešenja:

### 1. Tema se ne vidi u WordPress Admin
- Proverite da li je `theme-nutrilux` folder u: `wp-content/themes/`
- Proverite da li postoji `style.css` sa WordPress header komentarom

### 2. CSS/JS se ne učitavaju
- Proverite da li postoje fajlovi:
  - `/assets/css/base.css`
  - `/assets/css/layout.css`  
  - `/assets/js/site.js`
- Očistite cache: Ctrl+F5 u browseru

### 3. Navigation ne radi
- Proverite da li je JavaScript učitan
- Otvorite Developer Tools (F12) i proverite Console za greške

### 4. WooCommerce problemi
- Instalirajte WooCommerce plugin
- Aktivirajte temu posle WooCommerce instalacije
- Idite na WooCommerce setup wizard

### 5. Font problemi
- Proverite internet konekciju (Google Fonts)
- Ili dodajte fallback fontove u CSS

## Korisni Local commands:

- Start site: Kliknite "Start" u Local app
- Stop site: Kliknite "Stop" u Local app  
- Open site: Kliknite "Open site" ili idite na .local URL
- Database: Kliknite "Database" za phpMyAdmin
- SSL: Može se enable u Site Details

## Development workflow:

1. Editujte fajlove u theme-nutrilux folderu
2. Refresh browser (Ctrl+F5)
3. Test na mobile/desktop
4. Check browser console for errors

## Sledeće faze:

- P3: Homepage layout
- P4: WooCommerce shop pages  
- P5: Single product pages
- P6: Contact forms
- P7: Product data

---

## Live dev: symlink or sync (macOS / LocalWP)

Ako želiš da svaka promjena u repo-u odmah bude vidljiva u LocalWP sajtu, koristi jednu od ove dvije opcije.

### Opcija A — Symlink (preporučeno)
LocalWP sites su obično na:
`~/Library/Application Support/Local/sites/<site-name>/app/public/wp-content/themes`

Napravi symlink iz repo teme:
```bash
chmod +x scripts/dev-link.sh
./scripts/dev-link.sh "~/Library/Application Support/Local/sites/<site-name>/app/public/wp-content/themes"
```
Zatim u WP Admin → Appearance → Themes aktiviraj “Nutrilux”.

### Opcija B — Automatski sync (ako symlink nije opcija)
Rsync-uj temu u WP themes folder, jednokratno ili u watch modu:
```bash
chmod +x scripts/dev-sync.sh
# Jednokratno
./scripts/dev-sync.sh "~/Library/Application Support/Local/sites/<site-name>/app/public/wp-content/themes/theme-nutrilux"

# Watch mod (fswatch ako je instaliran; u suprotnom polling)
./scripts/dev-sync.sh --watch "~/Library/Application Support/Local/sites/<site-name>/app/public/wp-content/themes/theme-nutrilux"
```

Napomena: BrowserSync možeš pokrenuti (vidi package.json) za auto-reload browsera dok radiš.
