#!/bin/bash

# Assure-toi que .env est bien présent et les permissions sont correctes
chmod -R 775 storage bootstrap/cache

# Laravel config
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# Génère la clé de l'application
php artisan key:generate --force

# (optionnel) attend quelques secondes pour être sûr que DB est up
sleep 10

# Lancer les migrations
php artisan migrate --force

# Start Apache
apache2-foreground
