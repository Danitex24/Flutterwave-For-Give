<?php 
/**
 * Add currencies for Paystack.
 *
 * @param array $currencies List of currencies.
 *
 * @return array
 */
function flutterwave_give_add_currencies( $currencies ) {
	$add_currencies = array(
		'NGN' => array(
			'admin_label' => sprintf( __( 'Nigerian Naira (%1$s)', 'give' ), '&#8358;' ),
			'symbol'      => '&#8358;',
			'setting'     => array(
				'currency_position'   => 'before',
				'thousands_separator' => ',',
				'decimal_separator'   => '.',
				'number_decimals'     => 2,
			),
		),
		'GHS' => array(
			'admin_label' => sprintf( __( 'Ghana Cedis (%1$s)', 'give' ), 'GHS' ),
			'symbol'      => 'GHS;',
			'setting'     => array(
				'currency_position'   => 'before',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'number_decimals'     => 2,
			),
		),
		'ZAR' => array(
			'admin_label' => sprintf( __( 'South African Rands (%1$s)', 'give' ), 'ZAR' ),
			'symbol'      => 'ZAR;',
			'setting'     => array(
				'currency_position'   => 'before',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'number_decimals'     => 2,
			),
		),
		'KES' => array(
			'admin_label' => sprintf( __( 'Kenyan Shillings (%1$s)', 'give' ), 'KES' ),
			'symbol'      => 'KES;',
			'setting'     => array(
				'currency_position'   => 'before',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'number_decimals'     => 2,
			),
		),
		'USD' => array(
			'admin_label' => sprintf( __( 'US Dollars (%1$s)', 'give' ), 'USD' ),
			'symbol'      => 'USD;',
			'setting'     => array(
				'currency_position'   => 'before',
				'thousands_separator' => '.',
				'decimal_separator'   => ',',
				'number_decimals'     => 2,
			),
		),
	);

	return array_merge( $add_currencies, $currencies );
}
add_filter( 'give_currencies', 'flutterwave_give_add_currencies' );

add_action( 'parse_request', 'handle_api_requests', 0 );