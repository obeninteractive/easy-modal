<?php
/*
* Easy Modal
* http://wizardinternetsolutions.com/project/easy-modal/
*/
global $wp, $cur_ver;

if ( ! function_exists( 'get_plugin_data' ) )

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	
$plugininfo=get_plugin_data($pluginFILE);

$cur_ver = $plugininfo['Version'];

class easy_modal {
	var $adminOptionsName = "easy_modalOptions";
	//Returns an array of admin options
	function defaultAdminOptions(){
		$settings = array(
			'title' => '',
			'content' => '',
			'cf7form' => false
		);
		return $settings;		
	}
	
	function getModalList(){
		$return = get_option($this->adminOptionsName);
		if(is_array($return)){
			return $return;
		} else {
			return unserialize($return);
		}
	}
	
	
	function getAdminOptions($modalId){
		$settings = $this->defaultAdminOptions();
		$eMOptions = get_option($this->adminOptionsName.'_'.$modalId);
		if (!is_array($eMOptions)) {
			$eMOptions = unserialize($eMOptions);
		}
		foreach ($eMOptions as $key => $option)
			$settings[$key] = $option;

		update_option($this->adminOptionsName.'_'.$modalId, serialize($settings));
		
		return $settings;
		
	}
	function deleteModal($modalId){
		$modals = $this->getModalList();
		foreach($modals as $key => $value){
			if ($modalId == $value) unset($modals[$key]);
		}
		update_option($this->adminOptionsName, serialize($modals));
		delete_option($this->adminOptionsName.'_'.$modalId);
		return true;
	}
	function addNewModal($modalId){
		$modals = $this->getModalList();
		$modals = array_merge($modals,array($modalId));
		update_option($this->adminOptionsName, serialize($modals));
		$settings = $this->defaultAdminOptions();
		update_option($this->adminOptionsName.'_'.$modalId, serialize($settings));
	}
	
	function enqueue_settings(){
		$modals = $this->getModalList();
		$settings = array();
		foreach($modals as $key => $value){
			$setting = $this->getAdminOptions($value);
			$settings[$value] = array(
				"requestData" => array(
					"modalId" => $value,
					"action" => "easy_modal"
				),
				"cf7form" => $setting['cf7form']
			);
		}
		return $settings;
	}
	
