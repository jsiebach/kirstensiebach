# Specification: Laravel 12 & Filament 4 Migration

## Goal
Upgrade the application from Laravel 10 to Laravel 12 and replace Laravel Nova 4.33 with Filament 4 as the admin panel, while maintaining complete feature parity, preserving all existing data and relationships, and implementing role-based permissions to enable student administrators.

## User Stories

### Admin Users
- As an admin user, I want to continue managing all site content through an admin panel so that I can maintain the website without technical knowledge
- As an admin user, I want to reorder content using drag-and-drop so that I can control the display order of team members, research projects, and social links
- As an admin user, I want to manage site-wide settings (favicon, tracking code, schema markup) so that I can control global site configuration
- As an admin user, I want to edit each page's SEO metadata so that I can optimize search engine visibility

### Site Administrator
- As the site owner, I want to assign admin roles to students so that they can help manage site content
- As the site owner, I want all existing content preserved during the upgrade so that no information is lost
- As the site owner, I want to eliminate the Nova license dependency so that I can reduce ongoing software costs

### Developers
- As a developer, I want to work with Laravel 12 and PHP 8.4 so that I can use modern framework features
- As a developer, I want clear resource relationships so that I can understand how pages and content are connected
- As a developer, I want to remove deprecated Nova code so that the codebase is maintainable long-term

## Core Requirements

### Functional Requirements

#### 1. User Management
- Display list of all users with ID, name, email, and avatar (Gravatar)
- Create new users with name, email, and password (minimum 8 characters)
- Edit existing users with ability to update name, email, and role
- Delete users with confirmation
- Email uniqueness validation (except for current user on update)
- Search capability by id, name, and email
- Assign admin role to users for panel access

#### 2. Page Management (7 Page Types)
Each page type uses Single Table Inheritance pattern with the following structure:

**Common Fields (All Pages):**
- Title (required)
- Slug (unique identifier: home, lab, research, publications, cv, outreach, photography)
- SEO Settings Panel:
  - Meta Title (required)
  - Meta Description (optional)

**HomePage (slug: home):**
- Content stored in schemaless JSON attributes:
  - Tagline (text)
  - Banner (image, public disk)
  - Profile Picture (image, public disk)
  - Profile Summary (textarea)
  - Bio (markdown)
  - Add Call to Action Banner (boolean)
  - Call to Action (textarea, conditional on banner boolean)
  - Action Link (text, conditional)
  - Action Text (text, conditional)
- Relationships: Press items, Social Links

**LabPage (slug: lab):**
- Content stored in schemaless JSON attributes:
  - Banner (image, public disk)
  - Intro (markdown)
  - Lower Content (markdown)
- Relationships: Team Members

**ResearchPage (slug: research):**
- Content stored in schemaless JSON attributes:
  - Banner (image, public disk)
  - Intro (markdown)
- Relationships: Research Projects

**PublicationsPage (slug: publications):**
- No custom content fields
- Relationships: Publications, Science Abstracts

**CvPage (slug: cv):**
- Content stored in schemaless JSON attributes:
  - CV File (file upload with original filename preservation)

**OutreachPage (slug: outreach):**
- No custom content fields

**PhotographyPage (slug: photography):**
- Content stored in schemaless JSON attributes:
  - Flickr Album (text)

#### 3. Content Resources (Page-Specific Relationships)

**Team Members (belongs to LabPage):**
- Fields:
  - Name (text, required)
  - Email (text, required)
  - Alumni (boolean, default: false)
  - Bio (markdown, required)
  - Profile Picture (image, public disk)
- Features:
  - Sortable via drag-and-drop (sort_order column)
  - Scoped to page_id
  - Manual ordering takes precedence

**Research Projects (belongs to ResearchPage):**
- Fields:
  - Project Name (text, required)
  - Description (markdown, required)
  - Image (image, public disk)
- Features:
  - Sortable via drag-and-drop (sort_order column)
  - Scoped to page_id
  - Manual ordering takes precedence

**Publications (belongs to PublicationsPage):**
- Fields:
  - Title (text, required)
  - Authors (markdown, required)
  - Publication Name (text, required)
  - Published (boolean, help: "Has this been published, or is it still in review?")
  - Date Published (date, required, help: "Date submitted if not yet published")
  - Abstract (textarea, optional)
  - DOI (text, optional)
  - Link (text, optional)
