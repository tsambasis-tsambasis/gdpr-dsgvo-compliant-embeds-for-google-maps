=== GDPR-DSGVO compliant Embeds for Google Maps ===
Contributors: solutionfirst
Donate link: https://www.paypal.com/donate/?hosted_button_id=CUPZTPGSAHNKY
Tags: google maps, gdpr, dsgvo, privacy, shortcode
Requires at least: 6.2
Tested up to: 7.1
Stable tag: 1.1.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Show Google Maps after consent, with custom styles, text sizes, optional remembered consent and grouped loading for multiple maps.

== Description ==

Create Google Maps embeds that display a local consent overlay before loading the map. Add as many maps as you need, customize each one in WordPress, and insert it with a shortcode.

Published by [Tsambasis & Tsambasis](https://tsambasis.net/).
[Live Plugin Demo — Tsambasis & Tsambasis](https://plugin-demo.m00dy.org/live-demonstration/).

* Unlimited maps without a license key or paid tier.
* Separate iframe input and shortcode for each map.
* Light, dark and custom designs, with configurable overlay and button colors.
* Custom button text and rounded or square buttons.
* Separate font sizes for the button, overlay message, privacy text, privacy link and remember-selection label.
* An optional message between the load button and privacy notice.
* Configurable privacy notice, link text and privacy-policy URL.
* Existing map dimensions and theme content-width limits are respected. With the default shortcode, loading does not enlarge the map or reserve control space.
* Optional grouped loading: one click loads the maps on that page that also have the group option enabled.
* An optional remember checkbox: existing design by default, or a larger modern checkbox and label card selected per map.
* A separate reset button anywhere in page content with [dsgvo_map_reset].
* English source text and a German translation, following the WordPress language.
* No plugin advertising or developer tracking.

= Fonts, messages and multiple maps =

Font sizes accept px, em, rem and %. Leave enough map height for the message, privacy link and checkbox, especially on mobile. Under Checkbox design, Modern design (larger checkbox) enables the optional 22px checkbox and styled label card. Existing design remains the default; old maps do not change automatically.

The load-all setting is configured per map. Clicking a participating map loads the other participating maps on the same page; maps outside the group remain separate. Explain this scope in the button or overlay text before asking visitors to load the group.

= Consent and the optional cookie =

Without a remembered choice, the Google Maps iframe is created only after the visitor selects the load button. Selecting the optional remember checkbox before loading sets the first-party cookie `dsgvo_gm_consent=1` for up to 180 days, with path `/`, SameSite=Lax and Secure on HTTPS. This is a site-wide choice for maps with the remember option enabled: those maps can load on later visits without another click. Maps without that option still require explicit loading. Browser settings can block or remove the cookie.

The checkbox is optional and initially unchecked. Decide whether to offer remembered consent and describe its effect in your privacy notice. The cookie is a loading preference, not a server-side consent audit log.

Place `[dsgvo_map_reset]` anywhere in page content for an always-visible reset button, also on pages without a map. Customize its label with `[dsgvo_map_reset text="Reset my map choice"]`. It clears the site-wide consent cookie, unloads plugin maps on the current page and unchecks their remember boxes. A status message confirms the action; keyboard focus stays on the separate button. Already open maps in other tabs are not unloaded.

For a button inside a loaded map, opt in with `[dsgvo_map id="123" show_reset="true"]`; this uses 64px of its configured height. The ordinary map shortcode adds no reset control and keeps its layout. Neither reset method undoes data already sent to Google. Browser settings can also remove the cookie.

= External service: Google Maps =

Once a map is loaded, the visitor's browser connects directly to Google. Supported HTTPS embed URLs use `google.com`, `www.google.com`, `maps.google.com`, `google.de`, `www.google.de` or `maps.google.de`; the embed can load additional Google resources. Google receives connection information such as the IP address and browser/device information, and may process cookies or Google-account information. The iframe's `strict-origin-when-cross-origin` referrer policy sends the website origin to Google, without the page path or query.

* [Google Privacy Policy](https://policies.google.com/privacy)
* [Google Maps Additional Terms](https://www.google.com/help/terms_maps/)

The plugin's local overlay does not itself load the configured Google iframe before consent or remembered consent. It does not block Google requests made by your theme, other plugins, manually embedded maps or other services. After loading, Google controls the map content and related data processing.

The plugin supports consent-based loading; its name is not a guarantee that your website complies with GDPR/DSGVO or other law. You remain responsible for notices, valid consent, withdrawal options and your site's complete data flows. This is independent software, not an official Google product or legal advice.

= Support and more information =

Contact the publisher through [Tsambasis & Tsambasis](https://tsambasis.net/).
The existing [plugin information page — Tsambasis & Tsambasis](https://solutionfirst.m00dy.org/wp-plugin/) and [live demonstration](https://plugin-demo.m00dy.org/live-demonstration/) remain available at their established URLs.

== Installation ==

1. Install and activate the plugin ZIP through Plugins > Add New > Upload Plugin. For manual installation, use the `gdpr-dsgvo-compliant-embeds-for-google-maps` folder inside `wp-content/plugins/`.
2. Open Maps > Add New Map, give the map a title and paste its Google Maps iframe code.
3. Configure appearance, text sizes, privacy notice and loading options, then publish the map.
4. Copy its shortcode into a Shortcode block, for example `[dsgvo_map id="123"]`.
5. Test the public page on desktop and mobile, both before and after consent, and update your privacy notice.

JavaScript is required to load the map after consent. Maps are managed in WordPress; this plugin does not require a Google Maps API key for the supported share/embed iframe.

== Frequently Asked Questions ==

= Where do I find the iframe code? =

Open the location in Google Maps, choose Share and then Embed a map, and copy the iframe HTML. Paste the entire iframe into the map's settings. A normal location/share link is not the same as embed code.

= How do I add a map to a page? =

Insert `[dsgvo_map id="123"]`, replacing 123 with the map ID shown in its editor. You can use multiple map shortcodes on one page.

= Does one click always load every map? =

No. Grouped loading applies to maps with the load-all option enabled on the same page. A remembered choice can affect later loading as explained in the consent section.

= Can I change the language? =

The interface follows WordPress. German translations are included; the source language is English. Custom button text, messages and notices remain the text you entered.

= Is there a map limit or paid license? =

No. The plugin supports unlimited maps without a license key. Hosting and third-party services remain subject to their own terms.

== Screenshots ==
1. Map list in WordPress.
2. Iframe input, button text, font size and button shape.
3. Custom design colors and map dimensions.
4. Privacy text, link typography and optional overlay message.
5. Grouped loading, optional modern checkbox design, remember text, size and color.
6. Mobile consent overlay with Modern design (larger checkbox) explicitly enabled.
7. Light, dark and custom overlays with the optional modern checkbox enabled.
8. Loaded Google map: inline reset is shown only because show_reset="true" is enabled.
9. Separate, always-visible reset button inserted using [dsgvo_map_reset].

== Changelog ==
= 1.1.3 =
* Added [dsgvo_map_reset] for a separate reset button with custom text, status feedback and keyboard focus retention.
* Added an optional modern 22px remember checkbox; existing maps keep their original design.
* Clarified inline reset activation and refreshed all screenshots from the current version.

= 1.1.2 =
* Fixed the 1.1.1 layout regression: restored the 1.1.0 styling baseline and theme content-width behavior.
* Preserved compatible existing dimensions, typography and unchanged editor values.
* Made the reset control an explicit show_reset="true" shortcode option; ordinary maps keep their full configured size without automatic enlargement or reserved control height.
* Retained security validation and consent handling without applying previously ignored shortcode classes.

= 1.1.1 =
* Updated publisher details and documentation for Tsambasis & Tsambasis.
* Improved validation, permission checks and consent handling.
* Remembered consent now loads only maps that offer remembering; added an unload/reset control.
* Refreshed German translations and documentation of the 1.1.0 controls.
* Requires WordPress 6.2 or later.

= 1.1.0 =
* Added five text-size controls, an overlay message, optional grouped loading and a remember-selection checkbox with configurable text/color and a consent cookie.

= 1.0.5 =
* Updated banners.

= 1.0.4 =
* Expanded plugin information.

= 1.0.3 =
* Removed duplicate constants and unnecessary uninstall calls.

= 1.0.2 =
* Removed map limits/license keys; refactored code and screenshots.

= 1.0.1 =
* Fixed privacy text/link spacing and updated iframe sandbox flags.

= 1.0.0 =
* Initial release with consent-based iframe embeds.
