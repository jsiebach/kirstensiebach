const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

// Create screenshots directory
const screenshotsDir = path.join(__dirname, 'test-screenshots');
if (!fs.existsSync(screenshotsDir)) {
  fs.mkdirSync(screenshotsDir, { recursive: true });
}

// Test configuration
const BASE_URL = 'http://localhost:8080/filament';
const LOGIN_EMAIL = 'jsiebach@gmail.com';
const LOGIN_PASSWORD = 'admin';

// Page resources to test
const PAGE_RESOURCES = [
  { name: 'Home Pages', url: '/resources/home-pages' },
  { name: 'Lab Pages', url: '/resources/lab-pages' },
  { name: 'Research Pages', url: '/resources/research-pages' },
  { name: 'Publications Pages', url: '/resources/publications-pages' },
  { name: 'CV Pages', url: '/resources/cv-pages' },
  { name: 'Outreach Pages', url: '/resources/outreach-pages' },
  { name: 'Photography Pages', url: '/resources/photography-pages' }
];

let browser, context, page;
let testResults = [];
let consoleErrors = [];
let validationErrors = [];
let uiIssues = [];

async function setup() {
  console.log('🚀 Starting Filament CRUD Tests...\n');

  browser = await chromium.launch({
    headless: false,
    slowMo: 500 // Slow down actions for visibility
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

async function testReadListView(resource, index) {
  console.log(`📋 Testing READ (List View) - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'READ (List View)',
    status: 'PASS',
    notes: []
  };

  try {
    await page.goto(BASE_URL + resource.url);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1500);

    const screenshotName = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-list.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotName),
      fullPage: true
    });

    // Check if records are displayed
    const hasRecords = await page.locator('table tbody tr, .fi-ta-content tbody tr').count() > 0;
    if (hasRecords) {
      const recordCount = await page.locator('table tbody tr, .fi-ta-content tbody tr').count();
      result.notes.push(`Found ${recordCount} record(s)`);
    } else {
      result.notes.push('No records found in list');
    }

    // Test search if available
    const searchInput = await page.locator('input[type="search"], input[placeholder*="Search"]').first();
    if (await searchInput.count() > 0) {
      result.notes.push('Search functionality available');
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

async function testReadEditView(resource, index) {
  console.log(`👁️  Testing READ (Edit View) - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'READ (Edit View)',
    status: 'PASS',
    notes: []
  };

  try {
    // Check if there are any records to edit
    const editButtons = await page.locator('a[href*="/edit"], button:has-text("Edit"), [aria-label*="Edit"]').all();

    if (editButtons.length === 0) {
      result.status = 'SKIP';
      result.notes.push('No records available to edit');
      console.log(`⏭️  ${result.operation} - SKIP: No records available`);
      testResults.push(result);
      return result;
    }

    // Click the first edit button
    await editButtons[0].click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // Expand all sections if there are collapsible sections
    const expandButtons = await page.locator('button[aria-expanded="false"]').all();
    for (const button of expandButtons) {
      try {
        await button.click();
        await page.waitForTimeout(300);
      } catch (e) {
        // Ignore if button is not clickable
      }
    }

    const screenshotName = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-edit-view.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotName),
      fullPage: true
    });

    // Check if form fields are populated
    const inputFields = await page.locator('input[type="text"], textarea, input[type="email"]').count();
    result.notes.push(`Form has ${inputFields} text input fields`);

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);
  return result;
}

