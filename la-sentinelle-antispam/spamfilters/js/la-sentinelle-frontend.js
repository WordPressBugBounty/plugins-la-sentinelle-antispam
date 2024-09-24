/*
 * JavaScript for La Sentinelle antispam.
 *

Copyright 2018 - 2024  Marcel Pol  (email: marcel@timelord.nl)

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
jQuery(document).ready(function($) {
	jQuery( 'form' ).each( function( index, form ) {

		var honeypot  = la_sentinelle_frontend_script.honeypot;
		var honeypot2 = la_sentinelle_frontend_script.honeypot2;

		var honeypot_val = parseInt( jQuery( 'input.' + honeypot, form ).val(), 10 );
		var honeypot2_val = parseInt( jQuery( 'input.' + honeypot2, form ).val(), 10 );

		if ( ! isNaN( honeypot_val ) && (typeof honeypot_val != "undefined") && (typeof honeypot2_val != "undefined") ) {
			la_sentinelle_honeypot( form );
		}

	});

	// Hook into this.reset for Contact Form 7.
	jQuery('form.wpcf7-form').on('reset', function() {

		var form = this;
		setTimeout(function() {
				la_sentinelle_honeypot( form );
			}, 500 );

	});
});
function la_sentinelle_honeypot( form ) {

	var honeypot  = la_sentinelle_frontend_script.honeypot;
	var honeypot2 = la_sentinelle_frontend_script.honeypot2;

	var honeypot_val = parseInt( jQuery( 'input.' + honeypot, form ).val(), 10 );
	var honeypot2_val = parseInt( jQuery( 'input.' + honeypot2, form ).val(), 10 );

	if ( ! isNaN( honeypot_val ) && (typeof honeypot_val != "undefined") && (typeof honeypot2_val != "undefined") ) {
		if ( honeypot_val > 0 ) {
			jQuery( 'input.' + honeypot2, form ).val( honeypot_val );
			jQuery( 'input.' + honeypot, form ).val( '' );
		}
	}
}


/*
 * Mangle data for the form timeout.
 *
 * @since 1.0.0
 */
