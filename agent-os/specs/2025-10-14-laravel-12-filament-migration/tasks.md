# Task Breakdown: Laravel 12 & Filament 4 Migration

## Overview
**Total Estimated Time:** 18-28 hours
**Total Phases:** 12
**Risk Level:** Medium to High
**Rollback Strategy:** Multi-stage with backups at each critical phase

## Recommended Specialist Roles

For this migration, the following specialist roles are recommended:

1. **backend-engineer**: Framework upgrades, package management, service providers, migrations
2. **filament-specialist**: Filament resources, relation managers, panels, form schemas
3. **database-engineer**: Data integrity verification, permission system setup, migrations
4. **testing-engineer**: Test verification, data integrity checks, regression testing
5. **devops-engineer**: Deployment, environment configuration, backup/restore procedures

---

## Task List

### Phase 1: Pre-Migration Preparation

#### Task Group 1.1: Backup & Documentation
**Assigned Role:** devops-engineer
**Dependencies:** None
**Estimated Time:** 1-2 hours
**Risk Level:** Low
**Priority:** Critical

- [ ] 1.1.0 Complete pre-migration preparation
  - [ ] 1.1.1 Create full database backup
    - Execute: `mysqldump -u root -p kirstensiebach > backup_$(date +%Y%m%d_%H%M%S).sql`
    - Store backup in secure location outside project directory
    - Verify backup file is not corrupted (check file size > 0)
  - [ ] 1.1.2 Create git backup branch
    - Execute: `git checkout -b pre-migration-backup`
    - Commit all current changes
    - Push to remote for safety
  - [ ] 1.1.3 Backup storage directory
    - Execute: `tar -czf storage_backup_$(date +%Y%m%d_%H%M%S).tar.gz storage/app/public`
    - Store backup in secure location
    - Document all file paths currently in use
  - [ ] 1.1.4 Document current state
    - Take screenshots of all 7 Nova page resources
    - Take screenshots of all 6 Nova content resources
    - Take screenshots of Nova settings page
    - Export current settings values (Favicon, Tracking Code, Schema Markup)
  - [ ] 1.1.5 Document sort orders
    - Query and document Team Members sort_order values
    - Query and document Research Projects sort_order values
    - Query and document Social Links sort_order values
    - Save as reference file for post-migration verification
  - [ ] 1.1.6 Create migration branch
    - Execute: `git checkout -b laravel-12-filament-migration`
    - Set up clean working environment

**Acceptance Criteria:**
- Database backup file exists and is valid
- Git backup branch created and pushed
- Storage backup archived
- All screenshots captured (15+ screenshots)
- Sort order documentation complete
- New migration branch ready

**Rollback Plan:** N/A (this is the backup phase)

---

### Phase 2: PHP Version Upgrade

#### Task Group 2.1: PHP 8.4 Installation & Testing
**Assigned Role:** backend-engineer
**Dependencies:** Task Group 1.1
**Estimated Time:** 30 minutes - 1 hour
**Risk Level:** Low to Medium
**Priority:** High

- [ ] 2.1.0 Complete PHP upgrade
  - [ ] 2.1.1 Install PHP 8.4
    - macOS: `brew install php@8.4 && brew link php@8.4 --force --overwrite`
    - Verify: `php -v` shows 8.4.x
    - Document PHP extensions installed
  - [ ] 2.1.2 Update Composer for PHP 8.4 compatibility
    - Execute: `composer update --dry-run` to preview changes
    - Review output for any breaking changes
    - Execute: `composer update`
  - [ ] 2.1.3 Test application with PHP 8.4
    - Start server: `php artisan serve`
    - Visit /admin and verify Nova still works
    - Test user login
    - View 2-3 page resources
    - Check for PHP errors in logs
  - [ ] 2.1.4 Address PHP 8.4 deprecations
    - Review Laravel log for deprecation warnings
    - Fix any deprecated function calls
    - Update code using deprecated patterns

**Acceptance Criteria:**
- PHP 8.4 is active (`php -v` confirms)
- Composer dependencies updated without errors
- Application runs without PHP errors
- Nova admin panel is accessible and functional

**Rollback Plan:**
```bash
brew unlink php@8.4
brew link php@8.0 --force
git checkout composer.json composer.lock
composer install
```

---

### Phase 3: Laravel 10 → 11 Upgrade

#### Task Group 3.1: Framework Upgrade to Laravel 11
**Assigned Role:** backend-engineer
**Dependencies:** Task Group 2.1
**Estimated Time:** 1-2 hours
**Risk Level:** Medium
**Priority:** High

