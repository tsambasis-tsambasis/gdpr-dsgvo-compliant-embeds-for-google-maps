<?php

/**
 * @package         GDPR_Google_Maps_Embed_SF
 * @license         GPLv2 or later
 * @license URI     https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) exit; // Exit if accessed directly



add_action('admin_enqueue_scripts', function () {
    $screen = get_current_screen();
    if (!$screen || 'dsgvo_map' !== $screen->post_type) {
        return;
    }
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script(
        'dsgvo-gm-color-picker',
        DSGVO_GM_PLUGIN_URL . 'assets/js/dsgvo-gm-color-picker.js',
        ['wp-color-picker', 'jquery'],
        DSGVO_GM_VERSION,
        true
    );
});

// Register Custom Post Type
add_action('init', 'dsgvo_gm_register_post_type');
function dsgvo_gm_register_post_type()
{
    register_post_type('dsgvo_map', array(
        'labels' => array(
            'name'               => __('Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
            'singular_name'      => __('Map', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
            'add_new_item'       => __('Add New Map', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
            'edit_item'          => __('Edit Map', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
            'all_items'          => __('All Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        ),
        'public'        => false,
        'publicly_queryable' => false,
        'show_in_rest'  => false,
        'query_var'     => false,
        'rewrite'       => false,
        'exclude_from_search' => true,
        'show_ui'       => true,
        'show_in_menu'  => true,
        'supports'      => array('title'),
        'menu_icon'     => 'dashicons-location-alt',
    ));
}

// Add meta box for DSGVO Maps
add_action('add_meta_boxes', function () {
    add_meta_box(
        'dsgvo_gm_map_settings',
        __('Map Settings', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        'dsgvo_gm_map_settings_callback',
        'dsgvo_map',
        'normal',
        'high'
    );
});

// Render settings fields
function dsgvo_gm_map_settings_callback($post)
{
    wp_nonce_field('dsgvo_gm_save', 'dsgvo_gm_nonce');

    // Retrieve existing values or defaults
    $iframe     = dsgvo_gm_meta($post->ID, '_dsgvo_gm_iframe');
    $template   = dsgvo_gm_meta($post->ID, '_dsgvo_gm_template') ?: 'light';
    $btn_text   = dsgvo_gm_meta($post->ID, '_dsgvo_gm_button_text') ?: __('Load Google Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps');
    $btn_shape = dsgvo_gm_meta($post->ID, '_dsgvo_gm_button_shape');
    if (! in_array($btn_shape, ['rounded', 'square'], true)) {
        $btn_shape = 'rounded'; // Default
    }

    $overlay_bg = dsgvo_gm_meta($post->ID, '_dsgvo_gm_overlay_bg') ?: '#ffffff';
    $button_bg  = dsgvo_gm_meta($post->ID, '_dsgvo_gm_button_bg') ?: '#0073aa';
    $btn_color  = dsgvo_gm_meta($post->ID, '_dsgvo_gm_button_color') ?: '#ffffff';
    $btn_font_size = dsgvo_gm_meta($post->ID, '_dsgvo_gm_button_font_size', '16px');
    $privacy_color = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_color') ?: '#666666';
    $privacy_enabled = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_enabled') ?: 0;
    $privacy_link = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_link') ?: '';

    $privacy_text = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_text') ?: '';
    $privacy_link_text = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_link_text') ?: '';
    $privacy_font_size = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_font_size', '0.8em');
    $privacy_link_font_size = dsgvo_gm_meta($post->ID, '_dsgvo_gm_privacy_link_font_size', '0.8em');
    $message_text = dsgvo_gm_meta($post->ID, '_dsgvo_gm_message_text') ?: '';
    $message_font_size = dsgvo_gm_meta($post->ID, '_dsgvo_gm_message_font_size', '0.9em');

    $width = dsgvo_gm_meta($post->ID, '_dsgvo_gm_width', '100%');
    $height = dsgvo_gm_meta($post->ID, '_dsgvo_gm_height', '100%');
    $load_all_enabled = dsgvo_gm_meta($post->ID, '_dsgvo_gm_load_all_enabled') ?: 0;
    $remember_enabled = dsgvo_gm_meta($post->ID, '_dsgvo_gm_remember_enabled') ?: 0;
    $remember_text = dsgvo_gm_meta($post->ID, '_dsgvo_gm_remember_text') ?: __('Remember selection', 'gdpr-dsgvo-compliant-embeds-for-google-maps');
    $remember_font_size = dsgvo_gm_meta($post->ID, '_dsgvo_gm_remember_font_size', '0.85em');
    $remember_color = dsgvo_gm_meta($post->ID, '_dsgvo_gm_remember_color') ?: '#666666';


    // Set default if empty
    if ('' === $width) {
        $width = '100%';
    }
    if ('' === $height) {
        $height = '100%';
    }
    ?>
    <p>
        <strong><?php esc_html_e('Shortcode:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></strong><br>
        <input type="text" readonly style="width:100%;" value="<?php echo esc_attr("[dsgvo_map id=\"{$post->ID}\"]"); ?>" onclick="this.select();">
    </p>

    <br>
    <hr>
    <br>

    <p>
        <label for="dsgvo_gm_iframe"><?php esc_html_e('iframe Code:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <textarea id="dsgvo_gm_iframe" name="dsgvo_gm_iframe" style="width:100%;height:100px;" aria-describedby="dsgvo-gm-iframe-help"><?php printf('%s', esc_textarea($iframe)); ?></textarea>
        <span id="dsgvo-gm-iframe-help" class="description"><?php esc_html_e('Paste a Google Maps embed iframe with an HTTPS source URL. Other iframe sources are not supported.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></span>

    </p>

    <h4><?php esc_html_e('Button Settings', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></h4>

    <p>
        <label><?php esc_html_e('Button Text:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            type="text"
            name="dsgvo_gm_button_text"
            value="<?php printf('%s', esc_attr($btn_text)); ?>"
            style="width:100%;" />
    </p>

    <p>
        <label for="dsgvo_gm_button_font_size"><?php esc_html_e('Button Font Size:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_button_font_size"
            name="dsgvo_gm_button_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($btn_font_size)); ?>"
            style="width:100px;"
            placeholder="16px">
    </p>

    <p>
        <label><?php esc_html_e('Button Type:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <label style="margin-right:1em;">
            <input
                type="radio"
                name="dsgvo_gm_button_shape"
                value="rounded"
                <?php checked($btn_shape, 'rounded'); ?> />
            <?php esc_html_e('Rounded', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>
        </label>
        <label>
            <input
                type="radio"
                name="dsgvo_gm_button_shape"
                value="square"
                <?php checked($btn_shape, 'square'); ?> />
            <?php esc_html_e('Squared', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>
        </label>
    </p>

    <h4><?php esc_html_e('Style Settings', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></h4>

    <p>
        <label><?php esc_html_e('Design:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <select id="dsgvo_gm_template" name="dsgvo_gm_template">
            <option value="light" <?php selected($template, 'light'); ?>><?php esc_html_e('Light', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></option>
            <option value="dark" <?php selected($template, 'dark');  ?>><?php esc_html_e('Dark',  'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></option>
            <option value="custom" <?php selected($template, 'custom'); ?>><?php esc_html_e('Custom', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></option>
        </select>
    </p>

    <div id="dsgvo_gm_custom_colors" style="display:<?php printf('%s', esc_attr($template === 'custom' ? 'block' : 'none')); ?>;">
        <p>
            <label><?php esc_html_e('Overlay Background Color:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
            <input
                type="text"
                name="dsgvo_gm_overlay_bg"
                value="<?php printf('%s', esc_attr($overlay_bg)); ?>"
                class="wp-color-picker-field"
                data-default-color="#ffffff" />
        </p>

        <p>
            <label><?php esc_html_e('Button Background Color:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
            <input
                type="text"
                name="dsgvo_gm_button_bg"
                value="<?php printf('%s', esc_attr($button_bg)); ?>"
                class="wp-color-picker-field"
                data-default-color="#0073aa" />
        </p>

        <p>
            <label><?php esc_html_e('Button Text Color:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
            <input
                type="text"
                name="dsgvo_gm_button_color"
                value="<?php printf('%s', esc_attr($btn_color)); ?>"
                class="wp-color-picker-field"
                data-default-color="#ffffff" />
        </p>

        <p>
            <label><?php esc_html_e('Privacy Info Text Color:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
            <input
                type="text"
                name="dsgvo_gm_privacy_color"
                value="<?php printf('%s', esc_attr($privacy_color)); ?>"
                class="wp-color-picker-field"
                data-default-color="#666666" />
        </p>
    </div>

    <h4><?php esc_html_e('Size Settings (% or px)', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></h4>

    <p>
        <label for="dsgvo_gm_width"><?php esc_html_e('Width:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label>
        <input
            id="dsgvo_gm_width"
            name="dsgvo_gm_width"
            type="text"
            value="<?php printf('%s', esc_attr($width)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('100% or 600px', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>">
    </p>
    <p>
        <label for="dsgvo_gm_height"><?php esc_html_e('Height:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label>
        <input
            id="dsgvo_gm_height"
            name="dsgvo_gm_height"
            type="text"
            value="<?php printf('%s', esc_attr($height)); ?>"
            style="width:100px;"
            placeholder="<?php esc_attr_e('100% or 450px', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>">
    </p>

    <h4><?php esc_html_e('Privacy Settings', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></h4>

    <p>
        <label>
            <input
                type="checkbox"
                name="dsgvo_gm_privacy_enabled"
                value="1"
                <?php checked($privacy_enabled, 1); ?>>
            <?php esc_html_e('Enable privacy notice', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>
        </label>
    </p>

    <p>
        <label for="dsgvo_gm_privacy_text"><?php esc_html_e('Privacy Policy Text:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_privacy_text"
            name="dsgvo_gm_privacy_text"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_gm_privacy_font_size"><?php esc_html_e('Privacy Text Font Size:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_privacy_font_size"
            name="dsgvo_gm_privacy_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_font_size)); ?>"
            style="width:100px;"
            placeholder="0.8em">
    </p>

    <p>
        <label for="dsgvo_gm_privacy_link_text"><?php esc_html_e('Privacy Policy URL Text:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_privacy_link_text"
            name="dsgvo_gm_privacy_link_text"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_link_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_gm_privacy_link_font_size"><?php esc_html_e('Privacy Link Font Size:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_privacy_link_font_size"
            name="dsgvo_gm_privacy_link_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($privacy_link_font_size)); ?>"
            style="width:100px;"
            placeholder="0.8em">
    </p>

    <p>
        <label for="dsgvo_gm_privacy_link"><?php esc_html_e('Privacy Policy URL:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_privacy_link"
            name="dsgvo_gm_privacy_link"
            type="url"
            value="<?php printf('%s', esc_attr($privacy_link)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_gm_message_text"><?php esc_html_e('Overlay Message:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <textarea
            id="dsgvo_gm_message_text"
            name="dsgvo_gm_message_text"
            style="width:100%;height:70px;"><?php printf('%s', esc_textarea($message_text)); ?></textarea><br>
        <span class="description"><?php esc_html_e('Optional text shown between the button and the privacy notice.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></span>
    </p>

    <p>
        <label for="dsgvo_gm_message_font_size"><?php esc_html_e('Message Font Size:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_message_font_size"
            name="dsgvo_gm_message_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($message_font_size)); ?>"
            style="width:100px;"
            placeholder="0.9em">
    </p>

    <h4><?php esc_html_e('Load Behavior', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></h4>

    <p>
        <label>
            <input
                type="checkbox"
                name="dsgvo_gm_load_all_enabled"
                value="1"
                <?php checked($load_all_enabled, 1); ?>>
            <?php esc_html_e('Load all maps on one page', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>
        </label><br>
        <span class="description"><?php esc_html_e('If this option is enabled for multiple maps on the same page, one click loads all enabled maps together.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></span>
    </p>

    <p>
        <label>
            <input
                type="checkbox"
                name="dsgvo_gm_remember_enabled"
                value="1"
                <?php checked($remember_enabled, 1); ?>>
            <?php esc_html_e('Show remember selection', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?>
        </label><br>
        <span class="description"><?php esc_html_e('Shows a checkbox in the overlay. If checked when loading, a site-wide cookie remembers consent for 180 days for maps with this option enabled. The optional reset control requires show_reset="true" in the shortcode.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></span>
    </p>

    <p>
        <label for="dsgvo_gm_remember_text"><?php esc_html_e('Remember Selection Text:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_remember_text"
            name="dsgvo_gm_remember_text"
            type="text"
            value="<?php printf('%s', esc_attr($remember_text)); ?>"
            style="width:100%;">
    </p>

    <p>
        <label for="dsgvo_gm_remember_font_size"><?php esc_html_e('Remember Selection Font Size:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            id="dsgvo_gm_remember_font_size"
            name="dsgvo_gm_remember_font_size"
            type="text"
            value="<?php printf('%s', esc_attr($remember_font_size)); ?>"
            style="width:100px;"
            placeholder="0.85em">
    </p>

    <p>
        <label><?php esc_html_e('Remember Selection Text Color:', 'gdpr-dsgvo-compliant-embeds-for-google-maps'); ?></label><br>
        <input
            type="text"
            name="dsgvo_gm_remember_color"
            value="<?php printf('%s', esc_attr($remember_color)); ?>"
            class="wp-color-picker-field"
            data-default-color="#666666" />
    </p>

    <?php
}

/** Keep unchanged legacy values byte-for-byte; reject invalid edits without silent resets. */
function dsgvo_gm_save_size($post_id, $field, $value, $font = false)
{
    $key = '_dsgvo_gm_' . $field;
    $previous = dsgvo_gm_meta($post_id, $key);
    if (null === $value || $value === $previous || $value === str_replace(array("\r", "\n"), '', $previous)) {
        return;
    }
    if ('' === trim($value)) {
        update_post_meta($post_id, $key, '');
        return;
    }
    $clean = $font ? dsgvo_gm_sanitize_font_size($value, null) : dsgvo_gm_sanitize_dimension($value, null);
    if (null === $clean) {
        set_transient('dsgvo_gm_size_error_' . get_current_user_id(), 1, MINUTE_IN_SECONDS);
        return;
    }
    update_post_meta($post_id, $key, wp_slash($clean));
}

