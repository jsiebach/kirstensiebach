# Factory Usage Guide

## Overview

This application uses Laravel's model factories to generate test data consistently and efficiently. This guide documents the available factories, their states, and usage patterns.

## Available Factories

### Core Model Factories

#### UserFactory
Location: `database/factories/UserFactory.php`

**Default State:**
- Generates random name, email, password
- Email verification timestamp set
- Remember token generated

**States:**
- `unverified()` - Sets `email_verified_at` to null

**Usage:**
```php
// Create verified user
$user = User::factory()->create();

// Create unverified user
$user = User::factory()->unverified()->create();

// Create with specific email
$user = User::factory()->create(['email' => 'admin@example.com']);
```

---

#### ResearchFactory
Location: `database/factories/ResearchFactory.php`

**Default State:**
- Creates/finds research page automatically
- Random project name and description
- Sort order between 1-100
- No image by default

**States:**
- `withImage()` - Adds fake image path
- `featured()` - Low sort order (1-10) with featured image

**Usage:**
```php
// Basic research project
$research = Research::factory()->create();

// Research with image
$research = Research::factory()->withImage()->create();

// Featured research project
$research = Research::factory()->featured()->create();

// Specify page
$researchPage = ResearchPage::factory()->create();
$research = Research::factory()->create(['page_id' => $researchPage->id]);
```

---

#### PublicationFactory
Location: `database/factories/PublicationFactory.php`

**Default State:**
- Creates/finds publications page automatically
- Random title, authors, publication name
- 80% chance of being published
- Random date within last 5 years
- Fake DOI and link generated

**States:**
- `published()` - Sets published to true with valid date
- `draft()` - Sets published to false (date_published still set for testing)
- `recent()` - Published within last year

**Usage:**
```php
// Random publication
$publication = Publication::factory()->create();

// Published publication
$publication = Publication::factory()->published()->create();

// Draft publication
$publication = Publication::factory()->draft()->create();

// Recent publication
$publication = Publication::factory()->recent()->create();
```

---

#### PressFactory
Location: `database/factories/PressFactory.php`

**Default State:**
- Creates/finds outreach page automatically
- Random title and link
- Random date within last 2 years

**States:**
- `recent()` - Date within last 6 months
- `featured()` - Date within last 3 months

**Usage:**
```php
// Basic press item
$press = Press::factory()->create();

// Recent press coverage
$press = Press::factory()->recent()->create();

// Featured press item
$press = Press::factory()->featured()->create();
```

---

#### TeamMemberFactory
Location: `database/factories/TeamMemberFactory.php`

**Default State:**
- Creates/finds lab page automatically
- Random name, email, bio
- Sort order between 1-100
- 30% chance of being alumni
- Default profile picture path

**States:**
- `withImage()` - Custom profile picture path
- `alumni()` - Sets alumni to true
- `active()` - Sets alumni to false
- `featured()` - Low sort order, not alumni, featured image

**Usage:**
```php
// Random team member
$member = TeamMember::factory()->create();

// Active team member
$member = TeamMember::factory()->active()->create();

// Alumni member
$member = TeamMember::factory()->alumni()->create();

// Featured team member
$member = TeamMember::factory()->featured()->create();
```

---

#### ScienceAbstractFactory
Location: `database/factories/ScienceAbstractFactory.php`

**Default State:**
- Creates/finds outreach page automatically
- Random conference name, title, abstract
- Random date within last 3 years
- Random link generated

**States:**
- `recent()` - Date within last year
- `featured()` - Date within last 6 months
- `withoutLink()` - Sets link to null

**Usage:**
```php
// Basic science abstract
$abstract = ScienceAbstract::factory()->create();

// Recent presentation
$abstract = ScienceAbstract::factory()->recent()->create();

// Abstract without link
$abstract = ScienceAbstract::factory()->withoutLink()->create();
```

---

#### SocialLinkFactory
Location: `database/factories/SocialLinkFactory.php`

**Default State:**
- Creates/finds home page automatically
- Random platform and URL
- Sort order between 1-100

**States:**
- `featured()` - Low sort order (1-5)

**Usage:**
```php
// Random social link
$link = SocialLink::factory()->create();

// Featured social link
$link = SocialLink::factory()->featured()->create();
```

---

### Page Model Factories

#### PageFactory (Base)
Location: `database/factories/PageFactory.php`

**Default State:**
- Random title and slug
- Random SEO meta title and description

**Usage:**
```php
// Generic page
$page = Page::factory()->create();

// Page with specific slug
$page = Page::factory()->create(['slug' => 'custom-slug']);
```

