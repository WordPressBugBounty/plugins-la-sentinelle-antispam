<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Register Settings.
 *
 * @since 1.0.0
 */
function la_sentinelle_register_settings() {
	register_setting( 'la_sentinelle_options', 'la_sentinelle-ajax',                 'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-caldera_blocked',      'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-cf7_blocked',          'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-everest',              'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-everest_blocked',      'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-experiment',           'strval' ); // serialized string
	register_setting( 'la_sentinelle_options', 'la_sentinelle-formidable',           'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-formidable_blocked',   'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-forminator',           'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-forminator_blocked',   'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-honeypot',             'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-honeypot_value',       'intval' ); // random 1 - 100
	register_setting( 'la_sentinelle_options', 'la_sentinelle-nonce',                'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-noptin',               'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-noptin_blocked',       'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-remove_comments',      'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-save_comments',        'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-sfs',                  'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-timeout',              'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-version',              'strval' ); // string
	register_setting( 'la_sentinelle_options', 'la_sentinelle-webgl',                'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-woo-registration',     'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpcomment',            'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpcomments_blocked',   'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpforms',              'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpforms_blocked',      'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpjobmanager',         'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpjobmanager_blocked', 'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wplogin',              'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wplogin_blocked',      'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wppassword',           'strval' ); // 'false'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wppassword_blocked',   'intval' ); // int
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpregister',           'strval' ); // 'true'
	register_setting( 'la_sentinelle_options', 'la_sentinelle-wpregister_blocked',   'intval' ); // int
}
add_action( 'admin_init', 'la_sentinelle_register_settings' );


/*
 * Set default options.
 * Idea is to have all options in the database and thus cached, so we hit an empty cache less often.
 *
 * @since 1.0.0
 */
function la_sentinelle_set_defaults() {

	// Obsolete options.
	delete_option( 'la_sentinelle-edd-disable-ajax' ); // obsolete since 2.4.3

	// Setting defaults to avoid empty cache and multiple queries.
	if ( get_option('la_sentinelle-ajax', false) === false ) {
		update_option( 'la_sentinelle-ajax', 'false' );
	}
	if ( get_option('la_sentinelle-everest', false) === false ) {
		update_option( 'la_sentinelle-everest', 'true' );
	}
	if ( get_option('la_sentinelle-formidable', false) === false ) {
		update_option( 'la_sentinelle-formidable', 'true' );
	}
	if ( get_option('la_sentinelle-forminator', false) === false ) {
		update_option( 'la_sentinelle-forminator', 'true' );
	}
	if ( get_option('la_sentinelle-honeypot', false) === false ) {
		update_option( 'la_sentinelle-honeypot', 'true' );
	}
	if ( get_option('la_sentinelle-honeypot_value', false) === false ) {
		$random = rand( 1, 99 );
		update_option( 'la_sentinelle-honeypot_value', $random );
	}
	if ( get_option('la_sentinelle-nonce', false) === false ) {
		update_option( 'la_sentinelle-nonce', 'false' );
	}
	if ( get_option('la_sentinelle-noptin', false) === false ) {
		update_option( 'la_sentinelle-noptin', 'true' );
	}
	if ( get_option('la_sentinelle-remove_comments', false) === false ) {
		update_option( 'la_sentinelle-remove_comments', 'false' );
	}
	if ( get_option('la_sentinelle-save_comments', false) === false ) {
		update_option( 'la_sentinelle-save_comments', 'true' );
	}
	if ( get_option('la_sentinelle-sfs', false) === false ) {
		update_option( 'la_sentinelle-sfs', 'false' );
	}
	if ( get_option('la_sentinelle-timeout', false) === false ) {
		update_option( 'la_sentinelle-timeout', 'true' );
	}
	if ( get_option('la_sentinelle-webgl', false) === false ) {
		update_option( 'la_sentinelle-webgl', 'false' );
	}
	if ( get_option('la_sentinelle-woo-registration', false) === false ) {
		update_option( 'la_sentinelle-woo-registration', 'false' );
	}
	if ( get_option('la_sentinelle-wpcomment', false) === false ) {
		update_option( 'la_sentinelle-wpcomment', 'true' );
	}
	if ( get_option('la_sentinelle-wpforms', false) === false ) {
		update_option( 'la_sentinelle-wpforms', 'true' );
	}
	if ( get_option('la_sentinelle-wpjobmanager', false) === false ) {
		update_option( 'la_sentinelle-wpjobmanager', 'true' );
	}
	if ( get_option('la_sentinelle-wplogin', false) === false ) {
		update_option( 'la_sentinelle-wplogin', 'true' );
	}
	if ( get_option('la_sentinelle-wppassword', false) === false ) {
		update_option( 'la_sentinelle-wppassword', 'false' );
	}
	if ( get_option('la_sentinelle-wpregister', false) === false ) {
		update_option( 'la_sentinelle-wpregister', 'true' );
	}

	update_option('la_sentinelle-version', LASENT_VER);

}


/*
 * Get default error messages.
 *
 * @return array list of error messages in key / value, key=spamfilter, value=errormessage.
 * @since 3.0.0
 */
function la_sentinelle_get_default_error_messages() {

	$error_messages = array(
		'try_again'         => esc_html__( 'Your submission was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ),
		'go_back_try_again' => esc_html__( 'Your submission was marked as spam, please go back and try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ),
		'registration'      => esc_html__( 'Your registration was marked as spam, please try again or contact a site administrator for assistance.', 'la-sentinelle-antispam' ),
		);

	return $error_messages;

}