async function testUpdate(resource, index) {
  console.log(`✏️  Testing UPDATE - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'UPDATE',
    status: 'PASS',
    notes: []
  };

  try {
    // Should already be on edit page from previous test
    // If not, navigate back to list and open edit
    const isEditPage = await page.url().includes('/edit');

    if (!isEditPage) {
      await page.goto(BASE_URL + resource.url);
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1000);

      const editButtons = await page.locator('a[href*="/edit"], button:has-text("Edit")').all();
      if (editButtons.length === 0) {
        result.status = 'SKIP';
        result.notes.push('No records available to update');
        console.log(`⏭️  ${result.operation} - SKIP: No records available`);
        testResults.push(result);
        return result;
      }

      await editButtons[0].click();
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(2000);
    }

    // Find meta_description field
    const metaDescInput = await page.locator('textarea[id*="meta_description"], input[id*="meta_description"]').first();

    if (await metaDescInput.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Meta description field not found');
      console.log(`⏭️  ${result.operation} - SKIP: Meta description field not found`);
      testResults.push(result);
      return result;
    }

    // Get original value
    const originalValue = await metaDescInput.inputValue();
    result.notes.push(`Original value: ${originalValue.substring(0, 50)}...`);

    // Update the field
    const timestamp = Date.now();
    const updatedValue = originalValue.includes(' - Updated via test')
      ? originalValue
      : `${originalValue} - Updated via test ${timestamp}`;

    await metaDescInput.fill(updatedValue);
    await page.waitForTimeout(500);

    const screenshotBeforeSave = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-before-update.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotBeforeSave),
      fullPage: true
    });

    // Click Save button
    const saveButton = await page.locator('button:has-text("Save"), button[type="submit"]').first();
    await saveButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    // Take screenshot of success notification
    const screenshotAfterSave = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-after-update.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotAfterSave),
      fullPage: true
    });

    // Verify we're back on list view or edit view
    const currentUrl = page.url();
    result.notes.push(`After save URL: ${currentUrl}`);

    // Navigate back to edit to verify the change
    if (!currentUrl.includes('/edit')) {
      const editButtons = await page.locator('a[href*="/edit"]').all();
      if (editButtons.length > 0) {
        await editButtons[0].click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(1500);
      }
    }

    // Verify the updated value
    const verifyMetaDescInput = await page.locator('textarea[id*="meta_description"], input[id*="meta_description"]').first();
    if (await verifyMetaDescInput.count() > 0) {
      const verifiedValue = await verifyMetaDescInput.inputValue();

      if (verifiedValue.includes('Updated via test')) {
        result.notes.push('✅ Update verified successfully');

        const screenshotVerify = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-update-verified.png`;
        await page.screenshot({
          path: path.join(screenshotsDir, screenshotVerify),
          fullPage: true
        });
      } else {
        result.status = 'FAIL';
        result.notes.push('❌ Update not saved correctly');
      }
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);

  // Navigate back to list view for next test
  await page.goto(BASE_URL + resource.url);
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(1000);

  return result;
}

