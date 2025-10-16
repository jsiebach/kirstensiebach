# Spec Requirements: Laravel 12 & Filament 4 Migration

## Initial Description
Upgrade the application from Laravel 10 to Laravel 12, and replace Laravel Nova 4.33 with Laravel Filament 4 for the admin panel. This is a foundational migration that will enable better content management capabilities and remove the dependency on the Nova license.

Key components:
- Laravel Framework upgrade: 10.x → 12.x
- Admin panel replacement: Nova 4.33 → Filament 4
- Remove all Nova-specific packages and dependencies
- Ensure PHP 8.4 compatibility
- Maintain existing functionality while preparing for new features

## Requirements Discussion

### First Round Questions

**Q1: What is the current PHP version, and do you need to upgrade to PHP 8.4 as part of this migration?**
**Answer:** Currently on PHP 8.0. Need to upgrade to PHP 8.4 for Laravel 12 compatibility.

**Q2: For the navigation structure in Filament, I see two options:**
- Option A: Keep pages as a flat list in the sidebar (like current Nova setup with Home, Lab, Research, etc.)
- Option B: Group related items (e.g., "Content Management" group with pages, "Research" group with projects/publications)

**Which navigation structure would you prefer, or should we follow the current Nova structure exactly?**

**Answer:** Prefer flat top-level resources with each page having a direct sidebar link. If Filament can organize sub-resources on specific pages (identified by slug like in Nova), that would be better UX. Use logical groupings (e.g., Pages group, Content group, Research group).

**Q3: I notice Team Members, Research Projects, and Publications appear to have sortable functionality in the screenshots. Are there other resources that also need drag-and-drop sorting capability?**

**Answer:** Check Nova code to identify all resources with sortable functionality. Already confirmed: Team Members, Research Projects, Publications. Need to scan codebase for other sortable resources.

**Q4: For the page-specific sub-resources (like Press items on Home page, Team Members on Lab page), are these truly page-specific relationships, or should they be global resources that can be filtered by page?**

**Answer:** YES - these are page-specific relationships. Example: Press items only appear on specific pages (not global). This feature doesn't have to be retained, but currently that's how it works. Sub-resources were identified by page slug in Nova code.

**Q5: Should we replicate all the field types I see in the screenshots (Markdown, Image uploads, Date pickers, Boolean toggles, etc.)?**

**Answer:** YES - Replicate all field types observed in screenshots.

**Q6: For user permissions, I see the Nova gate checks for specific emails. Should we:**
- Keep the simple email whitelist approach?
- Implement proper role-based permissions (admin role for certain users)?
- Or something else?

**Answer:** Implement proper role-based permissions with admin role for students (email whitelist approach is temporary).

**Q7: Should we maintain the existing data during migration, or is this a fresh start?**

**Answer:** Must maintain all existing data. This is a platform upgrade, not a rebuild.

**Q8: Are there any Nova features currently in use that you definitely want to keep, improve, or remove?**

**Answer:** Keep all current features. The dynamic sidebar with page links is important. Settings functionality must be retained.

**Q9: Is there anything explicitly OUT OF SCOPE for this migration that we should avoid or plan for a future phase?**

**Answer:** Keep scope focused on feature parity with current Nova setup. New features come later.

### Existing Code to Reference

**Similar Features Identified:**
User mentioned checking Nova resource files in `app/Nova/` directory to understand all patterns and relationships.

**Components to potentially reuse:**
- Page model structure and relationships
- Sortable trait implementation (Spatie EloquentSortable)
- HasMany relationships for page-specific sub-resources
- NovaSettings pattern for global settings

**Backend logic to reference:**
- Nova resource definitions for field types and validation
- NovaServiceProvider for dynamic sidebar generation
- Gate definitions for access control
- Model relationships and scopes

### Follow-up Questions

**Follow-up 1: I found that Press items, Social Links, Team Members, and Research projects all use the Spatie sortable package. Should Publications and Science Abstracts also be sortable, or should they remain sorted by date (as shown in the models)?**

**Answer:** [Pending - will be addressed in implementation phase based on Nova resource traits]

### Visual Assets

