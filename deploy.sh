#!/usr/bin/env bash
#
# Deploy script for the Talent (UEMF Insights) Laravel app on a Linux server.
#
#   First install (empty server):   ./deploy.sh install /path/to/talent_full_YYYY-MM-DD.sql
#   Every later update:             ./deploy.sh update
#
# Before "install":
#   1. Create an empty MySQL/MariaDB database and a user for it.
#   2. Copy .env.example to .env and fill in APP_URL and the DB_* values
#      (the script does the copy for you and stops if .env is missing).
#
set -euo pipefail
cd "$(dirname "$0")"

MODE="${1:-}"
BACKUP_DIR="${BACKUP_DIR:-../talent-backups}"

say()  { printf '\n\033[1;34m==> %s\033[0m\n' "$*"; }
fail() { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

env_get() {
    grep -E "^$1=" .env | tail -n 1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//' -e "s/^'//" -e "s/'$//"
}

load_db_env() {
    DB_HOST="$(env_get DB_HOST)";       DB_HOST="${DB_HOST:-127.0.0.1}"
    DB_PORT="$(env_get DB_PORT)";       DB_PORT="${DB_PORT:-3306}"
    DB_DATABASE="$(env_get DB_DATABASE)"
    DB_USERNAME="$(env_get DB_USERNAME)"
    export MYSQL_PWD="$(env_get DB_PASSWORD)"   # keeps the password off the command line
    [ -n "$DB_DATABASE" ] || fail "DB_DATABASE is empty in .env"
    [ -n "$DB_USERNAME" ] || fail "DB_USERNAME is empty in .env"
}

mysql_cli() { mysql -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" "$@"; }

backup_db() {
    mkdir -p "$BACKUP_DIR"
    local file="$BACKUP_DIR/talent_$(date +%Y-%m-%d_%H-%M-%S).sql"
    say "Backing up database to $file"
    mysqldump -h "$DB_HOST" -P "$DB_PORT" -u "$DB_USERNAME" \
        --single-transaction --routines --triggers --default-character-set=utf8mb4 \
        "$DB_DATABASE" > "$file"
}

build_caches() {
    say "Rebuilding caches"
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
}

fix_permissions() {
    say "Making storage/ and bootstrap/cache/ writable"
    chmod -R ug+rwX storage bootstrap/cache
}

case "$MODE" in
install)
    DUMP="${2:-}"
    [ -n "$DUMP" ] && [ -f "$DUMP" ] || fail "Usage: ./deploy.sh install /path/to/talent_full.sql"

    if [ ! -f .env ]; then
        cp .env.example .env
        fail ".env created from .env.example. Edit it (APP_URL, APP_ENV=production, APP_DEBUG=false, DB_*) and run this command again."
    fi
    load_db_env

    say "Installing PHP dependencies"
    composer install --no-dev --optimize-autoloader --no-interaction

    if [ -z "$(env_get APP_KEY)" ]; then
        say "Generating APP_KEY"
        php artisan key:generate --force
    fi

    TABLES="$(mysql_cli -N -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_DATABASE'")"
    if [ "$TABLES" != "0" ]; then
        fail "Database '$DB_DATABASE' already has $TABLES tables. 'install' only runs on an empty database (use 'update' instead)."
    fi

    say "Importing $DUMP into '$DB_DATABASE'"
    mysql_cli --default-character-set=utf8mb4 "$DB_DATABASE" < "$DUMP"

    say "Running migrations"
    php artisan migrate --force

    php artisan storage:link 2>/dev/null || true
    fix_permissions
    build_caches

    say "Create (or promote) the admin account"
    php artisan user:create-admin

    say "Install finished. Delete the dump from the server once you're happy: it contains user data."
    ;;

update)
    [ -f .env ] || fail ".env is missing. Run './deploy.sh install <dump.sql>' first."
    load_db_env
    backup_db

    say "Putting the site in maintenance mode"
    php artisan down || true
    trap 'printf "\n\033[1;31mUpdate failed. The site is still in maintenance mode. Fix the problem, then run: php artisan up\033[0m\n" >&2' ERR

    say "Pulling latest code"
    git pull --ff-only

    say "Installing PHP dependencies"
    composer install --no-dev --optimize-autoloader --no-interaction

    say "Running migrations"
    php artisan migrate --force

    fix_permissions
    build_caches

    trap - ERR
    php artisan up
    say "Update finished."
    ;;

*)
    echo "Usage:"
    echo "  ./deploy.sh install /path/to/talent_full.sql   # first time, empty database"
    echo "  ./deploy.sh update                             # every later deploy"
    exit 1
    ;;
esac
