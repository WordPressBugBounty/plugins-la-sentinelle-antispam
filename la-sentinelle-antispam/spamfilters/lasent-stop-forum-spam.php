<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Spam filtering with Stop Forum Spam service for WordPress Comments.
 *
 * @param $comment array Comment data.
 *
 * @return string result 'spam' if it is considered spam, otherwise an empty string.
 *
 * @since 1.7.0
 *
 */
function la_sentinelle_check_stop_forum_spam_wpcomment( $comment ) {

	if (get_option( 'la_sentinelle-sfs', 'false') === 'true') {

		$check = array();

		if ( isset( $comment['comment_author_IP'] ) ) {
			$check['ip'] = $comment['comment_author_IP'];
		}

		if ( isset( $comment['comment_author_email'] ) && is_email( $comment['comment_author_email'] ) ) {
			$check['email'] = $comment['comment_author_email'];
		}

		if ( isset( $comment['comment_author'] ) ) {
			$check['username'] = $comment['comment_author'];
		}

		if ( la_sentinelle_check_stop_forum_spam( $check ) ) {
			la_sentinelle_check_scores( 'sfs' );
			return 'spam';
		}

	}

	return '';

}


/*
 * Spam filtering with Stop Forum Spam service for WordPress Login.
 *
 * @param $comment array login data.
 *
 * @return string result 'spam' if it is considered spam, otherwise an empty string.
 *
 * @since 1.7.0
 *
 */
function la_sentinelle_check_stop_forum_spam_wplogin( $user ) {

	if ( get_option( 'la_sentinelle-sfs', 'false') === 'true' ) {

		$check = array();

		if ( isset( $user['user_login'] ) ) {
			$check['username'] = $user['user_login'];
		}

		if ( la_sentinelle_check_stop_forum_spam( $check ) ) {
			la_sentinelle_check_scores( 'sfs' );
			return 'spam';
		}

	}

	return '';

}


/*
 * Spam filtering with Stop Forum Spam service for WordPress Register.
 *
 * @param string sanitized_user_login.
 * @param string user_email.
 *
 * @return string result 'spam' if it is considered spam, otherwise an empty string.
 *
 * @since 1.7.0
 *
 */
function la_sentinelle_check_stop_forum_spam_wpregister( $sanitized_user_login, $user_email ) {

	if (get_option( 'la_sentinelle-sfs', 'false') === 'true') {

		$check = array();

		if ( isset( $sanitized_user_login ) ) {
			$check['username'] = $sanitized_user_login;
		}

		if ( isset( $user_email ) ) {
			$check['email'] = $user_email;
		}

		if ( la_sentinelle_check_stop_forum_spam( $check ) ) {
			la_sentinelle_check_scores( 'sfs' );
			return 'spam';
		}

	}

	return '';

}


/*
 * Spam filtering with Stop Forum Spam service.
 *
 * @param $args array Data with parameters for ip, email and username.
 *
 * @return bool true or false, spam or not spam.
 *
 * @since 1.7.0
 *
 */
function la_sentinelle_check_stop_forum_spam( $args = array() ) {

	$defaults = array(
		'ip'       => la_sentinelle_get_user_ip(),
		'email'    => '',
		'username' => '',
	);
	$url = 'https://www.stopforumspam.com/api?';
	$args = wp_parse_args( $args, $defaults );
	$args['f'] = 'json';
	$args['confidence'] = true;
	$args = array_filter( $args );

	$query = $url . http_build_query( $args );
	$key = md5( $query );

	$transient = get_transient( 'la_sentinelle_sfs_' . $key );
	if ( false === $transient ) {
		$result = wp_remote_get( $query );
		if ( ! is_wp_error( $result ) ) {

			if ( strlen( $result['body'] ) < 10 || ! $result['response']['code'] === 200 ) {
				return false;
			}

			$data = json_decode( $result['body'] );

			if ( $data ) {
				// it is json. continue
				if ( $data->success !== 1 ) {
					return false;
				}

				if ( isset( $data->ip ) || isset( $data->email ) || isset( $data->username ) ) {
					$blocked = false;

					if ( isset( $data->ip->confidence )       && $data->ip->confidence > 75       ) { $blocked = 'ip'; }
					if ( isset( $data->username->confidence ) && $data->username->confidence > 80 ) { $blocked = 'username'; }
					if ( isset( $data->email->confidence )    && $data->email->confidence > 75    ) { $blocked = 'email'; }

					if ( $blocked ) {
						set_transient( 'la_sentinelle_sfs_' . $key, 'yes', DAY_IN_SECONDS );
						return true;
					} else {
						set_transient( 'la_sentinelle_sfs_' . $key, 'no', DAY_IN_SECONDS );
						return false;
					}
				}
			}
		}
	} else {
		if ( 'yes' === $transient ) {
			return true;
		}
	}

	return false;

}


/*
 * Check IP on login/admin pageload.
 *
 * @since 1.7.0
 *
 */
function plugins_loaded_la_sentinelle_check_stop_forum_spam() {
	if ( la_sentinelle_check_stop_forum_spam() ) {
		wp_die( '<center>Your IP is on a <a href="https://stopforumspam.com">Spam Blacklist</a>.</center>', 'la-sentinelle-antispam' );
	}
}
if ( is_admin() ) {
	add_action( 'plugins_loaded', 'plugins_loaded_la_sentinelle_check_stop_forum_spam' );
}


/*
 * Add example text to the privacy policy.
 *
 * @since 1.7.0
 */
function la_sentinelle_add_privacy_policy_content() {

	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	if (get_option( 'la_sentinelle-sfs', 'false') === 'true') {
		$content = sprintf(
			'<p>' . esc_html__( 'When registering an account, logging in or requesting a password reset, and also when submitting a comment, the metadata may be sent to the Stop Forum Spam service to help spam detection. Their respective privacy policy is at https://www.stopforumspam.com/privacy.', 'la-sentinelle-antispam' ) . '</p>'
		);

		wp_add_privacy_policy_content(
			'La Sentinelle',
			wp_kses_post( wpautop( $content, false ) )
		);
	}

}
add_action( 'admin_init', 'la_sentinelle_add_privacy_policy_content' );
