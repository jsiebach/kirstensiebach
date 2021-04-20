#! /bin/bash

# Fetch new docker images
docker-compose -f docker-compose.prod.yml pull

# Relaunch with new docker images
docker-compose -f docker-compose.prod.yml up -d

# Migrate the database
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
