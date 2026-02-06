/*
 * JavaScript for La Sentinelle antispam.
 *

Copyright 2018 - 2026  Marcel Pol  (email: marcel@timelord.nl)

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
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/


/*
 * Mangle data for the honeypot.
 *
 * @since 1.0.0
 */
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll('form')?.forEach( function(form) {

		var honeypot  = la_sentinelle_frontend_script.honeypot;
		var honeypot2 = la_sentinelle_frontend_script.honeypot2;

		var honeypot_val = parseInt( form.querySelector(`input.${honeypot}`)?.value, 10 );
		var honeypot2_val = parseInt( form.querySelector(`input.${honeypot2}`)?.value, 10 );

		if ( ! isNaN( honeypot_val ) && (typeof honeypot_val != "undefined") && (typeof honeypot2_val != "undefined") ) {
			la_sentinelle_honeypot( form );
		}
	});

	// Hook into this.reset for Contact Form 7 refill.
	document.querySelector('form.wpcf7-form')?.addEventListener('reset', function() {
		var form = this;
		setTimeout(function() {
			la_sentinelle_honeypot( form );
		}, 500 );
	});

});

function la_sentinelle_honeypot( form ) {

	var honeypot  = la_sentinelle_frontend_script.honeypot;
	var honeypot2 = la_sentinelle_frontend_script.honeypot2;

	var honeypot_val = parseInt( form.querySelector(`input.${honeypot}`)?.value, 10 );
	var honeypot2_val = parseInt( form.querySelector(`input.${honeypot2}`)?.value, 10 );

	if ( ! isNaN( honeypot_val ) && (typeof honeypot_val != "undefined") && (typeof honeypot2_val != "undefined") ) {
		if ( honeypot_val > 0 ) {
			form.querySelector(`input.${honeypot2}`).value = honeypot_val;
			form.querySelector(`input.${honeypot}`).value = '';
		}
	}
}


/*
 * Mangle data for the form timeout.
 *
 * @since 1.0.0
 */
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll('form')?.forEach( function(form) {

		var timeout  = la_sentinelle_frontend_script.timeout;
		var timeout2 = la_sentinelle_frontend_script.timeout2;

		var timer  = parseInt( form.querySelector(`input.${timeout}`)?.value, 10 );
		var timer2 = parseInt( form.querySelector(`input.${timeout2}`)?.value, 10 );

		if ( ! isNaN( timer ) && ! isNaN( timer2 ) && (typeof timer != "undefined") && (typeof timer2 != "undefined") ) {

			// Use setTimeout multiple times to avoid refill by contact form 7 when caching is used.
			for ( var counter = 0; counter < 20; counter++ ) {
				var timecounter = ( counter * 500 );
				setTimeout(function() {
					la_sentinelle_timeout( form );
				}, timecounter );
			}

		}
	});

	// Hook into this.reset for Contact Form 7 refill.
	document.querySelector('form.wpcf7-form')?.addEventListener('reset', function() {
		var form = this;
		setTimeout(function() {
			la_sentinelle_timeout( form );
		}, 500 );
	});
});

function la_sentinelle_timeout( form ) {

	var timeout  = la_sentinelle_frontend_script.timeout;
	var timeout2 = la_sentinelle_frontend_script.timeout2;

	var timer  = parseInt( form.querySelector(`input.${timeout}`)?.value, 10 );
	var timer2 = parseInt( form.querySelector(`input.${timeout2}`)?.value, 10 );

	if ( ! isNaN( timer ) && ! isNaN( timer2 ) && (typeof timer != "undefined") && (typeof timer2 != "undefined") ) {

		var timer  = timer - 1;
		var timer2 = timer2 + 1;

		form.querySelector(`input.${timeout}`).value = timer;
		form.querySelector(`input.${timeout2}`).value = timer2;

	}

}


/*
 * AJAX spamfilter.
 *
 * @since 3.0.0
 */
document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll('form')?.forEach( (form) => {
		var ajax2_field = la_sentinelle_frontend_script.ajax2;
		var ajax3_field = la_sentinelle_frontend_script.ajax3;
		var ajax2_val = parseInt( form.querySelector(`input.${ajax2_field}`)?.value, 10 );

		var ajax_enabled = la_sentinelle_frontend_script.ajax_enabled;
		if ( ajax_enabled !== 'true' ) {
			return;
		}

		if ( (typeof ajax2_val !== 'undefined') && ( ! isNaN( ajax2_val ) ) && ajax2_val > 0 ) {

			var ajax3_val = form.querySelector(`input.${ajax3_field}`).value;

			// Set up data to send
			var ajaxurl  = la_sentinelle_frontend_script.ajaxurl;
			var data     = new URLSearchParams({
				action: 'la_sentinelle_ajax',
				ajax2: ajax2_val,
				ajax3: ajax3_val
			});

			var request = new XMLHttpRequest();

			request.open('POST', ajaxurl, true);
			request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');
			request.onload = function () {
				response = this.response.trim();
				// console.log( response ); // debug: should say 'reported' if we got what we wanted.
			};
			request.send(data.toString());

		}
	});
});
