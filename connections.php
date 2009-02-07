<?php
/*
Plugin Name: Connections
Plugin URI: http://www.shazahm.net/?page_id=111
Description: An address book.
Version: 0.3.2
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

//GPL PHP upload class from http://www.verot.net/php_class_upload.htm
require_once(WP_PLUGIN_DIR . '/connections/php_class_upload/class.upload.php');

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

$current_version = "0.3.2";
session_start();

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

$defaultAddressTypes	=	array
							(
								'Select'=>null,
								'Home'=>'home',
								'Work'=>'work',
								'School'=>'school',
								'Other'=>'other'
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


//$plugin_options = new pluginOptions(get_option("connections_options"));
$plugin_options = new pluginOptions;


// CSS Styles for the plugin. This adds it to the admin page head.
/*add_action('admin_head', 'connections_adminhead');
function connections_adminhead() {
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/connections/css-admin.css" />' . "\n";
}*/

// This adds the menu to the Tools menu in WordPress and calls the function to load my CSS and JS.
add_action('admin_menu', 'connections_menus');
function connections_menus() {
	$connections_admin = add_management_page('connections', 'Connections', 4, 'connections/connections.php', 'connections_main');
	add_action( "admin_print_scripts-$connections_admin", 'connections_loadjs_admin_head' );
}

function connections_loadjs_admin_head() {
	//wp_enqueue_script('jquery');
	wp_enqueue_script('loadjs', get_bloginfo('wpurl') . '/wp-content/plugins/connections/js/ui.js');
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/connections/css-admin.css" />' . "\n";
}


