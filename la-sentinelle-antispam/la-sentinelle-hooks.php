<?php

/*
 * WordPress Actions and Filters.
 * See the Plugin API in the Codex:
 * http://codex.wordpress.org/Plugin_API
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Load CSS for admin.
 * Load JavaScript for admin.
 * It's called directly on the adminpages, it's not being used as a hook.
 *
 * @since 1.0.0
 */
function la_sentinelle_admin_enqueue() {
	wp_enqueue_style( 'la-sentinelle-admin-css', plugins_url( '/admin/css/la-sentinelle-admin.css', __FILE__ ), false, LASENT_VER, 'all' );
	wp_enqueue_script( 'la-sentinelle-admin-js', plugins_url( '/admin/js/la-sentinelle-admin.js', __FILE__ ), 'jquery', LASENT_VER, true );
}
//add_action( 'admin_enqueue_scripts', 'la_sentinelle_admin_enqueue' );


/*
 * Add link to settingspage on Dashboard > Plugins.
 *
 * @since 1.0.0
 */
function la_sentinelle_links( $links, $file ) {
	if ( $file === plugin_basename( dirname(__FILE__) . '/la-sentinelle.php' ) ) {
		$links[] = '<a href="' . admin_url( 'options-general.php?page=la-sentinelle.php' ) . '">' . esc_html__( 'Settings', 'la-sentinelle-antispam' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'la_sentinelle_links', 10, 2 );


/*
 * Check if we need to install or upgrade.
 * Supports MultiSite since 1.0.0.
 *
 * @since 1.0.0
 */
function la_sentinelle_init() {

	global $wpdb;

	$current_version = get_option( 'la_sentinelle-version' );

	if ($current_version && version_compare($current_version, LASENT_VER, '<')) {
		// Upgrade, if this version differs from what the database says.

		if ( is_multisite() ) {
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				la_sentinelle_set_defaults();
				restore_current_blog();
			}
		} else {
			la_sentinelle_set_defaults();
		}
	}
}
add_action( 'admin_init', 'la_sentinelle_init' );


/*
 * Install new blog on MultiSite.
 * Deprecated action since WP 5.1.0.
 *
 * @since 1.0.0
 */
function la_sentinelle_activate_new_site( $blog_id ) {
	switch_to_blog($blog_id);
	la_sentinelle_set_defaults();
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'la_sentinelle_activate_new_site' );


/*
 * Install new blog on MultiSite.
 * Used since WP 5.1.0.
 * Do not use wp_insert_site, since the options table doesn't exist yet...
 *
 * @since 1.5.2
 */
function la_sentinelle_wp_initialize_site( $blog ) {
	switch_to_blog( $blog->id );
	la_sentinelle_set_defaults();
	restore_current_blog();
}
add_action( 'wp_initialize_site', 'la_sentinelle_wp_initialize_site' );


/*
 * Load Language files for frontend and backend.
 *
 * @since 1.0.0
 */
function la_sentinelle_load_lang() {
	load_plugin_textdomain( 'la-sentinelle-antispam', false, LASENT_FOLDER . '/lang' );
}
add_action('plugins_loaded', 'la_sentinelle_load_lang');