---

#### HomePageFactory
Location: `database/factories/Pages/HomePageFactory.php`

**Default State:**
- Slug: 'home'
- Title: 'Home'
- Random meta title and description

**States:**
- `withImages()` - Sets banner and profile image paths in schemaless content
- `withCallToAction()` - Sets CTA banner fields in schemaless content

**Usage:**
```php
// Basic home page
$homePage = HomePage::factory()->create();

// Home page with images
$homePage = HomePage::factory()->withImages()->create();

// Home page with CTA
$homePage = HomePage::factory()->withCallToAction()->create();
```

---

#### LabPageFactory
Location: `database/factories/Pages/LabPageFactory.php`

**Default State:**
- Slug: 'lab'
- Title: 'Lab'
- Random meta data

**Usage:**
```php
$labPage = LabPage::factory()->create();
```

---

#### ResearchPageFactory
Location: `database/factories/Pages/ResearchPageFactory.php`

**Default State:**
- Slug: 'research'
- Title: 'Research'

**Usage:**
```php
$researchPage = ResearchPage::factory()->create();
```

---

#### PublicationsPageFactory
Location: `database/factories/Pages/PublicationsPageFactory.php`

**Default State:**
- Slug: 'publications'
- Title: 'Publications'

**Usage:**
```php
$publicationsPage = PublicationsPage::factory()->create();
```

---

#### OutreachPageFactory
Location: `database/factories/Pages/OutreachPageFactory.php`

**Default State:**
- Slug: 'outreach'
- Title: 'Outreach'

**Usage:**
```php
$outreachPage = OutreachPage::factory()->create();
```

---

#### CvPageFactory
Location: `database/factories/Pages/CvPageFactory.php`

**Default State:**
- Slug: 'cv'
- Title: 'CV'

**States:**
- `withCvFile()` - Sets CV file path in schemaless content

**Usage:**
```php
// Basic CV page
$cvPage = CvPage::factory()->create();

// CV page with file
$cvPage = CvPage::factory()->withCvFile()->create();
```

---

#### PhotographyPageFactory
Location: `database/factories/Pages/PhotographyPageFactory.php`

**Default State:**
- Slug: 'photography'
- Title: 'Photography'

**Usage:**
```php
$photographyPage = PhotographyPage::factory()->create();
```

---

## Factory Usage Patterns

### Creating Single Models

```php
// Create and persist to database
$user = User::factory()->create();

// Create without persisting (make)
$user = User::factory()->make();

// Create with specific attributes
$user = User::factory()->create([
    'email' => 'specific@example.com',
    'name' => 'Specific Name',
]);
```

### Creating Multiple Models

```php
// Create 5 users
$users = User::factory()->count(5)->create();

// Create 3 research projects
$research = Research::factory()->count(3)->create();
```

### Chaining States

```php
// Featured research with image
$research = Research::factory()
    ->featured()
    ->withImage()
    ->create();

// Recent published publication
$publication = Publication::factory()
    ->published()
    ->recent()
    ->create();
```

### Creating Related Models

```php
// Create page with research projects
$researchPage = ResearchPage::factory()->create();
$research1 = Research::factory()->create(['page_id' => $researchPage->id]);
$research2 = Research::factory()->create(['page_id' => $researchPage->id]);

// Or use has() relationship method
$researchPage = ResearchPage::factory()
    ->has(Research::factory()->count(3))
    ->create();
```

### Using Factories in Tests

#### Basic Usage

```php
test('user can be created', function (): void {
    $user = User::factory()->create();

    expect($user->id)->toBeInt();
    expect($user->email)->toContain('@');
});
```

#### Testing with States

```php
test('published publications appear on website', function (): void {
    $publication = Publication::factory()->published()->create();

    expect($publication->published)->toBeTrue();
    expect($publication->date_published)->toBeInstanceOf(Carbon::class);
});
```

#### Testing File Uploads

```php
test('research project with image', function (): void {
    $research = Research::factory()->withImage()->create();

    expect($research->image)->toBeString();
    expect($research->image)->toContain('images/research-');
});
```

#### Testing Relationships

```php
test('research belongs to page', function (): void {
    $researchPage = ResearchPage::factory()->create();
    $research = Research::factory()->create(['page_id' => $researchPage->id]);

    expect($research->page)->toBeInstanceOf(Page::class);
    expect($research->page_id)->toBe($researchPage->id);
});
```

## Best Practices

### 1. Use Factories Consistently

Always use factories instead of manual model creation in tests:

