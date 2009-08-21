<?php
/*
Plugin Name: Connections
Plugin URI: http://www.shazahm.net/?page_id=111
Description: An address book.
Version: 0.5.1
Author: Steven A. Zahm
Author URI: http://www.shazahm.net

Connections is based on Little Black Book  1.1.2 by Gerald S. Fuller which was based on
Little Black Book is based on Addressbook 0.7 by Sam Wilson
----------------------------------------
    Copyright (C)  2008  Steven A. Zahm

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	http://www.gnu.org/licenses/
----------------------------------------
*/

//session_start();
//$_SESSION['connections']['active'] = true;
//session_write_close();

define('CN_IMAGE_PATH', WP_CONTENT_DIR . "/connection_images/");
define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . "/connection_images/");
define('CN_TABLE_NAME','connections');
define('CN_CURRENT_VERSION', '0.5.32');

// This adds the menu items WordPress and calls the function to load my CSS and JS.
add_action('admin_menu', 'connections_menus');
function connections_menus()
{
	@session_start();
	$_SESSION['connections']['active'] = true;
	session_write_close();
	
	//GPL PHP upload class from http://www.verot.net/php_class_upload.htm
	require_once(WP_PLUGIN_DIR . '/connections/includes/php_class_upload/class.upload.php');
	
	//SQL objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.sql.php');
	//HTML FORM objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.form.php');
	//date objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.date.php');
	//entry objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.entry.php');
	//plugin option objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.options.php');
	//plugin utility objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.utility.php');
	//plugin template objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.output.php');
	//builds vCard
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.vcard.php');
	
	//Adds Connections to the top level menu.
	//add_menu_page('Connections : Administration', 'Connections', 'connections_view_entry_list', 'connections/connections.php', '_connections_main', WP_PLUGIN_URL . '/connections/images/menu.png');
	add_menu_page('Connections : Administration', 'Connections', 'manage_options', 'connections/connections.php', '_connections_main', WP_PLUGIN_URL . '/connections/images/menu.png');
	
	//Adds the Connections sub-menus.
	add_submenu_page('connections/connections.php', 'Connections : Entry List', 'Entry List', 'connections_view_entry_list', 'connections/connections.php', '_connections_main');
	add_submenu_page('connections/connections.php', 'Connections : Add Entry','Add Entry', 'connections_add_entry','connections/submenus/add.php');
	add_submenu_page('connections/connections.php', 'Connections : Settings','Settings', 'connections_change_settings','connections/submenus/settings.php');
	add_submenu_page('connections/connections.php', 'Connections : Roles &amp; Capabilites','Roles', 'connections_change_roles','connections/submenus/roles.php');
	add_submenu_page('connections/connections.php', 'Connections : Help','Help', 'connections_view_help','connections/submenus/help.php');
	
	// Call the function to add the CSS and JS only on pages related to the Connections plug-in.
	add_action( 'admin_print_scripts-toplevel_page_connections/connections', 'connections_loadjs_admin_head' );
	add_action( 'admin_print_styles-toplevel_page_connections/connections', 'connections_loadcss_admin_head' );
	
	add_action( 'admin_print_scripts-connections/submenus/add.php', 'connections_loadjs_admin_head' );
	add_action( 'admin_print_styles-connections/submenus/add.php', 'connections_loadcss_admin_head' );
	
	add_action( 'admin_print_scripts-connections/submenus/settings.php', 'connections_loadjs_admin_head' );
	add_action( 'admin_print_styles-connections/submenus/settings.php', 'connections_loadcss_admin_head' );
	
	add_action( 'admin_print_scripts-connections/submenus/roles.php', 'connections_loadjs_admin_head' );
	add_action( 'admin_print_styles-connections/submenus/roles.php', 'connections_loadcss_admin_head' );
	
	add_action( 'admin_print_scripts-connections/submenus/help.php', 'connections_loadjs_admin_head' );
	add_action( 'admin_print_styles-connections/submenus/help.php', 'connections_loadcss_admin_head' );
}

// The JS is only loaded on admin pages related to the Connections plug-in.
function connections_loadjs_admin_head() {
	//wp_enqueue_script('jquery');
	wp_enqueue_script('load_ui_js', WP_PLUGIN_URL . '/connections/js/ui.js');
	//wp_enqueue_script('load_jquery_plugin', WP_PLUGIN_URL . '/connections/js/jquery.template.js');
}

// The CSS is only loaded on admin pages related to the Connections plug-in.
function connections_loadcss_admin_head() {
	wp_enqueue_style('load_admin_css', WP_PLUGIN_URL . '/connections/css-admin.css');
}

// Queues up the scripts on the posts/pages.
add_action('wp_print_scripts', 'connections_loadjs_head');
function connections_loadjs_head() {
	
	/**
	 * @TODO: enqueuing the built-in jQuery breaks the Fancy Theme 2.0 by Mip Design Studio at http://www.mip-design.com/
	 * Is there a way to fix this???
	 */
	
	wp_enqueue_script('jquery');
	wp_enqueue_script('thickbox');
}

// Adds the plug-in CSS to the pages/posts.
add_action('wp_print_styles', 'connections_loadcss_head');
function connections_loadcss_head() {
	/*
	$styles = WP_PLUGIN_URL . '/connections/css-admin.css';
	
	wp_register_style('connections_styles', $styles);
	wp_enqueue_style('connections_styles');
	*/
}

