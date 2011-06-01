<?php
/*
Plugin Name: Easy Modal
Plugin URI: http://wizardinternetsolutions.com/plugins/easy-modal/
Description: Easy Modal allows you to easily add just about any shortcodes or other content into a modal window. This includes forms such as CF7 and Fast Secure Contact Form.
Author: Wizard Internet Solutions
Version: 0.9
Author URI: http://wizardinternetsolutions.com
*/

add_action('wp_print_styles', 'easy_modal_styles');

function easy_modal_styles()
{
	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
 
	if (!is_admin())	{
		wp_enqueue_style('easy-modal-style', $em_plugin_url.'/css/easy-modal.css');
	}
}

add_action('wp_print_scripts', 'easy_modal_scripts');

function easy_modal_scripts()
{
	global $eM;
	$options = $eM->getAdminOptions();
	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
 
	if (!is_admin())	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-form', array('jquery'));
		wp_enqueue_script('jquery-simplemodal', $em_plugin_url.'/js/jquery.simplemodal.js', array('jquery'));
		wp_enqueue_script('easy-modal-script', $em_plugin_url.'/js/easy-modal.js', array('jquery','jquery-simplemodal'));
		wp_localize_script( 'easy-modal-script', 'eMSettings', array(
			'plugin_url' => $em_plugin_url,
			'overlayId' => $options['overlayId'],
			'overlayCss' =>  $options['overlayCss'],
			'opacity' => $options['opacity'],
			'containerId' => $options['containerId'],
			'cf7form' => $options['cf7form'],
			
		));
	}
}

/* 

call variables in js


 
mode = WPWallSettings.mode;
*/

class easyModal {

	var $adminOptionsName = "easyModalOptions";

	function easyModal() { //constructor

	}
	
	//Returns an array of admin options
	function getAdminOptions() {
		$easyModalAdminOptions = array(
			'title' => '',
			'content' => '',
			'opacity' => '50',
			'overlayId' => 'eM-overlay',
			'overlayCss' =>  '',
			'containerId' => 'eM-container',
			'cf7form' => false,
		);
		$eMOptions = get_option($this->adminOptionsName);
		if (!empty($eMOptions)) {
			foreach ($eMOptions as $key => $option)
				$easyModalAdminOptions[$key] = $option;
		}
		update_option($this->adminOptionsName, $easyModalAdminOptions);
		return $easyModalAdminOptions;
	}
	
	// Plugin Initialization
	function init() {
		$this->getAdminOptions();
	}
	
	//Prints out the admin page
	function printAdminPage() {
		$eM_Options = $this->getAdminOptions();
		if (isset($_POST['update_eM_settings'])) {
			if (isset($_POST['eM_title'])) {
				$eM_Options['title'] = apply_filters('content_save_pre', $_POST['eM_title']);
			}
			if (isset($_POST['eM_content'])) {
				if(strstr($_POST['eM_content'],'[contact-form')!= NULL){ $eM_Options['cf7form'] = true; }
				else { $eM_Options['cf7form'] = false; }
				$eM_Options['content'] = $_POST['eM_content'];
			}
			if (isset($_POST['eM_overlayId'])) {
				$eM_Options['overlayId'] = $_POST['eM_overlayId'];
			}
			if (isset($_POST['eM_opacity'])) {
				if ($_POST['eM_opacity']>=0 && $_POST['eM_opacity']<=100){
					$eM_Options['opacity'] = $_POST['eM_opacity'];
				}
			}
			if (isset($_POST['eM_overlayCss'])) {
				$eM_Options['overlayCss'] = $_POST['eM_overlayCss'];
			}
			
			if (isset($_POST['eM_containerId'])) {
				$eM_Options['containerId'] = $_POST['eM_containerId'];
			}
			$eM_Options = stripslashes_deep($eM_Options);
			update_option($this->adminOptionsName, $eM_Options); ?>
		<div class="updated"><p><strong><?php _e("Settings Updated.", "easyModal");?></strong></p></div><?php
		} ?>
        <div class=wrap>
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <h2>Easy Modal</h2>
                <h3>Title</h3>
                <p><input type="text" name="eM_title" value="<?php _e($eM_Options['title'], 'easyModal') ?>" /></p>
                <h3 style="display:inline-block;">Modal Content</h3> - <h5 style="display:inline-block;">Can contain shortcodes</h5>
                <textarea name="eM_content" style="width: 80%; height: 100px;"><?php _e($eM_Options['content'], 'easyModal') ?></textarea>
                <h3>Overlay Options</h3>
                <h4>Overlay CSS Id</h4>
                <p><input type="text" name="eM_overlayId" value="<?php _e($eM_Options['overlayId'], 'easyModal') ?>" /></p>
                <h4>Overlay CSS Styles</h4>
                <p><input type="text" name="eM_overlayCss" value="<?php _e($eM_Options['overlayCss'], 'easyModal') ?>" /></p>
                <h4>Opacity</h4>
                <p><input type="text" name="eM_opacity" value="<?php _e($eM_Options['opacity'], 'easyModal') ?>" /></p>
                <h3>Window Options</h3>
                <h4>Container CSS Id</h4>
                <p><input type="text" name="eM_containerId" value="<?php _e($eM_Options['containerId'], 'easyModal') ?>" /></p>
                
                <div class="submit">
                    <input type="submit" name="update_eM_settings" value="<?php _e('Update Settings', 'easyModal') ?>" />
                </div>
            </form>
        </div><?php
	}//End function printAdminPage()
	
}
$eM = new easyModal;
register_activation_hook(__FILE__, array(&$eM, 'init'));

//Initialize the admin panel
if (!function_exists("easyModal_ap")) {
	function easyModal_ap() {
		global $eM;
		if (!isset($eM)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('Easy Modal', 'Easy Modal', 10, basename(__FILE__), array(&$eM, 'printAdminPage'));
		}
	}   
}
add_action('admin_menu', 'easyModal_ap');


?>