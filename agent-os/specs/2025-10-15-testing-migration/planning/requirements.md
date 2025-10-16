# Spec Requirements: testing-migration

## Initial Description
Migrate the testing suite to modern standards:
1. Define a new comprehensive test suite from scratch (rather than adapting existing Playwright tests)
2. Migrate all existing PHPUnit tests to Pest syntax
3. Establish testing best practices and standards for the project

## Requirements Discussion

### First Round Questions

**Q1: Test Structure - Should we delete the entire `tests/playwright/` folder and create a new structure with `tests/Feature/`, `tests/Unit/`, and `tests/Browser/` (with `Admin/` and `Frontend/` subdirectories inside Browser)?**

**Answer:** Yes - delete the entire `tests/playwright/` folder. Create new structure: `tests/Feature/`, `tests/Unit/`, and `tests/Browser/`. Within `tests/Browser/`: create `Admin/` and `Frontend/` subdirectories.

**Q2: Migration Approach - Should we keep existing `tests/Unit/` and `tests/Feature/` tests and only remove Playwright stuff, then create Admin and Frontend browser tests from scratch?**

**Answer:** Yes - keep existing `tests/Unit/` and `tests/Feature/` tests. Remove all Playwright stuff. Create Admin and Frontend browser tests from scratch (new tests, not migrated).

**Q3: Test Organization - Do you want to organize tests by feature/domain (e.g., `tests/Feature/Pages/`, `tests/Feature/Projects/`), use base test classes for shared setup, and leverage Pest's `uses()` for traits?**

**Answer:** Yes to the proposed structure - grouping by feature/domain, base test classes, and using Pest's `uses()`.

**Q4: Filament Testing Priorities - For admin panel testing, should we prioritize CRUD operations, authorization, validation, and relationships?**

**Answer:**
- CRUD operations (high priority)
- Authorization (high priority)
- Validation (high priority)
- **File uploads** - critically important to test that they work and are reflected correctly

**Q5: Factories - Should we create robust factories for all models to support testing?**

**Answer:** Yes, create robust factories to help with testing.

**Q6: CI/CD - Should we set up GitHub Actions workflow for automated test runs on PRs and commits?**

**Answer:** Existing CI/CD in `.github/` folder - audit it and include testing in the suite.

**Q7: Laravel Best Practices - Should we follow these best practices: use factories, test HTTP requests/responses, use RefreshDatabase, follow Laravel coding standards?**

**Answer:** All the proposed practices are good (factories, HTTP requests, RefreshDatabase, coding standards).

**Q8: Pest Migration Strategy - Should we convert existing PHPUnit tests in place to Pest syntax (no archiving needed)?**

**Answer:** Convert existing PHPUnit tests in place to Pest syntax (no archiving, direct conversion).

**Q9: Test Coverage Scope - What is the critical path we should focus on testing?**

**Answer:** The critical path to test is:
- CRUD operations in Filament admin panel
- Ensuring frontend displays correctly after page edits in admin
- Images showing properly on frontend
- Sortable relationships appearing in correct places on frontend
- Visual display of markdown on frontend (note: will be replaced with block editor in future)

### Existing Code to Reference

No similar existing features identified for reference. However, the existing CI/CD workflows in `.github/` folder should be audited and enhanced.

### Follow-up Questions
None needed - all requirements are clear.

## Visual Assets

### Files Provided:
No visual assets provided.

### Visual Insights:
N/A

## Requirements Summary

### Functional Requirements

**Test Structure Changes:**
- Delete entire `tests/playwright/` folder
- Ensure `tests/Unit/` exists
- Ensure `tests/Feature/` exists
- Create new `tests/Browser/` directory with:
  - `tests/Browser/Admin/` subdirectory
  - `tests/Browser/Frontend/` subdirectory

**Test Migration:**
- Keep all existing `tests/Unit/` tests (convert to Pest)
- Keep all existing `tests/Feature/` tests (convert to Pest)
- Remove all Playwright-related code and dependencies
- Create new browser tests from scratch (not migrated from Playwright)

