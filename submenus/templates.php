<?php
function connectionsShowTemplatesPage()
{
	/*
	 * Check whether user can edit Settings
	 */
	if (!current_user_can('connections_manage_template'))
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
		global $connections;
		
		$form = new cnFormObjects();
		
		$connections->displayMessages();
		
		/**
		 * Find the available templates --> START <--
		 */
		$templatePaths = array(CN_TEMPLATE_PATH, CN_CUSTOM_TEMPLATE_PATH);
		
		foreach ($templatePaths as $templatePath)
		{
			$templateDirectories = opendir($templatePath);
			
			while ( ( $templateDirectory = readdir($templateDirectories) ) !== FALSE )
			{
				if ( is_dir($templatePath . '/' . $templateDirectory) && is_readable($templatePath . '/' . $templateDirectory) )
				{
					if ( file_exists($templatePath . '/' . $templateDirectory . '/meta.php') )
					{
						$templates[$templateDirectory] = array( 'template_meta' => $templatePath . '/' . $templateDirectory . '/meta.php', 'template_path' => $templatePath . '/' . $templateDirectory);
						$templates[$templateDirectory]['type'] = ( $templatePath === CN_TEMPLATE_PATH ) ? 'default' : 'custom';
					}
				}
			}
			
			closedir($templateDirectories);
		}
		/**
		 * Find the available templates --> END <--
		 */
		
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Templates</h2>
			
			<table cellspacing="0" cellpadding="0" id="availablethemes">
				<tbody>
					<tr>
						<td class="current_template" colspan="3">
							<h2>Current Template</h2>
							
							<div id="current-theme">
								<?php
								$currentTemplate = $connections->options->getActiveTemplate();
								
								$template = new stdClass();
								$author = '';
								$template->slug = $currentTemplate;
								include_once( $templates[$currentTemplate]['template_meta'] );
								
								if ( file_exists( $templates[$currentTemplate]['template_path'] . '/thumbnail.png' ) )
								{
									if ( $templates[$currentTemplate]['type'] === 'custom' )
									{
										echo '<div class="current-template"><img class="template-thumbnail" src="' . CN_CUSTOM_TEMPLATE_URL . '/' . $template->slug . '/thumbnail.png' . '" /></div>';
									}
									else
									{
										echo '<div class="current-template"><img class="template-thumbnail" src="' . CN_TEMPLATE_URL . '/' . $template->slug . '/thumbnail.png' . '" /></div>';
									}
								}
								
								if ( isset($template->uri) )
								{
									$author = '<a title="Visit author\'s homepage." href="http://' . esc_attr($template->uri) . '">' . esc_attr($template->author) . '</a>';
								}
								else
								{
									$author = esc_attr($template->author);
								}
								
								echo '<h3>', esc_attr($template->name), ' ', esc_attr($template->version), ' by ', $author, '</h3>';
								echo '<p class="theme-description">', esc_attr($template->description), '</p>';
								
								// Remove the current active template.
								unset( $templates[$currentTemplate] );
								?>
							</div>
							<div class="clear"></div>
						</td>
					</tr>
					
					<tr>
						<td class="install_template" colspan="3">
							<h2>Install Template</h2>
							
							<?php 
							$formAttr = array(
										 'action' => 'admin.php?page=connections_templates&action=install',
										 'method' => 'post',
										 'enctype' => 'multipart/form-data'
										 );
							
							$form->open($formAttr);
							$form->tokenField('install_template');
							?>
							
							<p>
								<label for='template'>Select Template:
									<input type='file' value='' name='template' size='25' />
								</label>
								<input type="submit" value="Install Now" class="button">
							</p>
							
							<?php $form->close(); ?>
						</td>
					</tr>
					
					<tr>
						<td class="current_template" colspan="3">
							<h2>Available Templates</h2>
						</td>
					</tr>
					
					<?php
					$templateNames = array_keys($templates);
					natcasesort($templateNames);
					
					$table = array();
					$rows = ceil(count($templates) / 3);
					for ( $row = 1; $row <= $rows; $row++ )
						for ( $col = 1; $col <= 3; $col++ )
							$table[$row][$col] = array_shift($templateNames);
					
					foreach ( $table as $row => $cols )
					{
					?>
						<tr>
							<?php
							foreach ( $cols as $col => $templateName )
							{
								$activateTokenURL = NULL;
								$deleteTokenURL = NULL;
								
								$class = array('available-theme');
								if ( $row == 1 ) $class[] = 'top';
								if ( $row == $rows ) $class[] = 'bottom';
								if ( $col == 1 ) $class[] = 'left';
								if ( $col == 3 ) $class[] = 'right';
							?>
								<td class="<?php echo join(' ', $class); ?>">
								<?php
								if ( isset( $templates[$templateName]['template_meta'] ) )
								{
									$template = new stdClass();
									$author = '';
									$template->slug = $templateName;
									include_once( $templates[$templateName]['template_meta'] );
									
									if ( file_exists( $templates[$templateName]['template_path'] . '/thumbnail.png' ) )
									{
										if ( $templates[$templateName]['type'] === 'custom' )
										{
											echo '<div class="center-thumbnail"><img class="template-thumbnail" src="' . CN_CUSTOM_TEMPLATE_URL . '/' . $template->slug . '/thumbnail.png' . '" /></div>';
										}
										else
										{
											echo '<div class="center-thumbnail"><img class="template-thumbnail" src="' . CN_TEMPLATE_URL . '/' . $template->slug . '/thumbnail.png' . '" /></div>';
										}
									}
									
									if ( isset($template->uri) )
									{
										$author = '<a title="Visit author\'s homepage." href="http://' . esc_attr($template->uri) . '">' . esc_attr($template->author) . '</a>';
									}
									else
									{
										$author = esc_attr($template->author);
									}
									
									echo '<h3>', esc_attr($template->name), ' ', esc_attr($template->version), ' by ', $author, '</h3>';
									echo '<p class="description">', esc_attr($template->description), '</p>';
									if ( $templates[$templateName]['type'] === 'default' ) echo '<p>This a supplied template and can not be deleted.</p>';
								?>
									<span class="action-links">
										<?php
										$activateTokenURL = $form->tokenURL( 'admin.php?page=connections_templates&action=activate&template=' . esc_attr($template->slug), 'activate_' . esc_attr($template->slug) );
										
										if ( $templates[$templateName]['type'] === 'custom' )
										{
											$deleteTokenURL = $form->tokenURL( 'admin.php?page=connections_templates&action=delete&template=' . esc_attr($template->slug), 'delete_' . esc_attr($template->slug) );
										}
										
										?>
										
										<a class="activatelink" href="<?php echo esc_attr($activateTokenURL); ?>" title="Activate '<?php echo esc_attr($template->name); ?>'">Activate</a>
									
										<?php
										if ( isset($deleteTokenURL) )
										{
										?>
											 | <a class="deletelink" href="<?php echo esc_attr($deleteTokenURL); ?>" title="Delete '<?php echo esc_attr($template->name); ?>'" onclick="return confirm('You are about to delete this theme \'<?php echo esc_attr($template->name); ?>\'\n  \'Cancel\' to stop, \'OK\' to delete.');">Delete</a>
										<?php
										}
										?>
									<?php
									}
									?>
									</span>
								</td>
							<?php
							}
							?>
						</tr>
					<?php
					}
					?>
					
					
				</tbody>
			</table>
			
		</div>
	<?php
	}
}
?>