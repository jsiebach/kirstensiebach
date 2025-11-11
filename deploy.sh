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
docker-compose -f docker-compose.prod.yml pull

# Relaunch with new docker images
docker-compose -f docker-compose.prod.yml up -d

# Sym link the storage folder
docker-compose -f docker-compose.prod.yml exec -T app php artisan storage:link

# Cache the config
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache

# Migrate the database
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force

echo "✅ Deployment complete!"
