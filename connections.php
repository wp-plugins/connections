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

session_start();
$_SESSION['connections']['active'] = true;
//session_write_close();

//GPL PHP upload class from http://www.verot.net/php_class_upload.htm
require_once(WP_PLUGIN_DIR . '/connections/includes/php_class_upload/class.upload.php');

//SQL objects
require_once(WP_PLUGIN_DIR . '/connections/includes/class.sql.php');
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



// Define a few constants and defaults until I can get to creating the options page.
define('CN_DEFAULT_JPG_QUALITY', 80);
define('CN_DEFAULT_PROFILE_X', 300);
define('CN_DEFAULT_PROFILE_Y', 225);
define('CN_DEFAULT_ENTRY_X', 225);
define('CN_DEFAULT_ENTRY_Y', 150);
define('CN_DEFAULT_THUMBNAIL_X', 80);
define('CN_DEFAULT_THUMBNAIL_Y', 54);
define('CN_IMAGE_PATH', WP_CONTENT_DIR . "/connection_images/");
define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . "/connection_images/");
define('CN_TABLE_NAME','connections');
define('CN_CURRENT_VERSION', '0.5.2');

$defaultAddressTypes	=	array
							(
								''=>'Select',
								'home'=>'Home',
								'work'=>'Work',
								'school'=>'School',
								'other'=>'Other'
							);

$defaultPhoneNumberValues	=	array
								(
									array
									(
										'type'=>'homephone',
										'name'=>'Home Phone',
										'number'=>null,
										'visibility'=>'public'
									),
									array
									(
										'type'=>'homefax',
										'name'=>'Home Fax',
										'number'=>null,
										'visibility'=>'public'
									),
									array
									(
										'type'=>'cellphone',
										'name'=>'Cell Phone',
										'number'=>null,
										'visibility'=>'public'
									),
									array
									(
										'type'=>'workphone',
										'name'=>'Work Phone',
										'number'=>null,
										'visibility'=>'public'
									),
									array
									(
										'type'=>'workfax',
										'name'=>'Work Fax',
										'number'=>null,
										'visibility'=>'public'
									),
								);

$defaultEmailValues = 	array
						(
							array
							(
								'type'=>'personal',
								'name'=>'Personal Email',
								'address'=>null,
								'visibility'=>'public'
							),
							array(
								'type'=>'work',
								'name'=>'Work Email',
								'address'=>null,
								'visibility'=>'public'
							 )
						);

$defaultIMValues =	array
					(
						array
						(
							'type'=>'aim',
							'name'=>'AIM',
							'id'=>null,
							'visibility'=>'public'
						),
						array
						(
							'type'=>'yahoo',
							'name'=>'Yahoo IM',
							'id'=>null,
							'visibility'=>'public'
						),
						array
						(
							'type'=>'jabber',
							'name'=>'Jabber / Google Talk',
							'id'=>null,
							'visibility'=>'public'
						),
						array
						(
							'type'=>'messenger',
							'name'=>'Messenger',
							'id'=>null,
							'visibility'=>'public'
						),
					);

$defaultConnectionGroupValues = array(
										'' =>"Select Relation",
										'aunt' =>"Aunt",
										'brother' =>"Brother",
										'brotherinlaw' =>"Brother-in-law",
										'cousin' =>"Cousin",
										'daughter' =>"Daughter",
										'daughterinlaw' =>"Daughter-in-law",
										'father' =>"Father",
										'fatherinlaw' =>"Father-in-law",
										'granddaughter' =>"Grand Daughter",
										'grandfather' =>"Grand Father",
										'grandmother' =>"Grand Mother",
										'grandson' =>"Grand Son",
										'greatgrandmother' =>"Great Grand Mother",
										'greatgrandfather' =>"Great Grand Father",
										'husband' =>"Husband",
										'mother' =>"Mother",
										'motherinlaw' =>"Mother-in-law",
										'nephew' =>"Nephew",
										'niece' =>"Niece",
										'sister' =>"Sister",
										'sisterinlaw' =>"Sister-in-law",
										'son' =>"Son",
										'soninlaw' =>"Son-in-law",
										'stepbrother' =>"Step Brother",
										'stepdaughter' =>"Step Daughter",
										'stepfather' =>"Step Father",
										'stepmother' =>"Step Mother",
										'stepsister' =>"Step Sister",
										'stepson' =>"Step Son",
										'uncle' =>"Uncle",
										'wife' =>"Wife"
										);


//$plugin_options = new pluginOptions;

