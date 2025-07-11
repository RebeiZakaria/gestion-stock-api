#!/bin/bash

# Exécute les migrations s’il y en a
php artisan migrate --force

php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan migrate --force
php artisan key:generate --force
chmod -R 775 storage bootstrap/cache


# Démarre Apache (important pour Render)
apache2-foreground
