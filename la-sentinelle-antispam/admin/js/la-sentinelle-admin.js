/*
 * JavaScript for the settingspage of La Sentinelle antispam.
 */


/*
 * Settings Page Tabs.
 */
jQuery(document).ready(function($) {

	/* Select the right tab on the options page */
	jQuery( '.lasent-nav-tab-wrapper a' ).on('click', function() {
		jQuery( 'form.la_sentinelle_settingspage' ).removeClass( 'active' );
		jQuery( '.lasent-nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );

		var rel = jQuery( this ).attr('rel');
		jQuery( '.' + rel ).addClass( 'active' );
		jQuery( this ).addClass( 'nav-tab-active' );

		return false;
	});

});