	function resetAdminOptions($modalId) {
		update_option($this->adminOptionsName, serialize(array('1')));
		update_option($this->adminOptionsName.'_'.$modalId, serialize($this->defaultAdminOptions()));
	}
	// Plugin Initialization
	function init() {
		global $cur_ver;
		// Erase Settings For versions older than 0.9.0.4
		if(!get_option('eM_version')) $overwrite = true;
		if(version_compare(get_option('eM_version'),$cur_ver, '<')) $overwrite = true;
		if($overwrite == true) $this->resetAdminOptions();
		update_option('eM_version', $cur_ver);
		$this->getAdminOptions($cur_ver);
	}
	//Prints out the admin page
	function display_emodal_option($modalId, $new = true){
    	if($new == true){
			$settings = $this->defaultAdminOptions();
			$this->addNewModal($modalId);
		} else {
			$settings = $this->getAdminOptions($modalId);
		}
		
		ob_start(); ?>
        
           <form id="eModal-<?php echo $modalId ?>" method="post" action="options-general.php?page=easy-modal.php">
            
            
            <div class="postbox full">
            
                <h3><?php _e('Modal','easy-modal')?></h3>
                <div class="inside">
                    <input type="hidden" name="modalId" value="<?php echo $modalId ?>" />
                    <div class="shortcode">
                        <label for="code_to_copy"><h4><?php _e('Code To Copy','easy-modal')?></h4><span class="description"><?php _e('Add these css classes to just about any html element.','easy-modal')?></span></label>
                        <input type="text" id="code_to_copy" value="eModal eModal-<?php echo $modalId?>" />
                    </div>
                    <label for="eM_title"><h4><?php _e('Title','easy-modal')?></h4><span class="description"><?php _e('The title that appears in the modal window.','easy-modal')?></span></label>
                    <input type="text" id="eM_title" name="eM_title" value="<?php echo $settings['title'];?>" />
                    <label for="eM_content"><h4><?php _e('Content','easy-modal');?></h4><span class="description"><?php _e('Modal content. Can contain shortcodes.','easy-modal')?></span></label>
                    <textarea id="eM_content" name="eM_content" style="width: 100%; height: auto;"><?php echo $settings['content']?></textarea>
                    
                    <div class="submit">
                        <input type="submit" name="update_eM_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
                    </div>
                    
                </div>
                
            </div>
            
        <a class="delete_modal"><?php _e('Delete This Modal','easy-modal')?></a>
        </form>
        <?php
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	function printAdminPage() {

		if (isset($_POST['update_eM_settings'])) {
			
			$_POST   = stripslashes_deep($_POST);
			extract($_POST);
			$settings = $this->getAdminOptions($_POST['modalId']);
			if (isset($eM_title)) {
				$settings['title'] = apply_filters('content_save_pre', $eM_title);
			}
			if (isset($eM_content)) {
				if(strstr($eM_content,'[contact-form')!= NULL){ $settings['cf7form'] = true; }
				else { $settings['cf7form'] = false; }
				$settings['content'] = $eM_content;
			}
			
			
			if (isset($eM_overlayColor)) {
				//hex color is valid
				if(preg_match('/^#[a-f0-9]{6}$/i', $eM_overlayColor)){
					$settings['overlayColor'] = $eM_overlayColor;
				}
			}
			if (isset($eM_opacity)) {
				if ($eM_opacity>= 0 && $eM_opacity<=100){
					$settings['opacity'] = $eM_opacity;
				}
			}
			if (isset($eM_overlayClose)) {
				$settings['overlayClose'] = $eM_overlayClose;
			}
			update_option($this->adminOptionsName.'_'.$modalId, serialize($settings));?>
			
			<div class="updated"><strong><?php _e('Settings Updated','easy-modal')?>.</strong></div><?php
		} ?>
        <div id="poststuff" class="metabox-holder has-right-sidebar wrap">
            <div id="side-info-column" class="inner-sidebar">
            	<a href="">Click to donate</a>
            </div>
            <div id="post-body">
                <div id="post-body-content">
                    <h2><?php _e('Easy Modal','easy-modal')?></h2>
				  <?php 
						$modals = $this->getModalList();
						$count = 0;
						foreach($modals as $key => $modal){
							$tabs .= '<li><a href="#eModal-'.$modal.'">eModal-'.$modal.'</a></li>';
                            $panels .= $this->display_emodal_option($modal,false);
							if(intval($modal) > $count) $count = intval($modal);
                        }
                    ?>
                	<button id="addModal">Add New Modal</button>
                    <input type="hidden" name="count" value="<?php echo $count; ?>" />
                    <div id="tabs" style="padding:10px;">
                        <ul>
                            <?php echo $tabs ?>
                        </ul>
						<?php echo $panels ?>
                    </div>
                </div>
            </div>
        </div>
		<script>
			(function($){
				$(document).ready(function(){
					
					var tabs = $("#tabs").tabs({
						add: function(event, ui) {
							tabs.tabs('select', '#' + ui.panel.id);
						}
					})
					$('#addModal').click(function(){
						var modalId = parseInt($('[name=count]').val())+1;
						$('[name=count]').val(modalId);
						tabs.tabs("add","#eModal-"+modalId,"eModal-"+modalId);
						$("#eModal-"+modalId).load("<?php echo admin_url( 'admin-ajax.php')?>",{action:"eModal_Form",modalId:modalId},function(data){
							
							$(this).find('.delete_modal').click(function(){
								$(this).delModal();
							});
							$(this).find('.shortcode > input').click(function(){
								$(this).focus().select();
							});

						});
					});
					$.fn.delModal = function(){
						var modal = $(this).parent().attr('id').split('-');
						var modalId = modal[1];
						var del = confirm('Are you sure you want to delete eModal-'+ modalId);
						if(del){
							$("#eModal-"+modalId).load("<?php echo admin_url( 'admin-ajax.php')?>",{action:"eModal_Delete_Modal",modalId:modalId},function(data){
								if(data == 'Deleted'){
									tabs.tabs("remove","#eModal-"+modalId);
								}
							});
						};
					};

					$('.delete_modal').each(function(){
						$(this).click(function(){
							$(this).delModal();
						});
					});
					$('.shortcode > input').each(function(){
						$(this).click(function(){
							$(this).focus().select();
						});
					});
					
				});
			})(jQuery)
		</script><?php
			
	}//End function printAdminPage()
}
?>