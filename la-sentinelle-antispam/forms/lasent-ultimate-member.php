<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Echo form fields for a form.
 * Used for all UM forms.
 * https://wordpress.org/plugins/ultimate-member/
 *
 * @param  string be able to use different actions for the nonce.
 * @return void, this function only echoes.
 *
 * @since 3.0.2
 */
function la_sentinelle_get_spamfilters_ultimate_member( $nonce_action = 'default' ) {

	echo la_sentinelle_get_spamfilters( $nonce_action );

}
add_action( 'um_after_form_fields', 'la_sentinelle_get_spamfilters_ultimate_member', 10, 0 );


/*
 * Validate user password field on registration.
 * https://wordpress.org/plugins/ultimate-member/
 *
 * @param array $submitted_data
 *
 * @since 3.0.3
 */
function la_sentinelle_um_submit_form_errors_hook__registration( $submitted_data ) {

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		$error_messages = la_sentinelle_get_default_error_messages();
		la_sentinelle_add_statistic_blocked( 'wpregister' );
		// Use visible form field, otherwise the error is not shown.
		UM()->form()->add_error( 'user_login', $error_messages['registration'] );
	}

}
if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
	add_action( 'um_submit_form_errors_hook__registration', 'la_sentinelle_um_submit_form_errors_hook__registration' );
}


/*
 * Validate user password field on password reset.
 * https://wordpress.org/plugins/ultimate-member/
 *
 * @param array $submitted_data
 * @param array $form_data
 *
 * @since 3.0.3
 */
function la_sentinelle_um_reset_password_errors_hook( $submitted_data, $form_data ) {

	$marker = la_sentinelle_check_spamfilters();
	if ( $marker === 'spam' ) {
		$error_messages = la_sentinelle_get_default_error_messages();
		la_sentinelle_add_statistic_blocked( 'wppassword' );
		// Use visible form field, otherwise the error is not shown.
		UM()->form()->add_error( 'username_b', $error_messages['try_again'] );
	}

}
if (get_option( 'la_sentinelle-wppassword', 'false') === 'true') {
	add_action( 'um_reset_password_errors_hook', 'la_sentinelle_um_reset_password_errors_hook', 10, 2 );
}
