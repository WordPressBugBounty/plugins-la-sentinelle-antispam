<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add field in Formidable
 *
 * @since 1.5.0
 *
 * @uses "frm_entry_form" action
 *
 * @param array $form html with the form.
 *
 * @return string html with the input fields.
 */
function la_sentinelle_frm_entry_form( $form, $action = '', $errors = '' ) {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-formidable', 'true') === 'true') {
	add_action( 'frm_entry_form', 'la_sentinelle_frm_entry_form', 10, 3 );
}


/*
 * Validate form in Formidable
 *
 * @since 1.5.0
 *
 * @uses "frm_validate_entry" filter
 *
 * @param array $errors
 *
 * @return array $errors
 */
function la_sentinelle_frm_validate_entry( $errors, $values = '' ) {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'formidable' );
		la_sentinelle_save_spam_submission( 'formidable', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$errors['la_sentinelle'] = $error_messages['try_again'];
	}

	return $errors;

}
if (get_option( 'la_sentinelle-formidable', 'true') === 'true') {
	add_filter('frm_validate_entry', 'la_sentinelle_frm_validate_entry', 8, 2);
}
