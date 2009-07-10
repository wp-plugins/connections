<?php
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
	$plugin_options = new pluginOptions($current_user->ID);
	
	if (isset($_POST['submit']))
	{
		if (isset($_POST['settings']['allow_public']) && $_POST['settings']['allow_public'] == true)
		{
			$plugin_options->setAllowPublic(true);
		}
		else
		{
			$plugin_options->setAllowPublic(false);
		}
		
		if (isset($_POST['settings']['allow_public_override']) && $_POST['settings']['allow_public_override'] == true && !$plugin_options->getAllowPublic())
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
		$plugin_options->setImgThumbRatioCrop($_POST['settings']['image']['thumbnail']['ratio_crop']);
		$plugin_options->setImgThumbRatioFill($_POST['settings']['image']['thumbnail']['ratio_fill']);
		
		$plugin_options->setImgEntryQuality($_POST['settings']['image']['entry']['quality']);
		$plugin_options->setImgEntryX($_POST['settings']['image']['entry']['x']);
		$plugin_options->setImgEntryY($_POST['settings']['image']['entry']['y']);
		$plugin_options->setImgEntryRatioCrop($_POST['settings']['image']['entry']['ratio_crop']);
		$plugin_options->setImgEntryRatioFill($_POST['settings']['image']['entry']['ratio_fill']);
		
		$plugin_options->setImgProfileQuality($_POST['settings']['image']['profile']['quality']);
		$plugin_options->setImgProfileX($_POST['settings']['image']['profile']['x']);
		$plugin_options->setImgProfileY($_POST['settings']['image']['profile']['y']);
		$plugin_options->setImgProfileRatioCrop($_POST['settings']['image']['profile']['ratio_crop']);
		$plugin_options->setImgProfileRatioFill($_POST['settings']['image']['profile']['ratio_fill']);
		
		$plugin_options->saveOptions();
	}
	
?>
	<div class="wrap">
		<div id="icon-connections" class="icon32">
	        <br>
	    </div>
		
		<h2>Connections : Settings</h2>
		
		<form action="admin.php?page=connections/submenus/settings.php" method="post">
		
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
										<input type="hidden" value="0" name="settings[allow_public]"/>
										<input type="checkbox" value="1" name="settings[allow_public]" id="allow_public" 
											<?php if ($plugin_options->getAllowPublic()) echo 'CHECKED ' ?>
										/>
										Allow unregistered visitors and users not logged in
									</label>
									
									<label for="allow_public_override">
										<input type="hidden" value="0" name="settings[allow_public_override]"/>
										<input type="checkbox" value="1" name="settings[allow_public_override]" id="allow_public_override" 
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
					<table class="form-table">
						<tbody>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_quality">Thumbnail JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbQuality() ?>" id="image_thumbnail_quality" name="settings[image][thumbnail][quality]"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_x">Thumbnail Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbX() ?>" id="image_thumbnail_x" name="settings[image][thumbnail][x]"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_y">Thumbnail Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbY() ?>" id="image_thumbnail_y" name="settings[image][thumbnail][y]"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Thumbnail Ratio Crop
								</th>
								<td>
									<label for="image_thumbnail_ratio_crop">
										<input type="hidden" value="0" name="settings[image][thumbnail][ratio_crop]"/>
										<input type="checkbox" value="1" name="settings[image][thumbnail][ratio_crop]" id="image_thumbnail_ratio_crop" 
											<?php if ($plugin_options->getImgThumbRatioCrop()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width cropping the excess image
									</label>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Thumbnail Ratio Fill
								</th>
								<td>
									<label for="image_thumbnail_ratio_fill">
										<input type="hidden" value="0" name="settings[image][thumbnail][ratio_fill]"/>
										<input type="checkbox" value="1" name="settings[image][thumbnail][ratio_fill]" id="image_thumbnail_ratio_fill" 
											<?php if ($plugin_options->getImgThumbRatioFill()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width, fitting the image in the space and filling in the remaining space with color
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
									<label for="image_entry_quality">Entry JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryQuality() ?>" id="image_entry_quality" name="settings[image][entry][quality]"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_x">Entry Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryX() ?>" id="image_entry_x" name="settings[image][entry][x]"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_y">Entry Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryY() ?>" id="image_entry_y" name="settings[image][entry][y]"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Entry Ratio Crop
								</th>
								<td>
									<label for="image_entry_ratio_crop">
										<input type="hidden" value="0" name="settings[image][entry][ratio_crop]"/>
										<input type="checkbox" value="1" name="settings[image][entry][ratio_crop]" id="image_entry_ratio_crop" 
											<?php if ($plugin_options->getImgEntryRatioCrop()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width cropping the excess image
									</label>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Entry Ratio Fill
								</th>
								<td>
									<label for="image_entry_ratio_fill">
										<input type="hidden" value="0" name="settings[image][entry][ratio_fill]"/>
										<input type="checkbox" value="1" name="settings[image][entry][ratio_fill]" id="image_entry_ratio_fill" 
											<?php if ($plugin_options->getImgEntryRatioFill()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width, fitting the image in the space and filling in the remaining space with color
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
									<label for="image_profile_quality">Profile JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileQuality() ?>" id="image_profile_quality" name="settings[image][profile][quality]"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_x">Profile Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileX() ?>" id="image_profile_x" name="settings[image][profile][x]"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_y">Profile Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileY() ?>" id="image_profile_y" name="settings[image][profile][y]"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Profile Ratio Crop
								</th>
								<td>
									<label for="image_profile_ratio_crop">
										<input type="hidden" value="0" name="settings[image][profile][ratio_crop]"/>
										<input type="checkbox" value="1" name="settings[image][profile][ratio_crop]" id="image_profile_ratio_crop" 
											<?php if ($plugin_options->getImgProfileRatioCrop()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width cropping the excess image
									</label>
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									Profile Ratio Fill
								</th>
								<td>
									<label for="image_profile_ratio_fill">
										<input type="hidden" value="0" name="settings[image][profile][ratio_fill]"/>
										<input type="checkbox" value="1" name="settings[image][profile][ratio_fill]" id="image_profile_ratio_fill" 
											<?php if ($plugin_options->getImgProfileRatioFill()) echo 'CHECKED ' ?>
										/>
										If checked the image will resize retaining the ratio set by the height and width, fitting the image in the space and filling in the remaining space with color
									</label>
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

<?php } ?>