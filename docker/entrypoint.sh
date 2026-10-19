#!/usr/bin/env bash
set -e

# Logs applicatifs dans un fichier (lisible via storage/logs/laravel.log).
export LOG_CHANNEL=single

# Render fournit le port via la variable PORT (defaut 80 en local).
PORT="${PORT:-80}"

# Apache doit ecouter sur le port demande par la plateforme.
sed -ri "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/" /etc/apache2/sites-available/000-default.conf
grep -q "^ServerName" /etc/apache2/apache2.conf || echo "ServerName localhost" >> /etc/apache2/apache2.conf

cd /var/www/html

# Nettoyage des caches compiles (au cas ou l'image en contiendrait).
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Base de donnees (Neon / Postgres).
# Si SAMUEL_FRESH_DB=true : reinitialisation complete du schema (migrate:fresh).
if [ "${SAMUEL_FRESH_DB:-}" = "true" ]; then
    echo "SAMUEL_FRESH_DB=true : reinitialisation complete de la base (migrate:fresh --force)."
    php artisan migrate:fresh --force || true
else
    # Neon peut mettre quelques secondes a se reveiller : on retente.
    migrated=0
    for i in 1 2 3 4 5; do
        if php artisan migrate --force; then
            migrated=1
            break
        fi
        echo "Migration echouee (tentative $i/5), nouvelle tentative dans 5s..."
        sleep 5
    done
    if [ "$migrated" -ne 1 ]; then
        echo "AVERTISSEMENT : migrations non appliquees apres 5 tentatives. Demarrage du serveur malgre tout." >&2
    fi
fi

# Super administrateur initial (optionnel, via variables d'environnement).
if [ -n "${SAMUEL_ADMIN_EMAIL:-}" ] && [ -n "${SAMUEL_ADMIN_PASSWORD:-}" ]; then
    php artisan db:seed --class="Database\\Seeders\\SuperAdminSeeder" --force || true
fi

# Caches de production.
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Demarrage d'Apache (processus principal).
exec apache2-foreground
