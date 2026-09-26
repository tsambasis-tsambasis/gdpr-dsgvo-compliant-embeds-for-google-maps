(function ($) {
    'use strict';

    var consentCookieName = 'dsgvo_gm_consent';
    var consentCookieDays = 180;
    var messages = window.dsgvoGm || {};

    function hasConsentCookie() {
        return document.cookie.split(';').some(function (cookie) {
            return cookie.trim() === consentCookieName + '=1';
        });
    }

    function writeConsentCookie(remember) {
        var expires = new Date(remember ? Date.now() + consentCookieDays * 86400000 : 0);
        var cookie = consentCookieName + '=' + (remember ? '1' : '') + '; expires=' + expires.toUTCString() + '; path=/; SameSite=Lax';
        if (window.location.protocol === 'https:') {
            cookie += '; Secure';
        }
        document.cookie = cookie;
    }

    function allowedMapUrl(src) {
        if (/[\s\\\u0000-\u001f\u007f]/.test(src)) {
            return null;
        }
        var url;
        try {
            url = new URL(src);
        } catch (_) {
            return null;
        }
        var hosts = ['www.google.com', 'google.com', 'maps.google.com', 'www.google.de', 'google.de', 'maps.google.de'];
        if (url.protocol !== 'https:' || url.username || url.password || url.port || hosts.indexOf(url.hostname) === -1) {
            return null;
        }
        var embedPath = /^\/maps\/embed\/?$/.test(url.pathname) ||
            /^\/maps\/embed\/v1\/(place|view|directions|streetview|search)\/?$/.test(url.pathname) ||
            /^\/maps\/d\/embed\/?$/.test(url.pathname);
        var legacyPath = (url.pathname === '/maps' || url.pathname === '/maps/' || url.pathname === '/') && url.searchParams.get('output') === 'embed';
        return embedPath || legacyPath ? url.href : null;
    }

    function showError($container) {
        if (!$container.find('.dsgvo-gm-error').length) {
            $('<p>', { 'class': 'dsgvo-gm-error', role: 'alert' })
                .text(messages.errorText || 'This map could not be loaded. Please contact the website owner.')
                .appendTo($container);
        }
    }

    function loadOverlay($container) {
        if (!$container.length || $container.data('dsgvoGmLoaded')) {
            return false;
        }
        try {
            var b64 = ($container.attr('data-iframe') || '').replace(/\s+/g, '');
            if (!b64 || b64.length > 131072) {
                throw new Error('Invalid map data');
            }
            var raw = window.atob(b64);
            var bytes = Uint8Array.from(raw, function (character) { return character.charCodeAt(0); });
            var html = window.TextDecoder ? new TextDecoder('utf-8', { fatal: true }).decode(bytes) : raw;
            // Template contents are inert: discarded images, scripts and frames never load.
            var template = document.createElement('template');
            template.innerHTML = html;
            var iframe = template.content.querySelector('iframe');
            var src = iframe && allowedMapUrl(iframe.getAttribute('src') || '');
            if (!src) {
                throw new Error('Unsupported map URL');
            }

            // Rebuild from an allowlist; never copy author-supplied CSS or active attributes.
            var $safeIframe = $('<iframe>', {
                src: src,
                title: iframe.getAttribute('title') || messages.frameTitle || 'Google Maps',
                'class': 'dsgvo-gm-frame',
                allowfullscreen: '',
                loading: 'lazy',
                referrerpolicy: 'strict-origin-when-cross-origin',
                sandbox: 'allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox allow-top-navigation-by-user-activation'
            });
            var $revoke = $('<button>', { type: 'button', 'class': 'dsgvo-gm-revoke-btn' })
                .text(messages.revokeText || 'Unload maps and reset choice');
            var $controls = $('<div>', { 'class': 'dsgvo-gm-controls' }).append($revoke);
            $container.find('.dsgvo-gm-error').remove();
            $container.closest('.dsgvo-gm-container').addClass('dsgvo-gm-map-loaded');
            $container.data('dsgvoGmOriginal', $container.contents().detach());
            $container.data('dsgvoGmLoaded', true).addClass('dsgvo-gm-loaded').append($safeIframe, $controls);
            return true;
        } catch (_) {
            showError($container);
            return false;
        }
    }

    function getTargetOverlays($container) {
        return $container.attr('data-load-all') === '1' ? $('.dsgvo-gm-overlay[data-load-all="1"]') : $container;
    }

    $(document).on('click', '.dsgvo-gm-load-btn', function (event) {
        event.preventDefault();
        var $container = $(this).closest('.dsgvo-gm-overlay');
        var remember = $container.attr('data-remember-enabled') === '1' && $container.find('.dsgvo-gm-remember-checkbox').is(':checked');
        var loaded = false;
        getTargetOverlays($container).each(function () {
            loaded = loadOverlay($(this)) || loaded;
        });
        if (remember && loaded) {
            writeConsentCookie(true);
        }
    });

    $(document).on('click', '.dsgvo-gm-revoke-btn', function (event) {
        event.preventDefault();
        var $current = $(this).closest('.dsgvo-gm-overlay');
        writeConsentCookie(false);
        $('.dsgvo-gm-overlay').each(function () {
            var $container = $(this);
            if ($container.data('dsgvoGmLoaded')) {
                var original = $container.data('dsgvoGmOriginal');
                $container.empty().append(original).removeClass('dsgvo-gm-loaded');
                $container.closest('.dsgvo-gm-container').removeClass('dsgvo-gm-map-loaded');
                $container.removeData('dsgvoGmLoaded').removeData('dsgvoGmOriginal');
            }
            $container.find('.dsgvo-gm-remember-checkbox').prop('checked', false);
        });
        $current.find('.dsgvo-gm-load-btn').trigger('focus');
    });

    $(function () {
        if (hasConsentCookie()) {
            $('.dsgvo-gm-overlay[data-remember-enabled="1"]').each(function () {
                loadOverlay($(this));
            });
        }
    });
})(jQuery);