- Features:
  - Auto-sorted by date_published descending
  - Scoped to page_id
  - No manual reordering (date-based only)

**Science Abstracts (belongs to PublicationsPage):**
- Fields:
  - Title (text, required)
  - Link (text, optional)
  - Authors (text, required)
  - Location (text, required)
  - City State (text, required)
  - Date (date, required)
  - Details (markdown)
- Features:
  - Auto-sorted by date descending
  - Scoped to page_id
  - No manual reordering (date-based only)

**Press (belongs to HomePage):**
- Fields:
  - Title (text, required)
  - Link (text, required)
  - Date (date, required)
- Features:
  - Auto-sorted by date descending
  - Scoped to page_id
  - No manual reordering (date-based only)

**Social Links (belongs to HomePage):**
- Fields:
  - Icon (text, required, with help text referencing FontAwesome v5)
  - Title (text, required)
  - Link (text, required)
- Features:
  - Sortable via drag-and-drop (sort_order column)
  - Scoped to page_id
  - Manual ordering takes precedence

#### 4. Global Settings
- Favicon (image upload)
- Tracking Code (code editor field)
- Schema Markup (code editor field)
- Must be accessible from navigation
- Must persist using existing storage mechanism

#### 5. Navigation Structure
**Dynamic Sidebar Generation:**
- Main dashboard
- Resources group:
  - Users
- Pages group (dynamically generated from Page::all()):
  - Each page appears as direct link with page title
  - Links open page in edit mode
  - Order matches database records
- Settings (as separate top-level item)

#### 6. Access Control & Permissions
- Replace email whitelist gate (`viewNova`) with role-based permissions
- Implement "admin" role
- Only users with admin role can access Filament panel
- Migrate existing admin users (jsiebach@gmail.com, ksiebach@gmail.com) to admin role
- Future-proof for additional roles (student, editor, etc.)

### Non-Functional Requirements

#### Performance
- Maintain current page load performance or improve
- Use efficient queries for sortable resources (no N+1)
- Pagination for all resource lists
- Lazy loading for file/image uploads
- Optimize admin panel for responsive interaction

#### Security
- All routes protected by authentication
- Role-based authorization checks on all admin resources
- CSRF protection on all forms
- Secure file upload validation (file types, sizes)
- Continue using 'public' disk with existing security configuration
- Password hashing using Laravel defaults (bcrypt)

#### Data Integrity
- Zero data loss during migration
- Maintain all existing relationships (page_id foreign keys)
- Preserve exact sort_order values for sortable resources
- Preserve all schemaless content attributes on pages
- Maintain file paths for all uploaded images/documents
- Preserve settings data (favicon, tracking code, schema markup)

#### Compatibility
- PHP 8.4 compatibility (upgrade from 8.0)
- Laravel 12.x compatibility (upgrade from 10.x)
- MySQL database (no changes to database system)
- Public disk storage (no reorganization of storage structure)
- Preserve existing public URLs for uploaded files

#### Maintainability
- Remove all Nova-specific code after successful migration
- Use Filament best practices for resource definitions
- Clear separation between page types
- Well-documented relationship managers
- Follow Laravel conventions for service providers

## Technical Approach

### Architecture Overview

**Migration Strategy:**
1. Upgrade PHP 8.0 → 8.4
2. Upgrade Laravel 10 → 11 → 12 (incremental approach for safety)
3. Install Filament 4 alongside Nova (parallel operation)
4. Build all Filament resources with full feature parity
5. Implement role-based permissions
6. Thoroughly test Filament implementation
7. Remove Nova completely
8. Deploy to production

**STI Pattern Adaptation:**
- Current: Single `pages` table with `type` column (HomePage, LabPage, etc.)
- Challenge: Filament doesn't natively support STI like Nova
- Solution: Create separate Filament resources for each page type, each bound to specific model
- Reasoning: Better matches Nova's child resource approach, simpler implementation, clearer resource definitions

### Database

