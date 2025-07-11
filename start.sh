#!/bin/bash

# Exécute les migrations s’il y en a
php artisan migrate --force

# Démarre Apache (important pour Render)
apache2-foreground
