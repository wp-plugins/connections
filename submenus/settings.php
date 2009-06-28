<?php

	$plugin_options = new pluginOptions($current_user->ID);
	
	if (isset($_POST['submit']))
	{
		if (isset($_POST['roles']))
		{
			// Cycle thru each role available because checkboxes do not report a value when not checked.
			foreach ($plugin_options->getRoles() as $role => $name)
			{
				if (!isset($_POST['roles'][$role])) continue;
				
				foreach ($_POST['roles'][$role]['capabilities'] as $capability => $grant)
				{
					// the admininistrator should always have all capabilities
					if ($role == 'administrator') continue;
					
					if ($grant == 'true')
					{
						if (!$plugin_options->hasCapability($role, $capability)) $plugin_options->addCapability($role, $capability);
					}
					else
					{
						if ($plugin_options->hasCapability($role, $capability)) $plugin_options->removeCapability($role, $capability);
					}
				}
			}
		}

	}

?>
	<div class="wrap">
		<div id="icon-connections" class="icon32">
	        <br>
	    </div>
		
		<h2>Connections : Settings</h2>
		
		<div id="notice" class="error">
			<p><strong>The role support is experimental and may not function as the feature is intended.</strong></p>
		</div>
		
		<form action="admin.php?page=connections/submenus/settings.php" method="post">
		
		<h3>Roles</h3>
		<div class="connectionsform">
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="role-view-entry-list">View Entry List</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_view_entry_list">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_view_entry_list]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_view_entry_list" name="roles[' . $role . '][capabilities][connections_view_entry_list]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_view_entry_list')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-add">Add Entry</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_add_entry">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_add_entry]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_add_entry" name="roles[' . $role . '][capabilities][connections_add_entry]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_add_entry')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-edit">Edit Entry</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_edit_entry">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_edit_entry]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_edit_entry" name="roles[' . $role . '][capabilities][connections_edit_entry]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_edit_entry')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-delete">Delete Entry</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_delete_entry">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_delete_entry]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_delete_entry" name="roles[' . $role . '][capabilities][connections_delete_entry]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_delete_entry')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>					
					</tr>
												
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-view-private">View Private Entries</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_view_private">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_view_private]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_view_private" name="roles[' . $role . '][capabilities][connections_view_private]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_view_private')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-view-unlisted">View Unlisted Entries</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_view_unlisted">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_view_unlisted]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_view_unlisted" name="roles[' . $role . '][capabilities][connections_view_unlisted]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_view_unlisted')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="role-settings">Change Settings</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_change_settings">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_change_settings]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_change_settings" name="roles[' . $role . '][capabilities][connections_change_settings]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_change_settings')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-help">View Help</label>
						</th>
						<td>
							<?php
								foreach ($plugin_options->getRoles() as $role => $name)
								{
									echo '<label for="' . $role . '_view_help">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_view_help]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '_view_help" name="roles[' . $role . '][capabilities][connections_view_help]" value="true" '; 
									
									if ($plugin_options->hasCapability($role, 'connections_view_help')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div>
		<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="submit" /></p>
		
		</form>
	</div>
	<div class="clear"></div>