<?php
/**
 * Consent placeholder for published Google Maps configurations.
 *
 * @package GDPR_Google_Maps_Embed_SF
 * @license GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

add_shortcode('dsgvo_map', 'dsgvo_gm_shortcode');
function dsgvo_gm_shortcode($atts)
{
    $atts = shortcode_atts(array('id' => '', 'class' => ''), $atts, 'dsgvo_map');
    if (!is_scalar($atts['id']) || !preg_match('/^[1-9][0-9]*$/D', (string) $atts['id'])) {
        return '';
    }
    $id = (int) $atts['id'];
    $map = get_post($id);
    if (!$map || 'dsgvo_map' !== $map->post_type || 'publish' !== $map->post_status || !empty($map->post_password)) {
        return '';
    }
    // Validate existing metadata on every render, including entries saved by older versions.
    $iframe = dsgvo_gm_sanitize_iframe(dsgvo_gm_meta($id, '_dsgvo_gm_iframe'));
    if ('' === $iframe) {
        return '';
    }
    $btn_text = dsgvo_gm_meta($id, '_dsgvo_gm_button_text', __('Load Map', 'gdpr-dsgvo-compliant-embeds-for-google-maps'));
    $btn_shape = dsgvo_gm_meta($id, '_dsgvo_gm_button_shape', 'rounded');
    $template = dsgvo_gm_meta($id, '_dsgvo_gm_template', 'light');
    $template = in_array($template, array('light', 'dark', 'custom'), true) ? $template : 'light';
    $color = static function ($key, $default) use ($id) {
        $value = sanitize_hex_color(dsgvo_gm_meta($id, '_dsgvo_gm_' . $key));
        return $value ? $value : $default;
    };
    $font = static function ($key, $default) use ($id) {
        return dsgvo_gm_sanitize_font_size(dsgvo_gm_meta($id, '_dsgvo_gm_' . $key), $default);
    };
    $privacy_enabled = '1' === dsgvo_gm_meta($id, '_dsgvo_gm_privacy_enabled');
    $privacy_link = esc_url_raw(dsgvo_gm_meta($id, '_dsgvo_gm_privacy_link'), array('https', 'http'));
    $load_all_enabled = '1' === dsgvo_gm_meta($id, '_dsgvo_gm_load_all_enabled') ? 1 : 0;
    $remember_enabled = '1' === dsgvo_gm_meta($id, '_dsgvo_gm_remember_enabled') ? 1 : 0;
    $privacy_text = dsgvo_gm_meta($id, '_dsgvo_gm_privacy_text', __('Please see our', 'gdpr-dsgvo-compliant-embeds-for-google-maps'));
    $privacy_link_text = dsgvo_gm_meta($id, '_dsgvo_gm_privacy_link_text', __('Privacy Policy', 'gdpr-dsgvo-compliant-embeds-for-google-maps'));
    $message_text = dsgvo_gm_meta($id, '_dsgvo_gm_message_text');
    $remember_text = dsgvo_gm_meta($id, '_dsgvo_gm_remember_text', __('Remember selection', 'gdpr-dsgvo-compliant-embeds-for-google-maps'));
    $width = dsgvo_gm_sanitize_dimension(dsgvo_gm_meta($id, '_dsgvo_gm_width'));
    $height = dsgvo_gm_sanitize_dimension(dsgvo_gm_meta($id, '_dsgvo_gm_height'));
    $style_attr = 'width:' . $width . ';position:relative;overflow:hidden;';
    $style_attr .= '%' === substr($height, -1) ? 'height:0;padding-bottom:' . $height . ';' : 'height:' . $height . ';';
    $class = 'dsgvo-gm-' . $template;
    $extra_classes = array();
    if (is_string($atts['class'])) {
        foreach (preg_split('/\s+/', trim($atts['class'])) as $extra) {
            $extra_classes[] = sanitize_html_class($extra);
        }
    }
    $overlay_style = 'custom' === $template ? 'background-color:' . $color('overlay_bg', '#ffffff') . ';' : '';
    $btn_style = 'custom' === $template ? 'background-color:' . $color('button_bg', '#0073aa') . ';color:' . $color('button_color', '#ffffff') . ';' : '';
    $btn_style .= 'font-size:' . $font('button_font_size', '16px') . ';border-radius:' . ('square' === $btn_shape ? '0' : '15px') . ';';
    $privacy_style = 'custom' === $template ? 'color:' . $color('privacy_color', '#666666') . ';' : '';
    $privacy_text_style = $privacy_style . 'font-size:' . $font('privacy_font_size', '0.8em') . ';';
    $privacy_link_style = $privacy_style . 'font-size:' . $font('privacy_link_font_size', '0.8em') . ';';
    $message_style = $privacy_style . 'font-size:' . $font('message_font_size', '0.9em') . ';';
    $remember_color = sanitize_hex_color(dsgvo_gm_meta($id, '_dsgvo_gm_remember_color'));
    $remember_style = ($remember_color ? 'color:' . $remember_color . ';' : $privacy_style) . 'font-size:' . $font('remember_font_size', '0.85em') . ';';
    $b64 = base64_encode($iframe);
    $html = '<div class="dsgvo-gm-container ' . esc_attr(trim($class . ' ' . implode(' ', $extra_classes))) . '" style="' . esc_attr($style_attr) . '">';
    $html .= '<div class="dsgvo-gm-overlay ' . esc_attr($class) . '" style="' . esc_attr($overlay_style) . '" data-iframe="' . esc_attr($b64) . '" data-load-all="' . esc_attr((string) $load_all_enabled) . '" data-remember-enabled="' . esc_attr((string) $remember_enabled) . '" data-map-id="' . esc_attr((string) $id) . '">';
    $html .= '<button type="button" class="dsgvo-gm-load-btn ' . esc_attr($class) . '" style="' . esc_attr($btn_style) . '">' . esc_html($btn_text) . '</button>';
    if ('' !== $message_text) {
        $html .= '<div class="dsgvo-gm-message ' . esc_attr($class) . '" style="' . esc_attr($message_style) . '">' . esc_html($message_text) . '</div>';
    }
    if ($privacy_enabled && '' !== $privacy_link) {
        $html .= '<div class="dsgvo-gm-privacy-info ' . esc_attr($class) . '" style="' . esc_attr($privacy_style) . '">'
            . '<span style="' . esc_attr($privacy_text_style) . '">' . esc_html($privacy_text) . '</span>'
            . ' <a href="' . esc_url($privacy_link, array('https', 'http')) . '" target="_blank" rel="noopener noreferrer" style="' . esc_attr($privacy_link_style) . '">'
            . esc_html($privacy_link_text) . '</a></div>';
    }
    if ($remember_enabled) {
        $html .= '<label class="dsgvo-gm-remember-choice ' . esc_attr($class) . '" style="' . esc_attr($remember_style) . '">'
            . '<input type="checkbox" class="dsgvo-gm-remember-checkbox" value="1"> <span>' . esc_html($remember_text) . '</span></label>';
    }
    return $html . '</div></div>';
}
