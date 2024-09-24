<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get form timeout fields for a form.
 *
 * @return string html with input fields.
 *
 * @since 1.0.0
 */
function la_sentinelle_get_timeout() {

	$output = '';

	if ( get_option( 'la_sentinelle-timeout', 'true') === 'true' ) {
		$field_name = la_sentinelle_get_field_name( 'timeout' );
		$field_name2 = la_sentinelle_get_field_name( 'timeout2' );
		$field_id = la_sentinelle_get_field_id( $field_name );
		$field_id2 = la_sentinelle_get_field_id( $field_name2 );
		$random = rand( 100, 100000 );
		$output .= '
			<input value="' . esc_attr( $random ) . '" type="text" name="' . esc_attr( $field_name ) . '" class="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" placeholder="" style="transform: translateY(10000px);" />
			<input value="' . esc_attr( $random ) . '" type="text" name="' . esc_attr( $field_name2 ) . '" class="' . esc_attr( $field_name2 ) . '" id="' . esc_attr( $field_id2 ) . '" placeholder="" style="transform: translateY(10000px);" />
			';
	}

	return $output;

}


/*
 * Check timeout fields for a form.
 *
 * @return string result 'spam' if it is considered spam.
 *
 * @since 1.0.0
 */
function la_sentinelle_check_timeout() {

	$post_data = $_POST;
	$marked_by_timeout = false;

	if (get_option( 'la_sentinelle-timeout', 'true') === 'true') {
		if ( is_array( $post_data ) && ! empty( $post_data ) ) {
			$field_name = la_sentinelle_get_field_name( 'timeout' );
			$field_name2 = la_sentinelle_get_field_name( 'timeout2' );
			if ( isset($post_data["$field_name"]) && strlen($post_data["$field_name"]) > 0 && isset($post_data["$field_name2"]) && strlen($post_data["$field_name2"]) > 0 ) {
				// Input fields were filled in, so continue.
				$timeout  = (int) $post_data["$field_name"];
				$timeout2 = (int) $post_data["$field_name2"];
				if ( ( $timeout2 - $timeout ) < 2 ) {
					// Submitted less then 1 seconds after loading. Considered spam.
					$marked_by_timeout = true;
				}
			} else {
				// Input fields were not filled in correctly. Considered spam.
				$marked_by_timeout = true;
			}
		}
	}

	if ( $marked_by_timeout ) {
		la_sentinelle_check_scores( 'timeout' );
		return 'spam';
	}

	return '';

}
