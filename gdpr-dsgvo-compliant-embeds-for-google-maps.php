<?php

/**
 * Plugin Name:     GDPR-DSGVO compliant Embeds for Google Maps
 * Plugin URI:      https://solutionfirst.m00dy.org/wp-plugin/
 * Description:     Embeds Google Maps after consent, with per-map styles, notices and optional remembered choices.
 * Version:         1.1.3
 * Requires at least: 6.2
 * Requires PHP:    7.4
 * Author:          Tsambasis & Tsambasis
 * Author URI:      https://tsambasis.net/
 * Text Domain:     gdpr-dsgvo-compliant-embeds-for-google-maps
 * Domain Path:     /languages
 *
 * @package         GDPR_Google_Maps_Embed_SF
 *
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 */


if (! defined('ABSPATH')) exit; // Exit if accessed directly

function dsgvo_gm_plugin_action_links($links)
{
    $settings_label = __('Settings', 'gdpr-dsgvo-compliant-embeds-for-google-maps');
    $info_label      = __('More information', 'gdpr-dsgvo-compliant-embeds-for-google-maps');

    $new_links = array(
        // Link 1: Settings
        '<a href="' . esc_url(admin_url('edit.php?post_type=dsgvo_map')) . '">'
            . esc_html($settings_label) .
            '</a>',
        // Link 2: More information
        '<a href="' . esc_url('https://solutionfirst.m00dy.org/wp-plugin/') . '" target="_blank" rel="noopener noreferrer" title="Tsambasis &amp; Tsambasis" class="dsgvo-gm-info-link">'
            . esc_html($info_label) .
            '</a>',
    );

    return array_merge($links, $new_links);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'dsgvo_gm_plugin_action_links');


// Constants
define('DSGVO_GM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DSGVO_GM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DSGVO_GM_VERSION', '1.1.3');

// Direct ZIP installations need a registered local path before the first gettext call.
add_action('init', 'dsgvo_gm_register_translations', 0);
function dsgvo_gm_register_translations()
{
    global $wp_textdomain_registry;
    if ($wp_textdomain_registry instanceof WP_Textdomain_Registry) {
        $wp_textdomain_registry->set_custom_path('gdpr-dsgvo-compliant-embeds-for-google-maps', DSGVO_GM_PLUGIN_DIR . 'languages');
    }
}

/** Preserve the font-size syntax accepted before 1.1.1, including zero and large sizes. */
function dsgvo_gm_sanitize_font_size($font_size, $default = '')
{
    if (!is_scalar($font_size)) {
        return $default;
    }
    $font_size = trim((string) $font_size);
    return preg_match('/^\d+(\.\d+)?(px|em|rem|%)$/iD', $font_size) ? $font_size : $default;
}

/** A deliberately small CSS math grammar: lengths, numbers and four sizing functions. */
final class DSGVO_GM_CSS_Size
{
    private $tokens;
    private $position = 0;

    public static function valid($value)
    {
        // No declarations, strings, escapes, comments, custom properties or network functions.
        if (strlen($value) > 512 || !preg_match('/^(?:calc|min|max|clamp)\(/i', $value)) {
            return false;
        }
        preg_match_all('/(?:\d*\.\d+|\d+)(?:px|rem|em|dvw|dvh|svw|svh|lvw|lvh|vmin|vmax|vw|vh|rlh|lh|ch|ex|cm|mm|in|pt|pc|%)?|calc|min|max|clamp|[()+*\/,\-]|\s+/i', $value, $matches);
        if (implode('', $matches[0]) !== $value || count($matches[0]) > 128) {
            return false;
        }
        $previous = null;
        foreach ($matches[0] as $index => $token) {
            if (in_array($token, array('+', '-'), true)) {
                $binary = null !== $previous && (')' === $previous || preg_match('/^[0-9.]/', $previous));
                if ($binary && (!isset($matches[0][$index - 1], $matches[0][$index + 1]) || '' !== trim($matches[0][$index - 1]) || '' !== trim($matches[0][$index + 1]))) { return false; }
                if (!$binary && (!isset($matches[0][$index + 1]) || '' === trim($matches[0][$index + 1]))) { return false; }
            }
            if ('' !== trim($token)) { $previous = $token; }
        }
        $parser = new self();
        $parser->tokens = array_values(array_filter($matches[0], static function ($token) { return '' !== trim($token); }));
        $result = $parser->atom(0);
        return null !== $result && $parser->position === count($parser->tokens) && ('length' === $result[0] || 0.0 === $result[1]);
    }

