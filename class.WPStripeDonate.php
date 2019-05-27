<?php

namespace WPStripeDonate;

use Stripe\Charge;
use Stripe\Stripe;


Class WPStripeDonate {


    protected $api_key;
    protected $api_secret;
    protected $plugins_settings;

    public function __construct(){

        require_once('vendor/autoload.php');



        $this->plugins_settings = get_option('wpsd_settings');
        $this->api_key = $this->plugins_settings['stripe_key'];
        $this->api_secret = $this->plugins_settings['stripe_secret'];

        /* Action hooks with corresponding callback function placed in order of wordPress admin page request */

        /* with admin_init hook, register plugin settings which includes registering a section where settings will get added */
        add_action('admin_init', array( $this, 'register_plugin_settings'));
        /* with admin_menu hook,  */
        add_action('admin_menu', array( $this, 'add_to_admin_menu'));
        /* Add links to the settings page in plugins*/
        add_filter( 'plugin_action_links_' . WPSD_PLUGINS , array($this, 'plugin_action_links' ));
        /* Load jquery if not loaded already */
        add_action( 'wp_enqueue_scripts', array( $this,'enqueue_scripts') );
        /* register stripe donation form shortcode */
        add_shortcode('wpStripeDonate', array( $this, 'shortcode'));
        // Register style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );

        /* Handle stripe donation form submission */
        add_action('admin_post_handle_stripe_donation', array( $this, 'handle_stripe_donation'));


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


    public function shortcode(){
        wp_enqueue_style( 'wpsd-form-styles' );
        wp_enqueue_script ( 'wpsd-jquery');
        ob_start();
        include_once('public/shortcode.php');
        return ob_get_clean();

    }

    public function enqueue_scripts(){
        if( ! is_admin() ) {

        }
    }

    public function handle_stripe_donation(){

        if( !isset( $_POST['handle_stripe_donation_nonce'])
            || !wp_verify_nonce($_POST['handle_stripe_donation_nonce'], 'handle_stripe_donation') ){
            print 'Sorry, your nonce did not verify.';
            exit;
        }


        //die(var_dump($_POST));

        Stripe::setApiKey($this->api_secret);
        $charge = Charge::create(array('amount' => $_POST['amount'], 'currency' => $_POST['currency_code'], 'source' => $_POST['token']));
        die(var_dump($charge));


    }

    /**
	 * Register and enqueue style sheet.
	 */
	public function register_plugin_styles() {
		wp_register_style( 'wpsd-form-styles', WPSD_URL .'public/css/style.css' );
		wp_register_script( 'wpsd-jquery', 'https://code.jquery.com/jquery-3.4.1.slim.min.js' );

	}

	public function plugin_action_links($links){
	    $setting_page_links  = array(
	        '<a href="' . admin_url('admin.php?page=wp-stripe-donation') .'"> Settings <a/>',
        );

	    return array_merge($links, $setting_page_links);
    }


}