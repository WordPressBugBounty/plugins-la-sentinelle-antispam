<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Spamfilters tab of the Settings page.
 */
function la_sentinelle_settingstab_spamfilters() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam'));
	} ?>

	<input type="hidden" id="la_sentinelle_tab" name="la_sentinelle_tab" value="la_sentinelle_settingstab_spamfilters" />
	<?php
	settings_fields( 'la_sentinelle_options' );
	do_settings_sections( 'la_sentinelle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'la_sentinelle_settingstab_spamfilters' );
	echo '<input type="hidden" id="la_sentinelle_settingstab_spamfilters" name="la_sentinelle_settingstab_spamfilters" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

		<tr>
			<th scope="row"><label for="la_sentinelle-honeypot"><?php esc_html_e('Honeypot', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-honeypot', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-honeypot" id="la_sentinelle-honeypot">
				<label for="la_sentinelle-honeypot">
					<?php esc_html_e('Use Honeypot.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php esc_html_e('This will add a non-visible input field to the form. It should not get filled in, but when it is, the entry will be marked as spam.', 'la-sentinelle-antispam'); ?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="la_sentinelle-nonce"><?php esc_html_e('Nonce', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-nonce', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-nonce" id="la_sentinelle-nonce">
				<label for="la_sentinelle-nonce">
					<?php esc_html_e('Use Nonce.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add a Nonce to the form. It is a way to check for a human user. If it does not validate, the entry will be marked as spam.', 'la-sentinelle-antispam');
					echo '<br />';
					$link_wp = '<a href="https://codex.wordpress.org/Wordpress_Nonce_Implementation" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'If you want to know more about what a Nonce is and how it works, please read about it on the %1$sWordPress Codex%2$s.', 'la-sentinelle-antispam' ), $link_wp, '</a>' );
					echo '<br />';
					esc_html_e('If your website uses caching, it is possible that you get false-positives in your spamfolder. If this is the case, you could either disable the Nonce, or disable caching for the form pages.', 'la-sentinelle-antispam');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="la_sentinelle-timeout"><?php esc_html_e('Form Timeout', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-timeout', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-timeout" id="la_sentinelle-timeout">
				<label for="la_sentinelle-timeout">
					<?php esc_html_e('Set timeout for form submit.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will enable a timer function for the form. If the form is submitted faster than the timeout the input will be marked as spam.', 'la-sentinelle-antispam');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="la_sentinelle-sfs"><?php esc_html_e('Stop Forum Spam', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-sfs', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-sfs" id="la_sentinelle-sfs">
				<label for="la_sentinelle-sfs">
					<?php esc_html_e('Use Stop Forum Spam service.', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('Stop Forum Spam is an external service that acts as a spamfilter for submitted forms.', 'la-sentinelle-antispam'); echo '<br />';
					esc_html_e('Stop Forum Spam is currently only used for the default forms in WordPress Core, not for plugins.', 'la-sentinelle-antispam'); echo '<br />';
					$link_wp = '<a href="https://www.stopforumspam.com" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'If you want to know more about Stop Forum Spam and how it works, please read about it on their %1$swebsite%2$s.', 'la-sentinelle-antispam' ), $link_wp, '</a>' );
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="la_sentinelle-ajax"><?php esc_html_e('AJAX', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-ajax', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-ajax" id="la_sentinelle-ajax">
				<label for="la_sentinelle-ajax">
					<?php esc_html_e('Use AJAX.', 'la-sentinelle-antispam'); ?> <?php esc_html_e('(Experimental)', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add an AJAX test to the form. It is a way to check for a real browser with AJAX enabled and network traffic. If it does not validate, the entry will be marked as spam.', 'la-sentinelle-antispam');
					echo '<br />';
					esc_html_e('This spamfilter is experimental, it is possible that you get false-positives in your spamfolder. If this is the case, you are advised to disable this AJAX test and report this on the support forum.', 'la-sentinelle-antispam');
					?>
				</span>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="la_sentinelle-webgl"><?php esc_html_e('WebGL', 'la-sentinelle-antispam'); ?></label></th>
			<td>
				<input <?php
					if (get_option( 'la_sentinelle-webgl', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-webgl" id="la_sentinelle-webgl">
				<label for="la_sentinelle-webgl">
					<?php esc_html_e('Use WebGL.', 'la-sentinelle-antispam'); ?> <?php esc_html_e('(Experimental)', 'la-sentinelle-antispam'); ?>
				</label><br />
				<span class="setting-description">
					<?php
					esc_html_e('This will add a WebGL test to the form. It is a way to check for a real browser with 3D and WebGL enabled. If it does not validate, the entry will be marked as spam.', 'la-sentinelle-antispam');
					echo '<br />';
					esc_html_e('This spamfilter is experimental, it is possible that you get false-positives in your spamfolder. If this is the case, you are advised to disable this WebGL filter and report this on the support forum.', 'la-sentinelle-antispam');
					?>
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
