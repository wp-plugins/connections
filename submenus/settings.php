<?php

	global $current_user;

	get_currentuserinfo();
	$plugin_options = new pluginOptions($current_user->ID);
	
	//if ($plugin_options->getRoleMain() == 'testing') $plugin_options->setRoleMain('subscriber');
	//$plugin_options->saveOptions();
	
	//echo $plugin_options->getRoleMain();
	//echo $plugin_options->getVersion();

?>
	<div class="wrap">
		<div id="icon-connections" class="icon32">
	        <br>
	    </div>
		
		<h2>Connections : Settings</h2>
		
		<div class="updated fade below-h2" id="message">
			<p><strong>In the next version you will be able to set default for the images sizes.</strong></p> 
		</div>
		
		<h3>Roles</h3>
		<div class="form-field connectionsform">
			<form action="admin.php?page=connections/submenus/settings.php" method="post">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<th scope="row">
								<label for="role-plugin">Main</label>
							</th>
							<td>
								<select id="role-plugin">
									<?php wp_dropdown_roles($plugin_options->getRoleMain()) ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="role-settings">Change Settings</label>
							</th>
							<td>
								<select id="role-settings">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row">
								<label for="role-entry-view-private">View Private Entries</label>
							</th>
							<td>
								<select id="role-entry-view-private">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>
						</tr>
						
						<tr valign="top">
							<th scope="row">
								<label for="role-entry-view-unlisted">View Unlisted Entries</label>
							</th>
							<td>
								<select id="role-entry-view-unlisted">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>
						</tr>
	
						
						<tr valign="top">
							<th scope="row">
								<label for="role-entry-add">Add Entry</label>
							</th>
							<td>
								<select id="role-entry-add">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="role-entry-edit">Edit Entry</label>
							</th>
							<td>
								<select id="role-entry-edit">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row">
								<label for="role-entry-delete">Delete Entry</label>
							</th>
							<td>
								<select id="role-entry-delete">
									<?php wp_dropdown_roles() ?>
								</select>
							</td>					
						</tr>
					</tbody>
				</table>
				
			</form>
			
		</div>
		<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="Submit" /></p>
	</div>
	<div class="clear"></div>