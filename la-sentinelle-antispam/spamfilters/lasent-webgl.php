<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get webgl field for a form.
 *
 * @return string html with input field.
 *
 * @since 3.0.0
 */
function la_sentinelle_get_webgl() {

	$output = '';

	$field_name = la_sentinelle_get_field_name( 'webgl' );
	$field_name2 = la_sentinelle_get_field_name( 'webgl2' );
	$field_name3 = la_sentinelle_get_field_name( 'webgl3' );
	$field_id = la_sentinelle_get_field_id( $field_name );
	$field_id2 = la_sentinelle_get_field_id( $field_name2 );
	$field_id3 = la_sentinelle_get_field_id( $field_name3 );
	$honeypot_value = (int) get_option( 'la_sentinelle-honeypot_value', 15 );
	$webgl3_value = sanitize_text_field( base64_encode( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ) );
	$output .= '
		<input value="' . (int) $honeypot_value . '" type="text" name="' . esc_attr( $field_name ) . '" class="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" placeholder="" style="transform: translateY(10000px);" />
		<input value="" type="text" name="' . esc_attr( $field_name2 ) . '" class="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_id2 ) . '" placeholder="" style="transform: translateY(10000px);" />
		<input value="' . esc_attr( $webgl3_value ) . '" type="text" name="' . esc_attr( $field_name3 ) . '" class="' . esc_attr( $field_name3 ) . '" id="' . esc_attr( $field_id3 ) . '" placeholder="" style="transform: translateY(10000px);" />
	';

	return $output;

}


/*
 * Check webgl field for a form.
 *
 * @return string result 'spam' if it is considered spam.
 *
 * @since 3.0.0
 */
function la_sentinelle_check_webgl() {

	$post_data = $_POST;
	$marked_by_webgl = false;
	$verified = false;

	if ( get_option( 'la_sentinelle-webgl', 'false') === 'true' ) {
		if ( is_array( $post_data ) && ! empty( $post_data ) ) {
			$field_name2 = la_sentinelle_get_field_name( 'webgl2' );
			$field_name3 = la_sentinelle_get_field_name( 'webgl3' );
			if ( isset( $post_data["$field_name2"] ) && isset( $post_data["$field_name3"] ) ) {
				$webgl2 = (int) $post_data["$field_name2"];
				$webgl3 = esc_attr( sanitize_text_field( $post_data["$field_name3"] ) ); // should both be exactly the same if correctly base64 encoded.

				$transient = get_transient( 'la_sentinelle_webgl_' . (string) $webgl3 );
				if ( $transient == $webgl2 ) {
					$verified = true;
				}
			}
		}
		if ( $verified === false ) {
			// Webgl2 doesn't match transient, so considered spam.
			$marked_by_webgl = true;
		}
	}

	if ( $marked_by_webgl ) {
		$spamfilter_scores = la_sentinelle_check_scores( 'webgl' );
		return 'spam';
	}

	return '';

}


/*
 * Callback function for handling the Ajax request that is generated from the JavaScript above and in la-sentinelle-frontend.js.
 * Also for non-logged-in users.
 *
 * @since 3.0.0
 */
add_action( 'wp_ajax_la_sentinelle_webgl', 'wp_ajax_la_sentinelle_webgl' );
add_action( 'wp_ajax_nopriv_la_sentinelle_webgl', 'wp_ajax_la_sentinelle_webgl' );
function wp_ajax_la_sentinelle_webgl() {

	if ( isset($_POST['webgl2']) && isset($_POST['webgl3']) ) {
		$webgl2 = (int) $_POST['webgl2'];
		$webgl3 = esc_attr( sanitize_text_field( $_POST['webgl3'] ) ); // should both be exactly the same if correctly base64 encoded.

		set_transient( 'la_sentinelle_webgl_' . (string) $webgl3, (int) $webgl2, DAY_IN_SECONDS );

		echo 'reported';
		die();
	}

	echo 'error, not the right data';
	die();

}