**Existing Schema (No Changes):**
- `pages` table: id, type, title, slug, meta_title, meta_description, content (JSON)
- `users` table: Standard Laravel auth users table
- `team_members` table: id, page_id, sort_order, name, email, alumni, bio, profile_picture
- `research` table: id, page_id, sort_order, project_name, description, image
- `publications` table: id, page_id, title, publication_name, authors, published, date_published, abstract, link, doi
- `science_abstracts` table: id, page_id, title, link, authors, location, city_state, date, details
- `press` table: id, page_id, title, link, date
- `social_links` table: id, page_id, sort_order, title, link, icon

**New Schema (Permissions):**
- Option A: Add `role` column to users table (simple approach)
  - `users.role` (string, default: 'user', values: 'user', 'admin')
- Option B: Use Spatie Laravel-Permission package (robust approach)
  - `roles` table
  - `permissions` table
  - `model_has_roles` table
  - `role_has_permissions` table

**Recommendation:** Option B (Spatie Laravel-Permission) for future flexibility and standard approach

### API / Backend

**Package Changes:**

**Remove:**
- `laravel/nova: 4.33.3`
- `outl1ne/nova-sortable: ^3.4`
- `outl1ne/nova-settings: ^5.1`
- `vmitchell85/nova-links: ^2.1`
- Remove Nova repository from composer.json

**Add:**
- `filament/filament: ^4.0`
- `spatie/laravel-permission: ^6.0` (for role-based access)
- Settings plugin compatible with Filament 4 (research options)
- Optional: Filament sortable plugin if needed beyond native reorder

**Keep:**
- `spatie/eloquent-sortable: ^2.0` (used by models)
- `spatie/laravel-schemaless-attributes: ^2.0` (used by Page models)
- `spatie/laravel-sitemap: ^7.0` (frontend sitemap)
- `mdixon18/fontawesome: ^0.2.1` (icon references)

**Filament Resources to Create:**
- `app/Filament/Resources/UserResource.php`
- `app/Filament/Resources/HomePageResource.php`
- `app/Filament/Resources/LabPageResource.php`
- `app/Filament/Resources/ResearchPageResource.php`
- `app/Filament/Resources/PublicationsPageResource.php`
- `app/Filament/Resources/CvPageResource.php`
- `app/Filament/Resources/OutreachPageResource.php`
- `app/Filament/Resources/PhotographyPageResource.php`
- `app/Filament/Pages/Settings.php`

**Relation Managers to Create:**
- `HomePageResource/RelationManagers/PressRelationManager.php`
- `HomePageResource/RelationManagers/SocialLinksRelationManager.php`
- `LabPageResource/RelationManagers/TeamMembersRelationManager.php`
- `ResearchPageResource/RelationManagers/ResearchRelationManager.php`
- `PublicationsPageResource/RelationManagers/PublicationsRelationManager.php`
- `PublicationsPageResource/RelationManagers/ScienceAbstractsRelationManager.php`

**Service Providers:**
- Remove: `app/Providers/NovaServiceProvider.php`
- Create: `app/Providers/FilamentServiceProvider.php` (or use default Filament panel provider)
- Update: `config/app.php` (remove NovaServiceProvider registration)

**Dynamic Navigation Implementation:**
```php
// In FilamentServiceProvider or Panel configuration
Filament::serving(function () {
    $pages = Page::all();

    $navigationItems = $pages->map(function ($page) {
        return NavigationItem::make($page->title)
            ->url(route('filament.resources.{page-slug}.edit', $page))
            ->icon('heroicon-o-document-text')
            ->group('Pages');
    });

    Filament::registerNavigationItems($navigationItems->toArray());
});
```

### Frontend (Admin Panel)

**Field Type Mapping (Nova → Filament):**
| Nova Field | Filament Component | Implementation Notes |
|------------|-------------------|----------------------|
| Text | TextInput::make() | Standard text input |
| Textarea | Textarea::make() | Multi-line text, specify rows |
| Markdown | MarkdownEditor::make() | Rich text with markdown preview |
| Boolean | Toggle::make() | Switch UI, stores 0/1 |
| Date | DatePicker::make() | Date selection with picker |
| Image | FileUpload::make()->image()->disk('public') | Image with preview |
| File | FileUpload::make()->disk('public') | Generic file upload |
| Code | Textarea::make()->rows(10) | Code editing, consider syntax highlighting plugin |
| Gravatar | Avatar column | Display only in tables |
| Password | TextInput::make()->password()->dehydrateStateUsing(fn($state) => Hash::make($state)) | Secure password handling |
| HasMany | RelationManager | Embedded related resource tables |

