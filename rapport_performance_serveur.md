# Rapport performance serveur ROBUS

## Constat

Le serveur n'est pas forcement en panne, mais il atteint ses limites pendant les pics d'activite.

Sur les releves `htop` / `top`, on observe :

- Serveur actuel : environ 4 CPU et 12 Go RAM
- CPU : les 4 coeurs peuvent monter autour de 90% pendant les pics
- Load average observe : jusqu'a 4.86 sur une machine 4 CPU
- RAM utilisee : environ 7.5 Go a 8.2 Go sur 11.7 Go
- Swap utilisee : entre 728 Mo et 3.3 Go sur 4 Go selon les moments
- Disque : pas plein, environ 55% d'utilisation
- MariaDB/MySQL est local sur le serveur
- MariaDB consomme environ 3 Go de RAM
- Apache et PHP-FPM ne semblent pas etre les plus gros consommateurs au moment des controles

## Interpretation

Le CPU et le disque ne montrent pas une saturation permanente. Par contre, l'utilisation de la swap indique une pression memoire reelle.

Quand Linux utilise la swap, certaines operations deviennent beaucoup plus lentes, car la swap est sur disque et non en RAM. Cela peut expliquer pourquoi le client constate des lenteurs temporaires, puis tout redevient normal apres le pic.

Le probleme probable est donc un melange de :

- ressources serveur limitees pendant les pics
- MySQL/MariaDB local qui partage la RAM avec Laravel
- requetes SQL ou pages lourdes
- jobs Laravel
- taches planifiees
- generation QR/PDF/images

Les transactions peuvent rester rapides, car elles ne sollicitent pas forcement les memes traitements que les historiques, dashboards, notifications, exports ou taches de fond.

## Recommandation serveur

Configuration actuelle estimee :

```text
4 CPU / 12 Go RAM
```

Configuration recommandee minimum :

```text
8 CPU / 16 Go RAM
```

Configuration ideale si MySQL reste sur le meme serveur :

```text
8 CPU / 32 Go RAM
```

L'objectif est d'eviter que Laravel, Apache, PHP-FPM, MariaDB et les queues se partagent une RAM trop limitee pendant les pics.

## Optimisations Laravel recommandees

La configuration Laravel doit rester en production :


Il est recommande de passer cache, sessions et queues sur Redis :

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Il faut aussi traiter les queues avec Supervisor, au lieu de lancer les jobs via des routes HTTP.

Exemple de worker Supervisor :

```ini
[program:robus-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/projet/artisan queue:work redis --sleep=3 --tries=3 --timeout=120
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/chemin/vers/projet/storage/logs/worker.log
stopwaitsecs=3600
```

## Analyse page login

La vue `resources/views/auth/login.blade.php` est legere. Elle charge seulement le composant Livewire `login`.

La vue `resources/views/livewire/login.blade.php` contient principalement le formulaire de connexion et quelques liens. Elle ne semble pas etre la cause principale des pics CPU/RAM.

Le traitement backend est dans `app/Http/Livewire/Login.php`. A la connexion, il fait :

- verification du statut de l'application
- recherche utilisateur par email
- tentative d'authentification
- mise a jour de `last_login` et `last_logout`

Ce traitement peut etre optimise, mais il n'explique pas a lui seul une grosse saturation serveur, sauf en cas de tres grand nombre de connexions simultanees.

Optimisations recommandees pour le login :

- verifier qu'il existe un index sur `sid_users.email`
- verifier qu'il existe un index sur `sid_users.status`
- mettre le statut application en cache Redis
- eviter les requetes repetitives sur le statut de l'application a chaque tentative de login

Indexes utiles :

```sql
CREATE INDEX idx_sid_users_email ON sid_users (email);
CREATE INDEX idx_sid_users_status ON sid_users (status);
```

## Analyse page accueil

La vue `resources/views/home.blade.php` charge le composant Livewire `home`.

La vraie charge vient donc de `app/Http/Livewire/Home.php`, pas directement du fichier Blade.

Ce composant execute beaucoup de calculs dashboard :

- compteurs par statut
- totaux journaliers
- totaux mensuels
- totaux par provider
- totaux par type de transaction
- totaux utilisateurs
- totaux commissions

Dans `app/Http/Livewire/Home.php`, le dashboard appelle plusieurs dizaines de methodes de `HomeService`.

