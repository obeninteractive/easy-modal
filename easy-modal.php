<?php

/*

Plugin Name: Easy Modal

Plugin URI: http://wizardinternetsolutions.com/plugins/easy-modal/

Description: Easy Modal allows you to easily add just about any shortcodes or other content into a modal window. This includes forms such as CF7.

Author: Wizard Internet Solutions

Version: 0.9.0.2

Author URI: http://wizardinternetsolutions.com

*/



add_action('wp_print_styles', 'easy_modal_styles');



function easy_modal_styles()

{

	$em_plugin_url = trailingslashit( get_bloginfo('wpurl') ).PLUGINDIR.'/'. dirname( plugin_basename(__FILE__) );

 

	if (!is_admin())	{

		wp_enqueue_style('easy-modal-style', $em_plugin_url.'/css/easy-modal.css');

	} else {
		
		wp_enqueue_style('farbtastic');
		
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

			// Overlay Options

			'overlayId' => $options['overlayId'],
			
			'overlayColor' => $options['overlayColor'],

			'overlayCss' =>  $options['overlayCss'],

			'opacity' => $options['opacity'],
			
			// Container Options
			
			'containerId' => $options['containerId'],

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


class easy_modal {

	var $adminOptionsName = "easy_modalOptions";	

	//Returns an array of admin options

	function getAdminOptions() {

		$easy_modalAdminOptions = array(

			'title' => '',

			'content' => '',

			'overlayId' => 'eM-overlay',
			
			'overlayColor' => '#000000',
			
			'opacity' => '50',

			'overlayCss' =>  '',

			'containerId' => 'eM-container',

			'minHeight' => '',

			'maxHeight' => '',

			'minWidth' => '',

			'maxWidth' => '',

			'cf7form' => false,

		);

		$eMOptions = get_option($this->adminOptionsName);

		if (!empty($eMOptions)) {

			foreach ($eMOptions as $key => $option)

				$easy_modalAdminOptions[$key] = $option;

		}

		update_option($this->adminOptionsName, $easy_modalAdminOptions);

		return $easy_modalAdminOptions;

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
			// Validate Overlay Options
			if (isset($_POST['eM_overlayId'])) {

				$eM_Options['overlayId'] = $_POST['eM_overlayId'];

			}

			if (isset($_POST['eM_overlayColor'])) {
				
				//hex color is valid
				
				if(preg_match('/^#[a-f0-9]{6}$/i', $_POST['eM_overlayColor'])){
					
					$eM_Options['overlayColor'] = $_POST['eM_overlayColor'];
					
				}

			}
			
			if (isset($_POST['eM_opacity'])) {

				if ($_POST['eM_opacity']>=0 && $_POST['eM_opacity']<=100){

					$eM_Options['opacity'] = $_POST['eM_opacity'];

				}

			}

			if (isset($_POST['eM_overlayCss'])) {

				$eM_Options['overlayCss'] = $_POST['eM_overlayCss'];

			}

			if (isset($_POST['eM_overlayClose'])) {

				$eM_Options['overlayClose'] = $_POST['eM_overlayClose'];

			}
			// Validate Container Options
			if (isset($_POST['eM_containerId'])) {

				$eM_Options['containerId'] = $_POST['eM_containerId'];

			}

			if (isset($_POST['eM_minHeight'])) {

				$eM_Options['minHeight'] = $_POST['eM_minHeight'];

			}

			if (isset($_POST['eM_maxHeight'])) {

				$eM_Options['maxHeight'] = $_POST['eM_maxHeight'];

			}

			if (isset($_POST['eM_minWidth'])) {

				$eM_Options['minWidth'] = $_POST['eM_minWidth'];

			}

			if (isset($_POST['eM_maxWidth'])) {

				$eM_Options['maxWidth'] = $_POST['eM_maxWidth'];

			}

			$eM_Options = stripslashes_deep($eM_Options);

			update_option($this->adminOptionsName, $eM_Options);?>

		<div class="updated"><p><strong><?php _e('Settings Updated','easy-modal')?>.</strong></p></div><?php

		} ?>

        <div id="poststuff" class="metabox-holder has-right-sidebar wrap" style="width:600px;">

            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>">

                <h2><?php _e('Easy Modal','easy-modal')?></h2>

                <div class="postbox">

                	<h3><?php _e('Modal','easy-modal')?></h3>

                    <div class="inside">

                        <h4><?php _e('Title','easy-modal')?></h4>

                        <p><input type="text" name="eM_title" value="<?php echo $eM_Options['title'];?>" /></p>

                        <h4 style="display:inline-block;"><?php _e('Content','easy-modal');?></h4> - <h5 style="display:inline-block;"><?php _e('Can contain shortcodes','easy-modal')?></h5>

                        <p><textarea name="eM_content" style="width: 80%; height: 100px;"><?php echo $eM_Options['content']?></textarea></p>

                    </div>

                </div>

                <div class="postbox">

                    <h3><?php _e('Overlay Options','easy-modal')?></h3>

                    <div class="inside">

                        <h4><?php _e('CSS Id','easy-modal')?></h4>

                        <p><input type="text" name="eM_overlayId" value="<?php echo $eM_Options['overlayId']?>" /></p>

                        <h4><?php _e('Overlay Color', 'easy-modal');?></h4>

                        <p>
                        	<input type="hidden" name="eM_overlayColor" value="<?php echo $eM_Options['overlayColor']?>" />                       
							<div id="colorSelector" style="height:36px;position:relative;width:36px;">
								<div style="background: url('<?php echo WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) ?>/images/admin/color_selector.png') no-repeat center center transparent;background-color:<?php echo $eM_Options['overlayColor']?>;height: 30px;left: 3px;   position: absolute;top: 3px;width: 30px;"></div>
                        </div>
                        <script type="text/javascript">
							jQuery(document).ready(function() {
								jQuery('#colorSelector').ColorPicker({
									color: '<?php echo $eM_Options['overlayColor']?>',
									onShow: function (colpkr) {
										jQuery(colpkr).fadeIn(500);
										return false;
									},
									onHide: function (colpkr) {
										jQuery(colpkr).fadeOut(500);
										return false;
									},
									onChange: function (hsb, hex, rgb) {
										jQuery('#colorSelector div').css('backgroundColor', '#' + hex);
										jQuery('input[name=eM_overlayColor]').val('#' + hex);
									}
								});
							});
						</script>
</p>

                        <h4><?php _e('Opacity', 'easy-modal');?></h4>

                        <p><input type="text" name="eM_opacity" value="<?php echo $eM_Options['opacity']?>" /></p>

                    </div>

                </div>

                <div class="postbox">

                    <h3><?php _e('Container Options','easy-modal')?></h3>

                    <div class="inside">

                        <h4><?php _e('CSS Id','easy-modal')?></h4>

                        <p><input type="text" name="eM_containerId" value="<?php echo $eM_Options['containerId']?>" /></p>

                        <h4></h4>

                        <p></p>

                        <h4><?php _e('Height','easy-modal')?></h4>

                        <p><?php _e('Min','easy-modal')?>: <input type="text" name="eM_minHeight" value="<?php echo $eM_Options['minHeight']?>" /> <?php _e('Max','easy-modal')?>: <input type="text" name="eM_maxHeight" value="<?php echo $eM_Options['maxHeight']?>" /></p>

                        <h4><?php _e('Width','easy-modal')?></h4>

                        <p><?php _e('Min','easy-modal')?>: <input type="text" name="eM_minWidth" value="<?php echo $eM_Options['minWidth']?>" /> <?php _e('Max','easy-modal')?>: <input type="text" name="eM_maxWidth" value="<?php echo $eM_Options['maxWidth']?>" /></p>

        	        </div>              

                </div>

                <div class="submit">

                    <input type="submit" name="update_eM_settings" value="<?php _e('Update Settings','easy-modal')?>" />

                </div>

            </form>

        </div><?php

	}//End function printAdminPage()

	

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

$eM = new easy_modal;
register_activation_hook(__FILE__, array(&$eM, 'init'));

?>