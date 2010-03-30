<?php
function connectionsShowViewPage()
{
	//global $wpdb, $current_user, $connections;
	global $wpdb, $connections;
	
	if (class_exists('connectionsProLoad')) global $connectionsPro;
		
	//get_currentuserinfo();
	
	switch ($_GET['action'])
	{
		case 'copy':
			/*
			 * Check whether current user can add an entry.
			 */
			if (current_user_can('connections_add_entry'))
			{
				global $connections;
				$id = esc_attr($_GET['id']);
				check_admin_referer('entry_copy_' . $id);
				
				$entryForm = new cnEntryForm();
				$form = new cnFormObjects();
				$entry = $connections->retrieve->entry($id);
				
				echo '<div class="wrap">';
					echo '<div class="form-wrap" style="width:880px; margin: 0 auto;">';
						echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
							echo '<h2><a name="new"></a>Add Entry</h2>';
							
								$attr = array(
											 'action' => 'admin.php?page=connections&action=add&id=' . $id,
											 'method' => 'post',
											 'enctype' => 'multipart/form-data',
											 );
								
								$form->open($attr);
								$form->tokenField('add_entry');
								
								$entryForm->displayForm($entry);
								$form->close();
								
						echo '</div>';
					echo '</div>';
				echo '</div>';
			
				unset($entry);
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
				$form = new cnFormObjects();
				$id = esc_attr($_GET['id']);
				check_admin_referer('entry_edit_' . $id);
				
				$entryForm = new cnEntryForm();
				$entry = $connections->retrieve->entry($id);
				
				echo '<div class="wrap">';
					echo '<div class="form-wrap" style="width:880px; margin: 0 auto;">';
						echo '<div id="poststuff" class="metabox-holder has-right-sidebar">';
							echo '<h2><a name="new"></a>Edit Entry</h2>';
							
								$attr = array(
											 'action' => 'admin.php?page=connections&action=update&id=' . $id,
											 'method' => 'post',
											 'enctype' => 'multipart/form-data',
											 );
								
								$form->open($attr);
								$form->tokenField('update_entry');
								
								$entryForm->displayForm($entry);
								$form->close();
								
						echo '</div>';
					echo '</div>';
				echo '</div>';
				
				unset($entry);
			}
			else
			{
				$connections->setErrorMessage('capability_edit');
			}
		break;
		
		default:
			$form = new cnFormObjects();
			$categoryObjects = new cnCategoryObjects();
			
			$connections->displayMessages();
			
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
						$results = $connections->retrieve->entries();
						$connections->filter->permitted(&$results);
						$connections->filter->byEntryType(&$results, $connections->currentUser->getFilterEntryType());
						$connections->filter->byEntryVisibility(&$results, $connections->currentUser->getFilterVisibility());
					?>
						
						<form action="admin.php?page=connections&action=do" method="post">
						
						<?php $form->tokenField('bulk_action'); ?>
						
						<div class="tablenav">
							
							<?php
								if (isset($connectionsPro))
								{
									echo '<div class="alignleft actions">' . $connectionsPro->displayFilters($results) . '</div>';
									$results = $connectionsPro->applyFilters($results);
								}
							?>
							
							<div class="alignleft actions">
								<?php
									echo '<select class="postform" id="category" name="category">';
										echo '<option value="0">Show All Categories</option>';
										echo $categoryObjects->buildCategoryRow('option', $connections->retrieve->categories(), $level, $connections->currentUser->getFilterCategory());
									echo '</select>';
									
									echo $form->buildSelect('entry_type', array('all'=>'Show All Enties', 'individual'=>'Show Individuals', 'organization'=>'Show Organizations', 'connection_group'=>'Show Connection Groups'), $connections->currentUser->getFilterEntryType());
									
								?>
								
								<?php
									/*
									 * Builds the visibilty select list base on current user capabilities.
									 */
									if (current_user_can('connections_view_public') || $connections->options->getAllowPublic()) $visibilitySelect['public'] = 'Show Public';
									if (current_user_can('connections_view_private'))	$visibilitySelect['private'] = 'Show Private';
									if (current_user_can('connections_view_unlisted'))	$visibilitySelect['unlisted'] = 'Show Unlisted';
									
									if (isset($visibilitySelect))
									{
										/*
										 * Add the 'Show All' option and echo the list.
										 */
										$showAll['all'] = 'Show All';
										$visibilitySelect = $showAll + $visibilitySelect;
										echo $form->buildSelect('visibility_type', $visibilitySelect, $connections->currentUser->getFilterVisibility());
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
									 * Genreate the edit, copy and delete URLs with nonce tokens.
									 */
									$editTokenURL = $form->tokenURL('admin.php?page=connections&action=edit&id=' . $entry->getId(), 'entry_edit_' . $entry->getId());
									$copyTokenURL = $form->tokenURL('admin.php?page=connections&action=copy&id=' . $entry->getId(), 'entry_copy_' . $entry->getId());
									$deleteTokenURL = $form->tokenURL('admin.php?page=connections&action=delete&id=' . $entry->getId(), 'entry_delete_' . $entry->getId());
									
									
									echo "<tr id='row-" . $entry->getId() . "' class='parent-row'>";
										echo "<th class='check-column' scope='row'><input type='checkbox' value='" . $entry->getId() . "' name='entry[]'/></th> \n";
											echo '<td>' . $object->getThumbnailImage() . '</td>';
											echo '<td  colspan="2">';
												if ($setAnchor) echo $setAnchor;
												echo '<div style="float:right"><a href="#wphead" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" /></a></div>';
												
												if (current_user_can('connections_edit_entry'))
												{
													echo '<a class="row-title" title="Edit ' . $entry->getFullFirstLastName() . '" href="' . $editTokenURL . '"> ' . $entry->getFullLastFirstName() . '</a><br />';
												}
												else
												{
													echo '<strong>' . $entry->getFullLastFirstName() . '</strong>';
												}
												
												echo '<div class="row-actions">';
													echo '<a class="detailsbutton" id="row-' . $entry->getId() . '">Show Details</a> | ';
													if (current_user_can('connections_edit_entry')) echo '<a class="editbutton" href="' . $editTokenURL . '" title="Edit ' . $entry->getFullFirstLastName() . '">Edit</a> | ';
													if (current_user_can('connections_add_entry')) echo '<a class="copybutton" href="' . $copyTokenURL . '" title="Copy ' . $entry->getFullFirstLastName() . '">Copy</a> | ';
													if (current_user_can('connections_delete_entry')) echo '<a class="submitdelete" onclick="return confirm(\'You are about to delete this entry. \\\'Cancel\\\' to stop, \\\'OK\\\' to delete\');" href="' . $deleteTokenURL . '" title="Delete ' . $entry->getFullFirstLastName() . '">Delete</a>';
												echo '</div>';
										echo "</td> \n";
										echo "<td ><strong>" . $entry->displayVisibiltyType() . "</strong></td> \n";												
										echo '<td >';
											echo '<strong>On:</strong> ' . $entry->getFormattedTimeStamp() . '<br />';
											echo '<strong>By:</strong> ' . $entry->getEditedBy();
										echo "</td> \n";											
									echo "</tr> \n";
									
									echo "<tr class='child-row-" . $entry->getId() . " entrydetails' id='contact-" . $entry->getId() . "-detail' style='display:none;'>";
										echo "<td >&nbsp;</td> \n";
										echo "<td >&nbsp;</td> \n";
										echo "<td colspan='2'>";
											
											
											/*
											 * Check if the entry has relations. Count the relations and then cycle thru each relation.
											 * Before the out check that the related entry still exists. If it does and the current user
											 * has edit capabilites the edit link will be displayed. If the user does not have edit capabilities
											 * the only the relation will be shown. After all relations have been output insert a <br>
											 * for spacing [@TODO: NOTE: this should be done with styles].
											 */
											if ($entry->getConnectionGroup())
											{
												$count = count($entry->getConnectionGroup());
												$i = 0;
												
												foreach ($entry->getConnectionGroup() as $key => $value)
												{
													$relation = new cnEntry();
													$relation->set($key);
													$editRelationTokenURL = $form->tokenURL('admin.php?page=connections&action=edit&id=' . $relation->getId(), 'entry_edit_' . $relation->getId());
													
													if ($relation->getId())
													{
														if (current_user_can('connections_edit_entry'))
														{
															echo '<strong>' . $connections->options->getConnectionRelation($value) . ':</strong> ' . '<a href="' . $editRelationTokenURL . '" title="Edit ' . $relation->getFullFirstLastName() . '">' . $relation->getFullFirstLastName() . '</a><br />' . "\n";
														}
														else
														{
															echo '<strong>' . $connections->options->getConnectionRelation($value) . ':</strong> ' . $relation->getFullFirstLastName() . '<br />' . "\n";
														}
													}
													
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
											
											if ($entry->getSocialMedia())
											{
												$socialMediaObject = new cnSocialMedia();
												
												foreach ($entry->getSocialMedia() as $socialMediaRow)
												{
													if ($socialMediaObject->getId($socialMediaRow) != "") echo "<strong>" . $socialMediaObject->getName($socialMediaRow) . ":</strong><br />" . $socialMediaObject->getId($socialMediaRow) . "<br /><br />";
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
										echo '<td>
											<strong>Entry ID:</strong> ' . $entry->getId() . '<br />' . '
											<strong>Date Added:</strong> ' . $entry->getDateAdded() . '<br />
											<strong>Added By:</strong> ' . $entry->getAddedBy() . '<br />';
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
			else
			{
				$connections->setErrorMessage('capability_view_entry_list');
			}
			
		break;
	}
}
?>