<?php
function connectionsShowSettinsPage()
{
	/*
	 * Check whether user can edit Settings
	 */
	if (!current_user_can('connections_change_settings'))
	{
		wp_die('<p id="error-page" style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center;
				width:700px">You do not have sufficient permissions to access this page.</p>');
	}
	else
	{
		$plugin_options = new pluginOptions();
		
		if (isset($_POST['submit']))
		{
			$plugin_options->setAllowPublic($_POST['settings']['allow_public']);
			
			if ($_POST['settings']['allow_public_override'] === 'true' && !$plugin_options->getAllowPublic())
			{
				$plugin_options->setAllowPublicOverride(true);
			}
			else
			{
				$plugin_options->setAllowPublicOverride(false);
			}
			
			$plugin_options->setImgThumbQuality($_POST['settings']['image']['thumbnail']['quality']);
			$plugin_options->setImgThumbX($_POST['settings']['image']['thumbnail']['x']);
			$plugin_options->setImgThumbY($_POST['settings']['image']['thumbnail']['y']);
			$plugin_options->setImgThumbCrop($_POST['settings']['image']['thumbnail']['crop']);
			
			$plugin_options->setImgEntryQuality($_POST['settings']['image']['entry']['quality']);
			$plugin_options->setImgEntryX($_POST['settings']['image']['entry']['x']);
			$plugin_options->setImgEntryY($_POST['settings']['image']['entry']['y']);
			$plugin_options->setImgEntryCrop($_POST['settings']['image']['entry']['crop']);
			
			$plugin_options->setImgProfileQuality($_POST['settings']['image']['profile']['quality']);
			$plugin_options->setImgProfileX($_POST['settings']['image']['profile']['x']);
			$plugin_options->setImgProfileY($_POST['settings']['image']['profile']['y']);
			$plugin_options->setImgProfileCrop($_POST['settings']['image']['profile']['crop']);
			
			$plugin_options->saveOptions();
			
			echo "<div id='message' class='updated fade'>";
				echo "<p><strong>Settings have been updated.</strong></p>";
			echo "</div>";
		}
		
		//$plugin_options->setDefaultImageSettings();
		//$plugin_options->saveOptions();
		$form =  new formObjects();
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Settings</h2>
			
			<form action="admin.php?page=connections_settings" method="post">
			
				<div class="form-wrap">
					<div class="form-field connectionsform">
						<table class="form-table">
							<tbody>
							
								<tr valign="top">
									<th scope="row">
										Public Entries
									</th>
									<td>
										<label for="allow_public">
											<input type="hidden" value="false" name="settings[allow_public]"/>
											<input type="checkbox" value="true" name="settings[allow_public]" id="allow_public" 
												<?php if ($plugin_options->getAllowPublic()) echo 'CHECKED ' ?>
											/>
											Allow unregistered visitors and users not logged in
										</label>
										
										<label for="allow_public_override">
											<input type="hidden" value="false" name="settings[allow_public_override]"/>
											<input type="checkbox" value="true" name="settings[allow_public_override]" id="allow_public_override" 
												<?php if ($plugin_options->getAllowPublicOverride()) echo 'CHECKED ' ?>
												<?php if ($plugin_options->getAllowPublic()) echo 'DISABLED ' ?>
											/>
											Allow shortcode attribute override
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
				
					<div class="form-field connectionsform">
						<h3>Thumbnail Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbQuality() ?>" id="image_thumbnail_quality" name="settings[image][thumbnail][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbX() ?>" id="image_thumbnail_x" name="settings[image][thumbnail][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbY() ?>" id="image_thumbnail_y" name="settings[image][thumbnail][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][thumbnail][crop]', 'image_thumbnail_crop', array('Enlarge and crop' => 'crop', 'Shrink to fit' => 'fill', 'None' => 'none'), $plugin_options->getImgThumbCrop()); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
								
								
					<div class="form-field connectionsform">
						<h3>Entry Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryQuality() ?>" id="image_entry_quality" name="settings[image][entry][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryX() ?>" id="image_entry_x" name="settings[image][entry][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryY() ?>" id="image_entry_y" name="settings[image][entry][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][entry][crop]', 'image_entry_crop', array('Enlarge and crop' => 'crop', 'Shrink to fit' => 'fill', 'None' => 'none'), $plugin_options->getImgEntryCrop()); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
								
					<div class="form-field connectionsform">
						<h3>Profile Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileQuality() ?>" id="image_profile_quality" name="settings[image][profile][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileX() ?>" id="image_profile_x" name="settings[image][profile][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileY() ?>" id="image_profile_y" name="settings[image][profile][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][profile][crop]', 'image_profile_crop', array('Enlarge and crop' => 'crop', 'Shrink to fit' => 'fill', 'None' => 'none'), $plugin_options->getImgProfileCrop()); ?>
									</td>
								</tr>
							
							</tbody>
						</table>
					</div>
				</div>
			
			<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="submit" /></p>
			
			</form>
			
		</div>
		<div class="clear"></div>
	
	<?php }
}
?>