<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Filters the array of extra elements to list in the 'At a Glance'
 * dashboard widget.
 *
 * @since 2.1.3
 *
 * @param array $items list of extra 'At a Glance' widget items.
 * @return array $items list of extra 'At a Glance' widget items.
 */
function la_sentinelle_dashboard_glance_items( $items ) {

	// Fallback: Make sure admin always has access
	$lasent_cap = ( current_user_can( 'lasent_access') ) ? 'lasent_access' : 'manage_options';

	if ( ! current_user_can( $lasent_cap ) ) {
		return $items;
	}

	$count_posts = wp_count_posts( 'la_sentinelle_log' );
	$total_items = (int) $count_posts->draft;
	if ( $total_items === 0 ) {
		return $items;
	}

	$total_items_count_i18n = number_format_i18n( $total_items );
	$admin_url = admin_url( '/options-general.php?page=la-sentinelle-log.php' );

	/* translators: %s: Number of spam submissions. */
	$text = sprintf( _n( '%s Spam submission', '%s Spam submissions', $total_items, 'la-sentinelle-antispam' ), $total_items_count_i18n );
	$items[] = '<a href="' . $admin_url . '" class="la-sentinelle-spam-log">' . $text . '</a>';

	return $items;

}
add_action( 'dashboard_glance_items', 'la_sentinelle_dashboard_glance_items' );
