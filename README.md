# ROBUS

ROBUS est une application Laravel de gestion de wallets, paiements, rechargements, commissions, KYC, utilisateurs, distributeurs, sous-distributeurs et transferts Mobile Money.

Le projet integre notamment Pixel Innov / PixPay, Paystack, Laravel Passport, Pusher, Livewire et Vue/Vite.

## Stack technique

- PHP 8 / Laravel 9
- MySQL
- Laravel Passport
- Blade / Livewire / Vue 3 / Vite
- Pusher / Laravel Echo
- Pixel Innov / PixPay
- Paystack
- DomPDF
- Swagger / OpenAPI

## Installation locale

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

Si Passport n'est pas encore initialise :

```bash
php artisan passport:keys
php artisan passport:client --personal
```

## Configuration

Configurer les variables principales dans `.env` :

```dotenv
APP_URL=
APP_ENV=local
APP_DEBUG=true

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=
PAYSTACK_CALLBACK_URL=
PAYSTACK_WEBHOOK_URL=

PIXEL_INNOV_API_KEY=
PIXEL_INNOV_API_KEY_XAF=
PIXEL_INNOV_SANDBOX=false
PIXEL_INNOV_PUBLIC_URL=
PIXEL_INNOV_WAVE_BUSINESS_NAME_ID=
```

Important : ne jamais committer de secrets reels dans `.env.example`. Si des secrets ont ete exposes, les regenerer.

## Commandes utiles

```bash
php artisan route:list
php artisan migrate
php artisan migrate:status
php artisan config:clear
php artisan config:cache
php artisan test
npm run build
```

## Scheduler

Installer le cron Laravel sur le serveur :

```bash
* * * * * cd /chemin/vers/robus && php artisan schedule:run >> /dev/null 2>&1
```

Taches planifiees importantes :

- `app:close-status`
- `CheckAppCompanyStatus`
- `pixel:sync-pending`

## API et routes principales

API principale :

- prefixe : `/api/v1`
- fichier : `routes/api.php`

Administration et callbacks publics :

- fichier : `routes/web.php`

Callbacks Pixel Innov :

- `POST /payment_pixelInnov/callback`
- `POST /payment_pixelInnov/mobile-transfer/callback`
- `GET /payment_pixelInnov/success`
- `GET /payment_pixelInnov/error`

## Integration Pixel Innov

Fichiers principaux :

- `app/Http/Controllers/PixelPaymentController.php`
- `app/Http/Services/PixelInnovService.php`
- `app/Models/PixelInnoveService.php`
- `app/Models/PixelMobileZoneTransfer.php`
- `app/Console/Commands/SyncPendingPixelTransactions.php`
- `config/services.php`

Endpoints principaux :

- `GET /api/v1/pixelInnov/services`
- `POST /api/v1/pixelInnov/calculate`
- `POST /api/v1/pixelInnov/initialize`
- `POST /api/v1/pixelInnov/status`
- `POST /api/v1/pixelInnov/mobile-transfer/initialize`
- `POST /api/v1/pixelInnov/mobile-transfer/status`

## Deploiement

Procedure type :

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

En production, verifier :

- `APP_ENV=production`
- `APP_DEBUG=false`
- cron Laravel actif
- callbacks Pixel Innov accessibles publiquement
- sauvegardes base de donnees actives
- permissions correctes sur `storage/` et `bootstrap/cache/`

## Documentation de passation

Documents de passation :

- [domaine.md](domaine.md)
- [docs/PASSATION_TECHNIQUE.md](docs/PASSATION_TECHNIQUE.md)

Ces documents contiennent les details d'exploitation, les incidents courants, les tables sensibles, les integrations de paiement et la checklist de reprise.