**Sortable Implementation:**
```php
// For Team Members, Research, Social Links
public static function table(Table $table): Table
{
    return $table
        ->reorderable('sort_order')
        ->defaultSort('sort_order', 'asc')
        ->columns([...]);
}
```

**Panels and Field Groups:**
- Use `Section::make('SEO Settings')` for SEO fields on all pages
- Use `Section::make('Content')` for page-specific content fields
- Use `Section::make('Call to Action')` with `visible(fn($record) => $record->add_call_to_action_banner)` for conditional fields

**Form Validation:**
- Replicate all Nova validation rules in Filament:
  - `->required()` for required fields
  - `->email()` for email validation
  - `->maxLength(255)` for max length
  - `->unique(ignoreRecord: true)` for email uniqueness
  - `->minLength(8)` for passwords
  - `->nullable()` for optional fields

### Testing Strategy

**Manual Testing Checklist:**

**Authentication & Authorization:**
- [ ] Login with admin role grants access
- [ ] Login without admin role denies access
- [ ] Logout works correctly
- [ ] Password reset flow works

**User Resource:**
- [ ] List view displays all users with correct columns
- [ ] Search by id, name, email works
- [ ] Create user with validation
- [ ] Edit user preserves data
- [ ] Delete user with confirmation
- [ ] Email uniqueness validation works
- [ ] Password updates work (hashed properly)
- [ ] Role assignment works

**Page Resources (Test Each of 7 Page Types):**
- [ ] Edit view loads with existing data
- [ ] Title and slug display correctly
- [ ] SEO fields (meta_title, meta_description) editable
- [ ] Content fields load from schemaless attributes
- [ ] Content updates save to schemaless attributes
- [ ] Images upload and display correctly
- [ ] File uploads preserve original filenames (CV page)
- [ ] Validation prevents empty required fields
- [ ] Related content displays in relation managers

**Content Resources (Test Each Type):**
- [ ] List view in relation manager displays correctly
- [ ] Records filtered by page_id
- [ ] Create new record within relation manager
- [ ] Edit record within relation manager
- [ ] Delete record with confirmation
- [ ] Sortable resources: drag-and-drop reordering works
- [ ] Sortable resources: sort_order persists after reorder
- [ ] Date-sorted resources: display in correct order
- [ ] All field types render correctly
- [ ] Validation works on all fields

**Settings:**
- [ ] Settings page accessible from navigation
- [ ] Existing settings load correctly
- [ ] Favicon upload works
- [ ] Tracking Code saves and loads
- [ ] Schema Markup saves and loads
- [ ] Settings persist across sessions

**Navigation:**
- [ ] All 7 pages appear in "Pages" group
- [ ] Clicking page link opens edit view
- [ ] Page titles display correctly
- [ ] Navigation groups organized properly
- [ ] Settings appears as separate item

**Data Integrity:**
- [ ] All existing users present
- [ ] All 7 pages present with correct data
- [ ] All team members present with correct sort_order
- [ ] All research projects present with correct sort_order
- [ ] All publications present, sorted by date
- [ ] All science abstracts present, sorted by date
- [ ] All press items present, sorted by date
- [ ] All social links present with correct sort_order
- [ ] All uploaded files accessible at existing paths
- [ ] Settings data migrated correctly

**Performance:**
- [ ] List views load in under 2 seconds
- [ ] No N+1 query warnings in logs
- [ ] Image uploads complete successfully
- [ ] Large markdown fields save without timeout

**Error Handling:**
- [ ] Form validation displays clear error messages
- [ ] 404 errors for invalid routes
- [ ] 403 errors for unauthorized access
- [ ] No PHP errors in logs
- [ ] No JavaScript console errors

### Data Migration Plan

**No Database Migration Required:**
- Same database, same tables, same relationships
- Data remains in place throughout migration
- Filament reads from existing schema

**Pre-Migration Steps:**
1. Full database backup using mysqldump
2. Document current sort_order values for all sortable resources
3. Export current settings (favicon, tracking code, schema markup)
4. List all uploaded file paths
5. Take screenshots of all Nova resources for reference

