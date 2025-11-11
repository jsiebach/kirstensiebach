# Docker Build Optimization

This document explains the Docker build optimizations implemented in this project.

## Problem Statement

Previously, every push to `master` triggered a full Docker image build, even when changes didn't affect the Docker image (e.g., documentation updates, workflow changes, test updates).

**Costs:**
- 🐢 ~2-3 minutes per build
- 💰 GitHub Actions minutes consumed unnecessarily
- 🔄 Pointless deployments pulling same image

## Solution Overview

We implemented a **3-layer optimization strategy**:

1. **Conditional Builds** - Skip Docker build if files don't affect the image
2. **Layer Caching** - Improved Dockerfile for better layer reuse
3. **Registry Caching** - Cache Docker layers in Docker Hub

## Optimization Details

### 1. Conditional Docker Builds ⚡

The workflow now checks which files changed and only builds if necessary.

**Triggers Docker build:**
- ✅ `Dockerfile` changes
- ✅ PHP source code (`app/`, `bootstrap/`, `config/`, `database/`, `routes/`)
- ✅ Dependencies (`composer.json`, `composer.lock`)
- ✅ Frontend assets (`resources/`, `package.json`, `vite.config.js`)
- ✅ Public files

**Skips Docker build:**
- ⚡ Documentation files (`*.md`, `docs/`)
- ⚡ GitHub workflows (`.github/`)
- ⚡ Tests only (if they don't affect runtime)
- ⚡ Nginx config files (`nginx/`)
- ⚡ Environment examples (`.env.example`)

**Example:** Updating `README.md` will skip Docker build and deploy using the existing image.

### 2. Optimized Dockerfile Layers 🏗️

**Before:**
```dockerfile
COPY composer.lock /var/www/    # ❌ Breaks cache for every code change
COPY . /var/www                 # ❌ Invalidates all subsequent layers
```

**After:**
```dockerfile
# Install system dependencies (cached - rarely changes)
RUN apt-get update && apt-get install -y ...

# Install PHP extensions (cached - rarely changes)
RUN docker-php-ext-install ...

# Add user (cached - never changes)
RUN groupadd -g 1000 www ...

# Copy application (last - changes often)
COPY --chown=www:www . /var/www
```

**Benefits:**
- System dependencies layer cached (~1 min saved)
- PHP extensions layer cached (~30 sec saved)
- Only application copy layer rebuilds on code changes

### 3. Docker BuildKit Registry Caching 🚀

```yaml
cache-from: type=registry,ref=jsiebach/kirstensiebach:buildcache
cache-to: type=registry,ref=jsiebach/kirstensiebach:buildcache,mode=max
```

**How it works:**
1. First build creates cache in Docker Hub
2. Subsequent builds pull cache layers from Docker Hub
3. Only changed layers are rebuilt
4. Cache is pushed back to Docker Hub

**Benefits:**
- ⚡ Faster builds (reuse layers across builds)
- 💾 Persistent cache (survives GitHub Actions runner cleanup)
- 🌍 Shared cache (same cache for all builds)

## Build Time Comparison

### Scenario A: No Changes to Docker Files
**Before:** ~2-3 min (full build)
**After:** ~5 sec (skipped)
**Savings:** ~2+ minutes

### Scenario B: Only Application Code Changed
**Before:** ~2-3 min (rebuild everything)
**After:** ~30-60 sec (reuse system/PHP layers)
**Savings:** ~1-2 minutes

### Scenario C: Dependencies Changed
**Before:** ~2-3 min
**After:** ~2-3 min (must rebuild)
**Savings:** 0 min (cache helps slightly)

## Deployment Behavior

The deployment job is smart and always runs:

**If Docker built:** Deploys new image
**If Docker skipped:** Deploys existing image (still runs deploy script)

This ensures:
- Configuration changes deploy (nginx, .env, etc.)
- Database migrations run
- Cache clears
- Even if Docker image unchanged

## Files Changed

### Dockerfile
- Reorganized layers for better caching
- Removed unnecessary intermediate layers
- Added comments explaining caching strategy

### .github/workflows/master-push.yml
- Added `check-docker-changes` job to detect relevant file changes
- Made `build-and-push-docker-image` conditional
- Added Docker layer caching with registry backend
- Updated `deploy` job to run regardless of build status

## How to Test

### Test 1: Update Documentation (Should Skip Build)
```bash
echo "test" >> README.md
git add README.md
git commit -m "Update readme"
git push origin master
```

Expected: ⚡ "Skipping Docker build - no relevant changes detected"

### Test 2: Update Application Code (Should Build)
```bash
echo "// test" >> app/Http/Controllers/HomeController.php
git add app/
git commit -m "Update controller"
git push origin master
```

Expected: 🔨 "Docker build needed - application code changed"

### Test 3: Update Nginx Config (Should Skip Build, But Deploy)
```bash
echo "# test" >> nginx/prod/conf.d/kirstensiebach.conf
git add nginx/
git commit -m "Update nginx config"
git push origin master
```

Expected: ⚡ Skips build, but runs deployment (nginx config updated on server)

## Monitoring

Check GitHub Actions logs for:

```
🔨 Docker build needed - application code changed
```

or

```
⚡ Skipping Docker build - no relevant changes detected
```

## Cost Savings Estimate

Assuming:
- 10 pushes to master per week
- 40% are non-Docker changes (docs, workflows, configs)
- Docker build takes 2 minutes

**Savings per week:** 10 × 40% × 2 min = **8 minutes**
**Savings per month:** ~**32 minutes**
**Savings per year:** ~**6.4 hours** of GitHub Actions time

Plus:
- Faster feedback loop for developers
- Less waiting for deployments
- More efficient use of Docker Hub bandwidth

## Troubleshooting

### "Docker build skipped but I need it to run"
Manually trigger by updating the Dockerfile:
```bash
# Add a comment to force rebuild
echo "# $(date)" >> Dockerfile
```

### "Cache not working"
Check Docker Hub for `jsiebach/kirstensiebach:buildcache` tag. If missing:
- Verify Docker Hub credentials in GitHub Secrets
- Check workflow logs for cache push errors

### "Deploy failed after skip"
The deploy job runs even when build is skipped, pulling the existing image. Check:
- SSH connection to production server
- Deploy script errors in logs

## Future Optimizations

Possible future improvements:
- Multi-stage builds for smaller production images
- Separate base image for system dependencies
- Parallel builds for different architectures
- Automated cache cleanup for old layers
