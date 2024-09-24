<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add spamfilter fields to registration form.
 *
 * @since 1.0.0
 */
function la_sentinelle_registration_form() {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {

	// WP Core Single site:
	add_action( 'register_form', 'la_sentinelle_registration_form' );
	add_action( 'register_form', 'la_sentinelle_dead_enqueue' );

	// WP Core MultiSite:
	add_action( 'signup_extra_fields', 'la_sentinelle_registration_form' );
	add_action( 'signup_extra_fields', 'la_sentinelle_dead_enqueue' );

	// EDD shortcode [edd_register]
	add_action( 'edd_register_form_fields_before', 'la_sentinelle_registration_form' );
	// EDD Checkout with included register form.
	add_action( 'edd_purchase_form_register_fields', 'la_sentinelle_registration_form' );

	// Woo register form, will be on My Account form and on Checkout form.
	//if (get_option( 'la_sentinelle-woo-registration', 'false') === 'true') { // always add them.
		add_action( 'woocommerce_register_form', 'la_sentinelle_registration_form', 9999 );
		add_action( 'woocommerce_checkout_billing', 'la_sentinelle_registration_form', 9999 );
	//}

}


/*
 * Check fields in registration form and return errors if needed.
 * Adds integration to any core WordPress registration form, like the one at /wp-login.php?action=register
 *
 * @param WP_Error $errors               WP_Error object containing any errors encountered during registration.
 * @param string   $sanitized_user_login User's username after it has been sanitized.
 * @param string   $user_email           User's email.
 *
 */
function la_sentinelle_check_registration_form( $errors, $sanitized_user_login, $user_email ) {

	$marker = la_sentinelle_check_spamfilters();
	$marker_sfs = la_sentinelle_check_stop_forum_spam_wpregister( $sanitized_user_login, $user_email );

	if ( $marker === 'spam' || $marker_sfs === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		$errors->add( 'likely_spammer', esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ) );
	}

	return $errors;

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {

	// WordPress Core
	add_filter( 'registration_errors', 'la_sentinelle_check_registration_form', 10, 3 );

	// Woo register form, will be on My Account form and on Checkout form.
	if (get_option( 'la_sentinelle-woo-registration', 'false') === 'true') {
		// I don't dare to enable this by default. Please use cleanup options for accounts under WooCommerce > Settings > Accounts-tab, that is the least destructive option.
		// See https://wordpress.org/support/topic/not-working-on-woocommerce-registration-form/
		add_filter( 'woocommerce_registration_errors', 'la_sentinelle_check_registration_form', 9999, 3 );
	}

}


/*
 * Check fields in MultiSite registration form and return errors if needed.
 * Adds integration to any core WordPress registration form, like the one at /wp-signup.php
 */
function la_sentinelle_check_registration_form_mu( $result ) {

	// URL on /wp-admin/user-new.php
	if ( is_admin() ) {
		return $result;
	}

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		// Only 'generic' gets shown on the form.
		$result['errors']->add( 'generic', esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ) );
	}

	return $result;

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
	add_filter( 'wpmu_validate_user_signup', 'la_sentinelle_check_registration_form_mu', 10, 1 );
}


/*
 * Adds integration with Restrict Content Pro
 * @url https://restrictcontentpro.com/
 */
function la_sentinelle_check_registration_form_rcp( $user ) {

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		rcp_errors()->add( 'likely_spammer', esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ) );
	}

	return $user;
}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
	add_filter( 'rcp_user_registration_data', 'la_sentinelle_check_registration_form_rcp' );
}


/*
 * Adds integration with MemberPress
 * @url https://www.memberpress.com/
 */
function la_sentinelle_check_registration_form_mepr( $errors ) {

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		$errors[] = esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' );
	}

	return $errors;
}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
	add_filter( 'mepr-validate-signup', 'la_sentinelle_check_registration_form_mepr' );
}


/*
 * Adds integration with Give
 * @url https://givewp.com/
 */
function la_sentinelle_check_registration_form_give() {

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		give_set_error( 'likely_spammer', esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ) );
	}

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
	add_action( 'give_pre_process_register_form', 'la_sentinelle_check_registration_form_give' );
}



/*
 * Check fields in EDD register form.
 *
 * @since 1.8.0
 */
function la_sentinelle_check_registration_form_edd() {

	if ( ! function_exists( 'edd_set_error' ) || ! function_exists( 'edd_die' ) ) {
		return;
	}

	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) {
		return;
	}

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'wpregister' );

		$error_messages = la_sentinelle_get_default_error_messages();
		edd_set_error( 'likely_spammer', $error_messages['try_again'] );

		if ( isset( $_POST['edd_ajax']) && $_POST['edd_ajax'] === 'true' ) {
			do_action( 'edd_ajax_checkout_errors' );
			edd_die();
		} else {
			// Do a redirect and do not process any further. EDD will log the user in otherwise. The error gets shown after the redirect.
			$redirect = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			wp_safe_redirect( $redirect );
			exit;
		}
	}

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {

	// EDD shortcode [edd_register]
	add_action( 'edd_pre_process_register_form', 'la_sentinelle_check_registration_form_edd' );
	// EDD Checkout with included register form.
	// Breaks unexpectedly.
	//add_action( 'edd_checkout_error_checks', 'la_sentinelle_check_registration_form_edd' );

}
