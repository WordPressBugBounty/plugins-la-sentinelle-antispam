<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Register post type for logging.
 *
 * @since 2.0.0
 */
function la_sentinelle_register_log_post_type() {

	register_post_type( 'la_sentinelle_log', array(
			'labels'          => array(
				'name'          => esc_html__( 'La Sentinelle Logs', 'la-sentinelle-antispam' ),
				'singular_name' => esc_html__( 'Submission Log', 'la-sentinelle-antispam' ),
			),
			'rewrite'         => false,
			'query_var'       => false,
			'public'          => false,
			'supports'        => array( 'editor', 'custom-fields' ),
			'capability_type' => 'page',
		)
	);

}
add_action( 'init', 'la_sentinelle_register_log_post_type' );


/*
 * Save spam submission of plugin forms in the database in its own custom post type.
 *
 * @param $plugin_slug string Slug of the plugin that provided the form of this submission.
 * @param $spamfilters mixed  Array or string with list of spamfilters that were triggered and recognized the submisson as spam.
 *
 * @return $post_id    int Number higher than 0 if it was saved in the database.
 *
 * @since 2.0.0
 */
function la_sentinelle_save_spam_submission( $plugin_slug, $spamfilters ) {

	if (get_option( 'la_sentinelle-save_comments', 'true') !== 'true') {
		return 0;
	}

	/* Setting both dates will set the published date to this. */
	$post_date = current_time( 'mysql' );
	$post_date_gmt = get_gmt_from_date( $post_date );

	$post_content = '';
	$postdata = $_POST;
	foreach ( $postdata as $key => $value ) {
		if ( is_array( $value ) ) {
			$substring = '';
			$value = la_sentinelle_array_flatten( $value );
			foreach ( $value as $subkey => $subvalue ) {
				$substring .= sanitize_text_field( $subkey . ': ' . $subvalue ) . "<br />\r\n";
			}
			$post_content .= $substring;
		} else {
			$post_content .= sanitize_text_field( $key . ': ' . $value ) . "<br />\r\n";
		}
	}

	$plugin_slug = sanitize_text_field( $plugin_slug );

	if ( is_array( $spamfilters ) && ! empty( $spamfilters ) ) {
		$spamfilters = sanitize_text_field( implode( ', ', $spamfilters ) );
	} else if ( is_string( $spamfilters ) && strlen( $spamfilters ) > 0 ) {
		$spamfilters = sanitize_text_field( $spamfilters );
	} else {
		$spamfilters = esc_html__( '(none)', 'la-sentinelle-antispam' );
	}

	$post_data = array(
		'post_parent'    => 0,
		'post_status'    => 'draft',
		'post_type'      => 'la_sentinelle_log',
		'post_date'      => $post_date,
		'post_date_gmt'  => $post_date_gmt,
		'post_author'    => get_current_user_id(),
		'post_password'  => '',
		'post_content'   => $post_content,
		'menu_order'     => 0,
	);
	$post_id = wp_insert_post( $post_data );

	/* Bail if no post was added. */
	if ( empty( $post_id ) ) {
		return 0;
	}

	$post_meta = array(
		'lasent_plugin_slug' => $plugin_slug,
		'lasent_spamfilters' => $spamfilters,
	);

	// Insert post meta.
	foreach ( $post_meta as $meta_key => $meta_value ) {
		update_post_meta( $post_id, $meta_key, $meta_value );
	}

	do_action( 'la_sentinelle_after_save_spam_submission', $post_id );

	return $post_id;

}


/*
 * Flattens an array to only one level deep.
 * Taken from:
 * https://stackoverflow.com/questions/7179799/how-to-flatten-array-of-arrays-to-array
 *
 * @param array Array flat or multi-dimensional.
 * @return array Array flat.
 *
 * @since 2.4.0
 */
function la_sentinelle_array_flatten( $data_to_flatten ) {

	if ( ! is_array( $data_to_flatten ) ) {
		return $data_to_flatten;
	}

	$result = array();
	foreach ( $data_to_flatten as $key => $value ) {
		if ( is_array( $value ) ) {
			$result = array_merge( $result, la_sentinelle_array_flatten( $value ) );
		} else {
			$result[$key] = $value;
		}
	}

	return $result;

}
