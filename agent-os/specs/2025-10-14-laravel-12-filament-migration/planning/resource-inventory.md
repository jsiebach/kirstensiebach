# Complete Nova Resource Inventory

## Overview
This document provides a complete inventory of all Nova resources, their fields, relationships, and special behaviors that must be replicated in Filament.

## Global Resources

### User Resource
**File:** `app/Nova/User.php`
**Model:** `App\Models\User`
**Display In Navigation:** Yes (Resources group)
**Searchable:** Yes (id, name, email)

**Fields:**
- ID (sortable, auto-display)
- Gravatar (avatar, maxWidth: 50)
- Name (text, sortable, required, max:255)
- Email (text, sortable, required, email, max:254, unique on create/update)
- Password (password, forms only, min:8, required on create, nullable on update)

**Special Behaviors:**
- Standard Laravel user model
- Uses Gravatar for avatars
- Email uniqueness validation with exception for current record on update

---

## Page Resources (STI Pattern)

### Base Page Resource
**File:** `app/Nova/Page.php`
**Model:** `App\Models\Page` (abstract base)
**Display In Navigation:** No (children displayed via dynamic links)
**Searchable:** No

**Common Fields (all pages):**
- Title (text, sortable, required)

**Common Panels:**
- SEO Settings Panel:
  - Meta Title (text, required)
  - Meta Description (textarea, nullable)

- Call to Action Panel (conditional, HomePage only):
  - Add Call to Action Banner (boolean)
  - Call to Action (textarea)
  - Action Link (text)
  - Action Text (text)

- Content Panel (page-specific, see below)

**Special Behaviors:**
- Uses Single Table Inheritance (type column)
- Content stored in schemaless JSON column
- Identified by slug (not ID)
- Dynamic sidebar generation via NovaServiceProvider

---

### HomePage Resource
**File:** `app/Nova/Pages/HomePage.php`
**Model:** `App\Models\Pages\HomePage`
**URI Key:** `home`
**Slug:** `home`

**Content Fields:**
- Tagline (text)
- Banner (image, disk: public)
- Profile Picture (image, disk: public)
- Profile Summary (textarea)
- Bio (markdown)

**Relationships:**
- Press (HasMany, sortable)
- Social Links (HasMany, sortable)

**Schemaless Content Attributes:**
```php
[
    'add_call_to_action_banner',
    'call_to_action',
    'action_link',
    'action_text',
    'banner',
    'tagline',
    'profile_picture',
    'profile_summary',
    'bio',
]
```

---

### LabPage Resource
**File:** `app/Nova/Pages/LabPage.php`
**Model:** `App\Models\Pages\LabPage`
**URI Key:** `lab`
**Slug:** `lab`

**Content Fields:**
- Banner (image, disk: public)
- Intro (markdown)
- Lower Content (markdown)

**Relationships:**
- Team Members (HasMany, sortable)

---

### ResearchPage Resource
**File:** `app/Nova/Pages/ResearchPage.php`
**Model:** `App\Models\Pages\ResearchPage`
**URI Key:** `research`
**Slug:** `research`

**Content Fields:**
- Banner (image, disk: public)
- Intro (markdown)

**Relationships:**
- Research Projects (HasMany, sortable)

---

### PublicationsPage Resource
**File:** `app/Nova/Pages/PublicationsPage.php`
**Model:** `App\Models\Pages\PublicationsPage`
**URI Key:** `publications`
**Slug:** `publications`

**Content Fields:**
- None (only relationships)

**Relationships:**
- Publications (HasMany, auto-sorted by date)
- Science Abstracts (HasMany, auto-sorted by date)

---

### CvPage Resource
**File:** `app/Nova/Pages/CvPage.php`
**Model:** `App\Models\Pages\CvPage`
**URI Key:** `cv`
**Slug:** `cv`

**Content Fields:**
- CV File (file upload, preserves original filename)

**Special Behaviors:**
- Custom storeAs callback to preserve original filename

---

### OutreachPage Resource
**File:** `app/Nova/Pages/OutreachPage.php`
**Model:** `App\Models\Pages\OutreachPage`
**URI Key:** `outreach`
**Slug:** `outreach`

**Content Fields:**
- None (empty content fields array)

---

### PhotographyPage Resource
**File:** `app/Nova/Pages/PhotographyPage.php`
**Model:** `App\Models\Pages\PhotographyPage`
**URI Key:** `photography`
**Slug:** `photography`

**Content Fields:**
- Flickr Album (text)

---

## Content Resources (Page-Specific)

### Team Member Resource
**File:** `app/Nova/TeamMember.php`
**Model:** `App\Models\TeamMember`
**Display In Navigation:** No (embedded in LabPage)
**Searchable:** No
**Traffic Cop:** Disabled

**Fields:**
- Name (text, required)
- Email (text, required)
- Alumni (boolean, default: false)
- Bio (markdown, required)
- Profile Picture (image)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- sort_order (integer for sorting)
- name (string)
- title (string, nullable) - Note: Migration 2023_12_29 dropped this
- email (string)
- alumni (integer/boolean, nullable)
- bio (text)
- profile_picture (string)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (LabPage)

