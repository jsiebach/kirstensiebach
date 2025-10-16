# Laravel 12 & Filament 4 Migration Strategy

## Overview
This document outlines the technical strategy for migrating from Laravel 10 + Nova 4.33 to Laravel 12 + Filament 4, including sequencing, risk mitigation, and rollback procedures.

## Current State Analysis

### Current Technology Stack
- **PHP Version:** 8.0
- **Laravel Version:** 10.x
- **Admin Panel:** Laravel Nova 4.33.3
- **Database:** MySQL
- **Storage:** Public disk (Laravel default)

### Current Package Dependencies
```json
{
    "php": "^8.0",
    "laravel/framework": "^10.0",
    "laravel/nova": "4.33.3",
    "outl1ne/nova-sortable": "^3.4",
    "outl1ne/nova-settings": "^5.1",
    "vmitchell85/nova-links": "^2.1",
    "spatie/eloquent-sortable": "^2.0",
    "spatie/laravel-schemaless-attributes": "^2.0",
    "spatie/laravel-sitemap": "^7.0",
    "mdixon18/fontawesome": "^0.2.1"
}
```

### Current Environment
- Database: Existing production data (zero data loss requirement)
- Files: Existing uploads in public/storage
- Users: 2 admin users (email whitelist)
- Content: 7 pages + associated content resources

---

## Migration Phases

### Phase 1: Pre-Migration Preparation
**Estimated Time:** 1-2 hours
**Risk Level:** Low

#### 1.1 Backup Everything
```bash
# Database backup
php artisan db:backup  # or mysqldump
# OR
mysqldump -u root -p kirstensiebach > backup_$(date +%Y%m%d_%H%M%S).sql

# Code backup
git checkout -b pre-migration-backup
git add .
git commit -m "Pre-migration backup"

# Storage backup
tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public
```

#### 1.2 Document Current State
- [ ] Take screenshots of all Nova resources
- [ ] Export current settings (Favicon, Tracking Code, Schema Markup)
- [ ] Document current sort orders for all sortable resources
- [ ] List all users and their emails
- [ ] Document all file paths for uploads

#### 1.3 Environment Setup
- [ ] Create staging environment (copy of production)
- [ ] Test all migrations on staging first
- [ ] Prepare rollback procedures

#### 1.4 Create New Branch
```bash
git checkout -b laravel-12-filament-migration
```

---

### Phase 2: PHP Upgrade
**Estimated Time:** 30 minutes - 1 hour
**Risk Level:** Low to Medium

#### 2.1 Update PHP Version
```bash
# macOS with Homebrew
brew install php@8.4
brew link php@8.4 --force --overwrite

# Verify version
php -v  # Should show 8.4.x
```

#### 2.2 Update Composer Dependencies for PHP 8.4
```bash
composer update --dry-run  # See what will change
composer update
```

#### 2.3 Test Application
```bash
php artisan serve
# Visit /admin and verify Nova still works
# Check frontend still works
```

**Rollback:** If issues arise, revert PHP version and composer.lock

---

### Phase 3: Laravel 10 → 11 Upgrade
**Estimated Time:** 1-2 hours
**Risk Level:** Medium

#### 3.1 Review Laravel 11 Upgrade Guide
- Read: https://laravel.com/docs/11.x/upgrade
- Note any breaking changes affecting this project

#### 3.2 Update composer.json
```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^11.0",
        "laravel/nova": "4.33.3"
    }
}
```

#### 3.3 Run Composer Update
```bash
composer update laravel/framework --with-all-dependencies
```

#### 3.4 Update Configuration Files
- [ ] Review and update config files per Laravel 11 upgrade guide
- [ ] Update middleware if changed
- [ ] Update exception handler if needed

#### 3.5 Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### 3.6 Test Application
```bash
php artisan serve
# Test all Nova resources
# Test all page views
# Check for errors
```

**Rollback:** If issues arise, revert composer.json and run `composer install`

---

### Phase 4: Laravel 11 → 12 Upgrade
**Estimated Time:** 1-2 hours
**Risk Level:** Medium

#### 4.1 Review Laravel 12 Upgrade Guide
- Read: https://laravel.com/docs/12.x/upgrade
- Note any breaking changes affecting this project

