# Validation of version 1.1.3

Tested on 26 September 2026 with **WordPress 7.1.2**, **PHP 8.5.7**, SQLite Database Integration and Chromium. WordPress 6.2 and PHP 7.4 remain declared minimums; complete integration/browser execution used the versions stated above.

## Current results

| Check | Result |
| --- | --- |
| Isolated PHP regressions using the actual WordPress HTML parser | 181 passed |
| WordPress integration with real posts, users, metadata and hooks | 139 passed |
| Historical-layout comparison against the 1.1.0 and 1.1.1 Git versions | 178 assertions passed across 80 measurements |
| Existing isolated Chromium consent/security scenarios, rerun | 12 passed |
| New isolated separate-reset and modern-checkbox scenarios | 12 passed |
| WordPress admin/frontend browser scenarios in English and German | 15 passed |
| Current live Google rendering and inline-reset captures | 2 passed, English and German plugin UI |
| Syntax | 4 PHP and 2 runtime JS files passed |
| German translation catalog | 72 complete messages; PO/MO/POT identify 1.1.3 |
| Official Plugin Check against the runtime installation, including low severity | No errors or warnings |
| Exact release-ZIP upgrade and editor-save smoke check | 3 passed; original typography restored |

## What the new checks cover

The independent `[dsgvo_map_reset]` button works with or without maps, saved consent or an existing selection. It remains visible and enabled, clears the plugin cookie, unloads all plugin maps on the current page, unchecks both loaded and unloaded map selections, and leaves unrelated cookies alone. Its status feedback is exposed through `role="status"`; keyboard focus remains on the separate button. Multiple reset controls and repeated activation remain safe. Custom labels are escaped.

The optional modern checkbox is selected per map, has a 22px checkbox and styled label card, and supports keyboard and pointer use. Missing settings retain the classic design. Saving or rendering malformed settings does not introduce markup/CSS injection. The former inline reset remains an explicit `show_reset="true"` option and returns focus to the corresponding load button when used.

The historical-layout suite was rerun for the current source. Default maps retain the 1.1.0 layout, theme width, configured height, typography and previously ignored shortcode classes. Responsive maps respect the 645px desktop and 260px mobile theme limits in the fixture; fixed-width settings keep their historical behavior. The suite reproduces the old 1.1.1 regression separately and verifies that original metadata and the complete original post-ID set are restored.

Existing security/consent checks still cover nonce and capability enforcement, malformed input, safe iframe reconstruction, the Google HTTPS host/path allowlist, CSS injection prevention, published-map visibility, remembered-choice scope, grouped loading, exact cookie matching and no Google iframe/request before consent.

## Screenshots and real Google requests

All **18 screenshots (1–9 in English and German)** were newly captured from 1.1.3, not generated or reused from an older release. Screenshots 2 and 5 make the relevant editor controls discoverable. Screenshots 6–7 deliberately enable **Modern design (larger checkbox)**. Screenshot 8 deliberately enables **show_reset="true"** and includes a real Google map with attribution. Screenshot 9 uses the separate **[dsgvo_map_reset]** shortcode with visible status feedback. Ordinary map shortcodes do not automatically acquire either reset control or the modern checkbox.

Most automated scenarios intercept external traffic. The two separate live-provider runs used public Berlin/Hamburg examples: zero Google requests and zero iframes before the click, then real HTTP 200 responses, visibly loaded tiles and successful inline reset. Captures wait for the provider's fonts and UI to render. No customer information or API credentials were used. A sandbox network denial in the initial capture attempt was resolved through the permitted external-network execution; final live reports contain no provider request or JavaScript errors.

These checks establish observed behavior in this environment, not continuing Google availability or legal compliance of a complete website. Reset does not undo data already sent to Google or unload maps already open in other tabs.

## Evidence, restoration and packaging

Current evidence is in `maps-dev/php-regressions-results.json`, `wp-integration-results.json`, `legacy-dimensions-results.json`, `js-regression-results.json`, `reset-regression-results.json`, `browser-results.json`, both `google-live-smoke-*-results.json` locale reports and `plugin-check-results.txt`. Copies accompany the delivery under `test-results/`. The previous release's report is preserved as `TESTING-1.1.2.md` and is not counted as fresh evidence.

Screenshot fixtures, locale choices and original metadata were restored. The browser report confirms both normal and live-capture restoration. Test fixtures and temporary users were cleaned up. No customer site or public demonstration settings were modified.

The package tool reads the release version from the main header, checks the stable tag, requires all current English/German screenshots, and refuses to replace a differing existing release archive. Runtime-only ZIP contents exclude repository documentation and WordPress.org PNGs; artwork is packaged separately. Existing 1.1.1 and 1.1.2 archives/checksums remain unchanged.

## Final package verification

The exact 1.1.3 ZIP was installed with WP-CLI `--force --activate`. The three browser smoke checks verified version/publisher links, a real editor save and frontend typography, then restoration of the original typography; no JavaScript errors occurred. The plugin ZIP contains 12 source-identical runtime files (36,725 bytes), and the separate assets ZIP contains 22 source-identical PNGs (2,892,919 bytes). Both CRC checks passed; exact hashes are recorded in `package-manifest-1.1.3.json` and `SHA256.txt`. No package was changed after this test.
