const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

// Create screenshots directory
const screenshotsDir = path.join(__dirname, 'test-screenshots-v2');
if (!fs.existsSync(screenshotsDir)) {
  fs.mkdirSync(screenshotsDir, { recursive: true });
}

// Test configuration
const BASE_URL = 'http://localhost:8080/filament';
const LOGIN_EMAIL = 'jsiebach@gmail.com';
const LOGIN_PASSWORD = 'admin';

// Page resources to test (matching the sidebar navigation)
const PAGE_RESOURCES = [
  'Home Page',
  'Lab Page',
  'Research Page',
  'Publications Page',
  'CV Page',
  'Outreach Page',
  'Photography Page'
];

let browser, context, page;
let testResults = [];
let consoleErrors = [];
let validationErrors = [];
let uiIssues = [];
let createdRecords = {}; // Track created test records by resource name

async function setup() {
  console.log('🚀 Starting Filament CRUD Tests v2...\n');

  browser = await chromium.launch({
    headless: false,
    slowMo: 300
  });

  context = await browser.newContext({
    viewport: { width: 1920, height: 1080 }
  });

  page = await context.newPage();

  // Capture console errors
  page.on('console', msg => {
    if (msg.type() === 'error') {
      const error = `[${new Date().toISOString()}] ${msg.text()}`;
      consoleErrors.push(error);
      console.error('❌ Console Error:', msg.text());
    }
  });

  page.on('pageerror', error => {
    const errorMsg = `[${new Date().toISOString()}] ${error.message}`;
    consoleErrors.push(errorMsg);
    console.error('❌ Page Error:', error.message);
  });
}

async function login() {
  console.log('🔐 Logging in...');

  await page.goto(BASE_URL + '/login');
  await page.waitForLoadState('networkidle');

  await page.fill('input[type="email"]', LOGIN_EMAIL);
  await page.fill('input[type="password"]', LOGIN_PASSWORD);

  await page.screenshot({
    path: path.join(screenshotsDir, '00-login-form.png'),
    fullPage: true
  });

  await page.click('button[type="submit"]');
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(2000);

  await page.screenshot({
    path: path.join(screenshotsDir, '00-dashboard.png'),
    fullPage: true
  });

  console.log('✅ Login successful\n');
}

async function navigateToResource(resourceName) {
  console.log(`🔍 Navigating to ${resourceName}...`);

  // Click on the sidebar navigation item
  const navItem = await page.locator(`nav a:has-text("${resourceName}")`).first();

  if (await navItem.count() === 0) {
    throw new Error(`Navigation item for ${resourceName} not found`);
  }

  await navItem.click();
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(1500);
}

