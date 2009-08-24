<?php
function connectionsShowViewPage()
{
	global $wpdb, $current_user;
		
	get_currentuserinfo();
	$plugin_options = new pluginOptions();
	$form = new formObjects();
	
	/**
	 * @TODO: Move the session check to the base file -> start method.
	 */
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
	
	/**
	 * @TODO: Finish checking and setting the session token variables for the
	 * other form actions.
	 */
	/*
	 * Check to make sure the id and token are set in the query string. If they
	 * are not, they are set to false. This way they will fail the edit/copy form
	 * comparason and access to edit/copy entries where the token was not set will
	 * be denied.
	 * 
	 * Hopefully this prevents users from gaining access to entry data that they shouldn't.
	 */
	if (!isset($_GET['id'])) $ID = false;
	if (!isset($_GET['token'])) $token = false;
	
	if ($ID) $sessionTokenEdit =  $_SESSION['connections']['formTokens']['edit_' . $_GET['id']]['token'];
	if ($ID) $sessionTokenCopy =  $_SESSION['connections']['formTokens']['copy_' . $_GET['id']]['token'];
	
	/**
	 * @TODO: Split the edit/copy form into separate form actions. This is too confusing
	 * to follow and to debug.
	 */
	if ($_GET['action']=='editform' AND ($sessionTokenEdit === $token OR $sessionTokenCopy === $token))
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
					
					<form action="admin.php?page=connections&action=<?php echo $formAction ?>&id=<?php echo $entry->id; ?>" method="post" enctype="multipart/form-data">
					<?php 
						echo $entryForm->entryForm($entry);
					?>
					<input type="hidden" name="formId" value="<?php echo $formID ?>" />
					<input type="hidden" name="token" value="<?php echo $form->token($formID); ?>" />
					
					<p class="submit">
						<input  class="button-primary" type="submit" name="<?php echo $inputName ?>" value="Save" />
						<a href="admin.php?page=connections" class="button button-warning">Cancel</a> <!-- THERE HAS TO BE A BETTER WAY THAN REFERRING DIRECTLY TO THE TOOLS.PHP -->
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
		
		if ($_POST['doaction'] AND $_SESSION['connections']['formTokens']['do_action']['token'] === $_POST['token'])
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
		
		if ($_GET['action']=='delete' AND $_SESSION['connections']['formTokens']['delete_'.$_GET['id']]['token'] === $_GET['token'])
		{
	        $entry = new entry();
			$entry->delete($_GET['id']);
			echo '<div id="message" class="updated fade"><p><strong>The entry has been deleted.</strong></p></div>';
			unset($entry);
			unset($_SESSION['connections']['formTokens']);
	    }
		
		if ($_POST['dofilter'])
		{
			$plugin_options->setEntryType($_POST['entry_type'], $current_user->ID);
			$plugin_options->setVisibilityType($_POST['visibility_type'], $current_user->ID);
			
			$plugin_options->saveOptions();
		}
		
		?>
	
		<div class="wrap">
			<div class="icon32" id="icon-connections"><br/></div>
			<h2>Connections : Entry List</h2>
			
			<?php
				/*
				 * This switch will modify the query string based on the user selection
				 * for the visbility type in the admin.
				 * 
				 * The stored visibility filter for the current user is checked against
				 * the current user's capabilites; if the current user IS NOT permitted
				 * the query string is set not to query the visibility type and then the
				 * current users filter is set to NULL to show all. IF the current user
				 * IS permitted the query string will query the visibility type. Finally
				 * the remaining visibility types are checked and if NOT permitted that is
				 * appened to the query string.
				 */
				switch ($plugin_options->getVisibilityType($current_user->ID))
				{
					case 'public':
						if (!current_user_can('connections_view_public') && !$plugin_options->getAllowPublic())
						{
							$visibilityfilter = " AND NOT visibility='public' ";
							$plugin_options->setVisibilityType('', $current_user->ID);
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
							$plugin_options->setVisibilityType('', $current_user->ID);
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
							$plugin_options->setVisibilityType('', $current_user->ID);
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
					
						<form action="admin.php?page=connections" method="post">
						
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
								<?php echo $form->buildSelect('entry_type', array(''=>'Show All Enties', 'individual'=>'Show Individuals', 'organization'=>'Show Organizations', 'connection_group'=>'Show Connection Groups'), $plugin_options->getEntryType($current_user->ID))?>
								
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
										echo $form->buildSelect('visibility_type', $visibilitySelect, $plugin_options->getVisibilityType($current_user->ID));
									}
								?>
								<input id="doaction" class="button-secondary action" type="submit" name="dofilter" value="Filter" />
								<input type="hidden" name="formId" value="do_action" />
								<input type="hidden" name="token" value="<?php echo $form->token("do_action"); ?>" />
							</div>
						</div>
						<div class="clear"></div>
						<div class="tablenav">
							<div class="tablenav-pages">
								<?php echo $form->buildAlphaIndex(); ?>
							</div>
						</div>
						<div class="clear"></div>
						
				       	<table cellspacing="0" class="widefat connections">
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
									if ($plugin_options->getEntryType($current_user->ID) != "" )	{
										if ($entry->getEntryType() != $plugin_options->getEntryType($current_user->ID)) continue;
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
									
									/*
									 * Genreate the edit token for the entry becuse it has two links.
									 */
									$editToken = $form->token('edit_' . $entry->getId());
									
									echo "<tr id='row" . $entry->getId() . "' class='parent-row'>";
										echo "<th class='check-column' scope='row'><input type='checkbox' value='" . $entry->getId() . "' name='entry[]'/></th> \n";
											echo '<td colspan="2">';
											if ($setAnchor) echo $setAnchor;
											echo '<div style="float:right"><a href="#wphead" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" /></a></div>';
												
												if (current_user_can('connections_edit_entry'))
												{
													echo '<a class="row-title" title="Edit ' . $entry->getFullFirstLastName() . '" href="admin.php?page=connections&action=editform&id=' . $row->id . '&token=' . $editToken . '"> ' . $entry->getFullLastFirstName() . '</a><br />';
												}
												else
												{
													echo '<strong>' . $entry->getFullLastFirstName() . '</strong>';
												}
												
												echo '<div class="row-actions">';
													echo '<a class="detailsbutton" id="row-' . $entry->getId() . '">Show Details</a> | ';
													if (current_user_can('connections_edit_entry')) echo '<a class="editbutton" href="admin.php?page=connections&action=editform&id=' . $entry->getId() . '&editid=true&token=' . $editToken . '" title="Edit ' . $entry->getFullFirstLastName() . '">Edit</a> | ';
													if (current_user_can('connections_add_entry')) echo '<a class="copybutton" href="admin.php?page=connections&action=editform&id=' . $entry->getId() . '&copyid=true&token=' . $form->token('copy_' . $entry->getId()) . '" title="Copy ' . $entry->getFullFirstLastName() . '">Copy</a> | ';
													if (current_user_can('connections_delete_entry')) echo '<a class="submitdelete" onclick="return confirm(\'You are about to delete this entry. \\\'Cancel\\\' to stop, \\\'OK\\\' to delete\');" href="admin.php?page=connections&action=delete&id=' . $entry->getId() . '&token=' . $form->token('delete_' . $entry->getId()) . '" title="Delete ' . $entry->getFullFirstLastName() . '">Delete</a>';
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
													echo '<strong>' . $plugin_options->getConnectionRelation($value) . ':</strong> ' . '<a href="admin.php?page=connections&action=editform&id=' . $relation->getId() . '&editid=true&token=' . $form->token('copy_' . $relation->getId()) . '"" title="Edit ' . $relation->getFullFirstLastName() . '">' . $relation->getFullFirstLastName() . '</a>' . '<br />' . "\n";
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
	
	<?php }
}
?>