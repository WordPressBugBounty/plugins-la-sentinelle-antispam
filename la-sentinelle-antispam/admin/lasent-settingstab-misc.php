<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Misc tab of the Settings page.
 */
function la_sentinelle_settingstab_misc() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam'));
	} ?>

	<input type="hidden" id="la_sentinelle_tab" name="la_sentinelle_tab" value="la_sentinelle_settingstab_misc" />
	<?php
	settings_fields( 'la_sentinelle_options' );
	do_settings_sections( 'la_sentinelle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'la_sentinelle_settingstab_misc' );
	echo '<input type="hidden" id="la_sentinelle_settingstab_misc" name="la_sentinelle_settingstab_misc" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr valign="top">
			<th scope="row"><label for="la_sentinelle-save_comments"><?php esc_html_e('Save spam submissions', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-save_comments', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-save_comments" id="la_sentinelle-save_comments">
				<label for="la_sentinelle-save_comments">
					<?php esc_html_e('Save spam submissions.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('This will save spam comments in the spam folder as well as save spam submissions from form plugins. This way you can still look for false positives and in the case of spam comments you can manually moderate them.', 'la-sentinelle-antispam'); ?>
				</span>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><label for="la_sentinelle-remove_comments"><?php esc_html_e('Remove old spam', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-remove_comments', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-remove_comments" id="la_sentinelle-remove_comments">
				<label for="la_sentinelle-remove_comments">
					<?php esc_html_e('Remove old spam submissions.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('This will remove old spam comments after 3 months as well as spam submissions from form plugins.', 'la-sentinelle-antispam'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th colspan="2">
				<p class="submit">
					<input type="submit" name="la_sentinelle_settings_submit" id="la_sentinelle_settings_submit" class="button-primary" value="<?php esc_attr_e('Save settings', 'la-sentinelle-antispam'); ?>" />
				</p>
			</th>
		</tr>

		</tbody>
	</table>

	<?php
}
