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

# Pick up any nginx config changes from the pull (the config is bind-mounted)
docker compose -f docker-compose.prod.yml exec -T webserver nginx -s reload

# Smoke test: the homepage and the stylesheet it references must load through nginx
echo "🩺 Checking the site responds..."
for i in $(seq 1 15); do
    html=$(curl -fsS --resolve kirstensiebach.com:443:127.0.0.1 https://kirstensiebach.com/ 2>/dev/null) && break
    sleep 2
done
if [ -z "$html" ]; then
    echo "❌ Homepage did not respond after deploy"
    exit 1
fi
css=$(echo "$html" | grep -oE '/build/assets/app-[A-Za-z0-9_-]+\.css' | head -1)
if [ -z "$css" ] || ! curl -fsS -o /dev/null --resolve kirstensiebach.com:443:127.0.0.1 "https://kirstensiebach.com$css"; then
    echo "❌ Homepage stylesheet ($css) did not load"
    exit 1
fi

echo "✅ Deployment complete!"