function connections_main() {
		global $wpdb, $current_version, $current_user, $plugin_options;
		
		get_currentuserinfo();
		$plugin_options->setOptions(get_option("connections_options"), $current_user->ID);
		$plugin_options->setCurrentUserID($current_user->ID);
		
	    if ($_GET['action']=='editform') {
	        $sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
	        $row = $wpdb->get_row($sql);
			if ($_GET['copyid']) {
				$formID = "add_address";
				$formToken = "add_address";
				$formAction = "addnew";
				$inputName = "new";
			} else {
				$formID = "edit_address";
				$formToken = "edit_address";
				$formAction = "editcomplete";
				$inputName = "save";
			}
?>
			<div class="wrap">
				<div class="form-wrap" style="width:600px; margin: 0 auto;">
					<h2><a name="new"></a>Edit Entry</h2>
					
					<form action="admin.php?page=connections/connections.php&action=<?php echo $formAction ?>&id=<?php echo $row->id; ?>" method="post" enctype="multipart/form-data">
					<?php echo _connections_getaddressform($row); ?>
					<input type="hidden" name="formId" value="<?php echo $formID ?>" />
					<input type="hidden" name="token" value="<?php echo _formtoken($formToken); ?>" />
					<p class="submit">
						<input type="submit" name="<?php echo $inputName ?>" value="Save" />
						<a href="tools.php?page=connections/connections.php" class="button">Cancel</a> <!-- THERE HAS TO BE A BETTER WAY THAN REFERRING DIRECTLY TO THE TOOLS.PHP -->
					</p>
					</form>
				</div>
			</div>
<?php	
		} else {
	    
	        $table_name = $wpdb->prefix."connections";
			if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!= $table_name || $plugin_options->getVersion() != $current_version ) {
	            // Call the install function here rather than through the more usual
	            // activate_blah.php action hook so the user doesn't have to worry about
	            // deactivating then reactivating the plugin.  Should happen seamlessly.
	            _connections_install();
	            echo "<div id='message' class='updated fade'>
	                <p><strong>The Connections plug-in version " . $plugin_options->getVersion() . " has been installed or upgraded.</strong></p>
	            </div>";
	        } ?>

			<div class="wrap">
				<h2>Connections Administration</h2>
				
				<?php
				
				if ($_GET['action']=='addnew' AND $_POST['new'] AND $_SESSION['formTokens']['add_address']['token'] == $_POST['token']) {
					
					if ($_GET['id']) {
						$sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
						$row = $wpdb->get_row($sql);
						$options = unserialize($row->options);
					}
					
					$options['entry']['type'] = $_POST['entry_type'];
					
					//I think I should set these to null if no value was input???
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					//Did this because most often people don't want to give the year.
					$bdaydate = strtotime($_POST['birthday_day'] . '-' . $_POST['birthday_month'] . '-' . '1970 00:00:00');
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					$anndate = strtotime($_POST['anniversary_day'] . '-' . $_POST['anniversary_month'] . '-' . '1970 00:00:00');
					
					$serial_addresses = serialize($_POST['address']);
					$serial_phone_numbers = serialize($_POST['phone_numbers']);
					$serial_email = serialize($_POST['email']);
					$serial_im = serialize($_POST['im']);
					$serial_websites = serialize($_POST['websites']);
					
					if ($_POST['website'] == "http://") $_POST['website'] = "";
					
					if ($_FILES['original_image']['error'] != 4) {
						$image_proccess_results = _process_images($_FILES);
						$options['image']['name'] = $image_proccess_results['image_names'];
						$options['image']['linked'] = true;
						$options['image']['use'] = $image_proccess_results['image_names']['source'];
						$error = $image_proccess_results['error'];
						$success = $image_proccess_results['success'];
					}
					
					$serial_options = serialize($options);
					
					$sql = "INSERT INTO ".$wpdb->prefix."connections SET
			            first_name    = '".$wpdb->escape($_POST['first_name'])."',
			            last_name     = '".$wpdb->escape($_POST['last_name'])."',
						title    	  = '".$wpdb->escape($_POST['title'])."',
						organization  = '".$wpdb->escape($_POST['organization'])."',
						department    = '".$wpdb->escape($_POST['department'])."',
						visibility    = '".$wpdb->escape($_POST['visibility'])."',
						birthday      = '".$wpdb->escape($bdaydate)."',
						anniversary   = '".$wpdb->escape($anndate)."',
						addresses     = '".$wpdb->escape($serial_addresses)."',
						phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
						email	      = '".$wpdb->escape($serial_email)."',
						im  	      = '".$wpdb->escape($serial_im)."',
						websites      = '".$wpdb->escape($serial_websites)."',
						options       = '".$wpdb->escape($serial_options)."',
						bio           = '".$wpdb->escape($_POST['bio'])."',
			            notes         = '".$wpdb->escape($_POST['notes'])."'";
					
					if (!$error) {
						$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
						echo "<div id='message' class='updated fade'>";
							echo "<p><strong>Entry added.</strong></p> \n";
							if ($image_proccess_results['success']) echo $success;
						echo "</div>";
					} else {
						echo "<div id='notice' class='error'>";
							echo $error;
						echo "</div>";
					}
					
					unset($_SESSION['formTokens']);
				}
				
				if ($_GET['action']=='editcomplete' AND $_POST['save'] AND $_SESSION['formTokens']['edit_address']['token'] == $_POST['token']) {
					$sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
					$row = $wpdb->get_row($sql);
					
					$options = unserialize($row->options);
					$options['entry']['type'] = $_POST['entry_type'];
				
					//I think I should set these to null if no value was input???
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them. Did this because most often people don't want to give the year.
					$bdaydate = strtotime($_POST['birthday_day'] . '-' . $_POST['birthday_month'] . '-' . '1970 00:00:00');
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					$anndate = strtotime($_POST['anniversary_day'] . '-' . $_POST['anniversary_month'] . '-' . '1970 00:00:00');
					
					$serial_addresses = serialize($_POST['address']);
					$serial_phone_numbers = serialize($_POST['phone_numbers']);
					$serial_email = serialize($_POST['email']);
					$serial_im = serialize($_POST['im']);
					$serial_websites = serialize($_POST['websites']);					
					
					if ($_FILES['original_image']['error'] != 4) {
						$image_proccess_results = _process_images($_FILES);
						$options['image']['name'] = $image_proccess_results['image_names'];
						$options['image']['linked'] = true;
						$options['image']['display'] = true;
						$options['image']['use'] = $image_proccess_results['image_names']['source'];
						$error = $image_proccess_results['error'];
						$success = $image_proccess_results['success'];
					}
					
					if ($_POST['imgOptions'] == "remove") {
						$options['image']['linked'] = false;
					}
					
					if ($_POST['imgOptions'] == "hidden") {
						$options['image']['display'] = false;
					}
					
					if ($_POST['imgOptions'] == "show") {
						$options['image']['display'] = true;
					}
					
					$serial_options = serialize($options);
				
					$sql = "UPDATE ".$wpdb->prefix."connections SET
						first_name    = '".$wpdb->escape($_POST['first_name'])."',
						last_name     = '".$wpdb->escape($_POST['last_name'])."',
						title    	  = '".$wpdb->escape($_POST['title'])."',
						organization  = '".$wpdb->escape($_POST['organization'])."',
						department    = '".$wpdb->escape($_POST['department'])."',
						birthday      = '".$wpdb->escape($bdaydate)."',
						anniversary   = '".$wpdb->escape($anndate)."',
						addresses     = '".$wpdb->escape($serial_addresses)."',
						phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
						email	      = '".$wpdb->escape($serial_email)."',
						im  	      = '".$wpdb->escape($serial_im)."',
						websites      = '".$wpdb->escape($serial_websites)."',
						options       = '".$wpdb->escape($serial_options)."',
						bio           = '".$wpdb->escape($_POST['bio'])."',
						notes         = '".$wpdb->escape($_POST['notes'])."',
						visibility    = '".$wpdb->escape($_POST['visibility'])."'
						WHERE id ='".$wpdb->escape($_GET['id'])."'";
						
					//$wpdb->query($sql);
					if (!$error) {
						$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
						echo "<div id='message' class='updated fade'>";
							echo "<p><strong>The entry has been updated.</strong></p> \n";
							if ($image_proccess_results['success']) echo $success;
						echo "</div>";
					} else {
						echo "<div id='notice' class='error'>";
							echo $error;
						echo "</div>";
					}
					//echo '<div id="message" class="updated fade"><p><strong>The entry has been updated.</strong></p></div>';
					unset($_SESSION['formTokens']);
				}
				
				if ($_POST['doaction'] AND $_SESSION['formTokens']['do_action']['token'] == $_POST['token']) {
					if ($_POST['action'] != "") {
						echo "<div id='message' class='updated fade'>";
							$checked = $_POST['entry'];
							
							foreach ($checked as $id) {
								$sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($id)."'";
								$row = $wpdb->get_row($sql);
								
								$wpdb->query("UPDATE ".$wpdb->prefix."connections SET
									visibility		= '".$wpdb->escape($_POST['action'])."'
									WHERE id 		='".$wpdb->escape($id)."'");
							}
							
							echo "<p><strong>Entry(ies) visibility have been updated.</strong></p>";
						echo "</div>";
						unset($_SESSION['formTokens']);
					}
				}
				
				if ($_POST['dofilter']) {
					$plugin_options->setEntryType($_POST['entry_type']);
					$plugin_options->setVisibilityType($_POST['visibility_type']);
					update_option('connections_options', $plugin_options->getOptions());
				}
				
			    if ($_GET['action']=='delete' AND $_SESSION['formTokens']['delete_'.$_GET['id']]['token'] == $_GET['token']) {
			        $sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
			        $row = $wpdb->get_row($sql);			        
					$wpdb->query("DELETE FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'");
					echo '<div id="message" class="updated fade"><p><strong>The entry has been deleted.</strong></p></div>';	
					unset($_SESSION['formTokens']);
			    }?>
				
				
				<?php
					//print_r($plugin_options->getOptions());
					if ($plugin_options->getVisibilityType() != "") $filter = " AND visibility='" . $plugin_options->getVisibilityType() . "' ";
					$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = ''" . $filter . ") UNION (SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $filter . ") ORDER BY order_by, last_name, first_name";
					$results = $wpdb->get_results($sql);
				?>
				<div id="col-container">

					<div id="col-right">
						<div class="col-wrap">
							
							<form action="admin.php?page=connections/connections.php" method="post">
							
							<div class="tablenav">
								<div class="alignleft actions">
									<select name="action">
										<option value="" SELECTED>Bulk Actions</option>
										<option value="public">Set Public</option>
										<option value="private">Set Private</option>
										<option value="unlisted">Set Unlisted</option>
									</select>
									<input id="doaction" class="button-secondary action" type="submit" name="doaction" value="Apply" />
								</div>
								
								<div class="alignleft actions">
									<?php echo _build_select('entry_type', array('Show All Enties'=>'', 'Show Individuals'=>'individual', 'Show Organizations'=>'organization'), $plugin_options->getEntryType())?>
									<?php echo _build_select('visibility_type', array('Show All'=>'', 'Show Public'=>'public', 'Show Private'=>'private', 'Show Unlisted'=>'unlisted'), $plugin_options->getVisibilityType())?>
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
										//$options = unserialize($row->options);
										$entry = new entry($row);
										$addressObject = new addresses();
										$phoneNumberObject = new phoneNumber();
										$emailAddressObject = new email();
										$imObject = new im();
										$websiteObject = new website();
										
										$object = new output($row);
										
										if ($plugin_options->getEntryType() != "" )	{
											if ($entry->getEntryType() != $plugin_options->getEntryType()) continue;
										}
																				
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
												echo "<td colspan='2'>".$setAnchor."<div style='float:right'><a href='#wphead' title='Return to top.'><img src='" . WP_PLUGIN_URL . "/connections/images/uparrow.gif' /></a></div><a class='row-title' title='Edit " . $entry->getFullFirstLastName() . "' href='admin.php?page=connections/connections.php&action=editform&id=".$row->id."'> " . $entry->getFullLastFirstName(). "</a><br />";
												echo "<div class='row-actions'>
															<a class='detailsbutton' id='row-" . $entry->getId() . "'>Show Details</a> | 
															<a class='editbutton' href='admin.php?page=connections/connections.php&action=editform&id=" . $entry->getId() . "'>Edit</a> | 
															<a class='copybutton' href='admin.php?page=connections/connections.php&action=editform&id=" . $entry->getId() . "&copyid=true'>Copy</a> | 
															<a class='submitdelete' onclick='return confirm(\"You are about to delete this entry. Cancel to stop, OK to delete\");' href='admin.php?page=connections/connections.php&action=delete&id=" . $entry->getId() . "&token=" . _formtoken("delete_" . $entry->getId()) . "'>Delete</a>
													  </div>";
											echo "</td> \n";
											echo "<td ><strong>" . $entry->displayVisibiltyType() . "</strong></td> \n";												
											echo "<td >" . $entry->getFormattedTimeStamp() . "</td> \n";											
										echo "</tr> \n";
										
										echo "<tr class='child-row-" . $entry->getId() . " entrydetails' id='contact-" . $entry->getId() . "-detail' style='display:none;'>";
											echo "<td ></td> \n";
											echo "<td colspan='2'>";
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
											//echo $object->getAddressBlock();
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
														if ($imObject->getId($imRow) != "") echo "<strong>" . $imObject->getName($imRow) . ":</strong><br />" . $imObject->getId($imRow) . "<br />";
													}
													echo "<br />";
													
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
							<p style='font-size:smaller; text-align:center'>This is version <?php echo $plugin_options->getVersion(); ?> of Connections.</p>
						</div>
			        </div>
				
					<div id="col-left">
						<div class="col-wrap">
							<div class="form-wrap">
							<h3><a name="new"></a>Add Entry</h3>
							
								<form action="admin.php?page=connections/connections.php&action=addnew" method="post" enctype="multipart/form-data">
									<?php echo _connections_getaddressform(); ?>
									<input type="hidden" name="formId" value="add_address" />
									<input type="hidden" name="token" value="<?php echo _formtoken("add_address"); ?>" />

									<p class="submit">
										<input type="submit" name="new" value="Add Address" />
									</p>
								</form>
							</div>
						</div>
					</div>
					
				</div>
			</div>
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

	$_SESSION['formTokens'][$formId]['token'] = $token;
	$_SESSION['formTokens'][$formId]['token_timestamp'] = time();
	
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
			$select .= "value='" . $value . "'";
		} else {
			$select .= "value=''";
		}
		if ($selected == $value) {
			$select .= " SELECTED";
		}
		$select .= ">";
		$select .= $key;
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
		
		$radio .= '<input id="' . $idplus . '" type="radio" name="' . $name . '" value="' . $value . '" ' . $selected . ' />';
		$radio .= '<label for="' . $idplus . '">' . $label . '</label>';
		
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
		global $defaultAddressTypes, $defaultEmailValues, $defaultIMValues, $defaultPhoneNumberValues;
		
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
		"
		<div class='form-field connectionsform'>	
				<span class='radio_group'>" . _build_radio('entry_type','entry_type',array('Individual'=>'individual','Organization'=>'organization'),$defaultEntryType) . "</span>
		</div>

		<div class='form-field connectionsform'>
				<div class='namefield'>
					<div class='input inputhalfwidth'>
						<label for='first_name'>First name:</label>
						<input type='text' name='first_name' value='" . $entry->getFirstName() . "' />
					</div>
					<div class='input inputhalfwidth'>
						<label for='last_name'>Last name:</label>
						<input type='text' name='last_name' value='" . $entry->getLastName() . "' />
					</div>
					<div class='clear'></div>
						
					<label for='title'>Title:</label>
					<input type='text' name='title' value='" . $entry->getTitle() . "' />
				</div>

				<label for='organization'>Organization:</label>
				<input type='text' name='organization' value='" . $entry->getOrganization() . "' />
				
				<label for='department'>Department:</label>
				<input type='text' name='department' value='" . $entry->getDepartment() . "' />		
		</div>
		
		<div class='form-field connectionsform'>";
				
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
	global $wpdb, $current_version, $plugin_options;
	
    $table_name = $wpdb->prefix."connections";
    $sql = "CREATE TABLE " . $table_name . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        first_name tinytext NOT NULL,
        last_name tinytext NOT NULL,
		title tinytext NOT NULL,
		organization tinytext NOT NULL,
		department tinytext NOT NULL, 
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
	
	$plugin_options->setVersion($current_version);
	update_option('connections_options', $plugin_options->getOptions());
}

