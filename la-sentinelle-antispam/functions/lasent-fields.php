<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Use a custom field name for the form fields that are different for each website.
 *
 * @param string field name of the requested field.
 * @return string hashed fieldname or fieldname, prepended with la_sentinelle.
 *
 * @since 1.0.0
 */
function la_sentinelle_get_field_name( $field ) {

	$blog_url = get_option( 'siteurl' );
	// $blog_url = get_bloginfo('wpurl'); // Will be different depending on scheme (http/https).

	$key = 'la_sentinelle_' . $field . '_field_name_' . $blog_url;
	$field_name = wp_hash( $key, 'auth' );
	$field_name = 'la_sentinelle_' . $field_name;

	return $field_name;

}


/*
 * Use a custom and unique field id based on field name plus a counter for the form fields.
 * Forminator needs an id attribute on text input fields.
 *
 * @param string field name of the requested field.
 * @return string fieldname appended with a counter.
 *
 * @uses static $ids array with counters for each field.
 *
 * @since 3.1.0
 */
function la_sentinelle_get_field_id( $field_name ) {

	static $ids;

	if ( ! is_array( $ids ) ) {
		$ids = array();
	}

	if ( ! isset( $ids["$field_name"] )  ) {
		$ids["$field_name"] = 0;
	}
	$ids["$field_name"]++;

	$field_id = $field_name . '-' . $ids["$field_name"];

	return $field_id;

}
