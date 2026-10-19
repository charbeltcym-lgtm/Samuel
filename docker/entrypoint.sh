#!/usr/bin/env bash
set -e

# Logs applicatifs dans un fichier (lisible via storage/logs/laravel.log).
export LOG_CHANNEL=single

# Neon : le host "pooler" casse les migrations (DDL). On force le host direct.
# (POSIX sh : pas de substitution bash, on utilise sed.)
if [ -n "${DB_URL:-}" ]; then DB_URL=$(printf '%s' "$DB_URL" | sed 's/-pooler//g'); export DB_URL; fi
if [ -n "${DB_HOST:-}" ]; then DB_HOST=$(printf '%s' "$DB_HOST" | sed 's/-pooler//g'); export DB_HOST; fi

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

# Base de donnees (Neon / Postgres) : auto-reparation du schema.
# Si SAMUEL_FRESH_DB=true : reinitialisation complete forcee.
if [ "${SAMUEL_FRESH_DB:-}" = "true" ]; then
    echo "SAMUEL_FRESH_DB=true : reinitialisation complete de la base."
    php artisan migrate:fresh --force || true
    php artisan db:seed --class="Database\\Seeders\\SuperAdminSeeder" --force || true
else
    # Neon peut mettre quelques secondes a se reveiller : on retente l'auto-reparation.
    for i in 1 2 3 4 5; do
        if php artisan samuel:ensure-schema; then
            break
        fi
        echo "Preparation de la base echouee (tentative $i/5), nouvelle tentative dans 5s..."
        sleep 5
    done
fi

# Caches de production.
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Lien public/storage -> storage/app/public (fichiers uploades : photos de profil).
php artisan storage:link || true

# Demarrage d'Apache (processus principal).
exec apache2-foreground