- [ ] 3.1.0 Complete Laravel 11 upgrade
  - [ ] 3.1.1 Review Laravel 11 upgrade guide
    - Read: https://laravel.com/docs/11.x/upgrade
    - Document breaking changes relevant to this project
    - Identify affected files/features
  - [ ] 3.1.2 Update composer.json for Laravel 11
    - Change `"laravel/framework": "^11.0"`
    - Keep `"php": "^8.4"`
    - Keep all other dependencies as-is
    - Commit composer.json changes
  - [ ] 3.1.3 Run composer update
    - Execute: `composer update laravel/framework --with-all-dependencies`
    - Monitor for dependency conflicts
    - Resolve any version conflicts
  - [ ] 3.1.4 Update configuration files
    - Review and update config files per Laravel 11 upgrade guide
    - Update middleware if required
    - Update exception handler if required
    - Update service providers if required
  - [ ] 3.1.5 Clear all caches
    - Execute: `php artisan config:clear`
    - Execute: `php artisan cache:clear`
    - Execute: `php artisan view:clear`
    - Execute: `php artisan route:clear`
  - [ ] 3.1.6 Test Laravel 11 with Nova
    - Start server: `php artisan serve`
    - Test Nova admin panel access
    - Test all 7 page resources in Nova
    - Test CRUD operations on 2-3 resources
    - Check logs for errors
  - [ ] 3.1.7 Run existing tests (if any)
    - Execute: `php artisan test`
    - Address any test failures

**Acceptance Criteria:**
- Composer successfully updates to Laravel 11
- Application runs without errors
- Nova admin panel fully functional
- All page resources accessible
- No critical errors in logs

**Rollback Plan:**
```bash
git checkout composer.json composer.lock
composer install
php artisan config:clear
php artisan cache:clear
```

---

### Phase 4: Laravel 11 → 12 Upgrade

#### Task Group 4.1: Framework Upgrade to Laravel 12
**Assigned Role:** backend-engineer
**Dependencies:** Task Group 3.1
**Estimated Time:** 1-2 hours
**Risk Level:** Medium
**Priority:** High

- [ ] 4.1.0 Complete Laravel 12 upgrade
  - [ ] 4.1.1 Review Laravel 12 upgrade guide
    - Read: https://laravel.com/docs/12.x/upgrade
    - Document breaking changes relevant to this project
    - Identify affected files/features
  - [ ] 4.1.2 Update composer.json for Laravel 12
    - Change `"laravel/framework": "^12.0"`
    - Keep `"php": "^8.4"`
    - Keep all other dependencies as-is
    - Commit composer.json changes
  - [ ] 4.1.3 Run composer update
    - Execute: `composer update laravel/framework --with-all-dependencies`
    - Monitor for dependency conflicts
    - Resolve any version conflicts
  - [ ] 4.1.4 Update configuration and code
    - Review and update config files per Laravel 12 upgrade guide
    - Update any deprecated code patterns
    - Update middleware if required
    - Update routes if syntax changed
  - [ ] 4.1.5 Clear all caches
    - Execute: `php artisan config:clear`
    - Execute: `php artisan cache:clear`
    - Execute: `php artisan view:clear`
    - Execute: `php artisan route:clear`
  - [ ] 4.1.6 Comprehensive Laravel 12 + Nova testing
    - Start server: `php artisan serve`
    - Test Nova admin panel access
    - Test ALL 7 page resources in Nova
    - Test CRUD operations on each resource type
    - Test sortable functionality on Team Members
    - Test file upload on CV page
    - Test settings page
    - Check logs for errors
  - [ ] 4.1.7 Run existing tests (if any)
    - Execute: `php artisan test`
    - Address any test failures

**Acceptance Criteria:**
- Composer successfully updates to Laravel 12
- Application runs without errors
- Nova admin panel fully functional
- All page resources accessible and editable
- Sortable functionality works
- File uploads work
- Settings accessible
- No critical errors in logs

**Checkpoint:** Laravel 12 with Nova must be 100% functional before proceeding.

**Rollback Plan:**
```bash
git checkout composer.json composer.lock
composer install
php artisan config:clear
php artisan cache:clear
```

---

### Phase 5: Install Filament Alongside Nova

#### Task Group 5.1: Filament Installation & Initial Setup
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 4.1
**Estimated Time:** 30 minutes - 1 hour
**Risk Level:** Low
**Priority:** High

