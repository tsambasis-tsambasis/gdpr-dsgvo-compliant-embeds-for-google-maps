# Validation of 1.1.1

Tested on 26 September 2026 using **WordPress 7.1.2**, **PHP 8.5.7**, SQLite Database Integration and Chromium. WordPress 7.1.2 was the latest stable release in the [official release archive](https://wordpress.org/download/releases/) on that date. This is a tested environment, not a claim that every WordPress/PHP/database combination has been exercised.

| Check | Result |
| --- | --- |
| Isolated PHP regression assertions, using the actual WordPress HTML parser | 72 passed |
| Integration assertions inside WordPress with real posts, users, metadata and hooks | 76 passed |
| Isolated Chromium consent, security, cookie and responsive scenarios | 12 passed |
| WordPress admin/frontend browser scenarios, including English and German | 12 passed |
| Final ZIP upgrade, publisher links and a real editor-save round trip | 3 passed |
| PHP and JavaScript syntax | 4 PHP and 2 JS files passed |
| Official Plugin Check, including low-severity checks, against the installed runtime | No errors or warnings |
| Real Google Maps request and visual smoke test | Google returned HTTP 200; map tiles and map UI visible; unload/reset succeeded |

## What was exercised

- Valid and malformed Google iframe URLs, forbidden hosts/paths/protocols/credentials, malformed arrays, CSS injection and safe reconstruction of iframe attributes.
- Nonces, unauthorized users, foreign authors' maps, published/draft/password-protected/wrong-type posts and retention of the previous embed when an invalid replacement is submitted.
- Existing map IDs/shortcodes, stored settings and direct-ZIP German localization without a WordPress.org plugin language pack.
- No Google request or live iframe before consent, grouped loading limited to participating maps, explicit remembering for 180 days, exact cookie matching and respect for maps where remembering is disabled.
- Unloading all maps, clearing the consent cookie, restoring unchecked overlays, restoring keyboard focus and keeping withdrawal effective across reloads.
- Inert parsing, rejection of supplied script/event/CSS attributes, readable mobile controls and minimum loaded-map dimensions.
- The final `strict-origin-when-cross-origin` policy sends the website origin without the page path or query. This follows [Google's Embed API guidance](https://developers.google.com/maps/documentation/embed/embedding-map) and preserves domain-restricted API-key compatibility.
- Actual screenshots of the WordPress editor, each new control group, mobile consent, desktop designs and a loaded map with reset control.

Most regression tests deliberately intercepted external traffic. A separate fresh-browser live smoke test used public Berlin/Hamburg sample maps, confirmed zero Google requests before the click, then observed real redirects ending in HTTP 200 and rendered map tiles. No customer information or API credentials were used. The plugin does not guarantee Google's ongoing availability or legal compliance of a complete website.

Test fixtures and temporary test users were removed or restored after integration assertions. Customer sites and the public demonstration site were not modified. Multisite cleanup was reviewed and exercised in the isolated harness, not deployed to a live multisite network. PHP 7.4 and WordPress 6.2 remain declared minimums; full browser/integration execution here used the versions stated above.

Repository-only documentation, Git files and directory marketing PNGs are excluded from the installable ZIP. WordPress.org images are packaged separately, as required by its top-level SVN assets layout.