async function testReadListView(resourceName, index) {
  console.log(`📋 Testing READ (List View) - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'READ (List View)',
    status: 'PASS',
    notes: []
  };

  try {
    await navigateToResource(resourceName);

    const screenshotName = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-list.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotName),
      fullPage: true
    });

    // Check if there's a table with records
    const tableRows = await page.locator('table tbody tr, .fi-ta-content tbody tr').count();

    if (tableRows > 0) {
      result.notes.push(`Found ${tableRows} record(s) in table`);
    } else {
      // Check for empty state
      const emptyState = await page.locator('text=/No.*found/i, text=/empty/i').count();
      if (emptyState > 0) {
        result.notes.push('Empty state displayed (no records)');
      } else {
        result.notes.push('Page loaded successfully');
      }
    }

    // Check for search functionality
    const searchInput = await page.locator('input[type="search"], input[placeholder*="Search"]').count();
    if (searchInput > 0) {
      result.notes.push('Search functionality available');
    }

    // Check for "New" or "Create" button
    const createButton = await page.locator('a:has-text("New"), button:has-text("New"), a:has-text("Create")').count();
    if (createButton > 0) {
      result.notes.push('Create button available');
    } else {
      result.notes.push('No create button found');
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function testReadEditView(resourceName, index) {
  console.log(`👁️  Testing READ (Edit View) - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'READ (Edit View)',
    status: 'PASS',
    notes: []
  };

  try {
    // Should already be on the resource page
    const tableRows = await page.locator('table tbody tr, .fi-ta-content tbody tr').count();

    if (tableRows === 0) {
      result.status = 'SKIP';
      result.notes.push('No records available to view/edit');
      console.log(`⏭️  ${result.operation} - SKIP: No records available`);
      testResults.push(result);
      return result;
    }

    // Find and click the edit button in the first row
    const firstRow = await page.locator('table tbody tr, .fi-ta-content tbody tr').first();

    // Try different edit button selectors
    let editButton = await firstRow.locator('a[href*="/edit"]').first();

    if (await editButton.count() === 0) {
      editButton = await firstRow.locator('button:has-text("Edit"), [aria-label*="Edit"]').first();
    }

    if (await editButton.count() === 0) {
      // Try actions dropdown
      const actionsButton = await firstRow.locator('button[aria-label*="Actions"]').first();
      if (await actionsButton.count() > 0) {
        await actionsButton.click();
        await page.waitForTimeout(500);
        editButton = await page.locator('a:has-text("Edit"), button:has-text("Edit")').first();
      }
    }

    if (await editButton.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Edit button not found');
      console.log(`⏭️  ${result.operation} - SKIP: Edit button not found`);
      testResults.push(result);
      return result;
    }

    await editButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // Expand any collapsed sections
    const collapsedSections = await page.locator('button[aria-expanded="false"]').all();
    for (const section of collapsedSections) {
      try {
        await section.click();
        await page.waitForTimeout(300);
      } catch (e) {
        // Ignore if not clickable
      }
    }

    await page.waitForTimeout(500);

    const screenshotName = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-edit-view.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotName),
      fullPage: true
    });

    // Count form fields
    const textInputs = await page.locator('input[type="text"], input[type="email"], input[type="url"]').count();
    const textareas = await page.locator('textarea').count();
    result.notes.push(`Form has ${textInputs} text inputs and ${textareas} textareas`);

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function testUpdate(resourceName, index) {
  console.log(`✏️  Testing UPDATE - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'UPDATE',
    status: 'PASS',
    notes: []
  };

  try {
    // Check if we're on an edit page
    const isEditPage = page.url().includes('/edit');

    if (!isEditPage) {
      await navigateToResource(resourceName);
      await page.waitForTimeout(1000);

      const tableRows = await page.locator('table tbody tr, .fi-ta-content tbody tr').count();
      if (tableRows === 0) {
        result.status = 'SKIP';
        result.notes.push('No records available to update');
        console.log(`⏭️  ${result.operation} - SKIP: No records available`);
        testResults.push(result);
        return result;
      }

      // Click edit on first row
      const editLink = await page.locator('table tbody tr a[href*="/edit"], .fi-ta-content tbody tr a[href*="/edit"]').first();
      if (await editLink.count() === 0) {
        result.status = 'SKIP';
        result.notes.push('Edit link not found');
        console.log(`⏭️  ${result.operation} - SKIP: Edit link not found`);
        testResults.push(result);
        return result;
      }

      await editLink.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
    }

    // Find meta_description field
    const metaDescInput = await page.locator('textarea[id*="meta_description"], textarea[name*="meta_description"], input[id*="meta_description"]').first();

    if (await metaDescInput.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Meta description field not found');
      console.log(`⏭️  ${result.operation} - SKIP: Meta description field not found`);
      testResults.push(result);
      return result;
    }

    // Get current value
    const originalValue = await metaDescInput.inputValue();
    result.notes.push(`Original value length: ${originalValue.length} chars`);

    // Update the field
    const timestamp = Date.now();
    const updatedValue = `${originalValue} - Updated via test ${timestamp}`;

    await metaDescInput.fill(updatedValue);
    await page.waitForTimeout(500);

    const screenshotBeforeSave = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-before-update.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotBeforeSave),
      fullPage: true
    });

    // Find and click Save button
    const saveButton = await page.locator('button:has-text("Save"), button[type="submit"]:has-text("Save changes")').first();

    if (await saveButton.count() === 0) {
      result.status = 'FAIL';
      result.notes.push('Save button not found');
      console.error(`❌ ${result.operation} - FAIL: Save button not found`);
      testResults.push(result);
      return result;
    }

    await saveButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const screenshotAfterSave = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-after-update.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotAfterSave),
      fullPage: true
    });

    // Check for success notification
    const successNotification = await page.locator('[role="status"], .fi-no-notification, text=/saved/i, text=/updated/i').count();
    if (successNotification > 0) {
      result.notes.push('✅ Success notification displayed');
    }

    result.notes.push(`After save URL: ${page.url()}`);

    // Verify the update by navigating back to edit
    if (!page.url().includes('/edit')) {
      await page.waitForTimeout(1000);
      const editLinks = await page.locator('a[href*="/edit"]').all();
      if (editLinks.length > 0) {
        await editLinks[0].click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1500);
      }
    }

    // Verify the change
    const verifyInput = await page.locator('textarea[id*="meta_description"], textarea[name*="meta_description"]').first();
    if (await verifyInput.count() > 0) {
      const verifiedValue = await verifyInput.inputValue();

      if (verifiedValue.includes(`Updated via test ${timestamp}`)) {
        result.notes.push('✅ Update verified successfully');

        const screenshotVerify = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-update-verified.png`;
        await page.screenshot({
          path: path.join(screenshotsDir, screenshotVerify),
          fullPage: true
        });
      } else {
        result.status = 'FAIL';
        result.notes.push('❌ Update not persisted correctly');
      }
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);

  // Navigate back to list
  await navigateToResource(resourceName);
  await page.waitForTimeout(1000);

  return result;
}

