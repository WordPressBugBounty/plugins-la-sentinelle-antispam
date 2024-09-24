<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Get form fields for a form.
 *
 * @param  string be able to use different actions for the nonce.
 * @return string html with the enabled input fields.
 *
 * @since 1.0.0
 */
function la_sentinelle_get_spamfilters( $nonce_action = 'default' ) {

	$output = '
		<div class="la-sentinelle-container" style="max-height:0;overflow:hidden;">
		';

	$filters = range( 1, 6 );
	shuffle( $filters ); // random order.
	foreach ( $filters as $filter ) {
		if ( $filter === 1 ) {
			$output .= la_sentinelle_get_nonce( $nonce_action );
		} else if ( $filter === 2 ) {
			$output .= la_sentinelle_get_honeypot( true );
		} else if ( $filter === 3 ) {
			$output .= la_sentinelle_get_honeypot( false );
		} else if ( $filter === 4 ) {
			$output .= la_sentinelle_get_timeout();
		} else if ( $filter === 5 ) {
			$output .= la_sentinelle_get_ajax();
		} else if ( $filter === 6 ) {
			$output .= la_sentinelle_get_webgl();
		}
	}

	$output .= '
		</div>
		<div class="la-sentinelle-container-nojs-message">
			<noscript><div class="no-js">' . esc_html__( 'Warning: This form can only be used if JavaScript is enabled in your browser.', 'la-sentinelle-antispam' ) . '</div></noscript>
		</div>
		';

	return $output;

}


/*
 * Enqueue script.
 * Load it on admin_enqueue as well.
 *
 * @since 1.0.0
 */
function la_sentinelle_enqueue() {

	wp_register_script( 'la_sentinelle_frontend_js', plugins_url('js/la-sentinelle-frontend.js', __FILE__), array( 'jquery' ), LASENT_VER, true );
	$data_to_be_passed = array(
		'ajaxurl'   => esc_url( admin_url('admin-ajax.php') ),
		'ajax2'     => la_sentinelle_get_field_name( 'ajax2' ),
		'ajax3'     => la_sentinelle_get_field_name( 'ajax3' ),
		'honeypot'  => la_sentinelle_get_field_name( 'honeypot' ),
		'honeypot2' => la_sentinelle_get_field_name( 'honeypot2' ),
		'timeout'   => la_sentinelle_get_field_name( 'timeout' ),
		'timeout2'  => la_sentinelle_get_field_name( 'timeout2' ),
		'webgl'     => la_sentinelle_get_field_name( 'webgl' ),
		'webgl2'    => la_sentinelle_get_field_name( 'webgl2' ),
		'webgl3'    => la_sentinelle_get_field_name( 'webgl3' ),
	);
	wp_localize_script( 'la_sentinelle_frontend_js', 'la_sentinelle_frontend_script', $data_to_be_passed );
	wp_enqueue_script('la_sentinelle_frontend_js');

}
add_action( 'wp_enqueue_scripts', 'la_sentinelle_enqueue' );
add_action( 'admin_enqueue_scripts', 'la_sentinelle_enqueue' );


/*
 * Load styles and scripts the oldfashioned way.
 * Add spamfilter fields to login_form and lost_password_form and other silly forms without a proper hook.
 *
 * @since 1.0.0
 */
function la_sentinelle_dead_enqueue() {

	$url = get_bloginfo('wpurl') . '/wp-includes/js/jquery/jquery.js?ver=' . LASENT_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";

	$url = LASENT_URL . 'spamfilters/js/la-sentinelle-frontend.js?ver=' . LASENT_VER;
	echo "<script type='text/javascript' src='" . esc_attr( $url ) . "'></script>";

	?>
	<script type='text/javascript'>
	var la_sentinelle_frontend_script = {
		"ajaxurl":  "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>",
		"ajax2":    "<?php echo la_sentinelle_get_field_name( 'ajax2' ); ?>",
		"ajax3":    "<?php echo la_sentinelle_get_field_name( 'ajax3' ); ?>",
		"honeypot": "<?php echo la_sentinelle_get_field_name( 'honeypot' ); ?>",
		"honeypot2":"<?php echo la_sentinelle_get_field_name( 'honeypot2' ); ?>",
		"timeout":  "<?php echo la_sentinelle_get_field_name( 'timeout' ); ?>",
		"timeout2": "<?php echo la_sentinelle_get_field_name( 'timeout2' ); ?>",
		"webgl":    "<?php echo la_sentinelle_get_field_name( 'webgl' ); ?>",
		"webgl2":   "<?php echo la_sentinelle_get_field_name( 'webgl2' ); ?>",
		"webgl3":   "<?php echo la_sentinelle_get_field_name( 'webgl3' ); ?>",
	};
	</script>
	<?php

}
