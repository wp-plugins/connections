<?php

	$plugin_options = new pluginOptions($current_user->ID);
	
	if (isset($_POST['submit']))
	{
		if (isset($_POST['roles']))
		{
			// Cycle thru each role available because checkboxes do not report a value when not checked.
			$wpRoleNameArray = $wp_roles->get_names();
			foreach ($wpRoleNameArray as $role => $name)
			{
				if (!isset($_POST['roles'][$role])) continue;
				
				foreach ($_POST['roles'][$role]['capabilities'] as $capability => $grant)
				{
					// the admininistrator should always have all capabilities
					if ($role == 'administrator') continue;
					
					$wpRoleDataArray = $wp_roles->roles;
					$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];

					$wpRole = new WP_Role($role, $wpRoleCaps);
					if ($grant === 'true')
					{
						if (!$wpRole->has_cap($capability)) $wpRole->add_cap($capability);
					}
					else
					{
						if ($wpRole->has_cap($capability)) $wpRole->remove_cap($capability);
					}
					unset($wpRole);
					unset($wpRoleDataArray);
					unset($wpRoleCaps);
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
								$wpRoleNameArray = $wp_roles->get_names();
								foreach ($wpRoleNameArray as $role => $name)
								{
									$wpRoleDataArray = $wp_roles->roles;
									$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];
									$wpRole = new WP_Role($role, $wpRoleCaps);
									
									echo '<label for="' . $role . '">';
									echo '<input type="hidden" name="roles[' . $role . '][capabilities][connections_view_entry_list]" value="false" />';
									echo '<input type="checkbox" id="' . $role . '" name="roles[' . $role . '][capabilities][connections_view_entry_list]" value="true" '; 
									if ($wpRole->has_cap('connections_view_entry_list')) echo 'CHECKED ';
									// the admininistrator should always have all capabilities
									if ($role == 'administrator') echo 'DISABLED ';
									echo '/> ' . $name . '</label><br />' . "\n";
									
									unset($wpRoleDataArray);
									unset($wpRoleCaps);
									unset($wpRole);
								}
							?>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-settings">Change Settings</label>
						</th>
						<td>
							<select id="role-settings" name="role-settings">
								<?php wp_dropdown_roles($plugin_options->getRoleChangeSettings()) ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-help">View Help</label>
						</th>
						<td>
							<select id="role-help" name="role-help">
								<?php wp_dropdown_roles($plugin_options->getRoleViewHelp()) ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-view-private">View Private Entries</label>
						</th>
						<td>
							<select id="role-entry-view-private" name="role-entry-view-private">
								<?php wp_dropdown_roles() ?>
							</select>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-view-unlisted">View Unlisted Entries</label>
						</th>
						<td>
							<select id="role-entry-view-unlisted" name="role-entry-view-unlisted">
								<?php wp_dropdown_roles() ?>
							</select>
						</td>
					</tr>

					
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-add">Add Entry</label>
						</th>
						<td>
							<select id="role-entry-add" name="role-entry-add">
								<?php wp_dropdown_roles() ?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-edit">Edit Entry</label>
						</th>
						<td>
							<select id="role-entry-edit" name="role-entry-edit">
								<?php wp_dropdown_roles() ?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<label for="role-entry-delete">Delete Entry</label>
						</th>
						<td>
							<select id="role-entry-delete" name="role-entry-delete">
								<?php wp_dropdown_roles() ?>
							</select>
						</td>					
					</tr>
				</tbody>
			</table>
			
		</div>
		<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="submit" /></p>
		
		</form>
	</div>
	<div class="clear"></div>