#### Files Provided:
- `Screenshot 2025-10-14 at 5.58.21 AM.png`: Main Nova dashboard showing Users resource with navigation sidebar (Main, Resources, Pages submenu, Settings)
- `Screenshot 2025-10-14 at 5.58.33 AM.png`: Home Page details view showing SEO Settings panel, Call to Action panel, Content panel with fields
- `Screenshot 2025-10-14 at 5.58.40 AM.png`: Home Page with Press sub-resource table (sortable) and Social Links sub-resource, showing page-specific relationships
- `Screenshot 2025-10-14 at 5.58.47 AM.png`: Lab Page showing Banner, Intro, Team Members (sortable with drag handles), Lower Content fields
- `Screenshot 2025-10-14 at 5.58.58 AM.png`: Research Page showing sortable Research Projects with drag-and-drop handles
- `Screenshot 2025-10-14 at 5.59.08 AM.png`: Publications Page showing Publications list (sortable table) and Science Abstracts section
- `Screenshot 2025-10-14 at 5.59.22 AM.png`: CV Page detail showing simple file upload field

#### Visual Insights:
- Navigation uses collapsible groups (Resources, Pages) with Nova's dark theme
- Pages section shows all pages as direct links (Home, Lab, Research, Publications, CV, Speaking & Outreach, Photography)
- Each page has consistent structure: Title field + SEO Settings panel + optional Call to Action panel + Content panel
- Sub-resources appear as HasMany tables within page detail views
- Sortable resources show hamburger/drag icons in leftmost column
- All resources have consistent action buttons (view, edit, delete icons)
- Boolean fields show as toggle/checkbox with red/green status indicators
- Image fields show upload interface with download links
- Settings appears as separate sidebar item
- Fidelity level: High-fidelity screenshots of actual production Nova interface

## Requirements Summary

### Functional Requirements

#### Core Admin Panel Features
1. **User Management**
   - Users resource with ID, Avatar (Gravatar), Name, Email
   - Password management (creation and updates)
   - Email uniqueness validation
   - Search capability on id, name, email
   - Role-based access control (admin role)

2. **Page Management**
   - 7 page types with Single Table Inheritance (STI) pattern:
     - HomePage (slug: home)
     - LabPage (slug: lab)
     - ResearchPage (slug: research)
     - PublicationsPage (slug: publications)
     - CvPage (slug: cv)
     - OutreachPage (slug: outreach)
     - PhotographyPage (slug: photography)
   - Each page has:
     - Title field (required)
     - SEO Settings panel (Meta Title, Meta Description)
     - Content panel (page-specific fields)
     - Optional Call to Action panel (HomePage only currently)

3. **Content Resources (Page-Specific)**
   - **Team Members** (belongs to LabPage):
     - Fields: Name, Email, Alumni (boolean), Bio (markdown), Profile Picture (image)
     - Sortable via drag-and-drop
     - sort_order column

   - **Research Projects** (belongs to ResearchPage):
     - Fields: Project Name, Description (markdown), Image
     - Sortable via drag-and-drop
     - sort_order column

   - **Publications** (belongs to PublicationsPage):
     - Fields: Title, Authors (markdown), Publication Name, Published (boolean), Date Published (date), Abstract (textarea), DOI, Link
     - Currently sorted by date_published (desc) via global scope
     - Sortable interface in Nova

   - **Science Abstracts** (belongs to PublicationsPage):
     - Fields: Title, Link, Authors, Location, City State, Date, Details (markdown)
     - Sorted by date (desc) via global scope

   - **Press** (belongs to HomePage):
     - Fields: Title, Link, Date
     - Sorted by date (desc) via global scope
     - Sortable interface in Nova

   - **Social Links** (belongs to HomePage):
     - Fields: Icon (FontAwesome v5), Title, Link
     - Sortable via drag-and-drop
     - sort_order column

4. **Global Settings**
   - Favicon (image upload)
   - Tracking Code (code field)
   - Schema Markup (code field)
   - Stored using spatie/laravel-schemaless-attributes pattern

5. **Navigation & Sidebar**
   - Dynamic sidebar generation based on Page records
   - Grouped navigation structure:
     - Main dashboard
     - Resources group (Users)
     - Pages group (all 7 pages as direct links)
     - Settings
   - Each page link should open that page's detail view in edit mode

