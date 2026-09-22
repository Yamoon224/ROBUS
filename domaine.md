# Passation technique - ROBUS

Date : 2026-09-21

## 1. Objectif du document

Ce document sert de support de passation technique pour reprendre, maintenir et deployer l'application ROBUS. Il resume l'architecture, les dependances, les configurations importantes, les commandes d'exploitation, les integrations de paiement et les points de vigilance.

La documentation plus detaillee est disponible dans `docs/PASSATION_TECHNIQUE.md`.

## 2. Presentation du projet

ROBUS est une application Laravel permettant de gerer :

- les wallets utilisateurs ;
- les paiements et rechargements ;
- les transferts Mobile Money ;
- les commissions administrateur, sous-distributeur et compagnie ;
- les utilisateurs, distributeurs, sous-distributeurs, compagnies et com zones ;
- le KYC et les documents clients ;
- les historiques et reportings ;
- les integrations Pixel Innov / PixPay et Paystack.

## 3. Stack technique

- Backend : PHP 8 / Laravel 9
- Base de donnees : MySQL
- Authentification API : Laravel Passport
- Frontend : Blade, Livewire, Vue 3, Vite, Bootstrap
- Notifications temps reel : Pusher / Laravel Echo
- Paiements : Pixel Innov / PixPay, Paystack
- PDF : DomPDF
- Documentation API : Swagger / OpenAPI

Fichiers importants :

- `composer.json`
- `package.json`
- `routes/api.php`
- `routes/web.php`
- `config/services.php`
- `app/Console/Kernel.php`

## 4. Installation locale

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
npm run dev
php artisan serve
```

Si Passport n'est pas encore configure :

```bash
php artisan passport:keys
php artisan passport:client --personal
```

## 5. Configuration `.env`

Variables principales a renseigner :

```dotenv
APP_NAME=ROBUS
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="ROBUS"

BROADCAST_DRIVER=pusher
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