**Special Behaviors:**
- Uses `Outl1ne\NovaSortable\Traits\HasSortableRows` (Nova trait)
- Uses `Spatie\EloquentSortable\SortableTrait` (Model trait)
- Implements `Spatie\EloquentSortable\Sortable` interface
- Sortable config:
  - order_column_name: 'sort_order'
  - sort_when_creating: true
  - sort_on_has_many: true
- Scoped to page_id

---

### Research Resource
**File:** `app/Nova/Research.php`
**Model:** `App\Models\Research`
**Display In Navigation:** No (embedded in ResearchPage)
**Searchable:** No
**Traffic Cop:** Disabled
**Label:** "Research Projects"
**URI Key:** `research-project`

**Fields:**
- Project Name (text, required)
- Description (markdown, required)
- Image (image)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- sort_order (integer for sorting)
- project_name (string)
- description (text)
- image (string, nullable)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (ResearchPage)

**Special Behaviors:**
- Uses `Outl1ne\NovaSortable\Traits\HasSortableRows` (Nova trait)
- Uses `Spatie\EloquentSortable\SortableTrait` (Model trait)
- Implements `Spatie\EloquentSortable\Sortable` interface
- Sortable config:
  - order_column_name: 'sort_order'
  - sort_when_creating: true
  - sort_on_has_many: true
- Scoped to page_id

---

### Publication Resource
**File:** `app/Nova/Publication.php`
**Model:** `App\Models\Publication`
**Display In Navigation:** No (embedded in PublicationsPage)
**Searchable:** No
**Traffic Cop:** Disabled
**Label:** "Publications"
**URI Key:** `journal-publications`

**Fields:**
- Title (text, required)
- Authors (markdown, required)
- Publication Name (text, required)
- Published (boolean, help text: "Has this been published, or is it still in review?")
- Date Published (date, required, help text: "Date submitted if not yet published")
- Abstract (textarea, nullable)
- DOI (text, nullable)
- Link (text, nullable)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- title (string)
- publication_name (string)
- authors (text)
- published (integer/boolean)
- date_published (date)
- abstract (text, nullable)
- link (string, nullable)
- doi (string, nullable)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (PublicationsPage)

**Special Behaviors:**
- Uses `Outl1ne\NovaSortable\Traits\HasSortableRows` (Nova trait)
- Model uses global scope: `orderBy('date_published', 'desc')`
- Model casts date_published to date
- **INCONSISTENCY:** Nova shows sortable handles, but model auto-sorts by date
- **DECISION NEEDED:** Should this be manually sortable or auto-sorted by date?

---

### Science Abstract Resource
**File:** `app/Nova/ScienceAbstract.php`
**Model:** `App\Models\ScienceAbstract`
**Display In Navigation:** No (embedded in PublicationsPage)
**Searchable:** No
**Traffic Cop:** Disabled
**Label:** "Abstracts"
**URI Key:** `abstracts`

**Fields:**
- Title (text, required)
- Link (text, nullable)
- Authors (text, required)
- Location (text, required)
- City State (text, required)
- Date (date, required)
- Details (markdown)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- title (string)
- link (string, nullable)
- authors (string)
- location (string)
- city_state (string)
- date (date)
- details (text)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (PublicationsPage)

**Special Behaviors:**
- Model uses global scope: `orderBy('date', 'desc')`
- Model casts date to date
- No manual sorting (date-based only)

---

### Press Resource
**File:** `app/Nova/Press.php`
**Model:** `App\Models\Press`
**Display In Navigation:** No (embedded in HomePage)
**Searchable:** No
**Traffic Cop:** Disabled

**Fields:**
- Title (text, required)
- Link (text, required)
- Date (date, required)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- title (string)
- link (string)
- date (date)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (HomePage)

**Special Behaviors:**
- Uses `Outl1ne\NovaSortable\Traits\HasSortableRows` (Nova trait)
- Model uses global scope: `orderBy('date', 'desc')`
- Model casts date to date
- **INCONSISTENCY:** Nova shows sortable handles, but model auto-sorts by date
- **DECISION NEEDED:** Should this be manually sortable or auto-sorted by date?

---

### Social Link Resource
**File:** `app/Nova/SocialLink.php`
**Model:** `App\Models\SocialLink`
**Display In Navigation:** No (embedded in HomePage)
**Searchable:** No
**Traffic Cop:** Disabled

**Fields:**
- Icon (text, required, help text with FontAwesome v5 link)
- Title (text, required)
- Link (text, required)

**Database Fields:**
- id (primary key)
- page_id (foreign key to pages)
- sort_order (integer for sorting)
- title (string, nullable)
- link (string)
- icon (string)
- created_at, updated_at

**Relationships:**
- Belongs To: Page (HomePage)

**Special Behaviors:**
- Uses `Outl1ne\NovaSortable\Traits\HasSortableRows` (Nova trait)
- Uses `Spatie\EloquentSortable\SortableTrait` (Model trait)
- Implements `Spatie\EloquentSortable\Sortable` interface
- Sortable config:
  - order_column_name: 'sort_order'
  - sort_when_creating: true
  - sort_on_has_many: true