    private function current()
    {
        return isset($this->tokens[$this->position]) ? $this->tokens[$this->position] : null;
    }

    private function sum($depth)
    {
        $left = $this->product($depth);
        while (null !== $left && in_array($this->current(), array('+', '-'), true)) {
            $operator = $this->tokens[$this->position++];
            $right = $this->product($depth);
            if (null === $right || $left[0] !== $right[0]) {
                return null;
            }
            if ('number' === $left[0]) {
                $left[1] = '+' === $operator ? $left[1] + $right[1] : $left[1] - $right[1];
                if (!is_finite($left[1])) { return null; }
            }
        }
        return $left;
    }

    private function product($depth)
    {
        $left = $this->atom($depth);
        while (null !== $left && in_array($this->current(), array('*', '/'), true)) {
            $operator = $this->tokens[$this->position++];
            $right = $this->atom($depth);
            if (null === $right || ('/' === $operator && ('number' !== $right[0] || 0.0 === $right[1])) || ('*' === $operator && 'length' === $left[0] && 'length' === $right[0])) {
                return null;
            }
            if ('number' === $left[0] && 'number' === $right[0]) {
                $left[1] = '*' === $operator ? $left[1] * $right[1] : $left[1] / $right[1];
                if (!is_finite($left[1])) { return null; }
            } else {
                $left = array('length', null);
            }
        }
        return $left;
    }

    private function atom($depth)
    {
        if ($depth > 12) { return null; }
        $token = $this->current();
        $sign = 1;
        if (in_array($token, array('+', '-'), true)) {
            $sign = '-' === $token ? -1 : 1;
            ++$this->position;
            $token = $this->current();
            if (!is_string($token) || !preg_match('/^[0-9.]/', $token)) { return null; }
        }
        if (null === $token) { return null; }
        if (preg_match('/^((?:\d*\.\d+|\d+))(px|rem|em|dvw|dvh|svw|svh|lvw|lvh|vmin|vmax|vw|vh|rlh|lh|ch|ex|cm|mm|in|pt|pc|%)?$/iD', $token, $parts)) {
            ++$this->position;
            $number = (float) $parts[1] * $sign;
            return is_finite($number) ? array(isset($parts[2]) ? 'length' : 'number', isset($parts[2]) ? null : $number) : null;
        }
        if ('(' === $token) {
            ++$this->position;
            $value = $this->sum($depth + 1);
            if (')' !== $this->current()) { return null; }
            ++$this->position;
            return $value;
        }
        $function = strtolower($token);
        if (!in_array($function, array('calc', 'min', 'max', 'clamp'), true)) { return null; }
        ++$this->position;
        if ('(' !== $this->current()) { return null; }
        ++$this->position;
        $arguments = array();
        do {
            if (count($arguments) >= 16) { return null; }
            $argument = $this->sum($depth + 1);
            if (null === $argument || ($arguments && $arguments[0][0] !== $argument[0])) { return null; }
            $arguments[] = $argument;
            if (',' !== $this->current()) { break; }
            ++$this->position;
        } while (true);
        if (')' !== $this->current() || ('calc' === $function && 1 !== count($arguments)) || ('clamp' === $function && 3 !== count($arguments))) { return null; }
        ++$this->position;
        if ('length' === $arguments[0][0] || 'calc' === $function) { return $arguments[0]; }
        $values = array_column($arguments, 1);
        $number = 'min' === $function ? min($values) : ('max' === $function ? max($values) : max($values[0], min($values[1], $values[2])));
        return array('number', $number);
    }
}

/** Allow safe sizing values while keeping numeric legacy inputs as pixels. */
function dsgvo_gm_sanitize_dimension($value, $default = '100%')
{
    if (!is_scalar($value)) { return $default; }
    $value = trim((string) $value);
    // An optional final declaration terminator was harmless in the previous inline style.
    $value = rtrim($value, "; \t\n\r\0\x0B");
    if (strlen($value) > 512) { return $default; }
    if (preg_match('/^(?:\d*\.\d+|\d+)(?:px|rem|em|dvw|dvh|svw|svh|lvw|lvh|vmin|vmax|vw|vh|rlh|lh|ch|ex|cm|mm|in|pt|pc|%)?$/iD', $value)) {
        return preg_match('/[a-z%]$/i', $value) ? $value : $value . 'px';
    }
    if (in_array(strtolower($value), array('auto', 'inherit', 'initial', 'unset', 'min-content', 'max-content', 'fit-content'), true) || DSGVO_GM_CSS_Size::valid($value)) {
        return $value;
    }
    return $default;
}