#### Field Types Required
- Text (single line)
- Textarea (multi-line)
- Markdown (rich text editor)
- Boolean (toggle/checkbox)
- Date (date picker)
- Image (file upload with preview and download)
- File (document upload)
- Code (syntax highlighted code editor)
- HasMany (embedded related resource tables)

#### Sortable Functionality
Based on codebase analysis, the following resources use Spatie EloquentSortable:
- Team Members (sort_order column, page-scoped)
- Research Projects (sort_order column, page-scoped)
- Social Links (sort_order column, page-scoped)
- Press (sortable UI in Nova, but uses date ordering in model)
- Publications (sortable UI in Nova, but uses date_published ordering in model)

**Implementation Note:** Need to clarify in implementation phase whether Press, Publications, and Science Abstracts should:
- Add sort_order columns and SortableTrait for manual ordering
- OR remain with date-based automatic sorting
- Current Nova interface shows sortable handles for all, but models have date-based global scopes

#### Database Relationships
- **Pages Table**: STI base table with type column
  - Uses slug for identification (unique)
  - Uses content column (JSON/schemaless) for page-specific fields
  - Relations: hasMany for page-specific resources

- **Page-Specific Resources**: All have page_id foreign key
  - Belong to specific page via page_id
  - Filtered by page relationship in admin interface
  - Some have sort_order, some have date-based ordering

- **Users Table**: Standard Laravel auth users
  - No role column currently (uses email whitelist in Gate)
  - Needs role/permission structure added

### Non-Functional Requirements

#### Performance
- Must handle existing data volume efficiently
- Lazy loading for images and file uploads
- Pagination for resource lists (current Nova uses "simple" pagination)

#### Security
- Replace email whitelist Gate with proper role-based access control
- Maintain existing authentication system
- Secure file uploads (existing disk: 'public')
- CSRF protection on all forms

#### Compatibility
- PHP 8.4 compatibility
- Laravel 12.x compatibility
- MySQL database (no change)
- Existing public disk storage (no change)

#### Data Integrity
- Zero data loss during migration
- Maintain all existing relationships
- Preserve sort_order values for sortable resources
- Preserve schemaless content attributes on pages

### Reusability Opportunities

#### Existing Patterns to Preserve
1. **STI Pattern for Pages**: Single pages table with type column and schemaless content
2. **Sortable Pattern**: Spatie EloquentSortable with page-scoped sorting
3. **Schemaless Attributes**: Using Spatie package for flexible page content
4. **HasSlug Trait**: Pages identified by slug (home, lab, research, etc.)
5. **Global Scopes**: Date-based ordering for Press, Publications, Science Abstracts

#### Models to Reference
- `app/Models/Page.php` - Base page model with schemaless content
- `app/Models/TeamMember.php` - Example of sortable + page relationship
- `app/Models/Pages/HomePage.php` - Example of STI child with relationships
- `app/Models/Press.php` - Example of date-scoped ordering

#### Packages to Migrate/Replace
- `outl1ne/nova-sortable` → Filament sortable plugin
- `outl1ne/nova-settings` → Filament settings plugin
- `vmitchell85/nova-links` → Custom Filament navigation
- `mdixon18/fontawesome` → Keep for icon references
- Keep: `spatie/eloquent-sortable`, `spatie/laravel-schemaless-attributes`

### Scope Boundaries

#### In Scope
- Complete Laravel 10 → 12 upgrade
- Complete Nova 4.33 → Filament 4 replacement
- PHP 8.0 → 8.4 upgrade
- Replicate all existing Nova resources in Filament
- Replicate all field types and validation rules
- Maintain all existing data and relationships
- Implement role-based permissions (replacing email whitelist)
- Dynamic navigation based on pages
- Settings management
- Sortable drag-and-drop for applicable resources
- Page-specific sub-resource management
- SEO fields for all pages
- File/image upload functionality

