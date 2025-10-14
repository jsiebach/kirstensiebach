const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

(async () => {
  const screenshotDir = '/Users/jsiebach/code/kirstensiebach/test-screenshots';

  if (!fs.existsSync(screenshotDir)) {
    fs.mkdirSync(screenshotDir, { recursive: true });
  }

  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext({
    viewport: { width: 1920, height: 1080 }
  });
  const page = await context.newPage();

  const consoleErrors = [];
  page.on('console', msg => {
    if (msg.type() === 'error') {
      consoleErrors.push(`[${new Date().toISOString()}] ${msg.text()}`);
    }
  });

  const pageErrors = [];
  page.on('pageerror', error => {
    pageErrors.push(`[${new Date().toISOString()}] ${error.message}`);
  });

  const results = {
    loginTest: { success: false, errors: [] },
    dashboardTest: { success: false, errors: [] },
    navigationTests: {},
    createFormTest: { success: false, errors: [] },
    editFormTest: { success: false, errors: [] },
    screenshots: [],
    consoleErrors: [],
    pageErrors: []
  };

  try {
    console.log('=== TEST 1: Login Test ===');

    await page.goto('http://localhost:8080/filament/login', { waitUntil: 'networkidle' });
    await page.waitForTimeout(1500);

    const loginScreenshot = path.join(screenshotDir, 'v3-01-login-page.png');
    await page.screenshot({ path: loginScreenshot, fullPage: true });
    results.screenshots.push(loginScreenshot);
    console.log('Screenshot saved: v3-01-login-page.png');

    await page.fill('input[name="email"], input[type="email"]', 'jsiebach@gmail.com');
    await page.fill('input[name="password"], input[type="password"]', 'admin');
    await page.click('button[type="submit"]');

    await page.waitForLoadState('networkidle');
    await page.waitForTimeout(2000);

    const currentUrl = page.url();
    if (currentUrl.includes('/filament') && !currentUrl.includes('/login')) {
      results.loginTest.success = true;
      console.log('Login successful! Redirected to:', currentUrl);
    } else {
      results.loginTest.errors.push('Login failed - still on login page');
      console.log('Login failed. Current URL:', currentUrl);
    }

    console.log('\n=== TEST 2: Dashboard Test ===');

    const dashboardScreenshot = path.join(screenshotDir, 'v3-02-dashboard.png');
    await page.screenshot({ path: dashboardScreenshot, fullPage: true });
    results.screenshots.push(dashboardScreenshot);
    console.log('Screenshot saved: v3-02-dashboard.png');

    results.dashboardTest.success = true;

    console.log('\n=== TEST 3: Navigation Test - Click Through Sidebar ===');

    // Get all navigation links from sidebar
    const navItems = [
      'Home Page',
      'Lab Page',
      'Research Page',
      'Publications Page',
      'Team Members',
      'Research Projects',
      'Publications'
    ];

    for (let i = 0; i < navItems.length; i++) {
      const navItem = navItems[i];
      console.log(`\nTesting navigation: ${navItem}`);

      const errorsBefore = consoleErrors.length + pageErrors.length;

      try {
        // Find and click the navigation item
        const navLink = page.locator(`nav a:has-text("${navItem}")`).first();

        if (await navLink.count() > 0) {
          await navLink.click();
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(2000);

          const screenshotName = `v3-03-${i + 1}-${navItem.toLowerCase().replace(/ /g, '-')}.png`;
          const screenshotPath = path.join(screenshotDir, screenshotName);
          await page.screenshot({ path: screenshotPath, fullPage: true });
          results.screenshots.push(screenshotPath);
          console.log(`Screenshot saved: ${screenshotName}`);

          const errorsAfter = consoleErrors.length + pageErrors.length;
          const newErrors = errorsAfter - errorsBefore;

          // Check if we got a 404 by looking at page content
          const bodyText = await page.textContent('body');
          const is404 = bodyText.includes('404') || bodyText.includes('NOT FOUND');

          results.navigationTests[navItem] = {
            success: !is404,
            url: page.url(),
            errors: newErrors > 0 ? consoleErrors.slice(-newErrors) : [],
            is404: is404
          };

          if (is404) {
            console.log(`${navItem} returned 404`);
          } else {
            console.log(`${navItem} loaded successfully at: ${page.url()}`);
          }
        } else {
          results.navigationTests[navItem] = {
            success: false,
            error: 'Navigation link not found'
          };
          console.log(`Navigation link not found for: ${navItem}`);
        }

        // Go back to dashboard for next test
        await page.goto('http://localhost:8080/filament', { waitUntil: 'networkidle' });
        await page.waitForTimeout(1000);

      } catch (error) {
        results.navigationTests[navItem] = {
          success: false,
          error: error.message
        };
        console.log(`Error testing ${navItem}: ${error.message}`);
      }
    }

    console.log('\n=== TEST 4: Team Members - Full Page Test ===');

    try {
      // Navigate to Team Members
      const teamMembersLink = page.locator('nav a:has-text("Team Members")').first();
      if (await teamMembersLink.count() > 0) {
        await teamMembersLink.click();
        await page.waitForLoadState('networkidle');
        await page.waitForTimeout(2000);

        const bodyText = await page.textContent('body');
        const is404 = bodyText.includes('404') || bodyText.includes('NOT FOUND');

        if (!is404) {
          console.log('Team Members page loaded successfully');

          // Look for action buttons (Create, Edit, etc.)
          const createButton = page.locator('a:has-text("New"), a:has-text("Create"), button:has-text("New"), button:has-text("Create")').first();

          if (await createButton.count() > 0) {
            console.log('Found Create button, clicking...');
            await createButton.click();
            await page.waitForLoadState('networkidle');
            await page.waitForTimeout(3000);

            const createFormScreenshot = path.join(screenshotDir, 'v3-04-team-member-create-form.png');
            await page.screenshot({ path: createFormScreenshot, fullPage: true });
            results.screenshots.push(createFormScreenshot);
            console.log('Screenshot saved: v3-04-team-member-create-form.png');

            const formExists = await page.$('form');
            if (formExists) {
              results.createFormTest.success = true;
              console.log('Create form loaded successfully');
            }
          } else {
            console.log('No Create button found on Team Members page');
          }

          // Try to find edit button if there are records
          await page.goto('http://localhost:8080/filament', { waitUntil: 'networkidle' });
          await page.waitForTimeout(1000);

          const teamMembersLinkAgain = page.locator('nav a:has-text("Team Members")').first();
          await teamMembersLinkAgain.click();
          await page.waitForLoadState('networkidle');
          await page.waitForTimeout(2000);

          const editButton = page.locator('a[href*="/edit"], button[aria-label*="Edit"], td a').first();

          if (await editButton.count() > 0) {
            console.log('Found Edit button/link, clicking...');
            await editButton.click();
            await page.waitForLoadState('networkidle');
            await page.waitForTimeout(3000);

            const editFormScreenshot = path.join(screenshotDir, 'v3-05-team-member-edit-form.png');
            await page.screenshot({ path: editFormScreenshot, fullPage: true });
            results.screenshots.push(editFormScreenshot);
            console.log('Screenshot saved: v3-05-team-member-edit-form.png');

            results.editFormTest.success = true;
            console.log('Edit form loaded successfully');
          } else {
            console.log('No Edit button found (may be no records)');
          }
        } else {
          console.log('Team Members page returned 404');
        }
      }

    } catch (error) {
      console.log(`Error in Team Members test: ${error.message}`);
    }

    results.consoleErrors = consoleErrors;
    results.pageErrors = pageErrors;

    console.log('\n=== TEST RESULTS SUMMARY ===');
    console.log('Login Test:', results.loginTest.success ? 'PASS' : 'FAIL');
    console.log('Dashboard Test:', results.dashboardTest.success ? 'PASS' : 'FAIL');
    console.log('\nNavigation Tests:');
    for (const [name, test] of Object.entries(results.navigationTests)) {
      const status = test.success ? 'PASS' : (test.is404 ? '404' : 'FAIL');
      console.log(`  - ${name}: ${status}`);
    }
    console.log('\nForm Tests:');
    console.log('  - Create Form:', results.createFormTest.success ? 'PASS' : 'NOT TESTED');
    console.log('  - Edit Form:', results.editFormTest.success ? 'PASS' : 'NOT TESTED');
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

    const resultsPath = path.join(screenshotDir, 'test-results-v3.json');
    fs.writeFileSync(resultsPath, JSON.stringify(results, null, 2));
    console.log(`\nDetailed results saved to: ${resultsPath}`);

  } catch (error) {
    console.error('Test execution error:', error);
  } finally {
    await browser.close();
    console.log('\nBrowser closed. Test complete.');
  }
})();