function _connections_main()
{
	global $wpdb, $current_user, $defaultConnectionGroupValues;
	$sql = new sql();
	
	get_currentuserinfo();
	$plugin_options = new pluginOptions($current_user->ID);
	
	if ($wpdb->get_var("SHOW TABLES LIKE '{$sql->getTableName()}'")!= $sql->getTableName() || $plugin_options->getVersion() != CN_CURRENT_VERSION )
	{
        /* 
         * Call the install function here rather than through the more usual
         * activate_blah.php action hook so the user doesn't have to worry about
         * deactivating then reactivating the plugin.  Should happen seamlessly.
		 */
        _connections_install();
        echo "<div id='message' class='updated fade'>
            <p><strong>The Connections plug-in version " . $plugin_options->getVersion() . " has been installed or upgraded.</strong></p>
        </div>";
    }
	
	/*
	 * Run a quick check to see if the $_SESSION is started and verify that Connections data isn't being
	 * overwritten and notify the user of errors.
	 */
	if (!$_SESSION)
	{
		echo '<div id="notice" class="error">';
			echo '<p><strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in or site setup is preventing it from being used.</strong></p>';
		echo '</div>';
	}
	elseif (!$_SESSION['connections']['active'] == true)
	{
		echo '<div id="notice" class="error">';
			echo '<p><strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in seems to be overwritting the values for Connections.</strong></p>';
		echo '</div>';
	}
	
    if ($_GET['action']=='editform')
	{
		/*
		 * Check whether current user can edit or copy/add an entry
		 */
		if (!current_user_can('connections_edit_entry') && !current_user_can('connections_add_entry'))
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
			$entryForm = new entryForm();
			$entry = new entry();
			$entry = $entry->get($_GET['id']);
			
			if (isset($_GET['copyid']))
			{
				$formID = "entry_form";
				$formAction = "add";
				$inputName = "save";
			}
			else
			{
				$formID = "entry_form";
				$formAction = "update";
				$inputName = "save";
			}
					
?>
			<div class="wrap">
				<div class="form-wrap" style="width:600px; margin: 0 auto;">
					<h2><a name="new"></a>Edit Entry</h2>
					
					<form action="admin.php?page=connections/connections.php&action=<?php echo $formAction ?>&id=<?php echo $entry->id; ?>" method="post" enctype="multipart/form-data">
					<?php 
						echo $entryForm->entryForm($entry);
					?>
					<input type="hidden" name="formId" value="<?php echo $formID ?>" />
					<input type="hidden" name="token" value="<?php echo _formtoken($formID); ?>" />
					
					<p class="submit">
						<input  class="button-primary" type="submit" name="<?php echo $inputName ?>" value="Save" />
						<a href="admin.php?page=connections/connections.php" class="button button-warning">Cancel</a> <!-- THERE HAS TO BE A BETTER WAY THAN REFERRING DIRECTLY TO THE TOOLS.PHP -->
					</p>
					</form>
				</div>
			</div>
<?php	
		}
			unset($entry);
	}
	else
	{
	    	
		/*
		 * Check whether user can view the entry list
		 */
		if(!current_user_can('connections_view_entry_list'))
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
			
        if ($_POST['save'] && $_SESSION['connections']['formTokens']['entry_form']['token'] === $_POST['token'])
		{
			$entryForm = new entryForm();
			echo $entryForm->processEntry();
		}
		
		if ($_POST['doaction'] AND $_SESSION['connections']['formTokens']['do_action']['token'] == $_POST['token'])
		{
			if ($_POST['action'] != "delete")
			{
				$checked = $_POST['entry'];
				
				foreach ($checked as $id)
				{
					$entry = new entry();
					$entry->set($id);
					
					$entry->setVisibility($_POST['action']);
					$entry->update();
					unset($entry);
				}
					
				echo "<div id='message' class='updated fade'>";
					echo "<p><strong>Entry(ies) visibility have been updated.</strong></p>";
				echo "</div>";
				unset($_SESSION['connections']['formTokens']);
			}
			
			if ($_POST['action'] == "delete")
			{
				$checked = $_POST['entry'];
				
				foreach ($checked as $id)
				{
					$entry = new entry();
					$entry->delete($id);
					unset($entry);
				}
					
				echo "<div id='message' class='updated fade'>";
					echo "<p><strong>Entry(ies) have been deleted.</strong></p>";
				echo "</div>";
				unset($_SESSION['connections']['formTokens']);
			}
		}
		
		if ($_GET['action']=='delete' AND $_SESSION['connections']['formTokens']['delete_'.$_GET['id']]['token'] == $_GET['token'])
		{
	        $entry = new entry();
			$entry->delete($_GET['id']);
			echo '<div id="message" class="updated fade"><p><strong>The entry has been deleted.</strong></p></div>';
			unset($entry);
			unset($_SESSION['connections']['formTokens']);
	    }
		
		if ($_POST['dofilter'])
		{
			$plugin_options->setEntryType($_POST['entry_type']);
			$plugin_options->setVisibilityType($_POST['visibility_type']);
			
			$plugin_options->saveOptions();
		}
		
		?>

		<div class="wrap">
			<div class="icon32" id="icon-connections"><br/></div>
			<h2>Connections : Entry List</h2>
			
			<?php
				/**
				 * The stored visibility filter for the current user is checked against
				 * the current user's capabilites; if the current user IS NOT permitted
				 * the query string is set not to query the visibility type and then the
				 * current users filter is set to NULL to show all. IF the current user
				 * IS permitted the query string will query the visibility type. Finally
				 * the remain visibility types are checked and if NOT permitted that is
				 * appened to the query string.
				 */
				switch ($plugin_options->getVisibilityType())
				{
					case 'public':
						if (!current_user_can('connections_view_public') && !$plugin_options->getAllowPublic())
						{
							$visibilityfilter = " AND NOT visibility='public' ";
							$plugin_options->setVisibilityType('');
							$plugin_options->saveOptions();
						}
						else
						{
							$visibilityfilter = " AND visibility='public' ";
						}
						if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
						if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
												
						break;
						
					case 'private':
						if (!current_user_can('connections_view_private'))
						{
							$visibilityfilter = " AND NOT visibility='private' ";
							$plugin_options->setVisibilityType('');
							$plugin_options->saveOptions();
						}
						else
						{
							$visibilityfilter = " AND visibility='private' ";
						}
						if (!current_user_can('connections_view_public') && !$plugin_options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
						if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
						
						break;
						
					case 'unlisted':
						if (!current_user_can('connections_view_unlisted'))
						{
							$visibilityfilter = " AND NOT visibility='unlisted' ";
							$plugin_options->setVisibilityType('');
							$plugin_options->saveOptions();
						}
						else
						{
							$visibilityfilter = " AND visibility='unlisted' ";
						}
						if (!current_user_can('connections_view_public') && !$plugin_options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
						if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
						
						break;
					
					default:
						if (!current_user_can('connections_view_public') && !$plugin_options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
						if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
						if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
						break;
				}
				
				$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = ''" . $visibilityfilter . ")
						UNION
						(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != ''" . $visibilityfilter . ")
						UNION
						(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibilityfilter . ")
						ORDER BY order_by, last_name, first_name";
				$results = $wpdb->get_results($sql);
			?>
			
				
				<?php
				/*
				 * Check whether user can view the entry list
				 */
				if(current_user_can('connections_view_entry_list'))
				{
				?>
					
						<form action="admin.php?page=connections/connections.php" method="post">
						
						<div class="tablenav">
							
							<?php
							$form = new formObjects();
							
							if (current_user_can('connections_edit_entry') || current_user_can('connections_delete_entry'))
							{
								echo '<div class="alignleft actions">';
									echo '<select name="action">';
										echo '<option value="" SELECTED>Bulk Actions</option>';
										
											if (current_user_can('connections_edit_entry'))
											{
												echo '<option value="public">Set Public</option>';
												echo '<option value="private">Set Private</option>';
												echo '<option value="unlisted">Set Unlisted</option>';
											}
											
											if (current_user_can('connections_delete_entry'))
											{
												echo '<option value="delete">Delete</option>';
											}
																					
									echo '</select>';
									echo '<input id="doaction" class="button-secondary action" type="submit" name="doaction" value="Apply" />';
								echo '</div>';
							}
							?>
							
							<div class="alignleft actions">
								<?php echo $form->buildSelect('entry_type', array(''=>'Show All Enties', 'individual'=>'Show Individuals', 'organization'=>'Show Organizations', 'connection_group'=>'Show Connection Groups'), $plugin_options->getEntryType())?>
								
								<?php
									/**
									 * Builds the visibilty select list base on current user capabilities.
									 */
									if (current_user_can('connections_view_public') || $plugin_options->getAllowPublic()) $visibilitySelect['public'] = 'Show Public';
									if (current_user_can('connections_view_private'))	$visibilitySelect['private'] = 'Show Private';
									if (current_user_can('connections_view_unlisted'))	$visibilitySelect['unlisted'] = 'Show Unlisted';
									
									if (isset($visibilitySelect))
									{
										$showAll[''] = 'Show All';
										$visibilitySelect = $showAll + $visibilitySelect;
										echo $form->buildSelect('visibility_type', $visibilitySelect, $plugin_options->getVisibilityType());
									}
								?>
								<input id="doaction" class="button-secondary action" type="submit" name="dofilter" value="Filter" />
								<input type="hidden" name="formId" value="do_action" />
								<input type="hidden" name="token" value="<?php echo _formtoken("do_action"); ?>" />
							</div>
						</div>
						<div class="clear"></div>
						
				       	<table cellspacing="0" class="widefat connections">
							<thead>
								<tr><th colspan="5" style="text-align:center;"><?php echo _build_alphaindex(); ?></th></tr>
							</thead>
							<thead>
					            <tr>
					                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th><th scope="col" colspan="2" style="width:40%;">Name</th><th scope="col" style="width:35%;">Visibility</th><th scope="col" style="width:25%;">Last Modified</th>
					            </tr>
							</thead>
							<tfoot>
					            <tr>
					                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th><th scope="col" colspan="2" style="width:40%;">Name</th><th scope="col" style="width:35%;">Visibility</th><th scope="col" style="width:25%;">Last Modified</th>
					            </tr>
							</tfoot>
							<tbody>
								
								<?php
								
								foreach ($results as $row) {
									$entry = new entry($row);
									$addressObject = new addresses();
									$phoneNumberObject = new phoneNumber();
									$emailAddressObject = new email();
									$imObject = new im();
									$websiteObject = new website();
									
									$object = new output($row);
									
									/*
									 * This is to skip any entries that are not of the selected type when being filtered.
									 */
									if ($plugin_options->getEntryType() != "" )	{
										if ($entry->getEntryType() != $plugin_options->getEntryType()) continue;
									}
									
									/*
									 * Check whether the current user is permitted to view public, private or unlisted entries
									 * and filter those out where permission has not been granted.
									 * 
									 * This should be uneeded as the query should only query the entries that are permitted for
									 * display for the current user.
									 */
									//if ($entry->getVisibility() == 'public' && !current_user_can('connections_view_public') && !$plugin_options->getAllowPublic()) continue;
									//if ($entry->getVisibility() == 'private' && !current_user_can('connections_view_private')) continue;
									//if ($entry->getVisibility() == 'unlisted' && !current_user_can('connections_view_unlisted')) continue;
																			
									//Checks the first letter of the last name to see if it is the next letter in the alpha array and sets the anchor.
									$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
									if ($currentLetter != $previousLetter) {
										$setAnchor = "<a name='$currentLetter'></a>";
										$previousLetter = $currentLetter;
									} else {
										$setAnchor = null;
									}
									
									echo "<tr id='row" . $entry->getId() . "' class='parent-row'>";
										echo "<th class='check-column' scope='row'><input type='checkbox' value='" . $entry->getId() . "' name='entry[]'/></th> \n";
											echo '<td colspan="2">';
											if ($setAnchor) echo $setAnchor;
											echo '<div style="float:right"><a href="#wphead" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" /></a></div>';
												
												if (current_user_can('connections_edit_entry'))
												{
													echo '<a class="row-title" title="Edit ' . $entry->getFullFirstLastName() . '" href="admin.php?page=connections/connections.php&action=editform&id=' . $row->id . '"> ' . $entry->getFullLastFirstName() . '</a><br />';
												}
												else
												{
													echo '<strong>' . $entry->getFullLastFirstName() . '</strong>';
												}
												
												echo '<div class="row-actions">';
													echo '<a class="detailsbutton" id="row-' . $entry->getId() . '">Show Details</a> | ';
													if (current_user_can('connections_edit_entry')) echo '<a class="editbutton" href="admin.php?page=connections/connections.php&action=editform&id=' . $entry->getId() . '&editid=true" title="Edit ' . $entry->getFullFirstLastName() . '">Edit</a> | ';
													if (current_user_can('connections_add_entry')) echo '<a class="copybutton" href="admin.php?page=connections/connections.php&action=editform&id=' . $entry->getId() . '&copyid=true" title="Copy ' . $entry->getFullFirstLastName() . '">Copy</a> | ';
													if (current_user_can('connections_delete_entry')) echo '<a class="submitdelete" onclick="return confirm(\'You are about to delete this entry. \\\'Cancel\\\' to stop, \\\'OK\\\' to delete\');" href="admin.php?page=connections/connections.php&action=delete&id=' . $entry->getId() . '&token=' . _formtoken('delete_' . $entry->getId()) . '" title="Delete ' . $entry->getFullFirstLastName() . '">Delete</a>';
												echo '</div>';
										echo "</td> \n";
										echo "<td ><strong>" . $entry->displayVisibiltyType() . "</strong></td> \n";												
										echo "<td >" . $entry->getFormattedTimeStamp() . "</td> \n";											
									echo "</tr> \n";
									
									echo "<tr class='child-row-" . $entry->getId() . " entrydetails' id='contact-" . $entry->getId() . "-detail' style='display:none;'>";
										echo "<td ></td> \n";
										echo "<td colspan='2'>";
											
											if ($entry->getConnectionGroup())
											{
												$connections = $entry->getConnectionGroup();
												$count = count($entry->getConnectionGroup());
												$i = 0;
												
												foreach ($connections as $key => $value)
												{
													$relation = new entry();
													$relation->set($key);
													echo '<strong>' . $defaultConnectionGroupValues[$value] . ':</strong> ' . '<a href="admin.php?page=connections/connections.php&action=editform&id=' . $relation->getId() . '&editid=true" title="Edit ' . $relation->getFullFirstLastName() . '">' . $relation->getFullFirstLastName() . '</a>' . '<br />' . "\n";
													if ($count - 1 == $i) echo '<br />'; // Insert a break after all connections are listed.
													$i++;
													unset($relation);
												}
												unset($i);
												unset($count);
											}
											
											if ($entry->getTitle()) echo "<strong>Title:</strong><br />" . $entry->getTitle() . "<br /><br />";
											if ($entry->getOrganization() && $entry->getEntryType() != "organization" ) echo "<strong>Organization:</strong><br />" . $entry->getOrganization() . "<br /><br />";
											if ($entry->getDepartment()) echo "<strong>Department:</strong><br />" . $entry->getDepartment() . "<br /><br />";
											
											if ($entry->getAddresses())
											{
												foreach ($entry->getAddresses() as $addressRow)
												{
													echo "<div style='margin-bottom: 10px;'>";
													if ($addressObject->getName($addressRow) != null || $addressObject->getType($addressRow)) echo "<strong>" . $addressObject->getName($addressRow) . "</strong><br />"; //The OR is for compatiblity for 0.2.24 and under
													if ($addressObject->getLineOne($addressRow) != null) echo $addressObject->getLineOne($addressRow) . "<br />";
													if ($addressObject->getLineTwo($addressRow) != null) echo $addressObject->getLineTwo($addressRow) . "<br />";
													if ($addressObject->getCity($addressRow) != null) echo $addressObject->getCity($addressRow) . "&nbsp;";
													if ($addressObject->getState($addressRow) != null) echo $addressObject->getState($addressRow) . "&nbsp;";
													if ($addressObject->getZipCode($addressRow) != null) echo $addressObject->getZipCode($addressRow) . "<br />";
													if ($addressObject->getCountry($addressRow) != null) echo $addressObject->getCountry($addressRow);
													echo "</div>";														
												}
											}
										echo "</td> \n";
										
										echo "<td>";
											if ($entry->getEmailAddresses())
											{
												foreach ($entry->getEmailAddresses() as $emailRow)
												{
													if ($emailAddressObject->getAddress($emailRow) != null) echo "<strong>" . $emailAddressObject->getName($emailRow) . ":</strong><br /><a href='mailto:" . $emailAddressObject->getAddress($emailRow) . "'>" . $emailAddressObject->getAddress($emailRow) . "</a><br /><br />";
												}
											}
											
											if ($entry->getIm())
											{
												foreach ($entry->getIm() as $imRow)
												{
													if ($imObject->getId($imRow) != "") echo "<strong>" . $imObject->getName($imRow) . ":</strong><br />" . $imObject->getId($imRow) . "<br /><br />";
												}
											}
											
											if ($entry->getWebsites())
											{
												foreach ($entry->getWebsites() as $websiteRow)
												{
													if ($websiteObject->getAddress($websiteRow) != "") echo "<strong>Website:</strong><br /><a target='_blank' href='" . $websiteObject->getAddress($websiteRow) . "'>" . $websiteObject->getAddress($websiteRow) . "</a><br /><br />";
												}
											}
											
											if ($entry->getPhoneNumbers())
											{
												foreach ($entry->getPhoneNumbers() as $phoneNumberRow) 
												{
													if ($phoneNumberObject->getNumber($phoneNumberRow) != "") echo "<strong>" . $phoneNumberObject->getName($phoneNumberRow) . "</strong>: " .  $phoneNumberObject->getNumber($phoneNumberRow) . "<br />";
												}
											}
											
										echo "</td> \n";
																				
										echo "<td>";
											if ($entry->getBirthday()) echo "<strong>Birthday:</strong><br />" . $entry->getBirthday() . "<br /><br />";
											if ($entry->getAnniversary()) echo "<strong>Anniversary:</strong><br />" . $entry->getAnniversary();
										echo "</td> \n";
									echo "</tr> \n";
									
									echo "<tr class='child-row-" . $entry->getId() . " entrynotes' id='contact-" . $entry->getId() . "-detail-notes' style='display:none;'>";
										echo "<td>&nbsp;</td> \n";
										echo "<td colspan='3'>";
											if ($entry->getBio()) echo "<strong>Bio:</strong> " . $entry->getBio() . "<br />"; else echo "&nbsp;";
											if ($entry->getNotes()) echo "<strong>Notes:</strong> " . $entry->getNotes(); else echo "&nbsp;";
										echo "</td> \n";
										echo "<td><strong>Entry ID:</strong> " . $entry->getId();
											if (!$entry->getImageLinked()) echo "<br /><strong>Image Linked:</strong> No"; else echo "<br /><strong>Image Linked:</strong> Yes";
											if ($entry->getImageLinked() && $entry->getImageDisplay()) echo "<br /><strong>Display:</strong> Yes"; else echo "<br /><strong>Display:</strong> No";
										echo "</td> \n";
									echo "</tr> \n";
																			
								} ?>
							</tbody>
				        </table>
						</form>
						<p style="font-size:smaller; text-align:center">This is version <?php echo $plugin_options->getVersion(); ?> of Connections.</p>
						
						
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align:center">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="5070255">
							<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
							<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
						</form>
						
					
				
				<?php }	?>
				
			</div>
		
		<script type="text/javascript">
			/* <![CDATA[ */
			(function($){
				$(document).ready(function(){
					$('#doaction, #doaction2').click(function(){
						if ( $('select[name^="action"]').val() == 'delete' ) {
							var m = 'You are about to delete the selected entry(ies).\n  \'Cancel\' to stop, \'OK\' to delete.';
							return showNotice.warn(m);
						}
					});
				});
			})(jQuery);
			/* ]]> */
		</script>

<?php
	}
}

//Builds an alpha index.
function _build_alphaindex() {
	$alphaindex = range("A","Z");
	
	foreach ($alphaindex as $letter) {
		$linkindex .= '<a href="#' . $letter . '">' . $letter . '</a> ';
	}
	
	return $linkindex;
}

//Function inspired from:
//http://www.melbournechapter.net/wordpress/programming-languages/php/cman/2006/06/16/php-form-input-and-cross-site-attacks/
/**
 * Adds a random token and timestamp to the $_SESSION variable
 * @return array
 * @param string $formId The form ID
 */
function _formtoken($formId) {
	/**
	 * Random number
	 * @var integer
	 */
	$token = md5(uniqid(rand(), true));
	@session_start();
	$_SESSION['connections']['formTokens'][$formId]['token'] = $token;
	$_SESSION['connections']['formTokens'][$formId]['token_timestamp'] = time();
	session_write_close();
	return $token;
}

// This installs and/or upgrades the plugin.
function _connections_install() {
	global $wpdb;
	
	get_currentuserinfo();
	$plugin_options = new pluginOptions($current_user->ID);
	
    $table_name = $wpdb->prefix."connections";
    $sql = "CREATE TABLE " . $table_name . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ts TIMESTAMP,
        first_name tinytext NOT NULL,
        last_name tinytext NOT NULL,
		title tinytext NOT NULL,
		organization tinytext NOT NULL,
		department tinytext NOT NULL,
		group_name tinytext NOT NULL,
		birthday tinytext NOT NULL,
		anniversary tinytext NOT NULL,
		bio longtext NOT NULL,
        notes longtext NOT NULL,
		addresses longtext NOT NULL,
		phone_numbers longtext NOT NULL,
		email longtext NOT NULL,
		im longtext NOT NULL,
		websites longtext NOT NULL,
		options longtext NOT NULL,
		visibility tinytext NOT NULL,
        PRIMARY KEY  (id)
    );";
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
	
	$plugin_options->setVersion(CN_CURRENT_VERSION);
	if (!$plugin_options->getAllowPublic()) $plugin_options->setAllowPublic(true);
	$plugin_options->setDefaultCapabilities();
	$plugin_options->setDefaultImageSettings();
	$plugin_options->saveOptions();
}

function _connections_get_entry_select($name,$selected=null)
{
	global $wpdb;
	//$results = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "connections ORDER BY last_name, first_name");
	
	$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = '')
			UNION
			(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != '')
			UNION
			(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != '')
			ORDER BY order_by, last_name, first_name";
	$results = $wpdb->get_results($sql);
	
    $out = '<select name="' . $name . '">';
		$out .= '<option value="">Select Entry</option>';
		foreach($results as $row)
		{
			$entry = new entry($row);
			$out .= '<option value="' . $entry->getId() . '"';
			if ($selected == $entry->getId()) $out .= ' SELECTED';
			$out .= '>' . $entry->getFullLastFirstName() . '</option>';
		}
	$out .= '</select>';
	
	return $out;
}

