# GDPR-DSGVO compliant Embeds for Google Maps

Consent-based Google Maps embeds for WordPress, published by [Tsambasis & Tsambasis](https://tsambasis.net/).

Version **1.1.1** · WordPress **6.2+** · PHP **7.4+**

Tested with WordPress 7.1.2 and PHP 8.5.7. These checks do not cover every supported WordPress/PHP combination.

Create unlimited maps, place them with a shortcode and show a local overlay before the Google iframe is loaded. The plugin is free, without a license key, paid tier, advertising or developer tracking.

## Setup

1. Install the plugin and open **Maps → Add New Map**.
2. Give the map a title and paste the iframe code from **Google Maps → Share → Embed a map**.
3. Choose the design, dimensions, texts and consent options, then publish the map.
4. Copy its shortcode into a Shortcode block:

   ```text
   [dsgvo_map id="123"]
   ```

Replace `123` with your map's ID. JavaScript is required to load the iframe after consent. The supported Google Maps share/embed iframe does not require entering an API key in this plugin.

## Customize each map

- Light, dark or custom overlay, with configurable colors.
- Custom load-button text and rounded or square buttons.
- Separate font sizes for the button, message, privacy text, privacy link and remember-selection label.
- Optional overlay message, privacy notice and privacy-policy link.
- Width and height controls; percentage height provides an aspect ratio relative to the container width. Loaded maps use at least 200 × 200 pixels plus room for the reset control; resetting restores the configured size.
- Optional grouped loading for participating maps on the same page.
- An optional, initially unchecked remember-selection checkbox below the privacy notice.

The font-size controls, message, grouped loading and remember-selection feature were introduced in 1.1.0. This release updates validation, consent handling, translations and documentation. The interface follows WordPress's language; English source text and German translations are included. Custom text remains what you enter.

## Consent and privacy

Without a remembered choice, the configured Google Maps iframe is created after the load button is selected. When a visitor opts to remember the choice, the first-party `dsgvo_gm_consent=1` cookie lasts up to **180 days**. It has path `/`, SameSite=Lax and Secure on HTTPS. The choice applies site-wide to maps with the remember option enabled; those maps can load on later visits without another click. Other maps still require explicit loading. This is a loading preference, not a server-side consent audit log.

Grouped loading is configured per map: one participating map's load button also loads the other participating maps on the same page. Describe the group and remembered choice clearly in your consent text.

Use **Unload maps and reset choice** below a loaded map to delete the plugin's cookie and return all loaded plugin maps on the current page to their overlays. Remember checkboxes are cleared. This does not undo data already transmitted to Google.

Once loaded, the iframe contacts Google directly. Supported HTTPS embed hosts are `google.com`, `www.google.com`, `maps.google.com`, `google.de`, `www.google.de` and `maps.google.de`. Google receives connection data and, through the iframe's `strict-origin-when-cross-origin` policy, the website origin without the page path or query. Google's [Privacy Policy](https://policies.google.com/privacy) and [Maps Terms](https://www.google.com/help/terms_maps/) apply. This plugin does not block requests from other plugins, themes or independently embedded content.

Consent-based loading alone does not guarantee GDPR/DSGVO compliance. Configure appropriate notices and withdrawal options, review your site's full data flows and test the page on desktop and mobile. This is independent software, not legal advice or an official Google product. Detailed service and cookie information is in [readme.txt](readme.txt).

## Links

- Publisher and support: [Tsambasis & Tsambasis](https://tsambasis.net/)
- [Live demonstration — Tsambasis & Tsambasis](https://plugin-demo.m00dy.org/live-demonstration/)
- [Plugin information — Tsambasis & Tsambasis](https://solutionfirst.m00dy.org/wp-plugin/)

The established demonstration and plugin-information URLs are retained. The WordPress.org contributor username is `solutionfirst`; the public manufacturer is Tsambasis & Tsambasis.

## License

GPLv2 or later. See the license information in the plugin header and [readme.txt](readme.txt).
