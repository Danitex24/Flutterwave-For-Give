<?php

/**
 * Fired during plugin activation
 *
 * @link  https://app.flutterwave.com
 * @since 1.0.0
 *
 * @package    Flutterwave_Give
 * @subpackage Flutterwave_Give/includes
 */

/**
 * Fired during plugin activation.
 *
 * This function defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Flutterwave_Give
 * @subpackage Flutterwave_Give/includes
 * @author     Daniel Abughdyer <danieladasho@gmail.com>
 */
/**
 * Register payment method. 
 *
 * @since 1.0.0
 *
 * @param array $gateways List of registered gateways.
 *
 * @return array
 */

// register flutterwave payment gateway method
function flutterwave_for_give_register_payment_method( $gateways ) {


  $gateways['fluttwave'] = array(
    'admin_label'    => __( 'Flutterwave - Credit Card', 'flutterwave-for-give' ), // This label will be displayed under Give settings in admin.
    'checkout_label' => __( 'Flutterwave Payment', 'flutterwave-for-give' ), // This label will be displayed on donation form in frontend.
  );
  
  return $gateways;
}

add_filter( 'give_payment_gateways', 'flutterwave_for_give_register_payment_method' );
