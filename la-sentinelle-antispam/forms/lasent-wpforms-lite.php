<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add field in WPForms Lite
 *
 * @since 2.1.0
 *
 * @uses "wpforms_display_submit_before" action
 *
 * @param array $form_data array with data for the form.
 *
 */
function la_sentinelle_wpforms_display_submit_before( $form_data ) {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-wpforms', 'true') === 'true') {
	add_action( 'wpforms_display_submit_before', 'la_sentinelle_wpforms_display_submit_before', 10, 1 );
}


/*
 * Validate form in WPForms Lite
 *
 * @since 2.1.0
 *
 * @uses "wpforms_process_initial_errors" filter and uses the 'footer' data field for messaging an error.
 *
 * @param array $errors
 * @param array $form_data
 *
 * @return array $errors
 */
function la_sentinelle_wpforms_process_initial_errors( $errors, $form_data ) {

	if ( isset( $form_data['id'] ) ) {
		$form_id = absint( $form_data['id'] );
	} else {
		return $errors;
	}

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'wpforms' );
		la_sentinelle_save_spam_submission( 'wpforms', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$lasent_errors = $error_messages['try_again'];

		if ( ! isset( $errors[ $form_id ] ) ) {
			$errors[ $form_id ] = array();
		}
		$errors[ $form_id ]['footer'] = $lasent_errors;
	}

	return $errors;

}
if (get_option( 'la_sentinelle-wpforms', 'true') === 'true') {
	add_filter( 'wpforms_process_initial_errors', 'la_sentinelle_wpforms_process_initial_errors', 10, 2 );
}
