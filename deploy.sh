#! /bin/bash
export COMPOSE_INTERACTIVE_NO_CLI=1

# Fetch new docker images
docker-compose -f docker-compose.prod.yml pull

# Relaunch with new docker images
docker-compose -f docker-compose.prod.yml up -d

# Cache the config
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache

# Migrate the database
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
