<div class="wrap"><?php
	if($_POST)
	{
		$settings = $this->updateThemeSettings( 1, $_POST );
		?><div class="updated"><strong><?php _e('Settings Updated','easy-modal')?>.</strong></div><?php
	}
	else
	{
		$settings = $this->getThemeSettings(1);
	}
	global $wp;
	$borderTypes = array(
		'solid' => 'Solid',
		'dotted' => 'Dotted',
		'double' => 'Double'
	);
	$fontFamilys = array(
		'Sans-Serif'=>'Sans-Serif',
		'Tahoma' => 'Tahoma',
		'Georgia' => 'Georgia',
		'Comic Sans MS' => 'Comic Sans MS',
		'Arial' => 'Arial',
		'Lucida Grande' => 'Lucida Grande',
		'Times New Roman' => 'Times New Roman'
	);
	$closePositions = array(
		'topleft' => 'Top Left',
		'topright' => 'Top Right',
		'bottomleft' => 'Bottom Left',
		'bottomright' => 'Bottom Right'
	);
	?><form method="post" action="admin.php?page=<?php echo $this->Plugin['slug']?>-themes&theme_id=<?php echo 1?>">
		<h2>
			<?php echo ucfirst($settings['name']).' '; _e('Theme','easy-modal')?>
		</h2>
		<div class="emthemes">
			<div id="accordion">
				<h3><a href="#"><?php _e('Theme Options','easy-modal')?></a></h3>
				<div id="themeOptions">
					<table>
						<tr class="odd">
							<th>
								<label for="theme"><h4><?php _e('Theme Name', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="name" id="name" value="<?php echo $settings['name']?>"/>                      
								<span class="description"><?php //_e('Choose the overlay color.','easy-modal')?></span>
							</td>
						</tr>
					</table>
				</div>
				<h3><a href="#"><?php _e('Overlay','easy-modal')?></a></h3>
				<div id="overlayOptions">
					<table>
						<tr class="odd">
							<th>
								<label for="overlayColor"><h4><?php _e('Overlay Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="overlayColor" id="overlayColor" value="<?php echo $settings['overlayColor']?>" class="colorSelect" style="background-color:<?php echo $settings['overlayColor']?>" />                      
								<span class="description"><?php //_e('Choose the overlay color.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="overlayOpacity"><h4><?php _e('Opacity', 'easy-modal');?></h4></label>
							</th>
							<td>
								<div id="overlayOpacitySlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="overlayOpacityValue"><?php echo $settings['overlayOpacity']?></span>%</div>
								<input type="hidden" id="overlayOpacity" name="overlayOpacity" size="20" value="<?php echo $settings['overlayOpacity']?>"/>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
					</table>
				</div>
				<h3><a href="#"><?php _e('Close Button','easy-modal')?></a></h3>
				<div id="closeOptions">
					<table>
						<tr class="odd">
							<th>
								<label for="closeBgColor"><h4><?php _e('Background Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="closeBgColor" id="closeBgColor" value="<?php echo $settings['closeBgColor']?>" class="colorSelect" style="background-color:<?php echo $settings['closeBgColor']?>" />                      
								<span class="description"><?php //_e('The Presenter\'s WordPress User ID'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="closeFontColor"><h4><?php _e('Font Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="closeFontColor" id="closeFontColor" value="<?php echo $settings['closeFontColor']?>" class="colorSelect" style="background-color:<?php echo $settings['closeFontColor']?>" />                      
								<span class="description"><?php //_e('The Presenter\'s WordPress User ID'); ?></span>
							</td>
						</tr>
						<tr class="odd">
							<th>
								<label for="closeFontSize"><h4><?php _e('Font Size', 'easy-modal');?></h4></label>
							</th>
							<td>
								<div id="closeFontSizeSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="closeFontSizeValue"><?php echo $settings['closeFontSize']?></span>px</div>
								<input type="hidden" name="closeFontSize" id="closeFontSize" value="<?php echo $settings['closeFontSize']?>" />                      
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="closeBorderRadius"><h4><?php _e('Border Radius', 'easy-modal');?></h4></label>
							</th>                    
							<td>
								<div id="closeBorderRadiusSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="closeBorderRadiusValue"><?php echo $settings['closeBorderRadius']?></span>px</div>
								<input type="hidden" id="closeBorderRadius" name="closeBorderRadius" value="<?php echo $settings['closeBorderRadius']?>"/>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr class="odd">
							<th>
								<label for="closeSize"><h4><?php _e('Size', 'easy-modal');?></h4></label>
							</th>                    
							<td>
								<div id="closeSizeSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="closeSizeValue"><?php echo $settings['closeSize']?></span>px</div>
								<input type="hidden" id="closeSize" name="closeSize" value="<?php echo $settings['closeSize']?>"/>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="closePosition"><h4><?php _e('Position', 'easy-modal');?></h4></label>
							</th>
							<td>
								<select name="closePosition" id="closePosition"><?php
								foreach($closePositions as $type => $name){
									echo '<option value="'.$type.'"'.($type == $settings['closePosition'] ? 'selected="selected"' : '').'>'.$name.'</option>';
								}?>
								</select>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
					</table>
				</div>
				<h3><a href="#"><?php _e('Container','easy-modal')?></a></h3>
				<div id="containerOptions">
					<table>
						<tr class="odd">
							<th>
								<label for="containerBgColor"><h4><?php _e('Background Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="containerBgColor" id="containerBgColor" value="<?php echo $settings['containerBgColor']?>" class="colorSelect" style="background-color:<?php echo $settings['containerBgColor']?>" />                      
								<span class="description"><?php //_e('The Presenter\'s WordPress User ID'); ?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="containerPadding"><h4><?php _e('Padding', 'easy-modal');?></h4></label>
							</th>
							<td>
								<div id="containerPaddingSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="containerPaddingValue"><?php echo $settings['containerPadding']?></span>px</div>
								<input type="hidden" id="containerPadding" name="containerPadding" width="20" value="<?php echo $settings['containerPadding']?>"/>
								<span class="description"><?php //_e('The Presenter\'s WordPress User ID'); ?></span>
							</td>
						</tr>
						<tr class="odd">
							<th>
								<label for="containerBorderColor"><h4><?php _e('Border Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="containerBorderColor" id="containerBorderColor" value="<?php echo $settings['containerBorderColor']?>" class="colorSelect" style="background-color:<?php echo $settings['containerBorderColor']?>" />                      
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="containerBorderStyle"><h4><?php _e('Border Style', 'easy-modal');?></h4></label>
							</th>
							<td>
								<select name="containerBorderStyle" id="containerBorderStyle"><?php
								foreach($borderTypes as $type => $name){
									echo '<option value="'.$type.'"'.($type == $settings['containerBorderStyle'] ? 'selected="selected"' : '').'>'.$name.'</option>';
								}?>
								</select>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr class="odd">
							<th>
								<label for="containerBorderWidth"><h4><?php _e('Border Width', 'easy-modal');?></h4></label>
							</th>     
							<td>
								<div id="containerBorderWidthSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="containerBorderWidthValue"><?php echo $settings['containerBorderWidth']?></span>px</div>
								<input type="hidden" id="containerBorderWidth" name="containerBorderWidth" value="<?php echo $settings['containerBorderWidth']?>"/>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="containerBorderRadius"><h4><?php _e('Border Radius', 'easy-modal');?></h4></label>
							</th>                    
							<td>
								<div id="containerBorderRadiusSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="containerBorderRadiusValue"><?php echo $settings['containerBorderRadius']?></span>px</div>
								<input type="hidden" id="containerBorderRadius" name="containerBorderRadius" value="<?php echo $settings['containerBorderRadius']?>"/>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
					</table>
				</div>
				<h3><a href="#"><?php _e('Content','easy-modal')?></a></h3>
				<div id="contentOptions">
					<table>
						<tr class="odd">
							<th>
								<label for="contentTitleFontColor"><h4><?php _e('Title Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="contentTitleFontColor" id="contentTitleFontColor" value="<?php echo $settings['contentTitleFontColor']?>" class="colorSelect" style="background-color:<?php echo $settings['contentTitleFontColor']?>" />                      
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="contentTitleFontSize"><h4><?php _e('Title Font Size', 'easy-modal');?></h4></label>
							</th>
							<td>
								<div id="contentTitleFontSizeSlider" style="width:65%; float:left; display:inline-block;"></div><div style="display:inline-block; float:right; font-weight:bold;"><span id="contentTitleFontSizeValue"><?php echo $settings['contentTitleFontSize']?></span>px</div>
								<input type="hidden" name="contentTitleFontSize" id="contentTitleFontSize" value="<?php echo $settings['contentTitleFontSize']?>" />                      
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr class="odd">
							<th>
								<label for="contentTitleFontFamily"><h4><?php _e('Title Font Family', 'easy-modal');?></h4></label>
							</th>
							<td>
								<select name="contentTitleFontFamily" id="contentTitleFontFamily"><?php
								foreach($fontFamilys as $type => $name){
									echo '<option value="'.$type.'"'.($type == $settings['contentTitleFontFamily'] ? 'selected="selected"' : '').'>'.$name.'</option>';
								}?>
								</select>
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
						<tr>
							<th>
								<label for="contentFontColor"><h4><?php _e('Font Color', 'easy-modal');?></h4></label>
							</th>
							<td>
								<input type="text" name="contentFontColor" id="contentFontColor" value="<?php echo $settings['contentFontColor']?>" class="colorSelect" style="background-color:<?php echo $settings['contentFontColor']?>" />                      
								<span class="description"><?php //_e('The opacity value for the overlay, from 0 - 100.','easy-modal')?></span>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="submit" style="clear:both; overflow:auto;">
				<input type="submit" name="update_settings" class="button-primary" value="<?php _e('Save Settings','easy-modal')?>" />
			</div>
		</div>

		<div class="empreview">
			
			<div id="eModal-Preview">
				<div id="eModal-Overlay" style="background-color:<?php echo $settings['overlayColor']?>;opacity:<?php echo intval($settings['overlayOpacity'])/100 ?>;"></div>
				<h2><?php _e('eModal Theme Preview','easy-modal')?></h2>
				<div id="eModal-Container" style="
					background-color:<?php echo $settings['containerBgColor']?>;
					padding:<?php echo $settings['containerPadding']?>px;
					border:<?php echo $settings['containerBorderColor']?> <?php echo $settings['containerBorderWidth'].'px'?> <?php echo $settings['containerBorderStyle']?>;
					-moz-border-radius:<?php echo $settings['containerBorderRadius']?>px;
					-webkit-border-radius:<?php echo $settings['containerBorderRadius']?>px;
					border-radius:<?php echo $settings['containerBorderRadius']?>px;
					behavior: 'url(<?php echo get_bloginfo('wpurl').'/'.$this->Plugin['dir']?>/themes/PIE.htc)';
					color:<?php echo $settings['contentFontColor']?>;
				">
					<a id="eModal-Close" href="#close" style="
						background-color:<?php echo $settings['closeBgColor']?>;
						color:<?php echo $settings['closeFontColor']?>;
						font-size:<?php echo $settings['closeFontSize']?>px;
						-moz-border-radius:<?php echo $settings['closeBorderRadius']?>px;
						-webkit-border-radius:<?php echo $settings['closeBorderRadius']?>px;
						border-radius:<?php echo $settings['closeBorderRadius']?>px;
						width:<?php echo $settings['closeSize']?>px;
						height:<?php echo $settings['closeSize']?>px;
						line-height:<?php echo $settings['closeSize']?>px;
						<?php
						$size = 0-($settings['closeSize']/2).'px';
						$top = $left = $bottom = $right = 'auto';
						switch($settings['closePosition'])
						{
							case 'topleft': 
								$top = $size; 
								$left = $size; 
								break;
							case 'topright':
								$top = $size; 
								$right = $size; 
								break;
							case 'bottomleft': 
								$left = $size; 
								$bottom = $size;
								break;
							case 'bottomright':
								$right = $size; 
								$bottom = $size;
								break;
						}
						?>
						top: <?php echo $top?>;
						bottom: <?php echo $bottom?>;
						left: <?php echo $left?>;
						right: <?php echo $right?>;
					">X</a>
					<h1 id="eModal-Title" style="
						color:<?php echo $settings['contentTitleFontColor']?>;
						font-family:<?php echo $settings['contentTitleFontFamily']?>;
						font-size:<?php echo $settings['contentTitleFontSize']?>px;
					">Title Text</h1>
					<p>Suspendisse ipsum eros, tincidunt sed commodo ut, viverra vitae ipsum. Etiam non porta neque. Pellentesque nulla elit, aliquam in ullamcorper at, bibendum sed eros. Morbi non sapien tellus, ac vestibulum eros. In hac habitasse platea dictumst. Nulla vestibulum, diam vel porttitor placerat, eros tortor ultrices lectus, eget faucibus arcu justo eget massa. Maecenas id tellus vitae justo posuere hendrerit aliquet ut dolor.</p>
				</div>
			</div>
		</div>
	</form>
</div>