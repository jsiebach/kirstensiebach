#! /bin/bash

# Fetch new docker images
docker-compose -f docker-compose.prod.yml pull

# Relaunch with new docker images
docker-compose -f docker-compose.prod.yml up -d

# Sym link the storage folder
docker-compose -f docker-compose.prod.yml exec -T app php artisan storage:link

# Cache the config
docker-compose -f docker-compose.prod.yml exec -T app php artisan config:cache

# Migrate the database
docker-compose -f docker-compose.prod.yml exec -T app php artisan migrate --force