/** Only Google Maps embed endpoints can be stored or rendered. No network request. */
function dsgvo_gm_sanitize_embed_url($value)
{
    if (!is_string($value) || strlen($value) > 16000 || preg_match('/[\x00-\x20\x7f\\\\]/', $value)) {
        return '';
    }
    $parts = wp_parse_url($value);
    $hosts = array('www.google.com', 'google.com', 'maps.google.com', 'www.google.de', 'google.de', 'maps.google.de');
    if (!is_array($parts) || !isset($parts['scheme'], $parts['host']) || 'https' !== strtolower($parts['scheme']) || !in_array(strtolower($parts['host']), $hosts, true) || isset($parts['user']) || isset($parts['pass']) || (isset($parts['port']) && 443 !== $parts['port'])) {
        return '';
    }
    $path = isset($parts['path']) ? $parts['path'] : '/';
    $embedded = (bool) preg_match('~^/maps/(?:embed(?:/v1/(?:place|view|directions|streetview|search))?|d/embed)/?$~D', $path);
    if (!$embedded && in_array($path, array('/', '/maps', '/maps/'), true)) {
        $query = array();
        wp_parse_str(isset($parts['query']) ? $parts['query'] : '', $query);
        $embedded = isset($query['output']) && is_string($query['output']) && 'embed' === $query['output'];
    }
    return $embedded ? esc_url_raw($value, array('https')) : '';
}

/** Rebuild the iframe instead of carrying any author-supplied HTML attributes. */
function dsgvo_gm_sanitize_iframe($value)
{
    if (!is_string($value) || strlen($value) > 20000 || !class_exists('WP_HTML_Tag_Processor')) {
        return '';
    }
    $processor = new WP_HTML_Tag_Processor($value);
    if (!$processor->next_tag('iframe')) {
        return '';
    }
    $url = dsgvo_gm_sanitize_embed_url($processor->get_attribute('src'));
    if ('' === $url || $processor->next_tag('iframe')) {
        return '';
    }
    return '<iframe src="' . esc_url($url, array('https')) . '" width="600" height="450" title="' . esc_attr__('Google Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps') . '" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
}

/** Read scalar metadata only; malformed values must never reach HTML or CSS. */
function dsgvo_gm_meta($post_id, $key, $default = '')
{
    $value = get_post_meta($post_id, $key, true);
    return is_scalar($value) && '' !== (string) $value ? (string) $value : $default;
}

// Activation & Deactivation
register_activation_hook(__FILE__, 'dsgvo_gm_activate');
function dsgvo_gm_activate()
{
    dsgvo_gm_register_post_type();
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'dsgvo_gm_deactivate');
function dsgvo_gm_deactivate()
{
    flush_rewrite_rules();
}

add_action('wp_enqueue_scripts', 'dsgvo_gm_enqueue_assets');
function dsgvo_gm_enqueue_assets()
{
    // CSS: Load CSS
    wp_enqueue_style(
        'dsgvo-gm-style',
        DSGVO_GM_PLUGIN_URL . 'assets/css/dsgvo-gm.css',
        array(),
        DSGVO_GM_VERSION
    );

    // JS: Load JS
    wp_enqueue_script(
        'dsgvo-gm-script',
        DSGVO_GM_PLUGIN_URL . 'assets/js/dsgvo-gm.js',
        ['jquery'],
        DSGVO_GM_VERSION,
        true
    );

    wp_localize_script('dsgvo-gm-script', 'dsgvoGm', array(
        'buttonText' => __('Load Google Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        'revokeText' => __('Unload maps and reset choice', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        'errorText' => __('This map could not be loaded. Please contact the website owner.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        'frameTitle' => __('Google Maps', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
        'resetText' => __('Your choice has been reset. Maps will load only after you consent again.', 'gdpr-dsgvo-compliant-embeds-for-google-maps'),
    ));
}

// Includes
require_once DSGVO_GM_PLUGIN_DIR . 'includes/admin.php';
require_once DSGVO_GM_PLUGIN_DIR . 'includes/frontend.php';
