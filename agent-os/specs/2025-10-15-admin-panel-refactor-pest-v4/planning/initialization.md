# Initial Spec Idea

## User's Initial Description
Admin Panel Refactoring and Pest v4 Test Migration

The user wants to make comprehensive changes to the Filament admin panel and migrate all tests to Pest v4:

1. **Admin Panel Changes:**
   - Move resources back into the pages they belong to (e.g., Team Members resource should be within the relevant page, not separate)
   - Remove all index routes for pages
   - Make sidebar links go directly to the edit screen for each page (not to a list view)
   - Result: Left sidebar will have direct links to edit each page, with relevant resources displayed as sections on that page

2. **Test Suite Migration:**
   - Migrate both frontend and backend tests to Pest v4 (https://pestphp.com/)
   - Build comprehensive test suite
   - Use Laravel Boost MCP server tools to access Pest v4 documentation

Create the spec folder with an appropriate name (e.g., "admin-panel-refactor-pest-v4") and return the path to the created spec folder.

## Metadata
- Date Created: 2025-10-15
- Spec Name: admin-panel-refactor-pest-v4
- Spec Path: agent-os/specs/2025-10-15-admin-panel-refactor-pest-v4
