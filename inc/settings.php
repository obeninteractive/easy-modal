<div class="wrap"><?php
	if($_POST)
	{
		$settings = $this->updateSettings( $_POST );
		?><div class="updated"><strong><?php _e('Settings Updated','easy-modal')?>.</strong></div><?php
	}
	else
	{
		$settings = $this->getSettings();
	}
	?><form method="post" action="admin.php?page=<?php echo $this->Plugin['slug']?>-settings">				
		<div class="error">
			<p>If you purchased the Pro version and havent already recieved a key please email us at <a href="mailto:danieliser@wizardinternetsolutions.com">danieliser@wizardinternetsolutions.com</a></p>
		</div>   
		<div>
			<h3><?php _e('Settings','easy-modal')?></h3>
			<div id="accordion">
				<h4><a href="#"><?php _e('License','easy-modal')?></a></h4>
				<div>
					<div>
						<label for="license"><h4><?php _e('License Key','easy-modal')?></h4></label><?php
						$license_status = get_option('EasyModal_License_Status');
						$valid = $license_status['status']===200 ? true : false;?>
						<input <?php echo $valid ? 'style="background-color:#0f0;border-color:#090;"' : '' ?> type="text" id="license" name="license" value="<?php echo get_option('EasyModal_License')?>"/>
						<?php echo isset($license_status['message']) ? $license_status['message'] : ''?>
					</div>
					<div class="submit">
						<input type="submit" name="update_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
					</div>
				</div>
				<h3><a href="#"><?php _e('Usage Example','easy-modal')?></a></h3>
				<div>
					<h4><?php _e('Copy the class to the link/button you want to open this modal.','easy-modal')?><span class="desc">Will start with eModal- and end with a # of the modal you want to open.</span></h4>
					<h4>Link Example</h4>
					<a href="#" onclick="return false;" class="eModal-1">Open Modal</a>
					<pre>&lt;a href="#" class="eModal-1">Open Modal&lt;/a></pre>
					<h4>Button Example</h4>
					<button onclick="return false;" class="eModal-1">Open Modal</button>
					<pre>&lt;button class="eModal-1">Open Modal&lt;/button></pre>
					<h4>Image Example</h4>
					<img style="cursor:pointer;" src="http://www.truthunity.net/sites/all/content/graphics/ministry-click-me-button.jpg" width="75" onclick="return false;" class="eModal-1" />
					<pre>&lt;img src="ministry-click-me-button.jpg" class="eModal-1" /></pre>
				</div>
			</div>
		</div>
	</form>
</div>