- [ ] 5.1.0 Complete Filament installation
  - [ ] 5.1.1 Install Filament 4
    - Execute: `composer require filament/filament:"^4.0"`
    - Monitor installation output
    - Verify no conflicts with Nova
  - [ ] 5.1.2 Run Filament installation
    - Execute: `php artisan filament:install --panels`
    - Follow prompts for panel creation
    - Configure panel name as "admin"
  - [ ] 5.1.3 Configure Filament path
    - Update Filament panel configuration
    - Set path to `/filament` (different from Nova's `/admin`)
    - Configure brand name as "Site Admin"
  - [ ] 5.1.4 Create initial Filament admin user
    - Execute: `php artisan make:filament-user`
    - Create user with email: jsiebach@gmail.com
    - Set secure password
  - [ ] 5.1.5 Test both admin panels
    - Visit http://localhost:8000/admin (Nova - should work)
    - Visit http://localhost:8000/filament (Filament - should work)
    - Login to both panels
    - Verify no route conflicts
    - Verify no asset conflicts

**Acceptance Criteria:**
- Filament 4 installed successfully
- Both /admin (Nova) and /filament (Filament) accessible
- Can login to both panels
- No conflicts between Nova and Filament
- No errors in logs

**Rollback Plan:**
```bash
composer remove filament/filament
php artisan config:clear
php artisan route:clear
```

---

### Phase 6: Permission System Setup

#### Task Group 6.1: Role-Based Access Control
**Assigned Role:** database-engineer
**Dependencies:** Task Group 5.1
**Estimated Time:** 1-2 hours
**Risk Level:** Low to Medium
**Priority:** High

- [ ] 6.1.0 Complete permission system setup
  - [ ] 6.1.1 Install Spatie Laravel-Permission
    - Execute: `composer require spatie/laravel-permission`
    - Verify installation successful
  - [ ] 6.1.2 Publish permission migrations
    - Execute: `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
    - Review generated migration files
  - [ ] 6.1.3 Run permission migrations
    - Execute: `php artisan migrate`
    - Verify new tables created (roles, permissions, model_has_roles, etc.)
  - [ ] 6.1.4 Create admin role and assign to existing users
    - Create database seeder or migration
    - Create "admin" role
    - Assign role to jsiebach@gmail.com
    - Assign role to ksiebach@gmail.com
    - Execute seeder/migration
  - [ ] 6.1.5 Update User model
    - Add Spatie's `HasRoles` trait to User model
    - Test role assignment works
  - [ ] 6.1.6 Configure Filament auth
    - Update Filament panel configuration
    - Add middleware to check for admin role
    - Test auth: user with admin role can access
    - Test auth: user without admin role cannot access
  - [ ] 6.1.7 Write 2-4 focused tests for permission system
    - Test admin role can access Filament panel
    - Test non-admin role is denied access
    - Test role assignment works
    - Skip exhaustive permission testing (keep focused)

**Acceptance Criteria:**
- Spatie Laravel-Permission installed and migrated
- Admin role created
- Both existing admin users have admin role
- Filament requires admin role for access
- Non-admin users cannot access Filament
- 2-4 permission tests pass

**Rollback Plan:**
```bash
php artisan migrate:rollback
composer remove spatie/laravel-permission
# Restore backup if needed
```

---

### Phase 7: User Resource

#### Task Group 7.1: User Resource Implementation
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 6.1
**Estimated Time:** 1 hour
**Risk Level:** Low
**Priority:** High

- [ ] 7.1.0 Complete User resource
  - [ ] 7.1.1 Write 2-4 focused tests for User resource
    - Test user creation with validation
    - Test email uniqueness validation
    - Test password hashing
    - Skip exhaustive field testing
  - [ ] 7.1.2 Generate User resource
    - Execute: `php artisan make:filament-resource User --generate`
    - Review generated resource files
  - [ ] 7.1.3 Configure User resource fields
    - Name field: TextInput, required, max:255
    - Email field: TextInput, required, email, max:254, unique (ignoreRecord on update)
    - Password field: TextInput, password type, min:8, required on create, nullable on update
    - Password field: dehydrateStateUsing with Hash::make()
    - Role field: Select with admin/user options
  - [ ] 7.1.4 Configure User resource table
    - ID column (sortable)
    - Avatar column (Gravatar integration)
    - Name column (sortable, searchable)
    - Email column (sortable, searchable)
    - Role column
  - [ ] 7.1.5 Enable search on User resource
    - Add search to id, name, email fields
    - Test search functionality
  - [ ] 7.1.6 Test User resource CRUD
    - Create new user (test validation)
    - Edit existing user
    - Update password
    - Delete user (with confirmation)
    - Test email uniqueness
    - Test role assignment
  - [ ] 7.1.7 Run User resource tests
    - Execute ONLY the 2-4 tests from 7.1.1
    - Verify critical user operations work
    - Do NOT run full test suite

**Acceptance Criteria:**
- User resource created and visible in Filament
- All fields render correctly
- Validation works (email, password, uniqueness)
- Search works
- CRUD operations work
- Role assignment works
- 2-4 User tests pass

**Rollback Plan:** Delete resource files, continue using Nova for users

---

### Phase 8: Page Resources

#### Task Group 8.1: Base Page Resource Structure
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 7.1
**Estimated Time:** 4-6 hours
**Risk Level:** High
**Priority:** Critical

- [ ] 8.1.0 Complete all 7 Page resources
  - [ ] 8.1.1 Write 2-6 focused tests for Page resources
    - Test HomePage schemaless attribute saving/loading
    - Test image upload to page
    - Test SEO fields save correctly
    - Test conditional Call to Action fields
    - Skip exhaustive testing of all 7 page types
  - [ ] 8.1.2 Create HomePage resource
    - Execute: `php artisan make:filament-resource HomePage`
    - Bind to `App\Models\Pages\HomePage` model
    - Implement common fields: Title (required)
    - Implement SEO Settings section: Meta Title (required), Meta Description (nullable)
    - Implement schemaless content fields: Tagline, Banner (image), Profile Picture (image), Profile Summary (textarea), Bio (markdown)
    - Implement Call to Action section: Add CTA Banner (boolean), CTA (textarea, conditional), Action Link (conditional), Action Text (conditional)
    - Configure conditional field visibility based on CTA boolean
    - Test all fields save to schemaless content column
  - [ ] 8.1.3 Create LabPage resource
    - Execute: `php artisan make:filament-resource LabPage`
    - Bind to `App\Models\Pages\LabPage` model
    - Implement common fields: Title, SEO Settings
    - Implement content fields: Banner (image), Intro (markdown), Lower Content (markdown)
    - Test schemaless attribute saving
  - [ ] 8.1.4 Create ResearchPage resource
    - Execute: `php artisan make:filament-resource ResearchPage`
    - Bind to `App\Models\Pages\ResearchPage` model
    - Implement common fields: Title, SEO Settings
    - Implement content fields: Banner (image), Intro (markdown)
    - Test schemaless attribute saving
  - [ ] 8.1.5 Create PublicationsPage resource
    - Execute: `php artisan make:filament-resource PublicationsPage`
    - Bind to `App\Models\Pages\PublicationsPage` model
    - Implement common fields: Title, SEO Settings
    - No content fields (only relationships)
  - [ ] 8.1.6 Create CvPage resource
    - Execute: `php artisan make:filament-resource CvPage`
    - Bind to `App\Models\Pages\CvPage` model
    - Implement common fields: Title, SEO Settings
    - Implement content fields: CV File (file upload with original filename preservation)
    - Implement custom storeAs callback for filename preservation
    - Test file upload and download
  - [ ] 8.1.7 Create OutreachPage resource
    - Execute: `php artisan make:filament-resource OutreachPage`
    - Bind to `App\Models\Pages\OutreachPage` model
    - Implement common fields: Title, SEO Settings
    - No content fields
  - [ ] 8.1.8 Create PhotographyPage resource
    - Execute: `php artisan make:filament-resource PhotographyPage`
    - Bind to `App\Models\Pages\PhotographyPage` model
    - Implement common fields: Title, SEO Settings
    - Implement content fields: Flickr Album (text)
  - [ ] 8.1.9 Test schemaless attributes across all pages
    - Visit each page in Filament
    - Verify existing data loads correctly
    - Edit content fields
    - Save changes
    - Verify data persists to content JSON column
    - Compare with Nova to ensure data matches
  - [ ] 8.1.10 Configure file storage
    - Verify all image/file uploads use 'public' disk
    - Test image uploads persist to storage/app/public
    - Test existing uploaded files are accessible
    - Verify file URLs work correctly
  - [ ] 8.1.11 Run Page resource tests
    - Execute ONLY the 2-6 tests from 8.1.1
    - Verify critical page operations work
    - Do NOT run full test suite

**Acceptance Criteria:**
- All 7 page resources created
- All common fields (Title, SEO) work on all pages
- All page-specific content fields work
- Schemaless attributes save/load correctly
- Image uploads work and display previews
- File uploads work (CV page)
- Conditional fields work (HomePage CTA)
- All existing page data accessible
- 2-6 Page tests pass

**Rollback Plan:** Delete resource files, continue using Nova for pages

**High-Risk Item:** Schemaless attribute handling - test early and thoroughly

---

### Phase 9: Content Resources & Relation Managers

#### Task Group 9.1: Page-Specific Content Resources
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 8.1
**Estimated Time:** 4-6 hours
**Risk Level:** High
**Priority:** Critical

- [ ] 9.1.0 Complete all content resources and relation managers
  - [ ] 9.1.1 Write 2-6 focused tests for content resources
    - Test Team Member sortable drag-and-drop
    - Test Research Project creation via relation manager
    - Test Publication date-based sorting
    - Test page_id scoping works correctly
    - Skip exhaustive testing of all 6 content types
  - [ ] 9.1.2 Create TeamMember relation manager for LabPage
    - Execute: `php artisan make:filament-relation-manager LabPageResource teamMembers name`
    - Implement fields: Name (required), Email (required), Alumni (boolean, default false), Bio (markdown, required), Profile Picture (image)
    - Configure table: reorderable by sort_order, default sort by sort_order asc
    - Test drag-and-drop reordering
    - Verify sort_order persists correctly
    - Test page_id scoping (only show team members for this page)
  - [ ] 9.1.3 Create Research relation manager for ResearchPage
    - Execute: `php artisan make:filament-relation-manager ResearchPageResource research project_name`
    - Implement fields: Project Name (required), Description (markdown, required), Image (image)
    - Configure table: reorderable by sort_order, default sort by sort_order asc
    - Test drag-and-drop reordering
    - Verify sort_order persists correctly
    - Test page_id scoping
  - [ ] 9.1.4 Create Publication relation manager for PublicationsPage
    - Execute: `php artisan make:filament-relation-manager PublicationsPageResource publications title`
    - Implement fields: Title (required), Authors (markdown, required), Publication Name (required), Published (boolean with help text), Date Published (date, required, with help text), Abstract (textarea, nullable), DOI (nullable), Link (nullable)
    - Configure table: default sort by date_published desc (auto-sort)
    - **DECISION POINT:** Confirm with user whether to add manual sorting or keep date-based
    - Test auto-sorting by date
    - Test page_id scoping
  - [ ] 9.1.5 Create ScienceAbstract relation manager for PublicationsPage
    - Execute: `php artisan make:filament-relation-manager PublicationsPageResource scienceAbstracts title`
    - Implement fields: Title (required), Link (nullable), Authors (required), Location (required), City State (required), Date (date, required), Details (markdown)
    - Configure table: default sort by date desc (auto-sort)
    - No manual sorting (date-based only)
    - Test auto-sorting by date
    - Test page_id scoping
  - [ ] 9.1.6 Create Press relation manager for HomePage
    - Execute: `php artisan make:filament-relation-manager HomePageResource press title`
    - Implement fields: Title (required), Link (required), Date (date, required)
    - Configure table: default sort by date desc (auto-sort)
    - **DECISION POINT:** Confirm with user whether to add manual sorting or keep date-based
    - Test auto-sorting by date
    - Test page_id scoping
  - [ ] 9.1.7 Create SocialLink relation manager for HomePage
    - Execute: `php artisan make:filament-relation-manager HomePageResource socialLinks title`
    - Implement fields: Icon (required, with FontAwesome v5 help text), Title (required), Link (required)
    - Configure table: reorderable by sort_order, default sort by sort_order asc
    - Test drag-and-drop reordering
    - Verify sort_order persists correctly
    - Test page_id scoping
  - [ ] 9.1.8 Test all sortable functionality
    - Test Team Members drag-and-drop on Lab page
    - Test Research Projects drag-and-drop on Research page
    - Test Social Links drag-and-drop on Home page
    - Verify sort_order values update correctly
    - Compare sort_order with documented original values
  - [ ] 9.1.9 Test all date-sorted functionality
    - Test Publications sort by date_published desc
    - Test Science Abstracts sort by date desc
    - Test Press sort by date desc
    - Verify newest items appear first
  - [ ] 9.1.10 Test CRUD operations in relation managers
    - Create new record via relation manager
    - Edit existing record via relation manager
    - Delete record via relation manager
    - Verify page_id is automatically set on create
    - Verify records are scoped to correct page
  - [ ] 9.1.11 Run content resource tests
    - Execute ONLY the 2-6 tests from 9.1.1
    - Verify critical content operations work
    - Do NOT run full test suite

**Acceptance Criteria:**
- All 6 content relation managers created
- All fields render correctly in relation managers
- Sortable resources (TeamMember, Research, SocialLink) have working drag-and-drop
- Sort order values persist correctly
- Date-sorted resources (Publication, ScienceAbstract, Press) auto-sort correctly
- Page_id scoping works (records filtered by page)
- CRUD operations work in all relation managers
- All validation works
- 2-6 content resource tests pass

**Rollback Plan:** Delete relation manager files, continue using Nova for content

**High-Risk Item:** Sort order preservation - verify against documented original values

---

### Phase 10: Settings & Dynamic Navigation

#### Task Group 10.1: Settings Implementation
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 9.1
**Estimated Time:** 1 hour
**Risk Level:** Medium
**Priority:** High

- [ ] 10.1.0 Complete Settings page
  - [ ] 10.1.1 Research Filament 4 settings plugin
    - Evaluate: `filament/spatie-laravel-settings-plugin`
    - Evaluate other settings plugins for Filament 4
    - Choose plugin that supports image uploads and code fields
  - [ ] 10.1.2 Install settings plugin
    - Execute: `composer require [chosen-plugin]`
    - Run plugin installation commands
    - Publish plugin configuration
  - [ ] 10.1.3 Create Settings page or configure plugin
    - Create Settings page: `php artisan make:filament-page Settings`
    - OR configure plugin settings
  - [ ] 10.1.4 Implement settings fields
    - Favicon (FileUpload, image type, public disk)
    - Tracking Code (Textarea or CodeEditor, rows: 10)
    - Schema Markup (Textarea or CodeEditor, rows: 10)
  - [ ] 10.1.5 Migrate existing settings data
    - Export current values from Nova Settings
    - Import into Filament settings storage
    - Verify values accessible in Filament
  - [ ] 10.1.6 Test settings persistence
    - Update Favicon (upload new image)
    - Update Tracking Code
    - Update Schema Markup
    - Save and reload page
    - Verify values persist
    - Verify favicon displays correctly

**Acceptance Criteria:**
- Settings plugin installed and configured
- All 3 settings fields present and functional
- Existing settings data migrated
- Settings persist across sessions
- Favicon upload works
- Code fields display with proper formatting

**Rollback Plan:** Remove settings files, continue using Nova Settings

#### Task Group 10.2: Dynamic Navigation
**Assigned Role:** filament-specialist
**Dependencies:** Task Group 10.1
**Estimated Time:** 1-2 hours
**Risk Level:** Medium
**Priority:** High

- [ ] 10.2.0 Complete dynamic navigation
  - [ ] 10.2.1 Create FilamentServiceProvider or update panel provider
    - Create service provider if not exists
    - Register in config/app.php
  - [ ] 10.2.2 Implement navigation generation logic
    - In boot() or panel() method, fetch all pages: `Page::all()`
    - Map pages to NavigationItem objects
    - Set item title to page title
    - Set item URL to page edit route
    - Set item icon (e.g., heroicon-o-document-text)
    - Set item group to "Pages"
  - [ ] 10.2.3 Register navigation items
    - Use `Filament::registerNavigationItems()` or panel method
    - Register dynamically generated page items
    - Configure navigation groups: Resources, Pages, Settings
  - [ ] 10.2.4 Configure navigation structure
    - Main dashboard (top-level)
    - Resources group: Users
    - Pages group: All 7 pages (dynamically generated)
    - Settings (top-level or in Settings group)
  - [ ] 10.2.5 Test dynamic navigation
    - Verify all 7 pages appear in sidebar
    - Verify page titles display correctly
    - Click each page link, verify it opens correct page in edit mode
    - Verify navigation groups are organized correctly
    - Verify Settings appears in sidebar
  - [ ] 10.2.6 Test navigation order
    - Verify pages appear in correct order
    - If order is wrong, implement custom ordering logic
    - Test navigation persists across sessions

**Acceptance Criteria:**
- Dynamic navigation implemented
- All 7 pages appear in "Pages" group in sidebar
- Page titles display correctly
- Clicking page link opens that page in edit mode
- Navigation groups organized logically
- Settings accessible from sidebar
- Navigation order matches expected order

**Rollback Plan:** Remove navigation code, continue using Nova navigation

---

### Phase 11: Comprehensive Testing & Data Verification

#### Task Group 11.1: Test Review & Gap Analysis
**Assigned Role:** testing-engineer
**Dependencies:** Task Groups 6.1, 7.1, 8.1, 9.1, 10.1, 10.2
**Estimated Time:** 2-3 hours
**Risk Level:** Medium
**Priority:** Critical

- [ ] 11.1.0 Complete comprehensive testing
  - [ ] 11.1.1 Review all existing tests
    - Review 2-4 permission tests from Task 6.1.7
    - Review 2-4 User resource tests from Task 7.1.1
    - Review 2-6 Page resource tests from Task 8.1.1
    - Review 2-6 Content resource tests from Task 9.1.1
    - Total existing tests: approximately 8-20 tests
  - [ ] 11.1.2 Analyze test coverage gaps
    - Identify critical workflows lacking test coverage
    - Focus on: authentication flow, page editing workflow, content creation workflow
    - Prioritize end-to-end workflows over unit tests
    - Skip: exhaustive field validation, edge cases, performance tests
  - [ ] 11.1.3 Write up to 10 additional strategic tests maximum
    - Test complete page editing workflow (login → edit page → save → verify)
    - Test complete content creation workflow (login → open page → add content → save)
    - Test sortable persistence (reorder → save → reload → verify order)
    - Test file upload workflow (upload → verify storage → verify display)
    - Test settings persistence (update → save → reload → verify)
    - Focus on integration points, not unit tests
    - Do NOT write comprehensive coverage
  - [ ] 11.1.4 Run feature-specific tests only
    - Execute ONLY migration-related tests (from 6.1.7, 7.1.1, 8.1.1, 9.1.1, 11.1.3)
    - Expected total: approximately 18-30 tests maximum
    - Do NOT run entire application test suite
    - Verify critical workflows pass

#### Task Group 11.2: Data Integrity Verification
**Assigned Role:** database-engineer
**Dependencies:** Task Group 11.1
**Estimated Time:** 1-2 hours
**Risk Level:** High
**Priority:** Critical

- [ ] 11.2.0 Verify data integrity
  - [ ] 11.2.1 Record count verification
    - Count users in Nova vs Filament
    - Count pages in Nova vs Filament (should be 7)
    - Count team members in Nova vs Filament
    - Count research projects in Nova vs Filament
    - Count publications in Nova vs Filament
    - Count science abstracts in Nova vs Filament
    - Count press items in Nova vs Filament
    - Count social links in Nova vs Filament
    - All counts must match exactly
  - [ ] 11.2.2 Sort order verification
    - Query team_members table, compare sort_order values with documented original values
    - Query research table, compare sort_order values with documented original values
    - Query social_links table, compare sort_order values with documented original values
    - All sort_order values must match exactly (zero drift)
  - [ ] 11.2.3 File path verification
    - Query all image/file fields in database
    - Verify all file paths still point to existing files
    - Test file access for 5-10 random uploads
    - Verify images display in Filament
    - Verify files download correctly
  - [ ] 11.2.4 Settings verification
    - Compare Nova Settings values with Filament Settings values
    - Verify Favicon, Tracking Code, Schema Markup all match
    - Test settings display correctly
  - [ ] 11.2.5 Relationship integrity verification
    - Verify all team members have correct page_id (LabPage)
    - Verify all research projects have correct page_id (ResearchPage)
    - Verify all publications have correct page_id (PublicationsPage)
    - Verify all science abstracts have correct page_id (PublicationsPage)
    - Verify all press items have correct page_id (HomePage)
    - Verify all social links have correct page_id (HomePage)
  - [ ] 11.2.6 Schemaless attribute verification
    - For each of 7 pages, compare content JSON in database
    - Verify all schemaless fields accessible in Filament
    - Spot-check 3-5 pages in detail

#### Task Group 11.3: Manual Testing Checklist
**Assigned Role:** testing-engineer
**Dependencies:** Task Group 11.2
**Estimated Time:** 1-2 hours
**Risk Level:** Low
**Priority:** High

- [ ] 11.3.0 Complete manual testing checklist
  - [ ] 11.3.1 Authentication & Authorization
    - Login with admin role (should succeed)
    - Attempt login without admin role (should fail)
    - Logout works
    - Session persists correctly
  - [ ] 11.3.2 User Resource
    - List view displays correctly
    - Search by name works
    - Search by email works
    - Create user (test validation)
    - Edit user
    - Update password (verify hashed)
    - Delete user (with confirmation)
    - Role assignment works
  - [ ] 11.3.3 Page Resources (test each of 7 pages)
    - HomePage: Edit all fields, verify schemaless saves
    - LabPage: Edit all fields, verify schemaless saves
    - ResearchPage: Edit all fields, verify schemaless saves
    - PublicationsPage: Edit meta fields
    - CvPage: Upload CV file, verify download works
    - OutreachPage: Edit meta fields
    - PhotographyPage: Edit Flickr Album field
  - [ ] 11.3.4 Content Resources (test in relation managers)
    - Team Members: Create, edit, delete, reorder (drag-and-drop)
    - Research Projects: Create, edit, delete, reorder (drag-and-drop)
    - Publications: Create, edit, delete, verify date sorting
    - Science Abstracts: Create, edit, delete, verify date sorting
    - Press: Create, edit, delete, verify date sorting
    - Social Links: Create, edit, delete, reorder (drag-and-drop)
  - [ ] 11.3.5 Settings
    - Update Favicon (upload new image)
    - Update Tracking Code
    - Update Schema Markup
    - Save and reload
    - Verify persistence
  - [ ] 11.3.6 Navigation
    - All 7 pages appear in sidebar
    - Clicking each opens correct page
    - Navigation groups organized correctly
    - Settings accessible
  - [ ] 11.3.7 Performance Check
    - List views load in < 2 seconds
    - Edit views load in < 2 seconds
    - No visible N+1 query warnings
    - Image uploads complete successfully
  - [ ] 11.3.8 Error Handling
    - Form validation displays clear errors
    - Invalid data rejected
    - No PHP errors in logs
    - No JavaScript console errors

**Acceptance Criteria:**
- All feature-specific tests pass (18-30 tests)
- All record counts match between Nova and Filament
- All sort_order values preserved exactly
- All file paths still valid and accessible
- Settings values migrated correctly
- All relationships intact
- All schemaless attributes accessible
- Manual testing checklist 100% complete
- No critical errors in logs

**Rollback Plan:** If critical data integrity issues found, restore from backup and investigate

**High-Risk Item:** This is the final checkpoint before removing Nova - must be perfect

---

### Phase 12: Remove Nova & Deploy

#### Task Group 12.1: Nova Removal
**Assigned Role:** backend-engineer
**Dependencies:** Task Group 11.3
**Estimated Time:** 30 minutes
**Risk Level:** Low (if testing passed)
**Priority:** High

- [ ] 12.1.0 Remove Nova completely
  - [ ] 12.1.1 Take final backup
    - Database: `mysqldump -u root -p kirstensiebach > final_backup.sql`
    - Git: Commit all current changes
    - Storage: Already backed up in Phase 1
  - [ ] 12.1.2 Remove Nova from composer.json
    - Remove `"laravel/nova": "4.33.3"`
    - Remove `"outl1ne/nova-sortable": "^3.4"`
    - Remove `"outl1ne/nova-settings": "^5.1"`
    - Remove `"vmitchell85/nova-links": "^2.1"`
    - Remove Nova repository URL from repositories section
  - [ ] 12.1.3 Execute composer remove
    - Execute: `composer remove laravel/nova outl1ne/nova-sortable outl1ne/nova-settings vmitchell85/nova-links`
    - Verify packages removed successfully
  - [ ] 12.1.4 Delete Nova files
    - Execute: `rm -rf app/Nova`
    - Execute: `rm -f app/Providers/NovaServiceProvider.php`
    - Execute: `rm -f config/nova.php`
  - [ ] 12.1.5 Update config/app.php
    - Remove `App\Providers\NovaServiceProvider::class` from providers array
    - Verify no other Nova references
  - [ ] 12.1.6 Clear all caches
    - Execute: `php artisan config:clear`
    - Execute: `php artisan cache:clear`
    - Execute: `php artisan route:clear`
    - Execute: `php artisan view:clear`
    - Execute: `composer dump-autoload`
  - [ ] 12.1.7 Test application without Nova
    - Start server: `php artisan serve`
    - Visit /admin (should 404 or redirect)
    - Visit /filament (should work)
    - Test all Filament resources
    - Verify no errors in logs
    - Verify no Nova references in code

**Acceptance Criteria:**
- All Nova packages removed from composer.json
- All Nova files deleted
- No Nova references in config
- Application runs without errors
- /filament works perfectly
- No Nova code remains

**Rollback Plan:**
```bash
git checkout pre-migration-backup
composer install
# Restore database if needed
```

#### Task Group 12.2: Final Testing & Deployment
**Assigned Role:** devops-engineer
**Dependencies:** Task Group 12.1
**Estimated Time:** 2-3 hours
**Risk Level:** Medium
**Priority:** Critical

- [ ] 12.2.0 Deploy to production
  - [ ] 12.2.1 Final local testing
    - Complete all manual tests again (from 11.3.0)
    - Test all resources
    - Test all CRUD operations
    - Test sortable functionality
    - Test file uploads
    - Verify no errors
  - [ ] 12.2.2 Update documentation
    - Update README with new admin URL (/filament)
    - Document new login process
    - Document role-based access
    - Update deployment instructions
  - [ ] 12.2.3 Update environment configuration
    - Update .env.example with Filament config
    - Remove Nova environment variables
    - Document any new environment variables
  - [ ] 12.2.4 Deploy to staging (if available)
    - Push to staging branch
    - Run migrations on staging
    - Test on staging environment
    - Get stakeholder approval
  - [ ] 12.2.5 Production deployment preparation
    - Create final database backup on production
    - Document rollback procedure
    - Schedule maintenance window (if needed)
    - Notify admin users of change
  - [ ] 12.2.6 Production deployment
    - Merge to main: `git checkout main && git merge laravel-12-filament-migration`
    - Push to production: `git push origin main`
    - On production server:
      - `git pull origin main`
      - `composer install --no-dev --optimize-autoloader`
      - `php artisan migrate --force` (if any new migrations)
      - `php artisan config:cache`
      - `php artisan route:cache`
      - `php artisan view:cache`
  - [ ] 12.2.7 Post-deployment verification
    - Test admin login on production
    - Verify all resources accessible
    - Test CRUD operations on 2-3 resources
    - Verify file uploads work
    - Check error logs for issues
    - Monitor for 1-2 hours
  - [ ] 12.2.8 Monitor post-deployment (24-48 hours)
    - Check error logs daily
    - Monitor user feedback
    - Address any urgent issues
    - Document any unexpected behavior

**Acceptance Criteria:**
- Application deployed to production successfully
- All resources accessible
- Admin users can login and edit content
- No critical errors in production logs
- Documentation updated
- Stakeholders notified

**Rollback Plan (Production):**
```bash
git checkout [previous-commit]
git push origin main --force
# On production:
git pull origin main
composer install
mysql -u user -p database < final_backup.sql
php artisan config:clear
php artisan cache:clear
```

---

## Summary Statistics

- **Total Phases:** 12
- **Total Task Groups:** 16
- **Total Tasks:** 150+
- **Estimated Time:** 18-28 hours
- **Tests Written:** Maximum 18-34 tests (highly focused)
- **Resources Created:** 9 Filament resources, 6 relation managers, 1 settings page
- **Risk Level:** Medium to High (schemaless attributes, sort order preservation)

---

## Critical Success Factors

1. **Data Integrity:** Zero data loss, exact sort order preservation
2. **Feature Parity:** All Nova features replicated in Filament
3. **Testing:** All 18-34 feature tests pass
4. **Performance:** Admin panel loads in < 2 seconds
5. **Clean Removal:** No Nova code remains after Phase 12

---

## High-Risk Items & Mitigation

### Risk 1: Schemaless Attribute Handling (HIGH)
- **Mitigation:** Test HomePage schemaless fields early in Phase 8
- **Fallback:** Custom field wrappers if needed
- **Verification:** Compare content JSON in database before/after

### Risk 2: Sort Order Preservation (HIGH)
- **Mitigation:** Document all sort_order values in Phase 1, verify in Phase 11
- **Fallback:** Database restore if values drift
- **Verification:** SQL query comparison with original documentation

### Risk 3: File Path Changes (MEDIUM)
- **Mitigation:** Configure Filament to use identical disk/path structure
- **Fallback:** Database update to correct paths if needed
- **Verification:** Test file access for random sample of uploads

### Risk 4: STI Pattern with Filament (MEDIUM)
- **Mitigation:** Use separate resources per page type (matches Nova pattern)
- **Fallback:** Consolidate to single resource with conditional fields
- **Verification:** Test all 7 page types individually

---

## Open Questions for Implementation

1. **Publications Sorting:** Add manual sort_order column or keep date-based auto-sorting?
2. **Press Sorting:** Add manual sort_order column or keep date-based auto-sorting?
3. **Inertia.js:** Keep or remove after Nova removal? (check if used elsewhere)

---

## Key Files Reference

### Files to Create
- `app/Filament/Resources/UserResource.php`
- `app/Filament/Resources/HomePageResource.php`
- `app/Filament/Resources/LabPageResource.php`
- `app/Filament/Resources/ResearchPageResource.php`
- `app/Filament/Resources/PublicationsPageResource.php`
- `app/Filament/Resources/CvPageResource.php`
- `app/Filament/Resources/OutreachPageResource.php`
- `app/Filament/Resources/PhotographyPageResource.php`
- `app/Filament/Pages/Settings.php`
- 6 relation manager files

### Files to Modify
- `composer.json` - Update dependencies
- `config/app.php` - Remove NovaServiceProvider
- `.env` - Update admin panel config
- `app/Models/User.php` - Add HasRoles trait

### Files to Delete (Phase 12)
- `app/Nova/*` (entire directory)
- `app/Providers/NovaServiceProvider.php`
- `config/nova.php`

### Files to Reference
- All model files for field definitions
- `app/Providers/NovaServiceProvider.php` (before deletion) for patterns
- Nova resource files (before deletion) for validation rules

---

## Testing Focus

- **Maximum tests per phase:** 2-8 tests (highly focused)
- **Total feature tests:** 18-34 tests maximum
- **Test focus:** Critical workflows, integration points, data integrity
- **Test exclusions:** Edge cases, exhaustive coverage, performance tests
- **Verification:** Run ONLY feature-specific tests, not entire test suite

---

## Timeline Recommendation

### Day 1 (4-6 hours)
- Phases 1-5: Preparation, PHP upgrade, Laravel upgrades, Filament install

### Day 2 (6-8 hours)
- Phases 6-8: Permissions, User resource, all Page resources

### Day 3 (6-8 hours)
- Phase 9: All content resources and relation managers

### Day 4 (4-6 hours)
- Phases 10-12: Settings, navigation, testing, Nova removal, deployment

**Total:** 20-28 hours across 4 days
