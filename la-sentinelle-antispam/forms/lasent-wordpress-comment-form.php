<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Add spamfilter fields to comment form.
 *
 * @since 1.0.0
 */
function la_sentinelle_comment_form() {

	echo la_sentinelle_get_spamfilters();

}
if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
	add_action( 'comment_form', 'la_sentinelle_comment_form' );
}

/*
 * Check fields in comment form before saving comment.
 * Will only get called when spam comments are not saved, so only one match is needed.
 *
 * @param  array comment
 * @return array comment
 *
 * @since 1.3.0
 */
function la_sentinelle_preprocess_comment( $comment_array ) {

	if ( is_admin() && current_user_can( 'moderate_comments' ) ) {
		return $comment_array;
	}

	if ( defined('WP_IMPORTING') && WP_IMPORTING === true ) {
		return $comment_array;
	}

	if ( $comment_array['comment_type'] === 'pingback' ) {
		return $comment_array;
	}

	// WooCommerce order notes.
	if ( $comment_array['comment_type'] === 'order_note' ) {
		return $comment_array;
	}

	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) {
		return $comment_array;
	}

	la_sentinelle_check_spamfilters();

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'wpcomments' );

		$error_messages = la_sentinelle_get_default_error_messages();
		$message = $error_messages['go_back_try_again'] . '<br />
			<a href="#" title="' . esc_attr__('Go back', 'la-sentinelle-antispam' ) . '" onClick="history.back();">' . esc_html__('Go back &raquo;', 'la-sentinelle-antispam' ) . '</a>';
		wp_die( $message );
	}

	$marker = la_sentinelle_check_stop_forum_spam_wpcomment( $comment_array );
	if ( $marker === 'spam' ) {
		la_sentinelle_add_statistic_blocked( 'wpcomments' );
		$message = $error_messages['go_back_try_again'] . '<br />
			<a href="#" title="' . esc_attr__('Go back', 'la-sentinelle-antispam' ) . '" onClick="history.back();">' . esc_html__('Go back &raquo;', 'la-sentinelle-antispam' ) . '</a>';
		wp_die( $message );
	}

	return $comment_array;

}
if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
	if (get_option( 'la_sentinelle-save_comments', 'true') !== 'true') {
		add_filter( 'preprocess_comment', 'la_sentinelle_preprocess_comment' );
	}
}


/*
 * Check fields in comment form after saving comment and set status to spam if needed.
 *
 * @param  int   $id      comment ID.
 * @param  array $comment instance of WP_Comment.
 *
 * @since 1.0.0
 */
function la_sentinelle_wp_insert_comment( $id, $comment ) {

	if ( is_admin() && current_user_can( 'moderate_comments' ) ) {
		return;
	}

	if ( defined('WP_IMPORTING') && WP_IMPORTING === true ) {
		return;
	}

	if ( defined('XMLRPC_REQUEST') && XMLRPC_REQUEST ) {
		return;
	}

	$comment = get_comment( $id );
	if ( $comment->comment_type === 'pingback' ) {
		return;
	}

	// WooCommerce order notes.
	if ( $comment->comment_type === 'order_note' ) {
		return;
	}

	$add_statistic = false;
	la_sentinelle_check_spamfilters();
	$markers = la_sentinelle_check_scores();

	if ( is_array( $markers ) && ! empty( $markers ) ) {
		wp_set_comment_status( $id, 'spam' );
		$add_statistic = true;
		foreach ( $markers as $marker ) {
			if ( $marker === 'nonce' ) {
				update_comment_meta( $id, 'la_sentinelle_nonce', 'spam' );
			} else if ( $marker === 'honeypot' ) {
				update_comment_meta( $id, 'la_sentinelle_honeypot', 'spam' );
			} else if ( $marker === 'timeout' ) {
				update_comment_meta( $id, 'la_sentinelle_timeout', 'spam' );
			} else if ( $marker === 'ajax' ) {
				update_comment_meta( $id, 'la_sentinelle_ajax', 'spam' );
			} else if ( $marker === 'webgl' ) {
				update_comment_meta( $id, 'la_sentinelle_webgl', 'spam' );
			}
		}
	}

	$comment_array = (array) $comment;
	$marker_sfs = la_sentinelle_check_stop_forum_spam_wpcomment( $comment_array );
	if ( $marker_sfs === 'spam' ) {
		$add_statistic = true;
		wp_set_comment_status( $id, 'spam' );
		update_comment_meta( $id, 'la_sentinelle_sfs', 'spam' );
	}

	if ( $add_statistic ) {
		la_sentinelle_add_statistic_blocked( 'wpcomments' );
	}

}
if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
	if (get_option( 'la_sentinelle-save_comments', 'true') === 'true') {
		add_action( 'wp_insert_comment', 'la_sentinelle_wp_insert_comment', 10, 2 );
	}
}


