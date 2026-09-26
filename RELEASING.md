# Release 1.1.1

This branch prepares the next update; a GitHub pull request does not publish a WordPress.org release.

## WordPress.org

1. Review and merge the changes after checking the packaged ZIP on a staging site.
2. Copy the runtime files to SVN `trunk`: the main PHP file, `includes/`, `assets/css/`, `assets/js/`, `languages/`, `uninstall.php`, `LICENSE`, and `readme.txt`.
3. Copy the same runtime to `tags/1.1.1`. The main header, `DSGVO_GM_VERSION` and readme stable tag all identify 1.1.1.
4. Put the banner, icon and screenshot PNGs into the separate top-level SVN `assets/` directory. They do not belong in `trunk/assets/` or a release tag. Set PNG MIME types to `image/png` if needed.
5. Review the SVN diff, commit the release and complete any WordPress.org release confirmation required for this account.

See the official [plugin-assets guide](https://developer.wordpress.org/plugins/wordpress-org/plugin-assets/) and [SVN guide](https://developer.wordpress.org/plugins/wordpress-org/how-to-use-subversion/).

The numbered screenshot captions in `readme.txt` correspond to `assets/screenshot-1.png` through `screenshot-8.png`. Screenshots 1–7 also have German variants using the `-de` suffix. They are actual screenshots of WordPress 7.1.2; they are not generated UI mockups. Screenshot 8 includes a real Google map, with its provider attribution visible. The existing banners were rebranded with the built-in Imagegen tool and exported to the standard dimensions. The existing icon is unchanged.

## Existing installations

- Minimum WordPress version is now **6.2**, needed for the core HTML tag parser. PHP minimum remains **7.4**.
- The plugin slug, `dsgvo_map` post type, saved map IDs and `[dsgvo_map id="123"]` shortcodes remain unchanged.
- Existing iframe data is validated when rendered. Supported HTTPS Google Maps embeds continue to work. Unsupported or malformed iframe sources remain blocked and should be replaced in the map editor.
- Existing consent cookies retain their 180-day expiry. Automatic loading now applies only to maps with remembering enabled.
- Loaded maps offer an unload/reset button. Small maps grow to make space for the map and this control, then return to their configured dimensions when reset.
- `Contributors: solutionfirst` remains the existing WordPress.org account. Changing the public profile display name or username is a separate account operation. The plugin author is **Tsambasis & Tsambasis**, linking to **https://tsambasis.net/**.
- The established `https://solutionfirst.m00dy.org/wp-plugin/` and `https://plugin-demo.m00dy.org/` destinations are intentionally retained.

## Validation

See [TESTING.md](TESTING.md) for the tested environment, checks and their limits.
