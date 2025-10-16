# Specification: Testing Infrastructure Migration

## Goal

Migrate this Laravel 10 + Filament 4 application from a mixed PHPUnit/Playwright testing approach to a modern, comprehensive Pest-based testing infrastructure with Laravel Dusk browser tests, ensuring critical admin-to-frontend workflows are thoroughly tested.

## User Stories

- As a developer, I want all tests to use Pest v4 syntax so that the codebase has consistent, modern, readable test patterns
- As a developer, I want comprehensive browser tests for the Filament admin panel so that I can verify CRUD operations, file uploads, and authorization work correctly
- As a developer, I want browser tests that verify admin changes appear correctly on the frontend so that I can ensure the admin-to-frontend flow works end-to-end
- As a developer, I want robust model factories so that I can easily generate test data across all test types
- As a maintainer, I want CI/CD pipelines that run the complete test suite so that I catch regressions before deployment

## Core Requirements

### Functional Requirements

**Test Structure Overhaul:**
- Delete entire `tests/playwright/` directory and all Playwright-related files
- Remove Playwright dependencies from `package.json` (`@playwright/test`, `playwright`)
- Ensure clean test directory structure exists:
  - `tests/Unit/` - Unit tests (Pest syntax)
  - `tests/Feature/` - Feature tests organized by domain (Pest syntax)
  - `tests/Browser/` - New directory for browser tests
    - `tests/Browser/Admin/` - Filament admin panel tests (Dusk + Pest)
    - `tests/Browser/Frontend/` - Frontend display tests (Dusk + Pest)

**PHPUnit to Pest v4 Migration:**
- Convert all existing PHPUnit tests in `tests/Feature/` to Pest v4 syntax
- Convert all existing PHPUnit tests in `tests/Unit/` to Pest v4 syntax
- Convert tests in place (no archiving or backup copies)
- Use Laravel Boost MCP server `search-docs` tool to reference Pest v4 documentation
- Maintain identical test coverage and assertions - only syntax changes
- Follow Pest best practices:
  - Use `it()` or `test()` for test definitions
  - Use Pest's expectation syntax where appropriate
  - Leverage `uses()` for traits (RefreshDatabase, etc.)
  - Create base test classes with `uses()` for shared setup

**Test Organization by Domain:**
- Group feature tests by model/domain:
  - `tests/Feature/Pages/` - Page-related tests
  - `tests/Feature/Research/` - Research project tests
  - `tests/Feature/Publications/` - Publication tests
  - `tests/Feature/Press/` - Press coverage tests
  - `tests/Feature/TeamMembers/` - Team member tests
  - `tests/Feature/Users/` - User and authentication tests
  - Additional domains as needed
- Create base test case classes for shared setup patterns
- Use Pest's `uses()` to apply traits and base classes

**Browser Test Suite (High Priority):**
- Install and configure Laravel Dusk for browser testing
- Create comprehensive admin panel tests in `tests/Browser/Admin/`:
  - **CRUD Operations (High Priority):**
    - Create: Test creating records through Filament forms
    - Read: Test listing and viewing records
    - Update: Test editing records
    - Delete: Test soft-deleting and restoring records
  - **Authorization Tests (High Priority):**
    - Admin role can access Filament panel
    - Non-admin users receive 403 forbidden
    - Guest users redirect to login
    - Role-based access to specific resources
  - **Form Validation (High Priority):**
    - Required field validation
    - Format validation (email, URL, etc.)
    - Custom validation rules
    - Error message display
  - **File Upload Tests (Critically Important):**
    - Image uploads work correctly
    - Files save to correct storage location
    - File paths stored in database correctly
    - Uploaded files display in admin panel
    - File validation (type, size) works
- Create frontend verification tests in `tests/Browser/Frontend/`:
  - **Admin-to-Frontend Flow:**
    - Create/edit content in admin → verify display on frontend
    - Test images appear correctly after upload
    - Test sortable relationships display in correct order
    - Test markdown content renders properly
  - **Visual Display Tests:**
    - Images load with correct paths
    - Responsive image rendering
    - Relationship data displays correctly
    - SEO metadata appears correctly

**Model Factory Enhancement:**
- Audit all existing factories in `database/factories/`
- Create factories for any models missing them
- Enhance factories with:
  - Realistic fake data using Faker
  - Factory states for common test scenarios (e.g., `withImage()`, `published()`)
  - Proper relationship handling
  - Support for file upload testing (fake images)
- Existing factories to audit/enhance:
  - ResearchFactory
  - SocialLinkFactory
  - PressFactory
  - TeamMemberFactory
  - ScienceAbstractFactory
  - UserFactory
  - PublicationFactory
  - Factories for Page models (HomePage, AboutPage, etc.)