**During Migration:**
- Nova and Filament run in parallel (different URLs)
- Test Filament thoroughly before removing Nova
- Verify all data accessible in Filament
- Verify file paths work in Filament
- Verify settings load correctly in Filament

**Post-Migration Verification:**
1. Record count comparison (Nova vs Filament)
2. Sort order verification for sortable resources
3. File accessibility check for all uploads
4. Settings values comparison
5. Relationship integrity check
6. Create/update/delete operations test

**Settings Migration:**
- If using nova-settings compatible plugin: automatic
- If using different plugin: manual export/import of 3 settings values
- Test Plan: Load settings page, verify favicon/tracking/schema values match Nova

**Rollback Plan:**
- Keep Nova code in separate git branch until Filament validated
- Database backup available for restoration
- Storage backup available for file restoration
- Can revert composer dependencies to restore Nova

## Reusable Components

### Existing Code to Leverage

**Models (Keep As-Is):**
- `app/Models/Page.php` - Base page model with schemaless attributes pattern
- `app/Models/Pages/*` - All 7 page type models (HomePage, LabPage, etc.)
- `app/Models/TeamMember.php` - Sortable trait implementation reference
- `app/Models/Research.php` - Sortable trait implementation reference
- `app/Models/Publication.php` - Date-based scope reference
- `app/Models/ScienceAbstract.php` - Date-based scope reference
- `app/Models/Press.php` - Date-based scope reference
- `app/Models/SocialLink.php` - Sortable trait implementation reference
- `app/Models/User.php` - Standard Laravel auth user

**Traits:**
- `app/Traits/HasSlug.php` - Slug identification pattern (keep for models)
- `Spatie\EloquentSortable\SortableTrait` - Already on sortable models
- `Spatie\SchemalessAttributes` - Already on Page model

**Patterns to Reference:**
- Schemaless content attributes: `$contentAttributes` array on page models
- Page identification by slug: `Page::whereSlug('home')->first()`
- Sortable configuration: `$sortable` array on models
- HasMany relationships scoped to page_id
- Global scopes for date-based ordering
- Gravatar avatar generation for users

**Configuration:**
- `config/filesystems.php` - Public disk configuration (keep)
- `config/auth.php` - Authentication guards (extend for roles)
- `.env` - Database and storage configuration (keep)

### New Components Required

**Filament Resources:**
- All resource classes (listed in API section above)
- Reason: Filament has different resource structure than Nova, cannot reuse Nova resources directly

**Relation Managers:**
- All relation manager classes (listed in API section above)
- Reason: Filament's approach to HasMany relationships uses dedicated relation managers

**Settings Implementation:**
- New Filament settings page or plugin integration
- Reason: Nova Settings package not compatible with Filament

**Navigation Generator:**
- Custom service provider code for dynamic navigation
- Reason: Different navigation API than Nova's Links tool

**Permission System:**
- Spatie Laravel-Permission migrations and configuration
- Reason: Replacing simple gate with robust role system

**Form Schemas:**
- Each resource needs form schema definition using Filament's builder pattern
- Reason: Different field definition API than Nova

## UI/UX Specifications

### Visual Design Reference

**Based on Nova Screenshots:**
- Dark sidebar navigation with collapsible groups
- Clean, modern card-based layouts for forms
- Panel/section organization for related fields
- Inline image previews for uploads
- Toggle switches for boolean fields
- Drag handles for sortable lists
- Action buttons (view, edit, delete) as icons
- Green/red status indicators for boolean values

**Filament Implementation:**
- Use default Filament theme (modern, clean aesthetic)
- Organize fields into logical sections matching Nova panels
- Use Filament's native sortable interface for reorderable resources
- Implement toggle switches for boolean fields
- Use Filament's file upload component with image previews
- Organize navigation with groups matching Nova structure

**Responsive Design:**
- Admin panel must work on desktop (primary use case)
- Mobile responsive for urgent edits
- Touch-friendly drag-and-drop for sortable items
- Responsive tables with horizontal scroll on mobile

**Accessibility:**
- Keyboard navigation for all actions
- Screen reader compatible
- ARIA labels on interactive elements
- Sufficient color contrast
- Focus indicators on interactive elements

### Key UI Interactions

