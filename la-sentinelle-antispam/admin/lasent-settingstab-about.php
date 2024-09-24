<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * About tab of the Settings page.
 */
function la_sentinelle_settingstab_about() {
	?>
	<table class="form-table">
		<tbody>

		<tr valign="top">
			<th><?php esc_html_e('Statistics', 'la-sentinelle-antispam'); ?></th>
			<td>
				<p><?php
					$active_plugins = get_option('active_plugins');

					echo '<b>' . esc_html__('WordPress forms', 'la-sentinelle-antispam') . '</b><br />';

					$wpcomments_blocked = (int) la_sentinelle_get_statistic_blocked( 'wpcomments' );
					/* translators: %d is a counter for comments blocked */
					printf( _n( '%d spam comment was blocked.', '%d spam comments were blocked.', $wpcomments_blocked, 'la-sentinelle-antispam' ), $wpcomments_blocked );
					echo '<br />';

					$wplogin_blocked = (int) la_sentinelle_get_statistic_blocked( 'wplogin' );
					/* translators: %d is a counter for login tries blocked */
					printf( _n( '%d login try was blocked.', '%d login tries were blocked.', $wplogin_blocked, 'la-sentinelle-antispam' ), $wplogin_blocked );
					echo '<br />';

					$wpregister_blocked = (int) la_sentinelle_get_statistic_blocked( 'wpregister' );
					/* translators: %d is a counter for register tries blocked */
					printf( _n( '%d register try was blocked.', '%d register tries were blocked.', $wpregister_blocked, 'la-sentinelle-antispam' ), $wpregister_blocked );
					echo '<br />';

					$wppassword_blocked = (int) la_sentinelle_get_statistic_blocked( 'wppassword' );
					/* translators: %d is a counter for password reset tries blocked */
					printf( _n( '%d password reset try was blocked.', '%d password reset tries were blocked.', $wppassword_blocked, 'la-sentinelle-antispam' ), $wppassword_blocked );
					echo '<br />';

					if ( in_array( 'caldera-forms/caldera-core.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Caldera Forms', 'la-sentinelle-antispam') . '</b><br />';

						$caldera_blocked = (int) la_sentinelle_get_statistic_blocked( 'caldera' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $caldera_blocked, 'la-sentinelle-antispam' ), $caldera_blocked );
						echo '<br />';
					}

					if ( in_array( 'contact-form-7/wp-contact-form-7.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Contact Form 7', 'la-sentinelle-antispam') . '</b><br />';

						$cf7_blocked = (int) la_sentinelle_get_statistic_blocked( 'cf7' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $cf7_blocked, 'la-sentinelle-antispam' ), $cf7_blocked );
						echo '<br />';
					}

					if ( in_array( 'everest-forms/everest-forms.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Everest Forms', 'la-sentinelle-antispam') . '</b><br />';

						$everest_blocked = (int) la_sentinelle_get_statistic_blocked( 'everest' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $everest_blocked, 'la-sentinelle-antispam' ), $everest_blocked );
						echo '<br />';
					}

					if ( in_array( 'formidable/formidable.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Formidable', 'la-sentinelle-antispam') . '</b><br />';

						$formidable_blocked = (int) la_sentinelle_get_statistic_blocked( 'formidable' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $formidable_blocked, 'la-sentinelle-antispam' ), $formidable_blocked );
						echo '<br />';
					}

					if ( in_array( 'forminator/forminator.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Forminator', 'la-sentinelle-antispam') . '</b><br />';

						$forminator_blocked = (int) la_sentinelle_get_statistic_blocked( 'forminator' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $forminator_blocked, 'la-sentinelle-antispam' ), $forminator_blocked );
						echo '<br />';
					}

					if ( in_array( 'newsletter-optin-box/noptin.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('Newsletter Optin Box (noptin)', 'la-sentinelle-antispam') . '</b><br />';

						$noptin_blocked = (int) la_sentinelle_get_statistic_blocked( 'noptin' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $noptin_blocked, 'la-sentinelle-antispam' ), $noptin_blocked );
						echo '<br />';
					}

					if ( in_array( 'wpforms-lite/wpforms.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('WPForms Lite', 'la-sentinelle-antispam') . '</b><br />';

						$wpforms_blocked = (int) la_sentinelle_get_statistic_blocked( 'wpforms' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $wpforms_blocked, 'la-sentinelle-antispam' ), $wpforms_blocked );
						echo '<br />';
					}

					if ( in_array( 'wp-job-manager/wp-job-manager.php', $active_plugins, true ) ) {
						echo '<br /><b>' . esc_html__('WP Job Manager', 'la-sentinelle-antispam') . '</b><br />';

						$wpjobmanager_blocked = (int) la_sentinelle_get_statistic_blocked( 'wpjobmanager' );
						/* translators: %d is a counter for form submissions blocked */
						printf( _n( '%d form submission was blocked.', '%d form submissions were blocked.', $wpjobmanager_blocked, 'la-sentinelle-antispam' ), $wpjobmanager_blocked );
						echo '<br />';
					}
					?>
				</p>
			</td>
		</tr>

		<tr valign="top">
			<th><?php esc_html_e('Support', 'la-sentinelle-antispam'); ?></th>
			<td>
				<p><?php
					$support = '<a href="https://wordpress.org/support/plugin/la-sentinelle-antispam" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'If you have a problem or a feature request, please post it on the %1$ssupport forum at wordpress.org%2$s.', 'la-sentinelle-antispam' ), $support, '</a>' ); ?>
					<?php esc_html_e('I will do my best to respond as soon as possible.', 'la-sentinelle-antispam'); ?><br />
					<?php esc_html_e('If you send me an email, I will not reply. Please use the support forum.', 'la-sentinelle-antispam'); ?>
				</p>
			</td>
		</tr>

		<tr valign="top">
			<th><?php esc_html_e('Review', 'la-sentinelle-antispam'); ?></th>
			<td>
				<p><?php
					$review = '<a href="https://wordpress.org/support/view/plugin-reviews/la-sentinelle-antispam?rate=5#postform" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'If this plugin has any value to you, then please leave a review at %1$sthe plugin page%2$s at wordpress.org.', 'la-sentinelle-antispam' ), $review, '</a>' ); ?>
				</p>
			</td>
		</tr>

		<tr valign="top">
			<th><?php esc_html_e('Translations', 'la-sentinelle-antispam'); ?></th>
			<td>
				<p><?php
					$link = '<a href="https://translate.wordpress.org/projects/wp-plugins/la-sentinelle-antispam" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'Translations can be added very easily through %1$sGlotPress%2$s.', 'la-sentinelle-antispam' ), $link, '</a>' );
					echo '<br />';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'You can start translating strings there for your locale. They need to be validated though, so if there is no validator yet, and you want to apply for being validator (PTE), please post it on the %1$ssupport forum%1$s.', 'la-sentinelle-antispam' ), $support, '</a>' );
					echo '<br />';
					$make = '<a href="https://make.wordpress.org/polyglots/" target="_blank">';
					/* translators: %1$s and %2$s is a link */
					printf( esc_html__( 'I will make a request on %1$smake/polyglots%2$s to have you added as validator for this plugin/locale.', 'la-sentinelle-antispam' ), $make, '</a>' ); ?>
				</p>
			</td>
		</tr>

		</tbody>
	</table>

	<?php
}
