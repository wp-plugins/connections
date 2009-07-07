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
	//print_r($plugin_options);
	
	if (isset($_POST['submit']))
	{
		if (isset($_POST['settings']['view_public_entries']) && $_POST['settings']['view_public_entries'] == 'true')
		{
			$plugin_options->setAllowPublic(true);
			$plugin_options->saveOptions();
		}
		else
		{
			$plugin_options->setAllowPublic(false);
			$plugin_options->saveOptions();
		}
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
									<label for="view_public_entries">
										<input type="hidden" value="false" name="settings[view_public_entries"/>
										<input type="checkbox" value="true" name="settings[view_public_entries]" id="view_public_entries" 
											<?php if ($plugin_options->getAllowPublic() == true) echo CHECKED ?>
										/>
										Allow unregistered visitors and users not logged in
									</label>
									NOTE: This setting has precedents over the private override shortcode attribute.
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
									<label for="image_thumbnail_x">Thumbnail Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_thumbnail_x" name="image_thumbnail_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_thumbnail_y">Thumbnail Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_thumbnail_y" name="image_thumbnail_y"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_x">Entry Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_entry_x" name="image_entry_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_entry_y">Entry Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_entry_y" name="image_entry_y"/>px
								</td>
							</tr>
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_x">Profile Width</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_profile_x" name="image_profile_x"/>px
								</td>
							</tr>				
							
							<tr valign="top">
								<th scope="row">
									<label for="image_profile_y">Profile Height</label>
								</th>
								<td>
									<input type="text" class="small-text" value="" id="image_profile_y" name="image_profile_y"/>px
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