#### 4.2 Update composer.json
```json
{
    "require": {
        "php": "^8.4",
        "laravel/framework": "^12.0",
        "laravel/nova": "4.33.3"
    }
}
```

#### 4.3 Run Composer Update
```bash
composer update laravel/framework --with-all-dependencies
```

#### 4.4 Update Configuration and Code
- [ ] Review and update config files per Laravel 12 upgrade guide
- [ ] Update any deprecated code
- [ ] Update middleware if needed
- [ ] Update routes if syntax changed

#### 4.5 Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

#### 4.6 Test Application Thoroughly
```bash
php artisan serve
# Test all Nova resources (create, read, update, delete)
# Test all sortable functionality
# Test file uploads
# Test settings
# Check for errors in logs
```

**Checkpoint:** Laravel 12 with Nova should be fully functional before proceeding.

**Rollback:** If issues arise, revert composer.json to Laravel 11

---

### Phase 5: Install Filament Alongside Nova
**Estimated Time:** 30 minutes
**Risk Level:** Low

#### 5.1 Install Filament 4
```bash
composer require filament/filament:"^4.0"
```

#### 5.2 Run Filament Installation
```bash
php artisan filament:install --panels
```

#### 5.3 Configure Filament
Update `config/filament.php`:
```php
return [
    'path' => 'filament',  // Different from Nova's /admin
    // ... other config
];
```

#### 5.4 Create Admin User for Filament
```bash
php artisan make:filament-user
```

#### 5.5 Test Both Admin Panels
- Nova: http://localhost:8000/admin
- Filament: http://localhost:8000/filament

**Success Criteria:** Both admin panels accessible without conflicts

---

### Phase 6: Build Filament Resources
**Estimated Time:** 4-6 hours
**Risk Level:** Medium

#### 6.1 Install Additional Filament Plugins
```bash
# Settings plugin (research best option for Filament 4)
composer require filament/spatie-laravel-settings-plugin

# Or alternative settings plugin
# composer require [other-settings-plugin]

# Media library (if needed for file management)
# Research sortable plugin options for Filament 4
```

#### 6.2 Create User Resource
```bash
php artisan make:filament-resource User
```

Implement fields:
- Name (TextInput)
- Email (TextInput with email validation)
- Password (TextInput with password type)
- Role (Select - after implementing roles)

#### 6.3 Implement Role-Based Access Control

**Option A: Spatie Laravel-Permission**
```bash
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate
```

Create admin role and assign to existing users:
```php
// In migration or seeder
use Spatie\Permission\Models\Role;

Role::create(['name' => 'admin']);

// Assign to existing users
User::whereIn('email', ['jsiebach@gmail.com', 'ksiebach@gmail.com'])
    ->each(fn($user) => $user->assignRole('admin'));
```

**Option B: Simple Role Column**
```bash
php artisan make:migration add_role_to_users_table
```

```php
// Migration
Schema::table('users', function (Blueprint $table) {
    $table->string('role')->default('user');
});

// Update existing admins
DB::table('users')
    ->whereIn('email', ['jsiebach@gmail.com', 'ksiebach@gmail.com'])
    ->update(['role' => 'admin']);
```

Update Filament config:
```php
// config/filament.php or FilamentServiceProvider
use Filament\Facades\Filament;

Filament::auth(function () {
    return auth()->check() && auth()->user()->role === 'admin';
});
```

#### 6.4 Create Page Resources

**Base Page Resource**
```bash
php artisan make:filament-resource Page
```

**Challenge:** Filament doesn't natively support STI like Nova does. Two approaches:

**Approach A: Single Page Resource with Type Selection**
- Create one Page resource
- Add type field (select) to switch between page types
- Conditionally show fields based on type
- More complex but maintains STI pattern

**Approach B: Separate Resources for Each Page Type**
- Create HomePage, LabPage, ResearchPage, etc. resources
- Each resource explicitly binds to its model
- Simpler implementation
- Better matches Nova's child resource approach

**Recommendation: Approach B** (matches Nova pattern more closely)

```bash
php artisan make:filament-resource HomePage
php artisan make:filament-resource LabPage
php artisan make:filament-resource ResearchPage
php artisan make:filament-resource PublicationsPage
php artisan make:filament-resource CvPage
php artisan make:filament-resource OutreachPage
php artisan make:filament-resource PhotographyPage
```

