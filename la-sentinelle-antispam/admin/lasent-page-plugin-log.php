<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Adds an adminpage Settings > La Sentinelle Logs.
 *
 * @since 2.0.0
 */
function la_sentinelle_adminpage_plugin_log_hook() {

	// Fallback: Make sure admin always has access
	$lasent_cap = ( current_user_can( 'lasent_access') ) ? 'lasent_access' : 'manage_options';

	add_options_page( esc_html__('La Sentinelle Log', 'la-sentinelle-antispam'), esc_html__('La Sentinelle Log', 'la-sentinelle-antispam'), $lasent_cap, 'la-sentinelle-log.php', 'la_sentinelle_adminpage_plugin_log');

}
add_action( 'admin_menu', 'la_sentinelle_adminpage_plugin_log_hook', 11 );


/*
 * Admin page with logs.
 *
 * @since 2.0.0
 */
function la_sentinelle_adminpage_plugin_log() {

	// Fallback: Make sure admin always has access
	$lasent_cap = ( current_user_can( 'lasent_access') ) ? 'lasent_access' : 'manage_options';

	if ( ! current_user_can( $lasent_cap ) ) {
		die(esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam'));
	}

	?>
		<div class="wrap la_sentinelle_settingspage_wrapper">
			<div id="icon-la-sentinelle"><br /></div>
			<h1><?php esc_html_e('La Sentinelle Log', 'la-sentinelle-antispam'); ?></h1>
			<?php

			if ( isset($_POST['la_sentinelle_log_massedit'])  ) {
				la_sentinelle_adminpage_plugin_log_massedit();
			}

			$pagenum = 1;
			if ( isset( $_GET['pagenum'] ) ) {
				$pagenum = (int) $_GET['pagenum'];
			}
			$nonce          = wp_create_nonce( 'la_sentinelle_log_wpnonce' );
			$post_type      = 'la_sentinelle_log';
			$posts_per_page = 20;
			$count_posts    = wp_count_posts('la_sentinelle_log');
			$total_items    = (int) $count_posts->draft;
			$total_pages    = (int) ceil( $total_items / $posts_per_page );
			$offset         = ( $pagenum - 1 ) * $posts_per_page;


			$args = array(
				'post_type'      => $post_type,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'post_status'    => 'draft',
				'posts_per_page' => $posts_per_page,
				'offset'         => $offset,
			);

			$the_query = new WP_Query( $args );

			?>
			<form name="la_sentinelle_log" id="la_sentinelle_log" action="#" method="POST" accept-charset="UTF-8">
				<input type="hidden" name="pagenum" value="<?php echo (int) $pagenum; ?>">
				<input type="hidden" name="posts_per_page" value="<?php echo (int) $posts_per_page; ?>">
				<input type="hidden" id="la_sentinelle_log_wpnonce" name="la_sentinelle_log_wpnonce" value="<?php echo esc_attr( $nonce ); ?>" />

				<div class="tablenav">
					<?php
					if ( $total_items > 0 ) {
						?>
					<div class="alignleft actions">
						<select name="la_sentinelle_log_massedit">
							<option value="0"><?php esc_html_e('Select...', 'la-sentinelle-antispam'); ?></option>
							<!--<option value="mark_read"><?php esc_html_e('Mark Read', 'la-sentinelle-antispam'); ?></option>-->
							<!--<option value="mark_unread"><?php esc_html_e('Mark Unread', 'la-sentinelle-antispam'); ?></option>-->
							<option value="remove"><?php esc_html_e('Remove Permanently', 'la-sentinelle-antispam'); ?></option>
						</select>
						<input type="submit" name="la_sentinelle_doaction" id="la_sentinelle_doaction" class="button-secondary action" value="<?php esc_attr_e('Apply', 'la-sentinelle-antispam'); ?>" />
						<input type="submit" name="la_sentinelle_delete_all" id="la_sentinelle_delete_all" class="button apply action" value="<?php esc_attr_e('Empty Spam', 'la-sentinelle-antispam'); ?>" />
					</div>

					<div class="alignright actions">
						<div class="tablenav-pages">
							<h2 class="screen-reader-text"><?php esc_html_e('List Navigation', 'la-sentinelle-antispam'); ?></h2>

							<span><?php /* translators: Total number of items of spam submissions that were saved. */
								printf( _n( '%d Item', '%d Items', $total_items, 'la-sentinelle-antispam' ), $total_items ); ?>
							</span>

							<?php
							if ( $pagenum > 1 ) {
								$link = admin_url( '/options-general.php?page=la-sentinelle-log.php&pagenum=' . round( $pagenum - 1 ) ); ?>
								<a class="prev prev-page page-numbers button" href="<?php echo esc_attr( $link ); ?>" rel="prev"><span class="screen-reader-text"><?php esc_html_e('Previous Page', 'la-sentinelle-antispam'); ?></span><span aria-hidden="true">&larr;</span></a><?php
							} else {
								?><span class="tablenav-pages-navspan button disabled" aria-hidden="true">&larr;</span><?php
							} ?>

							<span class="page-numbers current"><?php printf( esc_html__( 'Page %1$d of %2$d', 'la-sentinelle-antispam' ), $pagenum, $total_pages ); ?></span>

							<?php
							if ( $pagenum < $total_pages ) {
								$link = admin_url( '/options-general.php?page=la-sentinelle-log.php&pagenum=' . round( $pagenum + 1 ) ); ?>
								<a class="next next-page page-numbers button" href="<?php echo esc_attr( $link ); ?>" rel="next"><span class="screen-reader-text"><?php esc_html_e('Next Page', 'la-sentinelle-antispam'); ?></span><span aria-hidden="true">&rarr;</span></a><?php
							} else {
								?><span class="tablenav-pages-navspan button disabled" aria-hidden="true">&rarr;</span><?php
							} ?>
						</div>
					</div>
						<?php
					} ?>
				</div>

				<table class="widefat">
					<thead>
						<tr>
							<th scope="col" class="manage-column column-cb check-column"><input name="check-all-top" id="check-all-top" type="checkbox"></th>
							<th scope="col"><?php esc_html_e('Plugin', 'la-sentinelle-antispam'); ?></th>
							<th scope="col">
								<?php esc_html_e('Form Data', 'la-sentinelle-antispam');
								if ( $total_items > 0 ) {
								?><br />
								<a class="lasent-log-viewer-open-all" href="#"><?php esc_html_e('View All Form Data', 'la-sentinelle-antispam'); ?></a>
								<a class="lasent-log-viewer-close-all" href="#" style="display:none;"><?php esc_html_e('Hide All Form Data', 'la-sentinelle-antispam'); ?></a><?php
								} ?>
							</th>
							<th scope="col"><?php esc_html_e('Date', 'la-sentinelle-antispam'); ?></th>
							<th scope="col"><?php esc_html_e('Spamfilters', 'la-sentinelle-antispam'); ?></th>
						</tr>
					</thead>

					<tbody>
					<?php
					if ( $the_query->have_posts() ) {
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$post_id = (int) get_the_id();
							$lasent_plugin_slug = sanitize_text_field( get_post_meta( $post_id, 'lasent_plugin_slug', true ) );
							$lasent_spamfilters = sanitize_text_field( get_post_meta( $post_id, 'lasent_spamfilters', true ) );
							?>

						<tr class="lasent-log-<?php echo $post_id; ?>">
							<td><input name="check-<?php echo $post_id; ?>" id="check-<?php echo $post_id; ?>" type="checkbox"></td>
							<td scope="col"><?php echo esc_attr( $lasent_plugin_slug ); ?></td>
							<td scope="col" class="lasent-log-viewer">
								<a class="lasent-log-viewer-open" href="#"><?php esc_html_e('View Form Data', 'la-sentinelle-antispam'); ?></a>
								<a class="lasent-log-viewer-close" href="#" style="display:none;"><?php esc_html_e('Hide Form Data', 'la-sentinelle-antispam'); ?></a>
								<div class="lasent-log-viewer-content" style="display:none;"><?php echo wpautop( wp_kses_post( wp_strip_all_tags( get_the_content() ) ) ); ?></div>
							</td>
							<td scope="col"><?php echo get_the_date(); echo ' '; the_time(); ?></td>
							<td scope="col"><?php echo esc_attr( $lasent_spamfilters ); ?></td>
						</tr>

							<?php
							add_action( 'admin_footer', 'la_sentinelle_adminpage_plugin_log_javascript' );
						}
					} else {

						echo '
								<tr>
									<td colspan="4" align="center">
										<strong>' . esc_html__('No items found.', 'la-sentinelle-antispam') . '</strong>
									</td>
								</tr>';

					}
					?>
					</tbody>

				</table>
			</form>

		</div>
	<?php

}