**Sortable Resources:**
- Drag handle icon in leftmost column
- Visual feedback during drag (highlight, cursor change)
- Auto-save sort order on drop
- Immediate UI update without page reload

**Image Uploads:**
- Drag-and-drop upload zone
- Image preview after upload
- Download/delete actions on existing images
- File size/type validation feedback

**Markdown Fields:**
- Live preview or split-pane editor
- Toolbar for common markdown syntax
- Code syntax highlighting for markdown code blocks

**Code Fields (Settings):**
- Syntax highlighting for HTML/JavaScript
- Line numbers
- Monospace font
- Sufficient height (10+ rows)

**Relation Managers:**
- Embedded tables within page edit view
- Create new button above table
- Inline edit/delete actions
- Pagination for large lists
- Empty state message when no records

## Out of Scope

**Explicitly Not Included:**
1. Frontend website changes (this is admin-only migration)
2. Database schema changes (except permissions tables)
3. Storage reorganization (keep public disk as-is)
4. New admin features beyond Nova parity
5. Advanced filtering beyond basic search
6. Bulk actions (except delete)
7. Export/import functionality
8. Activity logging or audit trail
9. Email notification system changes
10. Custom dashboard widgets or metrics
11. Multi-language support for admin panel
12. Two-factor authentication
13. API additions or changes
14. Third-party integrations
15. Scheduled content publishing
16. Content versioning or revision history
17. Block editor for markdown fields (future enhancement, keep basic markdown for now)
18. Media library organization (files stay in current locations)
19. Advanced permissions (page-specific permissions, field-level permissions)
20. Custom themes or branding

## Success Criteria

### Must Have (Launch Blockers)
- [ ] All Laravel 12 features working with PHP 8.4
- [ ] All 7 page types editable with correct fields
- [ ] All 6 content resource types functional with correct relationships
- [ ] User management working with create/edit/delete
- [ ] Role-based permissions implemented (admin role required for access)
- [ ] Settings page functional with all 3 settings (favicon, tracking, schema)
- [ ] Sortable resources (Team Members, Research, Social Links) have working drag-and-drop
- [ ] Date-sorted resources (Publications, Science Abstracts, Press) display in correct order
- [ ] All file uploads work and existing files accessible
- [ ] Dynamic navigation shows all 7 pages
- [ ] Zero data loss (all records present and correct)
- [ ] Exact sort orders preserved for sortable resources
- [ ] Nova completely removed (no traces in code or dependencies)
- [ ] No critical PHP errors in logs
- [ ] No JavaScript console errors

### Should Have (High Priority)
- [ ] Page load times equal to or better than Nova
- [ ] Search functionality on Users resource
- [ ] Clear validation error messages on all forms
- [ ] Confirmation dialogs before delete actions
- [ ] Helpful field help text where needed (e.g., FontAwesome link for icons)
- [ ] Responsive design works on tablets
- [ ] Keyboard navigation works throughout panel

### Nice to Have (Post-Launch)
- [ ] Improved mobile experience beyond basic responsiveness
- [ ] Enhanced markdown editor with better preview
- [ ] Better code editor for settings (syntax highlighting, themes)
- [ ] Quick actions or shortcuts for common tasks
- [ ] Performance optimizations if needed
- [ ] Additional admin users onboarded and trained

### Measurement Criteria
1. **Data Integrity:** 100% of existing records accessible and editable in Filament
2. **Feature Parity:** Every Nova feature replicated in Filament (checklist: 100%)
3. **Performance:** Admin panel page loads < 2 seconds (equal to or better than Nova)
4. **Error Rate:** Zero critical errors in first week post-launch
5. **User Acceptance:** Admin users can complete all tasks without Nova
6. **Code Quality:** Zero Nova dependencies remaining in composer.json

## Risk Assessment

### High-Risk Items

**1. Schemaless Content Attribute Handling**
- Risk: Filament may handle schemaless attributes differently than Nova
- Impact: Page content fields may not save/load correctly
- Mitigation: Early testing of HomePage with all schemaless fields
- Fallback: Custom field wrappers to handle schemaless attribute access

**2. Sort Order Preservation**
- Risk: Sortable implementation may reset or alter existing sort_order values
- Impact: Content displays in wrong order, requiring manual reordering
- Mitigation: Document all current sort orders before migration, verify after
- Fallback: Database backup allows restoration of correct sort_order values

