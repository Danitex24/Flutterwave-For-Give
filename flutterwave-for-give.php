<?php

/**
 * The plugin root file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://app.flutterwave.com
 * @since   1.0.0
 * @package Flutterwave_Give
 *
 * @wordpress-plugin
 * Plugin Name:       Flutterwave Payments for Give
 * Plugin URI:        http://wordpress.org/plugins/flutterwave-give
 * Description:       Flutterwave Give integration for accepting payments via card, bank accounts, USSD.
 * Version:           1.0.0
 * Author:            Daniel Abughdyer
 * Author URI:        https://www.codeable.io/developers/daniel-abughdyer
 * License:           GNU GPL v3.0
 * License URI:       http://www.gnu.org/licenses
 * Text Domain:       flutterwave-give
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

//add admin settings link to our plugin
add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'plugin_links' );

function plugin_links( $links ) {

  $links = array_merge( array(

    '<a href="' . admin_url('edit.php?post_type=give_forms&page=give-settings&tab=gateways&section=flutterwave-settings') . '">' . __( 'Settings', 'textdomain' ) ), $links );
  
  return $links;
}


//add our plugin files
require_once(plugin_dir_path(__FILE__).'/includes/flutterwave-give-activator.php');
require_once(plugin_dir_path(__FILE__).'/includes/flutterwave-give-gateway-settings.php');
require_once(plugin_dir_path(__FILE__).'/includes/flutterwave-give-gateway-fields.php');
require_once(plugin_dir_path(__FILE__).'/includes/flutterwave-give-submit-donation.php');