#### 6.5 Create Content Resources
```bash
php artisan make:filament-resource TeamMember
php artisan make:filament-resource Research --generate
php artisan make:filament-resource Publication
php artisan make:filament-resource ScienceAbstract
php artisan make:filament-resource Press
php artisan make:filament-resource SocialLink
```

#### 6.6 Implement Relation Managers

For page-specific resources, create relation managers:

```bash
php artisan make:filament-relation-manager HomePageResource press title
php artisan make:filament-relation-manager HomePageResource socialLinks title
php artisan make:filament-relation-manager LabPageResource teamMembers name
php artisan make:filament-relation-manager ResearchPageResource research project_name
php artisan make:filament-relation-manager PublicationsPageResource publications title
php artisan make:filament-relation-manager PublicationsPageResource scienceAbstracts title
```

#### 6.7 Implement Sortable Functionality

Research best Filament 4 sortable plugin. Options might include:
- Native Filament reorder action
- Community sortable plugin

For each sortable resource (TeamMember, Research, SocialLink):
```php
// In resource table definition
public static function table(Table $table): Table
{
    return $table
        ->reorderable('sort_order')  // Enables drag-and-drop
        ->defaultSort('sort_order')
        // ... columns
}
```

#### 6.8 Implement Settings Page

```bash
php artisan make:filament-page Settings
```

Implement settings fields:
- Favicon (FileUpload)
- Tracking Code (Textarea or CodeEditor)
- Schema Markup (Textarea or CodeEditor)

If using settings plugin, integrate with existing nova-settings data storage.

#### 6.9 Test Each Resource Thoroughly

For each resource, test:
- [ ] List view displays correctly
- [ ] Create form works with all fields
- [ ] Validation works
- [ ] Edit form loads with existing data
- [ ] Update saves correctly
- [ ] Delete works
- [ ] Relationships load correctly
- [ ] Sortable drag-and-drop works (if applicable)
- [ ] File uploads work
- [ ] Images display correctly

---

### Phase 7: Implement Dynamic Navigation
**Estimated Time:** 1-2 hours
**Risk Level:** Medium

#### 7.1 Create Navigation Group for Pages

In `FilamentServiceProvider` or `FilamentPanelProvider`:

```php
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use App\Models\Page;

public function boot()
{
    Filament::serving(function () {
        $pages = Page::all();

        $navigationItems = $pages->map(function ($page) {
            return NavigationItem::make($page->title)
                ->url(route('filament.resources.pages.edit', ['record' => $page->id]))
                ->icon('heroicon-o-document-text')
                ->group('Pages');
        });

        Filament::registerNavigationItems($navigationItems->toArray());
    });
}
```

#### 7.2 Configure Navigation Groups

```php
NavigationGroup::make('Resources')
    ->items([
        // Users resource
    ]);

NavigationGroup::make('Pages')
    ->items([
        // Dynamic page items
    ]);

NavigationGroup::make('Settings')
    ->items([
        // Settings page
    ]);
```

#### 7.3 Test Navigation

- [ ] All 7 pages appear in sidebar
- [ ] Clicking each opens correct page
- [ ] Navigation groups work correctly
- [ ] Order is correct

---

### Phase 8: Data Verification
**Estimated Time:** 1-2 hours
**Risk Level:** Low

#### 8.1 Verify All Data Accessible

For each resource type:
- [ ] All records display in Filament
- [ ] Record counts match Nova
- [ ] Relationships load correctly
- [ ] Files/images accessible
- [ ] Sort orders preserved

#### 8.2 Settings Migration

- [ ] Export current settings from Nova
- [ ] Import into Filament settings
- [ ] Verify settings display correctly

#### 8.3 Test CRUD Operations

For each resource:
- [ ] Create new record
- [ ] Edit existing record
- [ ] Delete record (test, then restore from backup)
- [ ] Test validation

---

### Phase 9: Remove Nova
**Estimated Time:** 30 minutes
**Risk Level:** Low (if previous phases successful)

#### 9.1 Update Composer Dependencies