**3. File Path Changes**
- Risk: Filament may store files with different paths than Nova
- Impact: Existing uploaded images/files become inaccessible
- Mitigation: Configure Filament to use exact same disk and path structure
- Fallback: Database update to correct file paths if needed

**4. STI Pattern with Filament**
- Risk: Separate resources per page type may create inconsistencies
- Impact: Some page types may behave differently than others
- Mitigation: Create base page resource class that other page resources extend
- Fallback: Consolidate to single page resource with conditional field visibility

### Medium-Risk Items

**5. Settings Migration**
- Risk: Settings data may not transfer cleanly to new plugin
- Impact: Favicon, tracking code, schema markup lost
- Mitigation: Export settings before migration, manual import if needed
- Fallback: Screenshots of current settings for manual re-entry

**6. Permission System Integration**
- Risk: Role-based permissions may conflict with existing auth
- Impact: Users unable to access panel or wrong access levels
- Mitigation: Test permissions thoroughly on staging with both admin users
- Fallback: Simplified role check in config without full permission package

**7. Dynamic Navigation Implementation**
- Risk: Custom navigation code may not work as expected
- Impact: Pages don't appear in sidebar or links are broken
- Mitigation: Test navigation generation early in development
- Fallback: Static navigation configuration as backup

### Low-Risk Items

**8. Laravel Framework Upgrade**
- Risk: Breaking changes in Laravel 11 or 12
- Impact: Application errors after upgrade
- Mitigation: Follow official upgrade guides, incremental approach (10→11→12)
- Fallback: Well-documented rollback procedure

**9. PHP Version Upgrade**
- Risk: PHP 8.4 compatibility issues with existing code
- Impact: PHP errors, deprecated function warnings
- Mitigation: Test on PHP 8.4 early, address deprecations
- Fallback: Stay on PHP 8.3 if 8.4 causes issues (verify Laravel 12 compatibility)

**10. Filament Learning Curve**
- Risk: Developer unfamiliarity with Filament patterns
- Impact: Slower development, suboptimal implementations
- Mitigation: Reference Filament documentation, community examples
- Fallback: Filament has excellent documentation and active Discord community

### Risk Mitigation Strategy
1. **Parallel Operation:** Keep Nova running until Filament 100% validated
2. **Staging Environment:** Test entire migration on staging before production
3. **Incremental Approach:** Complete one phase at a time with testing
4. **Comprehensive Backups:** Database, storage, and code backups before each major step
5. **Rollback Procedures:** Documented, tested rollback for each phase
6. **Early Testing:** Test high-risk items (schemaless attributes, sortable) early
7. **Stakeholder Communication:** Keep admin users informed of progress and timeline

## Implementation Notes

### Development Phases

**Phase 1: Pre-Migration (1-2 hours)**
- Complete database backup
- Document current sort orders
- Export settings
- Create git branch
- Take reference screenshots

**Phase 2: Framework Upgrade (2-4 hours)**
- Upgrade PHP 8.0 → 8.4
- Upgrade Laravel 10 → 11
- Test application with Laravel 11
- Upgrade Laravel 11 → 12
- Test application with Laravel 12

**Phase 3: Filament Installation (30 minutes)**
- Install Filament 4
- Configure at different URL (/filament)
- Create initial admin user
- Verify both panels accessible

**Phase 4: Permission System (1-2 hours)**
- Install Spatie Laravel-Permission
- Run migrations
- Create admin role
- Assign role to existing admin users
- Configure Filament auth to check role

**Phase 5: User Resource (1 hour)**
- Create UserResource
- Implement all fields
- Add role assignment
- Test CRUD operations

**Phase 6: Page Resources (4-6 hours)**
- Create resources for all 7 page types
- Implement common fields (title, SEO)
- Implement page-specific content fields
- Configure schemaless attribute handling
- Test each page type thoroughly

**Phase 7: Content Resources & Relation Managers (4-6 hours)**
- Create relation managers for all 6 content types
- Implement sortable for applicable resources
- Configure date-based sorting for applicable resources
- Test CRUD operations in relation managers
- Test sortable drag-and-drop
- Verify page_id scoping

**Phase 8: Settings (1 hour)**
- Install/configure Filament settings plugin
- Migrate 3 settings values
- Test settings persistence

