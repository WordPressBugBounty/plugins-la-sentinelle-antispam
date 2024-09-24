<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Save settings.
 *
 * @return string messages for the user as feedback.
 *
 * @since 1.0.0
 */
function la_sentinelle_settingspage_formupdate() {

	if ( ! current_user_can('manage_options') ) {
		return esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam');
	}

	$saved = false;
	$messages = '';
	//if ( WP_DEBUG ) { echo "_POST: "; var_dump($_POST); }

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'la_sentinelle_options' ) {
		if ( isset( $_POST['la_sentinelle_tab'] ) ) {
			$active_tab = $_POST['la_sentinelle_tab'];

			switch ( $active_tab ) {
				case 'la_sentinelle_settingstab_spamfilters':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['la_sentinelle_settingstab_spamfilters']) ) {
						$verified = wp_verify_nonce( $_POST['la_sentinelle_settingstab_spamfilters'], 'la_sentinelle_settingstab_spamfilters' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						$messages .= '<p>' . esc_html__('Nonce check failed. Please try again.', 'la-sentinelle-antispam') . '</p>';
						break;
					}

					if (isset($_POST['la_sentinelle-honeypot']) && $_POST['la_sentinelle-honeypot'] === 'on') {
						update_option('la_sentinelle-honeypot', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-honeypot', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-nonce']) && $_POST['la_sentinelle-nonce'] === 'on') {
						update_option('la_sentinelle-nonce', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-nonce', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-timeout']) && $_POST['la_sentinelle-timeout'] === 'on') {
						update_option('la_sentinelle-timeout', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-timeout', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-sfs']) && $_POST['la_sentinelle-sfs'] === 'on') {
						update_option('la_sentinelle-sfs', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-sfs', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-ajax']) && $_POST['la_sentinelle-ajax'] === 'on') {
						update_option('la_sentinelle-ajax', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-ajax', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-webgl']) && $_POST['la_sentinelle-webgl'] === 'on') {
						update_option('la_sentinelle-webgl', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-webgl', 'false');
						$saved = true;
					}

					break;

				case 'la_sentinelle_settingstab_forms':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['la_sentinelle_settingstab_forms']) ) {
						$verified = wp_verify_nonce( $_POST['la_sentinelle_settingstab_forms'], 'la_sentinelle_settingstab_forms' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						$messages .= '<p>' . esc_html__('Nonce check failed. Please try again.', 'la-sentinelle-antispam') . '</p>';
						break;
					}

					if (isset($_POST['la_sentinelle-wpcomment']) && $_POST['la_sentinelle-wpcomment'] === 'on') {
						update_option('la_sentinelle-wpcomment', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wpcomment', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-wplogin']) && $_POST['la_sentinelle-wplogin'] === 'on') {
						update_option('la_sentinelle-wplogin', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wplogin', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-wppassword']) && $_POST['la_sentinelle-wppassword'] === 'on') {
						update_option('la_sentinelle-wppassword', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wppassword', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-wpregister']) && $_POST['la_sentinelle-wpregister'] === 'on') {
						update_option('la_sentinelle-wpregister', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wpregister', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-everest']) && $_POST['la_sentinelle-everest'] === 'on') {
						update_option('la_sentinelle-everest', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-everest', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-formidable']) && $_POST['la_sentinelle-formidable'] === 'on') {
						update_option('la_sentinelle-formidable', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-formidable', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-forminator']) && $_POST['la_sentinelle-forminator'] === 'on') {
						update_option('la_sentinelle-forminator', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-forminator', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-noptin']) && $_POST['la_sentinelle-noptin'] === 'on') {
						update_option('la_sentinelle-noptin', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-noptin', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-woo-registration']) && $_POST['la_sentinelle-woo-registration'] === 'on') {
						update_option('la_sentinelle-woo-registration', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-woo-registration', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-wpforms']) && $_POST['la_sentinelle-wpforms'] === 'on') {
						update_option('la_sentinelle-wpforms', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wpforms', 'false');
						$saved = true;
					}

					if (isset($_POST['la_sentinelle-wpjobmanager']) && $_POST['la_sentinelle-wpjobmanager'] === 'on') {
						update_option('la_sentinelle-wpjobmanager', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-wpjobmanager', 'false');
						$saved = true;
					}

					$active_tab = 'la_sentinelle_settingstab_forms';

					break;

				case 'la_sentinelle_settingstab_misc':
					/* Check Nonce */
					$verified = false;
					if ( isset($_POST['la_sentinelle_settingstab_misc']) ) {
						$verified = wp_verify_nonce( $_POST['la_sentinelle_settingstab_misc'], 'la_sentinelle_settingstab_misc' );
					}
					if ( $verified === false ) {
						// Nonce is invalid.
						$messages .= '<p>' . esc_html__('Nonce check failed. Please try again.', 'la-sentinelle-antispam') . '</p>';
						break;
					}

					if (isset($_POST['la_sentinelle-save_comments']) && $_POST['la_sentinelle-save_comments'] === 'on') {
						update_option('la_sentinelle-save_comments', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-save_comments', 'false');
						$saved = true;
					}
					if (isset($_POST['la_sentinelle-remove_comments']) && $_POST['la_sentinelle-remove_comments'] === 'on') {
						update_option('la_sentinelle-remove_comments', 'true');
						$saved = true;
					} else {
						update_option('la_sentinelle-remove_comments', 'false');
						$saved = true;
					}

					$active_tab = 'la_sentinelle_settingstab_misc';

					break;

				default:
					/* Just load the first tab */
					$active_tab = 'la_sentinelle_settingstab_spamfilters';

					break;

			}
		}
	}

	if ( $saved ) {
		$messages .= '<p>' . esc_html__('Changes saved.', 'la-sentinelle-antispam') . '</p>';
	}

	return $messages;

}