Meme avec un cache de 5 minutes, quand le cache expire ou quand plusieurs utilisateurs ouvrent l'accueil en meme temps, Laravel peut envoyer beaucoup de requetes SQL a MariaDB d'un coup.

Le risque principal de la page accueil :

- beaucoup de `COUNT`
- beaucoup de `SUM`
- jointures avec `wallets`
- filtres par date
- filtres par `status`
- filtres par `provider_id`
- filtres par `wallet_id`

Cela peut expliquer les pics CPU observes sur le serveur.

## Optimisations prioritaires pour la page accueil

1. Augmenter le cache dashboard

Actuellement, le cache est de 5 minutes. Pour reduire la charge, passer a 10 ou 15 minutes si le metier l'accepte.

Exemple :

```php
Cache::remember($cacheKey, now()->addMinutes(15), function () {
    // calcul dashboard
});
```

2. Utiliser Redis pour ce cache

Si `CACHE_DRIVER=file`, le cache utilise le disque. Pour un dashboard tres consulte, Redis est preferable :

```env
CACHE_DRIVER=redis
```

3. Eviter les requetes non selectives

Dans `HomeService`, certaines conditions comme :

```php
where('wallets.country_id', '!=', $countryId)
orWhere('wallets.country_id', '=', $countryId)
```

reviennent a prendre presque toutes les lignes. Cela oblige MariaDB a travailler sur beaucoup de donnees.

4. Ajouter des index sur les tables de transactions

Les tables `wallet_*` doivent avoir des index adaptes aux filtres du dashboard.

Indexes typiques :

```sql
CREATE INDEX idx_wallet_transactions_status_created_at ON wallet_transactions (status, created_at);
CREATE INDEX idx_wallet_transactions_wallet_status_created ON wallet_transactions (wallet_id, status, created_at);
CREATE INDEX idx_wallet_transactions_provider_status_created ON wallet_transactions (provider_id, status, created_at);
```

Meme logique pour :

```text
wallet_payments
wallet_reloads
wallet_phone_credits
wallet_inters
wallet_banks
wallet_deposits
wallet_xbets
wallet_djamos
wallet_pushes
wallet_tresort_money
wallet_versements
```

5. Precalculer les statistiques

La meilleure optimisation pour l'accueil est de ne pas recalculer tous les totaux a chaque affichage.

Solution recommandee :

- creer une table de statistiques journalieres
- mettre a jour cette table via cron/job
- afficher les valeurs deja calculees sur la page accueil

Cela reduit fortement les requetes `SUM` et `COUNT` sur les grosses tables.

## Conclusion sur login et accueil

La page login ne semble pas etre la cause principale.

La page accueil est un candidat serieux pour les pics serveur, car elle lance beaucoup de calculs SQL via Livewire/HomeService.

Il faut donc :

- garder login leger avec indexes sur `sid_users`
- passer le cache de l'accueil sur Redis
- augmenter la duree du cache dashboard
- ajouter les bons indexes MySQL sur les tables de transactions
- envisager une table de statistiques precalculees pour le dashboard

## Point important sur l'analyse

L'utilisateur actuel n'est pas root :

```text
uid245128
```

Avec cet acces, les processus systeme ne sont pas visibles correctement. Les commandes `ps` et `htop` ne montrent que les processus de cet utilisateur, pas tous les services comme MySQL, Apache, PHP-FPM ou Redis.

Pour identifier exactement le service responsable pendant une lenteur, il faut un acces root ou un monitoring admin.

Commandes a lancer avec root pendant une lenteur :

```bash



ps aux --sort=-%cpu | head -30
free -h
top -b -n 1 | head -40
mysqladmin processlist -u root -p
systemctl status mariadb
systemctl status php8.1-fpm
systemctl status apache2
```

## Conclusion

Le serveur n'est pas forcement defectueux, mais les mesures montrent qu'il est limite pour la charge actuelle.

La swap utilisee, les pics CPU et MariaDB local indiquent que les lenteurs peuvent venir de pics memoire ou de traitements applicatifs/base de donnees.

Je recommande donc :

- augmenter le serveur a 8 CPU / 16 Go RAM minimum
- viser 8 CPU / 32 Go RAM si MySQL reste local
- installer Redis
- passer cache/session/queue sur Redis
- configurer Supervisor pour les queues Laravel
- analyser MySQL pendant les lenteurs avec un acces root
