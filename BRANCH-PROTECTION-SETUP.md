# Branch Protection Setup

This document explains how to configure GitHub branch protection rules for this repository's optimized CI/CD workflow.

## Testing Strategy Overview

Our streamlined CI/CD pipeline tests code **once** on pull requests, then fast-tracks through dev → master → production:

```
Feature Branch → PR to dev → [TESTS RUN HERE] → Merge to dev
                                                       ↓
                                                  [Auto-merge]
                                                       ↓
                                                   master
                                                       ↓
                                              [Build & Deploy]
                                                       ↓
                                                  Production
```

**Key Points:**
- ✅ Tests run on **PRs to dev** (dev-pr.yml)
- ❌ No tests on push to dev (dev-push.yml) - just auto-merges to master
- ❌ No tests on push to master (master-push.yml) - just builds and deploys

This is efficient because:
1. PRs are already tested before merging
2. Faster deployments (no redundant test runs)
3. Branch protection ensures all code goes through PR review and testing

## Required Branch Protection Rules

To ensure all code is properly tested, you **must** configure branch protection for the `dev` branch.

### Protect the `dev` Branch

1. **Go to Branch Settings:**
   - Navigate to: https://github.com/jsiebach/kirstensiebach/settings/branches
   - Click "Add rule" or "Add branch protection rule"

2. **Branch Name Pattern:**
   ```
   dev
   ```

3. **Enable These Settings:**

   ✅ **Require a pull request before merging**
   - Ensures all code goes through PR process
   - Tests will run via dev-pr.yml workflow

   ✅ **Require status checks to pass before merging**
   - Click "Require status checks to pass before merging"
   - Search for and select: `laravel-tests`
   - This is the test job from dev-pr.yml

   ✅ **Require branches to be up to date before merging** (Optional but recommended)
   - Ensures PRs are tested against latest dev code

   ⚠️ **Do not allow bypassing the above settings** (Recommended)
   - Prevents admins from accidentally bypassing protections

   ❌ **Do NOT require pull request reviews** (Optional)
   - Only enable if you want mandatory human reviews
   - For solo projects, testing alone may be sufficient

4. **Optional Settings:**

   ✅ **Require linear history** (Nice to have)
   - Prevents merge commits, keeps history clean
   - Works well with "Squash and merge" strategy

   ✅ **Require deployments to succeed before merging** (Optional)
   - Only if you have preview deployments

5. **Click "Create" or "Save changes"**

### Master Branch Protection (Already Implicit)

The `master` branch is protected by the workflow itself:
- Only the `dev-push.yml` workflow can merge to master
- The workflow uses `GIT_TOKEN` secret to bypass branch protection
- This ensures controlled, automated merges only

You can optionally add explicit protection to master:
- Restrict who can push directly (select specific users/teams)
- But don't require status checks (dev already tested)

## What Happens Without Branch Protection?

If you don't enable branch protection on `dev`:

⚠️ **Risk:** Someone could push directly to dev, bypassing tests
- Code would auto-merge to master
- Would deploy to production untested
- Could break production

✅ **Solution:** Branch protection forces all changes through PRs where tests run

## Testing the Setup

After configuring branch protection:

1. **Create a test PR to dev:**
   ```bash
   git checkout -b test-branch-protection
   echo "test" >> README.md
   git add README.md
   git commit -m "Test branch protection"
   git push origin test-branch-protection
   ```

2. **Open PR on GitHub:**
   - Target branch: `dev`
   - You should see tests running automatically
   - Merge button should be disabled until tests pass

3. **After tests pass:**
   - Merge button becomes enabled
   - Merge the PR

4. **Watch auto-deployment:**
   - dev-push.yml auto-merges dev → master
   - master-push.yml builds Docker image and deploys

## Troubleshooting

### "Status check 'laravel-tests' not found"
- The status check won't appear until the first PR runs the workflow
- Create a test PR first, then add the protection rule
- Or add the rule without selecting specific checks initially

### "Workflow isn't triggering"
- Check that `.github/workflows/dev-pr.yml` exists on the branch
- Verify the `on: pull_request: branches: [ dev ]` trigger is correct

### "Can't merge even after tests pass"
- Check if "Require branches to be up to date" is enabled
- You may need to update the branch with latest dev first
- Click "Update branch" button on PR

## Summary

**Critical Setup:** Enable branch protection on `dev` with required status checks

This ensures:
- ✅ All code is tested before merging
- ✅ Fast CI/CD pipeline (tests run once)
- ✅ Automated deployments
- ✅ Production safety
