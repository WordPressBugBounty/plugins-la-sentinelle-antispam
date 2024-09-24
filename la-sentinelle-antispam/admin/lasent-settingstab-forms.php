<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Forms tab of the Settings page.
 */
function la_sentinelle_settingstab_forms() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam'));
	} ?>

	<input type="hidden" id="la_sentinelle_tab" name="la_sentinelle_tab" value="la_sentinelle_settingstab_forms" />
	<?php
	settings_fields( 'la_sentinelle_options' );
	do_settings_sections( 'la_sentinelle_options' );

	/* Nonce */
	$nonce = wp_create_nonce( 'la_sentinelle_settingstab_forms' );
	echo '<input type="hidden" id="la_sentinelle_settingstab_forms" name="la_sentinelle_settingstab_forms" value="' . esc_attr( $nonce ) . '" />';
	?>
	<table class="form-table">
		<tbody>

			<tr valign="top">
				<th scope="row">
					WordPress
				</th>
				<td>
					<input <?php
					if (get_option( 'la_sentinelle-wpcomment', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-wpcomment" id="la_sentinelle-wpcomment">
					<label for="la_sentinelle-wpcomment">
						<?php esc_html_e('Enable spamfilters for WordPress Comment Forms.', 'la-sentinelle-antispam'); ?>
					</label><br /><br />

					<input <?php
					if (get_option( 'la_sentinelle-wplogin', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-wplogin" id="la_sentinelle-wplogin">
					<label for="la_sentinelle-wplogin">
						<?php esc_html_e('Enable spamfilters for WordPress Login Form.', 'la-sentinelle-antispam'); ?>
					</label><br /><br />

					<input <?php
					if (get_option( 'la_sentinelle-wppassword', 'false') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-wppassword" id="la_sentinelle-wppassword">
					<label for="la_sentinelle-wppassword">
						<?php esc_html_e('Enable spamfilters for WordPress Reset Password Form.', 'la-sentinelle-antispam'); ?>
					</label><br /><br />

					<input <?php
					if (get_option( 'la_sentinelle-wpregister', 'true') === 'true') {
						echo 'checked="checked"';
					} ?>
					type="checkbox" name="la_sentinelle-wpregister" id="la_sentinelle-wpregister">
					<label for="la_sentinelle-wpregister">
						<?php esc_html_e('Enable spamfilters for WordPress Register Form.', 'la-sentinelle-antispam'); ?>
					</label><br /><br />

				</td>
			</tr>

		<?php
		$active_plugins = get_option('active_plugins');
		// print_r( $active_plugins );
		$supported_plugin = false;

		foreach ( $active_plugins as $plugin ) {

			if ( $plugin === 'caldera-forms/caldera-core.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-caldera">Caldera Forms</label>
					</th>
					<td>
						<span class="setting-description">
							<?php
							esc_html_e( 'You can easily add the formfields for this plugin to your contact form. Just click the button for "Add Field". On the popup pane go to the Special tab and add the form-tag for La Sentinelle to the form.', 'la-sentinelle-antispam' ); echo '<br />';
							esc_html_e( 'You need to give in a name/slug, which can be anything.', 'la-sentinelle-antispam' ); echo '<br />';
							$link = '<a href="' . esc_attr( admin_url( 'admin.php?page=caldera-forms' ) ) . '">' . esc_html__( 'caldera forms overview', 'la-sentinelle-antispam' ) . '</a>';
							/* translators: %s is a link to the contact forms overview. */
							printf( esc_html__( 'You can go to the %s and add it to the forms that you want.', 'la-sentinelle-antispam' ), $link );
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'clean-login/clean-login.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						Clean Login
					</th>
					<td>
						<span class="setting-description">
							<?php
							esc_html_e( 'Login form in Clean Login is supported automatically.', 'la-sentinelle-antispam' ); echo '<br />';
							esc_html_e( 'Please check the checkbox for WordPress Login form above.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'contact-form-7/wp-contact-form-7.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						Contact Form 7
					</th>
					<td>
						<span class="setting-description">
							<?php /* translators: form-tag is a term specific to Contact Form 7, it is a shortcode-like tag in the form. */
							esc_html_e( 'You can easily add the formfields for this plugin to your contact form. Just click the button for "La Sentinelle" and on the popup pane add the tag to the form.', 'la-sentinelle-antispam' ); echo '<br />';
							$link = '<a href="' . esc_attr( admin_url( 'admin.php?page=wpcf7' ) ) . '">' . esc_html__( 'contact form 7 overview', 'la-sentinelle-antispam' ) . '</a>';
							/* translators: %s is a link to the contact forms overview. */
							printf( esc_html__( 'You can go to the %s and add it to the forms that you want.', 'la-sentinelle-antispam' ), $link );
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'easy-digital-downloads/easy-digital-downloads.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						Easy Digital Downloads
					</th>
					<td>
						<span class="setting-description">
							<?php
							esc_html_e( 'Login form and Register form in Easy Digital Downloads are supported automatically.', 'la-sentinelle-antispam' ); echo '<br />';
							esc_html_e( 'Please check the checkbox for WordPress Login form and WordPress Register form above.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'everest-forms/everest-forms.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-everest">Everest Forms</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-everest', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-everest" id="la_sentinelle-everest">
						<label for="la_sentinelle-everest">
							<?php esc_html_e('Enable spamfilters for Everest.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to every Everest form.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'formidable/formidable.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-formidable">Formidable</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-formidable', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-formidable" id="la_sentinelle-formidable">
						<label for="la_sentinelle-formidable">
							<?php esc_html_e('Enable spamfilters for Formidable.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to every Formidable form.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'forminator/forminator.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-forminator">Forminator</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-forminator', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-forminator" id="la_sentinelle-forminator">
						<label for="la_sentinelle-forminator">
							<?php esc_html_e('Enable spamfilters for Forminator.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to every Forminator form.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'newsletter-optin-box/noptin.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-noptin">Newsletter Optin Box</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-noptin', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-noptin" id="la_sentinelle-noptin">
						<label for="la_sentinelle-noptin">
							<?php esc_html_e('Enable spamfilters for Noptin.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to the main Noptin form, when used as a shortcode.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'ultimate-member/ultimate-member.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						Ultimate Member
					</th>
					<td>
						<span class="setting-description">
							<?php
							esc_html_e( 'Login form, Register form and Lost Password form in Ultimate Member are supported automatically.', 'la-sentinelle-antispam' ); echo '<br />';
							esc_html_e( 'Please check the checkbox for WordPress Login form and WordPress Register form above.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'woocommerce/woocommerce.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						WooCommerce
					</th>
					<td>
						<p><span class="setting-description">
							<?php
							esc_html_e( 'Login form and Lost Password form in WooCommerce are supported automatically.', 'la-sentinelle-antispam' );
							echo '<br />';
							esc_html_e( 'Please check the checkboxes for WordPress forms above.', 'la-sentinelle-antispam' );
							echo '<br /><br />';
							?>
						</span></p>
						<p>
							<input <?php
							if (get_option( 'la_sentinelle-woo-registration', 'false') === 'true') {
								echo 'checked="checked"';
							} ?>
							type="checkbox" name="la_sentinelle-woo-registration" id="la_sentinelle-woo-registration">
							<label for="la_sentinelle-woo-registration">
								<?php esc_html_e('Enable spamfilters on registration form for WooCommerce.', 'la-sentinelle-antispam'); ?>
							</label><br />
							<span class="setting-description">
								<?php
								esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to the register forms in WooCommerce, when register forms in WordPress Core are also enabled.', 'la-sentinelle-antispam' );
								echo '<br />';
								esc_html_e( 'Please understand that it will also be enabled on the Checkout page. You will want to be careful and test thoroughly if that works fine in your website, before enabling it. Instead, it is preferred to use cleanup options for accounts under WooCommerce > Settings > Accounts-tab, that is the least destructive option.', 'la-sentinelle-antispam' );
								?>
							</span>
						</p>
					</td>
				</tr><?php
			}

			if ( $plugin === 'wpforms-lite/wpforms.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-wpforms">WPForms Lite</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-wpforms', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-wpforms" id="la_sentinelle-wpforms">
						<label for="la_sentinelle-wpforms">
							<?php esc_html_e('Enable spamfilters for WPForms.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to every WPForms form.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}

			if ( $plugin === 'wp-job-manager/wp-job-manager.php' ) {
				$supported_plugin = true; ?>
				<tr valign="top">
					<th scope="row">
						<label for="la_sentinelle-wpjobmanager">WP Job Manager</label>
					</th>
					<td>
						<input <?php
						if (get_option( 'la_sentinelle-wpjobmanager', 'true') === 'true') {
							echo 'checked="checked"';
						} ?>
						type="checkbox" name="la_sentinelle-wpjobmanager" id="la_sentinelle-wpjobmanager">
						<label for="la_sentinelle-wpjobmanager">
							<?php esc_html_e('Enable spamfilters for WP Job Manager.', 'la-sentinelle-antispam'); ?>
						</label><br />
						<span class="setting-description">
							<?php
							esc_html_e( 'Enabling this setting will add La Sentinelle antispam fields to the submit job form of WP Job Manager if registration is enabled.', 'la-sentinelle-antispam' ); echo '<br />';
							?>
						</span>
					</td>
				</tr><?php
			}
		}

		if ( $supported_plugin === false ) { ?>
				<tr valign="top">
					<th scope="row">
						<?php esc_html_e('No Plugins', 'la-sentinelle-antispam'); ?>
					</th>
					<td>
						<span class="setting-description">
							<?php
							esc_html_e( 'There are no plugins installed that use the antispam features of La Sentinelle.', 'la-sentinelle-antispam' );
							?>
						</span>
					</td>
				</tr><?php
		}
		?>

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