- Scoped to page_id
- Icon field references FontAwesome v5 icons

---

## Settings (Nova Settings Tool)

**Implementation:** `app/Providers/NovaServiceProvider.php` (lines 26-30)

**Fields:**
- Favicon (image)
- Tracking Code (code)
- Schema Markup (code)

**Special Behaviors:**
- Uses `outl1ne/nova-settings` package
- Settings stored globally (not page-specific)
- Accessible via Settings sidebar item

---

## Navigation Structure

**Implementation:** `app/Providers/NovaServiceProvider.php` (lines 38-50)

**Dynamic Sidebar Generation:**
```php
$pages = Page::all();
$sidebarGroup = (new Links('Pages'));
$pages->each(fn ($page) => $sidebarGroup->addLink($page->title, "/resources/{$page->slug}/{$page->id}"));
```

**Current Structure:**
- Main (dashboard)
- Resources
  - Users
- Pages (dynamic group)
  - Home
  - Lab
  - Research
  - Publications
  - CV
  - Speaking & Outreach
  - Photography
- Settings

**Filament Implementation Notes:**
- Need to dynamically generate navigation items based on Page::all()
- Each page link should open that page resource in edit mode
- Pages should be grouped together
- Order should match database records or a specific order

---

## Access Control

**Implementation:** `app/Providers/NovaServiceProvider.php` (lines 82-90)

**Current Gate:**
```php
Gate::define('viewNova', function ($user) {
    return in_array($user->email, [
        'jsiebach@gmail.com',
        'ksiebach@gmail.com',
    ]);
});
```

**Migration Target:**
- Replace with role-based permissions
- Add "admin" role
- Assign to existing admin users
- Use Filament's auth middleware

---

## Dashboard

**File:** `app/Nova/Dashboards/Main.php`

**Current Implementation:**
- Default Nova dashboard
- No custom widgets or metrics

**Filament Implementation:**
- Can start with default Filament dashboard
- Add custom widgets later if needed

---

## Database Tables Reference

### pages
- id
- type (STI discriminator: HomePage, LabPage, etc.)
- title
- slug
- meta_title
- meta_description
- content (JSON/schemaless)
- created_at
- updated_at

### users
- id
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at

### team_members
- id
- page_id
- sort_order
- name
- email
- alumni
- bio
- profile_picture
- created_at
- updated_at

### research (table name)
- id
- page_id
- sort_order
- project_name
- description
- image
- created_at
- updated_at

### publications
- id
- page_id
- title
- publication_name
- authors
- published
- date_published
- abstract
- link
- doi
- created_at
- updated_at

### science_abstracts
- id
- page_id
- title
- link
- authors
- location
- city_state
- date
- details
- created_at
- updated_at

### press
- id
- page_id
- title
- link
- date
- created_at
- updated_at

### social_links
- id
- page_id
- sort_order
- title
- link
- icon
- created_at
- updated_at

---

## Package Dependencies

### Current Nova-Specific Packages (to remove):
- `laravel/nova: 4.33.3`
- `outl1ne/nova-sortable: ^3.4`
- `outl1ne/nova-settings: ^5.1`
- `vmitchell85/nova-links: ^2.1`

### Packages to Keep:
- `spatie/eloquent-sortable` - Used by models for sorting
- `spatie/laravel-schemaless-attributes: ^2.0` - Used by Page models
- `spatie/laravel-sitemap: ^7.0` - Frontend sitemap generation
- `mdixon18/fontawesome: ^0.2.1` - FontAwesome helper

### Packages to Add:
- `filament/filament: ^4.0` - Main Filament package
- Filament sortable plugin (research which one is best for v4)
- Filament settings plugin (research which one is best for v4)
- Potentially `spatie/laravel-permission` for role-based access

---

## Summary Statistics

- **Total Nova Resources:** 9 (User + 8 page/content resources)
- **Page Types:** 7 (HomePage, LabPage, ResearchPage, PublicationsPage, CvPage, OutreachPage, PhotographyPage)
- **Content Resources:** 6 (TeamMember, Research, Publication, ScienceAbstract, Press, SocialLink)
- **Sortable Resources:** 4 confirmed (TeamMember, Research, Press, SocialLink) + 2 unclear (Publication, Press)
- **HasMany Relationships:** 8 relationships across pages
- **Settings Fields:** 3 global settings
- **Dynamic Navigation Items:** 7 pages (generated dynamically)

---

## Critical Decisions Needed

1. **Press Resource Sorting:**
   - Nova shows sortable handles
   - Model has date-based global scope
   - Should we add sort_order column or keep date sorting?

2. **Publication Resource Sorting:**
   - Nova shows sortable handles
   - Model has date-based global scope
   - Should we add sort_order column or keep date sorting?

3. **Science Abstract Resource Sorting:**
   - No sortable handles in Nova
   - Model has date-based global scope
   - Keep date sorting (this one seems clear)

**Recommendation:** Clarify with user during implementation whether Press and Publications should have manual sorting or remain auto-sorted by date. Visual evidence suggests sortable, but model implementation suggests date-based.
