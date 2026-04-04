const { test, expect } = require('@playwright/test');

test('page title contains "Example"', async ({ page }) => {
  // Navigate to example.com
  await page.goto('https://example.com');

  // Assert the page title contains "Example"
  await expect(page).toHaveTitle(/Example/);

  // Take a screenshot and save it to the test-results folder
  await page.screenshot({ path: 'test-results/example-com.png', fullPage: true });
});