**CI/CD Integration:**
- Audit existing GitHub Actions workflows:
  - `.github/workflows/dev-pr.yml`
  - `.github/workflows/dev-push.yml`
  - `.github/workflows/master-push.yml`
- Update workflows to:
  - Replace PHPUnit with Pest test execution
  - Add Laravel Dusk browser test execution
  - Configure Chrome/ChromeDriver for Dusk tests
  - Maintain 80% test coverage requirement
  - Run complete test suite on PRs and commits
- Ensure browser tests run in headless mode for CI

### Non-Functional Requirements

**Performance:**
- Feature and unit tests execute quickly (< 30 seconds total)
- Browser tests complete in reasonable time (< 5 minutes)
- Use `RefreshDatabase` trait with transactions for fast database resets
- Leverage Pest's parallel execution capabilities where appropriate

**Maintainability:**
- Tests use descriptive names explaining what's being tested
- Follow consistent organizational patterns across all test types
- Reuse test setup through base classes and Pest's `uses()`
- Factories provide single source of truth for test data creation
- Clear separation between unit, feature, and browser tests

**Code Quality:**
- Follow Laravel coding standards (enforced by Pint)
- Use type hints in test methods
- Leverage Pest's expectation API for readable assertions
- Maintain DRY principles through shared test utilities
- Document complex test scenarios with inline comments

**Browser Test Reliability:**
- Use stable selectors (prefer data attributes over CSS classes)
- Add appropriate wait conditions for async operations
- Handle Filament's Livewire interactions correctly
- Screenshot failures for debugging
- Retry flaky operations appropriately

## Visual Design

No visual mockups provided. Browser tests will verify existing UI implementation in Filament 4 admin panel and Inertia.js v1 frontend.

## Reusable Components

### Existing Code to Leverage

**Test Infrastructure:**
- `tests/TestCase.php` - Base test case class (convert to work with Pest)
- `tests/CreatesApplication.php` - Application bootstrapping trait (reuse as-is)
- Existing test patterns in `PageResourceTest.php` and `PermissionSystemTest.php` serve as conversion examples

**Model Factories (Existing):**
- `database/factories/UserFactory.php` - User creation patterns
- `database/factories/ResearchFactory.php` - Research project patterns
- `database/factories/PublicationFactory.php` - Publication patterns
- `database/factories/PressFactory.php` - Press coverage patterns
- `database/factories/TeamMemberFactory.php` - Team member patterns
- All other existing factories

**CI/CD Workflows:**
- `.github/workflows/dev-pr.yml` - PR testing workflow (update for Pest/Dusk)
- Existing PHP 8.4 setup and dependency installation steps
- Asset compilation steps (npm install/build)
- Coverage reporting configuration