/*
 * Add list-item to Right Now dashboard widget.
 *
 * @param  array $items list of items to add.
 * @return array $items list of items to add.
 *
 * @since 1.1.0
 */
function la_sentinelle_dashboard_spam_comments( $items ) {

	$args = array(
		'fields' => 'ids',
		'status' => 'spam',
	);
	$comments = get_comments( $args );

	if ( is_array( $comments ) && ! empty( $comments ) ) {
		$count   = count($comments);
		$text    = sprintf( _n( '%s spam comment', '%s spam comments', $count, 'la-sentinelle-antispam' ), number_format_i18n( $count ) );
		$items[] = '<a href="edit-comments.php?comment_status=spam">' . $text . '</a>';
	}

	return $items;

}
if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
	add_filter( 'dashboard_glance_items', 'la_sentinelle_dashboard_spam_comments' );
}


/*
 * Add postbox for comment meta logging.
 *
 * @param  string $output  html for postbox.
 * @param  object $comment instance of WP_Comment.
 * @return string $output  html for postbox.
 *
 * @since 1.1.0
 */
function la_sentinelle_edit_comment_meta( $output, $comment ) {

	$comment_id = $comment->comment_ID;

	$la_sentinelle_nonce    = get_comment_meta( $comment_id, 'la_sentinelle_nonce', true );
	$la_sentinelle_honeypot = get_comment_meta( $comment_id, 'la_sentinelle_honeypot', true );
	$la_sentinelle_timeout  = get_comment_meta( $comment_id, 'la_sentinelle_timeout', true );
	$la_sentinelle_sfs      = get_comment_meta( $comment_id, 'la_sentinelle_sfs', true );
	$la_sentinelle_ajax     = get_comment_meta( $comment_id, 'la_sentinelle_ajax', true );
	$la_sentinelle_webgl    = get_comment_meta( $comment_id, 'la_sentinelle_webgl', true );

	if ( $la_sentinelle_nonce === 'spam' || $la_sentinelle_honeypot === 'spam' || $la_sentinelle_timeout === 'spam' || $la_sentinelle_sfs === 'spam' || $la_sentinelle_ajax === 'spam' || $la_sentinelle_webgl === 'spam' ) {

		$output .= '<div class="misc-pub-section misc-pub-la-sentinelle">
			<span><b>' . esc_html__( 'La Sentinelle', 'la-sentinelle-antispam' ) . '</b></span><br />
			';

		if ( $la_sentinelle_nonce === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by Nonce', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}
		if ( $la_sentinelle_honeypot === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by Honeypot', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}
		if ( $la_sentinelle_timeout === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by Timeout', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}
		if ( $la_sentinelle_sfs === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by Stop Forum Spam', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}
		if ( $la_sentinelle_ajax === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by AJAX', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}
		if ( $la_sentinelle_webgl === 'spam' ) {
			$output .= '<span>&bull; ' . esc_html__( 'Marked as spam by WebGL', 'la-sentinelle-antispam' ) . '</span><br />
				';
		}

		$output .= '</div>
			';

	}

	return $output;

}
add_filter( 'edit_comment_misc_actions', 'la_sentinelle_edit_comment_meta', 10, 2 );