**Phase 9: Dynamic Navigation (1-2 hours)**
- Implement navigation generation logic
- Register page links dynamically
- Configure navigation groups
- Test all links work correctly

**Phase 10: Testing & Refinement (2-3 hours)**
- Complete comprehensive testing checklist
- Fix any issues found
- Verify data integrity
- Performance testing
- Get admin user approval

**Phase 11: Remove Nova (30 minutes)**
- Remove Nova packages from composer.json
- Delete app/Nova directory
- Delete NovaServiceProvider
- Delete config/nova.php
- Update .env if needed
- Clear all caches
- Test application without Nova

**Phase 12: Deployment (1-2 hours)**
- Deploy to staging
- Stakeholder testing on staging
- Deploy to production
- Post-deployment verification
- Monitor logs for 24-48 hours

**Total Estimated Time:** 18-28 hours

### Key Files Reference

**Files to Create:**
- `app/Filament/Resources/UserResource.php`
- `app/Filament/Resources/HomePageResource.php`
- `app/Filament/Resources/LabPageResource.php`
- `app/Filament/Resources/ResearchPageResource.php`
- `app/Filament/Resources/PublicationsPageResource.php`
- `app/Filament/Resources/CvPageResource.php`
- `app/Filament/Resources/OutreachPageResource.php`
- `app/Filament/Resources/PhotographyPageResource.php`
- `app/Filament/Pages/Settings.php`
- All relation manager classes (6 total)

**Files to Modify:**
- `composer.json` - Update dependencies
- `config/app.php` - Remove NovaServiceProvider
- `.env` - Remove Nova config, add Filament config if needed
- Database migration for permissions system

**Files to Delete (After Migration):**
- `app/Nova/*` (entire directory)
- `app/Providers/NovaServiceProvider.php`
- `config/nova.php`

**Files to Reference (Keep):**
- All model files for field definitions and relationships
- `app/Providers/NovaServiceProvider.php` (before deletion) for settings and navigation patterns
- Nova resource files (before deletion) for field types and validation rules

### Configuration Reference

**Filament Panel Configuration:**
```php
->authMiddleware(['role:admin'])
->path('admin')  // Keep /admin URL for consistency
->brandName('Site Admin')
```

**Settings Storage:**
- Research compatible Filament settings plugins
- Ensure can access same data structure as Nova Settings
- Verify 3 fields (image, code, code) supported

**Sortable Configuration:**
```php
// Model (already exists, keep)
public $sortable = [
    'order_column_name' => 'sort_order',
    'sort_when_creating' => true,
    'sort_on_has_many' => true,
];

// Filament table
->reorderable('sort_order')
->defaultSort('sort_order', 'asc')
```

### Critical Decisions

**Decision 1: STI Implementation**
- Chosen: Separate Filament resource per page type
- Reasoning: Simpler, clearer, matches Nova child resources
- Alternative: Single resource with conditional fields (more complex)

**Decision 2: Permission System**
- Chosen: Spatie Laravel-Permission package
- Reasoning: Industry standard, flexible, future-proof
- Alternative: Simple role column (less flexible long-term)

**Decision 3: Settings Plugin**
- Decision Needed: Research best Filament 4 settings plugin during implementation
- Requirements: Must support image uploads and code fields
- Alternative: Custom settings page if no suitable plugin

**Decision 4: Laravel Upgrade Path**
- Chosen: Incremental (10→11→12)
- Reasoning: Safer, easier to identify breaking changes
- Alternative: Direct 10→12 (faster but riskier)

**Decision 5: Publication/Press Sorting**
- Clarification Needed: Keep date-based auto-sort or add manual sorting?
- Current: Models use date scopes, Nova shows sortable handles (inconsistent)
- Recommendation: Keep date-based auto-sorting (simpler, logical for dated content)
- User Preference: To be confirmed during implementation

### Support Resources
- Laravel 12 Upgrade Guide: https://laravel.com/docs/12.x/upgrade
- Filament 4 Documentation: https://filamentphp.com/docs/4.x/panels
- Spatie Laravel-Permission: https://spatie.be/docs/laravel-permission
- Filament Discord: https://discord.gg/filamentphp
- Laravel Discord: https://discord.gg/laravel
