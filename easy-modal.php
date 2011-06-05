<?php

/*

Plugin Name: Easy Modal

Plugin URI: http://wizardinternetsolutions.com/plugins/easy-modal/

Description: Easy Modal allows you to easily add just about any shortcodes or other content into a modal window. This includes forms such as CF7.

Author: Wizard Internet Solutions

Version: 0.9.0.2

Author URI: http://wizardinternetsolutions.com

*/
require_once('includes/easy-modal.class.php');

$eM = new easy_modal;

add_action('wp_print_styles', 'easy_modal_styles');
add_action( 'admin_init', 'easy_modal_styles' );

function easy_modal_styles()

{

	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

 

	if (!is_admin())	{

		//wp_enqueue_style('easy-modal-style', $em_plugin_url.'/css/easy-modal.css');
		wp_enqueue_style('easy-modal-style', $em_plugin_url.'/css/easy-modal.css.php');

	} else {
		
		//wp_enqueue_style('farbtastic');
		wp_enqueue_style('easy-modal-admin-style', $em_plugin_url.'/css/easy-modal-admin.css');
	
	}

}

add_action('wp_print_scripts', 'easy_modal_scripts');

add_action( 'admin_init', 'easy_modal_styles' );

function easy_modal_scripts()

{

	global $eM;

	$options = $eM->getAdminOptions();

	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

	if (!is_admin())	{

		wp_enqueue_script('jquery');

		wp_enqueue_script('jquery-form', array('jquery'));

		wp_enqueue_script('jquery-simplemodal', $em_plugin_url.'/js/jquery.simplemodal.js', array('jquery'));

		//wp_enqueue_script('easy-modal-script', $em_plugin_url.'/js/easy-modal.js', array('jquery','jquery-simplemodal'));

		wp_enqueue_script('easy-modal-script', $em_plugin_url.'/js/easy-modal.js.php', array('jquery','jquery-simplemodal'));

		wp_localize_script( 'easy-modal-script', 'eMSettings', array(

			'plugin_url' => $em_plugin_url,

			// Overlay Options

			'overlayId' => $options['overlayId'],
			
			'overlayColor' => $options['overlayColor'],

			'overlayCss' => $options['overlayCss'],
			
			'opacity' => $options['opacity'],
			
			'overlayClose' => $options['overlayClose'],
			
			// Container Options
			
			'containerId' => $options['containerId'],

			'autoResize' => $options['autoResize'],

			'autoPosition' => $options['autoPosition'],
			
			'positionX' => $options['positionX'],
			
			'positionY' => $options['positionY'],

			'minHeight' => $options['minHeight'],

			'maxHeight' => $options['maxHeight'],

			'minWidth' => $options['minWidth'],

			'maxWidth' => $options['maxWidth'],

			// Content Options

			'cf7form' => $options['cf7form'],

			

		));

	} else {
		
		wp_enqueue_script( 'farbtastic' );

	}

}


//Initialize the admin panel
add_action('admin_menu', 'easy_modal_ap');

if (!function_exists("easy_modal_ap")) {

	function easy_modal_ap() {

		global $eM;

		if (!isset($eM)) {

			return;

		}

		if (function_exists('add_options_page')) {

			add_options_page('Easy Modal', 'Easy Modal', 10, basename(__FILE__), array(&$eM, 'printAdminPage'));

		}

	}   

}

// Display a Settings link on the main Plugins page

add_filter( 'plugin_action_links', 'easy_modal_plugin_action_links', 10, 2 );

function easy_modal_plugin_action_links( $links, $file ) {

	if ( $file == plugin_basename( __FILE__ ) ) {
		
		$posk_links = '<a href="'.get_admin_url().'options-general.php?page=easy-modal.php">'.__('Settings').'</a>';
		
		// make the 'Settings' link appear first
		
		array_unshift( $links, $posk_links );
		
	}

	return $links;
	
}


// Initialize i18n Support

add_action( 'init', 'easy_modal_i18n' );

if(!function_exists(easy_modal_i18n)){

	function easy_modal_i18n() {	

		load_plugin_textdomain( 'easy-modal', false, 'easy-modal/languages' );

	}

}

register_activation_hook(__FILE__, array(&$eM, 'init'));

?>