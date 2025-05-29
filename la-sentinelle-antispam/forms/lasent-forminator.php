<?php


/*
 * Add fields to forminator form.
 * This is with normal non-ajax load of the form.
 *
 * Do not use a second parameter $buuton, as it will crash the Paypal button renderer.
 * Reference: https://wordpress.org/support/topic/forminator-form-does-not-work-with-paypal-field-because-of-la-sentinelle/
 *
 * @param $html the rendered html.
 *
 * @since 3.1.0
 *
 */
function lasent_forminator_render_button_markup( $html ) {

	$elements = la_sentinelle_get_spamfilters();

	return str_replace( '<button ', $elements . '<button ', $html );

}
if (get_option( 'la_sentinelle-forminator', 'true') === 'true') {
	add_action( 'forminator_render_button_markup', 'lasent_forminator_render_button_markup', 10, 1 );
}


/*
 * Verify fields in forminator form.
 *
 * @since 3.1.0
 *
 */
function lasent_forminator_cform_form_is_submittable( $can_show, $id, $form_settings ) {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'forminator' );
		la_sentinelle_save_spam_submission( 'forminator', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$message = $error_messages['try_again'];

		return array(
			'can_submit' => false,
			'error'      => $message,
		);
	}

	return $can_show; // unchanged

}
if (get_option( 'la_sentinelle-forminator', 'true') === 'true') {
	add_filter( 'forminator_cform_form_is_submittable', 'lasent_forminator_cform_form_is_submittable', 10, 3 );
}