/*
 * Admin page with logs, updated from massedit dropdown.
 *
 * @since 2.0.0
 */
function la_sentinelle_adminpage_plugin_log_massedit() {

	// Fallback: Make sure admin always has access
	$lasent_cap = ( current_user_can( 'lasent_access') ) ? 'lasent_access' : 'manage_options';

	if ( ! current_user_can( $lasent_cap ) ) {
		$messages = '<p>' . esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam') . '</p>';
		echo '
					<div id="message" class="updated fade notice is-dismissible">' .
						$messages .
					'</div>';
		return;
	}

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['la_sentinelle_log_wpnonce']) ) {
		$verified = wp_verify_nonce( $_POST['la_sentinelle_log_wpnonce'], 'la_sentinelle_log_wpnonce' );
	}
	if ( $verified === false ) {
		// Nonce is invalid, so considered spam.
		$messages = '<p>' . esc_html__('Nonce check failed. Please try again.', 'la-sentinelle-antispam') . '</p>';
		echo '
					<div id="message" class="updated fade notice is-dismissible">' .
						$messages .
					'</div>';
		return;
	}

	$messages = '<p>' . esc_html__('Something unexpected happened, please try again.', 'la-sentinelle-antispam') . '</p>';

	if ( isset($_POST['la_sentinelle_delete_all']) ) {

		$posts_removed = 0;
		$args = array(
			'post_type'              => 'la_sentinelle_log',
			'posts_per_page'         => -1,
			'nopaging'               => true,
			'post_status'            => 'draft',
			'fields'                 => 'ids',
			'cache_results'          => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);

		$the_query = new WP_Query( $args );
		if ( isset( $the_query->posts ) && ! empty( $the_query->posts ) ) {
			foreach ( $the_query->posts as $postid ) {
				$postid = (int) $postid;
				if ( isset( $postid ) && $postid > 0 ) {

					// wp_delete_post( $postid = 0, $force_delete = false )
					$deleted_data = wp_delete_post( $postid, true );
					if ( is_object( $deleted_data ) && is_a( $deleted_data, 'WP_Post' ) && isset( $deleted_data->ID ) && $deleted_data->ID === $postid ) {
						$posts_removed++;
					}
				}
			}
		}

		if ( $posts_removed > 0 ) {
			$messages = '<p>' . sprintf( _n('%d form submission removed.', '%d form submissions removed.', $posts_removed, 'la-sentinelle-antispam'), $posts_removed ) . '</p>';
		} else {
			$messages = esc_html__('No form submissions removed.', 'la-sentinelle-antispam');
		}

	} else if ( isset($_POST['la_sentinelle_doaction']) && isset($_POST['la_sentinelle_log_massedit']) && $_POST['la_sentinelle_log_massedit'] === 'remove' ) {

		$posts_removed = 0;
		foreach ( array_keys($_POST) as $key ) {
			if (strpos($key, 'check') > -1 && ! strpos($key, '-all-') && isset($_POST["$key"]) && $_POST["$key"] === 'on') {
				$postid = (int) str_replace( 'check-', '', $key );
				if ( isset( $postid ) && $postid > 0 ) {

					// wp_delete_post( $postid = 0, $force_delete = false )
					$deleted_data = wp_delete_post( $postid, true );
					if ( is_object( $deleted_data ) && is_a( $deleted_data, 'WP_Post' ) && isset( $deleted_data->ID ) && $deleted_data->ID === $postid ) {
						$posts_removed++;
					}
				}
			}
		}

		if ( $posts_removed > 0 ) {
			$messages = '<p>' . sprintf( _n('%d form submission removed.', '%d form submissions removed.', $posts_removed, 'la-sentinelle-antispam'), $posts_removed ) . '</p>';
		} else {
			$messages = esc_html__('No form submissions removed.', 'la-sentinelle-antispam');
		}

	}

	echo '
					<div id="message" class="updated fade notice is-dismissible">' .
						$messages .
					'</div>';

}



