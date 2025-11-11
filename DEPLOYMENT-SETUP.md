# Deployment Setup Guide

This guide explains how to set up GitHub Actions automated deployment for this Laravel application.

## Problem

The GitHub Actions workflow was failing with:
```
git@github.com: Permission denied (publickey).
fatal: Could not read from remote repository.
```

This happens because the production server needs to authenticate with GitHub to pull code changes.

## Solution: Add GitHub Deploy Key

### Step 1: Generate SSH Key on Production Server

SSH into your production server and run:

```bash
# Generate a new SSH key for deployment
ssh-keygen -t ed25519 -C "deploy@kirstensiebach.com" -f ~/.ssh/github_deploy_key

# Display the public key (copy this)
cat ~/.ssh/github_deploy_key.pub
```

### Step 2: Add Deploy Key to GitHub

1. Go to: https://github.com/jsiebach/kirstensiebach/settings/keys
2. Click "Add deploy key"
3. Give it a title: "Production Server Deploy Key"
4. Paste the public key from Step 1
5. **Do NOT check** "Allow write access" (read-only is safer)
6. Click "Add key"

### Step 3: Configure SSH on Production Server

Tell git to use this key when connecting to GitHub:

```bash
# Add configuration to SSH config file
cat >> ~/.ssh/config << 'EOF'
Host github.com
    IdentityFile ~/.ssh/github_deploy_key
    IdentitiesOnly yes
EOF

# Set proper permissions
chmod 600 ~/.ssh/config
chmod 600 ~/.ssh/github_deploy_key
```

### Step 4: Test the Connection

```bash
# Test SSH connection to GitHub
ssh -T git@github.com

# Should see: "Hi jsiebach/kirstensiebach! You've successfully authenticated..."
```

### Step 5: Verify Git Remote

Make sure your git remote is using SSH (not HTTPS):

```bash
cd /path/to/kirstensiebach

# Check current remote
git remote -v

# Should show: git@github.com:jsiebach/kirstensiebach.git
# If it shows https://, change it:
git remote set-url origin git@github.com:jsiebach/kirstensiebach.git
```

### Step 6: Make Deploy Script Executable

```bash
cd /var/www/kirstensiebach
chmod +x deploy.sh
```

### Step 7: Test Deployment

```bash
cd /var/www/kirstensiebach
./deploy.sh
```

If successful, you should see:
```
🚀 Starting deployment...
📥 Pulling latest code from master branch...
🐳 Fetching new docker images...
...
✅ Deployment complete!
```

## Alternative: Using HTTPS with Personal Access Token

If you prefer HTTPS over SSH:

1. **Create a Personal Access Token:**
   - Go to: https://github.com/settings/tokens
   - Click "Generate new token (classic)"
   - Select scope: `repo`
   - Copy the token (starts with `ghp_`)

2. **Configure git on production:**
   ```bash
   cd /path/to/kirstensiebach

   # Change to HTTPS
   git remote set-url origin https://github.com/jsiebach/kirstensiebach.git

   # Configure credentials
   git config credential.helper store

   # Pull (will prompt for credentials once)
   git pull origin master
   # Username: jsiebach
   # Password: ghp_yourTokenHere
   ```

## Deployment Script

The `deploy.sh` script now:
1. Pulls latest code from git
2. Pulls latest Docker images
3. Restarts containers
4. Links storage
5. Caches config
6. Runs migrations

## GitHub Actions Workflow

The workflow (`master-push.yml`) automatically:
1. Runs tests
2. Builds Docker image
3. Pushes to Docker Hub
4. SSHs to production server
5. Runs `cd /var/www/kirstensiebach && ./deploy.sh`

The deployment command is now stored in the workflow file (not a secret) since it just runs the deploy script.

## Troubleshooting

### "Permission denied (publickey)"
- Deploy key not added to GitHub, or
- SSH config not pointing to correct key file

### "Could not resolve hostname"
- Network issue or DNS problem
- Check internet connection on production server

### "fatal: Not a git repository"
- deploy.sh is not running in the correct directory
- The workflow expects the app to be at `/var/www/kirstensiebach`
- Update the path in `.github/workflows/master-push.yml` if your app is elsewhere

### Docker containers not updating
- Make sure you're pulling the latest image: `docker-compose pull`
- Verify image tag in docker-compose.yml matches what's pushed to Docker Hub
