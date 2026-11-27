#!/usr/bin/env bash
# Creates a private SQL backup without PHP process functions (disabled on the shared host).
# Usage: scripts/backup-database.sh            (keeps the 14 most recent backups)
set -euo pipefail
cd "$(dirname "$0")/.."
umask 077

PHP="${PHP_BIN:-php}"
DIR=storage/app/private/backups
mkdir -p "$DIR"
CNF="$(mktemp)"
trap 'rm -f "$CNF" "${OUT:-}.partial"' EXIT

DB="$("$PHP" artisan database:client-config "$CNF")"
OUT="$DIR/database-$(date +%Y-%m-%d-%H%M%S).sql"

mysqldump --defaults-extra-file="$CNF" --single-transaction --skip-lock-tables \
  --default-character-set=utf8mb4 "$DB" > "$OUT.partial"
test -s "$OUT.partial"
mv "$OUT.partial" "$OUT"
chmod 600 "$OUT"

ls -1t "$DIR"/database-*.sql 2>/dev/null | tail -n +15 | xargs -r rm -f
echo "Database backup created: $OUT"
