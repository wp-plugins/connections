<?php
function connectionsShowSettingsPage()
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
		/*if (isset($_POST['submit']))
		{
			if (isset($_POST['settings']['allow_public']) && $_POST['settings']['allow_public'] === 'true')
			{
				$connections->options->setAllowPublic(TRUE);
			}
			else
			{
				$connections->options->setAllowPublic(FALSE);
			}
			
			
			if ($_POST['settings']['allow_public_override'] === 'true' && !$connections->options->getAllowPublic())
			{
				$connections->options->setAllowPublicOverride(TRUE);
			}
			else
			{
				$connections->options->setAllowPublicOverride(FALSE);
			}
			
			if ($_POST['settings']['allow_private_override'] === 'true')
			{
				$connections->options->setAllowPrivateOverride(TRUE);
			}
			else
			{
				$connections->options->setAllowPrivateOverride(FALSE);
			}
			
			$connections->options->setImgThumbQuality($_POST['settings']['image']['thumbnail']['quality']);
			$connections->options->setImgThumbX($_POST['settings']['image']['thumbnail']['x']);
			$connections->options->setImgThumbY($_POST['settings']['image']['thumbnail']['y']);
			$connections->options->setImgThumbCrop($_POST['settings']['image']['thumbnail']['crop']);
			
			$connections->options->setImgEntryQuality($_POST['settings']['image']['entry']['quality']);
			$connections->options->setImgEntryX($_POST['settings']['image']['entry']['x']);
			$connections->options->setImgEntryY($_POST['settings']['image']['entry']['y']);
			$connections->options->setImgEntryCrop($_POST['settings']['image']['entry']['crop']);
			
			$connections->options->setImgProfileQuality($_POST['settings']['image']['profile']['quality']);
			$connections->options->setImgProfileX($_POST['settings']['image']['profile']['x']);
			$connections->options->setImgProfileY($_POST['settings']['image']['profile']['y']);
			$connections->options->setImgProfileCrop($_POST['settings']['image']['profile']['crop']);
			
			$connections->options->saveOptions();
			
			echo "<div id='message' class='updated fade'>";
				echo "<p><strong>Settings have been updated.</strong></p>";
			echo "</div>";
		}*/
		//$connections->options->removeOptions();
		//$connections->options->setDefaultImageSettings();
		//$connections->options->saveOptions();
		//print_r($_POST['settings']['allow_public']);
		
		global $connections;
		
		$entryForm = new cnEntryForm();
		$form = new cnFormObjects();
		
		$connections->displayMessages();
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Settings</h2>
			
			<?php 
				$attr = array(
							 'action' => 'admin.php?page=connections_settings&action=update_settings',
							 'method' => 'post',
							 );
				
				$form->open($attr);
				$connections->tokenField('update_settings');
			?>
			
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
											<input type="checkbox" value="true" name="settings[allow_public]" id="allow_public" 
												<?php if ($connections->options->getAllowPublic()) echo 'CHECKED ' ?>
											/>
											Allow unregistered visitors and users not logged in to view entries<br />
											<small>(When disabled, use roles to define which roles may view the public entries.)</small>
										</label>
										
										<label for="allow_public_override">
											<input type="checkbox" value="true" name="settings[allow_public_override]" id="allow_public_override" 
												<?php if ($connections->options->getAllowPublicOverride()) echo 'CHECKED ' ?>
												<?php if ($connections->options->getAllowPublic()) echo 'DISABLED ' ?>
											/>
											Allow shortcode attribute override
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					<div class="form-field connectionsform">
						<table class="form-table">
							<tbody>
							
								<tr valign="top">
									<th scope="row">
										Private Entries
									</th>
									<td>
										<label for="allow_private_override">
											<input type="hidden" value="false" name="settings[allow_private_override]"/>
											<input type="checkbox" value="true" name="settings[allow_private_override]" id="allow_private_override" 
												<?php if ($connections->options->getAllowPrivateOverride()) echo 'CHECKED ' ?>
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
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbQuality() ?>" id="image_thumbnail_quality" name="settings[image][thumbnail][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbX() ?>" id="image_thumbnail_x" name="settings[image][thumbnail][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbY() ?>" id="image_thumbnail_y" name="settings[image][thumbnail][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][thumbnail][crop]', 'image_thumbnail_crop', array('Enlarge and crop (maintain aspect ratio)' => 'crop', 'Shrink to fit (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgThumbCrop()); ?>
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
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryQuality() ?>" id="image_entry_quality" name="settings[image][entry][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryX() ?>" id="image_entry_x" name="settings[image][entry][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryY() ?>" id="image_entry_y" name="settings[image][entry][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][entry][crop]', 'image_entry_crop', array('Enlarge and crop (maintain aspect ratio)' => 'crop', 'Shrink to fit (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgEntryCrop()); ?>
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
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileQuality() ?>" id="image_profile_quality" name="settings[image][profile][quality]"/>%
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileX() ?>" id="image_profile_x" name="settings[image][profile][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileY() ?>" id="image_profile_y" name="settings[image][profile][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][profile][crop]', 'image_profile_crop', array('Enlarge and crop (maintain aspect ratio)' => 'crop', 'Shrink to fit (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgProfileCrop()); ?>
									</td>
								</tr>
							
							</tbody>
						</table>
					</div>
				</div>
			
			<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="save" /></p>
			
			<?php $form->close(); ?>
			
		</div>
		<div class="clear"></div>
	
	<?php }
}
?>