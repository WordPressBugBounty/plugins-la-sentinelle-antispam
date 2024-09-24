<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function la_sentinelle_caldera_forms_init() {

	$la_sentinelle = new CF_La_Sentinelle();

	add_filter( 'caldera_forms_get_field_types', array( $la_sentinelle, 'add_field' ), 25 );

	add_filter( 'caldera_forms_validate_field_la_sentinelle', array( $la_sentinelle, 'handler' ), 10, 3 );

}
add_action( 'caldera_forms_includes_complete', 'la_sentinelle_caldera_forms_init' );




class CF_La_Sentinelle {
	/*
	 * Add field in Caldera Forms
	 *
	 * @since 1.5.0
	 *
	 * @uses "caldera_forms_get_field_types" filter
	 *
	 * @param array $fields Registered fields
	 *
	 * @return array
	 */
	public function add_field( $fields ) {

		$fields['la_sentinelle'] = array(
			'field'       => esc_html__( 'La Sentinelle', 'la-sentinelle-antispam' ),
			'description' => esc_html__( 'La Sentinelle antispam', 'la-sentinelle-antispam' ),
			'file'        => LASENT_DIR . '/forms/caldera-forms/field.php',
			'category'    => esc_html__( 'Special', 'caldera-forms' ),
			'handler'     => array( $this, 'handler' ),
			'capture'     => false,
			'setup'       => array(
				'template'      => LASENT_DIR . '/forms/caldera-forms/config.php',
				'preview'       => LASENT_DIR . '/forms/caldera-forms/preview.php',
				'not_supported' => array(
					'hide_label',
					'caption',
					'required',
				),
			),
			'scripts' => array(),
		);

		return $fields;

	}


	/*
	 * Field handler -- checks for la_sentinelle and verifies it.
	 *
	 * @since 1.5.0
	 *
	 * @param string $value Field value, should be empty
	 * @param array $field Field config
	 * @param array $form Form config
	 *
	 * @return WP_Error|boolean
	 */
	public function handler( $value, $field, $form ) {

		la_sentinelle_check_spamfilters();

		$markers = la_sentinelle_check_scores();
		if ( is_array( $markers ) && ! empty( $markers ) ) {
			la_sentinelle_add_statistic_blocked( 'caldera' );
			la_sentinelle_save_spam_submission( 'caldera-forms', $markers );

			$error_messages = la_sentinelle_get_default_error_messages();
			return new WP_Error( 'error', $error_messages['try_again'] );
		}

		// Do not return a value, that would come into the email content.
		return;

	}

}