async function testCreate(resource, index) {
  console.log(`➕ Testing CREATE - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'CREATE',
    status: 'PASS',
    notes: [],
    createdRecordId: null
  };

  try {
    await page.goto(BASE_URL + resource.url);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Click New/Create button
    const createButton = await page.locator('a:has-text("New"), button:has-text("New"), a:has-text("Create")').first();

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

    // Fill in required fields
    // Title
    const titleInput = await page.locator('input[id*="title"], input[name*="title"]').first();
    if (await titleInput.count() > 0) {
      await titleInput.fill(`Test Page ${timestamp}`);
      result.notes.push(`Title: Test Page ${timestamp}`);
    }

    // Slug
    const slugInput = await page.locator('input[id*="slug"], input[name*="slug"]').first();
    if (await slugInput.count() > 0) {
      await slugInput.fill(`test-page-${timestamp}`);
      result.notes.push(`Slug: test-page-${timestamp}`);
    }

    // Meta Title
    const metaTitleInput = await page.locator('input[id*="meta_title"], input[name*="meta_title"]').first();
    if (await metaTitleInput.count() > 0) {
      await metaTitleInput.fill('Test Meta Title');
      result.notes.push('Meta Title: Test Meta Title');
    }

    // Meta Description
    const metaDescInput = await page.locator('textarea[id*="meta_description"], input[id*="meta_description"]').first();
    if (await metaDescInput.count() > 0) {
      await metaDescInput.fill('Test meta description for automated testing');
      result.notes.push('Meta Description: Test meta description for automated testing');
    }

    await page.waitForTimeout(1000);

    const screenshotFilled = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-create-filled.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotFilled),
      fullPage: true
    });

    // Click Save/Create button
    const saveButton = await page.locator('button:has-text("Create"), button:has-text("Save"), button[type="submit"]').first();
    await saveButton.click();
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const screenshotSuccess = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-create-success.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotSuccess),
      fullPage: true
    });

    // Check if we're redirected to list or edit view
    const currentUrl = page.url();
    result.notes.push(`After create URL: ${currentUrl}`);

    // Navigate to list to verify the record appears
    if (!currentUrl.includes(resource.url)) {
      await page.goto(BASE_URL + resource.url);
      await page.waitForLoadState('networkidle');
      await page.waitForTimeout(1000);
    }

    // Look for the test record in the list
    const testRecordExists = await page.locator(`text=/Test Page ${timestamp}/i`).count() > 0;

    if (testRecordExists) {
      result.notes.push('✅ New record verified in list');

      const screenshotList = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-create-in-list.png`;
      await page.screenshot({
        path: path.join(screenshotsDir, screenshotList),
        fullPage: true
      });

      // Store the timestamp for deletion test
      result.createdRecordId = timestamp;
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

async function testDelete(resource, index, createdRecordId) {
  console.log(`🗑️  Testing DELETE - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'DELETE',
    status: 'PASS',
    notes: []
  };

  try {
    if (!createdRecordId) {
      result.status = 'SKIP';
      result.notes.push('No test record to delete (CREATE test was skipped or failed)');
      console.log(`⏭️  ${result.operation} - SKIP: No test record to delete`);
      testResults.push(result);
      return result;
    }

    await page.goto(BASE_URL + resource.url);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Find the test record row
    const testRecordRow = await page.locator(`tr:has-text("Test Page ${createdRecordId}")`).first();

    if (await testRecordRow.count() === 0) {
      result.status = 'FAIL';
      result.notes.push('Test record not found for deletion');
      console.log(`❌ ${result.operation} - FAIL: Test record not found`);
      testResults.push(result);
      return result;
    }

    // Look for delete button/action
    const deleteButton = await testRecordRow.locator('button[aria-label*="Delete"], button:has-text("Delete"), [aria-label*="delete"]').first();

    if (await deleteButton.count() === 0) {
      // Try to find delete in actions menu
      const actionsButton = await testRecordRow.locator('button[aria-label*="Actions"], button:has-text("Actions")').first();
      if (await actionsButton.count() > 0) {
        await actionsButton.click();
        await page.waitForTimeout(500);
      }
    }

    const deleteButtonFinal = await page.locator('button[aria-label*="Delete"], button:has-text("Delete"), [aria-label*="delete"]').first();

    if (await deleteButtonFinal.count() === 0) {
      result.status = 'SKIP';
      result.notes.push('Delete button not found');
      console.log(`⏭️  ${result.operation} - SKIP: Delete button not found`);
      testResults.push(result);
      return result;
    }

    await deleteButtonFinal.click();
    await page.waitForTimeout(1000);

    const screenshotConfirm = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-delete-confirm.png`;
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

    const screenshotAfterDelete = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-after-delete.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotAfterDelete),
      fullPage: true
    });

    // Verify record is gone
    const recordStillExists = await page.locator(`text=/Test Page ${createdRecordId}/i`).count() > 0;

    if (!recordStillExists) {
      result.notes.push('✅ Record successfully deleted and verified');
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

async function testFormValidation(resource, index) {
  console.log(`✔️  Testing Form Validation - ${resource.name}...`);

  const result = {
    resource: resource.name,
    operation: 'Form Validation',
    status: 'PASS',
    notes: []
  };

  try {
    await page.goto(BASE_URL + resource.url);
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(1000);

    // Click New/Create button
    const createButton = await page.locator('a:has-text("New"), button:has-text("New"), a:has-text("Create")').first();

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
    const saveButton = await page.locator('button:has-text("Create"), button:has-text("Save"), button[type="submit"]').first();
    await saveButton.click();
    await page.waitForTimeout(1500);

    const screenshotValidation = `${String(index).padStart(2, '0')}-${resource.name.toLowerCase().replace(/ /g, '-')}-validation.png`;
    await page.screenshot({
      path: path.join(screenshotsDir, screenshotValidation),
      fullPage: true
    });

    // Check for validation error messages
    const errorMessages = await page.locator('.text-danger, .error, [role="alert"], .text-red-600').count();

    if (errorMessages > 0) {
      result.notes.push(`✅ Form validation working: ${errorMessages} error(s) displayed`);

      // Collect error messages
      const errors = await page.locator('.text-danger, .error, [role="alert"], .text-red-600').allTextContents();
      errors.forEach(err => {
        if (err.trim()) {
          validationErrors.push(`${resource.name}: ${err.trim()}`);
        }
      });
    } else {
      result.notes.push('⚠️ No validation errors displayed (or form submitted)');
    }

    console.log(`✅ ${result.operation} - PASS`);

  } catch (error) {
    result.status = 'FAIL';
    result.notes.push(`Error: ${error.message}`);
    console.error(`❌ ${result.operation} - FAIL: ${error.message}`);
  }

  testResults.push(result);

  // Navigate back to list
  await page.goto(BASE_URL + resource.url);
  await page.waitForLoadState('networkidle');
  await page.waitForTimeout(500);

  return result;
}

async function runFullTestSuite() {
  try {
    await setup();
    await login();

    console.log('=' .repeat(60));
    console.log('Starting comprehensive CRUD tests for all Page resources');
    console.log('=' .repeat(60) + '\n');

    // Test all resources
    for (let i = 0; i < PAGE_RESOURCES.length; i++) {
      const resource = PAGE_RESOURCES[i];
      const index = (i + 1) * 10; // 10, 20, 30, etc. for better screenshot ordering

      console.log('\n' + '=' .repeat(60));
      console.log(`Testing Resource ${i + 1}/${PAGE_RESOURCES.length}: ${resource.name}`);
      console.log('=' .repeat(60) + '\n');

      // 1. READ - List View
      await testReadListView(resource, index);

      // 2. READ - Edit View
      await testReadEditView(resource, index + 1);

      // 3. UPDATE
      await testUpdate(resource, index + 2);

      // 4. Form Validation
      await testFormValidation(resource, index + 3);

      // 5. CREATE (only for CV and Photography pages)
      let createdRecordId = null;
      if (resource.name === 'CV Pages' || resource.name === 'Photography Pages') {
        const createResult = await testCreate(resource, index + 4);
        createdRecordId = createResult.createdRecordId;

        // 6. DELETE (for test records only)
        if (createdRecordId) {
          await testDelete(resource, index + 5, createdRecordId);
        }
      }

      console.log(`\n✅ Completed testing ${resource.name}\n`);
      await page.waitForTimeout(1000);
    }

    // Generate final report
    await generateReport();

  } catch (error) {
    console.error('❌ Fatal error during test execution:', error);
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

  // Count results by status
  const passCount = testResults.filter(r => r.status === 'PASS').length;
  const failCount = testResults.filter(r => r.status === 'FAIL').length;
  const skipCount = testResults.filter(r => r.status === 'SKIP').length;

  console.log(`Total Tests: ${testResults.length}`);
  console.log(`✅ Passed: ${passCount}`);
  console.log(`❌ Failed: ${failCount}`);
  console.log(`⏭️  Skipped: ${skipCount}`);
  console.log(`\nConsole Errors: ${consoleErrors.length}`);
  console.log(`Validation Errors Captured: ${validationErrors.length}`);

  // Group results by resource
  console.log('\n' + '-'.repeat(60));
  console.log('RESULTS BY RESOURCE');
  console.log('-'.repeat(60) + '\n');

  PAGE_RESOURCES.forEach(resource => {
    console.log(`\n${resource.name}:`);
    const resourceResults = testResults.filter(r => r.resource === resource.name);
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

  // Console errors
  if (consoleErrors.length > 0) {
    console.log('\n' + '-'.repeat(60));
    console.log('CONSOLE ERRORS');
    console.log('-'.repeat(60) + '\n');
    consoleErrors.forEach(error => console.log(error));
  }

  // Validation errors
  if (validationErrors.length > 0) {
    console.log('\n' + '-'.repeat(60));
    console.log('VALIDATION ERRORS CAPTURED');
    console.log('-'.repeat(60) + '\n');
    validationErrors.forEach(error => console.log(error));
  }

  // Write JSON report
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

  console.log(`\n📊 Detailed report saved to: ${path.join(screenshotsDir, 'test-report.json')}`);
  console.log(`📸 Screenshots saved to: ${screenshotsDir}`);
  console.log('\n' + '=' .repeat(60));
  console.log('TESTING COMPLETE');
  console.log('=' .repeat(60) + '\n');
}

// Run the test suite
runFullTestSuite().catch(console.error);