COM_ZONE_CRON_SECRET=
```

Point de vigilance : ne jamais mettre de secrets reels dans `.env.example`. Les cles exposees doivent etre regenerees.

## 6. Deploiement

Procedure standard :

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

Apres deploiement, redemarrer PHP-FPM, le serveur web ou les workers si necessaire.

## 7. Scheduler et taches automatiques

Le scheduler est configure dans `app/Console/Kernel.php`.

Taches importantes :

- `app:close-status` : toutes les minutes ;
- `CheckAppCompanyStatus` : toutes les minutes ;
- `pixel:sync-pending` : toutes les 5 minutes.

Cron serveur a installer :

```bash
* * * * * cd /chemin/vers/robus && php artisan schedule:run >> /dev/null 2>&1
```

## 8. Routes principales

API principale :

- prefixe : `/api/v1`
- fichier : `routes/api.php`

Routes web :

- fichier : `routes/web.php`
- contient l'administration, les callbacks paiement et certaines routes utilitaires.

Callbacks Pixel Innov publics :

- `POST /payment_pixelInnov/callback`
- `POST /payment_pixelInnov/mobile-transfer/callback`
- `GET /payment_pixelInnov/success`
- `GET /payment_pixelInnov/error`

Callbacks Paystack :

- `POST /api/v1/paystack/webhook`
- `GET /api/v1/paystack/verify/{reference}`

## 9. Integration Pixel Innov / PixPay

Fichiers principaux :

- `app/Http/Controllers/PixelPaymentController.php`
- `app/Http/Services/PixelInnovService.php`
- `app/Models/PixelInnoveService.php`
- `app/Models/PixelMobileZoneTransfer.php`
- `app/Console/Commands/SyncPendingPixelTransactions.php`

Endpoints API :

- `GET /api/v1/pixelInnov/services`
- `GET /api/v1/pixelInnov/zone-services`
- `POST /api/v1/pixelInnov/calculate`
- `POST /api/v1/pixelInnov/initialize`
- `POST /api/v1/pixelInnov/status`
- `GET /api/v1/pixelInnov/mobile-transfer/services`
- `POST /api/v1/pixelInnov/mobile-transfer/calculate`
- `POST /api/v1/pixelInnov/mobile-transfer/initialize`
- `POST /api/v1/pixelInnov/mobile-transfer/status`

Fonctionnement :

1. Le front recupere les services Pixel disponibles.
2. Le front calcule les frais.
3. Le paiement ou transfert est initialise.
4. Une ligne locale est creee en `PENDING`.
5. Pixel Innov renvoie un IPN sur le callback public.
6. Le hash IPN est verifie avec `SHA256(api_key + transaction_id)`.
7. La transaction passe en `SUCCESS` ou `ERROR`.
8. Les wallets, commissions et ecritures comptables sont mis a jour.

Pour les services XAF, le code utilise `PIXEL_INNOV_API_KEY_XAF`. Pour les autres zones, il utilise `PIXEL_INNOV_API_KEY`.

## 10. Tables importantes

- `sid_users` : utilisateurs, admins, compagnies, sous-distributeurs.
- `wallets` : soldes utilisateurs.
- `wallet_reloads` : rechargements et transactions Pixel simples.
- `payment_reloads` : transactions finalisees.
- `ledger_entries` : journal comptable.
- `wallet_admins` : wallet administrateur.
- `wallet_commissions` : wallet commission.
- `pixel_innove_services` : catalogue des services Pixel Innov.
- `pixel_mobile_zone_transfers` : transferts Mobile Zone.
- `providers`, `provider_types`, `countries`, `currencies` : referentiels.

## 11. Verification et diagnostic

Commandes utiles :

```bash
php artisan route:list
php artisan config:clear
php artisan config:cache
php artisan migrate:status
php artisan test
npm run build
php artisan pixel:sync-pending
```

Logs :

```bash
storage/logs/laravel.log
```

Recherches utiles :

```bash
grep -n "Pixel Innov" storage/logs/laravel.log
grep -n "Mobile Zone Transfer" storage/logs/laravel.log
```

## 12. Incidents courants

### Transaction Pixel en attente

1. Chercher la transaction dans `wallet_reloads` avec `reference`.
2. Appeler `/api/v1/pixelInnov/status`.
3. Lancer `php artisan pixel:sync-pending`.
4. Verifier les logs Pixel Innov.

### Mobile Zone bloque

1. Chercher dans `pixel_mobile_zone_transfers`.
2. Verifier `sender_status`, `recipient_status`, `refund_status`.
3. Si le Cash Out est OK mais le Cash In absent, appeler `/api/v1/pixelInnov/mobile-transfer/status`.
4. Ne pas relancer manuellement sans verifier l'etat cote Pixel Innov.

### Probleme de solde

1. Identifier la transaction source.
2. Comparer `wallets`, `wallet_reloads`, `payment_reloads`, `ledger_entries`.
3. Verifier si un IPN a ete recu plusieurs fois.
4. Toute correction SQL doit etre sauvegardee et documentee.

## 13. Securite

Actions prioritaires :

- nettoyer `.env.example` ;
- regenerer les secrets deja exposes ;
- verifier que `.env` n'est pas suivi par Git ;
- mettre `APP_DEBUG=false` en production ;
- proteger les routes de maintenance dans `routes/web.php` ;
- verifier les permissions de `storage/` et `bootstrap/cache/` ;
- limiter l'acces a la base de donnees ;
- verifier les callbacks publics Pixel Innov et Paystack.

## 14. Checklist de passation

- Acces serveur et SSH.
- Acces depot Git.
- Acces base de donnees et sauvegardes.
- Acces domaine, DNS et SSL.
- Acces Pixel Innov / PixPay.
- Acces Paystack.
- Acces SMTP.
- Acces Pusher.
- Procedure de deploiement validee.
- Cron Laravel installe.
- Documentation des comptes administrateurs.
- Liste des contacts support.
- Dernier backup teste ou restaure sur environnement de test.

