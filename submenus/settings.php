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
		if (isset($_POST['settings']['allow_public']) && $_POST['settings']['allow_public'] == 'true')
		{
			$plugin_options->setAllowPublic(true);
		}
		else
		{
			$plugin_options->setAllowPublic(false);
		}
		
		if (isset($_POST['settings']['allow_public_override']) && $_POST['settings']['allow_public_override'] == 'true' && !$plugin_options->getAllowPublic())
		{
			$plugin_options->setAllowPublicOverride(true);
		}
		else
		{
			$plugin_options->setAllowPublicOverride(false);
		}
		
		$plugin_options->setImgThumbQuality($_POST['image_thumbnail_quality']);
		$plugin_options->setImgThumbX($_POST['image_thumbnail_x']);
		$plugin_options->setImgThumbY($_POST['image_thumbnail_y']);
		
		$plugin_options->setImgEntryQuality($_POST['image_entry_quality']);
		$plugin_options->setImgEntryX($_POST['image_entry_x']);
		$plugin_options->setImgEntryY($_POST['image_entry_y']);
		
		$plugin_options->setImgProfileQuality($_POST['image_profile_quality']);
		$plugin_options->setImgProfileX($_POST['image_profile_x']);
		$plugin_options->setImgProfileY($_POST['image_profile_y']);
		
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
					<table class="form-table">
						<tbody>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_quality">Thumbnail JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbQuality() ?>" id="image_thumbnail_quality" name="image_thumbnail_quality"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_x">Thumbnail Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbX() ?>" id="image_thumbnail_x" name="image_thumbnail_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_y">Thumbnail Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgThumbY() ?>" id="image_thumbnail_y" name="image_thumbnail_y"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_quality">Entry JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryQuality() ?>" id="image_entry_quality" name="image_entry_quality"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_x">Entry Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryX() ?>" id="image_entry_x" name="image_entry_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_y">Entry Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgEntryY() ?>" id="image_entry_y" name="image_entry_y"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_quality">Profile JPEG Quality</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileQuality() ?>" id="image_profile_quality" name="image_profile_quality"/>%
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_x">Profile Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileX() ?>" id="image_profile_x" name="image_profile_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_y">Profile Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="<?php echo $plugin_options->getImgProfileY() ?>" id="image_profile_y" name="image_profile_y"/>px
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