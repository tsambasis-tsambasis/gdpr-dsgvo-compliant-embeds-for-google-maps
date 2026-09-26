# Validation of the 1.1.2 layout hotfix

Tested on 26 September 2026 with **WordPress 7.1.2**, **PHP 8.5.7**, SQLite Database Integration and Chromium. WordPress 6.2 and PHP 7.4 remain the declared minimums; the complete integration/browser suite was run on the versions stated above.

## Current 1.1.2 checks

| Check | Result |
| --- | --- |
| Isolated PHP regression assertions with the actual WordPress HTML parser | 154 passed |
| Integration inside WordPress with real posts, users, metadata and hooks | 107 passed |
| Historical-layout comparison against the 1.1.0 and 1.1.1 Git versions | 178 assertions passed across 80 measurements |
| Isolated Chromium consent, security, cookie and responsive scenarios | 12 passed |
| WordPress admin/frontend browser scenarios, including English and German | 12 passed |
| Upgrade using the exact release ZIP, publisher links and real editor-save round trip | 3 passed |
| PHP and JavaScript syntax | 4 PHP and 2 JS files passed |
| German translation catalog | 64 translated messages; PO/MO/POT identify 1.1.2 |
| Official Plugin Check including low-severity checks against the runtime-only installation | No errors or warnings |

The earlier 1.1.1 suites passed but missed a real layout regression: a 645px theme content boundary was overridden, typography and spacing changed, and an automatic reset control reduced the loaded-map height. Those earlier results were not accepted as proof of compatibility for this hotfix.

The new comparison uses the actual Git versions and existing metadata. Version 1.1.2 again respects the **645px desktop** and **260px mobile** theme boundaries, matching 1.1.0. It preserves configured card height and ignores previously unused shortcode classes. The 1.1.0 and 1.1.2 reference captures before loading and after loading were byte-identical. The comparison also reproduces the 1.1.1 width failure separately. Test records were removed and original metadata was verified unchanged.

The default shortcode adds no reset control or reserved control height. Tests enable the control explicitly with `show_reset="true"` when testing withdrawal. Only this opt-in control uses 64px of the configured height.

## Scope and evidence

- Dimensions and fonts: existing supported units and safe `calc`/`min`/`max`/`clamp` expressions, unchanged values and formatting, invalid-change retention, and ordinary shortcode layout.
- Security: nonce/capability checks, malformed inputs, iframe host/path/protocol restrictions, inert iframe reconstruction and CSS-injection prevention.
- Consent: no Google iframe/request before consent, grouped loading limited to participating maps, optional 180-day remembered choice, exact cookie matching and respect for maps without remembering enabled.
- Explicit reset: removing loaded frames, clearing the cookie, restoring unchecked controls and keyboard focus, and keeping withdrawal effective after reload.
- Localization and presentation: English/German editor labels, new size-validation notice and reset help, mobile consent controls and light/dark/custom overlays.

Local evidence is recorded in `maps-dev/php-regressions-results.json`, `wp-integration-results.json`, `legacy-dimensions-results.json`, `js-regression-results.json` and `browser-results.json`. The final browser run created 14 fresh screenshots (1–7 in English and German) and restored its fixture snapshot. The QA installation retained only its original three maps after cleanup; customer sites and the public demonstration site were not modified.

During test preparation, a fixture-cleanup scope error left temporary layout-test posts behind. The isolated helper was corrected, only its own posts were removed, and the final browser run was repeated against the clean original fixture set. This was a test-harness issue, not a plugin data-deletion defect.

## External-service limits and previous evidence

The current automated consent/layout checks intercept external traffic. They test request initiation, iframe construction and local behavior; they do not establish ongoing Google availability.

A separate **previous 1.1.1** live smoke test used public Berlin/Hamburg sample maps. It confirmed no Google requests before clicking, then real HTTP 200 responses, rendered map tiles and successful reset. Screenshot 8 retains that real-provider capture: the explicitly enabled reset-control layout still matches it. This live-provider test has not been represented as a new 1.1.2 run. The separate current ZIP test installed the exact 1.1.2 package, verified its version and publisher links, saved 19px in the real editor, verified the frontend and restored the original typography. No customer information or API credentials were used.

The retained `strict-origin-when-cross-origin` iframe policy sends the website origin without the page path or query, following [Google's Embed API guidance](https://developers.google.com/maps/documentation/embed/embedding-map). Consent-based loading does not guarantee legal compliance of a complete website.

Repository-only documentation, Git files and WordPress.org marketing PNGs are excluded from the installable ZIP. Images are packaged separately. The version-aware packaging tool preserves existing 1.1.1 packages and recorded checksums, and refuses to replace a differing existing release artifact.

## Final packages

The installable 1.1.2 ZIP contains 12 runtime files (34,038 bytes); every archived file was byte-identical to source and the CRC check passed. The separate WordPress.org assets ZIP contains 19 PNGs (2,611,639 bytes). `package-manifest-1.1.2.json` and `SHA256.txt` record the exact hashes. Existing 1.1.1 archives and recorded hashes were verified unchanged.
