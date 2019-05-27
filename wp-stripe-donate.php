<?php
/*
Plugin Name: WP Stripe Donate
Plugin URI: http://wp_stripe_donate
Description:This plugins allows to make simple donation via stripe payment gateway
Version: 1.0
Author: Mashrur Chowdhury
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/

namespace WPStripeDonate;



//if this file is called directly abort
if( ! defined ('WPINC') ){
    die;
}



define( 'WPSD_VERSION', '1.0.0' );
define( 'WPSD_PLUGINS', plugin_basename( __FILE__ ) );
define( 'WPSD_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPSD_URL', plugin_dir_url( __FILE__ ) );



register_activation_hook( __FILE__, 'activate_plugin' );
register_deactivation_hook(__FILE__, 'deactivate_plugin');

require_once( WPSD_PATH . 'class.WPStripeDonate.php' );



new WPStripeDonate();






