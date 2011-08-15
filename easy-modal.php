<?php
/*
Plugin Name: Easy Modal
Plugin URI: http://wizardinternetsolutions.com/plugins/easy-modal/
Description: Easy Modal allows you to easily add just about any shortcodes or other content into a modal window. This includes forms such as CF7.
Author: Wizard Internet Solutions
Version: 1.0.3
Author URI: http://wizardinternetsolutions.com
*/
$pluginDIR = PLUGINDIR.'/'. dirname( plugin_basename(__FILE__));
$pluginFILE = __FILE__;
if ( ! function_exists( 'get_plugin_data' ) )
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$plugininfo=get_plugin_data($pluginFILE);
$cur_ver = $plugininfo['Version'];


require_once('includes/easy-modal.class.php');
$eModal = new easy_modal;

add_action( 'wp_ajax_nopriv_eModal_Form', 'easy_modal_new_form' );
add_action( 'wp_ajax_eModal_Form', 'easy_modal_new_form' );
function easy_modal_new_form(){
	global $eModal;
	foreach ($_POST as $key => $value) {
		$_POST[$key] = mysql_real_escape_string($value);
	}
	extract($_POST);
	echo $eModal->display_emodal_option($modalId);
    exit;
}
add_action( 'wp_ajax_nopriv_eModal_Delete_Modal', 'easy_modal_delete_modal' );
add_action( 'wp_ajax_eModal_Delete_Modal', 'easy_modal_delete_modal' );
function easy_modal_delete_modal(){
	global $eModal;
	foreach ($_POST as $key => $value) {
		$_POST[$key] = mysql_real_escape_string($value);
	}
	extract($_POST);
	if($eModal->deleteModal($modalId)) {
		die('Deleted');
	} else {
	die('0');
	}
    exit;
}

add_action('wp_print_styles', 'easy_modal_styles');
add_action( 'admin_init', 'easy_modal_styles' );
function easy_modal_styles()
{
	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
	if (!is_admin())	{
		wp_enqueue_style('easy-modal-theme', $em_plugin_url.'/themes/default/styles.css');
	} else {
		wp_enqueue_style('easy-modal-admin-style', $em_plugin_url.'/css/easy-modal-admin.css');
		//wp_enqueue_style('jquery-colorpicker', $em_plugin_url.'/css/colorpicker.css');
		wp_enqueue_style('jquery-ui-theme', $em_plugin_url.'/css/jquery-ui-1.8.14.custom.css');
	}	
}





function js_localize($name, $vars) {
    ?>
    <script type='text/javascript'>
    /* <![CDATA[ */
    var <?php echo $name; ?> = 
    <?php 
    require_once(ABSPATH . '/wp-includes/class-json.php');
        $wp_json = new Services_JSON();
        echo stripslashes($wp_json->encodeUnsafe($vars)); 
    ?>;
    /* ]]> */
    </script>
<?php
}


add_action('wp_print_scripts', 'easy_modal_scripts');
add_action( 'admin_init', 'easy_modal_scripts' );
function easy_modal_scripts(){
	global $eModal;
	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );
	if (!is_admin())	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('easy-modal-script', $em_plugin_url.'/js/easy-modal.js', array('jquery'));
		$settings = $eModal->enqueue_settings();
		$data = array( 'ajaxurl' => $em_plugin_url.'/ajax/content.php' , 'settings' => $settings );
		js_localize('easymodal',$data);
//$settings = '{"1": { "triggerOpen": { "click": true }, "requestData": { "modalId": 1, "action": "easy_modal" } }, "2": { "triggerOpen": { "dblclick": true }, "requestData": { "modalId": 2, "action": "easy_modal" } } }';
		//wp_localize_script( 'easy-modal-script', 'easymodal', $data );
	} else {
		wp_enqueue_script(array('jquery','jquery-ui-core','jquery-ui-tabs'));
		//wp_enqueue_script('jquery-colorpicker', $em_plugin_url.'/js/colorpicker.js',  array('jquery'));
	}
}




// if both logged in and not logged in users can send this AJAX request,
// add both of these actions, otherwise add only the appropriate one
//add_action( 'wp_ajax_nopriv_easy_modal', 'easy_modal_ajax' );
//add_action( 'wp_ajax_easy_modal', 'easy_modal_ajax' );
 
function easy_modal_ajax() {
    // get the submitted parameters
	foreach ($_POST as $key => $value) {
		$_POST[$key] = mysql_real_escape_string($value);
	}
	extract($_POST);
 
    global $eModal, $post;
	$settings = $eModal->getAdminOptions($modalId);?>
    
    <h1 class='eM-title'><?php echo $settings['title'] ?></h1><?php 
		echo apply_filters('the_content',do_shortcode($settings['content']));

    // IMPORTANT: don't forget to "exit"
	
    exit;
}






//Initialize the admin panel
add_action('admin_menu', 'easy_modal_ap');
if (!function_exists("easy_modal_ap")) {
	function easy_modal_ap() {
		global $eModal;
		if (!isset($eModal)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('Easy Modal', 'Easy Modal', 10, basename(__FILE__), array(&$eModal, 'printAdminPage'));
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















/* Initialize i18n Support
add_action( 'init', 'easy_modal_i18n' );
if(!function_exists(easy_modal_i18n)){
	function easy_modal_i18n() {	
		load_plugin_textdomain( 'easy-modal', false, 'easy-modal/languages' );
	}
} */









register_activation_hook(__FILE__, array(&$eModal, 'init'));
?>