Remove from `composer.json`:
```json
{
    "require": {
        "laravel/nova": "4.33.3",
        "outl1ne/nova-sortable": "^3.4",
        "outl1ne/nova-settings": "^5.1",
        "vmitchell85/nova-links": "^2.1"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://nova.laravel.com"
        }
    ]
}
```

```bash
composer remove laravel/nova outl1ne/nova-sortable outl1ne/nova-settings vmitchell85/nova-links
```

#### 9.2 Remove Nova Files

```bash
rm -rf app/Nova
rm -f app/Providers/NovaServiceProvider.php
rm -f config/nova.php
```

#### 9.3 Update Providers

Remove from `config/app.php`:
```php
'providers' => [
    // Remove:
    // App\Providers\NovaServiceProvider::class,
]
```

#### 9.4 Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

#### 9.5 Test Application

- [ ] Visit /admin (should 404 or redirect)
- [ ] Visit /filament (should work)
- [ ] Test all Filament resources
- [ ] No errors in logs

---

### Phase 10: Final Testing & Deployment
**Estimated Time:** 2-3 hours
**Risk Level:** Medium

#### 10.1 Comprehensive Testing

**Admin Panel:**
- [ ] Login works
- [ ] All resources accessible
- [ ] CRUD operations work
- [ ] Sortable works
- [ ] File uploads work
- [ ] Settings work
- [ ] Navigation works
- [ ] Permissions work

**Frontend:**
- [ ] All pages still load
- [ ] All content displays
- [ ] No broken links
- [ ] Images load
- [ ] No console errors

**Performance:**
- [ ] Page load times acceptable
- [ ] No N+1 query issues
- [ ] File upload performance good

#### 10.2 Update Documentation

- [ ] Update README with new admin URL
- [ ] Document new setup process
- [ ] Update deployment instructions
- [ ] Document any environment variable changes

#### 10.3 Environment Configuration

Update `.env`:
```
# Remove Nova-specific vars
# Add Filament-specific vars if needed
```

Update `.env.example`:
```
# Update with Filament examples
```

#### 10.4 Staging Deployment

- [ ] Deploy to staging
- [ ] Test on staging
- [ ] Verify with stakeholders
- [ ] Get approval

#### 10.5 Production Deployment

**Pre-deployment:**
```bash
# Final backup
php artisan backup:run  # or custom backup command
mysqldump -u user -p database > final_backup.sql
```

**Deployment:**
```bash
git checkout main
git merge laravel-12-filament-migration
git push origin main

# On production server:
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force  # if any new migrations
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Post-deployment:**
- [ ] Test admin login
- [ ] Verify all resources work
- [ ] Monitor error logs
- [ ] Notify users of new admin URL (if changed)

---

## Rollback Procedures

### If Issues in Phase 2-4 (Laravel Upgrade)
```bash
# Revert composer.json to previous version
git checkout composer.json composer.lock
composer install

# Restore database if migrations ran
mysql -u user -p database < backup.sql

# Restart services
php artisan config:clear
php artisan cache:clear
```

### If Issues in Phase 5-8 (Filament Installation)
```bash
# Remove Filament
composer remove filament/filament [other-filament-packages]

# Keep Nova
# Application should still work with Nova

# Or revert entire branch
git checkout main
git branch -D laravel-12-filament-migration
```

### If Issues in Phase 9-10 (After Nova Removal)
```bash
# Restore from backup branch
git checkout pre-migration-backup

# Restore database
mysql -u user -p database < backup.sql

# Restore storage
tar -xzf storage_backup_[date].tar.gz

# Reinstall dependencies
composer install
```

---

## Risk Mitigation Strategies

### 1. Incremental Approach
- Complete one phase fully before moving to next
- Test thoroughly after each phase
- Commit after each successful phase

### 2. Parallel Development
- Keep Nova working while building Filament
- Only remove Nova when Filament is 100% ready
- Can switch URLs if needed (/admin for Nova, /filament for new)

### 3. Staging Environment
- Test everything on staging first
- Never test directly on production
- Get stakeholder approval on staging

### 4. Backup Strategy
- Database backup before each major phase
- Git commits after each phase
- Storage backup before removing Nova
- Keep backups for at least 30 days

### 5. Feature Flags (Optional)
```php
// .env
ADMIN_PANEL=nova  # or filament

