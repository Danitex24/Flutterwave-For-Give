<?php
/**
 * Adding a Settings Page for Your Custom Payment Gateway
 * @since 1.0.0
 * 
 /

 /**
 * Register Section for Flutterwave Payment Gateway Settings.
 *
 * @param array $sections List of payment gateway sections.
 *
 * @since 1.0.0
 *
 * @return array
 */

// register flutterwave payment gateway sections
 function flutterwave_for_give_register_payment_gateway_sections( $sections ) {

    // `flutterwave-settings` is the name/slug of the payment gateway section.
 	$sections['flutterwave-settings'] = __( 'Flutterwave', 'flutterwave-for-give' );

 	return $sections;
 }

 add_filter( 'give_get_sections_gateways', 'flutterwave_for_give_register_payment_gateway_sections' );