function connections_getselect($name) {
    global $wpdb;
    $out = "<select name='$name'>";
    $rows = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."connections ORDER BY last_name, first_name");
    foreach($rows as $row) {
        $out .= "<option value='$row->id'>$row->last_name $row->first_name</option>";
    }
    $out .= "</select>";
    return $out;
}

add_shortcode('connections_list', '_connections_list');
function _connections_list($atts, $content=null) {
    global $wpdb;
	
	$atts = shortcode_atts( array(
			'id' => null,
			'private_override' => 'false',
			'show_alphaindex' => 'false',
			'list_type' => 'all',
			'template_name' => 'card',
			'custom_template'=>'false',
			), $atts ) ;
			
	if (is_user_logged_in() or $atts['private_override'] != 'false') { 
		$visibilityfilter = " AND (visibility='private' OR visibility='public') ";
	} else {
		$visibilityfilter = " AND visibility='public' ";
	}
	
	if ($atts['id'] != null) $visibilityfilter .= " AND id='" . $atts['id'] . "' ";
	
	$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = ''" . $visibilityfilter . ") UNION (SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibilityfilter . ") ORDER BY order_by, last_name, first_name";
					
	$results = $wpdb->get_results($sql);
		
	if ($results != null) {
		
		if (!$atts['id']) $out = "<div id='connections-list-head'></div>";
		if ($atts['show_alphaindex'] == 'true') $out .= "<div class='cnalphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . _build_alphaindex() . "</div>";
		$out .=  "<div class='connections-list'>\n";
		
		foreach ($results as $row) {
			$entry = new output($row);
			
			if ($atts['list_type'] != 'all') {
				if ($atts['list_type'] != $entry->getEntryType()) {
					continue;
				}
			}
	
			//Checks the first letter of the last name to see if it is the next letter in the alpha array and sets the anchor.
			$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
			if ($currentLetter != $previousLetter && $atts['id'] == null) {
				$setAnchor = "<a name='$currentLetter'></a>";
				$previousLetter = $currentLetter;
			} else {
				$setAnchor = null;
			}
			
			if ($atts['show_alphaindex'] == 'true') $out .= $setAnchor;
			
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
				ob_start();
			    include($template);
			    $out .= ob_get_contents();
			    ob_end_clean();
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
	
	//Not 100% sure how this SQL statement works, found it here: http://searchoracle.techtarget.com/expert/KnowledgebaseAnswer/0,289625,sid41_cid458485,00.html
	/*The part about this problem that's the toughest to figure out is what to do about year boundaries.
	You get into difficulty if you start by reconstructing the person's birthday this year --

			    select DATE_FORMAT(CURRENT_DATE,'%Y')
			           + DATE_FORMAT(birthdate,'-%m-%d')

	What if today is December 24 and the birthday is January 5? That's certainly within 14 days, but suddenly the comparison is not so straight-forward -- 
	maybe you have to use the person's birthday next year, not this year, and do you subtract it from CURRENT_DATE or subtract CURRENT_DATE from the birthday?

	Here's a better approach. My age, considered as an integer, does not go up until my next birthday is reached. If I'm 39 today, and in two weeks I'm 40, then my 
	birthday must have occurred somewhere within those 14 days. Never mind whether we crossed a year boundary. All we need is a convenient formula for age in years.
	The MySQL docs give a splendid example --

	To determine how many years old each of your pets is, compute the difference in the year part of the current date and the birth date,
	then subtract one if the current date occurs earlier in the calendar year than the birth date. The following query shows, for each pet,
	the birth date, the current date, and the age in years.

			    select ( YEAR(CURRENT_DATE) - YEAR(birth) )
			           - ( RIGHT(CURRENT_DATE,5) < RIGHT(birth,5) )
			          as age

	Here, YEAR() pulls out the year part of a date and RIGHT() pulls off the rightmost five characters that represent the MM-DD (calendar year) part of the date.
	The part of the expression that compares the MM-DD values evaluates to 1 or 0, which adjusts the year difference down a year if CURRENT_DATE occurs earlier in the year than birth.

	Now all we have to do is compare the person's age in 14 days with the age today and Bob's your uncle --

			    select lastname, birthdate
			      from yourtable
			     where ( YEAR(DATE_ADD(CURRENT_DATE, INTERVAL 14 DAYS))
			             - YEAR(birthdate) )
			           - ( RIGHT(DATE_ADD(CURRENT_DATE, INTERVAL 14 DAYS),5)
			               < RIGHT(birthdate,5) )
			         > ( YEAR(CURRENT_DATE)
			             - YEAR(birthdate) )
			           - ( RIGHT(CURRENT_DATE,5)
			               < RIGHT(birthdate,5) ) 

	This problem is also discussed on pages 74-76 of Joe Celko's SQL for Smarties (ISBN 1-55860-323-9). */
	
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
				if (date("m", $row->$atts['list_type']) < date("m")) {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y") . " + 1 year");
				} else {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y"));
				}
				if (!$atts['show_lastname']) {
					$upcoming_list["<span class='name' id='cn_name" . $row->id . "'>" . $row->first_name . " " . $row->last_name{0} . ".</span>"] .= $current_date;
				} else {
					$upcoming_list["<span class='name' id='cn_name" . $row->id . "'>" . $row->first_name . " " . $row->last_name . "</span>"] .= $current_date;
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