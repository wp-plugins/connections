<?php

/*
 * Check whether user can edit roles
 */
if (!current_user_can('connections_change_roles'))
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
	global $wp_roles;
	$plugin_options = new pluginOptions($current_user->ID);
	$plugin_options->setDefaultCapabilities();
						
	if (isset($_POST['submit']))
	{
		if (isset($_POST['roles']))
		{
			// Cycle thru each role available because checkboxes do not report a value when not checked.
			foreach ($wp_roles->get_names() as $role => $name)
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
		
		<h2>Connections : Roles &amp; Capabilities</h2>
		
		<form action="admin.php?page=connections/submenus/roles.php" method="post">
					
			<table class="widefat">
				<tbody>
					
					<?php
						foreach ($wp_roles->get_names() as $role => $name)
						{
							
							if (isset($alternateRowStyle))
							{
								unset($alternateRowStyle);
							}
							else
							{
								$alternateRowStyle = ' class="alternate" ';
							}
							
							echo '<tr ' . $alternateRowStyle . 'valign="top">';
								echo '<th scope="row">';
									echo $name;
								echo '</th>';
								echo '<td>';
									$capabilies = $plugin_options->getDefaultCapabilities();
									
									foreach ($capabilies as $capability => $capabilityName)
									{
										echo '<label for="' . $role . '_' . $capability . '">';
										echo '<input type="hidden" name="roles[' . $role . '][capabilities][' . $capability . ']" value="false" />';
										echo '<input type="checkbox" id="' . $role . '_' . $capability . '" name="roles[' . $role . '][capabilities][' . $capability . ']" value="true" '; 
										
										if ($plugin_options->hasCapability($role, $capability)) echo 'CHECKED ';
										// the admininistrator should always have all capabilities
										if ($role == 'administrator') echo 'DISABLED ';
										echo '/> ' . $capabilityName . '</label><br />' . "\n";
										
									}
								echo '</td>';
							echo '</tr>';									
						}
					
					?>

				</tbody>
			</table>
			
		<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="submit" /></p>
		
		</form>
	</div>
	<div class="clear"></div>
	
<?php } ?>