<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Form handler; output the Sentinelle fields.
 *
 * @since 2.2.0
 */
function la_sentinelle_submit_job_form_end() {

	echo la_sentinelle_get_spamfilters( 'wpjobmanager' );

}
add_action( 'submit_job_form_end', 'la_sentinelle_submit_job_form_end' );


/*
 * Form handler; validate the Sentinelle fields.
 *
 * @param bool $success
 * @return bool|WP_Error
 *
 * @since 2.2.0
 */
function la_sentinelle_submit_draft_job_form_validate_fields( $success ) {

	if ( is_user_logged_in() ) {
		// We only guard registration forms in wpjobmanager.
		return $success;
	}

	$account_required = job_manager_user_requires_account(); // account required or anonymous submit.
	$registration_enabled = job_manager_enable_registration(); // user can register at submit form.

	// Check for spam only if these options are set this way.
	// Only non-logged-in users get here.
	if ( ! $account_required || $registration_enabled ) {

		la_sentinelle_check_spamfilters( 'wpjobmanager' );

		$markers = la_sentinelle_check_scores();
		if ( is_array( $markers ) && ! empty( $markers ) ) {
			la_sentinelle_add_statistic_blocked( 'wpjobmanager' );
			la_sentinelle_save_spam_submission( 'wpjobmanager', $markers );

			$error_messages = la_sentinelle_get_default_error_messages();

			return new WP_Error( 'validation-error', $error_messages['try_again'] );
		}
	}

	return $success;

}
if (get_option( 'la_sentinelle-wpjobmanager', 'true') === 'true') {
	add_filter( 'submit_draft_job_form_validate_fields', 'la_sentinelle_submit_draft_job_form_validate_fields' );
	add_filter( 'submit_job_form_validate_fields', 'la_sentinelle_submit_draft_job_form_validate_fields' );
}
