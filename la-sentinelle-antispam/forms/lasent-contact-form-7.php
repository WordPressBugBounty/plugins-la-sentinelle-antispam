<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Initialize the shortcode; this lets CF7 know about us.
 *
 * @since 1.0.0
 */
function la_sentinelle_wpcf7_add_form_tag() {

	// Test if new 4.6+ functions exists.
	if ( function_exists('wpcf7_add_form_tag') ) {
		wpcf7_add_form_tag( 'la_sentinelle', 'la_sentinelle_wpcf7_formtag_handler', true );
	} else if ( function_exists('wpcf7_add_shortcode') ) {
		wpcf7_add_shortcode( 'la_sentinelle', 'la_sentinelle_wpcf7_formtag_handler', true );
	}

}
add_action( 'wpcf7_init', 'la_sentinelle_wpcf7_add_form_tag', 10 );


/*
 * Form Tag handler; this is where we generate the HTML from the shortcode options.
 *
 * @since 1.0.0
 */
function la_sentinelle_wpcf7_formtag_handler( $tag ) {

	// Test if new 4.6+ functions exists
	if ( class_exists('WPCF7_FormTag') ) {
		$tag = new WPCF7_FormTag( $tag );
	} else if ( class_exists('WPCF7_Shortcode') ) {
		$tag = new WPCF7_Shortcode( $tag );
	}

	if ( empty( $tag->name ) )
		return '';

	// Return, not echo.
	$html = la_sentinelle_get_spamfilters( 'cf7' );
	return $html;

}


/*
 * Validation filter.
 * This sets the correct status, in case it doesnot validate.
 *
 * @param array $result the result in case it doesnot validate.
 * @param string $tag the tag in this form that is being validated in this filter.
 * @return array $result unchanged input. The message gets changed elsewhere, based on spam positive or negative.
 *
 * @since 1.0.0
 */
function la_sentinelle_wpcf7_formtag_validate( $result, $tag ) {

	la_sentinelle_check_spamfilters( 'cf7' );

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		la_sentinelle_add_statistic_blocked( 'cf7' );
		la_sentinelle_save_spam_submission( 'contact-form-7', $markers );
	}

	return $result; // unchanged

}
add_filter( 'wpcf7_validate_la_sentinelle', 'la_sentinelle_wpcf7_formtag_validate', 10, 2 );


/*
 * Set spam to true if our spamfilters have a positive match.
 *
 * $param bool $spam if it was already seen as spam or not.
 * $return bool $spam set to true if our spamfilters have a positive match.
 *
 * @since 1.5.1
 */
function la_sentinelle_wpcf7_spam( $spam ) {

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {
		return true;
	}

	return $spam;

}
add_filter( 'wpcf7_spam', 'la_sentinelle_wpcf7_spam', 10, 1 );


/*
 * Tag generator; add tags to the CF7 form editor.
 *
 * @since 1.0.0
 */
function la_sentinelle_wpcf7_formtag_generator() {

	if ( class_exists('WPCF7_TagGenerator') ) {
		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add( 'la_sentinelle', esc_html__( 'La Sentinelle antispam', 'la-sentinelle-antispam' ), 'la_sentinelle_wpcf7_formtag_pane' );
	} else if ( function_exists('wpcf7_add_tag_generator') ) {
		wpcf7_add_tag_generator( 'la_sentinelle', esc_html__( 'La Sentinelle antispam', 'la-sentinelle-antispam' ), 'la_sentinelle_wpcf7_formtag_pane', 'la_sentinelle_wpcf7_formtag_pane' );
	}

}
add_action( 'wpcf7_admin_init', 'la_sentinelle_wpcf7_formtag_generator', 35 );


/*
 * Tag generator; add popup pane for the CF7 form editor.
 *
 * @since 1.0.0
 */
function la_sentinelle_wpcf7_formtag_pane( $contact_form, $args = '' ) {
	if (class_exists('WPCF7_TagGenerator')) {
		$args = wp_parse_args( $args, array() );
		/* translators: %s is a link to the settingspage with the description of the tag. */
		$description = esc_html__( 'Generate a form-tag for antispamfilter fields. For more details, see %s.', 'la-sentinelle-antispam' );
		$desc_link = '<a href="' . admin_url( 'options-general.php?page=la-sentinelle.php' ) . '">' . esc_html__( 'Settings for La Sentinelle', 'la-sentinelle-antispam' ) . '</a>';
		?>
		<div class="control-box">
			<fieldset>
				<legend><?php printf( esc_html( $description ), $desc_link ); ?></legend>

				<table class="form-table"><tbody>
					<tr>
						<th scope="row">
							<label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html__( 'Name', 'la-sentinelle-antispam' ); ?></label>
						</th>
						<td>
							<input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /><br>
						</td>
					</tr>

				</tbody></table>
			</fieldset>
		</div>

		<div class="insert-box">
			<input type="text" name="la_sentinelle" class="tag code" readonly="readonly" onfocus="this.select()" />

			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr__( 'Insert Tag', 'la-sentinelle-antispam' ); ?>" />
			</div>

			<br class="clear" />
		</div>

	<?php } else { ?>

		<div id="wpcf7-tg-pane-la_sentinelle" class="hidden">
			<form action="">
				<table>
					<tr>
						<td>
							<?php echo esc_html__( 'Name', 'la-sentinelle-antispam' ); ?><br />
							<input type="text" name="name" class="tg-name oneline" /><br />
						</td>
						<td></td>
					</tr>
				</table>

				<div class="tg-tag">
					<?php echo esc_html__( 'Copy this code and paste it into the form left.', 'la-sentinelle-antispam' ); ?><br />
					<input type="text" name="la_sentinelle" class="tag" readonly="readonly" onfocus="this.select()" />
				</div>
			</form>
		</div>
	<?php }
}


/*
 * Messages for feedback in AJAX post.
 *
 * @param string $message the message that the visitor will see.
 * @param string $status the current status of the submission.
 * @return string $message if our spamfilters mark it as positive, set it to our custom message.
 *
 * @since 1.5.1
 */
function la_sentinelle_wpcf7_validation_messages_fail( $message, $status ) {

	$markers = la_sentinelle_check_scores();
	if ( is_array( $markers ) && ! empty( $markers ) ) {

		if ( $status === 'spam' ) {
			$error_messages = la_sentinelle_get_default_error_messages();
			$message .= $error_messages['try_again'];
		}
	}

	return $message;

}
add_filter( 'wpcf7_display_message', 'la_sentinelle_wpcf7_validation_messages_fail', 99, 2 );