/*
 * Add JavaScript to the admin footer of this page.
 * Gets added to 'admin_footer' hook by 'la_sentinelle_adminpage_plugin_log' function so it only gets added on this adminpage.
 *
 * @since 2.0.0
 */
function la_sentinelle_adminpage_plugin_log_javascript() {

	// Fallback: Make sure admin always has access
	$lasent_cap = ( current_user_can( 'lasent_access') ) ? 'lasent_access' : 'manage_options';

	if ( ! current_user_can( $lasent_cap ) ) {
		return;
	} ?>

	<script>
	jQuery( document ).ready( function( $ ) {

		jQuery( 'a.lasent-log-viewer-open-all' ).on('click', function() {
			jQuery('a.lasent-log-viewer-open').css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-open-all').css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-close').css( 'display', 'block' );
			jQuery('a.lasent-log-viewer-close-all').css( 'display', 'block' );
			jQuery('div.lasent-log-viewer-content').slideDown(500);
			return false;
		});

		jQuery( 'a.lasent-log-viewer-close-all' ).on('click', function() {
			var viewer_td = jQuery(this).parent();
			jQuery('a.lasent-log-viewer-close').css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-close-all').css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-open').css( 'display', 'block' );
			jQuery('a.lasent-log-viewer-open-all').css( 'display', 'block' );
			jQuery('div.lasent-log-viewer-content').slideUp(500);
			return false;
		});

		jQuery( 'a.lasent-log-viewer-open' ).on('click', function() {
			var viewer_td = jQuery(this).parent();
			jQuery('a.lasent-log-viewer-open', viewer_td).css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-close', viewer_td).css( 'display', 'block' );
			jQuery('div.lasent-log-viewer-content', viewer_td).slideDown(500);
			return false;
		});

		jQuery( 'a.lasent-log-viewer-close' ).on('click', function() {
			var viewer_td = jQuery(this).parent();
			jQuery('a.lasent-log-viewer-close', viewer_td).css( 'display', 'none' );
			jQuery('a.lasent-log-viewer-open', viewer_td).css( 'display', 'block' );
			jQuery('div.lasent-log-viewer-content', viewer_td).slideUp(500);
			return false;
		});

		jQuery( "form#la_sentinelle_log input[name='check-all-top']" ).on('change', function() {

			jQuery("input[name^='check-']").attr( 'checked', jQuery("input[name='check-all-top']").is(":checked") );

		});

	});
	</script>
	<?php

}
