<?php

	$plugin_options = new pluginOptions($current_user->ID);

	if (isset($_POST['submit']))
	{
		$role_to_level = array('subscriber'=>0, 'contributer'=>1, 'author'=>2, 'editor'=>3, 'admin'=>8);
		
		$plugin_options->setRoleMain($role_to_level[$_POST['role-main']]);
		$plugin_options->setRoleChangeSettings($role_to_level[$_POST['role-settings']]);
		$plugin_options->setRoleViewHelp($role_to_level[$_POST['role-help']]);
		$plugin_options->saveOptions();
		
		//print_r($_POST);
		
	}
//print_r($plugin_options->getRoleMain() . '<br />');
//print_r($plugin_options->getRoleChangeSettings() . '<br />');
//print_r($plugin_options->getRoleViewHelp() . '<br />');
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
		<div class="form-field connectionsform">
			
			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">
							<label for="role-main">Main</label>
						</th>
						<td>
							<select id="role-plugin" name="role-main">
								<?php wp_dropdown_roles($plugin_options->getRoleMain()) ?>
							</select>
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