<?php
/**
 * Register Admin Settings.
 *
 * @param array $settings List of admin settings.
 *
 * @since 1.0.0
 *
 * @return array
 */
/**
 * Add Flutterwave settings fields to GiveWP payment gateways.
 */
function flutterwave_for_give_register_payment_gateway_setting_fields( $settings ) {
	switch ( give_get_current_setting_section() ) {
		case 'flutterwave-settings':
		$settings = array(
			array(
				'id'   => 'give_title_flutterwave',
				'type' => 'title',
			),
		);

		$settings[] = array(
			'name'    => __( 'Test Mode', 'flutterwave-for-give' ),
			'type'    => 'checkbox',
			'id'      => 'flutterwave_for_give_test_mode',
			'desc'    => __( 'Enable Test Mode to use the Test API Keys.', 'flutterwave-for-give' ),
			'default' => 'no',
		);

		$settings[] = array(
			'name'     => __( 'Public Key', 'flutterwave-for-give' ),
			'desc'     => __( 'Enter your Flutterwave public key.', 'flutterwave-for-give' ),
			'id'       => 'flutterwave_for_give_public_key_test',
			'type'     => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		);

		$settings[] = array(
			'name'     => __( 'Secret Key', 'flutterwave-for-give' ),
			'desc'     => __( 'Enter your Flutterwave secret key.', 'flutterwave-for-give' ),
			'id'       => 'flutterwave_for_give_secret_key_test',
			'type'     => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		);

		$settings[] = array(
			'name'    => __( 'Live Mode', 'flutterwave-for-give' ),
			'type'    => 'checkbox',
			'id'      => 'flutterwave_for_give_live_mode',
			'desc'    => __( 'Enable Live Mode to use the Live API Keys.', 'flutterwave-for-give' ),
			'default' => 'no',
		);

		$settings[] = array(
			'name'     => __( 'Public Key', 'flutterwave-for-give' ),
			'desc'     => __( 'Enter your Flutterwave public key.', 'flutterwave-for-give' ),
			'id'       => 'flutterwave_for_give_public_key_live',
			'type'     => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		);

		$settings[] = array(
			'name'     => __( 'Secret Key', 'flutterwave-for-give' ),
			'desc'     => __( 'Enter your Flutterwave secret key.', 'flutterwave-for-give' ),
			'id'       => 'flutterwave_for_give_secret_key_live',
			'type'     => 'text',
			'sanitize_callback' => 'sanitize_text_field',
		);

		$settings[] = array(
			'id'   => 'give_title_flutterwave',
			'type' => 'sectionend',
		);
		break;
	}

	return $settings;
}
add_filter( 'give_get_settings_gateways', 'flutterwave_for_give_register_payment_gateway_setting_fields' );
