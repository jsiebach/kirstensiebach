const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  const screenshotDir = '/Users/jsiebach/code/kirstensiebach/test-screenshots';

  // Create screenshot directory if it doesn't exist
  if (!fs.existsSync(screenshotDir)) {
    fs.mkdirSync(screenshotDir, { recursive: true });
  }

  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext({
    viewport: { width: 1920, height: 1080 }
  });
  const page = await context.newPage();

  // Track console errors
  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') {
      consoleErrors.push(`[${new Date().toISOString()}] ${msg.text()}`);
    }
  });

  // Track page errors
  const pageErrors = [];
  page.on('pageerror', error => {
    pageErrors.push(`[${new Date().toISOString()}] ${error.message}`);
  });

  const results = {
    loginTest: { success: false, errors: [] },
    dashboardTest: { success: false, errors: [] },
    resourceTests: {},
    createFormTest: { success: false, errors: [] },
    screenshots: [],
    consoleErrors: [],
    pageErrors: []
  };

  try {
    console.log('=== TEST 1: Login Test ===');

    // Navigate to login page
    await page.goto('http://localhost:8080/filament/login', { waitUntil: 'networkidle' });
    await page.waitForTimeout(2000);

    // Take screenshot of login page
    const loginScreenshot = path.join(screenshotDir, 'v2-01-login-page.png');
    await page.screenshot({ path: loginScreenshot, fullPage: true });
    results.screenshots.push(loginScreenshot);
    console.log('Screenshot saved: v2-01-login-page.png');

    // Fill in login form
    await page.fill('input[name="email"], input[type="email"]', 'jsiebach@gmail.com');
    await page.fill('input[name="password"], input[type="password"]', 'admin');

    // Take screenshot before submit
    const beforeSubmitScreenshot = path.join(screenshotDir, 'v2-01b-before-submit.png');
    await page.screenshot({ path: beforeSubmitScreenshot, fullPage: true });
    results.screenshots.push(beforeSubmitScreenshot);

    // Submit form
    await page.click('button[type="submit"]');

    // Wait for navigation after login
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(3000);

    // Check if we're logged in (should redirect to dashboard)
    const currentUrl = page.url();
    if (currentUrl.includes('/filament') && !currentUrl.includes('/login')) {
      results.loginTest.success = true;
      console.log('Login successful! Redirected to:', currentUrl);
    } else {
      results.loginTest.success = false;
      results.loginTest.errors.push('Login failed - still on login page or unexpected URL: ' + currentUrl);
      console.log('Login may have failed. Current URL:', currentUrl);
    }

    console.log('\n=== TEST 2: Dashboard Test ===');

    // Take screenshot of dashboard
    const dashboardScreenshot = path.join(screenshotDir, 'v2-02-dashboard.png');
    await page.screenshot({ path: dashboardScreenshot, fullPage: true });
    results.screenshots.push(dashboardScreenshot);
    console.log('Screenshot saved: v2-02-dashboard.png');

    results.dashboardTest.success = true;
    results.dashboardTest.errors = consoleErrors.length > 0 ? [...consoleErrors] : [];

    console.log('\n=== TEST 3: Resource Navigation Test ===');

    const resourcesToTest = [
      { name: 'Home Pages', urlPath: 'home-pages' },
      { name: 'Lab Pages', urlPath: 'lab-pages' },
      { name: 'Team Members', urlPath: 'team-members' },
      { name: 'Research', urlPath: 'research' },
      { name: 'Publications', urlPath: 'publications' }
    ];

    for (let i = 0; i < resourcesToTest.length; i++) {
      const resource = resourcesToTest[i];
      console.log(`\nTesting resource: ${resource.name}`);

      const errorsBefore = consoleErrors.length + pageErrors.length;

      try {
        // Navigate directly to resource
        await page.goto(`http://localhost:8080/filament/resources/${resource.urlPath}`, { waitUntil: 'networkidle' });
        await page.waitForTimeout(2000);

        // Take screenshot
        const screenshotName = `v2-03-${i + 1}-${resource.urlPath}.png`;
        const screenshotPath = path.join(screenshotDir, screenshotName);
        await page.screenshot({ path: screenshotPath, fullPage: true });
        results.screenshots.push(screenshotPath);
        console.log(`Screenshot saved: ${screenshotName}`);

        const errorsAfter = consoleErrors.length + pageErrors.length;
        const newErrors = errorsAfter - errorsBefore;

        results.resourceTests[resource.name] = {
          success: true,
          url: page.url(),
          errors: newErrors > 0 ? consoleErrors.slice(-newErrors) : []
        };

        console.log(`${resource.name} loaded successfully at: ${page.url()}`);

      } catch (error) {
        results.resourceTests[resource.name] = {
          success: false,
          error: error.message
        };
        console.log(`Error testing ${resource.name}: ${error.message}`);
      }
    }

    console.log('\n=== TEST 4: Create Form Test ===');

    try {
      // Navigate to Home Pages if not already there
      await page.goto('http://localhost:8080/filament/resources/home-pages', { waitUntil: 'networkidle' });
      await page.waitForTimeout(2000);

      // Look for Create button (try multiple selectors)
      const createButtonSelectors = [
        'a:has-text("New")',
        'a:has-text("Create")',
        'button:has-text("New")',
        'button:has-text("Create")',
        '[href*="/create"]',
        '.filament-button:has-text("New")'
      ];

      let createButton = null;
      for (const selector of createButtonSelectors) {
        const btn = page.locator(selector).first();
        if (await btn.count() > 0) {
          createButton = btn;
          console.log(`Found create button with selector: ${selector}`);
          break;
        }
      }

      if (createButton) {
        await createButton.click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(3000);

        // Take screenshot of create form
        const createFormScreenshot = path.join(screenshotDir, 'v2-04-create-form.png');
        await page.screenshot({ path: createFormScreenshot, fullPage: true });
        results.screenshots.push(createFormScreenshot);
        console.log('Screenshot saved: v2-04-create-form.png');

        // Check for form fields
        const formExists = await page.$('form');
        if (formExists) {
          results.createFormTest.success = true;
          console.log('Create form loaded successfully');

          // Check for Section components
          const sections = await page.$$('[data-field-wrapper], .filament-forms-section-component, section');
          console.log(`Found ${sections.length} form sections/components`);
        } else {
          results.createFormTest.errors.push('Form element not found on page');
        }
      } else {
        results.createFormTest.errors.push('Create button not found with any selector');
        console.log('Create button not found, taking screenshot of current page');

        const noCreateButtonScreenshot = path.join(screenshotDir, 'v2-04-no-create-button.png');
        await page.screenshot({ path: noCreateButtonScreenshot, fullPage: true });
        results.screenshots.push(noCreateButtonScreenshot);
      }

    } catch (error) {
      results.createFormTest.errors.push(error.message);
      console.log(`Error in create form test: ${error.message}`);
    }

    console.log('\n=== TEST 5: Edit Form Test ===');

    try {
      // Go back to list page
      await page.goto('http://localhost:8080/filament/resources/home-pages', { waitUntil: 'networkidle' });
      await page.waitForTimeout(2000);

      // Look for first edit button/link in table
      const editLink = await page.locator('a[href*="/edit"], button[aria-label*="Edit"]').first();

      if (await editLink.count() > 0) {
        await editLink.click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(3000);

        // Take screenshot of edit form
        const editFormScreenshot = path.join(screenshotDir, 'v2-05-edit-form.png');
        await page.screenshot({ path: editFormScreenshot, fullPage: true });
        results.screenshots.push(editFormScreenshot);
        console.log('Screenshot saved: v2-05-edit-form.png');
        console.log('Edit form loaded successfully');
      } else {
        console.log('No records found to edit');
      }

    } catch (error) {
      console.log(`Error in edit form test: ${error.message}`);
    }

    // Collect all errors
    results.consoleErrors = consoleErrors;
    results.pageErrors = pageErrors;

    console.log('\n=== TEST RESULTS SUMMARY ===');
    console.log('Login Test:', results.loginTest.success ? 'PASS' : 'FAIL');
    console.log('Dashboard Test:', results.dashboardTest.success ? 'PASS' : 'FAIL');
    console.log('Resource Tests:');
    for (const [name, test] of Object.entries(results.resourceTests)) {
      console.log(`  - ${name}:`, test.success ? 'PASS' : 'FAIL');
    }
    console.log('Create Form Test:', results.createFormTest.success ? 'PASS' : 'FAIL');
    console.log('\nTotal Console Errors:', consoleErrors.length);
    console.log('Total Page Errors:', pageErrors.length);
    console.log('Screenshots taken:', results.screenshots.length);

    if (consoleErrors.length > 0) {
      console.log('\nConsole Errors (first 10):');
      consoleErrors.slice(0, 10).forEach(err => console.log('  -', err));
    }

    if (pageErrors.length > 0) {
      console.log('\nPage Errors:');
      pageErrors.forEach(err => console.log('  -', err));
    }

    // Save detailed results to file
    const resultsPath = path.join(screenshotDir, 'test-results-v2.json');
    fs.writeFileSync(resultsPath, JSON.stringify(results, null, 2));
    console.log(`\nDetailed results saved to: ${resultsPath}`);

  } catch (error) {
    console.error('Test execution error:', error);
  } finally {
    await browser.close();
    console.log('\nBrowser closed. Test complete.');
  }
})();