```php
// Good
$user = User::factory()->create();

// Avoid
$user = new User();
$user->name = 'Test User';
$user->email = 'test@example.com';
$user->save();
```

### 2. Create Specific Factory States

When you find yourself repeatedly setting the same attributes, create a factory state:

```php
// If you often need this:
$publication = Publication::factory()->create([
    'published' => true,
    'date_published' => now(),
]);

// Create a state instead:
// In PublicationFactory
public function published(): Factory
{
    return $this->state(fn ($attributes) => [
        'published' => true,
        'date_published' => now(),
    ]);
}

// Then use:
$publication = Publication::factory()->published()->create();
```

### 3. Use make() for Non-Persisted Models

When you don't need database persistence, use `make()`:

```php
test('user name validation', function (): void {
    $user = User::factory()->make(['name' => '']);

    // Test validation without saving
    expect(fn () => $user->save())->toThrow(ValidationException::class);
});
```

### 4. Override Only What's Necessary

```php
// Good - Override only what you need to test
$user = User::factory()->create(['email' => 'specific@example.com']);

// Avoid - Overriding everything defeats the factory purpose
$user = User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password',
    // ...
]);
```

### 5. Test Factory States

Ensure all factory states work correctly:

```php
test('research factory withImage state works', function (): void {
    $research = Research::factory()->withImage()->create();

    expect($research->image)->toBeString();
    expect($research->image)->toContain('images/research-');
});
```

## Common Patterns

### Setup Test Data

```php
beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->researchPage = ResearchPage::factory()->create();
    $this->research = Research::factory()->count(3)->create([
        'page_id' => $this->researchPage->id,
    ]);
});

test('research page displays projects', function (): void {
    expect($this->research)->toHaveCount(3);
});
```

### Creating Complex Data Structures

```php
test('complete lab structure', function (): void {
    $labPage = LabPage::factory()->create();

    $activeMembers = TeamMember::factory()
        ->count(3)
        ->active()
        ->create(['page_id' => $labPage->id]);

    $alumniMembers = TeamMember::factory()
        ->count(2)
        ->alumni()
        ->create(['page_id' => $labPage->id]);

    expect(TeamMember::where('alumni', false)->count())->toBe(3);
    expect(TeamMember::where('alumni', true)->count())->toBe(2);
});
```

## Debugging Factories

### Check Generated Attributes

```php
test('inspect factory output', function (): void {
    $user = User::factory()->make();

    dump($user->toArray()); // See generated attributes
    expect($user->email)->toBeString();
});
```

### Verify Relationships

```php
test('factory creates relationships correctly', function (): void {
    $research = Research::factory()->create();

    // Verify the page was created
    expect($research->page)->toBeInstanceOf(Page::class);
    expect($research->page->slug)->toBe('research');
});
```

## Performance Tips

### 1. Use make() When Possible

```php
// Faster - no database hit
$users = User::factory()->count(100)->make();

// Slower - 100 database inserts
$users = User::factory()->count(100)->create();
```

### 2. Batch Related Models

```php
// Good - One query for page, one for research
$researchPage = ResearchPage::factory()->create();
$research = Research::factory()->count(10)->create(['page_id' => $researchPage->id]);

// Less efficient - Creates page for each research
$research = Research::factory()->count(10)->create(); // Creates 10 pages
```

### 3. Reuse Test Data

```php
beforeEach(function (): void {
    $this->page = ResearchPage::factory()->create();
});

test('multiple tests can use same page', function (): void {
    $research = Research::factory()->create(['page_id' => $this->page->id]);
    // ... test code
});
```

## Troubleshooting

### Missing Page Relations

If you see errors about missing pages:

```php
// Explicitly create the page first
$researchPage = ResearchPage::factory()->create(['slug' => 'research']);
$research = Research::factory()->create(['page_id' => $researchPage->id]);
```

### Mass Assignment Errors

If you get mass assignment exceptions, ensure factory attributes match model's `$fillable` or the model uses `$guarded = []`.

### Unique Constraint Violations

```php
// If slug must be unique, override it:
$page1 = Page::factory()->create(['slug' => 'unique-1']);
$page2 = Page::factory()->create(['slug' => 'unique-2']);
```

## Factory Validation Tests

Location: `tests/Unit/Factories/FactoryTest.php`

All factories have corresponding tests that verify:
1. Factory creates valid, saveable model
2. Factory states work correctly
3. Factory handles relationships properly

Run factory tests:
```bash
vendor/bin/pest tests/Unit/Factories/FactoryTest.php
```
