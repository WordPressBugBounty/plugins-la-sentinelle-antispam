<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get ajax field for a form.
 *
 * @return string html with input field.
 *
 * @since 3.0.0
 */
function la_sentinelle_get_ajax() {

	$output = '';

	$field_name2 = la_sentinelle_get_field_name( 'ajax2' );
	$field_name3 = la_sentinelle_get_field_name( 'ajax3' ); // there is no ajax(1), to keep it similar to webgl field order.
	$field_id2 = la_sentinelle_get_field_id( $field_name2 );
	$field_id3 = la_sentinelle_get_field_id( $field_name3 ); // there is no ajax(1), to keep it similar to webgl field order.
	$honeypot_value = (int) get_option( 'la_sentinelle-honeypot_value', 15 );
	$ajax3_value = sanitize_text_field( base64_encode( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ) );
	$output .= '
		<input value="' . (int) $honeypot_value . '" type="text" name="' . esc_attr( $field_name2 ) . '" class="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_id2 ) . '" placeholder="" style="transform: translateY(10000px);" />
		<input value="' . esc_attr( $ajax3_value ) . '" type="text" name="' . esc_attr( $field_name3 ) . '" class="' . esc_attr( $field_name3 ) . '" id="' . esc_attr( $field_id3 ) . '" placeholder="" style="transform: translateY(10000px);" />
	';

	return $output;

}


/*
 * Check ajax field for a form.
 *
 * @return string result 'spam' if it is considered spam.
 *
 * @since 3.0.0
 */
function la_sentinelle_check_ajax() {

	$post_data = $_POST;
	$marked_by_ajax = false;
	$verified = false;

	if ( get_option( 'la_sentinelle-ajax', 'false') === 'true' ) {
		if ( is_array( $post_data ) && ! empty( $post_data ) ) {
			$field_name2 = la_sentinelle_get_field_name( 'ajax2' );
			$field_name3 = la_sentinelle_get_field_name( 'ajax3' );
			if ( isset( $post_data["$field_name2"] ) && isset( $post_data["$field_name3"] ) ) {
				$ajax2 = (int) $post_data["$field_name2"];
				$ajax3 = esc_attr( sanitize_text_field( $post_data["$field_name3"] ) );

				$transient = get_transient( 'la_sentinelle_ajax_' . (string) $ajax3 );
				if ( $transient == $ajax2 ) {
					$verified = true;
				}
			}
		}
		if ( $verified === false ) {
			// ajax2 doesn't match transient, so considered spam.
			$marked_by_ajax = true;
		}
	}

	if ( $marked_by_ajax ) {
		$spamfilter_scores = la_sentinelle_check_scores( 'ajax' );
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
add_action( 'wp_ajax_la_sentinelle_ajax', 'wp_ajax_la_sentinelle_ajax' );
add_action( 'wp_ajax_nopriv_la_sentinelle_ajax', 'wp_ajax_la_sentinelle_ajax' );
function wp_ajax_la_sentinelle_ajax() {

	if ( isset($_POST['ajax2']) && isset($_POST['ajax3']) ) {
		$ajax2 = (int) $_POST['ajax2'];
		$ajax3 = esc_attr( sanitize_text_field( $_POST['ajax3'] ) ); // should both be exactly the same if correctly base64 encoded.

		set_transient( 'la_sentinelle_ajax_' . (string) $ajax3, (int) $ajax2, DAY_IN_SECONDS );

		echo 'reported';
		die();
	}

	echo 'error, not the right data';
	die();

}
