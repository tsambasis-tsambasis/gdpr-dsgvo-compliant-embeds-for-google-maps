# GDPR-DSGVO compliant Embeds for Google Maps

Consent-based Google Maps embeds for WordPress, published by [Tsambasis & Tsambasis](https://tsambasis.net/).

Version **1.1.3** · WordPress **6.2+** · PHP **7.4+**

Version 1.1.3 was checked with WordPress 7.1.2 and PHP 8.5.7, including the new reset/checkbox controls and the existing-layout comparison. See [TESTING.md](TESTING.md) for the current results and limits.

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
- Width and height controls that preserve existing layout and theme content-width limits. Maps are not automatically enlarged and no control height is deducted from ordinary embeds.
- Optional grouped loading for participating maps on the same page.
- An optional, initially unchecked remember-selection checkbox below the privacy notice. Existing design is the default; choose **Modern design (larger checkbox)** for a 22px checkbox and styled label card.
- A separate reset button anywhere in page content using `[dsgvo_map_reset]`.

The font-size controls, message, grouped loading and remember-selection feature were introduced in 1.1.0. Version 1.1.2 fixes the layout regression introduced in 1.1.1, restoring the 1.1.0 styling baseline while retaining security and consent checks. The interface follows WordPress's language; English source text and German translations are included. Custom text remains what you enter.

## A reset button outside the map

Add `[dsgvo_map_reset]` in a Shortcode block or page content, for example on your privacy page. Its button is always visible, even without a map or saved choice. Customize the label with `[dsgvo_map_reset text="Reset my map choice"]`. It clears the website's consent cookie, unloads maps on the current page and unchecks all their remember boxes. An accessible status message confirms the reset, with focus remaining on the separate button. Already open maps in other tabs are not unloaded.

Screenshots 6–7 explicitly enable the optional modern checkbox. Screenshot 8 explicitly enables the inline reset using `show_reset="true"`; it is not added by the ordinary map shortcode. Screenshot 9 shows the separate reset shortcode.

## Consent and privacy

Without a remembered choice, the configured Google Maps iframe is created after the load button is selected. When a visitor opts to remember the choice, the first-party `dsgvo_gm_consent=1` cookie lasts up to **180 days**. It has path `/`, SameSite=Lax and Secure on HTTPS. The choice applies site-wide to maps with the remember option enabled; those maps can load on later visits without another click. Other maps still require explicit loading. This is a loading preference, not a server-side consent audit log.

Grouped loading is configured per map: one participating map's load button also loads the other participating maps on the same page. Describe the group and remembered choice clearly in your consent text.

Enable the optional **Unload maps and reset choice** control explicitly with `[dsgvo_map id="123" show_reset="true"]`. The ordinary shortcode adds no reset control and preserves its previous map dimensions. When enabled, the control uses 64px of the configured map height. The optional control deletes the plugin cookie and returns loaded plugin maps on the current page to their overlays. It does not undo data already sent to Google. Visitors can also clear this website's consent cookie through browser settings.

Once loaded, the iframe contacts Google directly. Supported HTTPS embed hosts are `google.com`, `www.google.com`, `maps.google.com`, `google.de`, `www.google.de` and `maps.google.de`. Google receives connection data and, through the iframe's `strict-origin-when-cross-origin` policy, the website origin without the page path or query. Google's [Privacy Policy](https://policies.google.com/privacy) and [Maps Terms](https://www.google.com/help/terms_maps/) apply. This plugin does not block requests from other plugins, themes or independently embedded content.

Consent-based loading alone does not guarantee GDPR/DSGVO compliance. Configure appropriate notices and withdrawal options, review your site's full data flows and test the page on desktop and mobile. This is independent software, not legal advice or an official Google product. Detailed service and cookie information is in [readme.txt](readme.txt).

## Links

- Publisher and support: [Tsambasis & Tsambasis](https://tsambasis.net/)
- [Live demonstration — Tsambasis & Tsambasis](https://plugin-demo.m00dy.org/live-demonstration/)
- [Plugin information — Tsambasis & Tsambasis](https://solutionfirst.m00dy.org/wp-plugin/)

The established demonstration and plugin-information URLs are retained. The WordPress.org contributor username is `solutionfirst`; the public manufacturer is Tsambasis & Tsambasis.

## License

GPLv2 or later. See the license information in the plugin header and [readme.txt](readme.txt).