jQuery(document).ready(function($) {
	jQuery( 'form' ).each( function( index, form ) {

		var timeout  = la_sentinelle_frontend_script.timeout;
		var timeout2 = la_sentinelle_frontend_script.timeout2;

		var timer  = parseInt( jQuery( 'input.' + timeout, form ).val(), 10 );
		var timer2 = parseInt( jQuery( 'input.' + timeout2, form ).val(), 10 );

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

	// Hook into this.reset for Contact Form 7.
	jQuery('form.wpcf7-form').on('reset', function() {

		var form = this;
		setTimeout(function() {
				la_sentinelle_timeout( form );
			}, 500 );

	});

});
function la_sentinelle_timeout( form ) {

	var timeout  = la_sentinelle_frontend_script.timeout;
	var timeout2 = la_sentinelle_frontend_script.timeout2;

	var timer  = parseInt( jQuery( 'input.' + timeout, form ).val(), 10 );
	var timer2 = parseInt( jQuery( 'input.' + timeout2, form ).val(), 10 );

	if ( ! isNaN( timer ) && ! isNaN( timer2 ) && (typeof timer != "undefined") && (typeof timer2 != "undefined") ) {

		var timer  = timer - 1;
		var timer2 = timer2 + 1;

		jQuery( 'input.' + timeout, form ).val( timer );
		jQuery( 'input.' + timeout2, form ).val( timer2 );

	}

}


/*
 * AJAX spamfilter.
 *
 * @since 3.0.0
 */
jQuery(document).ready(function($) {
	jQuery( 'form' ).each( function( index, form ) {

		var ajax2_field  = la_sentinelle_frontend_script.ajax2;
		var ajax3_field  = la_sentinelle_frontend_script.ajax3;
		var ajax2_val = parseInt( jQuery( 'input.' + ajax2_field, form ).val(), 10 );

		if ( (typeof ajax2_val !== 'undefined') && ( ! isNaN( ajax2_val ) ) && ajax2_val > 0 ) {

			var ajax3_val  = jQuery( 'input.' + ajax3_field, form ).val();

			// Set up data to send
			var ajaxurl  = la_sentinelle_frontend_script.ajaxurl;
			var data     = {
				action: 'la_sentinelle_ajax',
				ajax2: ajax2_val,
				ajax3: ajax3_val
			};

			jQuery.post( ajaxurl, data, function( response ) {

				response = response.trim();
				//console.log( response ); // debug: should say 'reported' if we got what we wanted.

			});

		}

	});
});


/*
 * Spamfilter with Canvas, Webgl and AJAX.
 *
 * @since 3.0.0
 */
jQuery(document).ready(function($) {
	jQuery( 'form' ).each( function( index, form ) {

		var canvas_element = jQuery('<canvas />').attr({
			id: 'canvas-' + index,
			width: 20,
			height: 20
		});

		var returnvalue = jQuery( 'div.la-sentinelle-container', form ).append( canvas_element );
		if ( typeof returnvalue[0] === "undefined" ) {
			// No sentinelle in this form.
			//console.log( 'undefined' );
		} else {
			//console.log( 'defined' );

			var canvas = document.querySelector( '#canvas-' + index );
			var gl = canvas.getContext( 'webgl' );

			// Only continue if WebGL is available and working
			if (gl === null) {
				alert( 'Unable to initialize WebGL. Your browser or machine may not support it.' );
				return;
			}

			const pixels = new Uint8Array(
				gl.drawingBufferWidth * gl.drawingBufferHeight * 4
			);

			var webgl  = la_sentinelle_frontend_script.webgl;
			var webgl_val = parseInt( jQuery( 'input.' + webgl, form ).val(), 10 );

			if ( (typeof webgl_val != 'undefined') ) {
				if ( webgl_val > 0 ) {
					var honey = webgl_val;
				}
			}

			if ( (typeof honey !== 'undefined') && ( ! isNaN( honey ) ) ) {
				// only one decimal allowed.
				honey = ( honey / 10 );
				honey = Math.round( honey );
				honey = ( honey / 10 );

				/* Step2: Define the geometry and store it in buffer objects */

				var vertices = [-0.5, 0.5, -0.5, -0.5, 0.0, -0.5,];

				// Create a new buffer object
				var vertex_buffer = gl.createBuffer();

				// Bind an empty array buffer to it
				gl.bindBuffer(gl.ARRAY_BUFFER, vertex_buffer);

				// Pass the vertices data to the buffer
				gl.bufferData(gl.ARRAY_BUFFER, new Float32Array(vertices), gl.STATIC_DRAW);

				// Unbind the buffer
				gl.bindBuffer(gl.ARRAY_BUFFER, null);

				/* Step3: Create and compile Shader programs */

				// Vertex shader source code
				var vertCode =
				'attribute vec2 coordinates;' +
				'void main(void) {' + ' gl_Position = vec4(coordinates,0.0, 1.0);' + '}';

				//Create a vertex shader object
				var vertShader = gl.createShader(gl.VERTEX_SHADER);

				//Attach vertex shader source code
				gl.shaderSource(vertShader, vertCode);

				//Compile the vertex shader
				gl.compileShader(vertShader);

				//Fragment shader source code
				var fragCode = 'void main(void) {' + 'gl_FragColor = vec4(0.0, 0.0, 0.0, 0.1);' + '}';

				// Create fragment shader object
				var fragShader = gl.createShader(gl.FRAGMENT_SHADER);

				// Attach fragment shader source code
				gl.shaderSource(fragShader, fragCode);

				// Compile the fragment shader
				gl.compileShader(fragShader);

				// Create a shader program object to store combined shader program
				var shaderProgram = gl.createProgram();

				// Attach a vertex shader
				gl.attachShader(shaderProgram, vertShader);

				// Attach a fragment shader
				gl.attachShader(shaderProgram, fragShader);

				// Link both programs
				gl.linkProgram(shaderProgram);

				// Use the combined shader program object
				gl.useProgram(shaderProgram);

				/* Step 4: Associate the shader programs to buffer objects */

				//Bind vertex buffer object
				gl.bindBuffer(gl.ARRAY_BUFFER, vertex_buffer);

				//Get the attribute location
				var coord = gl.getAttribLocation(shaderProgram, "coordinates");

				//point an attribute to the currently bound VBO
				gl.vertexAttribPointer(coord, 2, gl.FLOAT, false, 0, 0);

				//Enable the attribute
				gl.enableVertexAttribArray(coord);

				/* Step5: Drawing the required object (triangle) */

				// Clear the canvas
				gl.clearColor(honey, honey, honey, 0.9);

				// Enable the depth test
				gl.enable(gl.DEPTH_TEST);

				// Clear the color buffer bit
				gl.clear(gl.COLOR_BUFFER_BIT);

				// Set the view port
				gl.viewport(0,0,canvas.width,canvas.height);

				// Draw the triangle
				gl.drawArrays(gl.TRIANGLES, 0, 3);

				gl.readPixels(
					0,
					0,
					gl.drawingBufferWidth,
					gl.drawingBufferHeight,
					gl.RGBA,
					gl.UNSIGNED_BYTE,
					pixels
				); // Uint8Array

				var webgl2_val = pixels[0];
				var webgl2_field  = la_sentinelle_frontend_script.webgl2;
				var webgl3_field  = la_sentinelle_frontend_script.webgl3;
				jQuery( 'input.' + webgl2_field, form ).val( webgl2_val );
				var webgl3_val  = jQuery( 'input.' + webgl3_field, form ).val();

				// Set up data to send
				var ajaxurl  = la_sentinelle_frontend_script.ajaxurl;
				var data     = {
					action: 'la_sentinelle_webgl',
					webgl2: webgl2_val,
					webgl3: webgl3_val
				};

				jQuery.post( ajaxurl, data, function( response ) {

					response = response.trim();
					if ( response === 'reported' ) { // We got what we wanted
						//console.log( 'reported' );
					} else {
						//console.log( 'not reported' );
					}

				});

			}

		}

	});
});
