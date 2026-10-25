#! /bin/bash
set -e

echo "🚀 Starting deployment..."

# Pull latest code from git
echo "📥 Pulling latest code from master branch..."
git fetch origin
git checkout master
git pull origin master

# Fetch new docker images
echo "🐳 Fetching new docker images..."
docker compose -f docker-compose.prod.yml pull

# Relaunch with new docker images
docker compose -f docker-compose.prod.yml up -d

# Sym link the storage folder
docker compose -f docker-compose.prod.yml exec -T app php artisan storage:link

# Clear stale caches (views, routes, config, permissions)
docker compose -f docker-compose.prod.yml exec -T app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec -T app php artisan permission:cache-reset

# Cache the config
docker compose -f docker-compose.prod.yml exec -T app php artisan config:cache

# Wait for MySQL to accept connections (it may be running a version upgrade)
echo "⏳ Waiting for database..."
for i in $(seq 1 60); do
    docker compose -f docker-compose.prod.yml exec -T db mysqladmin ping -h 127.0.0.1 --silent && break
    sleep 2
done

# Migrate the database
docker compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

echo "✅ Deployment complete!"