**Filament Resources (Test Targets):**
- All resources in `app/Filament/Resources/` serve as test targets:
  - Research/ResearchResource
  - Publications/PublicationResource
  - Press/PressResource
  - TeamMembers/TeamMemberResource
  - Users/UserResource
  - Pages/* (various page resources)

### New Components Required

**Pest Configuration:**
- `tests/Pest.php` - Pest configuration file defining test suites, base paths, and global `uses()`
- New because: Pest requires its own configuration separate from PHPUnit

**Laravel Dusk Setup:**
- `tests/DuskTestCase.php` - Dusk base test case
- Dusk configuration for ChromeDriver
- Browser test helpers and custom assertions
- New because: Browser testing framework not currently in project

**Test Organization Structure:**
- Feature test subdirectories by domain (Pages/, Research/, etc.)
- Browser test organization (Admin/, Frontend/)
- New because: Current tests lack domain-based organization

**Factory States:**
- States for common scenarios (published, with_image, featured, etc.)
- Builders for complex test data setups
- New because: Existing factories lack comprehensive state support

**Test Utilities:**
- Admin authentication helpers for browser tests
- File upload test helpers
- Filament form interaction helpers
- New because: Browser testing requires specialized utilities

## Technical Approach

### Dependency Management

**Add Dependencies:**
```bash
composer require --dev pestphp/pest pestphp/pest-plugin-laravel --with-all-dependencies
composer require --dev laravel/dusk
```

**Remove Dependencies:**
- Remove from `package.json`: `@playwright/test`, `playwright`
- Delete `tests/playwright/` directory
- Delete any Playwright config files

**Initialize Pest:**
```bash
php artisan pest:install
```

**Initialize Dusk:**
```bash
php artisan dusk:install
php artisan dusk:chrome-driver
```

### Test Migration Strategy

**Phase 1: PHPUnit to Pest Conversion**
1. Start with `tests/Unit/ExampleTest.php` as practice conversion
2. Convert feature tests one domain at a time:
   - Tests/Feature/PageResourceTest.php → tests/Feature/Pages/PageResourceTest.php
   - Tests/Feature/PermissionSystemTest.php → tests/Feature/Users/PermissionSystemTest.php
   - Tests/Feature/UserResourceTest.php → tests/Feature/Users/UserResourceTest.php
3. Set up `uses()` calls in Pest.php for RefreshDatabase and other traits
4. Run tests after each conversion to ensure functionality preserved
5. Use Laravel Boost `search-docs` tool to reference Pest v4 syntax

**Conversion Pattern Example:**
```php
// Before (PHPUnit)
public function test_homepage_schemaless_attributes_save_and_load(): void
{
    $homePage = HomePage::create([...]);
    $this->assertEquals('Welcome', $homePage->content->tagline);
}

// After (Pest)
test('homepage schemaless attributes save and load', function () {
    $homePage = HomePage::create([...]);
    expect($homePage->content->tagline)->toBe('Welcome');
});
```

**Phase 2: Browser Test Creation**
1. Create `tests/Browser/Admin/` structure
2. Start with authentication and authorization tests
3. Create CRUD tests for one Filament resource (e.g., Research)
4. Add file upload tests for resources with media
5. Expand to all Filament resources
6. Create `tests/Browser/Frontend/` tests verifying admin changes appear

**Phase 3: Factory Enhancement**
1. Audit each existing factory
2. Add missing factories for Page models
3. Create factory states (e.g., `published()`, `withImage()`)
4. Add relationship handling
5. Test all factories produce valid models

**Phase 4: CI/CD Updates**
1. Update workflow files to use Pest instead of PHPUnit
2. Add Dusk browser test execution
3. Configure headless Chrome for CI environment
4. Verify coverage reporting works with Pest
5. Test complete pipeline end-to-end

### Database

**Models Requiring Factory Updates:**
- All existing models (Research, Publication, Press, TeamMember, ScienceAbstract, SocialLink, User)
- Page models (HomePage, AboutPage, LabPage, PublicationsPage, OutreachPage, CvPage, etc.)

**Test Database Strategy:**
- Use SQLite in-memory database for tests (configured in `phpunit.xml`)
- Use `RefreshDatabase` trait via Pest's `uses()` for automatic migrations
- Leverage database transactions for test isolation
- Seed necessary data (roles, permissions) in base test setup

### Frontend Testing Approach

**Filament Admin Testing:**
- Use Dusk to interact with Livewire components
- Test form submissions through Filament's action system
- Verify table listings and filtering
- Test modal interactions and notifications
- Verify authorization at UI level (buttons visible/hidden)

**Inertia Frontend Testing:**
- Use Dusk to verify Inertia page loads
- Test that props passed from Laravel appear in rendered HTML
- Verify images render with correct src attributes
- Test sortable lists maintain order
- Verify markdown renders as HTML

**File Upload Testing:**
- Use `Storage::fake('public')` in feature tests
- Use Dusk's `attach()` method for browser upload tests
- Verify files in storage and database
- Test image display in both admin and frontend

### Test Organization

**Pest.php Configuration:**
```php
uses(TestCase::class, RefreshDatabase::class)
    ->in('Feature', 'Unit');

uses(DuskTestCase::class)
    ->in('Browser');
```

**Naming Conventions:**
- Test files: `*Test.php` (e.g., `PageResourceTest.php`)
- Test methods: Descriptive phrases (e.g., `test('admin can create research project')`)
- Browser tests: Group by resource or feature area

**Directory Structure:**
```
tests/
├── Browser/
│   ├── Admin/
│   │   ├── ResearchCrudTest.php
│   │   ├── PublicationCrudTest.php
│   │   ├── FileUploadTest.php
│   │   └── AuthorizationTest.php
│   └── Frontend/
│       ├── ResearchDisplayTest.php
│       ├── ImageRenderingTest.php
│       └── MarkdownRenderingTest.php
├── Feature/
│   ├── Pages/
│   │   └── PageResourceTest.php
│   ├── Research/
│   │   └── ResearchTest.php
│   ├── Publications/
│   ├── Press/
│   ├── TeamMembers/
│   └── Users/
│       ├── PermissionSystemTest.php
│       └── UserResourceTest.php
├── Unit/
│   └── ExampleTest.php
├── CreatesApplication.php
├── DuskTestCase.php
├── Pest.php
└── TestCase.php
```

### Testing Priorities (Implementation Order)

**Critical Path Testing (Highest Priority):**
1. File upload functionality (critically important)
   - Admin upload → storage → database → admin display → frontend display
2. Admin CRUD operations for core resources (Research, Publications, Press)
3. Authorization system (admin role, non-admin blocked, guest redirect)
4. Admin changes reflect on frontend (create/edit in admin → verify on frontend)

**High Priority Testing:**
5. Form validation in Filament resources
6. Image rendering on frontend pages
7. Sortable relationships display correctly
8. Markdown content rendering

**Standard Priority Testing:**
9. Remaining Filament resources CRUD
10. SEO metadata handling
11. Edge cases and error states

## Out of Scope

**Not Included in This Migration:**
- Migration or adaptation of existing Playwright tests (creating fresh tests instead)
- Testing future block editor functionality (current markdown implementation only)
- Performance testing or load testing
- Accessibility testing (WCAG compliance)
- Cross-browser testing (Chrome only for browser tests)
- Visual regression testing (screenshot comparison)
- API testing (if APIs exist, can be added later)
- E2E tests beyond admin-to-frontend verification
- Mobile-specific browser tests

**Future Enhancements:**
- Block editor testing when implemented
- Expanded browser test coverage beyond critical paths
- Performance benchmarking
- Mutation testing
- Additional factory states as needs arise

## Success Criteria

**Migration Completeness:**
- Zero Playwright files remain in codebase
- All PHPUnit tests converted to Pest v4 syntax
- All converted tests pass with same coverage
- New browser test suite created and passing

**Test Coverage:**
- Maintain or exceed current 80% code coverage threshold
- All Filament resources have CRUD browser tests
- File upload flow tested end-to-end
- Admin-to-frontend flow verified for core content types

**Code Quality:**
- All tests pass `vendor/bin/pint` formatting check
- Tests use Pest best practices and expectation syntax
- Tests organized logically by domain
- Factories exist and work for all models

**CI/CD Integration:**
- GitHub Actions workflows execute Pest tests successfully
- Browser tests run in headless mode in CI
- Coverage reporting works with Pest
- All workflows pass on dev and master branches

**Developer Experience:**
- Tests run quickly in local development
- Clear test organization makes finding tests easy
- Factory usage simplifies test data creation
- Browser test failures produce helpful screenshots

**Documentation:**
- Test writing standards updated for Pest
- Browser testing patterns documented
- Factory usage examples provided
- CI/CD configuration documented

## Risks and Mitigations

**Risk: Pest v4 Syntax Differences**
- Mitigation: Use Laravel Boost `search-docs` tool to access version-specific Pest v4 documentation before each conversion
- Mitigation: Convert tests incrementally, running test suite after each conversion
- Mitigation: Start with simple tests to establish patterns

**Risk: Browser Test Flakiness**
- Mitigation: Use Dusk's built-in wait methods for async operations
- Mitigation: Use stable selectors (Filament provides good defaults)
- Mitigation: Configure appropriate timeouts for Livewire interactions
- Mitigation: Screenshot failures for debugging

**Risk: Breaking Existing Test Coverage**
- Mitigation: Convert tests in place with same assertions
- Mitigation: Run tests frequently during migration
- Mitigation: Keep RefreshDatabase and other traits
- Mitigation: Maintain coverage reporting to catch drops

**Risk: Filament/Livewire Testing Complexity**
- Mitigation: Use Filament's testing utilities designed for Livewire
- Mitigation: Reference Filament documentation for testing patterns
- Mitigation: Start with simple CRUD flows before complex interactions
- Mitigation: Use Dusk's debugging tools (pause(), dump())

**Risk: CI/CD Browser Test Environment**
- Mitigation: Use Dusk's headless Chrome configuration
- Mitigation: Configure ChromeDriver in GitHub Actions
- Mitigation: Test CI pipeline early in migration
- Mitigation: Use Dusk's screenshot on failure for debugging CI issues

**Risk: Factory Gaps or Incomplete Data**
- Mitigation: Audit all factories systematically
- Mitigation: Test factories produce valid, saveable models
- Mitigation: Add states incrementally as test needs arise
- Mitigation: Document factory usage patterns

**Risk: Test Execution Time**
- Mitigation: Use database transactions via RefreshDatabase
- Mitigation: Leverage Pest's parallel execution
- Mitigation: Keep browser tests focused on critical paths
- Mitigation: Profile slow tests and optimize

**Risk: Loss of Testing Knowledge During Migration**
- Mitigation: Keep existing test logic identical during Pest conversion
- Mitigation: Document any testing patterns that change
- Mitigation: Maintain test organization that mirrors application structure
- Mitigation: Create reusable test utilities for common patterns