// This adds the menu items WordPress and calls the function to load my CSS and JS.
add_action('admin_menu', 'connections_menus');
function connections_menus() {
	//Adds Connections to the top level menu.
	$connections_admin = add_menu_page('Connections : Administration', 'Connections', 'connections_access', 'connections/connections.php', '_connections_main', WP_PLUGIN_URL . '/connections/images/menu.png');
	//Adds the Connections sub-menus.
	add_submenu_page('connections/connections.php', 'Connections : Settings','Settings', 'connections_change_settings','connections/submenus/settings.php');
	add_submenu_page('connections/connections.php', 'Connections : Roles','Roles', 'connections_change_roles','connections/submenus/roles.php');
	add_submenu_page('connections/connections.php', 'Connections : Help','Help', 'connections_view_help','connections/submenus/help.php');
	
	// Call the function to add the CSS and JS only on pages related to the Connections plug-in.
	/* 
	 * NOTE: I should have been able to call 'connections/connections.php' directly using the
	 * 		 admin_print_script- hook but it didn't work. I have to assign it to a variable.
	 * 		 The sub-pages worked as expected.
	 */
	add_action( "admin_print_scripts-$connections_admin", 'connections_loadjs_admin_head' );
	add_action( "admin_print_styles-$connections_admin", 'connections_loadcss_admin_head' );
	
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

function _connections_main() {
		global $wpdb, $current_user, $defaultConnectionGroupValues;
		$sql = new sql();
		
		get_currentuserinfo();
		$plugin_options = new pluginOptions($current_user->ID);
		//$plugin_options->setOptions($current_user->ID);
		
	    if ($_GET['action']=='editform')
		{
			
			/*
			 * Check whether user can edit or copy/add an entry
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
			
			$row = new entry();
			$row = $row->get($_GET['id']);
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
					
					<form action="admin.php?page=connections/connections.php&action=<?php echo $formAction ?>&id=<?php echo $row->id; ?>" method="post" enctype="multipart/form-data">
					<?php echo _connections_getaddressform($row); ?>
					<input type="hidden" name="formId" value="<?php echo $formID ?>" />
					<input type="hidden" name="token" value="<?php echo _formtoken($formID); ?>" />
					
					<?php session_write_close(); ?>
					
					<p class="submit">
						<input  class="button-primary" type="submit" name="<?php echo $inputName ?>" value="Save" />
						<a href="admin.php?page=connections/connections.php" class="button button-warning">Cancel</a> <!-- THERE HAS TO BE A BETTER WAY THAN REFERRING DIRECTLY TO THE TOOLS.PHP -->
					</p>
					</form>
				</div>
			</div>
<?php	
			unset($row);
		} else {
	    	
			/*
			 * Check whether user can access Connections
			 */
			if(!current_user_can('connections_access')) {
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
			
	        $table_name = $sql->getTableName();
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!= $table_name || $plugin_options->getVersion() != CN_CURRENT_VERSION ) {
	            // Call the install function here rather than through the more usual
	            // activate_blah.php action hook so the user doesn't have to worry about
	            // deactivating then reactivating the plugin.  Should happen seamlessly.
	            _connections_install();
	            echo "<div id='message' class='updated fade'>
	                <p><strong>The Connections plug-in version " . $plugin_options->getVersion() . " has been installed or upgraded.</strong></p>
	            </div>";
	        } ?>

			<div class="wrap">
				<div class="icon32" id="icon-connections"><br/></div>
				<h2>Connections Administration</h2>
				
				<?php
				
				if ($_POST['save'] AND $_SESSION['connections']['formTokens']['entry_form']['token'] == $_POST['token'])
				{
					$entry = new entry();
					
					// If copying/editing an entry, the entry data is loaded into the class 
					// properties and then properties are overwritten by the POST data as needed.
					if (isset($_GET['id']))
					{
						$entry->set($_GET['id']);
					}
										
					$entry->setEntryType($_POST['entry_type']);
					$entry->setGroupName($_POST['connection_group_name']);
					$entry->setConnectionGroup($_POST['connection_group']);
					$entry->setFirstName($_POST['first_name']);
					$entry->setLastName($_POST['last_name']);
					$entry->setTitle($_POST['title']);
					$entry->setOrganization($_POST['organization']);
					$entry->setDepartment($_POST['department']);
					$entry->setAddresses($_POST['address']);
					$entry->setPhoneNumbers($_POST['phone_numbers']);
					$entry->setEmailAddresses($_POST['email']);
					$entry->setIm($_POST['im']);
					$entry->setWebsites($_POST['websites']);
					$entry->setBirthday($_POST['birthday_day'], $_POST['birthday_month']);
					$entry->setAnniversary($_POST['anniversary_day'], $_POST['anniversary_month']);
					$entry->setBio($_POST['bio']);
					$entry->setNotes($_POST['notes']);
					$entry->setVisibility($_POST['visibility']);
													
					if ($_FILES['original_image']['error'] != 4) {
						$image_proccess_results = _process_images($_FILES);
						
						$entry->setImageLinked(true);
						$entry->setImageDisplay(true);
						$entry->setImageNameThumbnail($image_proccess_results['image_names']['thumbnail']);
						$entry->setImageNameCard($image_proccess_results['image_names']['entry']);
						$entry->setImageNameProfile($image_proccess_results['image_names']['profile']);
						$entry->setImageNameOriginal($image_proccess_results['image_names']['original']);
						
						$error = $image_proccess_results['error'];
						$success = $image_proccess_results['success'];
					}
					
					// If copying an entry, the image visibility property is set based on the user's choice.
					// NOTE: This must come after the image processing.
					if (isset($_POST['imgOptions']))
					{
						switch ($_POST['imgOptions'])
						{
							case 'remove':
								$entry->setImageDisplay(false);
								$entry->setImageLinked(false);
							break;
							
							case 'hidden':
								$entry->setImageDisplay(false);
							break;
							
							case 'show':
								$entry->setImageDisplay(true);
							break;
							
							default:
								$entry->setImageDisplay(false);
							break;
						}
					}
					
					switch ($_GET['action'])
					{
						case 'add':
							if ($entry->save() === FALSE)
							{
								$error = '<p><strong>Entry could not be added.</strong></p>';
							}
							$success .= "<p><strong>Entry added.</strong></p> \n";
						break;
						
						case 'update':
							if ($entry->update() === FALSE)
							{
								$error = '<p><strong>Entry could not be updated.</strong></p>';
							}
							
							$success .= "<p><strong>The entry has been updated.</strong></p> \n";
						break;
					}
										
					if (!$error)
					{
						unset($_SESSION['connections']['formTokens']);
						unset($entry);
						
						echo '<div id="message" class="updated fade">';
							echo $success;
						echo '</div>';
					}
					else
					{
						unset($_SESSION['connections']['formTokens']);
						unset($entry);
						
						echo '<div id="notice" class="error">';
							echo $error;
						echo '</div>';
					}
					
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
				
				if ($_POST['dofilter']) {
					$plugin_options->setEntryType($_POST['entry_type']);
					$plugin_options->setVisibilityType($_POST['visibility_type']);
					
					$plugin_options->saveOptions();
				}
				
				/*
				 * Run a quick check to see if the $_SESSION is started and verify that Connections data isn't being
				 * overwritten and notify the user of errors.
				 */
				if (!$_SESSION)
				{
					echo '<div id="notice" class="error">';
						echo '<p><strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in or or site setup is preventing it from being used.</strong></p>';
					echo '</div>';
				}
				elseif (!$_SESSION['connections']['active'] == true)
				{
					echo '<div id="notice" class="error">';
						echo '<p><strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in seems to be overwritting the values for Connections.</strong></p>';
					echo '</div>';
				}
				
				?>
				
				
				<?php
					/**
					 * The stored visibility filter for the current user is checked against
					 * the current user's capabilites; if the stored visibility filter is not
					 * permitted by the current user's capabilities the filter is set to NULL
					 * which will query all entries which then are filtered out based on the
					 * current user's capabilities individually further down in the code.
					 */
					/**
					 * @TODO Modify the query to query only the entries the current users can
					 * access rather than filtering them out in the loop further down in the code.
					 */
					switch ($plugin_options->getVisibilityType())
					{
						case 'public':
							if (!current_user_can('connections_view_public'))
							{
								$visibility = '';
								$plugin_options->setVisibilityType('');
							}
						break;
						
						case 'private':
							if (!current_user_can('connections_view_private'))
							{
								$visibility = '';
								$plugin_options->setVisibilityType('');
							}
						break;
						
						case 'unlisted':
							if (!current_user_can('connections_view_unlisted'))
							{
								$visibility = '';
								$plugin_options->setVisibilityType('');
							}
						break;
					}
					if ($plugin_options->getVisibilityType() != "") $visibility = " AND visibility='" . $plugin_options->getVisibilityType() . "' ";
					
					$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = ''" . $visibility . ")
							UNION
							(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != ''" . $visibility . ")
							UNION
							(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibility . ")
							ORDER BY order_by, last_name, first_name";
					$results = $wpdb->get_results($sql);
				?>
				<div id="col-container">
					
					<?php
					/*
					 * Check whether user can view the entry list
					 */
					if(current_user_can('connections_view_entry_list'))
					{
					?>
					<div id="col-right" <?php /* If the user can not add an entry; set the column width to 100% */ if (!current_user_can('connections_add_entry')) echo 'style="width: 100%"' ?>>
						<div class="col-wrap">
							
							<form action="admin.php?page=connections/connections.php" method="post">
							
							<div class="tablenav">
								
								<?php
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
												
												if (current_user_can('connections_delete_entry')) echo '<option value="delete">Delete</option>';
																						
										echo '</select>';
										echo '<input id="doaction" class="button-secondary action" type="submit" name="doaction" value="Apply" />';
									echo '</div>';
								}
								?>
								
								<div class="alignleft actions">
									<?php echo _build_select('entry_type', array(''=>'Show All Enties', 'individual'=>'Show Individuals', 'organization'=>'Show Organizations', 'connection_group'=>'Show Connection Groups'), $plugin_options->getEntryType())?>
									
									<?php
										/**
										 * Builds the visibilty select list base on current user capabilities.
										 */
										if (current_user_can('connections_view_public'))	$visibilitySelect['public'] = 'Show Public';
										if (current_user_can('connections_view_private'))	$visibilitySelect['private'] = 'Show Private';
										if (current_user_can('connections_view_unlisted'))	$visibilitySelect['unlisted'] = 'Show Unlisted';
										
										if (isset($visibilitySelect))
										{
											$showAll[''] = 'Show All';
											$visibilitySelect = $showAll + $visibilitySelect;
											echo _build_select('visibility_type', $visibilitySelect, $plugin_options->getVisibilityType());
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
										
										/**
										 * This is to skip any entries that are not of the selected type when being filtered.
										 */
										if ($plugin_options->getEntryType() != "" )	{
											if ($entry->getEntryType() != $plugin_options->getEntryType()) continue;
										}
										
										/**
										 * Check whether the current user is permitted to view public, private or unlisted entries
										 * and filter those out where permission has not been granted.
										 */
										if ($entry->getVisibility() == 'public' && !current_user_can('connections_view_public')) continue;
										if ($entry->getVisibility() == 'private' && !current_user_can('connections_view_private')) continue;
										if ($entry->getVisibility() == 'unlisted' && !current_user_can('connections_view_unlisted')) continue;
																				
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
							
						</div>
			        </div>
					
					<?php
					}
					/*
					 * Check if a user can add an entry and then display the form or not accordingly.
					 */
					if (current_user_can('connections_add_entry'))
					{ ?>
					<div id="col-left">
						<div class="col-wrap">
							<div class="form-wrap">
							<h3><a name="new"></a>Add Entry</h3>
							
								<form action="admin.php?page=connections/connections.php&action=add" method="post" enctype="multipart/form-data">
									<?php echo _connections_getaddressform(); ?>
									<input type="hidden" name="formId" value="entry_form" />
									<input type="hidden" name="token" value="<?php echo _formtoken("entry_form"); ?>" />
									
									<?php session_write_close(); ?>
									
									<p class="submit">
										<input class="button-primary" type="submit" name="save" value="Add Address" />
									</p>
								</form>
							</div>
						</div>
					</div>
					<?php }	?>
					
				</div>
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
?>
<?php
//Builds an alpha index.
function _build_alphaindex() {
	$alphaindex = range("A","Z");
	
	foreach ($alphaindex as $letter) {
		$linkindex .= '<a href="#' . $letter . '">' . $letter . '</a> ';
	}
	
	return $linkindex;
}

function _process_images($_FILES) {
	// Uses the upload.class.php to handle file uploading and image manipulation.
	
		$process_image = new Upload($_FILES['original_image']);
		$image['source'] = $process_image->file_src_name_body;
		
		if ($process_image->uploaded) {
			// Saves the uploaded image with no changes to the wp_content/connection_images/ dir.
			// If needed this will create the upload dir and chmod it.
			$process_image->auto_create_dir		= true;
			$process_image->auto_chmod_dir		= true;
			$process_image->file_safe_name		= true;
			$process_image->file_auto_rename	= true;
			$process_image->file_name_body_add= '_original';
			$process_image->image_convert		= 'jpg';
			$process_image->jpeg_quality		= CN_DEFAULT_JPG_QUALITY;
			$process_image->Process(CN_IMAGE_PATH);
			if ($process_image->processed) {
				$success .= "<p><strong>Uploaded image saved.</strong></p> \n";
				//$image_names['original'] = $process_image->file_dst_name;
				$image['original'] = $process_image->file_dst_name;
			} else {
				$error .= "<p><strong>Uploaded could not be saved to the destination folder.</strong></p> \n
						   <p><strong>Error: </strong>" . $process_image->error . "</p> \n";
			}
			
			// Creates the profile image and saves it to the wp_content/connection_images/ dir.
			// If needed this will create the upload dir and chmod it.
			$process_image->auto_create_dir		= true;
			$process_image->auto_chmod_dir		= true;
			$process_image->file_safe_name		= true;
			$process_image->file_auto_rename	= true;
			$process_image->file_name_body_add= '_profile';
			$process_image->image_convert		= 'jpg';
			$process_image->jpeg_quality		= CN_DEFAULT_JPG_QUALITY;
			$process_image->image_resize		= true;
			$process_image->image_ratio_crop	= true;
			$process_image->image_ratio_fill	= true;
			$process_image->image_y				= CN_DEFAULT_PROFILE_Y;
			$process_image->image_x				= CN_DEFAULT_PROFILE_X;
			$process_image->Process(CN_IMAGE_PATH);
			if ($process_image->processed) {
				$success .= "<p><strong>Profile image created and saved.</strong></p> \n";
				//$image_names['profile'] = $process_image->file_dst_name;
				$image['profile'] = $process_image->file_dst_name;
			} else {
				$error .= "<p><strong>Profile image could not be created and/or saved to the destination folder.</strong></p> \n
						   <p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			}						
			
			// Creates the entry image and saves it to the wp_content/connection_images/ dir.
			// If needed this will create the upload dir and chmod it.
			$process_image->auto_create_dir		= true;
			$process_image->auto_chmod_dir		= true;
			$process_image->file_safe_name		= true;
			$process_image->file_auto_rename	= true;
			$process_image->file_name_body_add= '_entry';
			$process_image->image_convert		= 'jpg';
			$process_image->jpeg_quality		= CN_DEFAULT_JPG_QUALITY;
			$process_image->image_resize		= true;
			$process_image->image_ratio_crop	= true;
			$process_image->image_ratio_fill	= true;
			$process_image->image_y				= CN_DEFAULT_ENTRY_Y;
			$process_image->image_x				= CN_DEFAULT_ENTRY_X;
			$process_image->Process(CN_IMAGE_PATH);
			if ($process_image->processed) {
				$success .= "<p><strong>Entry image created and saved.</strong></p> \n";
				//$image_names['entry'] = $process_image->file_dst_name;
				$image['entry'] = $process_image->file_dst_name;
			} else {
				$error .= "<p><strong>Entry image could not be created and/or saved to the destination folder.</strong></p> \n
						   <p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			}
			
			// Creates the thumbnail image and saves it to the wp_content/connection_images/ dir.
			// If needed this will create the upload dir and chmod it.
			$process_image->auto_create_dir		= true;
			$process_image->auto_chmod_dir		= true;
			$process_image->file_safe_name		= true;
			$process_image->file_auto_rename	= true;
			$process_image->file_name_body_add= '_thumbnail';
			$process_image->image_convert		= 'jpg';
			$process_image->jpeg_quality		= CN_DEFAULT_JPG_QUALITY;
			$process_image->image_resize		= true;
			$process_image->image_ratio_crop	= true;
			$process_image->image_ratio_fill	= true;
			$process_image->image_y				= CN_DEFAULT_THUMBNAIL_Y;
			$process_image->image_x				= CN_DEFAULT_THUMBNAIL_X;
			$process_image->Process(CN_IMAGE_PATH);
			if ($process_image->processed) {
				$success .= "<p><strong>Thumbnail image created and saved.</strong></p> \n";
				//$image_names['thumbnail'] = $process_image->file_dst_name;
				$image['thumbnail'] = $process_image->file_dst_name;
			} else {
				$error .= "<p><strong>Thumbnail image could not be created and/or saved to the destination folder.</strong></p> \n
						   <p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			}
			
			//$serial_image_options = serialize($image_options);
			$process_image->Clean();
		} else {
			$error = "<p><strong>Image could not be uploaded.</strong></p> \n
					  <p><strong>Error: </strong>" . $process_image->error . "</p> \n";
		}
	$results = array('success'=>$success, 'error'=>$error, 'image_names'=>$image);
	return $results;
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
	
	return $token;
}

/**
 * Builds a form select list
 * @return HTML form select
 * @param string $name
 * @param array $value_options Associative array where the key is the name visible in the HTML output and the value is the option attribute value
 * @param string $selected[optional]
 */
function _build_select($name, $value_options, $selected=null) {
	
	/**
	 * HTML output string
	 * @var string
	 */
	$select = "<select name='" . $name . "'> \n";
	foreach($value_options as $key=>$value) {
		$select .= "<option ";
		if ($value != null) {
			$select .= "value='" . $key . "'";
		} else {
			$select .= "value=''";
		}
		if ($selected == $key) {
			$select .= " SELECTED";
		}
		$select .= ">";
		$select .= $value;
		$select .= "</option> \n";
	}
	$select .= "</select> \n";
	
	return $select;
}
//Builds radio groups. Function requires (name as string, id as string, values and labels as an associative string array containing the key and values, OPTIONAL value to be selected by default)
function _build_radio($name, $id, $value_labels, $checked=null) {
	$radio = null;
	$count = 0;
	
	foreach ($value_labels as $label=>$value) {
		$idplus = $id . $count;
		
		if ($checked == $value) {
			$selected = 'CHECKED';
		}
		
		$radio .= '<label for="' . $idplus . '">';
		$radio .= '<input id="' . $idplus . '" type="radio" name="' . $name . '" value="' . $value . '" ' . $selected . ' />';
		$radio .= $label . '</label>';
		
		$selected = null;
		$idplus = null;
		$count = $count + 1;
	}
	
	return $radio;
}

/**
 * Builds the input/edit form.
 * @return HTML form
 * @param object $data[optional]
 */
function _connections_getaddressform($data=null) {
		global $defaultAddressTypes, $defaultEmailValues, $defaultIMValues, $defaultPhoneNumberValues, $defaultConnectionGroupValues;
		
		$entry = new entry($data);
		$addressObject = new addresses();
		$phoneNumberObject = new phoneNumber();
		$emailObject = new email();
		$imObject = new im();
		$websiteObject = new website();
		$options = unserialize($data->options);
		$date = new dateFunctions();
		$ticker = new counter();
		
		if (!$data->visibility) $defaultVisibility = 'unlisted'; else $defaultVisibility = $entry->getVisibility();
		if (!isset($options['entry']['type'])) $defaultEntryType = "individual"; else $defaultEntryType = $entry->getEntryType();
				
	    $out =
		'
		<div class="form-field connectionsform">	
				<span class="radio_group">' . _build_radio("entry_type","entry_type",array("Individual"=>"individual","Organization"=>"organization","Connection Group"=>"connection_group"),$defaultEntryType) . '</span>
		</div>
		
		<div id="connection_group" class="form-field connectionsform">
			
				<label for="connection_group_name">Connection Group Name:</label>
				<input type="text" name="connection_group_name" value="' . $entry->getGroupName() . '" />';
				$out .= '<div id="relations">';
						
					// --> Start template for Connection Group <-- \\
					$out .= '<textarea id="relation_row_base" style="display: none">';
						$out .= _connections_get_entry_select('connection_group[::FIELD::][entry_id]');
						$out .= _build_select('connection_group[::FIELD::][relation]', $defaultConnectionGroupValues);
					$out .= '</textarea>';
					// --> End template for Connection Group <-- \\
					
					if ($entry->getConnectionGroup())
					{
						$connections = $entry->getConnectionGroup();
						foreach ($connections as $key => $value)
						{
							$relation = new entry();
							$relation->set($key);
							
							$out .= '<div id="relation_row_' . $relation->getId() . '" class="relation_row">';
								$out .= _connections_get_entry_select('connection_group[' . $relation->getId() . '][entry_id]', $key);
								$out .= _build_select('connection_group[' . $relation->getId() . '][relation]', $defaultConnectionGroupValues, $value);
								$out .= '<a href="#" id="remove_button_' . $i . '" class="button button-warning" onClick="removeRelationRow(\'#relation_row_' . $relation->getId() . '\'); return false;">Remove</a>';
							$out .= '</div>';
							
							unset($relation);
						}
					}						
					
				$out .= '</div>';
				$out .= '<p class="add"><a id="add_button" class="button">Add Connection</a></p>';
			$out .= '
		</div>
		
		<div class="form-field connectionsform">
				<div class="namefield">
					<div class="input inputhalfwidth">
						<label for="first_name">First name:</label>
						<input type="text" name="first_name" value="' . $entry->getFirstName() . '" />
					</div>
					<div class="input inputhalfwidth">
						<label for="last_name">Last name:</label>
						<input type="text" name="last_name" value="' . $entry->getLastName() . '" />
					</div>
					<div class="clear"></div>
						
					<label for="title">Title:</label>
					<input type="text" name="title" value="' . $entry->getTitle() . '" />
				</div>
				
				<div class="organization">
					<label for="organization">Organization:</label>
					<input type="text" name="organization" value="' . $entry->getOrganization() . '" />
					
					<label for="department">Department:</label>
					<input type="text" name="department" value="' . $entry->getDepartment() . '" />
				</div>
		</div>
		
		<div class="form-field connectionsform">';
				
				if ($entry->getImageLinked()) {
					if ($entry->getImageDisplay()) $selected = "show"; else $selected = "hidden";
					
					$imgOptions = _build_radio("imgOptions", "imgOptionID_", array("Display"=>"show", "Not Displayed"=>"hidden", "Remove"=>"remove"), $selected);
					$out .= "<div style='text-align:center'> <img src='" . CN_IMAGE_BASE_URL . $entry->getImageNameProfile() . "' /> <br /> <span class='radio_group'>" . $imgOptions . "</span></div> <br />"; 
				}
				
				$out .= "<label for='original_image'>Select Image:</label>
				<input type='file' value='' name='original_image' size='25'/>
				
		</div>";
		
		if ($data->addresses != null) $addressValues = $entry->getAddresses(); else $addressValues = array(array('null'),array('null')); //The empty null is just to make the address section build twice untul jQuery form building can be implemented
		$ticker->reset();
		foreach ($addressValues as $addressRow)
		{
			$selectName = "address["  . $ticker->getcount() . "][type]";
			$out .= "<div class='form-field connectionsform'>";
				$out .= "<span class='selectbox alignright'>Type: " . _build_select($selectName,$defaultAddressTypes,$addressObject->getType($addressRow)) . "</span>";
				$out .= "<div class='clear'></div>";
				
				$out .= "<label for='address'>Address Line 1:</label>";
				$out .= "<input type='text' name='address[" . $ticker->getcount() . "][address_line1]' value='" . $addressObject->getLineOne($addressRow) . "' />";
	
				$out .= "<label for='address'>Address Line 2:</label>";
				$out .= "<input type='text' name='address[" . $ticker->getcount() . "][address_line2]' value='" . $addressObject->getLineTwo($addressRow) . "' />";
	
				$out .= "<div class='input' style='width:60%'>";
					$out .= "<label for='address'>City:</label>";
					$out .= "<input type='text' name='address[" . $ticker->getcount() . "][city]' value='" . $addressObject->getCity($addressRow) . "' />";
				$out .= "</div>";
				$out .= "<div class='input' style='width:15%'>";
					$out .= "<label for='address'>State:</label>";
					$out .= "<input type='text' name='address[" . $ticker->getcount() . "][state]' value='" . $addressObject->getState($addressRow) . "' />";
				$out .= "</div>";
				$out .= "<div class='input' style='width:25%'>";
					$out .= "<label for='address'>Zipcode:</label>";
					$out .= "<input type='text' name='address[" . $ticker->getcount() . "][zipcode]' value='" . $addressObject->getZipCode($addressRow) . "' />";
				$out .= "</div>";
				
				$out .= "<label for='address'>Country</label>";
				$out .= "<input type='text' name='address[" . $ticker->getcount() . "][country]' value='" . $addressObject->getCountry($addressRow) . "' />";
				
				$out .= "<input type='hidden' name='phone_numbers[" . $ticker->getcount() . "][visibility]' value='" . $addressObject->getVisibility($addressRow) . "' />";
			
			$out .= "<div class='clear'></div>";
			$out .= "</div>";
			$ticker->step();
		}
		$ticker->reset();
		
		if ($data->phone_numbers != null) $phoneNumberValues = $entry->getPhoneNumbers(); else $phoneNumberValues = $defaultPhoneNumberValues;
		$out .= "<div class='form-field connectionsform'>";
			$ticker->reset();
			foreach ($phoneNumberValues as $phoneNumberRow)
			{
				$out .= "<div class='" . $phoneNumberObject->getType($phoneNumberRow) . "'>";
					$out .= "<label for='phone_numbers'>" . $phoneNumberObject->getName($phoneNumberRow) . ":</label>";
					$out .= "<input type='text' name='phone_numbers[" . $ticker->getcount() . "][number]' value='" . $phoneNumberObject->getNumber($phoneNumberRow) . "' />";
					$out .= "<input type='hidden' name='phone_numbers[" . $ticker->getcount() . "][type]' value='" . $phoneNumberObject->getType($phoneNumberRow) . "' />";
					$out .= "<input type='hidden' name='phone_numbers[" . $ticker->getcount() . "][visibility]' value='" . $phoneNumberObject->getVisibility($phoneNumberRow) . "' />";
					$ticker->step();
				$out .= "</div>";
			}
			$ticker->reset();
		$out .= "</div>";


		if ($data->email != null) $emailValues = $entry->getEmailAddresses(); else $emailValues = $defaultEmailValues;
		$out .= "<div class='form-field connectionsform'>";
			$ticker->reset();
			foreach ($emailValues as $emailRow)
			{
				$out .= "<div class='" . $emailObject->getType($emailRow) . "'>";
					$out .= "<label for='email'>" . $emailObject->getName($emailRow) . ":</label>";
					$out .= "<input type='text' name='email[" . $ticker->getcount() . "][address]' value='" . $emailObject->getAddress($emailRow) . "' />";
					$out .= "<input type='hidden' name='email[" . $ticker->getcount() . "][name]' value='" . $emailObject->getName($emailRow) . "' />";
					$out .= "<input type='hidden' name='email[" . $ticker->getcount() . "][type]' value='" . $emailObject->getType($emailRow) . "' />";
					$out .= "<input type='hidden' name='email[" . $ticker->getcount() . "][visibility]' value='" . $emailObject->getVisibility($emailRow) . "' />";
				$ticker->step();
				$out .= "</div>";
			}
			$ticker->reset();
		$out .= "</div>";


		if ($data->im != null) $imValues = $entry->getIm(); else $imValues = $defaultIMValues;
		$out .= "<div class='form-field connectionsform im'>";
			$ticker->reset();
			foreach ($imValues as $imRow)
			{
				$out .= "<label for='im'>" . $imObject->getName($imRow) . ":</label>";
				$out .= "<input type='text' name='im[" . $ticker->getcount() . "][id]' value='" . $imObject->getId($imRow) . "' />";
				$out .= "<input type='hidden' name='im[" . $ticker->getcount() . "][name]' value='" . $imObject->getName($imRow) . "' />";
				$out .= "<input type='hidden' name='im[" . $ticker->getcount() . "][type]' value='" . $imObject->getType($imRow) . "' />";
				$out .= "<input type='hidden' name='im[" . $ticker->getcount() . "][visibility]' value='" . $imObject->getVisibility($imRow) . "' />";
				$ticker->step();
			}
			$ticker->reset();
		$out .= "</div>";
		
		if ($data->websites != null) $websiteValues = $entry->getWebsites(); else $websiteValues = array(array()); //Empty array as a place holder
		$out .= "<div class='form-field connectionsform'>";
		$ticker->reset();
		foreach ($websiteValues as $websiteRow)
		{
			$out .= "<label for='websites'>Website:</label>";
			$out .= "<input type='hidden' name='websites[" . $ticker->getcount() . "][type]' value'personal' />";
			$out .= "<input type='hidden' name='websites[" . $ticker->getcount() . "][name]' value'Personal' />";
			$out .= "<input type='text' name='websites[" . $ticker->getcount() . "][address]' value='" . $websiteObject->getAddress($websiteRow) . "' />";
			$out .= "<input type='hidden' name='websites[" . $ticker->getcount() . "][visibility]' value'public' />";
			$ticker->step();
		}
		$ticker->reset();
		$out .= "</div>";
		
		$out .= "<div class='form-field connectionsform celebrate'>
				<span class='selectbox'>Birthday: " . _build_select('birthday_month',$date->months,$date->getMonth($entry->getBirthday())) . "</span>
				<span class='selectbox'>" . _build_select('birthday_day',$date->days,$date->getDay($entry->getBirthday())) . "</span>
				<br />
				<span class='selectbox'>Anniversary: " . _build_select('anniversary_month',$date->months,$date->getMonth($entry->getAnniversary())) . "</span>
				<span class='selectbox'>" . _build_select('anniversary_day',$date->days,$date->getDay($entry->getAnniversary())) . "</span>
		</div>
		
		<div class='form-field connectionsform'>
				<label for='bio'>Biographical Info:</label>
				<textarea name='bio' rows='3'>" . $entry->getBio() . "</textarea>
		</div>
		
		<div class='form-field connectionsform'>
				<label for='notes'>Notes:</label>
				<textarea name='notes' rows='3'>" . $entry->getNotes() . "</textarea>
		</div>
		
		<div class='form-field connectionsform'>	
				<span class='radio_group'>" . _build_radio('visibility','vis',array('Public'=>'public','Private'=>'private','Unlisted'=>'unlisted'),$defaultVisibility) . "</span>
		</div>";
		return $out;
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
	//update_option('connections_options', $plugin_options->getOptions());
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
    global $wpdb;
	
	$atts = shortcode_atts( array(
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
			
	if (is_user_logged_in() or $atts['private_override'] != 'false') { 
		$visibilityfilter = " AND (visibility='private' OR visibility='public') ";
	} else {
		$visibilityfilter = " AND visibility='public' ";
	}
	
	if ($atts['id'] != null) $visibilityfilter .= " AND id='" . $atts['id'] . "' ";
	
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
		
		foreach ($results as $row) {
			$entry = new output($row);
			$vCard = new vCard($row);
			
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
?>