// Route configuration
if (env('ADMIN_PANEL') === 'filament') {
    // Filament routes
} else {
    // Nova routes
}
```

---

## Timeline Estimate

| Phase | Description | Time | Cumulative |
|-------|-------------|------|------------|
| 1 | Pre-Migration Prep | 1-2h | 1-2h |
| 2 | PHP Upgrade | 0.5-1h | 1.5-3h |
| 3 | Laravel 10→11 | 1-2h | 2.5-5h |
| 4 | Laravel 11→12 | 1-2h | 3.5-7h |
| 5 | Install Filament | 0.5h | 4-7.5h |
| 6 | Build Resources | 4-6h | 8-13.5h |
| 7 | Navigation | 1-2h | 9-15.5h |
| 8 | Data Verification | 1-2h | 10-17.5h |
| 9 | Remove Nova | 0.5h | 10.5-18h |
| 10 | Final Testing | 2-3h | 12.5-21h |

**Total Estimated Time:** 12-21 hours

**Recommended Schedule:**
- **Day 1 (4-6 hours):** Phases 1-5 (Laravel upgrade + Filament install)
- **Day 2 (6-8 hours):** Phase 6 (Build all Filament resources)
- **Day 3 (2-4 hours):** Phases 7-8 (Navigation + Data verification)
- **Day 4 (2-3 hours):** Phases 9-10 (Remove Nova + Final testing)

---

## Success Criteria

### Must Have
- [ ] All Laravel 12 features working
- [ ] All Filament resources functional
- [ ] All data accessible and editable
- [ ] No data loss
- [ ] All sortable functionality working
- [ ] All file uploads/downloads working
- [ ] Settings persisting
- [ ] Access control working
- [ ] No critical errors in logs

### Nice to Have
- [ ] Improved navigation UX
- [ ] Faster page loads
- [ ] Better mobile responsiveness
- [ ] Improved search functionality

---

## Post-Migration Tasks

### Immediate (Within 1 Week)
- [ ] Monitor error logs daily
- [ ] Gather user feedback
- [ ] Fix any minor bugs found
- [ ] Optimize performance if needed

### Short-term (Within 1 Month)
- [ ] Add any missing features identified
- [ ] Improve UI/UX based on feedback
- [ ] Document any new patterns
- [ ] Train users on new interface

### Long-term (1-3 Months)
- [ ] Consider additional Filament plugins
- [ ] Evaluate custom widgets for dashboard
- [ ] Consider activity logging
- [ ] Evaluate bulk actions

---

## Contact & Support

### During Migration
- Keep stakeholders informed of progress
- Document any blockers immediately
- Ask for clarification on any unclear requirements

### Resources
- Laravel Upgrade Guide: https://laravel.com/docs/upgrade
- Filament Documentation: https://filamentphp.com/docs
- Laravel Discord: https://discord.gg/laravel
- Filament Discord: https://discord.gg/filamentphp

---

## Notes & Considerations

### Nova vs Filament Differences

**Advantages of Filament:**
- Open source (no licensing cost)
- Active community
- Modern UI components
- Better mobile support
- More flexible customization

**Things to Watch:**
- Different conventions (learn curve)
- Different plugin ecosystem
- Some Nova features may not have exact equivalents
- May need custom components for specific needs

### Critical Files to Monitor

During migration, pay special attention to:
- `composer.json` / `composer.lock`
- `config/app.php`
- `config/auth.php`
- `config/filesystems.php`
- `.env`
- All model files (ensure relationships intact)
- All migration files (ensure no accidental changes)

### Testing Checklist

Print this and check off as you test:
- [ ] Login to admin panel
- [ ] Create user
- [ ] Edit user
- [ ] Delete user
- [ ] View each of 7 pages
- [ ] Edit each page's content
- [ ] Upload image to page
- [ ] Create team member
- [ ] Reorder team members
- [ ] Create research project
- [ ] Reorder research projects
- [ ] Create publication
- [ ] Create science abstract
- [ ] Create press item
- [ ] Create social link
- [ ] Reorder social links
- [ ] Update settings (favicon, tracking, schema)
- [ ] Verify all uploaded files accessible
- [ ] Check frontend displays all content
- [ ] Verify no console errors
- [ ] Check server error logs
