<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Auto Deletes comments that are older than 3 months.
 *
 * @since 1.0.2
 */
function la_sentinelle_remove_comments() {

	if (get_option( 'la_sentinelle-remove_comments', 'false') === 'true') {

		$current_time = current_time('timestamp');
		$timestamp = strtotime( '-3 months', $current_time );

		$year = date( 'Y', $timestamp );
		$month = date( 'n', $timestamp );
		$day = date( 'j', $timestamp );

		if ( $year === false || $month === false || $day === false ) {
			return;
		}

		if ( ! is_numeric( $year ) || ! is_numeric( $month ) || ! is_numeric( $day ) ) {
			return;
		}

		$args = array(
			'fields'     => 'ids',
			'number'     => 10,
			'orderby'    => 'comment_date_gmt',
			'order'      => 'ASC',
			'status'     => 'spam',
			'date_query' => array(
					array(
						'before'    => array(
							'year'  => (int) $year,
							'month' => (int) $month,
							'day'   => (int) $day,
						),
					),
				),
			);
		$comments = get_comments( $args );

		if ( is_array($comments) && ! empty($comments) ) {
			foreach ($comments as $comment_id) {
				wp_delete_comment( $comment_id, true );
			}
		}
	}
}
if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
	add_action( 'shutdown', 'la_sentinelle_remove_comments' );
}


/*
 * Auto Deletes spam submissions that are older than 3 months.
 *
 * @since 2.0.0
 */
function la_sentinelle_remove_spam_submissions() {

	if (get_option( 'la_sentinelle-remove_comments', 'false') === 'true') {

		$current_time = current_time('timestamp');
		$timestamp = strtotime( '-3 months', $current_time );

		$year = date( 'Y', $timestamp );
		$month = date( 'n', $timestamp );
		$day = date( 'j', $timestamp );

		if ( $year === false || $month === false || $day === false ) {
			return;
		}

		if ( ! is_numeric( $year ) || ! is_numeric( $month ) || ! is_numeric( $day ) ) {
			return;
		}

		$args = array(
			'post_type'              => 'la_sentinelle_log',
			'posts_per_page'         => 10,
			'post_status'            => 'draft',
			'orderby'                => 'date',
			'order'                  => 'ASC',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'date_query' => array(
					array(
						'before'    => array(
							'year'  => (int) $year,
							'month' => (int) $month,
							'day'   => (int) $day,
						),
					),
				),
			);

		$the_query = new WP_Query( $args );
		if ( isset( $the_query->posts ) && ! empty( $the_query->posts ) ) {
			foreach ( $the_query->posts as $postid ) {
				$postid = (int) $postid;
				if ( isset( $postid ) && $postid > 0 ) {
					// wp_delete_post( $postid = 0, $force_delete = false )
					$deleted_data = wp_delete_post( $postid, true );
				}
			}
		}
	}
}

if (get_option( 'la_sentinelle-remove_comments', 'false') === 'true') {
	add_action( 'shutdown', 'la_sentinelle_remove_spam_submissions' );
}
