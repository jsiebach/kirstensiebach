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
    const loginScreenshot = path.join(screenshotDir, '01-login-page.png');
    await page.screenshot({ path: loginScreenshot, fullPage: true });
    results.screenshots.push(loginScreenshot);
    console.log('Screenshot saved: 01-login-page.png');

    // Fill in login form
    await page.fill('input[name="email"], input[type="email"]', 'jsiebach@gmail.com');
    await page.fill('input[name="password"], input[type="password"]', 'password');

    // Submit form
    await page.click('button[type="submit"]');

    // Wait for navigation after login
    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

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
    const dashboardScreenshot = path.join(screenshotDir, '02-dashboard.png');
    await page.screenshot({ path: dashboardScreenshot, fullPage: true });
    results.screenshots.push(dashboardScreenshot);
    console.log('Screenshot saved: 02-dashboard.png');

    results.dashboardTest.success = true;
    results.dashboardTest.errors = consoleErrors.length > 0 ? [...consoleErrors] : [];

    console.log('\n=== TEST 3: Resource Navigation Test ===');

    const resourcesToTest = [
      { name: 'Home Pages', selector: 'a[href*="home-pages"], a:has-text("Home Pages")' },
      { name: 'Lab Pages', selector: 'a[href*="lab-pages"], a:has-text("Lab Pages")' },
      { name: 'Team Members', selector: 'a[href*="team-members"], a:has-text("Team Members")' },
      { name: 'Research', selector: 'a[href*="research"], a:has-text("Research")' },
      { name: 'Publications', selector: 'a[href*="publications"], a:has-text("Publications")' }
    ];

    for (let i = 0; i < resourcesToTest.length; i++) {
      const resource = resourcesToTest[i];
      console.log(`\nTesting resource: ${resource.name}`);

      const errorsBefore = consoleErrors.length + pageErrors.length;

      try {
        // Try to find and click the navigation link
        await page.waitForTimeout(1000);

        // Try multiple strategies to find the link
        let linkFound = false;

        // Strategy 1: Try href-based selector
        const hrefLink = await page.$(`a[href*="${resource.name.toLowerCase().replace(' ', '-')}"]`);
        if (hrefLink) {
          await hrefLink.click();
          linkFound = true;
        } else {
          // Strategy 2: Try text-based selector
          const textLink = await page.locator(`text="${resource.name}"`).first();
          if (await textLink.count() > 0) {
            await textLink.click();
            linkFound = true;
          }
        }

        if (!linkFound) {
          console.log(`Could not find navigation link for ${resource.name}, trying direct URL...`);
          const urlPath = resource.name.toLowerCase().replace(' ', '-');
          await page.goto(`http://localhost:8080/filament/resources/${urlPath}`, { waitUntil: 'networkidle' });
        }

        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);

        // Take screenshot
        const screenshotName = `03-${i + 1}-${resource.name.toLowerCase().replace(' ', '-')}.png`;
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
      // Try to navigate to Home Pages if not already there
      await page.goto('http://localhost:8080/filament/resources/home-pages', { waitUntil: 'networkidle' });
      await page.waitForTimeout(2000);

      // Look for Create button
      const createButton = await page.locator('a:has-text("Create"), a:has-text("New"), button:has-text("Create"), button:has-text("New")').first();

      if (await createButton.count() > 0) {
        await createButton.click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);

        // Take screenshot of create form
        const createFormScreenshot = path.join(screenshotDir, '04-create-form.png');
        await page.screenshot({ path: createFormScreenshot, fullPage: true });
        results.screenshots.push(createFormScreenshot);
        console.log('Screenshot saved: 04-create-form.png');

        // Check for form fields
        const formExists = await page.$('form');
        if (formExists) {
          results.createFormTest.success = true;
          console.log('Create form loaded successfully');
        } else {
          results.createFormTest.errors.push('Form element not found on page');
        }
      } else {
        results.createFormTest.errors.push('Create button not found');
        console.log('Create button not found, taking screenshot of current page');

        const noCreateButtonScreenshot = path.join(screenshotDir, '04-no-create-button.png');
        await page.screenshot({ path: noCreateButtonScreenshot, fullPage: true });
        results.screenshots.push(noCreateButtonScreenshot);
      }

    } catch (error) {
      results.createFormTest.errors.push(error.message);
      console.log(`Error in create form test: ${error.message}`);
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
      console.log('\nConsole Errors:');
      consoleErrors.forEach(err => console.log('  -', err));
    }

    if (pageErrors.length > 0) {
      console.log('\nPage Errors:');
      pageErrors.forEach(err => console.log('  -', err));
    }

    // Save detailed results to file
    const resultsPath = path.join(screenshotDir, 'test-results.json');
    fs.writeFileSync(resultsPath, JSON.stringify(results, null, 2));
    console.log(`\nDetailed results saved to: ${resultsPath}`);

  } catch (error) {
    console.error('Test execution error:', error);
  } finally {
    await browser.close();
    console.log('\nBrowser closed. Test complete.');
  }
})();
