#! /bin/bash
set -euo pipefail

cd "$(dirname "$0")"

# Nightly on-server backups of the database and uploaded files, kept for 14 days.
# This guards against bad migrations and accidental deletes, not loss of the server.
BACKUP_DIR=/var/backups/kirstensiebach
STAMP=$(date +%Y%m%d-%H%M%S)
mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

docker compose -f docker-compose.prod.yml exec -T db sh -c \
    'exec mysqldump -uroot -p"$MYSQL_ROOT_PASSWORD" --all-databases --single-transaction --routines --events --triggers' \
    2>/dev/null | gzip > "$BACKUP_DIR/mysql-$STAMP.sql.gz"

docker run --rm -v kirstensiebach_storage:/storage:ro -v "$BACKUP_DIR":/backup alpine \
    tar czf "/backup/storage-$STAMP.tgz" -C /storage .

# A dump this small means mysqldump failed; keep older backups and fail loudly
if [ "$(stat -c %s "$BACKUP_DIR/mysql-$STAMP.sql.gz")" -lt 10000 ]; then
    echo "Database dump looks empty" >&2
    exit 1
fi

find "$BACKUP_DIR" -name 'mysql-*.sql.gz' -mtime +14 -delete
find "$BACKUP_DIR" -name 'storage-*.tgz' -mtime +14 -delete
