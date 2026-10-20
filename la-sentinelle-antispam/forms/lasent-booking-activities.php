<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add fields to the Booking Activities booking form.
 *
 * The action is fired inside the <form> element, so our fields are part of the
 * FormData that Booking Activities sends to admin-ajax.php.
 * The login-only form (context 'login_form') has its own AJAX action that is not validated, so it gets no fields.
 *
 * @since 4.2.0
 *
 * @uses "bookacti_form_after" action
 *
 * @param array  $form                  data of the form.
 * @param string $instance_id           unique id of this form instance.
 * @param string $context               context in which the form is displayed.
 * @param array  $displayed_form_fields fields that are displayed in the form.
 */
function la_sentinelle_bookacti_form_after( $form, $instance_id = '', $context = 'display', $displayed_form_fields = array() ) {

	if ( $context === 'login_form' ) {
		return;
	}

	echo la_sentinelle_get_spamfilters();

}
if ( get_option( 'la_sentinelle-bookingactivities', 'true' ) === 'true' ) {
	add_action( 'bookacti_form_after', 'la_sentinelle_bookacti_form_after', 10, 4 );
}


/*
 * Validate the Booking Activities booking form.
 *
 * Booking Activities has no filter to validate a submission, and its handler can register users
 * and make bookings, so we check at the earliest priority on the AJAX action, before its own handler at priority 10.
 * On failure we answer in the same JSON format as Booking Activities does, so the form displays our message.
 *
 * @since 4.2.0
 *
 * @uses "wp_ajax_bookactiSubmitBookingForm" and "wp_ajax_nopriv_bookactiSubmitBookingForm" actions.
 */
function la_sentinelle_bookacti_validate_booking_form() {

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'bookingactivities' );
		la_sentinelle_save_spam_submission( 'booking-activities', $markers );

		$error_messages = la_sentinelle_get_default_error_messages();
		$message        = $error_messages['try_again'];

		$response = array(
			'status'   => 'failed',
			'error'    => 'la_sentinelle',
			'messages' => array( 'la_sentinelle' => $message ),
			'message'  => $message,
		);

		if ( function_exists( 'bookacti_send_json' ) ) {
			bookacti_send_json( $response, 'submit_booking_form' ); // Applies the plugin filters and ends the request.
		}
		wp_send_json( $response ); // Ends the request.
	}

}
if ( get_option( 'la_sentinelle-bookingactivities', 'true' ) === 'true' ) {
	add_action( 'wp_ajax_nopriv_bookactiSubmitBookingForm', 'la_sentinelle_bookacti_validate_booking_form', 1 );
	add_action( 'wp_ajax_bookactiSubmitBookingForm', 'la_sentinelle_bookacti_validate_booking_form', 1 );
}
