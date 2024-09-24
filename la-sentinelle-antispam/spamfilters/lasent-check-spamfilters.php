<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Check form fields for a form.
 *
 * @param  string be able to use different actions for the nonce.
 * @return string result 'spam' if it is considered spam.
 *
 * @since 1.0.0
 */
function la_sentinelle_check_spamfilters( $nonce_action = 'default' ) {

	la_sentinelle_check_nonce( $nonce_action );
	la_sentinelle_check_honeypot();
	la_sentinelle_check_timeout();
	la_sentinelle_check_ajax();
	la_sentinelle_check_webgl();

	// All filters are checked, now see if a check had a positive score.
	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		return 'spam';
	}

	return '';

}


/*
 * Check form fields for a form.
 *
 * @param  string name with the spamfilter if it was considered spam, otherwise empty string.
 * @return array $spamfilters_scored list of spamfilters that have scored.
 *
 * @uses static array $spamfilters_scored list of spamfilters that have scored.
 *
 * @since 3.0.0
 */
function la_sentinelle_check_scores( $score = '' ) {

	static $spamfilters_scored;

	if ( ! isset( $spamfilters_scored ) || ! is_array( $spamfilters_scored ) ) {
		$spamfilters_scored = array();
	}

	if ( $score ) {
		$spamfilters_scored[] = $score;
	}

	return $spamfilters_scored;

}
