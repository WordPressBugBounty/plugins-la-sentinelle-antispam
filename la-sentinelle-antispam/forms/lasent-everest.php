<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add field in Everest Forms
 *
 * @since 1.6.0
 *
 * @uses "everest_forms_frontend_output" action. This has 4 parameters available, we only need 1.
 *
 * @param array $form html with the form data and settings.
 *
 */
function la_sentinelle_everest_forms_frontend_output( $form_data ) {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-everest', 'true') === 'true') {
	add_action( 'everest_forms_frontend_output', 'la_sentinelle_everest_forms_frontend_output', 10, 1 );
}


/*
 * Validate form in Everest Forms
 *
 * @since 1.6.0
 *
 * @uses "everest_forms_process_before_filter" action
 *
 * @param array $entry Data from $_POST with only fields from Everest forms. We use raw $_POST data.
 * @param array $form_data To be formatted data for the form.
 *
 * @return array $entry Unchanged data.
 */
function la_sentinelle_everest_forms_process_before_filter( $entry, $form_data ) {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'everest' );
		la_sentinelle_save_spam_submission( 'everest-forms', $markers );
		add_filter( 'everest_forms_process_initial_errors', 'la_sentinelle_everest_forms_process_initial_errors', 10, 2 );
	}

	return $entry;

}
if (get_option( 'la_sentinelle-everest', 'true') === 'true') {
	add_filter('everest_forms_process_before_filter', 'la_sentinelle_everest_forms_process_before_filter', 10, 2 );
}


/*
 * Set error message when validation fails in Everest Forms.
 * Only gets called when 'la_sentinelle_everest_forms_process_before_filter()' finds it spam.
 *
 * @since 1.6.0
 *
 * @uses "everest_forms_process_initial_errors" filter
 *
 * @param array $errors
 * @param array $form_data To be formatted data for the form.
 *
 * @return array $errors With an error that should fail inside Everest Forms, returns the form and displays this error message.
 */
function la_sentinelle_everest_forms_process_initial_errors( $errors, $form_data ) {

	$form_id = (int) $form_data['id'];
	$error_messages = la_sentinelle_get_default_error_messages();

	$errors["$form_id"]['header'] = $error_messages['try_again'];

	return $errors;

}
