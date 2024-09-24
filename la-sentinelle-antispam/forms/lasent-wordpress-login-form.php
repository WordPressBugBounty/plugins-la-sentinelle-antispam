<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add spamfilter fields to login form.
 *
 * @since 1.0.0
 */
function la_sentinelle_login_form() {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-wplogin', 'true') === 'true') {

	// Add spamfilter fields to WordPress login form.
	add_action( 'login_form', 'la_sentinelle_login_form' );
	add_action( 'login_form', 'la_sentinelle_dead_enqueue' );

	// Add spamfilter fields to WooCommerce login form.
	add_action( 'woocommerce_login_form', 'la_sentinelle_login_form' );

	// EDD shortcode [edd_login].
	add_action( 'edd_login_fields_before', 'la_sentinelle_login_form' );
	// EDD Checkout with included login form (not used).
	add_action( 'edd_checkout_login_fields_before', 'la_sentinelle_login_form' );

	// Add spamfilter fields to Clean login form [clean-login].
	add_action( 'cl_login_form', 'la_sentinelle_login_form' );

}


/*
 * Check fields in WordPress Core login form.
 *
 * @param object WP_User|WP_Error $user WP_User or WP_Error object if a previous callback failed authentication.
 *
 * @since 1.0.0
 */
function la_sentinelle_authenticate( $user ) {

	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) {
		return $user;
	}

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'wplogin' );
		$error_messages = la_sentinelle_get_default_error_messages();
		return new WP_Error( 'likely_spammer', $error_messages['try_again'] );
	}

	$user_array = (array) $user;
	$marker_sfs = la_sentinelle_check_stop_forum_spam_wplogin( $user_array );
	if ( $marker_sfs === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wplogin' );
		return new WP_Error( 'likely_spammer', $error_messages['try_again'] );
	}

	return $user;

}
if (get_option( 'la_sentinelle-wplogin', 'true') === 'true') {
	add_filter( 'wp_authenticate_user', 'la_sentinelle_authenticate', 10, 1 );
}


/*
 * Check fields in EDD login form.
 *
 * @param array data WP_User in the form of an array.
 *
 * @since 1.8.0
 */
function la_sentinelle_edd_authenticate( $data ) {

	if ( ! function_exists( 'edd_set_error' ) ) {
		return;
	}

	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) {
		return;
	}

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		$error_messages = la_sentinelle_get_default_error_messages();
		edd_set_error( 'likely_spammer', $error_messages['try_again'] );
	}

	if ( ! isset( $data['user_login'] ) && isset( $data['edd_user_login'] ) ) {
		$data['user_login'] = $data['edd_user_login'];
	}
	$marker_sfs = la_sentinelle_check_stop_forum_spam_wplogin( $user_array );
	if ( $marker_sfs === 'spam' ) {
		edd_set_error( 'likely_spammer', $error_messages['try_again'] );
	}

	if ( ( is_array( $markers ) && ! empty( $markers ) ) || $marker_sfs === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wplogin' );
		// Do a redirect and do not process any further. EDD will log the user in otherwise. The error gets shown after the redirect.
		$redirect = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		wp_safe_redirect( $redirect );
		exit;
	}

}
if (get_option( 'la_sentinelle-wplogin', 'true') === 'true') {
	add_action( 'edd_user_login', 'la_sentinelle_edd_authenticate', 1 );
}


/*
 * Remove our checks from EDD checkout login form, it breaks, seemingly at random.
 *
 * @param array data WP_User in the form of an array.
 *
 * @since 1.8.0
 */
function la_sentinelle_edd_authenticate_action( $data ) {

	remove_filter( 'wp_authenticate_user', 'la_sentinelle_authenticate', 10, 1 );

}
//add_action( 'edd_user_login', 'la_sentinelle_edd_authenticate_action', 1 ); // Shortcode works, no need to remove spamfilter.
add_action( 'edd_insert_user', 'la_sentinelle_edd_authenticate_action', 1 );
add_action( 'wp_ajax_edd_process_checkout_login', 'la_sentinelle_edd_authenticate_action', 1 ); // Checkout login, sends only main data, no extra spamfilter data, so remove our spamfilter.
add_action( 'wp_ajax_nopriv_edd_process_checkout_login', 'la_sentinelle_edd_authenticate_action', 1 ); // Checkout login, sends only main data, no extra spamfilter data, so remove our spamfilter.
