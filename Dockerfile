# ---- Build des assets (Vite 3 / Vue) ----
FROM node:18-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- Dépendances PHP ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# ---- Image finale (PHP 8.2 + Nginx) ----
FROM serversideup/php:8.2-fpm-nginx
USER root
RUN install-php-extensions gd bcmath intl
USER www-data
WORKDIR /var/www/html
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=vendor /app/vendor ./vendor
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# L'application génère des URLs /public/... (index.php à la racine en hébergement mutualisé) ;
# ici la racine web est déjà public/, ce lien permet à /public/dist/... de se résoudre.
RUN ln -s . public/public