**Test Organization:**
- Group tests by feature/domain (e.g., `tests/Feature/Pages/`, `tests/Feature/Projects/`)
- Create base test classes for shared setup logic
- Leverage Pest's `uses()` for trait inclusion

**PHPUnit to Pest Conversion:**
- Convert existing PHPUnit tests in place to Pest syntax
- No archiving - direct conversion
- Maintain same test coverage and assertions
- Follow Pest best practices

**Factory Enhancement:**
- Create robust factories for all models
- Ensure factories support comprehensive test scenarios
- Include factory states where appropriate

**Admin Panel Testing (High Priority):**
- CRUD operations for all Filament resources
- Authorization checks (role/permission-based access)
- Validation rules for all forms
- **File upload handling (critically important)**
  - Test file uploads work correctly
  - Verify uploaded files are reflected in database
  - Ensure files display properly in admin and frontend

**Frontend Display Testing (Critical Path):**
- Verify admin CRUD → frontend display flow
- Test images showing properly on frontend after upload
- Test sortable relationships appearing in correct places on frontend
- Test visual display of markdown content on frontend
- Ensure page edits in admin are reflected correctly on frontend

**CI/CD Integration:**
- Audit existing `.github/` workflows
- Enhance workflows to include comprehensive test suite
- Ensure tests run on PRs and commits
- Configure appropriate test environments

### Non-Functional Requirements

**Performance:**
- Tests should run efficiently
- Use database transactions where possible (RefreshDatabase trait)
- Minimize test execution time

**Maintainability:**
- Follow Laravel coding standards
- Use descriptive test names
- Organize tests logically by feature/domain
- Reuse test setup through base classes and traits

**Code Quality:**
- Follow Pest best practices
- Use factories consistently
- Test HTTP requests/responses properly
- Ensure tests are isolated and repeatable

### Reusability Opportunities

**Existing CI/CD Workflows:**
- Audit `.github/` folder for existing workflow configurations
- Enhance/extend rather than replace existing workflows
- Integrate new test suite into existing automation

**Factory Patterns:**
- Review existing factory implementations
- Ensure consistent patterns across all factories
- Create reusable factory states

### Scope Boundaries

**In Scope:**
- Deletion of `tests/playwright/` folder and all Playwright dependencies
- Creation of new `tests/Browser/Admin/` and `tests/Browser/Frontend/` test structure
- Conversion of existing PHPUnit tests to Pest syntax (in place)
- Creation of comprehensive admin panel tests (CRUD, authorization, validation, file uploads)
- Creation of frontend display verification tests
- Enhancement of model factories
- Audit and enhancement of existing `.github/` CI/CD workflows
- Establishment of testing best practices and standards documentation

**Out of Scope:**
- Migration/adaptation of existing Playwright tests (creating new tests instead)
- Testing of block editor functionality (markdown is current implementation, block editor is future enhancement)
- Creation of new features beyond testing infrastructure
- Performance optimization beyond standard testing practices

### Technical Considerations

**Testing Framework:**
- Pest (migration from PHPUnit)
- Laravel Dusk for browser tests (Admin and Frontend)
- PHPUnit assertions available through Pest

**File Structure:**
- `tests/Unit/` - Unit tests (Pest)
- `tests/Feature/` - Feature tests organized by domain (Pest)
- `tests/Browser/Admin/` - Admin panel browser tests (Dusk + Pest)
- `tests/Browser/Frontend/` - Frontend browser tests (Dusk + Pest)

**Dependencies to Remove:**
- All Playwright-related packages
- Playwright configuration files
- Playwright test files

**Dependencies to Add/Ensure:**
- Pest PHP
- Laravel Dusk (if not already present)

**CI/CD:**
- Existing workflows in `.github/` folder
- Need to audit and enhance for comprehensive test coverage

**Critical Testing Flow:**
Admin CRUD → Database Update → Frontend Display Verification

This flow must be tested end-to-end with special attention to:
- File uploads and display
- Sortable relationship rendering
- Markdown content display
- Image handling and optimization

**Laravel Ecosystem Compatibility:**
- Laravel 10 (as per project tech stack)
- Filament 4 admin panel testing
- Inertia.js v1 frontend testing considerations