add_shortcode('connections_list', '_connections_list');
function _connections_list($atts, $content=null) {
	global $wpdb, $current_user;
	
	//SQL objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.sql.php');
	//HTML FORM objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.form.php');
	//date objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.date.php');
	//entry objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.entry.php');
	//plugin option objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.options.php');
	//plugin utility objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.utility.php');
	//plugin template objects
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.output.php');
	//builds vCard
	require_once(WP_PLUGIN_DIR . '/connections/includes/class.vcard.php');
	
	$atts = shortcode_atts( array(
				'allow_public_override' => 'false',
				'id' => null,
				'private_override' => 'false',
				'show_alphaindex' => 'false',
				'repeat_alphaindex' => 'false',
				'show_alphahead' => 'false',
				'list_type' => 'all',
				'last_name' => null,
				'title' => null,
				'organization' => null,
				'department' => null,
				'city' => null,
				'state' => null,
				'zip_code' => null,
				'country' => null,
				'template_name' => 'card',
				'custom_template'=>'false',
				), $atts ) ;
	
	// Setting the plugin options with a NULL if a user in not logged in
	if (is_user_logged_in())
	{
		$plugin_options = new pluginOptions($current_user->ID);
	}
	else
	{
		$plugin_options = new pluginOptions(null);
	}
	
	/**
	 * If the view public entries override shortcode attribute is not permitted the attribute is unset
	 * to ensure that only possible way the next expression will not equal false and give access to the
	 * entries is for $atts['allow_public_override'] to be set and it's value be true
	 */
	if (!$plugin_options->getAllowPublicOverride()) unset($atts['allow_public_override']);
	
	/**
	 * Check whether the public is permitted to see the entry list based on if the user is logged in,
	 * if the the settings are set to allow public entries to be listed for a user that is not logged in
	 * and if the shortcode attribute for the override is set and it's value is true. If any of these 
	 * are false access will not be granted.
	 */
	if (!$plugin_options->getAllowPublic() && !is_user_logged_in() && $atts['allow_public_override'] !== 'true')
	{
		return '<p style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center">You do not have sufficient permissions to view these entries.</p>';
	}
	else
	{	
		/*if (is_user_logged_in() or $atts['private_override'] != 'false') { 
			$visibilityfilter = " AND (visibility='private' OR visibility='public') ";
		} else {
			$visibilityfilter = " AND visibility='public' ";
		}*/
		
		if ($atts['id'] != null) $visibilityfilter = " AND id='" . $atts['id'] . "' ";
		
		$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = ''" . $visibilityfilter . ")
				UNION
				(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != ''" . $visibilityfilter . ")
				UNION
				(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibilityfilter . ")
				ORDER BY order_by, last_name, first_name";
		$results = $wpdb->get_results($sql);
				
			
		if ($results != null) {
			
			$out = '<a name="connections-list-head"></a>';
			
			/*
			 * The alpha index is only displayed if set set to true and not set to repeat using the shortcode attributes.
			 * If a alpha index is set to repeat, that is handled down separately.
			 */
			if ($atts['show_alphaindex'] == 'true' && $atts['repeat_alphaindex'] != 'true') $out .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . _build_alphaindex() . "</div>";
			
			$out .=  "<div class='connections-list'>\n";
			
			foreach ($results as $row)
			{
				$entry = new output($row);
				$vCard = new vCard($row);
				
				/**
				 * Check whether the current user, if logged in, is permitted to view public, private
				 * or unlisted entries and filter those where permission has not been granted. If unregistered
				 * visitors and users not logged in are permitted to view public entries so should a logged in
				 * user regardless of the set capability
				 * 
				 * If unregistered visitors and users not logged in; private and unlisted entries are not displayed
				 * unless the private override attribute is set to true then private entries will be displayed.
				 * 
				 * @TODO
				 * Build the query string to query only permitted entries.
				 */
				if (is_user_logged_in())
				{
					if ($entry->getVisibility() == 'public' && !current_user_can('connections_view_public') && !$plugin_options->getAllowPublic()) continue;
					if ($entry->getVisibility() == 'private' && !current_user_can('connections_view_private') && $atts['private_override'] == 'false') continue;
					if ($entry->getVisibility() == 'unlisted' && !current_user_can('connections_view_unlisted')) continue;
				}
				else
				{
					if ($entry->getVisibility() == 'private' && $atts['private_override'] == 'false') continue;
					if ($entry->getVisibility() == 'unlisted') continue;
				}
				
				/*
				 * If any of the following variables are set from a previous iteration
				 * they are unset.
				 */
				if (isset($continue)) unset($continue);
				if (isset($cities)) unset($cities);
				if (isset($states)) unset($states);
				if (isset($zipcodes)) unset($zipcodes);
				if (isset($countries)) unset($countries);
				if (isset($setAnchor)) unset($setAnchor);
				
				/*
				 * First check to make sure there is data stored in the address array.
				 * Then cycle thru each address, building separate arrays for city, state, zip and country.
				 */
				if ($entry->getAddresses())
				{
					$addressObject = new addresses;
					foreach ($entry->getAddresses() as $addressRow)
					{
						if ($addressObject->getCity($addressRow) != null) $cities[] = $addressObject->getCity($addressRow);
						if ($addressObject->getState($addressRow) != null) $states[] = $addressObject->getState($addressRow);
						if ($addressObject->getZipCode($addressRow) != null) $zipcodes[] = $addressObject->getZipCode($addressRow);
						if ($addressObject->getCountry($addressRow) != null) $countries[] = $addressObject->getCountry($addressRow);
					}			
				}
				
				/*
				 * Here we filter out the entries that are wanted based on the
				 * filter attributes that may have been used in the shortcode.
				 * 
				 * NOTE: The '@' operator is used to suppress PHP generated errors. This is done
				 * because not every entry will have addresses to populate the arrays created above.
				 */
				if ($atts['list_type'] != 'all' && $atts['list_type'] != $entry->getEntryType())			$continue = true;
				if ($entry->getLastName() != $atts['last_name'] && $atts['last_name'] != null)				$continue = true;
				if ($entry->getTitle() != $atts['title'] && $atts['title'] != null)							$continue = true;
				if ($entry->getOrganization() != $atts['organization'] && $atts['organization'] != null) 	$continue = true;
				if ($entry->getDepartment() != $atts['department'] && $atts['department'] != null) 			$continue = true;
				if (@!in_array($atts['city'], $cities) && $atts['city'] != null) 							$continue = true;
				if (@!in_array($atts['state'], $states) && $atts['state'] != null) 							$continue = true;
				if (@!in_array($atts['zip_code'], $zipcodes) && $atts['zip_code'] != null) 					$continue = true;
				if (@!in_array($atts['country'], $countries) && $atts['country'] != null) 					$continue = true;
				
				/*
				 * If any of the above filters returned true, the script will continue to the next entry.
				 */
				if ($continue == true) continue;
		
				/*
				 * Checks the first letter of the last name to see if it is the next
				 * letter in the alpha array and sets the anchor.
				 * 
				 * If the alpha index is set to repeat it will append to the anchor.
				 * 
				 * If the alpha head set to true it will append the alpha head to the anchor.
				 */
				$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
				if ($currentLetter != $previousLetter && $atts['id'] == null) {
					if ($atts['show_alphaindex'] == 'true') $setAnchor = '<a name="' . $currentLetter . '"></a>';
					
					if ($atts['show_alphaindex'] == 'true' && $atts['repeat_alphaindex'] == 'true') $setAnchor .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . _build_alphaindex() . "</div>";
					
					if ($atts['show_alphahead'] == 'true') $setAnchor .= '<h4 class="cn-alphahead">' . $currentLetter . '</h4>';
					$previousLetter = $currentLetter;
				} else {
					$setAnchor = null;
				}
				
				/*
				 * The anchor and/or the alpha head is displayed if set to true using the shortcode attributes.
				 */
				if ($atts['show_alphaindex'] == 'true' || $atts['show_alphahead'] == 'true') $out .= $setAnchor;
				
				if ($atts['custom_template'] == 'true')
				{
					if (is_dir(WP_CONTENT_DIR . '/connections_templates'))
					{
						if (file_exists(WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php'))
						{
							// Custom Template Name
							$template = WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php';
						}
						else
						{
							$out .= '<p style="color:red; font-weight:bold; text-align:center;">ERROR CUSTOM TEMPLATE DOES NOT EXIST</p>';
						}
					}
					else
					{
						$out .= '<p style="color:red; font-weight:bold; text-align:center;">ERROR CUSTOM TEMPLATE DIRECTORY DOES NOT EXSIT</p>';
					}
				}
				else
				{
					// Use the specified default template
					$template = WP_PLUGIN_DIR . '/connections/templates/' .  $atts['template_name'] . '.php';
				}
				
				if (isset($template))
				{
					$out .= '<div class="vcard">' . "\n";
						ob_start();
					    include($template);
					    $out .= ob_get_contents();
					    ob_end_clean();
					$out .= '</div>' . "\n";
				}
							
			}
			$out .= "</div>\n";
		}
		return $out;
	}
}

add_shortcode('upcoming_list', '_upcoming_list');
function _upcoming_list($atts, $content=null) {
    global $wpdb;
	
	$atts = shortcode_atts( array(
			'list_type' => 'birthday',
			'days' => '30',
			'private_override' => false,
			'date_format' => 'F jS',
			'show_lastname' => false,
			'list_title' => null,
			), $atts ) ;
	
	if (is_user_logged_in() or $atts['private_override'] != false) { 
		$visibilityfilter = " AND (visibility='private' OR visibility='public') AND (".$atts['list_type']." != '')";
	} else {
		$visibilityfilter = " AND (visibility='public') AND (".$atts['list_type']." != '')";
	}
	
	if ($atts['list_title'] == null) {
		if ($atts['list_type'] == "birthday") $list_title = "Upcoming Birthdays the next " . $atts['days'] . " days";
		if ($atts['list_type'] == "anniversary") $list_title = "Upcoming Anniversaries the next " . $atts['days'] . " days";
	} else {
		$list_title = $atts['list_title'];
	}
	
	$sql = "SELECT id, ".$atts['list_type'].", last_name, first_name FROM ".$wpdb->prefix."connections where (YEAR(DATE_ADD(CURRENT_DATE, INTERVAL ".$atts['days']." DAY))"
        . " - YEAR(FROM_UNIXTIME(".$atts['list_type'].")) )"
        . " - ( MID(DATE_ADD(CURRENT_DATE, INTERVAL ".$atts['days']." DAY),5,6)"
        . " < MID(FROM_UNIXTIME(".$atts['list_type']."),5,6) )"
        . " > ( YEAR(CURRENT_DATE)"
        . " - YEAR(FROM_UNIXTIME(".$atts['list_type'].")) )"
        . " - ( MID(CURRENT_DATE,5,6)"
        . " < MID(FROM_UNIXTIME(".$atts['list_type']."),5,6) )"
		. $visibilityfilter
		. " ORDER BY FROM_UNIXTIME(".$atts['list_type'].") ASC";

	$results = $wpdb->get_results($sql);
	
	if ($results != null) {
		$out = "<div class='connections-list' style='-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; position: relative;'>\n";
		$out .= "<div class='cn_list_title' style='font-size: large; font-variant: small-caps; font-weight: bold; text-align:center;'>" . $list_title  . "</div>";
		
		
		/*The SQL returns an array sorted by the birthday and/or anniversary date. However the year end wrap needs to be accounted for.
		Otherwise earlier months of the year show before the later months in the year. Example Jan before Dec. The desired output is to show
		Dec then Jan dates.  This function checks to see if the month is a month earlier than the current month. If it is the year is changed to the following year rather than the current.
		After a new list is built, it is resorted based on the date.*/
		foreach ($results as $row) {
			if ($row->$atts['list_type']) {
				if (date("m", $row->$atts['list_type']) <= date("m") && date("d", $row->$atts['list_type']) < date("d")) {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y") . " + 1 year");
				} else {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y"));
				}
				if (!$atts['show_lastname']) {
					$upcoming_list["<span class='name'>" . $row->first_name . " " . $row->last_name{0} . ".</span>"] .= $current_date;
				} else {
					$upcoming_list["<span class='name'>" . $row->first_name . " " . $row->last_name . "</span>"] .= $current_date;
				}
			}
		}
		asort($upcoming_list);

		foreach ($upcoming_list as $key=>$value) {
		
			if (!$setstyle == "alternate") {
				$setstyle = "alternate";
				$alternate = "background-color:#F9F9F9; ";
			} else {
				$setstyle = "";
				$alternate = "";
			}
			
				$out .= "<div class='cn_row' style='" . $alternate . "padding:2px 4px;'>" . $key . " <span class='cn_date' style='position: absolute; right:4px;'>" . date($atts['date_format'],$value) . "</span></div>";
		}
		
		$out .= "</div>\n";
		return $out;
	}
}

/**
 * @author:  Phill Pafford
 * @website: http://llihp.blogspot.com
 * 
 * @notes:
 *    Add the JavaScript and CSS to the header
 */

function contact_preview_head() {	
	$plugindir = get_bloginfo('wpurl').'/wp-content/plugins';
    
    $addToHead =<<<ADDTOHEAD
<script type='text/javascript' src='$plugindir/connections/js/jquery.contactpreview.js'></script> 

<style type="text/css">
#contact-info{
    position:absolute;
    border:1px solid #ccc;
    background:#333;
    padding:10px;
    display:none;
    color:#fff;
    width:350px;
    z-index:100;
    
    /* Rounded Corners for CSS3 */
    -moz-border-radius-topright:20px;
    -webkit-border-top-right-radius:20px;
    -moz-border-radius-bottomleft:20px;
    -webkit-border-bottom-left-radius:20px;
}
#close-contact{
    color:red;   
}
#close-contact-footer{
    text-align:right; 
}
.google-maps-link{
    color:#33CCFF; 
    text-decoration:none;   
}
.member-entry{
    -moz-border-radius:4px; 
    background-color:#FFFFFF; 
    border:1px solid #E3E3E3; 
    margin:8px 0px; 
    padding:6px; 
    position:relative;
}
.member-details{
    font-size:14px; 
    font-variant: small-caps;
}
#popup-group-name {
    color:#33CCFF; 
    font-size:13px;
    text-align:center;
    font-variant: small-caps;
}
#popup-group-members{
    color:#33CCFF; 
    font-size:15px;
    text-align:center;
    font-variant: small-caps;   
}
.m-contact{
    font-size:14px; 
    font-variant: small-caps;    
}

</style>   
    
ADDTOHEAD;

    echo $addToHead;	
}

// Add the jQuery function here 
add_action( "wp_head", 'contact_preview_head' );

?>