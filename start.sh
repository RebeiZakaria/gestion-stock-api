#!/bin/bash

# Appliquer les permissions
chmod -R 775 storage bootstrap/cache

# Générer la clé si absente
php artisan key:generate --force || true

# Cacher la config
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Migrer la BDD (si connectée)
php artisan migrate --force || true

# Démarrer Apache (Render)
apache2-foreground
