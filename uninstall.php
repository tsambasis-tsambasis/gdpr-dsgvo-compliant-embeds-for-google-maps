<?php
/**
 * Delete map configurations on each site when WordPress uninstalls the plugin.
 *
 * @package GDPR_Google_Maps_Embed_SF
 * @license GPLv2 or later
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

function dsgvo_gm_uninstall_site()
{
    // All statuses include drafts, trash and auto-drafts; bounded batches avoid large loads.
    do {
        $ids = get_posts(array(
            'post_type' => 'dsgvo_map',
            'post_status' => array_keys(get_post_stati()),
            'numberposts' => 100,
            'fields' => 'ids',
            'orderby' => 'ID',
            'order' => 'ASC',
            'no_found_rows' => true,
            // phpcs:ignore WordPressVIPMinimum.Performance.WPQueryParams.SuppressFilters_suppress_filters -- Uninstall must remove all of this plugin's private records, independent of presentation-language filters.
            'suppress_filters' => true,
        ));
        $removed = 0;
        foreach ($ids as $id) {
            if (wp_delete_post($id, true)) {
                ++$removed;
            }
        }
    } while (count($ids) === 100 && $removed > 0);
}

if (is_multisite()) {
    $dsgvo_gm_offset = 0;
    do {
        $dsgvo_gm_site_ids = get_sites(array('fields' => 'ids', 'number' => 100, 'offset' => $dsgvo_gm_offset));
        foreach ($dsgvo_gm_site_ids as $dsgvo_gm_site_id) {
            switch_to_blog($dsgvo_gm_site_id);
            dsgvo_gm_uninstall_site();
            restore_current_blog();
        }
        $dsgvo_gm_offset += count($dsgvo_gm_site_ids);
    } while (count($dsgvo_gm_site_ids) === 100);
} else {
    dsgvo_gm_uninstall_site();
}
