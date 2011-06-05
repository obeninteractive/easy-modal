<?php

global $wp;

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
		
			'overlayClose' => true,

			'containerId' => 'eM-container',
		
			'autoResize' => false,

			'autoPosition' => true,
		
			'positionX' => '',
		
			'positionY' => '',

			'minHeight' => '50%',

			'maxHeight' => '75%',

			'minWidth' => '30%',

			'maxWidth' => '50%',

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

				if ($_POST['eM_overlayClose'] == 'true') $eM_Options['overlayClose'] = true;
				else $eM_Options['overlayClose'] = false;

			}
		
			// Validate Container Options
			if (isset($_POST['eM_containerId'])) {

				$eM_Options['containerId'] = $_POST['eM_containerId'];

			}
		
			if (isset($_POST['eM_autoResize'])) {
			
				if ($_POST['eM_autoResize'] == 'true') $eM_Options['autoResize'] = true;
				else $eM_Options['autoResize'] = false;

			}

		
			if (isset($_POST['eM_autoPosition'])) {

				if ($_POST['eM_autoPosition'] == 'true') $eM_Options['autoPosition'] = true;
				else $eM_Options['autoPosition'] = false;

			}
		
			if (isset($_POST['eM_positionX'])) {

				$eM_Options['positionX'] = $_POST['eM_positionX'];

			}
		
		
			if (isset($_POST['eM_positionY'])) {

				$eM_Options['positionY'] = $_POST['eM_positionY'];

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

		<div class="updated"><strong><?php _e('Settings Updated','easy-modal')?>.</strong></div><?php

		} ?>

        <div id="poststuff" class="metabox-holder has-right-sidebar wrap">
        
        	<div id="side-info-column" class="inner-sidebar">
            
            </div>
            
            <div id="post-body">
            
                <div id="post-body-content">
                
                    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>">
        
                        <h2><?php _e('Easy Modal','easy-modal')?></h2>
        
                        <div class="postbox full">
        
                            <h3><?php _e('Modal','easy-modal')?></h3>
        
                            <div class="inside">
        
                                <label for="eM_title"><h4><?php _e('Title','easy-modal')?><span class="desc"><?php _e('The title that appears in the modal window.','easy-modal')?></span></h4></label>
        
                                <input type="text" id="eM_title" name="eM_title" value="<?php echo $eM_Options['title'];?>" />
        
                                <label for="eM_content"><h4><?php _e('Content','easy-modal');?><span class="desc"><?php _e('Modal content. Can contain shortcodes.','easy-modal')?></span></h4></label>
        
                                <textarea id="eM_content" name="eM_content" style="width: 100%; height: auto;"><?php echo $eM_Options['content']?></textarea>
        
                                <div class="submit">
        
                                    <input type="submit" name="update_eM_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
                
                                </div>

                            </div>
        
                        </div>
        
                        <div class="postbox half">
        
                            <h3><?php _e('Container Options','easy-modal')?></h3>
        
                            <div class="inside">
        
                                <h4><label for="eM_containerId"><?php _e('Container Id','easy-modal')?><span class="desc"><?php _e('The CSS Id for the container.','easy-modal')?></span></label></h4>
                                <input type="text" id="eM_containerId" name="eM_containerId" value="<?php echo $eM_Options['containerId']?>" />
        
                                <h4><?php _e('Auto Position','easy-modal')?><span class="desc"><?php _e('Automatically position the container upon creation and on window resize?','easy-modal')?></span></h4>
                                <input type="radio" id="eM_autoPosition1" name="eM_autoPosition" value="false" <?php echo $eM_Options['autoPosition'] == false ? 'checked="checked"' : '' ?> />
                                <h5><label for="eM_autoPosition1"><?php _e('False','easy-modal')?></label></h5>
                                <input type="radio" id="eM_autoPosition2" name="eM_autoPosition" value="true" <?php echo $eM_Options['autoPosition'] == true ? 'checked="checked"' : '' ?> />
                                <h5><label for="eM_autoPosition2"><?php _e('True','easy-modal')?></span></label></h5>
        
                                <h4><?php _e('Position','easy-modal')?><span class="desc"><?php _e('Position of container. Can be number of pixels (px) or percentage (%).','easy-modal')?></span></h4>
                                <h5><label for="eM_positionX"><?php _e('Top','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_positionX" name="eM_positionX" value="<?php echo $eM_Options['positionX']?>" />
                                <h5><label for="eM_positionY"><?php _e('Left','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_positionY" name="eM_positionY" value="<?php echo $eM_Options['positionY']?>" />
        
                                <h4><?php _e('Auto Resize','easy-modal')?><span class="desc"><?php _e('Resize the container if it exceeds the browser window dimensions?','easy-modal')?></span></h4>
                                <input type="radio" id="eM_autoResize1" name="eM_autoResize" value="false"  <?php echo $eM_Options['autoResize'] == false ? 'checked="checked"' : '' ?> />
                                <h5><label for="eM_autoResize1"><?php _e('False','easy-modal')?></label></h5>
                                <input type="radio" id="eM_autoResize2" name="eM_autoResize" value="true" <?php echo $eM_Options['autoResize'] == true ? 'checked="checked"' : '' ?> />
                                <h5><label for="eM_autoResize2"><?php _e('True','easy-modal')?></label></h5>
                               
                                <h4><?php _e('Height','easy-modal')?><span class="desc"><?php _e('The height for the container.','easy-modal')?></span></h4>
                                <h5><label for="eM_minHeight"><?php _e('Min','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_minHeight" name="eM_minHeight" value="<?php echo $eM_Options['minHeight']?>" /> 
                                <h5><label for="eM_maxHeight"><?php _e('Max','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_maxHeight" name="eM_maxHeight" value="<?php echo $eM_Options['maxHeight']?>" />
        
                                <h4><?php _e('Width','easy-modal')?><span class="desc"><?php _e('The width of the container.','easy-modal')?></span></h4>
                                <h5><label for="eM_minWidth"><?php _e('Min','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_minWidth" name="eM_minWidth" value="<?php echo $eM_Options['minWidth']?>" /> 
                                <h5><label for="eM_maxWidth"><?php _e('Max','easy-modal')?>:</label></h5>
                                <input type="text" id="eM_maxWidth" name="eM_maxWidth" value="<?php echo $eM_Options['maxWidth']?>" />
        
                                <div class="submit">
        
                                    <input type="submit" name="update_eM_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
                
                                </div>

                            </div>            
        
                        </div>

                        <div class="postbox half last">
        
                            <h3><?php _e('Overlay Options','easy-modal')?></h3>
        
                            <div class="inside">
        
                                <h4><label for="eM_overlayId"><?php _e('Overlay Id','easy-modal')?><span class="desc"><?php _e('Choose the CSS Id for the overlay.','easy-modal')?></span></label></h4>
                                <input type="text" id="eM_overlayId" name="eM_overlayId" value="<?php echo $eM_Options['overlayId']?>" />
        
                                <h4><label for="colorSelector"><?php _e('Overlay Color', 'easy-modal');?><span class="desc"><?php _e('Choose the overlay color.','easy-modal')?></span></label></h4>
                                <input type="hidden" name="eM_overlayColor" value="<?php echo $eM_Options['overlayColor']?>" />                      
                                <div id="colorSelector" style="height:28px;position:relative;width:28px;">
                                    <div style="background: url('<?php echo WP_PLUGIN_URL.'/' ?>easy-modal/images/admin/color_selector.png') no-repeat center center transparent;background-color:<?php echo $eM_Options['overlayColor']?>;height: 28px;width: 28px;"></div>
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
        
                                <h4><label for="eM_opacity"><?php _e('Opacity', 'easy-modal');?><span class="desc"><?php _e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span></label></h4>
                                <input type="text" id="eM_opacity" name="eM_opacity" value="<?php echo $eM_Options['opacity']?>" />
        
                                <h4><?php _e('Close on Click','easy-modal')?><span class="desc"><?php _e('Allow click on overlay to close the dialog?','easy-modal')?></span></h4>
                                <input type="radio" id="eM_overlayClose1" name="eM_overlayClose" value="false" <?php echo $eM_Options['overlayClose'] == false ? 'checked="checked"' : '' ?> />
                                <label for="eM_overlayClose1"><?php _e('False','easy-modal')?></label>
                                <input type="radio" id="eM_overlayClose2" name="eM_overlayClose" value="true" <?php echo $eM_Options['overlayClose'] == true ? 'checked="checked"' : '' ?> />
                                <label for="eM_overlayClose2"><?php _e('True','easy-modal')?></label>
                              
                                <div class="submit">
        
                                    <input type="submit" name="update_eM_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
                
                                </div>

                            </div>
        
                        </div>
        
                    </form>
                    
                </div>
                
			</div>
            
        </div><?php

	}//End function printAdminPage()


}

?>