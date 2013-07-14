<div class="wrap"><?php
	$modal_id = isset($_GET['modal_id']) ? $_GET['modal_id'] : NULL;
	if($modal_id > 0 && isset($_GET['action']) && $_GET['action']=='delete')
	{
		?><<< - <a href="admin.php?page=<?php echo $this->Plugin['slug']?>-modals">Back to Modals</a><?php
		if(!isset($_GET['confirm']))
		{
			?><h2><?php _e('Delete Modal','easy-modal')?></h2>
			<p>Are you sure you want to delete this modal?</p>
			<a style="color:#fff;border:1px solid #333; background-color:#900; text-shadow: none;"class="add-new-h2" href="admin.php?page=<?php echo $this->Plugin['slug']?>-modals&modal_id=<?php echo $modal_id?>&action=delete&confirm=yes">Confirm</a><?php
		}
		else
		{
			$this->deleteModal($modal_id);
			?><p>Modal Deleted</p><?php
		}
	}
	elseif($modal_id!=NULL)
	{
		if($_POST)
		{
			$settings = $this->updateModalSettings( $modal_id, $_POST );
			?><div class="updated"><strong><?php _e('Settings Updated','easy-modal')?>.</strong></div><?php
		}
		elseif($modal_id=='new')
		{
			$settings = $this->defaultModalSettings();
		}
		else
		{
			$settings = $this->getModalSettings( $modal_id );
		}
		?><<< - <a href="admin.php?page=<?php echo $this->Plugin['slug']?>-modals">Back to Modal Lists</a>
		<h2>
			<?php echo ucfirst($settings['name']).' '; _e('Modal','easy-modal')?>
			<a style="color:#fff;border:1px solid #333; background-color:#900; text-shadow: none;"class="add-new-h2" href="admin.php?page=<?php echo $this->Plugin['slug']?>-modals&modal_id=<?php echo $modal_id?>&action=delete">Delete</a>
		</h2>
		<form method="post" action="admin.php?page=<?php echo $this->Plugin['slug']?>-modals&modal_id=<?php echo (isset($settings['modal_id'])&&$settings['modal_id']!=''?$settings['modal_id']:'new')?>">
			<div id="accordion">
				<h3><a href="#"><?php _e('Modal','easy-modal')?></a></h3>
				<div>
					<label for="name"><h4><?php _e('Name','easy-modal')?><span class="desc"><?php _e('','easy-modal')?></span></h4></label>
					<input type="text" id="name" name="name" value="<?php echo $settings['name'];?>" />
	
					<label for="title"><h4><?php _e('Title','easy-modal')?><span class="desc"><?php _e('The title that appears in the modal window.','easy-modal')?></span></h4></label>
					<input type="text" id="title" name="title" value="<?php echo $settings['title'];?>" />
	
					<label for="content"><h4><?php _e('Content','easy-modal');?><span class="desc"><?php _e('Modal content. Can contain shortcodes.','easy-modal')?></span></h4></label>
					<textarea id="content" name="content" style="width: 100%; height: auto;"><?php echo $settings['content']?></textarea>
					<div class="submit">
						<input type="submit" name="update_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
					</div>
				</div>
				<h3><a href="#"><?php _e('Size Options','easy-modal')?></a></h3>
				<div>
					<input type="hidden" name="type" value="1"/>
					<div style="display:block; position:relative; clear:both; overflow:auto;">
						<div style="float:left; margin-right:10px;">
							<label for="userHeight"><h4><?php _e('Height','easy-modal')?></h4></label>
							<input type="text" id="userHeight" name="userHeight "size="5" value="<?php echo $settings['userHeight'];?>" />px
						</div>
						<div style="float:left">
							<label for="userWidth"><h4><?php _e('Width','easy-modal')?></h4></label>
							<input type="text" id="userWidth" name="userWidth" size="5"value="<?php echo $settings['userWidth'];?>" />px
						</div>
					</div>
					<label for="userMaxHeight"><h4><?php _e('Max Height','easy-modal')?></h4></label>
					<div id="userMaxHeightSlider" style="width:100px;margin:8px;float:left;"></div>
					<input type="text" id="userMaxHeight" name="userMaxHeight" size="3" value="<?php echo $settings['userMaxHeight'];?>" />%
					<label for="userMaxWidth"><h4><?php _e('Max Width','easy-modal')?></h4></label>
					<div id="userMaxWidthSlider" style="width:100px;margin:8px;float:left;"></div>
					<input type="text" id="userMaxWidth" name="userMaxWidth" size="3" value="<?php echo $settings['userMaxWidth'];?>" />%
					<div class="submit">
						<input type="submit" name="update_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
					</div>
				</div>
				<h3><a href="#"><?php _e('Additional Settings','easy-modal')?></a></h3>
				<div>
					<label for="overlayClose"><h4><?php _e('Click Overlay to Close','easy-modal')?></h4></label>
					<p class="field switch" style="clear:both; overflow:auto; display:block;">
						<label class="cb-enable"><span>On</span></label>
						<label class="cb-disable selected"><span>Off</span></label>
						<input type="checkbox" class="checkbox" id="overlayClose" name="overlayClose" value="true" <?php echo $settings['overlayClose'] == 'true' ? 'checked="checked"' : '' ?> />
					</p>
					<label for="overlayEscClose"><h4><?php _e('ESC Key to Close Overlay','easy-modal')?></h4></label>
					<p class="field switch" style="clear:both; overflow:auto; display:block;">
						<label class="cb-enable"><span>On</span></label>
						<label class="cb-disable selected"><span>Off</span></label>
						<input type="checkbox" class="checkbox" id="overlayEscClose" name="overlayEscClose" value="true" <?php echo $settings['overlayEscClose'] == 'true' ? 'checked="checked"' : '' ?> />
					</p>
					<div class="submit">
						<input type="submit" name="update_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
					</div>
				</div>
				<h3><a href="#"><?php _e('Usage Example','easy-modal')?></a></h3>
				<div>
					<h4><?php _e('Copy this class to the link/button you want to open this modal.','easy-modal')?><span class="desc">eModal-<?php echo $settings['modal_id']?></span></h4>
					<h4>Link Example</h4>
					<a href="#" onclick="return false;" class="eModal-<?php echo $settings['modal_id']?>">Open Modal</a>
					<pre>&lt;a href="#" class="eModal-<?php echo $settings['modal_id']?>">Open Modal&lt;/a></pre>
					<h4>Button Example</h4>
					<button onclick="return false;" class="eModal-<?php echo $settings['modal_id']?>">Open Modal</button>
					<pre>&lt;button class="eModal-<?php echo $settings['modal_id']?>">Open Modal&lt;/button></pre>
					<h4>Image Example</h4>
					<img style="cursor:pointer;" src="http://www.truthunity.net/sites/all/content/graphics/ministry-click-me-button.jpg" width="75" onclick="return false;" class="eModal-<?php echo $settings['modal_id']?>" />
					<pre>&lt;img src="ministry-click-me-button.jpg" class="eModal-<?php echo $settings['modal_id']?>" /></pre>
				</div>
			</div>
		</form><?php
	}
	else
	{
		$modals = $this->getModalList();
		?><h2>Easy Modal Modals<a class="add-new-h2" href="admin.php?page=<?php echo $this->Plugin['slug']?>-modals&modal_id=new">Add New</a></h2><?php
		if( $modals && count($modals) > 0 )
		{
			?><table>
				<thead>
					<th>Name</th>
				</thead>
				<tbody><?php
				foreach($modals as $id => $name)
				{
					echo '<tr><td><a href="admin.php?page='. $this->Plugin['slug']. '-modals&modal_id='.$id.'">'.ucfirst($name).'</a></td></tr>';
				}
				?></tbody>
			</table><?php
		}
		else
		{
			?><p>To get started click the "add new" button above</p><?php
		}
	}
?></div>