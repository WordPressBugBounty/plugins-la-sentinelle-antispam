<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/*
 * Add field in Newsletter Optin Box to the shortcode form, popup form, sliding form, legacy widget and new widget.
 * do_action( 'after_print_noptin_form_fields', $singleLine, $id );
 *
 * @since 2.0.0
 *
 * @uses "after_print_noptin_form_fields" action
 * @uses "before_noptin_quick_widget_submit" action
 *
 * @return string html with the input fields.
 *
 */
function la_sentinelle_after_print_noptin_form_fields() {

	echo la_sentinelle_get_spamfilters();

}
function la_sentinelle_after_print_noptin_form_fields_() {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-noptin', 'true') === 'true') {
	add_action( 'after_print_noptin_form_fields', 'la_sentinelle_after_print_noptin_form_fields', 10, 0 ); // shortcode, popup, slide_in
	add_action( 'before_noptin_quick_widget_submit', 'la_sentinelle_after_print_noptin_form_fields', 10, 0 ); // legacy widget
	add_action( 'before_output_noptin_form_submit_button', 'la_sentinelle_after_print_noptin_form_fields' ); // new widget
}

/*
 * Validate shortcode form in Newsletter Optin Box
 *
 * @since 2.0.0
 *
 * @uses "noptin_before_add_ajax_subscriber" action and "noptin_new_subscriber" ajax action, used by shortcode, popup, sliding, legacy widget.
 *
 */
function la_sentinelle_noptin_before_add_ajax_subscriber() {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'noptin' );
		la_sentinelle_save_spam_submission( 'newsletter-optin-box', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$errors = $error_messages['try_again'];

		wp_die( $errors );
	}

}
if (get_option( 'la_sentinelle-noptin', 'true') === 'true') {
	add_action( 'noptin_before_add_ajax_subscriber', 'la_sentinelle_noptin_before_add_ajax_subscriber' );
}


/*
 * Validate form widget in Newsletter Optin Box.
 *
 * @since 2.3.0
 *
 * @uses "noptin_form_errors" action, used by New Widget.
 *
 */
function la_sentinelle_noptin_form_errors( $Noptin_Form_Listener ) {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'noptin' );
		la_sentinelle_save_spam_submission( 'newsletter-optin-box', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$errors = $error_messages['try_again'];

		$Noptin_Form_Listener->error->add( 'spam_la_sentinelle', esc_html( $errors ) ); // action, no return.
	}

}
if (get_option( 'la_sentinelle-noptin', 'true') === 'true') {
	add_action( 'noptin_form_errors', 'la_sentinelle_noptin_form_errors', 10, 1 );
}
