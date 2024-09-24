<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/*
 * Adds an option page Settings > La Sentinelle.
 *
 * @since 1.0.0
 */
function la_sentinelle_settingspage_hook() {
	add_options_page( esc_html__('La Sentinelle', 'la-sentinelle-antispam'), esc_html__('La Sentinelle', 'la-sentinelle-antispam'), 'manage_options', 'la-sentinelle.php', 'la_sentinelle_settingspage');
}
add_action( 'admin_menu', 'la_sentinelle_settingspage_hook', 11 );



/*
 * Admin Settings page.
 *
 * @since 1.0.0
 */
function la_sentinelle_settingspage() {

	if ( ! current_user_can('manage_options') ) {
		die(esc_html__('You need a higher level of permission.', 'la-sentinelle-antispam'));
	}

	la_sentinelle_admin_enqueue();

	$messages = '';
	$active_tab = 'la_sentinelle_settingstab_spamfilters';

	if ( isset( $_POST['option_page']) && $_POST['option_page'] === 'la_sentinelle_options' ) {
		if ( isset( $_POST['la_sentinelle_tab'] ) ) {
			$active_tab = sanitize_text_field( $_POST['la_sentinelle_tab'] );
		}

		$messages = la_sentinelle_settingspage_formupdate();

	} ?>

	<div class="wrap la_sentinelle_settingspage_wrapper">

		<div id="icon-la-sentinelle"><br /></div>
		<h1><?php esc_html_e('La Sentinelle antispam', 'la-sentinelle-antispam'); ?></h1>

		<?php
		if ( $messages ) {
			echo '
			<div id="message" class="updated fade notice is-dismissible">' .
				$messages . '
			</div>';
		}

		/* The rel attribute will be the form that becomes active */
		/* Do not use nav but h2, since it is using (in)visible content, not real navigation. */
		?>
		<h2 class="nav-tab-wrapper lasent-nav-tab-wrapper" role="tablist">
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'la_sentinelle_settingstab_spamfilters') { echo 'nav-tab-active';} ?>" rel="la_sentinelle_settingstab_spamfilters"><?php /* translators: Settings page tab */ esc_html_e('Spamfilters', 'la-sentinelle-antispam'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'la_sentinelle_settingstab_forms')       { echo 'nav-tab-active';} ?>" rel="la_sentinelle_settingstab_forms"><?php /* translators: Settings page tab */ esc_html_e('Forms', 'la-sentinelle-antispam'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'la_sentinelle_settingstab_misc')        { echo 'nav-tab-active';} ?>" rel="la_sentinelle_settingstab_misc"><?php /* translators: Settings page tab */ esc_html_e('Misc', 'la-sentinelle-antispam'); ?></a>
			<a href="#" role="tab" class="nav-tab <?php if ($active_tab === 'la_sentinelle_settingstab_about')       { echo 'nav-tab-active';} ?>" rel="la_sentinelle_settingstab_about"><?php /* translators: Settings page tab */ esc_html_e('About', 'la-sentinelle-antispam'); ?></a>
		</h2>

		<form name="la_sentinelle_settingspage" role="tabpanel" class="la_sentinelle_settingspage la_sentinelle_settingstab_spamfilters <?php if ($active_tab === 'la_sentinelle_settingstab_spamfilters') { echo 'active';} ?>" method="post" action="">
			<?php la_sentinelle_settingstab_spamfilters(); ?>
		</form>

		<form name="la_sentinelle_settingspage" role="tabpanel" class="la_sentinelle_settingspage la_sentinelle_settingstab_forms <?php if ($active_tab === 'la_sentinelle_settingstab_forms') { echo 'active';} ?>" method="post" action="">
			<?php la_sentinelle_settingstab_forms(); ?>
		</form>

		<form name="la_sentinelle_settingspage" role="tabpanel" class="la_sentinelle_settingspage la_sentinelle_settingstab_misc <?php if ($active_tab === 'la_sentinelle_settingstab_misc') { echo 'active';} ?>" method="post" action="">
			<?php la_sentinelle_settingstab_misc(); ?>
		</form>

		<form name="la_sentinelle_settingspage" role="tabpanel" class="la_sentinelle_settingspage la_sentinelle_settingstab_about <?php if ($active_tab === 'la_sentinelle_settingstab_about') { echo 'active';} ?>" method="post" action="">
			<?php la_sentinelle_settingstab_about(); ?>
		</form>

	</div> <!-- wrap -->
	<?php
}
