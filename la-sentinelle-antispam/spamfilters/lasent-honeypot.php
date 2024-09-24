<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get honeypot fields for a form.
 *
 * @param  bool   first field or second field.
 * @return string html with input fields.
 *
 * @since 1.0.0
 */
function la_sentinelle_get_honeypot( $first_field ) {

	$output = '';

	if (  $first_field ) {
		$field_name = la_sentinelle_get_field_name( 'honeypot' );
		$field_id = la_sentinelle_get_field_id( $field_name );
		$honeypot_value = (int) get_option( 'la_sentinelle-honeypot_value', 15 );
		$output .= '
		<input value="' . (int) $honeypot_value . '" type="text" name="' . esc_attr( $field_name ) . '" class="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" placeholder="" style="transform: translateY(10000px);" />
		';
	} else {
		$field_name2 = la_sentinelle_get_field_name( 'honeypot2' );
		$field_id2 = la_sentinelle_get_field_id( $field_name2 );
		$output .= '
		<input value="" type="text" name="' . esc_attr( $field_name2 ) . '" class="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_id2 ) . '" placeholder="" style="transform: translateY(10000px);" />
		';
	}

	return $output;

}


/*
 * Check honeypot fields for a form.
 *
 * @return string result 'spam' if it is considered spam.
 *
 * @since 1.0.0
 */
function la_sentinelle_check_honeypot() {

	$post_data = $_POST;
	$marked_by_honeypot = false;

	if (get_option( 'la_sentinelle-honeypot', 'true') === 'true') {
		if ( is_array( $post_data ) && ! empty( $post_data ) ) {
			$field_name = la_sentinelle_get_field_name( 'honeypot' );
			$field_name2 = la_sentinelle_get_field_name( 'honeypot2' );
			$honeypot_value = (int) get_option( 'la_sentinelle-honeypot_value', 15 );
			if ( ! isset($post_data["$field_name"]) ) {
				// Input field was not in form submit, so considered spam.
				$marked_by_honeypot = true;
			}
			if ( isset($post_data["$field_name"]) && strlen($post_data["$field_name"]) > 0 ) {
				// Input field was filled in, so considered spam.
				$marked_by_honeypot = true;
			}
			if ( ! isset($post_data["$field_name2"]) || (int) $post_data["$field_name2"] !== $honeypot_value ) {
				// Input field was not filled in correctly, so considered spam.
				$marked_by_honeypot = true;
			}
		}
	}

	if ( $marked_by_honeypot ) {
		la_sentinelle_check_scores( 'honeypot' );
		return 'spam';
	}

	return '';

}
