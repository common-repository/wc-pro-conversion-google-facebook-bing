<?php

/*

* Plugin Name: WC Pro Conversion (Google, Facebook, Bing)

* Plugin URI: http://www.egg-baby.com/

* Description: WC Pro Conversion allows you to add Facebook Pixel, Google Conversion and Bing Conversion code into your website for the whole shop, specific categories, or even upto the product level.

* Version: 1.5

* Author: WebComers

* Author URI: http://www.webcomers.com/

*/





// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) {

	exit;

}



class Wc_Initialize_Conversion_Code{

	

	public $plugin_name = 'wc-category-conversion-code';	

	

	function __construct(){				

	 	

		// include function file

		require_once('inc/functions.php');

		require_once('inc/class-wc-cat-cn.php');

		require_once('inc/class-wc-prod-cn.php');

		require_once('inc/class-wc-cat-cn-settings.php');

		

		// CREATE CATEGORY OBJECT

		$wc_cat_cn = new Wc_Category_Conversion_Code;

		

		// --- Actions ------------------------------------------------------------- //

		// Localize our plugin

        add_action( 'init', array($this, 'wc_localization_setup') );

		// include style and script				
		add_action( 'admin_enqueue_scripts', array($this, 'wc_admin_print_scripts' ) );	

		// add fields in add product category page
		add_action( 'product_cat_add_form_fields', array($wc_cat_cn, 'wc_cat_cn_code_add_fields' ) );	

		// save conversion codes fields
		add_action( 'create_product_cat', array($wc_cat_cn, 'wc_cat_cn_code_save_fields' ) );

		// edit conversion codes fields
		add_action( 'edit_product_cat', array($wc_cat_cn, 'wc_cat_cn_code_save_fields' ) );

		// add settings menu
		add_action('admin_menu', array($this, 'register_cat_cn_code_settings_menu'), 99);

		// Edit conversion codes fields
		add_action( 'product_cat_edit_form_fields', array($wc_cat_cn, 'wc_cat_cn_code_edit_fields' ), 10,2 );

		

		// activation hook 
		register_activation_hook( __FILE__, array($this, 'wc_cat_cn_activated') );

		//uninstall hook / delete hook
		register_uninstall_hook( __FILE__, array('Wc_Initialize_Conversion_Code', 'wc_cat_cn_uninstall') );

		// --- FILTERS ------------------------------------------------------------- //

		//add_filter( "plugin_action_links_".$this->plugin_name."", 'wc_cat_cn_code_add_settings_link' );			

	}

	

	/*

	*	SET PRIORITY OPTIONS BY DEFAULT WHEN PLGUIN IS ACTIVATING

	*	@SPECIFIC PRODUCT HIGHER PRIORITY

	* 	@CATEGORY MEDIUM @GENERAL = LOW

	*/

	public function wc_cat_cn_activated(){

	   update_option( '_wc_conversion_code_diplay_option', 'header' );

	}

	
	/*

	*	DELETE ALL PLUGIN DATA WHEN DELETING

	*/
	public function wc_cat_cn_uninstall(){

		delete_metadata('term', 0, '_cat_cn_code', true);

		delete_metadata('post', 0, '_product_cn_code', true);

		delete_option('_woocommerce_coversion_code_priority');

		delete_option('_woocommerce_conversion_default_script');

		delete_option('_wc_conversion_code_diplay_option');

	}

	

	/**

     * Initialize plugin for localization

     *

     * @uses load_plugin_textdomain()

    */

	public function wc_localization_setup(){

		 load_plugin_textdomain( 'wc-cat-cn-code', false, $this->wc_cat_cn_code_plugin_url() . '/languages/' );

	}

	

	/**

     * PLUGIN URL

     *

     */

	public function wc_cat_cn_code_plugin_url() {

		return plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );

	}

	

	 /**

     * Enque all styles and scripts

     * @custom css in assets css folder

	 * @custom js in assets js folder

     */

	function wc_admin_print_scripts(){

		wp_register_script( 'wc-cat-conversion-code-scripts', $this->wc_cat_cn_code_plugin_url().'/assets/js/wc-cat-conversion-code.js' , array( 'jquery' ) );

		wp_enqueue_script( 'wc-cat-conversion-code-scripts' );

		wp_register_style( 'wc-cat-conversion-code-css', $this->wc_cat_cn_code_plugin_url().'/assets/css/wc-cat-conversion-code.css' , false, '1.0.1' );

        wp_enqueue_style( 'wc-cat-conversion-code-css' );

	}

	

	/*

	*	Create settings menu under woocommerce submenus

	*/

	function register_cat_cn_code_settings_menu(){

		  // INITIALIZE SETTINGS PAGE

		 $wc_cat_cn_settings = new Wc_Conversion_Code_Settings;

		 add_submenu_page( 'woocommerce', 'Conversion Code', 'Conversion Code', 'manage_options', 'can-cn-code-settings', array($wc_cat_cn_settings, 'cn_code_settings_callback') );

	}

	

}



// initialize plugin
$wc_initialize_cn_code	= new Wc_Initialize_Conversion_Code;

// load conversion code field for specific products
$wc_product_cn_code		= new Wc_Product_Conversion_Code;



?>