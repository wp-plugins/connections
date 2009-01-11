<?php
/*
Plugin Name: Connections
Plugin URI: http://shazahm.net/
Description: An address book.
Version: 0.2.8
Author: Steve. A. Zahm
Author URI: http://www.shazahm.net/

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

$connections_version = '0.2.8';
session_start();

// Define a few constants until I can get to creating the options page.
define('CN_DEFAULT_JPG_QUALITY', 80);
define('CN_DEFAULT_PROFILE_X', 300);
define('CN_DEFAULT_PROFILE_Y', 225);
define('CN_DEFAULT_ENTRY_X', 225);
define('CN_DEFAULT_ENTRY_Y', 150);
define('CN_DEFAULT_THUMBNAIL_X', 80);
define('CN_DEFAULT_THUMBNAIL_Y', 54);
define('CN_IMAGE_PATH', WP_CONTENT_DIR . "/connection_images/");
define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . "/connection_images/");


// CSS Styles for the plugin. This adds it to the admin page head.
add_action('admin_head', 'connections_adminhead');
function connections_adminhead() {
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/connections/css-admin.css" />' . "\n";
}

// This adds the menu to the Tools menu in WordPress.
add_action('admin_menu', 'connections_menus');
function connections_menus() {
	add_management_page('connections', 'Connections', 4, 'connections/connections.php', 'connections_main');
}

function connections_main() {
	    global $wpdb, $connections_version;
		
	    if ($_GET['action']=='editform') {
	        $sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
	        $row = $wpdb->get_row($sql);
?>
			<div class="wrap">
				<div class="form-wrap" style="width:600px; margin: 0 auto;">
					<h2><a name="new"></a>Edit Address</h2>
					
					<form action="admin.php?page=connections/connections.php&action=editcomplete&id=<?php echo $row->id; ?>" method="post" enctype="multipart/form-data">
					<?php echo _connections_getaddressform($row); ?>
					<input type="hidden" name="formId" value="edit_address" />
					<input type="hidden" name="token" value="<?php echo _formtoken("edit_address"); ?>" />
					<p class="submit">
						<input type="submit" name="save" value="Save" />
						<a href="tools.php?page=connections/connections.php" class="button">Cancel</a> <!-- THERE HAS TO BE A BETTER WAY THAN REFERRING DIRECTLY TO THE TOOLS.PHP -->
					</p>
					</form>
				</div>
			</div>
<?php	
		} else {
	    
	        $table_name = $wpdb->prefix."connections";
	        If ($wpdb->get_var("SHOW TABLES LIKE '$table_name'")!=$table_name
	            || get_option("connections_version")!=$connections_version ) {
	            // Call the install function here rather than through the more usual
	            // activate_blah.php action hook so the user doesn't have to worry about
	            // deactivating then reactivating the plugin.  Should happen seamlessly.
	            _connections_install();
	            echo '<div id="message" class="updated fade">
	                <p><strong>The Connections plugin (version
	                '.get_option("connections_version").') has been installed or upgraded.</strong></p>
	            </div>';
	        } ?>

			<div class="wrap">
				<h2>Connections Administration</h2>
				
				<?php
				
				if ($_GET['action']=='addnew' AND $_POST['new'] AND $_SESSION['formTokens']['add_address']['token'] == $_POST['token']) {
					
					//I think I should set these to null if no value was input???
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					//Did this because most often people don't want to give the year.
					$bdaydate = strtotime($_POST['birthday_day'] . '-' . $_POST['birthday_month'] . '-' . '1970 00:00:00');
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					$anndate = strtotime($_POST['anniversary_day'] . '-' . $_POST['anniversary_month'] . '-' . '1970 00:00:00');
					
					$addresses[] = array(type=>$_POST['address_type'],
										 address_line1=>$_POST['address_line1'],
										 address_line2=>$_POST['address_line2'],
										 city=>$_POST['city'],
										 state=>$_POST['state'],
										 zipcode=>$_POST['zipcode'],
										 visibility=>'unlisted');
					
					$addresses[] = array(type=>$_POST['address2_type'],
										 address_line1=>$_POST['address2_line1'],
										 address_line2=>$_POST['address2_line2'],
										 city=>$_POST['city2'],
										 state=>$_POST['state2'],
										 zipcode=>$_POST['zipcode2'],
										 visibility=>'unlisted');
					
					$phone_numbers[] = array(type=>'home', homephone=>$_POST['homephone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'homefax', homephone=>$_POST['homefax'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'cell', homephone=>$_POST['cellphone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'work', homephone=>$_POST['workphone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'workfax', homephone=>$_POST['workfax'], visibility=>'unlisted');
					
					$email[] = array(type=>personal, address=>$_POST['personalemail'], visibility=>'unlisted');
					$email[] = array(type=>work, address=>$_POST['workemail'], visibility=>'unlisted');
					
					$websites[] = array(type=>'personal', address=>$_POST['website'], visibility=>'unlisted');
					
					$serial_addresses = serialize($addresses);
					$serial_phone_numbers = serialize($phone_numbers);
					$serial_email = serialize($email);
					$serial_websites = serialize($websites);
					
					if ($_POST['website'] == "http://") $_POST['website'] = "";
					
					if ($_FILES['original_image']['error'] != 4) {
						$image_proccess_results = _process_images($_FILES);
						$options['image']['name'] = $image_proccess_results['image_names'];
						$options['image']['linked'] = true;
						$options['image']['use'] = $image_proccess_results['image_names']['source'];
						$serial_options = serialize($options);
						$error = $image_proccess_results['error'];
						$success = $image_proccess_results['success'];
					}
					
					$sql = "INSERT INTO ".$wpdb->prefix."connections SET
			            first_name    = '".$wpdb->escape($_POST['first_name'])."',
			            last_name     = '".$wpdb->escape($_POST['last_name'])."',
						title    	  = '".$wpdb->escape($_POST['title'])."',
						organization  = '".$wpdb->escape($_POST['organization'])."',
			            personalemail = '".$wpdb->escape($_POST['personalemail'])."',
			            workemail     = '".$wpdb->escape($_POST['workemail'])."',
			            website       = '".$wpdb->escape($_POST['website'])."',
						address_type  = '".$wpdb->escape($_POST['address_type'])."',
			            address_line1 = '".$wpdb->escape($_POST['address_line1'])."',
			            address_line2 = '".$wpdb->escape($_POST['address_line2'])."',
			            city          = '".$wpdb->escape($_POST['city'])."',
			            state         = '".$wpdb->escape($_POST['state'])."',
			            zipcode       = '".$wpdb->escape($_POST['zipcode'])."',
						address2_type = '".$wpdb->escape($_POST['address2_type'])."',
						address2_line1= '".$wpdb->escape($_POST['address2_line1'])."',
			            address2_line2= '".$wpdb->escape($_POST['address2_line2'])."',
			            city2         = '".$wpdb->escape($_POST['city2'])."',
			            state2        = '".$wpdb->escape($_POST['state2'])."',
			            zipcode2      = '".$wpdb->escape($_POST['zipcode2'])."',
			            homephone     = '".$wpdb->escape($_POST['homephone'])."',
						homefax       = '".$wpdb->escape($_POST['homefax'])."',
			            cellphone     = '".$wpdb->escape($_POST['cellphone'])."',
						workphone     = '".$wpdb->escape($_POST['workphone'])."',
						workfax       = '".$wpdb->escape($_POST['workfax'])."',
						visibility    = '".$wpdb->escape($_POST['visibility'])."',
						birthday      = '".$wpdb->escape($bdaydate)."',
						anniversary   = '".$wpdb->escape($anndate)."',
						addresses     = '".$wpdb->escape($serial_addresses)."',
						phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
						email	      = '".$wpdb->escape($serial_email)."',
						websites      = '".$wpdb->escape($serial_websites)."',
						options   = '".$wpdb->escape($serial_options)."',
			            notes         = '".$wpdb->escape($_POST['notes'])."'";
					
					if (!$error) {
						$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
						echo "<div id='message' class='updated fade'>";
							echo "<p><strong>Address added.</strong></p> \n";
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
				
					//I think I should set these to null if no value was input???
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them. Did this because most often people don't want to give the year.
					$bdaydate = strtotime($_POST['birthday_day'] . '-' . $_POST['birthday_month'] . '-' . '1970 00:00:00');
					//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
					$anndate = strtotime($_POST['anniversary_day'] . '-' . $_POST['anniversary_month'] . '-' . '1970 00:00:00');
					
					$addresses[] = array(type=>$_POST['address_type'],
										 address_line1=>$_POST['address_line1'],
										 address_line2=>$_POST['address_line2'],
										 city=>$_POST['city'],
										 state=>$_POST['state'],
										 zipcode=>$_POST['zipcode'],
										 visibility=>'unlisted');
					
					$addresses[] = array(type=>$_POST['address2_type'],
										 address_line1=>$_POST['address2_line1'],
										 address_line2=>$_POST['address2_line2'],
										 city=>$_POST['city2'],
										 state=>$_POST['state2'],
										 zipcode=>$_POST['zipcode2'],
										 visibility=>'unlisted');
					
					$phone_numbers[] = array(type=>'home', homephone=>$_POST['homephone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'homefax', homephone=>$_POST['homefax'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'cell', homephone=>$_POST['cellphone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'work', homephone=>$_POST['workphone'], visibility=>'unlisted');
					$phone_numbers[] = array(type=>'workfax', homephone=>$_POST['workfax'], visibility=>'unlisted');
					
					$email[] = array(type=>personal, address=>$_POST['personalemail'], visibility=>'unlisted');
					$email[] = array(type=>work, address=>$_POST['workemail'], visibility=>'unlisted');
					
					$websites[] = array(type=>'personal', address=>$_POST['website'], visibility=>'unlisted');
					
					$serial_addresses = serialize($addresses);
					$serial_phone_numbers = serialize($phone_numbers);
					$serial_email = serialize($email);
					$serial_websites = serialize($websites);
					
					$options = unserialize($row->options);
					if ($_FILES['original_image']['error'] != 4) {
						$image_proccess_results = _process_images($_FILES);
						$options['image']['name'] = $image_proccess_results['image_names'];
						$options['image']['linked'] = true;
						$options['image']['use'] = $image_proccess_results['image_names']['source'];
						$error = $image_proccess_results['error'];
						$success = $image_proccess_results['success'];
					}
					$serial_options = serialize($options);
				
					$sql = "UPDATE ".$wpdb->prefix."connections SET
						first_name    = '".$wpdb->escape($_POST['first_name'])."',
						last_name     = '".$wpdb->escape($_POST['last_name'])."',
						title    	  = '".$wpdb->escape($_POST['title'])."',
						organization  = '".$wpdb->escape($_POST['organization'])."',
						personalemail = '".$wpdb->escape($_POST['personalemail'])."',
						workemail     = '".$wpdb->escape($_POST['workemail'])."',
						homephone     = '".$wpdb->escape($_POST['homephone'])."',
						homefax       = '".$wpdb->escape($_POST['homefax'])."',
						cellphone     = '".$wpdb->escape($_POST['cellphone'])."',
						workphone     = '".$wpdb->escape($_POST['workphone'])."',
						workfax       = '".$wpdb->escape($_POST['workfax'])."',
						address_type  = '".$wpdb->escape($_POST['address_type'])."',
						address_line1 = '".$wpdb->escape($_POST['address_line1'])."',
						address_line2 = '".$wpdb->escape($_POST['address_line2'])."',
						city          = '".$wpdb->escape($_POST['city'])."',
						state         = '".$wpdb->escape($_POST['state'])."',
						zipcode       = '".$wpdb->escape($_POST['zipcode'])."',
						address2_type = '".$wpdb->escape($_POST['address2_type'])."',
						address2_line1= '".$wpdb->escape($_POST['address2_line1'])."',
						address2_line2= '".$wpdb->escape($_POST['address2_line2'])."',
						city2         = '".$wpdb->escape($_POST['city2'])."',
						state2        = '".$wpdb->escape($_POST['state2'])."',
						zipcode2      = '".$wpdb->escape($_POST['zipcode2'])."',
						birthday      = '".$wpdb->escape($bdaydate)."',
						anniversary   = '".$wpdb->escape($anndate)."',
						addresses     = '".$wpdb->escape($serial_addresses)."',
						phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
						email	      = '".$wpdb->escape($serial_email)."',
						websites      = '".$wpdb->escape($serial_websites)."',
						options       = '".$wpdb->escape($serial_options)."',
						notes         = '".$wpdb->escape($_POST['notes'])."',
						website       = '".$wpdb->escape($_POST['website'])."',
						visibility    = '".$wpdb->escape($_POST['visibility'])."'
						WHERE id ='".$wpdb->escape($_GET['id'])."'";
						
					//$wpdb->query($sql);
					if (!$error) {
						$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
						echo "<div id='message' class='updated fade'>";
							echo "<p><strong>The address has been updated.</strong></p> \n";
							if ($image_proccess_results['success']) echo $success;
						echo "</div>";
					} else {
						echo "<div id='notice' class='error'>";
							echo $error;
						echo "</div>";
					}
					//echo '<div id="message" class="updated fade"><p><strong>The address has been updated.</strong></p></div>';
					unset($_SESSION['formTokens']);
				}
				
				if ($_POST['doaction'] AND $_SESSION['formTokens']['do_action']['token'] == $_POST['token']) {
					if ($_POST['action'] != "") {
						echo "<div id='message' class='updated fade'>";
							$checked = $_POST['address'];
							
							foreach ($checked as $id) {
								$sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($id)."'";
								$row = $wpdb->get_row($sql);
								
								$wpdb->query("UPDATE ".$wpdb->prefix."connections SET
									visibility		= '".$wpdb->escape($_POST['action'])."'
									WHERE id 		='".$wpdb->escape($id)."'");
							}
							
							echo "<p><strong>Address(es) visibility have been updated.</strong></p>";
						echo "</div>";
						unset($_SESSION['formTokens']);
					}
										
					$filterby = $_POST['filter'];
					
					 if ($filterby != "all") {
						$visibilityfilter = " WHERE visibility='" . $_POST['filter'] . "' ";
						$visibilityselect = $_POST['filter'];
					} else {
						$visibilityfilter = "";
						$visibilityselect = "all";
					}
				}
				
				if ($_POST['dofilter']) {
					$filterby = $_POST['filter'];
					
					 if ($filterby != "all") {
						$visibilityfilter = " WHERE visibility='" . $_POST['filter'] . "' ";
						$visibilityselect = $_POST['filter'];
					} else {
						$visibilityfilter = "";
						$visibilityselect = "all";
					}
				}
				
			    if ($_GET['action']=='delete' AND $_SESSION['formTokens']['delete_'.$_GET['id']]['token'] == $_GET['token']) {
			        $sql = "SELECT * FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'";
			        $row = $wpdb->get_row($sql);			        
					$wpdb->query("DELETE FROM ".$wpdb->prefix."connections WHERE id='".$wpdb->escape($_GET['id'])."'");
					echo '<div id="message" class="updated fade"><p><strong>The address has been deleted.</strong></p></div>';	
					unset($_SESSION['formTokens']);
			    }?>
				
				
				<div id="col-container">

					<div id="col-right">
						<div class="col-wrap">
					        
					        <script type="text/javascript">
								function click_contact(row, id) {
									
									if (
										document.getElementById('contact-'+id+'-detail').style.display != 'none' ) {
										document.getElementById('contact-'+id+'-detail').style.display = 'none';
										document.getElementById('contact-'+id+'-detail-notes').style.display = 'none';
										document.getElementById('detailbutton'+id).innerHTML='Show Details';
									}
									else {
										document.getElementById('contact-'+id+'-detail').style.display = '';
										document.getElementById('contact-'+id+'-detail-notes').style.display = '';
										document.getElementById('detailbutton'+id).innerHTML='Hide Details';
									}
									
								}
					        </script>
							
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
									<?php echo _build_select('filter', array('Show All'=>'all', 'Show Public'=>'public', 'Show Private'=>'private', 'Show Unlisted'=>'unlisted'), $visibilityselect)?>
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
									<?php $sql = "SELECT * FROM ".$wpdb->prefix."connections " . $visibilityfilter . "ORDER BY last_name, first_name";
									$results = $wpdb->get_results($sql);
									$alphaindex = array();
									$i = 0;
									//unset($_SESSION['formTokens']);
									//print_r($_SESSION);
									//Builds an alpha array of the first letter of the last names.
									foreach ($results as $row) {
										$letter = strtoupper(substr($row->last_name, 0, 1));
										if ($letter != $oldletter) {
											$alphaindex[] = strtoupper(substr($row->last_name, 0, 1));
											$oldletter = strtoupper(substr($row->last_name, 0, 1));
										}
									}
																	
									foreach ($results as $row) {
										$options = unserialize($row->options);
										
										if (!$altrow == "alternate") {
											$altrow = "alternate";
										} else {
											$altrow = "";
										}
										
										//Checks the first letter of the last name to see if it is the next letter in the alpha array and sets the anchor.
										if ($alphaindex[$i] == strtoupper(substr($row->last_name, 0, 1))) {
											$alphaanchor = "<a name='" . $alphaindex[$i] . "'></a>";
											$i++;
										} else {
											$alphaanchor = "";
										}
										
										echo "<tr class='".$altrow."'>";
											echo "<th class='check-column ".$altrow."' scope='row'><input type='checkbox' value='".$row->id."' name='address[]'/></th> \n";
											echo "<td class='".$altrow."' colspan='2'>".$alphaanchor."<a class='row-title' title='Edit ".$row->last_name.", ".$row->first_name."' href='admin.php?page=connections/connections.php&action=edit&id=".$row->id."'> ".$row->last_name.", ".$row->first_name."</a><br />";
												echo "<div class='row-actions'><span class='detailsbutton' id='detailbutton".$row->id."' onClick='click_contact(this, ".$row->id.")'>Show Details</span> | <a class='editbutton' href='admin.php?page=connections/connections.php&action=editform&id=".$row->id."'>Edit</a> | <a class='submitdelete' onclick='return confirm(\"You are about to delete this address. Cancel to stop, OK to delete\");' href='admin.php?page=connections/connections.php&action=delete&id=".$row->id."&token="._formtoken("delete_".$row->id)."'>Delete</a> | <a href='#wphead' title='Return to top.'>Up</a></div>";
											echo "</td> \n";
											echo "<td class='".$altrow."'><strong>".ucwords($row->visibility)."</strong></td> \n";												
											echo "<td class='".$altrow."'>" . date("m/d/Y",strtotime($row->ts)) . "</td> \n";											
										echo "</tr> \n";
										
										echo "<tr class='".$altrow." addressdetails' id='contact-".$row->id."-detail' style='display:none;'>";
											echo "<td class='".$altrow."'></td> \n";
											echo "<td class='".$altrow."' colspan='2'>";
												if ($row->address_type) echo "<strong>" . ucfirst($row->address_type) . " Address</strong><br />";
												if ($row->address_line1) echo $row->address_line1."<br />";
												if ($row->address_line2) echo $row->address_line2."<br />";
												if ($row->city) echo $row->city.", "; if ($row->state) echo $row->state."  "; if ($row->zipcode) echo $row->zipcode."<br /><br />";
												
												if ($row->address2_type) echo "<strong>" . ucfirst($row->address2_type) . " Address</strong><br />";
												if ($row->address2_line1) echo $row->address2_line1."<br />";
												if ($row->address2_line2) echo $row->address2_line2."<br />";
												if ($row->city2) echo $row->city2.", "; if ($row->state2) echo $row->state2."  "; if ($row->zipcode2) echo $row->zipcode2."<br /><br />";
											echo "</td> \n";
											
											echo "<td class='".$altrow."'>";
												if ($row->personalemail) echo "<strong>Personal Email:</strong><br /><a href='mailto:".$row->personalemail."'>".$row->personalemail."</a><br /><br />";
												if ($row->workemail) echo "<strong>Work Email:</strong><br /><a href='mailto:".$row->workemail."'>".$row->workemail."</a><br /><br />";
												if ($row->website) echo "<strong>Website:</strong><br /><a target='_blank' href='" . $row->website . "'>" . $row->website . "</a><br /><br />";
												
												if ($row->homephone) echo "<strong>Home Phone:</strong> ".$row->homephone."<br />";
												if ($row->homefax) echo "<strong>Home Fax:</strong> ".$row->homefax."<br />";
												if ($row->cellphone) echo "<strong>Cell Phone:</strong> ".$row->cellphone."<br />";
												if ($row->workphone) echo "<strong>Work Phone:</strong> ".$row->workphone."<br />";
												if ($row->workfax) echo "<strong>Work Fax:</strong> ".$row->workfax."<br />";
											echo "</td> \n";
																					
											echo "<td class='".$altrow."'>";
												if ($row->birthday) echo "<strong>Birthday:</strong><br />".date("F jS", $row->birthday)."<br /><br />";
												if ($row->anniversary) echo "<strong>Anniversary:</strong><br />".date("F jS", $row->anniversary);
											echo "</td> \n";
										echo "</tr> \n";
										
										echo "<tr class='".$altrow." addressnotes' id='contact-".$row->id."-detail-notes' style='display:none;'>";
											echo "<td class='".$altrow."'>&nbsp;</td> \n";
											echo "<td class='".$altrow."' colspan='3'>";
												if ($row->notes) echo "<strong>Notes:</strong> " . $row->notes; else echo "&nbsp;";
											echo "</td> \n";
											echo "<td class='".$altrow."'><strong>Entry ID:</strong> " . $row->id;
												if (!$options['image']['linked']) echo "<br /><strong>Image Linked:</strong> No"; else echo "<br /><strong>Image Linked:</strong> Yes";
											echo "</td> \n";
										echo "</tr> \n";
																				
									} ?>
								</tbody>
					        </table>
							</form>
							<p style='font-size:smaller; text-align:center'>This is version <?php echo get_option("connections_version"); ?> of the Connections.</p>
						</div>
			        </div>
				
					<div id="col-left">
						<div class="col-wrap">
							<div class="form-wrap">
							<h3><a name="new"></a>Add Address</h3>
							
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

//Builds select drop down. Function requires (name as string, options as an associative string array containing the key and values, OPTIONAL value to be selected by default)
function _build_select($name, $value_options, $selected=null) {
	
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

//This builds the address input/edit form.
function _connections_getaddressform($data=null) {
		if ($data != null) {
			$options = unserialize($data->options);
			$post_options = $data->options;
			if ($options['image']['linked']) {
				$img_html_block = '<img src="' . CN_IMAGE_BASE_URL . $options['image']['name']['thumbnail'] . '" /> <div class="clear"></div>'; 
			} else {
				$img_html_block = "";
			}
		}
		
		if (!$data) $website = 'http://'; else $website = $data->website;
		if (!$data->birthday) $birthday_month = null; else $birthday_month = date("m", $data->birthday);
		if (!$data->birthday) $birthday_day = null; else $birthday_day = date("d", $data->birthday);
		if (!$data->anniversary) $anniversary_month = null; else $anniversary_month = date("m", $data->anniversary);
		if (!$data->anniversary) $anniversary_day = null; else $anniversary_day = date("d", $data->anniversary);
		if (!$data->visibility) $default_visibility = 'unlisted'; else $default_visibility = $data->visibility;
		
		$months = array('Month'=>null,
						'January'=>'01',
						'February'=>'02',
						'March'=>'03',
						'April'=>'04',
						'May'=>'05',
						'June'=>'06',
						'July'=>'07',
						'August'=>'08',
						'September'=>'09',
						'October'=>'10',
						'November'=>'11',
						'December'=>'12');
		$days = array(	'Day'=>null,
						'1st'=>'01',
						'2nd'=>'02',
						'3rd'=>'03',
						'4th'=>'04',
						'5th'=>'05',
						'6th'=>'06',
						'7th'=>'07',
						'8th'=>'08',
						'9th'=>'09',
						'10th'=>'10',
						'11th'=>'11',
						'12th'=>'12',
						'13th'=>'13',
						'14th'=>'14',
						'15th'=>'15',
						'16th'=>'16',
						'17th'=>'17',
						'18th'=>'18',
						'19th'=>'19',
						'20th'=>'20',
						'21st'=>'21',
						'22nd'=>'22',
						'23rd'=>'23',
						'24th'=>'24',
						'25th'=>'25',
						'26th'=>'26',
						'27th'=>'27',
						'28th'=>'28',
						'29th'=>'29',
						'30th'=>'30',
						'31st'=>'31',);
		
		$address_types = array('Select'=>null,'Home'=>'home','Work'=>'work','School'=>'school','Other'=>'other');
		
		$address_select = _build_select('address_type',$address_types,$data->address_type);
		$address2_select = _build_select('address2_type',$address_types,$data->address2_type);
		
		$bday_month = _build_select('birthday_month',$months,$birthday_month);
		$bday_day = _build_select('birthday_day',$days,$birthday_day);
		
		$ann_month = _build_select('anniversary_month',$months,$anniversary_month);
		$ann_day = _build_select('anniversary_day',$days,$anniversary_day);
		
		$visibility = _build_radio('visibility','vis',array('Public'=>'public','Private'=>'private','Unlisted'=>'unlisted'),$default_visibility);
		
	    $out = // This mess needs re-written to following coding style used for the front end output!!!
		'
		<div class="form-field connectionsform">
			<div class="input inputhalfwidth">
				<label for="first_name">First name:</label>
				<input type="text" name="first_name" value="'.$data->first_name.'" />
			</div>
			<div class="input inputhalfwidth">
				<label for="last_name">Last name:</label>
				<input type="text" name="last_name" value="'.$data->last_name.'" />
			</div>
			<div class="clear"></div>
		</div>
		
		<div class="form-field connectionsform">				
				<label for="title">Title:</label>
				<input type="text" name="title" value="'.$data->title.'" />

				<label for="organization">Organization:</label>
				<input type="text" name="organization" value="'.$data->organization.'" />		
		</div>
		
		<div class="form-field connectionsform">
				' . $img_html_block . '
				<label for="original_image">Select Image:</label>
				<input type="file" value="" name="original_image" size="25"/>
		</div>
		
		<div class="form-field connectionsform">
			<span class="selectbox alignright">Type: '.$address_select.'</span>
			<div class="clear"></div>
			
			<label for="address_line1">Address Line 1:</label>
			<input type="text" name="address_line1" value="'.$data->address_line1.'" />

			<label for="address_line2">Address Line 2:</label>
			<input type="text" name="address_line2" value="'.$data->address_line2.'" />


			<div class="input" style="width:60%">
				<label for="city">City:</label>
				<input type="text" name="city" value="'.$data->city.'" />
			</div>
			<div class="input" style="width:15%">
				<label for="state">State:</label>
				<input type="text" name="state" value="'.$data->state.'" />
			</div>
			<div class="input" style="width:25%">
				<label for="zipcode">Zipcode:</label>
				<input type="text" name="zipcode" value="'.$data->zipcode.'" />
			</div>

			<div class="clear"></div>
		</div>
		
		<div class="form-field connectionsform">

			<span class="selectbox alignright">Type: '.$address2_select.'</span>
			<div class="clear"></div>
			
			<label for="address2_line1">Address Line 1:</label>
			<input type="text" name="address2_line1" value="'.$data->address2_line1.'" />

			<label for="address2_line2">Address Line 2:</label>
			<input type="text" name="address2_line2" value="'.$data->address2_line2.'" />

			<div class="input" style="width:60%">
				<label for="city2">City:</label>
				<input type="text" name="city2" value="'.$data->city2.'" />
			</div>
			<div class="input" style="width:15%">
				<label for="state2">State:</label>
				<input type="text" name="state2" value="'.$data->state2.'" />
			</div>
			<div class="input" style="width:25%">
				<label for="zipcode2">Zipcode:</label>
				<input type="text" name="zipcode2" value="'.$data->zipcode2.'" />
			</div>

			<div class="clear"></div>
		</div>
		
		<div class="form-field connectionsform">				
				<label for="homephone">Home Phone:</label>
				<input type="text" name="homephone" value="'.$data->homephone.'" />

				<label for="homefax">Home Fax:</label>
				<input type="text" name="homefax" value="'.$data->homefax.'" />

				<label for="cellphone">Cell Phone:</label>
				<input type="text" name="cellphone" value="'.$data->cellphone.'" />

				<label for="workphone">Work Phone:</label>
				<input type="text" name="workphone" value="'.$data->workphone.'" />

				<label for="workfax">Work Fax:</label>
				<input type="text" name="workfax" value="'.$data->workfax.'" />		</div>
		<div class="form-field connectionsform">				<label for="personalemail">Personal Email:</label>
				<input type="text" name="personalemail" value="'.$data->personalemail.'" />

				<label for="workemail">Work Email:</label>
				<input type="text" name="workemail" value="'.$data->workemail.'" />
		</div>

		<div class="form-field connectionsform">
				<span class="selectbox">Birthday: '.$bday_month.'</span>
				<span class="selectbox">'.$bday_day.' </span>
				<br />
				<span class="selectbox">Anniversary: '.$ann_month.'</span>
				<span class="selectbox">'.$ann_day.'</span>
		</div>
		
		<div class="form-field connectionsform">
				<label for="website">Website:</label>
				<input type="text" name="website" value="'.$website.'" />
		</div>
		<div class="form-field connectionsform">
				<label for="notes">Notes:</label>
				<textarea name="notes" rows="3">'.$data->notes.'</textarea>
		</div>
		
		<div class="form-field connectionsform">	
				<span class="radio_group">'.$visibility.'</span>
		</div>';
		return $out;
	}

// This installs and/or upgrades the plugin.
function _connections_install() {
    global $wpdb, $connections_version;
    $table_name = $wpdb->prefix."connections";
    $sql = "CREATE TABLE " . $table_name . " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ts TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        first_name tinytext NOT NULL,
        last_name tinytext NOT NULL,
		title tinytext NOT NULL,
		organization tinytext NOT NULL,
        personalemail tinytext NOT NULL,
        workemail tinytext NOT NULL,
        homephone tinytext NOT NULL,
		homefax tinytext NOT NULL,
        cellphone tinytext NOT NULL,
		workphone tinytext NOT NULL,
		workfax tinytext NOT NULL,
		address_type tinytext NOT NULL,
        address_line1 tinytext NOT NULL,
        address_line2 tinytext NOT NULL,
        city tinytext NOT NULL,
        zipcode tinytext NOT NULL,
        state tinytext NOT NULL,
		address2_type tinytext NOT NULL,
		address2_line1 tinytext NOT NULL,
        address2_line2 tinytext NOT NULL,
        city2 tinytext NOT NULL,
        zipcode2 tinytext NOT NULL,
        state2 tinytext NOT NULL,
		birthday tinytext NOT NULL,
		anniversary tinytext NOT NULL,
        website VARCHAR(55) NOT NULL,
        notes tinytext NOT NULL,
		addresses longtext NOT NULL,
		phone_numbers longtext NOT NULL,
		email longtext NOT NULL,
		websites longtext NOT NULL,
		options longtext NOT NULL,
		visibility tinytext NOT NULL,
        PRIMARY KEY  (id)
    );";
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
    update_option('connections_version', $connections_version);
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
	$alphaindex = array();
	$i = 0;
	
	$atts = shortcode_atts( array(
			'id' => null,
			'private_override' => false,
			'show_alphaindex' => false,
			), $atts ) ;
			
	if (is_user_logged_in() or $atts['private_override'] != false) { 
		$visibilityfilter = " WHERE (visibility='private' OR visibility='public') ";
	} else {
		$visibilityfilter = " WHERE visibility='public' ";
	}
	
	if ($atts['id'] != null) $visibilityfilter .= " AND id='" . $atts['id'] . "' ";
		
    $sql = "SELECT * FROM ".$wpdb->prefix."connections " . $visibilityfilter ." ORDER BY last_name, first_name";
    $results = $wpdb->get_results($sql);
		
	if ($results != null) {
		//Builds an alpha array of the first letter of the last names.
		foreach ($results as $row) {
			$letter = strtoupper(substr($row->last_name, 0, 1));
			if ($letter != $oldletter) {
				$alphaindex[] = strtoupper(substr($row->last_name, 0, 1));
				$oldletter = strtoupper(substr($row->last_name, 0, 1));
			}
		}
		
		if ($atts['show_alphaindex']) $out = "<div class='cnalphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . _build_alphaindex() . "</div>";
		$out .= "<div class='connections-list'>";
		
		foreach ($results as $row) {
			$options = unserialize($row->options);
	
			//Checks the first letter of the last name to see if it is the next letter in the alpha array and sets the anchor.
			if ($alphaindex[$i] == strtoupper(substr($row->last_name, 0, 1))) {
				$alphaanchor = "<a name='" . $alphaindex[$i] . "'></a>";
				$i++;
			} else {
				$alphaanchor = "";
			}
			  
			//A check to make sure that the birthday column contains a value. If it does, it formats the date into the variable to be used in the output.
			if ($row->birthday) $birthday = date("F jS", $row->birthday);
			  
			//A check to make sure that the anniversary column contains a value. If it does, it formats the date into the variable to be used in the output.
			if ($row->anniversary) $anniversary = date("F jS", $row->anniversary);
			  
			$age = (int) abs( time() - strtotime( $row->ts ) );
			if ( $age < 657000 )	// less than one week: red
				$ageStyle = "color:red";
			elseif ( $age < 1314000 )	// one-two weeks: maroon
				$ageStyle = "color:maroon";
			elseif ( $age < 2628000 )	// two weeks to one month: green
				$ageStyle = "color:green";
			elseif ( $age < 7884000 )	// one - three months: blue
				$ageStyle = "color:blue";
			elseif ( $age < 15768000 )	// three to six months: navy
				$ageStyle = "color:navy";
			elseif ( $age < 31536000 )	// six months to a year: black
				$ageStyle = "color:black";
			else						// more than one year: don't show the update age
				$ageStyle = "display:none";
			
			if ($atts['show_alphaindex']) $out .= $alphaanchor;
			$out .= "<div class='cnitem' id='cn" .  $row->id . "' style='-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;'>\n";
						$out .= "<div style='width:49%; float:left'>";
							if ($options['image']['linked']) $out .= '<img src="' . CN_IMAGE_BASE_URL . $options['image']['name']['entry'] . '" /> <div class="clear"></div>';
							$out .= "<span class='name' id='" .  $row->id . "' style='font-size:larger;font-variant: small-caps'><strong>" . $row->first_name . " " . $row->last_name . "</strong></span>\n";
							if ($row->title) $out .= "<br /><span class='title'>" . $row->title . "</span>\n";
							if ($row->organization) $out .= "<br /><span class='organization'>" . $row->organization . "</span>\n";
							$out .= "<div class='address'>\n";
								if ($row->address_type) $out .= "<br /><span class='address_type'><strong>" . ucfirst($row->address_type) . " Address</strong></span><br />\n";
								$out .= "<span class='address-line1'>" . $row->address_line1 . "</span><br />\n";
								if ($row->address_line2) $out .= "<span class='address-line2'>" . $row->address_line2 . "</span><br />\n";
								if ($row->city) $out .= "<span class='city'>" . $row->city . ",</span>\n";
								if ($row->state) $out .= "<span class='state'>" . $row->state . "</span>\n";
								if ($row->zipcode) $out .= "<span class='zipcode'>" . $row->zipcode . "</span>\n";
							$out .= "</div>";
							$out .= "<div class='address2'>\n";
								if ($row->address2_type) $out .= "<br /><span class='address_type'><strong>" . ucfirst($row->address2_type) . " Address</strong></span><br />\n";
								$out .= "<span class='address2-line1'>" . $row->address2_line1 . "</span><br />\n";
								if ($row->address2_line2) $out .= "<span class='address2-line2'>" . $row->address2_line2 . "</span><br />\n";
								if ($row->city2) $out .= "<span class='city2'>" . $row->city2 . ",</span>\n";
								if ($row->state2) $out .= "<span class='state2'>" . $row->state2 . "</span>\n";
								if ($row->zipcode2) $out .= "<span class='zipcode2'>" . $row->zipcode2 . "</span>\n";
							$out .= "</div>";
						$out .= "</div>";
						$out .= "<div align='right' >";
							if ($row->homephone) $out .= "<span class='homephone'><strong>Home Phone:</strong> " . $row->homephone . "</span><br />\n";
							if ($row->homefax) $out .= "<span class='homefax'><strong>Home Fax:</strong> " . $row->homefax . "</span><br />\n";
							if ($row->cellphone) $out .= "<span class='cellphone'><strong>Cell Phone:</strong> " . $row->cellphone . "</span><br />\n";
							if ($row->workphone) $out .= "<span class='workphone'><strong>Work Phone:</strong> " . $row->workphone . "</span><br />\n";
							if ($row->workfax) $out .= "<span class='workfax'><strong>Work Fax:</strong> " . $row->workfax . "</span><br />\n";
							if ($row->personalemail) $out .= "<br /><strong>Personal:</strong> <a class='personalemail' href='mailto:" . antispambot($row->personalemail) . "'>" . antispambot($row->personalemail) . "</a><br />\n";
							if ($row->workemail) $out .= "<strong>Work:</strong> <a class='workemail' href='mailto:" . antispambot($row->workemail) . "'>" . antispambot($row->workemail) . "</a><br />\n";
							if ($row->website) $out .= "<strong>Website:</strong> <a target='_blank' href='" . $row->website . "'>" . $row->website . "</a><br />\n";
							if ($row->birthday) $out .= "<br /><span class='birthday'><strong>Birthday:</strong> ".$birthday."</span><br />\n";
							if ($row->anniversary) $out .= "<span class='anniversary'><strong>Anniversary:</strong> ".$anniversary."</span><br />\n";
							$out .= "<br /><span style='" . $ageStyle . "; font-size:x-small; font-variant: small-caps; position: absolute; right: 6px; bottom: 8px;'>Updated " . human_time_diff(strtotime($row->ts)) . " ago</span><br />\n";
						$out .= "</div>\n";
						$out .= "<div style='clear:both'></div></div>\n";
		}
		$out .= "</div>\n";
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