// Save only this post type and only authorized, intentional editor submissions.
add_action('save_post_dsgvo_map', 'dsgvo_gm_save_meta');
function dsgvo_gm_save_meta($post_id)
{
    if (!isset($_POST['dsgvo_gm_nonce']) || !is_string($_POST['dsgvo_gm_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['dsgvo_gm_nonce'])), 'dsgvo_gm_save')) {
        return;
    }
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || 'dsgvo_map' !== get_post_type($post_id) || !current_user_can('edit_post', $post_id)) {
        return;
    }
    // Missing or non-scalar fields are ignored instead of erasing existing values.
    $input = static function ($key) {
        // The enclosing handler verified the nonce and edit capability; each returned value is sanitized for its specific field below.
        // phpcs:ignore WordPress.Security.NonceVerification.Missing,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        return isset($_POST[$key]) && is_string($_POST[$key]) ? wp_unslash($_POST[$key]) : null;
    };
    $iframe_input = $input('dsgvo_gm_iframe');
    $iframe_previous = dsgvo_gm_meta($post_id, '_dsgvo_gm_iframe');
    // Browsers submit textarea newlines as CRLF; this does not constitute an author edit.
    $iframe_changed = null !== $iframe_input && str_replace(array("\r\n", "\r"), "\n", $iframe_input) !== str_replace(array("\r\n", "\r"), "\n", $iframe_previous);
    if ($iframe_changed) {
        $iframe = dsgvo_gm_sanitize_iframe($iframe_input);
        if ('' === trim($iframe_input) || '' !== $iframe) {
            update_post_meta($post_id, '_dsgvo_gm_iframe', wp_slash($iframe));
        } else {
            set_transient('dsgvo_gm_iframe_error_' . get_current_user_id(), 1, MINUTE_IN_SECONDS);
        }
    }
    $text_fields = array('button_text', 'privacy_text', 'privacy_link_text', 'remember_text');
    foreach ($text_fields as $field) {
        $value = $input('dsgvo_gm_' . $field);
        if (null !== $value) {
            update_post_meta($post_id, '_dsgvo_gm_' . $field, sanitize_text_field(substr($value, 0, 2000)));
        }
    }
    $message = $input('dsgvo_gm_message_text');
    if (null !== $message) {
        update_post_meta($post_id, '_dsgvo_gm_message_text', sanitize_textarea_field(substr($message, 0, 8000)));
    }
    foreach (array('button_font_size', 'privacy_font_size', 'privacy_link_font_size', 'message_font_size', 'remember_font_size') as $field) {
        $value = $input('dsgvo_gm_' . $field);
        if (null !== $value) {
            dsgvo_gm_save_size($post_id, $field, $value, true);
        }
    }
    foreach (array('button_shape' => array('rounded', 'square'), 'template' => array('light', 'dark', 'custom')) as $field => $allowed) {
        $value = $input('dsgvo_gm_' . $field);
        if (null !== $value && in_array($value, $allowed, true)) {
            update_post_meta($post_id, '_dsgvo_gm_' . $field, $value);
        }
    }
    foreach (array('overlay_bg', 'button_bg', 'button_color', 'privacy_color', 'remember_color') as $field) {
        $value = $input('dsgvo_gm_' . $field);
        if (null !== $value) {
            $color = sanitize_hex_color($value);
            if ($color) {
                update_post_meta($post_id, '_dsgvo_gm_' . $field, $color);
            } elseif ('' === $value) {
                delete_post_meta($post_id, '_dsgvo_gm_' . $field);
            }
        }
    }
    foreach (array('width', 'height') as $field) {
        $value = $input('dsgvo_gm_' . $field);
        if (null !== $value) {
            dsgvo_gm_save_size($post_id, $field, $value);
        }
    }
    foreach (array('privacy_enabled', 'load_all_enabled', 'remember_enabled') as $field) {
        update_post_meta($post_id, '_dsgvo_gm_' . $field, '1' === $input('dsgvo_gm_' . $field) ? 1 : 0);
    }
    $privacy_link = $input('dsgvo_gm_privacy_link');
    if (null !== $privacy_link) {
        update_post_meta($post_id, '_dsgvo_gm_privacy_link', esc_url_raw($privacy_link, array('https', 'http')));
    }
}

add_action('admin_notices', function () {
    $screen = get_current_screen();
    $size_key = 'dsgvo_gm_size_error_' . get_current_user_id();
    if ($screen && 'dsgvo_map' === $screen->post_type && get_transient($size_key)) {
        delete_transient($size_key);
        echo '<div class="notice notice-error"><p>' . esc_html__('One or more size values were not saved because they contain unsupported CSS. The previous values have been kept.', 'gdpr-dsgvo-compliant-embeds-for-google-maps') . '</p></div>';
    }
    $key = 'dsgvo_gm_iframe_error_' . get_current_user_id();
    if ($screen && 'dsgvo_map' === $screen->post_type && get_transient($key)) {
        delete_transient($key);
        echo '<div class="notice notice-error"><p>' . esc_html__('The iframe was not saved because it is not a supported HTTPS Google Maps embed. The previous embed has been kept.', 'gdpr-dsgvo-compliant-embeds-for-google-maps') . '</p></div>';
    }
});
