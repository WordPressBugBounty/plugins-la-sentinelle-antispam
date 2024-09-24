<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Save counter for spamfilter blocked.
 *
 * @param string $form which form was blocked by the spamfilter.
 *
 * @since 1.5.0
 */
function la_sentinelle_add_statistic_blocked( $form ) {

	$supported_forms = array(
		'wpcomments',
		'wplogin',
		'wpregister',
		'wppassword',
		'caldera',
		'cf7',
		'everest',
		'formidable',
		'forminator',
		'noptin',
		'wpforms',
		'wpjobmanager',
	);
	if ( ! in_array( $form, $supported_forms, true ) ) {
		return 0;
	}

	$option = 'la_sentinelle-' . $form . '_blocked';
	$blocked = (int) get_option( $option, 0 );
	$blocked++;
	update_option( $option, $blocked );

}


/*
 * Return counter for form blocked.
 *
 * @param string $form which form was blocked by the spamfilter.
 * @return int counter for the number of forms blocked.
 *
 * @since 1.5.0
 */
function la_sentinelle_get_statistic_blocked( $form ) {

	$supported_forms = array(
		'wpcomments',
		'wplogin',
		'wpregister',
		'wppassword',
		'caldera',
		'cf7',
		'everest',
		'formidable',
		'forminator',
		'noptin',
		'wpforms',
		'wpjobmanager',
	);
	if ( ! in_array( $form, $supported_forms, true ) ) {
		return 0;
	}

	$option = 'la_sentinelle-' . $form . '_blocked';
	$blocked = (int) get_option( $option, 0 );
	return $blocked;

}
