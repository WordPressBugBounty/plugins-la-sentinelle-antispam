<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get nonce field for a form.
 *
 * @param  string be able to use different actions for the nonce.
 * @return string html with input field.
 *
 * @since 1.0.0
 */
function la_sentinelle_get_nonce( $nonce_action = 'default' ) {

	$output = '';

	$field_name = la_sentinelle_get_field_name( 'nonce' );
	$field_id = la_sentinelle_get_field_id( $field_name );
	$nonce_action = 'la_sentinelle_nonce_' . $nonce_action;
	$nonce = lasent_create_nonce( $nonce_action );
	$output .= '<input type="text" class="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" name="' . esc_attr( $field_name ) . '" value="' . esc_attr( $nonce ) . '" style="transform: translateY(10000px);" />
		';

	return $output;

}


/*
 * Check nonce field for a form.
 *
 * @return string result 'spam' if it is considered spam.
 *
 * @since 1.0.0
 */
function la_sentinelle_check_nonce( $nonce_action = 'default' ) {

	$post_data = $_POST;
	$marked_by_nonce = false;

	if (get_option( 'la_sentinelle-nonce', 'false') === 'true') {
		$verified = false;
		if ( is_array( $post_data ) && ! empty( $post_data ) ) {
			$field_name = la_sentinelle_get_field_name( 'nonce' );
			if ( isset( $post_data["$field_name"] ) ) {
				$nonce = $post_data["$field_name"];
				$nonce_action = 'la_sentinelle_nonce_' . $nonce_action;
				$verified = lasent_verify_nonce( $nonce, $nonce_action );
			}
		}
		if ( $verified === false ) {
			// Nonce is invalid or non-existant, so considered spam.
			$marked_by_nonce = true;
		}
	}

	if ( $marked_by_nonce ) {
		la_sentinelle_check_scores( 'nonce' );
		return 'spam';
	}

	return '';

}


/*
 * Creates a cryptographic token tied to a specific action, user, user session,
 * and window of time.
 * Use our own function to create and verify nonces, since Contact Form 7 doesn't do the verification with a logged in user, which always fails.
 *
 * @since 1.0.0 (taken from WP 4.9.7 without $uid)
 *
 * @param string|int $action Scalar value to add context to the nonce.
 * @return string The token.
 *
 */
function lasent_create_nonce( $action = -1 ) {

	$token = wp_get_session_token();
	$i = wp_nonce_tick();

	return substr( wp_hash( $i . '|' . $action . '|' . $token, 'nonce' ), -12, 10 );

}


/*
 * Verify that correct nonce was used with time limit.
 *
 * The user is given an amount of time to use the token, so therefore, since the
 * UID and $action remain the same, the independent variable is the time.
 *
 * @since 1.0.0 (taken from WP 4.9.7 without $uid)
 *
 * @param string     $nonce  Nonce that was used in the form to verify
 * @param string|int $action Should give context to what is taking place and be the same when nonce was created.
 * @return false|int False if the nonce is invalid, 1 if the nonce is valid and generated between
 *                   0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
 */
function lasent_verify_nonce( $nonce, $action = -1 ) {

	$nonce = (string) $nonce;
	$nonce = trim( $nonce );

	if ( strlen( $nonce ) === 0 ) {
		return false;
	}

	$token = wp_get_session_token();
	$i = wp_nonce_tick();

	// Nonce generated 0-12 hours ago
	$expected = substr( wp_hash( $i . '|' . $action . '|' . $token, 'nonce'), -12, 10 );
	if ( hash_equals( $expected, $nonce ) ) {
		return 1;
	}

	// Nonce generated 12-24 hours ago
	$expected = substr( wp_hash( ( $i - 1 ) . '|' . $action . '|' . $token, 'nonce' ), -12, 10 );
	if ( hash_equals( $expected, $nonce ) ) {
		return 2;
	}

	// Invalid nonce
	return false;

}
