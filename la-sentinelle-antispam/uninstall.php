<?php
/*
 * This file will be called when pressing 'Delete' on Dashboard > Plugins.
 */


// if uninstall.php is not called by WordPress, die.
if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
	die();
}

$option_names = array(
		'la_sentinelle-ajax',
		'la_sentinelle-honeypot',
		'la_sentinelle-honeypot_value',
		'la_sentinelle-nonce',
		'la_sentinelle-sfs',
		'la_sentinelle-timeout',
		'la_sentinelle-wpcomment',
		'la_sentinelle-wpcomments_blocked',
		'la_sentinelle-wplogin',
		'la_sentinelle-wplogin_blocked',
		'la_sentinelle-wppassword',
		'la_sentinelle-wppassword_blocked',
		'la_sentinelle-wpregister',
		'la_sentinelle-wpregister_blocked',
		'la_sentinelle-caldera_blocked',
		'la_sentinelle-cf7_blocked',
		'la_sentinelle-everest',
		'la_sentinelle-everest_blocked',
		'la_sentinelle-formidable',
		'la_sentinelle-formidable_blocked',
		'la_sentinelle-forminator',
		'la_sentinelle-forminator_blocked',
		'la_sentinelle-noptin',
		'la_sentinelle-noptin_blocked',
		'la_sentinelle-webgl',
		'la_sentinelle-woo-registration',
		'la_sentinelle-wpforms',
		'la_sentinelle-wpforms_blocked',
		'la_sentinelle-wpjobmanager',
		'la_sentinelle-wpjobmanager_blocked',
		'la_sentinelle-save_comments',
		'la_sentinelle-remove_comments',
		'la_sentinelle-version',
	);

foreach ( $option_names as $option_name ) {

	delete_option( $option_name );

	// for site options in Multisite
	delete_site_option( $option_name );

}


$args = array(
	'post_type'              => 'la_sentinelle_log',
	'posts_per_page'         => -1,
	'nopaging'               => true,
	'post_status'            => 'draft',
	'orderby'                => 'date',
	'order'                  => 'ASC',
	'fields'                 => 'ids',
	'cache_results'          => false,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
	);

$the_query = new WP_Query( $args );
if ( isset( $the_query->posts ) && ! empty( $the_query->posts ) ) {
	foreach ( $the_query->posts as $postid ) {
		$postid = (int) $postid;
		if ( isset( $postid ) && $postid > 0 ) {
			// wp_delete_post( $postid = 0, $force_delete = false )
			$deleted_data = wp_delete_post( $postid, true );
		}
	}
}