#### Out of Scope
- New features beyond Nova parity
- UI/UX redesign (keep similar to Nova's aesthetic)
- Changes to frontend (this is admin-only)
- Changes to database schema (except adding roles/permissions tables if needed)
- API additions
- Additional resource types not in current Nova setup
- Third-party integrations
- Email/notification system enhancements
- Advanced reporting or analytics

#### Future Enhancements (Not in This Spec)
- Two-factor authentication
- Activity logging/audit trail
- Advanced search and filtering
- Bulk actions beyond delete
- Export/import functionality
- Multi-language support
- Custom dashboard widgets
- Scheduled content publishing

### Technical Considerations

#### Migration Strategy
1. **Framework Upgrade Path**:
   - Upgrade PHP 8.0 → 8.4
   - Upgrade Laravel 10 → 11 → 12 (incremental)
   - Update all dependencies for compatibility
   - Run tests at each step

2. **Nova → Filament Transition**:
   - Install Filament 4 alongside Nova temporarily
   - Build all Filament resources (can reference Nova resources)
   - Test Filament implementation thoroughly
   - Cut over to Filament (remove Nova)
   - Remove Nova packages and config

3. **Data Migration**:
   - No data migration needed (same database)
   - Verify all relationships work in Filament
   - Verify file paths still work
   - Verify settings data accessible in Filament

#### Package Dependencies

**Remove:**
- `laravel/nova: 4.33.3`
- `outl1ne/nova-sortable`
- `outl1ne/nova-settings`
- `vmitchell85/nova-links`
- Nova repository from composer.json

**Add:**
- `filament/filament: ^4.0`
- Filament sortable plugin (e.g., `filament/spatie-laravel-tags-plugin` or similar)
- Filament settings plugin (compatible with Filament 4)
- Filament media library plugin (for file/image management)

**Keep:**
- `spatie/eloquent-sortable` (still used by models)
- `spatie/laravel-schemaless-attributes` (still used by Page models)
- `spatie/laravel-sitemap`
- `mdixon18/fontawesome` (for icon references in Social Links)
- All other non-Nova packages

#### Configuration Files to Update
- `config/nova.php` → Remove (replace with Filament config)
- `config/auth.php` → Add role/permission guard if needed
- `composer.json` → Update dependencies
- `app/Providers/NovaServiceProvider.php` → Replace with FilamentServiceProvider
- `.env.example` → Remove Nova vars, add Filament vars

#### Code Patterns to Follow

**Resource Definition (Nova → Filament):**
```php
// Nova pattern (reference)
public function fields(NovaRequest $request) {
    return [
        Text::make('Title')->rules('required'),
        Image::make('Banner')->disk('public'),
    ];
}

// Should become Filament pattern
public static function form(Form $form): Form {
    return $form->schema([
        Forms\Components\TextInput::make('title')->required(),
        Forms\Components\FileUpload::make('banner')->disk('public'),
    ]);
}
```

**Navigation (Dynamic Page Links):**
- Current: Uses `vmitchell85/nova-links` package to dynamically generate sidebar
- Filament: Use `Navigation::group()` and dynamically register page resources
- Reference: `app/Providers/NovaServiceProvider.php` lines 40-44

**Settings Pattern:**
- Current: Uses `outl1ne/nova-settings` with `NovaSettings::addSettingsFields()`
- Filament: Use Filament settings plugin with similar field definitions
- Reference: `app/Providers/NovaServiceProvider.php` lines 26-30

**Sortable Pattern:**
- Current: `Outl1ne\NovaSortable\Traits\HasSortableRows` on Nova resources
- Keep: `Spatie\EloquentSortable\SortableTrait` on models
- Filament: Use Filament sortable plugin or reorder action
- Models already implement `Sortable` interface correctly

**Page-Specific Resources:**
- Current: HasMany fields in Nova page resources
- Filament: Use Filament relation managers
- Scope by page_id in relationship
- Reference: `app/Nova/Pages/HomePage.php` HasMany definitions

#### Access Control Migration

**Current (Nova Gate):**
```php
Gate::define('viewNova', function ($user) {
    return in_array($user->email, [
        'jsiebach@gmail.com',
        'ksiebach@gmail.com',
    ]);
});
```

**Target (Role-based):**
- Add roles table and permissions system (e.g., Spatie Laravel-permission)
- Create "admin" role
- Assign admin role to specific users
- Use Filament's built-in auth checks with role middleware
- Keep initial users as admins during migration

#### URL Structure
- Current: `/admin` (from Nova config)
- Target: Keep `/admin` for consistency
- All resource URLs will change from Nova's pattern to Filament's pattern

#### Testing Strategy
1. Manual testing of all resources (create, read, update, delete)
2. Verify sortable functionality works
3. Verify file uploads work
4. Verify settings persist
5. Verify dynamic navigation generates correctly
6. Verify permissions work
7. Test on staging before production

#### Risk Mitigation
- Take full database backup before migration
- Keep Nova code in separate branch until Filament fully validated
- Test sort_order preservation
- Test file path preservation
- Test schemaless content attribute access

### Field Type Mapping (Nova → Filament)

| Nova Field | Filament Component | Notes |
|------------|-------------------|-------|
| Text::make() | TextInput::make() | Standard text input |
| Textarea::make() | Textarea::make() | Multi-line text |
| Markdown::make() | MarkdownEditor::make() | Rich text with markdown |
| Boolean::make() | Toggle::make() or Checkbox::make() | Boolean values |
| Date::make() | DatePicker::make() | Date selection |
| Image::make() | FileUpload::make()->image() | Image upload with preview |
| File::make() | FileUpload::make() | File upload |
| Code::make() | Textarea::make()->rows(10) or custom field | Code editing |
| Gravatar::make() | Custom component or Avatar | User avatar |
| Password::make() | TextInput::make()->password() | Password field |
| HasMany::make() | RelationManager | Embedded related tables |
| ID::make() | Auto-included in Filament | Primary key |

### Validation Rules to Preserve

All validation rules from Nova resources must be replicated in Filament:
- Required fields
- Email validation
- Max length constraints
- Unique constraints (with update exceptions)
- Min length for passwords
- Date validation
- Nullable fields

### Sort Order Implementation Details

**Resources with sort_order column** (needs drag-and-drop):
- Team Members (scoped to page_id)
- Research Projects (scoped to page_id)
- Social Links (scoped to page_id)

**Resources with date-based sorting** (auto-sorted, no manual reordering):
- Press (date desc)
- Publications (date_published desc)
- Science Abstracts (date desc)

**Implementation Decision Needed:**
- Should Press, Publications, and Science Abstracts also get manual sorting (add sort_order)?
- Or keep date-based auto-sorting?
- Nova interface shows drag handles for Press and Publications, but models use date scopes
- This should be clarified during implementation based on user preference

### Settings Migration

**Current Nova Settings:**
- Favicon (image)
- Tracking Code (code)
- Schema Markup (code)

**Storage Method:**
- Uses `outl1ne/nova-settings` which likely stores in database table
- Need to migrate settings data to Filament settings format
- Verify existing settings are accessible after migration

### File Storage

**Current Configuration:**
- Storage disk: 'public'
- Images stored in public/storage
- Files uploaded via Nova use Laravel's standard storage system

**Migration Notes:**
- No change to storage location
- Verify Filament uses same disk configuration
- Verify existing file paths work in Filament
- All file/image fields specify ->disk('public')

## Implementation Notes

### Priority Order
1. Framework upgrade (PHP + Laravel)
2. Install Filament alongside Nova
3. Create basic Filament resources (User, Pages)
4. Implement page-specific resources with relationships
5. Implement sortable functionality
6. Implement settings
7. Implement dynamic navigation
8. Implement access control
9. Remove Nova
10. Final testing

### Key Files to Create/Modify

**New Filament Files:**
- `app/Filament/Resources/UserResource.php`
- `app/Filament/Resources/PageResource.php`
- `app/Filament/Resources/TeamMemberResource.php`
- `app/Filament/Resources/ResearchResource.php`
- `app/Filament/Resources/PublicationResource.php`
- `app/Filament/Resources/ScienceAbstractResource.php`
- `app/Filament/Resources/PressResource.php`
- `app/Filament/Resources/SocialLinkResource.php`
- `app/Filament/Pages/Settings.php`
- `app/Providers/FilamentServiceProvider.php`

**Files to Modify:**
- `composer.json` - Update dependencies
- `config/auth.php` - Add role support
- `.env` - Update admin panel config
- `app/Models/User.php` - Add role relationship if using Spatie permissions

**Files to Remove (after migration):**
- `app/Nova/*` (entire directory)
- `app/Providers/NovaServiceProvider.php`
- `config/nova.php`

### Success Criteria
- All Nova resources accessible in Filament
- All field types working correctly
- All validations working correctly
- Sortable resources function properly
- Settings persist and are editable
- Dynamic navigation works
- Access control works
- No data loss
- File uploads/downloads work
- All existing content visible and editable
