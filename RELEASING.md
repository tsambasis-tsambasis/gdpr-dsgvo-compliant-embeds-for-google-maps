# Release 1.1.3

This branch adds the separate reset shortcode and optional modern checkbox in 1.1.3 while preserving the default 1.1.2 layout. A GitHub pull request does not publish a WordPress.org release. The current checks and their status are documented in TESTING.md. Review the final package on staging before publishing.

## WordPress.org

1. Review and merge the changes after checking the packaged ZIP on a staging site.
2. Copy the runtime files to SVN `trunk`: the main PHP file, `includes/`, `assets/css/`, `assets/js/`, `languages/`, `uninstall.php`, `LICENSE`, and `readme.txt`.
3. Copy the same runtime to `tags/1.1.3`. The main header, `DSGVO_GM_VERSION` and readme stable tag all identify 1.1.3.
4. Put the banner, icon and screenshot PNGs into the separate top-level SVN `assets/` directory. They do not belong in `trunk/assets/` or a release tag. Set PNG MIME types to `image/png` if needed.
5. Review the SVN diff, commit the release and complete any WordPress.org release confirmation required for this account.

See the official [plugin-assets guide](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/) and [SVN guide](https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/).

The numbered screenshot captions in `readme.txt` correspond to `assets/screenshot-1.png` through `screenshot-9.png`, with German variants using the `-de` suffix. They are actual screenshots of WordPress 7.1.2; they are not generated UI mockups. Screenshots 6–7 explicitly enable the optional modern checkbox. Screenshot 8 uses a real Google map with attribution and the explicitly enabled `show_reset="true"` control. Screenshot 9 uses the separate `[dsgvo_map_reset]` shortcode. All are newly captured in 1.1.3. The existing banners were rebranded with the built-in Imagegen tool and exported to the standard dimensions. The existing icon is unchanged.

## Existing installations

- Minimum WordPress version remains **6.2**, needed for the core HTML tag parser. PHP minimum remains **7.4**.
- The plugin slug, `dsgvo_map` post type, saved map IDs and `[dsgvo_map id="123"]` shortcodes remain unchanged.
- Existing iframe data is validated when rendered. Supported HTTPS Google Maps embeds continue to work. Unsupported or malformed iframe sources remain blocked and should be replaced in the map editor.
- Existing consent cookies retain their 180-day expiry. Automatic loading now applies only to maps with remembering enabled.
- The default shortcode retains the 1.1.0 layout and full configured map dimensions. No automatic minimum-size expansion or reset-control height deduction is applied. Theme content-width constraints are respected.
- Place `[dsgvo_map_reset text="Reset my map choice"]` separately in page content to reset consent without occupying map space. The classic remember-checkbox style remains the default; Modern design is enabled per map in the editor.
- Enable the optional unload/reset control explicitly with `[dsgvo_map id="123" show_reset="true"]`. Previously ignored `class` attributes are not newly applied.
- Safe existing dimension/font values and unchanged editor inputs are preserved. Values already altered and saved by an earlier update cannot be reconstructed automatically; check stored settings or a backup if necessary.
- `Contributors: solutionfirst` remains the existing WordPress.org account. Changing the public profile display name or username is a separate account operation. The plugin author is **Tsambasis & Tsambasis**, linking to **https://tsambasis.net/**.
- The established `https://solutionfirst.m00dy.org/wp-plugin/` and `https://plugin-demo.m00dy.org/` destinations are intentionally retained.

## Validation

See [TESTING.md](TESTING.md) for the tested environment, checks and their limits.


## Packaging safeguards

The packaging tool reads the release version from the plugin header and checks the readme stable tag. It creates versioned ZIPs, preserves existing SHA-256 entries, and refuses to replace an existing package with different contents. Keep the 1.1.1 and 1.1.2 artifacts unchanged. Package only after the current screenshot and test results are ready.
