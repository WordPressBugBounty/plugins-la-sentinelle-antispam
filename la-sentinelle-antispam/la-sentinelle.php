<?php
/*
Plugin Name: La Sentinelle antispam
Plugin URI: https://wordpress.org/plugins/la-sentinelle-antispam/
Description: Feel safe knowing that your website is safe from spam. La Sentinelle will guard your WordPress website against spam in a simple and effective way.
Version: 3.1.0
Author: Marcel Pol
Author URI: https://timelord.nl
License: GPLv2 or later
Text Domain: la-sentinelle-antispam
Domain Path: /lang/



Copyright 2018 - 2024  Marcel Pol  (marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// Plugin Version
define('LASENT_VER', '3.1.0');


/*
 * Todo:
 *
 * - Support and test bbPress and BuddyPress.
 * - Add manually moved slider for when spammers start using JavaScript (like Ali Express).
 * - Borrow cookie ideas from https://wordpress.org/plugins/spam-destroyer/
 *
 *
 * Not supported plugins:
 *
 * - Ninja Forms
 * - Gravity Forms (contact their support?)
 *
 *
 * Nodo:
 *
 * - Add option to only validate on non-loggedin users: Give everyone the same experience. Current situation is better in case of a problem.
 *
 */


/*
 * Definitions
 */
define('LASENT_FOLDER', plugin_basename(dirname(__FILE__)));
define('LASENT_DIR', WP_PLUGIN_DIR . '/' . LASENT_FOLDER);
define('LASENT_URL', plugins_url( '/', __FILE__ ));


// Functions for the spamfilters
require_once LASENT_DIR . '/spamfilters/lasent-check-spamfilters.php';
require_once LASENT_DIR . '/spamfilters/lasent-get-spamfilters.php';
require_once LASENT_DIR . '/spamfilters/lasent-ajax.php';
require_once LASENT_DIR . '/spamfilters/lasent-honeypot.php';
require_once LASENT_DIR . '/spamfilters/lasent-nonce.php';
require_once LASENT_DIR . '/spamfilters/lasent-stop-forum-spam.php';
require_once LASENT_DIR . '/spamfilters/lasent-timeout.php';
require_once LASENT_DIR . '/spamfilters/lasent-webgl.php';

// Functions for the forms
require_once LASENT_DIR . '/forms/lasent-wordpress-comment-form.php';
require_once LASENT_DIR . '/forms/lasent-wordpress-login-form.php';
require_once LASENT_DIR . '/forms/lasent-wordpress-lost-password-form.php';
require_once LASENT_DIR . '/forms/lasent-wordpress-registration-form.php';
require_once LASENT_DIR . '/forms/lasent-caldera-forms.php';
require_once LASENT_DIR . '/forms/lasent-contact-form-7.php';
require_once LASENT_DIR . '/forms/lasent-everest.php';
require_once LASENT_DIR . '/forms/lasent-formidable.php';
require_once LASENT_DIR . '/forms/lasent-forminator.php';
require_once LASENT_DIR . '/forms/lasent-newsletter-optin-box.php';
require_once LASENT_DIR . '/forms/lasent-ultimate-member.php';
require_once LASENT_DIR . '/forms/lasent-wpforms-lite.php';
require_once LASENT_DIR . '/forms/lasent-wpjobmanager.php';

// Functions and pages for the backend
if ( is_admin() ) {
	require_once LASENT_DIR . '/admin/lasent-admin-hooks.php';
	require_once LASENT_DIR . '/admin/lasent-settingspage-formupdate.php';
	require_once LASENT_DIR . '/admin/lasent-settingspage.php';
	require_once LASENT_DIR . '/admin/lasent-settingstab-about.php';
	require_once LASENT_DIR . '/admin/lasent-settingstab-misc.php';
	require_once LASENT_DIR . '/admin/lasent-settingstab-forms.php';
	require_once LASENT_DIR . '/admin/lasent-settingstab-spamfilters.php';
	// Later because of priority in settings menu.
	require_once LASENT_DIR . '/admin/lasent-page-plugin-log.php';
}

// General Functions
require_once LASENT_DIR . '/functions/lasent-fields.php';
require_once LASENT_DIR . '/functions/lasent-plugin-log.php';
require_once LASENT_DIR . '/functions/lasent-remove-spam.php';
require_once LASENT_DIR . '/functions/lasent-settings.php';
require_once LASENT_DIR . '/functions/lasent-statistics.php';
require_once LASENT_DIR . '/functions/lasent-user-ip.php';

// General Hooks
require_once LASENT_DIR . '/la-sentinelle-hooks.php';


/*
 * Trigger an install/upgrade function when the plugin is activated.
 */
function la_sentinelle_activation( $networkwide ) {
	global $wpdb;

	$current_version = get_option( 'la_sentinelle-version' );

	if ( is_multisite() ) {
		$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
		foreach ($blogids as $blog_id) {
			switch_to_blog($blog_id);
			if ( $current_version === false ) {
				la_sentinelle_set_defaults();
			} else if ($current_version !== LASENT_VER) {
				la_sentinelle_set_defaults();
			}
			restore_current_blog();
		}
	} else {
		if ( $current_version === false ) {
			la_sentinelle_set_defaults();
		} else if ($current_version !== LASENT_VER) {
			la_sentinelle_set_defaults();
		}
	}
}
register_activation_hook(__FILE__, 'la_sentinelle_activation');
