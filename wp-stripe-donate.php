<?php
/*
Plugin Name: WP Stripe Donate
Plugin URI: http://wp_stripe_donate
Description:This plugins allows to make simple donation via stripe payment gateway
Version: 1.0
Author: mashrur
Author URI: http://URI_Of_The_Plugin_Author
License: A "Slug" license name e.g. GPL2
*/



//if this file is called directly abort
if( ! defined ('WPINC') ){
    die;
}

define( 'WPSD_VERSION', '1.0.0' );


register_activation_hook( __FILE__, 'activate_plugin' );
register_deactivation_hook(__FILE__, 'deactivate_plugin');



Class WPStripeDonate {

    protected $api_key;
    protected $api_secret;

    public function __construct(){

        require_once('vendor/autoload.php');

        /* Action hooks with corresponding callback function placed in order of wordPress admin page request */

        /* with admin_init hook, register plugin settings which includes registering a section where settings will get added */
        add_action('admin_init', array( $this, 'register_plugin_settings'));
        /* with admin_menu hook,  */
        add_action('admin_menu', array( $this, 'add_to_admin_menu'));


    }


    public function register_plugin_settings()
    {

        //store all my settings in one options field, as an array, This is usually the recommended way
        register_setting(
            'wpsd_settings', // Option group
            'wpsd_settings' // Option name

        );

        // register a new section in the plugin page
         add_settings_section(
            'wpsd-settings_section', //section id (unique)
            'WP Stripe Donate', //section title or name (to be output on the page)
            array( $this , 'wpsd_settings_section_cbk'), //callback function
            'wp-stripe-donation' //page name (This needs to match the text we gave to the do_settings_sections function call.)
        );

         add_settings_field(
             'wpsd_stripe_key', //field ID (unique)
             'Stripe Key', //title for the field
             array( $this, 'wpsd_output_input_fields_key'), //callback function
             'wp-stripe-donation', //page name (same as the do_settings_sections function call).
             'wpsd-settings_section' //The fifth is the id of the settings section that this goes into (same as the first argument to add_settings_section).
         );

          add_settings_field(
             'wpsd_stripe_secret', //field ID (unique)
             'Stripe Secret', //title for the field
             array( $this, 'wpsd_output_input_fields_secret'), //callback function
             'wp-stripe-donation', //page name (same as the do_settings_sections function call).
             'wpsd-settings_section' //The fifth is the id of the settings section that this goes into (same as the first argument to add_settings_section).
         );

    }

    /* wp stripe donation setting section callback function outputs only section title */
    public  function wpsd_settings_section_cbk(){
        echo '<hr/>';
        echo '<h3>WP Stripe Donate Option </h3>';
    }

    public function wpsd_output_input_fields_key(){

        $options = get_option('wpsd_settings');
        echo "<input class='regular-text code' id='wpsd-stripe-key' name='wpsd_settings[stripe_key]'  type='text' value='{$options['stripe_key']}' />";

    }

    public function wpsd_output_input_fields_secret(){

        $options = get_option('wpsd_settings');
        echo "<input class='regular-text code' id='wpsd-stripe-secret' name='wpsd_settings[stripe_secret]' type='text' value='{$options['stripe_secret']}' />";

    }


    /* create admin menu */
    public function add_to_admin_menu(){

        add_menu_page(
            'WP Stripe Donation',
            'WP Stripe Donation',
            'manage_options',
            'wp-stripe-donation',
            array($this, 'config_page')

        );

    }


    /* admin menu callback function create config_page */
    public function config_page() {

        include_once('admin/view.php');
    }




}



new WPStripeDonate();