async function testCreate(resourceName, index) {
  console.log(`➕ Testing CREATE - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'CREATE',
    status: 'PASS',
    notes: [],
    createdRecordTitle: null
  };

  try {
    await navigateToResource(resourceName);
    await page.waitForTimeout(1000);

    // Find "New" button
    const createButton = await page.locator('a:has-text("New")').first();

    if (await createButton.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Create button not found');
      console.log(`⏭️  ${result.operation} - SKIP: Create button not found`);
      testResults.push(result);
      return result;
    }

    await createButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const timestamp = Date.now();
    const testTitle = `Test Page ${timestamp}`;
    const testSlug = `test-page-${timestamp}`;

    // Fill Title
    const titleInput = await page.locator('input[id*="title"], input[name="title"]').first();
    if (await titleInput.count() > 0) {
      await titleInput.fill(testTitle);
      result.notes.push(`Title: ${testTitle}`);
    } else {
      result.notes.push('⚠️ Title field not found');
    }

    await page.waitForTimeout(500);

    // Fill Slug (might auto-generate)
    const slugInput = await page.locator('input[id*="slug"], input[name="slug"]').first();
    if (await slugInput.count() > 0) {
      const slugValue = await slugInput.inputValue();
      if (!slugValue) {
        await slugInput.fill(testSlug);
        result.notes.push(`Slug: ${testSlug}`);
      } else {
        result.notes.push(`Slug: ${slugValue} (auto-generated)`);
      }
    }

    await page.waitForTimeout(500);

    // Fill Meta Title
    const metaTitleInput = await page.locator('input[id*="meta_title"], input[name*="meta_title"]').first();
    if (await metaTitleInput.count() > 0) {
      await metaTitleInput.fill('Test Meta Title');
      result.notes.push('Meta Title: Test Meta Title');
    }

    await page.waitForTimeout(500);

    // Fill Meta Description
    const metaDescInput = await page.locator('textarea[id*="meta_description"], textarea[name*="meta_description"]').first();
    if (await metaDescInput.count() > 0) {
      await metaDescInput.fill('Test meta description for automated testing');
      result.notes.push('Meta Description: Test meta description for automated testing');
    }

    await page.waitForTimeout(1000);

    const screenshotFilled = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-create-filled.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotFilled),
      fullPage: true
    });

    // Click Create/Save button
    const saveButton = await page.locator('button:has-text("Create"), button[type="submit"]').first();

    if (await saveButton.count() === 0) {
      result.status = 'FAIL';
      result.notes.push('Create/Save button not found');
      console.error(`❌ ${result.operation} - FAIL: Save button not found`);
      testResults.push(result);
      return result;
    }

    await saveButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const screenshotSuccess = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-create-success.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotSuccess),
      fullPage: true
    });

    result.notes.push(`After create URL: ${page.url()}`);

    // Navigate to list to verify
    await navigateToResource(resourceName);
    await page.waitForTimeout(1500);

    // Look for the test record
    const testRecordExists = await page.locator(`text="${testTitle}"`).count() > 0;

    if (testRecordExists) {
      result.notes.push('✅ New record found in list');
      result.createdRecordTitle = testTitle;

      const screenshotList = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-create-in-list.png`;
      await page.screenshot({
        path: path.join(screenshotsDir, screenshotList),
        fullPage: true
      });
    } else {
      result.status = 'FAIL';
      result.notes.push('❌ New record not found in list');
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function testDelete(resourceName, index, createdRecordTitle) {
  console.log(`🗑️  Testing DELETE - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'DELETE',
    status: 'PASS',
    notes: []
  };

  try {
    if (!createdRecordTitle) {
      result.status = 'SKIP';
      result.notes.push('No test record to delete');
      console.log(`⏭️  ${result.operation} - SKIP: No test record to delete`);
      testResults.push(result);
      return result;
    }

    await navigateToResource(resourceName);
    await page.waitForTimeout(1000);

    // Find the row with our test record
    const testRecordRow = await page.locator(`tr:has-text("${createdRecordTitle}")`).first();

    if (await testRecordRow.count() === 0) {
      result.status = 'FAIL';
      result.notes.push('Test record not found for deletion');
      console.error(`❌ ${result.operation} - FAIL: Test record not found`);
      testResults.push(result);
      return result;
    }

    // Look for delete button in the row
    let deleteButton = await testRecordRow.locator('button[aria-label*="Delete"], button:has-text("Delete")').first();

    if (await deleteButton.count() === 0) {
      // Try actions dropdown
      const actionsButton = await testRecordRow.locator('button[aria-label*="Actions"]').first();
      if (await actionsButton.count() > 0) {
        await actionsButton.click();
        await page.waitForTimeout(500);

        const screenshotActionsMenu = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-delete-actions-menu.png`;
        await page.screenshot({
          path: path.join(screenshotsDir, screenshotActionsMenu),
          fullPage: true
        });

        deleteButton = await page.locator('button:has-text("Delete"), [role="menuitem"]:has-text("Delete")').first();
      }
    }

    if (await deleteButton.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Delete button not found');
      console.log(`⏭️  ${result.operation} - SKIP: Delete button not found`);
      testResults.push(result);
      return result;
    }

    await deleteButton.click();
    await page.waitForTimeout(1000);

    const screenshotConfirm = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-delete-confirm.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotConfirm),
      fullPage: true
    });

    // Confirm deletion
    const confirmButton = await page.locator('button:has-text("Confirm"), button:has-text("Delete"), button:has-text("Yes")').first();
    if (await confirmButton.count() > 0) {
      await confirmButton.click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
    }

    const screenshotAfterDelete = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-after-delete.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotAfterDelete),
      fullPage: true
    });

    // Verify deletion
    const recordStillExists = await page.locator(`text="${createdRecordTitle}"`).count() > 0;

    if (!recordStillExists) {
      result.notes.push('✅ Record successfully deleted');
    } else {
      result.status = 'FAIL';
      result.notes.push('❌ Record still exists after deletion');
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function testFormValidation(resourceName, index) {
  console.log(`✔️  Testing Form Validation - ${resourceName}...`);

  const result = {
    resource: resourceName,
    operation: 'Form Validation',
    status: 'PASS',
    notes: []
  };

  try {
    await navigateToResource(resourceName);
    await page.waitForTimeout(1000);

    const createButton = await page.locator('a:has-text("New")').first();

    if (await createButton.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Create form not accessible');
      testResults.push(result);
      return result;
    }

    await createButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1500);

    // Try to submit empty form
    const saveButton = await page.locator('button:has-text("Create"), button[type="submit"]').first();
    await saveButton.click();
    await page.waitForTimeout(2000);

    const screenshotValidation = `${String(index).padStart(2, '0')}-${resourceName.toLowerCase().replace(/ /g, '-')}-validation.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotValidation),
      fullPage: true
    });

    // Check for validation errors
    const errorSelectors = [
      '.text-danger',
      '.error',
      '[role="alert"]',
      '.text-red-600',
      '.fi-fo-field-wrp-error-message',
      'p:has-text("required")',
      'p:has-text("field is required")'
    ];

    let errorCount = 0;
    for (const selector of errorSelectors) {
      errorCount += await page.locator(selector).count();
    }

    if (errorCount > 0) {
      result.notes.push(`✅ Form validation working: ${errorCount} error(s) displayed`);

      // Collect error messages
      for (const selector of errorSelectors) {
        const errors = await page.locator(selector).allTextContents();
        errors.forEach(err => {
          if (err.trim() && err.length < 100) {
            validationErrors.push(`${resourceName}: ${err.trim()}`);
          }
        });
      }
    } else {
      result.notes.push('⚠️ No validation errors displayed');
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function runFullTestSuite() {
  try {
    await setup();
    await login();

    console.log('=' .repeat(60));
    console.log('COMPREHENSIVE CRUD TESTS FOR ALL PAGE RESOURCES');
    console.log('=' .repeat(60) + '\n');

    for (let i = 0; i < PAGE_RESOURCES.length; i++) {
      const resourceName = PAGE_RESOURCES[i];
      const index = (i + 1) * 10;

      console.log('\n' + '=' .repeat(60));
      console.log(`Testing Resource ${i + 1}/${PAGE_RESOURCES.length}: ${resourceName}`);
      console.log('=' .repeat(60) + '\n');

      // 1. READ - List View
      await testReadListView(resourceName, index);

      // 2. READ - Edit View
      await testReadEditView(resourceName, index + 1);

      // 3. UPDATE
      await testUpdate(resourceName, index + 2);

      // 4. Form Validation
      await testFormValidation(resourceName, index + 3);

      // 5. CREATE (only for CV and Photography pages)
      let createdRecordTitle = null;
      if (resourceName === 'CV Page' || resourceName === 'Photography Page') {
        const createResult = await testCreate(resourceName, index + 4);
        createdRecordTitle = createResult.createdRecordTitle;

        // 6. DELETE
        if (createdRecordTitle) {
          await testDelete(resourceName, index + 5, createdRecordTitle);
        }
      }

      console.log(`\n✅ Completed testing ${resourceName}\n`);
      await page.waitForTimeout(1000);
    }

    await generateReport();

  } catch (error) {
    console.error('❌ Fatal error:', error);
  } finally {
    if (browser) {
      await browser.close();
    }
  }
}

async function generateReport() {
  console.log('\n' + '=' .repeat(60));
  console.log('TEST SUMMARY');
  console.log('=' .repeat(60) + '\n');

  const passCount = testResults.filter(r => r.status === 'PASS').length;
  const failCount = testResults.filter(r => r.status === 'FAIL').length;
  const skipCount = testResults.filter(r => r.status === 'SKIP').length;

  console.log(`Total Tests: ${testResults.length}`);
  console.log(`✅ Passed: ${passCount}`);
  console.log(`❌ Failed: ${failCount}`);
  console.log(`⏭️  Skipped: ${skipCount}`);
  console.log(`\nConsole Errors: ${consoleErrors.length}`);
  console.log(`Validation Errors: ${validationErrors.length}`);

  console.log('\n' + '-'.repeat(60));
  console.log('RESULTS BY RESOURCE');
  console.log('-'.repeat(60) + '\n');

  PAGE_RESOURCES.forEach(resourceName => {
    console.log(`\n${resourceName}:`);
    const resourceResults = testResults.filter(r => r.resource === resourceName);
    resourceResults.forEach(result => {
      const icon = result.status === 'PASS' ? '✅' : result.status === 'FAIL' ? '❌' : '⏭️';
      console.log(`  ${icon} ${result.operation}: ${result.status}`);
      if (result.notes.length > 0) {
        result.notes.forEach(note => {
          console.log(`     - ${note}`);
        });
      }
    });
  });

  if (consoleErrors.length > 0) {
    console.log('\n' + '-'.repeat(60));
    console.log('CONSOLE ERRORS (First 10)');
    console.log('-'.repeat(60) + '\n');
    consoleErrors.slice(0, 10).forEach(error => console.log(error));
  }

  if (validationErrors.length > 0) {
    console.log('\n' + '-'.repeat(60));
    console.log('VALIDATION ERRORS');
    console.log('-'.repeat(60) + '\n');
    validationErrors.forEach(error => console.log(error));
  }

  const reportData = {
    timestamp: new Date().toISOString(),
    summary: {
      total: testResults.length,
      passed: passCount,
      failed: failCount,
      skipped: skipCount
    },
    results: testResults,
    consoleErrors: consoleErrors,
    validationErrors: validationErrors,
    uiIssues: uiIssues
  };

  fs.writeFileSync(
    path.join(screenshotsDir, 'test-report.json'),
    JSON.stringify(reportData, null, 2)
  );

  console.log(`\n📊 Report: ${path.join(screenshotsDir, 'test-report.json')}`);
  console.log(`📸 Screenshots: ${screenshotsDir}`);
  console.log('\n' + '=' .repeat(60));
  console.log('TESTING COMPLETE');
  console.log('=' .repeat(60) + '\n');
}

runFullTestSuite().catch(console.error);
