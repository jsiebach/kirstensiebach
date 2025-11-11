# Production Deployment Changes

## Problem
The production environment was mounting the host filesystem over the Docker container's `/var/www` directory, which meant:
- Files in the Docker image were completely replaced by files on the host
- Required running `git pull` on the production server to update code
- Filament CSS/JS assets from the Docker image were not accessible

## Solution
Updated Docker configuration to run completely from the Docker image without depending on host filesystem:

### Changes Made

1. **Dockerfile** - Now includes:
   - Composer and Node.js installation
   - PHP and Node dependency installation
   - Frontend asset building
   - Laravel optimization (config/route/view cache)

2. **docker-compose.prod.yml** - Updated:
   - Removed `- ./:/var/www` volume mount from webserver that was overriding image files
   - Added `volumes_from: app:ro` to webserver - nginx now accesses app container's filesystem directly (read-only)
   - Only `.env` and config files are mounted from host, everything else comes from the Docker image

## Deployment Instructions

### First Time Setup

1. **Build and push the new Docker image:**
   ```bash
   docker build -t jsiebach/kirstensiebach:latest .
   docker push jsiebach/kirstensiebach:latest
   ```

2. **On production server, stop existing containers:**
   ```bash
   docker-compose -f docker-compose.prod.yml down
   ```

3. **Pull the new image:**
   ```bash
   docker-compose -f docker-compose.prod.yml pull
   ```

4. **Start containers:**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

5. **Run Laravel migrations if needed:**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
   ```

6. **Create storage symlink:**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan storage:link
   ```

### Future Deployments

For all future deployments, you no longer need to run `git pull` on the production server. Simply:

1. **Build and push new image:**
   ```bash
   docker build -t jsiebach/kirstensiebach:latest .
   docker push jsiebach/kirstensiebach:latest
   ```

2. **On production, pull and restart:**
   ```bash
   docker-compose -f docker-compose.prod.yml pull
   docker-compose -f docker-compose.prod.yml up -d
   ```

That's it! The new code and assets are now served entirely from the Docker image.

## Important Notes

- The `.env` file is still mounted from the host (for environment-specific configuration)
- Storage directory uses a named volume for persistence
- Nginx accesses the app container's filesystem directly using `volumes_from` (read-only)
- All static assets (CSS, JS, images) are served from the Docker image

## Rollback

If you need to rollback to the previous version:

1. Use a specific image tag instead of `latest`
2. Or rebuild from a specific git commit:
   ```bash
   git checkout <previous-commit>
   docker build -t jsiebach/kirstensiebach:latest .
   docker push jsiebach/kirstensiebach:latest
   docker-compose -f docker-compose.prod.yml pull
   docker-compose -f docker-compose.prod.yml up -d
   ```
