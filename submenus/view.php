<?php
function connectionsShowViewPage()
{
	global $wpdb, $current_user, $connections;
	
	if (class_exists('connectionsProLoad')) global $connectionsPro;
		
	get_currentuserinfo();
	
	/**
	 * @TODO: Scrub code to use global $options instead of defining a new object.
	 */
	//$plugin_options = new cnOptions();
	
	$form = new cnFormObjects();
	$showEntryList = true;
		
	switch ($_GET['action'])
	{
		case 'copy':
			/*
			 * Check whether current user can add an entry.
			 */
			if (current_user_can('connections_add_entry'))
			{
				/*
				 * Make sure the action token and $_SESSION token are set and equal before
				 * performing the copy. This should hopefully prevent user from accessing
				 * entries for which they do not have permission
				 */
				if (isset($_GET['id']))
				{
					$id = $_GET['id'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_entry_id');
				}
				
				if (isset($_GET['token']))
				{
					$token = $_GET['token'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_entry_token');
				}
				
				if (isset($_SESSION['cn_session']['formTokens']['copy_' . $_GET['id']]['token']))
				{
					$sessionToken = $_SESSION['cn_session']['formTokens']['copy_' . $_GET['id']]['token'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_session_token');
				}
				
				if ($sessionToken === $token && !$error)
				{
					$entryForm = new cnEntryForm();
					$form = new cnFormObjects();
					$entry = new cnEntry();
					$entry = $entry->get($_GET['id']);
					
					$out = '<div class="wrap">';
						$out .= '<div class="form-wrap" style="width:600px; margin: 0 auto;">';
							$out .= '<h2><a name="new"></a>Add Entry</h2>';
							
							$out .= '<form action="admin.php?page=connections&action=add&id=' . $_GET['id'] . '" method="post" enctype="multipart/form-data">';
							 
								$out .= $entryForm->displayForm($entry);
								
								$out .= '<input type="hidden" name="formId" value="entry_form" />';
								$out .= '<input type="hidden" name="token" value="' . $form->token('entry_form') . '" />';
								
								$out .= '<p class="submit">';
									$out .= '<input  class="button-primary" type="submit" name="save" value="Save" />';
									$out .= '<a href="admin.php?page=connections" class="button button-warning">Cancel</a>';
								$out .= '</p>';
							$out .= '</form>';
						$out .= '</div>';
					$out .= '</div>';
				
					unset($entry);
					$showEntryList = false;
					
					echo $out;
				}
				else
				{
					$connections->setErrorMessage('form_token_mismatch');
				}
			}
			else
			{
				$connections->setErrorMessage('capability_add');
			}
		break;
		
		case 'edit':
			/*
			 * Check whether the current user can edit entries.
			 */
			if (current_user_can('connections_edit_entry'))
			{
				/*
				 * Make sure the action token and $_SESSION token are set and equal before
				 * performing the copy. This should hopefully prevent user from accessing
				 * entries for which they do not have permission
				 */
				if (isset($_GET['id']))
				{
					$id = $_GET['id'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_entry_id');
				}
				
				if (isset($_GET['token']))
				{
					$token = $_GET['token'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_entry_token');
				}
				
				if (isset($_SESSION['cn_session']['formTokens']['edit_' . $_GET['id']]['token']))
				{
					$sessionToken = $_SESSION['cn_session']['formTokens']['edit_' . $_GET['id']]['token'];
				}
				else
				{
					$error = true;
					$connections->setErrorMessage('form_no_session_token');
				}
				
				if ($sessionToken === $token && !$error)
				{
					$entryForm = new cnEntryForm();
					$form = new cnFormObjects();
					$entry = new cnEntry();
					$entry = $entry->get($_GET['id']);
					
					$out = '<div class="wrap">';
						$out .= '<div class="form-wrap" style="width:600px; margin: 0 auto;">';
							$out .= '<h2><a name="new"></a>Edit Entry</h2>';
							
							$out .= '<form action="admin.php?page=connections&action=update&id=' . $_GET['id'] . '" method="post" enctype="multipart/form-data">';
							 
								$out .= $entryForm->displayForm($entry);
								
								$out .= '<input type="hidden" name="formId" value="entry_form" />';
								$out .= '<input type="hidden" name="token" value="' . $form->token('entry_form') . '" />';
								
								$out .= '<p class="submit">';
									$out .= '<input  class="button-primary" type="submit" name="update" value="Update" />';
									$out .= '<a href="admin.php?page=connections" class="button button-warning">Cancel</a>';
								$out .= '</p>';
							$out .= '</form>';
						$out .= '</div>';
					$out .= '</div>';
					
					unset($entry);
					$showEntryList = false;
					
					echo $out;
				}
				else
				{
					$connections->setErrorMessage('form_token_mismatch');
				}
			}
			else
			{
				$connections->setErrorMessage('capability_edit');
			}
		break;
		
		case 'delete':
			delete($_GET['id']);
			$showEntryList = true;
		break;
		
		case 'add':
			/*
			 * Check whether the current user can add an entry.
			 */
			if (current_user_can('connections_add_entry'))
			{
				/*
				 * Check whether the token for the current entry equal the token stored in the $_SESSION.
				 */
				if ($_POST['save'] && $_SESSION['cn_session']['formTokens']['entry_form']['token'] === $_POST['token'])
				{
					$entryForm = new cnEntryForm();
					echo $entryForm->processEntry();
					unset($_SESSION['cn_session']['formTokens']);
				}
				else
				{
					$connections->setErrorMessage('form_token_mismatch');
				}
				
				$showEntryList = true;
			}
			else
			{
				$connections->setErrorMessage('capability_add');
			}
		break;
		
		case 'update':
			/*
			 * Check whether the current user can edit an entry.
			 */
			if (current_user_can('connections_edit_entry'))
			{
				/*
				 * Check whether the token for the current entry equal the token stored in the $_SESSION.
				 */
				if ($_POST['update'] && $_SESSION['cn_session']['formTokens']['entry_form']['token'] === $_POST['token'])
				{
					$entryForm = new cnEntryForm();
					echo $entryForm->processEntry();
					unset($_SESSION['cn_session']['formTokens']);
				}
				else
				{
					$connections->setErrorMessage('form_token_mismatch');
				}
				
				$showEntryList = true;
			}
			else
			{
				$connections->setErrorMessage('capability_edit');
			}
		break;

		case 'do':
			switch ($_POST['action'])
			{
				case 'delete':
					delete($_POST['entry']);
				break;
				
				case 'public':
				case 'private':
				case 'unlisted':
					/*
					 * Check whether the current user can edit entries.
					 */
					if (current_user_can('connections_edit_entry'))
					{
						/*
						 * Check whether the token for the current entry equal the token stored in the $_SESSION.
						 */
						if ($_SESSION['cn_session']['formTokens']['do_action']['token'] === $_POST['token'])
						{
							
							foreach ($_POST['entry'] as $id)
							{
								$entry = new cnEntry();
								$entry->set($id);
								
								$entry->setVisibility($_POST['action']);
								$entry->update();
								unset($entry);
							}
							
							$connections->setSuccessMessage('form_entry_visibility_bulk');
							
							unset($_SESSION['cn_session']['formTokens']);
						}
					}
					else
					{
						$connections->setErrorMessage('capability_edit');
					}
				break;
			}
			
			if ($_POST['filter'])
			{
				$connections->options->setEntryType($_POST['entry_type'], $current_user->ID);
				$connections->options->setVisibilityType($_POST['visibility_type'], $current_user->ID);
				
				$connections->options->saveOptions();
			}
			
			$showEntryList = true;
			
		break;

	}
	
	if ($showEntryList === true)
	{
		echo $connections->displayMessages();
		
		/*
		 * Check whether user can view the entry list
		 */
		if(current_user_can('connections_view_entry_list'))
		{
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
					/*switch ($connections->options->getVisibilityType($current_user->ID))
					{
						case 'public':
							if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic())
							{
								$visibilityfilter = " AND NOT visibility='public' ";
								$connections->options->setVisibilityType('', $current_user->ID);
								$connections->options->saveOptions();
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
								$connections->options->setVisibilityType('', $current_user->ID);
								$connections->options->saveOptions();
							}
							else
							{
								$visibilityfilter = " AND visibility='private' ";
							}
							if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
							if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
							
							break;
							
						case 'unlisted':
							if (!current_user_can('connections_view_unlisted'))
							{
								$visibilityfilter = " AND NOT visibility='unlisted' ";
								$connections->options->setVisibilityType('', $current_user->ID);
								$connections->options->saveOptions();
							}
							else
							{
								$visibilityfilter = " AND visibility='unlisted' ";
							}
							if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
							if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
							
							break;
						
						default:
							if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
							if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
							if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
							break;
					}
					
					$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = ''" . $visibilityfilter . ")
							UNION
							(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != ''" . $visibilityfilter . ")
							UNION
							(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibilityfilter . ")
							ORDER BY order_by, last_name, first_name";*/
							
					//$results = $wpdb->get_results($sql);
					//$results = $sql->getEntries();
					$results = $connections->db->getEntries();
					
					$connections->filter->byEntryType(&$results, $connections->options->getEntryType($current_user->ID));
					$connections->filter->byEntryVisibility(&$results, $connections->options->getVisibilityType($current_user->ID));
					?>
					
					<form action="admin.php?page=connections&action=do" method="post">
					
					<div class="tablenav">
						
						<?php
							if (isset($connectionsPro))
							{
								echo '<div class="alignleft actions">' . $connectionsPro->displayFilters($results) . '</div>';
								$results = $connectionsPro->applyFilters($results);
							}
						?>
						
						<div class="alignleft actions">
							<?php echo $form->buildSelect('entry_type', array(''=>'Show All Enties', 'individual'=>'Show Individuals', 'organization'=>'Show Organizations', 'connection_group'=>'Show Connection Groups'), $connections->options->getEntryType($current_user->ID))?>
							
							<?php
								/**
								 * Builds the visibilty select list base on current user capabilities.
								 */
								if (current_user_can('connections_view_public') || $connections->options->getAllowPublic()) $visibilitySelect['public'] = 'Show Public';
								if (current_user_can('connections_view_private'))	$visibilitySelect['private'] = 'Show Private';
								if (current_user_can('connections_view_unlisted'))	$visibilitySelect['unlisted'] = 'Show Unlisted';
								
								if (isset($visibilitySelect))
								{
									$showAll[''] = 'Show All';
									$visibilitySelect = $showAll + $visibilitySelect;
									echo $form->buildSelect('visibility_type', $visibilitySelect, $connections->options->getVisibilityType($current_user->ID));
								}
							?>
							<input id="doaction" class="button-secondary action" type="submit" name="filter" value="Filter" />
							<input type="hidden" name="formId" value="do_action" />
							<input type="hidden" name="token" value="<?php echo $form->token("do_action"); ?>" />
						</div>
					</div>
					<div class="clear"></div>
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
						
						<div class="tablenav-pages">
							<?php
								/*
								 * Dynamically builds the alpha index based on the available entries.
								 */
								foreach ($results as $row)
								{
									$entry = new cnEntry($row);
									$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
									if ($currentLetter != $previousLetter)
									{
										$setAnchor .= '<a href="#' . $currentLetter . '">' . $currentLetter . '</a> ';
										$previousLetter = $currentLetter;
									}
								}
								
								echo $setAnchor;
							?>
						</div>
					</div>
					<div class="clear"></div>
					
			       	<table cellspacing="0" class="widefat connections">
						<thead>
				            <tr>
				                <th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"/></th>
								<th class="col" style="width:10%;"></th>
								<th scope="col" colspan="2" style="width:50%;">Name</th>
								<th scope="col" style="width:20%;">Visibility</th>
								<th scope="col" style="width:20%;">Last Modified</th>
				            </tr>
						</thead>
						<tfoot>
				            <tr>
				                <th class="manage-column column-cb check-column" scope="col"><input type="checkbox"/></th>
								<th class="col" style="width:10%;"></th>
								<th scope="col" colspan="2" style="width:50%;">Name</th>
								<th scope="col" style="width:20%;">Visibility</th>
								<th scope="col" style="width:20%;">Last Modified</th>
				            </tr>
						</tfoot>
						<tbody>
							
							<?php
							
							foreach ($results as $row) {
								$entry = new cnEntry($row);
								
								/**
								 * @TODO: Use the Output class to show entry details.
								 * @TODO: Add the vCard.
								 */								
								$object = new cnOutput($row);
								
								$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
								if ($currentLetter != $previousLetter) {
									$setAnchor = "<a name='$currentLetter'></a>";
									$previousLetter = $currentLetter;
								} else {
									$setAnchor = null;
								}
								
								/*
								 * Genreate the edit token for the entry because it has two links.
								 */
								$editToken = $form->token('edit_' . $entry->getId());
								
								echo "<tr id='row-" . $entry->getId() . "' class='parent-row'>";
									echo "<th class='check-column' scope='row'><input type='checkbox' value='" . $entry->getId() . "' name='entry[]'/></th> \n";
										echo '<td>' . $object->getThumbnailImage() . '</td>';
										echo '<td  colspan="2">';
											if ($setAnchor) echo $setAnchor;
											echo '<div style="float:right"><a href="#wphead" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" /></a></div>';
											
											if (current_user_can('connections_edit_entry'))
											{
												echo '<a class="row-title" title="Edit ' . $entry->getFullFirstLastName() . '" href="admin.php?page=connections&action=edit&id=' . $entry->getId() . '&token=' . $editToken . '"> ' . $entry->getFullLastFirstName() . '</a><br />';
											}
											else
											{
												echo '<strong>' . $entry->getFullLastFirstName() . '</strong>';
											}
											
											echo '<div class="row-actions">';
												echo '<a class="detailsbutton" id="row-' . $entry->getId() . '">Show Details</a> | ';
												//if (current_user_can('connections_edit_entry')) echo '<a class="editbutton" href="admin.php?page=connections&action=editform&id=' . $entry->getId() . '&editid=true&token=' . $editToken . '" title="Edit ' . $entry->getFullFirstLastName() . '">Edit</a> | ';
												if (current_user_can('connections_edit_entry')) echo '<a class="editbutton" href="admin.php?page=connections&action=edit&id=' . $entry->getId() . '&token=' . $editToken . '" title="Edit ' . $entry->getFullFirstLastName() . '">Edit</a> | ';
												//if (current_user_can('connections_add_entry')) echo '<a class="copybutton" href="admin.php?page=connections&action=editform&id=' . $entry->getId() . '&copyid=true&token=' . $form->token('copy_' . $entry->getId()) . '" title="Copy ' . $entry->getFullFirstLastName() . '">Copy</a> | ';
												if (current_user_can('connections_add_entry')) echo '<a class="copybutton" href="admin.php?page=connections&action=copy&id=' . $entry->getId() . '&token=' . $form->token('copy_' . $entry->getId()) . '" title="Copy ' . $entry->getFullFirstLastName() . '">Copy</a> | ';
												if (current_user_can('connections_delete_entry')) echo '<a class="submitdelete" onclick="return confirm(\'You are about to delete this entry. \\\'Cancel\\\' to stop, \\\'OK\\\' to delete\');" href="admin.php?page=connections&action=delete&id=' . $entry->getId() . '&token=' . $form->token('delete_' . $entry->getId()) . '" title="Delete ' . $entry->getFullFirstLastName() . '">Delete</a>';
											echo '</div>';
									echo "</td> \n";
									echo "<td ><strong>" . $entry->displayVisibiltyType() . "</strong></td> \n";												
									echo "<td >" . $entry->getFormattedTimeStamp() . "</td> \n";											
								echo "</tr> \n";
								
								echo "<tr class='child-row-" . $entry->getId() . " entrydetails' id='contact-" . $entry->getId() . "-detail' style='display:none;'>";
									echo "<td >&nbsp;</td> \n";
									echo "<td >&nbsp;</td> \n";
									echo "<td colspan='2'>";
										
										if ($entry->getConnectionGroup())
										{
											//$connections = $entry->getConnectionGroup();
											$count = count($entry->getConnectionGroup());
											$i = 0;
											
											foreach ($entry->getConnectionGroup() as $key => $value)
											{
												$relation = new cnEntry();
												$relation->set($key);
												
												/**
												 * @TODO: Edit link for relation should only show if the current user has edit capabilities.
												 * @TODO: First check to make sure a relation exists before out. Relation could have been deleted.
												 */
												
												echo '<strong>' . $connections->options->getConnectionRelation($value) . ':</strong> ' . '<a href="admin.php?page=connections&action=edit&id=' . $relation->getId() . '&token=' . $form->token('edit_' . $relation->getId()) . '"" title="Edit ' . $relation->getFullFirstLastName() . '">' . $relation->getFullFirstLastName() . '</a>' . '<br />' . "\n";
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
											$addressObject = new cnAddresses();
								
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
											$emailAddressObject = new cnEmail();
											
											foreach ($entry->getEmailAddresses() as $emailRow)
											{
												if ($emailAddressObject->getAddress($emailRow) != null) echo "<strong>" . $emailAddressObject->getName($emailRow) . ":</strong><br /><a href='mailto:" . $emailAddressObject->getAddress($emailRow) . "'>" . $emailAddressObject->getAddress($emailRow) . "</a><br /><br />";
											}
										}
										
										if ($entry->getIm())
										{
											$imObject = new cnIM();
											
											foreach ($entry->getIm() as $imRow)
											{
												if ($imObject->getId($imRow) != "") echo "<strong>" . $imObject->getName($imRow) . ":</strong><br />" . $imObject->getId($imRow) . "<br /><br />";
											}
										}
										
										if ($entry->getWebsites())
										{
											$websiteObject = new cnWebsite();
											
											foreach ($entry->getWebsites() as $websiteRow)
											{
												if ($websiteObject->getAddress($websiteRow) != "") echo "<strong>Website:</strong><br /><a target='_blank' href='" . $websiteObject->getAddress($websiteRow) . "'>" . $websiteObject->getAddress($websiteRow) . "</a><br /><br />";
											}
										}
										
										if ($entry->getPhoneNumbers())
										{
											$phoneNumberObject = new cnPhoneNumber();
																						
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
									echo "<td >&nbsp;</td> \n";
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
					<p style="font-size:smaller; text-align:center">This is version <?php echo $connections->options->getVersion(); ?> of Connections.</p>
					
					
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align:center">
						<input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="5070255">
						<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
						<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				
			</div>
			
			<!-- <script type="text/javascript">
				This is now part of WP core.
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
			</script> -->
		<?php
		}
		else
		{
			$connections->setErrorMessage('capability_view_entry_list');
		}
	}
}

function delete($ids)
{
	global $connections;
	
	if (current_user_can('connections_delete_entry'))
	{
		$error = false;
		
		if (!is_array($ids))
		{
			if (isset($_GET['id']))
			{
				$id = $_GET['id'];
			}
			else
			{
				$error = true;
				$connections->setErrorMessage('form_no_entry_id');
			}
			
			if (isset($_GET['token']))
			{
				$token = $_GET['token'];
			}
			else
			{
				$error = true;
				$connections->setErrorMessage('form_no_entry_token');
			}
			
			if (isset($_SESSION['cn_session']['formTokens']['delete_' . $id]['token']))
			{
				$sessionToken = $_SESSION['cn_session']['formTokens']['delete_' . $id]['token'];
			}
			else
			{
				$error = true;
				$connections->setErrorMessage('form_no_session_token');
			}
			
			if ($sessionToken === $token && !$error)
			{
		        $entry = new cnEntry();
				$entry->delete($_GET['id']);
				$connections->setSuccessMessage('form_entry_delete');
				unset($entry);
		    }
			else
			{
				$connections->setErrorMessage('form_token_mismatch');
			}
		}
		else
		{
			if ($_SESSION['cn_session']['formTokens']['do_action']['token'] === $_POST['token'])
			{
				foreach ($ids as $id)
				{
					$entry = new cnEntry();
					$entry->delete($id);
					unset($entry);
				}
				$connections->setSuccessMessage('form_entry_delete_bulk');
			}
			else
			{
				$connections->setErrorMessage('form_token_mismatch');
			}
		}
	
		unset($_SESSION['cn_session']['formTokens']);
	}
	else
	{
		$connections->setErrorMessage('capability_delete');
	}
}
?>