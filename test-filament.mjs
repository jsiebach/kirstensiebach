import { chromium } from 'playwright';
import { mkdir } from 'fs/promises';
import { join, dirname } from 'path';
import { fileURLToPath } from 'url';

const __dirname = dirname(fileURLToPath(import.meta.url));

async function testFilamentLogin() {
  let browser;

  try {
    console.log('Launching browser...');
    browser = await chromium.launch({ headless: false });
    const context = await browser.newContext({
      viewport: { width: 1280, height: 720 }
    });
    const page = await context.newPage();

    // Collect console messages
    const consoleMessages = [];
    const errors = [];

    page.on('console', msg => {
      const text = msg.text();
      consoleMessages.push({ type: msg.type(), text });
      console.log(`[${msg.type()}] ${text}`);
    });

    page.on('pageerror', error => {
      errors.push(error.message);
      console.error(`[PAGE ERROR] ${error.message}`);
    });

    // Navigate to Filament login
    console.log('\nNavigating to Filament login page...');
    const response = await page.goto('http://localhost:8080/filament/login', {
      waitUntil: 'networkidle',
      timeout: 30000
    });

    console.log(`Response status: ${response.status()}`);

    // Wait a bit for any dynamic content
    await page.waitForTimeout(2000);

    // Take screenshot
    const screenshotDir = join(__dirname, 'agent-os', 'specs', 'filament-verification', 'screenshots');
    await mkdir(screenshotDir, { recursive: true });

    const screenshotPath = join(screenshotDir, 'filament-login-desktop.png');
    await page.screenshot({
      path: screenshotPath,
      fullPage: true
    });
    console.log(`\nScreenshot saved to: ${screenshotPath}`);

    // Check for specific Filament elements
    console.log('\nChecking for Filament elements...');
    const checks = {
      loginForm: await page.locator('form').count() > 0,
      emailInput: await page.locator('input[type="email"], input[name="email"]').count() > 0,
      passwordInput: await page.locator('input[type="password"], input[name="password"]').count() > 0,
      submitButton: await page.locator('button[type="submit"]').count() > 0,
    };

    console.log('Element checks:', JSON.stringify(checks, null, 2));

    // Test mobile viewport
    console.log('\nTesting mobile viewport...');
    await page.setViewportSize({ width: 375, height: 667 });
    await page.waitForTimeout(1000);

    const mobileScreenshotPath = join(screenshotDir, 'filament-login-mobile.png');
    await page.screenshot({
      path: mobileScreenshotPath,
      fullPage: true
    });
    console.log(`Mobile screenshot saved to: ${mobileScreenshotPath}`);

    // Summary
    console.log('\n=== VERIFICATION SUMMARY ===');
    console.log(`Page loaded: ${response.status() === 200 ? 'YES' : 'NO'}`);
    console.log(`Response status: ${response.status()}`);
    console.log(`Console errors: ${consoleMessages.filter(m => m.type === 'error').length}`);
    console.log(`Page errors: ${errors.length}`);
    console.log(`Login form present: ${checks.loginForm ? 'YES' : 'NO'}`);
    console.log(`Email input present: ${checks.emailInput ? 'YES' : 'NO'}`);
    console.log(`Password input present: ${checks.passwordInput ? 'YES' : 'NO'}`);
    console.log(`Submit button present: ${checks.submitButton ? 'YES' : 'NO'}`);

    if (errors.length > 0) {
      console.log('\n=== PAGE ERRORS ===');
      errors.forEach((error, index) => {
        console.log(`${index + 1}. ${error}`);
      });
    }

    const consoleErrors = consoleMessages.filter(m => m.type === 'error');
    if (consoleErrors.length > 0) {
      console.log('\n=== CONSOLE ERRORS ===');
      consoleErrors.forEach((error, index) => {
        console.log(`${index + 1}. ${error.text}`);
      });
    }

    // Keep browser open for a moment
    await page.waitForTimeout(2000);

    await browser.close();

    // Return results
    return {
      success: response.status() === 200 && checks.loginForm,
      status: response.status(),
      checks,
      errors,
      consoleErrors: consoleErrors.map(e => e.text),
      screenshots: [screenshotPath, mobileScreenshotPath]
    };

  } catch (error) {
    console.error('Test failed:', error.message);
    if (browser) await browser.close();
    throw error;
  }
}

testFilamentLogin()
  .then(results => {
    console.log('\n=== TEST COMPLETED ===');
    process.exit(results.success ? 0 : 1);
  })
  .catch(error => {
    console.error('\n=== TEST FAILED ===');
    console.error(error);
    process.exit(1);
  });
