<?php

/**
 * Create custom HTML forms.
 */
class cnFormObjects
{
	/**
	 * @todo
	 * Finish adding form tag attributes.
	 */
	/**
	 * @return string HTML form open tag
	 * @param array $attr
	 */
	 public function open($attr)
	{
		if ($attr['name'] != null) $name = 'name="' . $attr['name'] . '" ';
		if ($attr['action'] != null) $action = 'action="' . $attr['action'] . '" ';
		if ($attr['accept'] != null) $accept = 'accept="' . $attr['accept'] . '" ';
		if ($attr['accept-charset'] != null) $acceptcharset = 'accept-charset="' . $attr['accept-charset'] . '" ';
		if ($attr['enctype'] != null) $enctype = 'enctype="' . $attr['enctype'] . '" ';
		if ($attr['method'] != null) $method = 'method="' . $attr['method'] . '" ';
				
		return '<form ' . $action . $method . $enctype . '>';
	}
	
	/**
	 * @return string HTML close tag
	 */
	public function close()
	{
		return '</form>';
	}
	
	//Function inspired from:
	//http://www.melbournechapter.net/wordpress/programming-languages/php/cman/2006/06/16/php-form-input-and-cross-site-attacks/
	/**
	 * Adds a random token and timestamp to the $_SESSION variable
	 * 
	 * @return array
	 * @param string $formId The form ID
	 * 
	 * @return string Random generated token string
	 */
	public function token($formId)
	{
		$token = md5(uniqid(rand(), true));
		@session_start();
		$_SESSION['cn_session']['formTokens'][$formId]['token'] = $token;
		$_SESSION['cn_session']['formTokens'][$formId]['token_timestamp'] = time();
		
		return $token;
	}
	
	public function tokenCheck($tokenID, $token)
	{
		global $connections;
		$token = attribute_escape($token);
		
		/**
		 * @TODO: Check for $tokenID.
		 */
		
		if (isset($_SESSION['cn_session']['formTokens'][$tokenID]['token']))
		{
			$sessionToken = attribute_escape($_SESSION['cn_session']['formTokens'][$tokenID]['token']);
		}
		else
		{
			$connections->setErrorMessage('form_no_session_token');
			$error = TRUE;
		}
		
		if (empty($token))
		{
			$connections->setErrorMessage('form_no_token');
			$error = TRUE;
		}
		
		if ($sessionToken === $token && !$error)
		{
			unset($_SESSION['cn_session']['formTokens']);
			return TRUE;
		}
		else
		{
			$connections->setErrorMessage('form_token_mismatch');
		}
				
	}
	
	/**
	 * Builds an alpha index.
	 * @return string
	 */
	public function buildAlphaIndex() {
		$alphaindex = range("A","Z");
		
		foreach ($alphaindex as $letter) {
			$linkindex .= '<a href="#' . $letter . '">' . $letter . '</a> ';
		}
		
		return $linkindex;
	}
	
	/**
	 * Builds a form select list
	 * @return HTML form select
	 * @param string $name
	 * @param array $value_options Associative array where the key is the name visible in the HTML output and the value is the option attribute value
	 * @param string $selected[optional]
	 */
	public function buildSelect($name, $value_options, $selected=null)
	{
		
		$select = "<select name='" . $name . "'> \n";
		foreach($value_options as $key=>$value)
		{
			$select .= "<option ";
			if ($value != null)
			{
				$select .= "value='" . $key . "'";
			}
			else
			{
				$select .= "value=''";
			}
			if ($selected == $key) $select .= " SELECTED";
			
			$select .= ">";
			$select .= $value;
			$select .= "</option> \n";
		}
		$select .= "</select> \n";
		
		return $select;
	}
	
	/**
	 * Builds radio groups. 
	 * @return HTML form radio group
	 * @param object $name
	 * @param object $id
	 * @param object $value_labels associative string array label name [key] and value [value]
	 * @param object $checked[optional] value to be selected by default
	 */
	public function buildRadio($name, $id, $value_labels, $checked=null)
	{
		$radio = null;
		$count = 0;
		
		foreach ($value_labels as $label => $value)
		{
			$idplus = $id . '_' . $count;
			
			if ($checked == $value) $selected = 'CHECKED';
			
			$radio .= '<label for="' . $idplus . '">';
			$radio .= '<input id="' . $idplus . '" type="radio" name="' . $name . '" value="' . $value . '" ' . $selected . ' />';
			$radio .= $label . '</label>';
			
			$selected = null;
			$idplus = null;
			$count = $count + 1;
		}
		
		return $radio;
	}
	
}

class cnEntryForm
{
	private $defaultAddressTypes	=	array
											(
												''=>'Select',
												'home'=>'Home',
												'work'=>'Work',
												'school'=>'School',
												'other'=>'Other'
											);
											
	private $defaultPhoneNumberTypes	=	array
											(
												'homephone'=>'Home Phone',
												'homefax'=>'Home Fax',
												'cellphone'=>'Cell Phone',
												'workphone'=>'Work Phone',
												'workfax'=>'Work Fax'
											);

	private $defaultPhoneNumberValues	=	array
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
	
	private $defaultEmailTypes  =   array
									(
										'personal'=>'Personal',
										'work'=>'Work'
									);
	
	private $defaultEmailValues = 	array
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
	
	private $defaultIMValues =	array
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
	
	private $defaultIMTypes  =   array
									(
										'aim'=>'AIM',
										'yahoo'=>'Yahoo IM',
										'jabber'=>'Jabber / Google Talk',
										'messenger'=>'Messenger'
									);
	
	/**
	 * Builds the input/edit entry form.
	 * @return HTML form
	 * @param object $data[optional]
	 */
	function displayForm($data = null)
	{
		//global $wpdb, $current_user;
		global $wpdb, $connections;
	
		//get_currentuserinfo();
		//$plugin_options = new cnOptions();
		
		$form = new cnFormObjects();
		$categoryObjects = new cnCategoryObjects();
		$entry = new cnEntry($data);
		$addressObject = new cnAddresses();
		$phoneNumberObject = new cnPhoneNumber();
		$emailObject = new cnEmail();
		$imObject = new cnIM();
		$socialMediaObject = new cnSocialMedia();
		$websiteObject = new cnWebsite();
		$options = unserialize($data->options);
		$date = new cnDate();
		$ticker = new cnCounter();
		
		$action = attribute_escape($_GET['action']);
		
		/**
		 * @TODO: Do something better with these statements.
		 */
		if (!$data->visibility) $defaultVisibility = 'unlisted'; else $defaultVisibility = $entry->getVisibility();
		if (!isset($options['entry']['type'])) $defaultEntryType = "individual"; else $defaultEntryType = $entry->getEntryType();
				
		//$out =
		'<div class="form-field connectionsform">	
				<span class="radio_group">' . $form->buildRadio("entry_type","entry_type",array("Individual"=>"individual","Organization"=>"organization","Connection Group"=>"connection_group"),$defaultEntryType) . '</span>
		</div>';
		
		$out = '<div id="side-info-column" class="inner-sidebar">';
			
			$out .= '<div class="postbox">';
				$out .= '<h3>Entry Type</h3>';
				$out .= '<div class="inside">';
					$out .= $form->buildRadio("entry_type","entry_type",array("Individual"=>"individual","Organization"=>"organization","Connection Group"=>"connection_group"),$defaultEntryType);
				$out .= '</div>';
			$out .= '</div>';
			
			$out .= '<div class="postbox" id="submitdiv">';
				$out .= '<h3>Publish</h3>';
				$out .= '<div class="inside">';
					$out .= '<div id="minor-publishing">';
						$out .= '<span class="radio_group">' . $form->buildRadio('visibility','vis',array('Public'=>'public','Private'=>'private','Unlisted'=>'unlisted'),$defaultVisibility) . '</span>';
						$out .= '<div class="clear"></div>';
					$out .= '</div>';
					$out .= '<div id="major-publishing-actions">';
						
						switch ($action)
						{
							case 'edit':
								$out .= '<a href="admin.php?page=connections" class="button button-warning">Cancel</a><div id="publishing-action"><input  class="button-primary" type="submit" name="update" value="Update" /></div>';
							break;
							
							case 'copy':
								$out .= '<a href="admin.php?page=connections" class="button button-warning">Cancel</a><div id="publishing-action"><input class="button-primary" type="submit" name="save" value="Add Entry" /></div>';;
							break;
							
							default:
								$out .= '<div id="publishing-action"><input class="button-primary" type="submit" name="save" value="Add Entry" /></div>';
							break;
						}
												
						$out .= '<div class="clear"></div>';
					$out .= '</div>';
				$out .= '</div>';
			$out .= '</div>';
			
			$out .= '<div id="fieldsdiv" class="postbox">';
				$out .= '<h3>Fields</h3>';
				$out .= '<div class="inside">';
					$out .= '<p><a id="add_address" class="button">Add Address</a></p>';
					$out .= '<p><a id="add_phone_number" class="button">Add Phone Number</a></p>';
					$out .= '<p><a id="add_email_address" class="button">Add Email Address</a></p>';
					$out .= '<p><a id="add_im_id" class="button">Add Messenger ID</a></p>';
					$out .= '<p><a id="add_social_media" class="button">Add Social Media ID</a></p>';
				$out .= '</div>';
			$out .= '</div>';
			
			$out .= '<div id="categorydiv" class="postbox">';
				$out .= '<h3>Categories</h3>';
				$out .= '<div class="inside">';
					$out .= '<div id="categories-all" class="tabs-panel">';
						$out .= '<ul class="categorychecklist">';
							$out .= $categoryObjects->buildCategoryRow('checklist', $connections->retrieve->categories());
						$out .= '</ul>';
					$out .= '</div>';
				$out .= '</div>';
			$out .= '</div>';
			
		$out .= '</div>';
		
		$out .= '<div id="post-body-content">';
			$out .= '<div id="connection_group" class="form-field connectionsform">';
				
					$out .= '<label for="connection_group_name">Connection Group Name:</label>';
					$out .= '<input type="text" name="connection_group_name" value="' . $entry->getGroupName() . '" />';
					$out .= '<div id="relations">';
							
						// --> Start template for Connection Group <-- \\
						$out .= '<textarea id="relation_row_base" style="display: none">';
							$out .= $this->getEntrySelect('connection_group[::FIELD::][entry_id]');
							$out .= $form->buildSelect('connection_group[::FIELD::][relation]', $connections->options->getDefaultConnectionGroupValues());
						$out .= '</textarea>';
						// --> End template for Connection Group <-- \\
						
						if ($entry->getConnectionGroup())
						{
							//$connections = $entry->getConnectionGroup();
							foreach ($entry->getConnectionGroup() as $key => $value)
							{
								$relation = new cnEntry();
								$relation->set($key);
								
								$out .= '<div id="relation_row_' . $relation->getId() . '" class="relation_row">';
									$out .= $this->getEntrySelect('connection_group[' . $relation->getId() . '][entry_id]', $key);
									$out .= $form->buildSelect('connection_group[' . $relation->getId() . '][relation]', $connections->options->getDefaultConnectionGroupValues(), $value);
									$out .= '<a href="#" id="remove_button_' . $i . '" class="button button-warning" onClick="removeRelationRow(\'#relation_row_' . $relation->getId() . '\'); return false;">Remove</a>';
								$out .= '</div>';
								
								unset($relation);
							}
						}						
						
					$out .= '</div>';
					$out .= '<p class="add"><a id="add_relation" class="button">Add Connection</a></p>';
					
					/**
					 * @TODO: Move the inline style to the stylesheet.
					 */
				$out .= '
			</div>
			
			<div class="form-field connectionsform namefield">
					<div class="">
						
						<label for="honorable_prefix">Prefix:
							<select name="honorable_prefix">
								<option>Mr.</option>
								<option>Ms.</option>
							</select>
						</label>
					
						<div style="float: left; width: 35%">
							<label for="first_name">First Name:</label>
							<input type="text" name="first_name" value="' . $entry->getFirstName() . '" />
						</div>
						
						<div style="float: left; width: 30%">
							<label for="middle_name">Middle Name:</label>
							<input type="text" name="middle_name" value="' . $entry->getMiddleName() . '" />
						</div>
					
						<div style="float: left; width: 35%">
							<label for="last_name">Last Name:</label>
							<input type="text" name="last_name" value="' . $entry->getLastName() . '" />
						</div>
					
						<label for="honorable_suffix" style="clear: both;">Suffix:
							<select name="honorable_suffix">
								<option>Jr.</option>
								<option>MD</option>
							</select>
						</label>
						
						<label for="title">Title:</label>
						<input type="text" name="title" value="' . $entry->getTitle() . '" />
					</div>
				</div>
				
				<div class="form-field connectionsform">
					<div class="organization">
						<label for="organization">Organization:</label>
						<input type="text" name="organization" value="' . $entry->getOrganization() . '" />
						
						<label for="department">Department:</label>
						<input type="text" name="department" value="' . $entry->getDepartment() . '" />
						
						<div id="contact_name">
							<div class="input inputhalfwidth">
								<label for="contact_first_name">Contact First Name:</label>
								<input type="text" name="contact_first_name" value="' . $entry->getContactFirstName() . '" />
							</div>
							<div class="input inputhalfwidth">
								<label for="contact_last_name">Contact Last Name:</label>
								<input type="text" name="contact_last_name" value="' . $entry->getContactLastName() . '" />
							</div>
							<div class="clear"></div>
						</div>
					</div>
			</div>
			
			<div class="form-field connectionsform">';
					
					if ($entry->getImageLinked()) {
						if ($entry->getImageDisplay()) $selected = "show"; else $selected = "hidden";
						
						$imgOptions = $form->buildRadio("imgOptions", "imgOptionID_", array("Display"=>"show", "Not Displayed"=>"hidden", "Remove"=>"remove"), $selected);
						$out .= "<div style='text-align:center'> <img src='" . CN_IMAGE_BASE_URL . $entry->getImageNameProfile() . "' /> <br /> <span class='radio_group'>" . $imgOptions . "</span></div> <br />"; 
					}
					
					$out .= '<div class="clear"></div>';
					$out .= "<label for='original_image'>Select Image:
					<input type='file' value='' name='original_image' size='25' /></label>
					
			</div>";
			
						
			//$out .= '<div class="form-field connectionsform addresses">';
				$out .= '<div id="addresses">';
					
					// --> Start template for Addresses <-- \\
					$out .= '<textarea id="address_row_base" style="display: none">';
						$out .= '<div class="form-field connectionsform address">';
						$out .= '<div class="address">';
							$out .= '<span class="selectbox alignright">Type: ' . $form->buildSelect('address[::FIELD::][type]',$this->defaultAddressTypes,$addressObject->getType($addressRow)) . '</span>';
							$out .= '<div class="clear"></div>';
							
								$out .= '<label for="address">Address Line 1:</label>';
								$out .= '<input type="text" name="address[::FIELD::][address_line1]" value="" />';
					
								$out .= '<label for="address">Address Line 2:</label>';
								$out .= '<input type="text" name="address[::FIELD::][address_line2]" value="" />';
					
								$out .= '<div class="input" style="width:60%">';
									$out .= '<label for="address">City:</label>';
									$out .= '<input type="text" name="address[::FIELD::][city]" value="" />';
								$out .= '</div>';
								$out .= '<div class="input" style="width:15%">';
									$out .= '<label for="address">State:</label>';
									$out .= '<input type="text" name="address[::FIELD::][state]" value="" />';
								$out .= '</div>';
								$out .= '<div class="input" style="width:25%">';
									$out .= '<label for="address">Zipcode:</label>';
									$out .= '<input type="text" name="address[::FIELD::][zipcode]" value="" />';
								$out .= '</div>';
								
								$out .= '<label for="address">Country</label>';
								$out .= '<input type="text" name="address[::FIELD::][country]" value="" />';
								
								$out .= '<input type="hidden" name="address[::FIELD::][visibility]" value="public" />';
							
								$out .= '<div class="clear"></div>';
								$out .= '<br />';
								$out .= '<a href="#" id="remove_button_::FIELD::" class="button button-warning" onClick="removeEntryRow(\'#address_row_::FIELD::\'); return false;">Remove</a>';
						$out .= '</div>';
						$out .= '</div>';
					$out .= '</textarea>';
					// --> End template for Addresses <-- \\
					
					if ($data->addresses != null)
					{
						$addressValues = $entry->getAddresses();
						$ticker->reset();
						
						if ($addressValues != null)
						{
							foreach ($addressValues as $addressRow)
							{
								$token = $form->token($entry->getId());
								$out .= '<div class="form-field connectionsform address">';
									$out .= '<div class="address_row" id="address_row_'  . $token . '">';
										$selectName = 'address['  . $token . '][type]';
									
										$out .= '<span class="selectbox alignright">Type: ' . $form->buildSelect($selectName,$this->defaultAddressTypes,$addressObject->getType($addressRow)) . '</span>';
										$out .= '<div class="clear"></div>';
										
										$out .= '<label for="address">Address Line 1:</label>';
										$out .= '<input type="text" name="address[' . $token . '][address_line1]" value="' . $addressObject->getLineOne($addressRow) . '" />';
							
										$out .= '<label for="address">Address Line 2:</label>';
										$out .= '<input type="text" name="address[' . $token . '][address_line2]" value="' . $addressObject->getLineTwo($addressRow) . '" />';
							
										$out .= '<div class="input" style="width:60%">';
											$out .= '<label for="address">City:</label>';
											$out .= '<input type="text" name="address[' . $token . '][city]" value="' . $addressObject->getCity($addressRow) . '" />';
										$out .= '</div>';
										$out .= '<div class="input" style="width:15%">';
											$out .= '<label for="address">State:</label>';
											$out .= '<input type="text" name="address[' . $token . '][state]" value="' . $addressObject->getState($addressRow) . '" />';
										$out .= '</div>';
										$out .= '<div class="input" style="width:25%">';
											$out .= '<label for="address">Zipcode:</label>';
											$out .= '<input type="text" name="address[' . $token . '][zipcode]" value="' . $addressObject->getZipCode($addressRow) . '" />';
										$out .= '</div>';
										
										$out .= '<label for="address">Country</label>';
										$out .= '<input type="text" name="address[' . $token . '][country]" value="' . $addressObject->getCountry($addressRow) . '" />';
										
										$out .= '<input type="hidden" name="address[' . $token . '][visibility]" value="' . $addressObject->getVisibility($addressRow) . '" />';
									
										$out .= '<div class="clear"></div>';
										$out .= '<br />';
										$out .= '<a href="#" id="remove_button_'. $token . '" class="button button-warning" onClick="removeEntryRow(\'#address_row_'. $token . '\'); return false;">Remove</a>';
									$out .= '</div>';
								$out .= '</div>';
								
								$ticker->step();
							}
						}
						$ticker->reset();
					}
					
				$out .= '</div>';
				//$out .= '<p class="add"><a id="add_address" class="button">Add Address</a></p>';
			//$out .= '</div>';
			
			
			//$out .= '<div class="form-field connectionsform phone_numbers">';
				$out .= '<div id="phone_numbers">';
					
					// --> Start template for Phone Numbers <-- \\
					$out .= '<textarea id="phone_number_row_base" style="display: none">';
						$out .= '<div class="form-field connectionsform phone_number">';
						$out .= $form->buildSelect('phone_numbers[::FIELD::][type]', $this->defaultPhoneNumberTypes);
						$out .= '<input type="text" name="phone_numbers[::FIELD::][number]" value="" style="width: 30%"/>';
						$out .= '<input type="hidden" name="phone_numbers[::FIELD::][visibility]" value="public" />';
						$out .= '<a href="#" id="remove_button_::FIELD::" class="button button-warning" onClick="removeEntryRow(\'#phone_number_row_::FIELD::\'); return false;">Remove</a>';
						$out .= '</div>';
					$out .= '</textarea>';
					// --> End template for Phone Numbers <-- \\
					
					if ($data->phone_numbers != null)
					{
						$phoneNumberValues = $entry->getPhoneNumbers();
						$ticker->reset();
						
						if ($phoneNumberValues != null)
						{
							foreach ($phoneNumberValues as $phoneNumberRow)
							{
								if ($phoneNumberObject->getNumber($phoneNumberRow) != null)
								{
								$token = $form->token($entry->getId());
								$out .= '<div class="form-field connectionsform phone_number">';
									$out .= '<div class="phone_number_row" id="phone_number_row_'  . $token . '">';
										$out .= $form->buildSelect('phone_numbers[' . $token . '][type]', $this->defaultPhoneNumberTypes, $phoneNumberObject->getType($phoneNumberRow));
										$out .= '<input type="text" name="phone_numbers[' . $token . '][number]" value="' . $phoneNumberObject->getNumber($phoneNumberRow) . '" style="width: 30%"/>';
										$out .= '<input type="hidden" name="phone_numbers[' . $token . '][visibility]" value="' . $phoneNumberObject->getVisibility($phoneNumberRow) . '" />';
										$out .= '<a href="#" id="remove_button_'. $token . '" class="button button-warning" onClick="removeEntryRow(\'#address_row_'. $token . '\'); return false;">Remove</a>';
									$out .= '</div>';
								$out .= '</div>';
								
								$ticker->step();
								}
							}
							$ticker->reset();
						}
					}
					
				
				$out .= '</div>';
				//$out .= '<p class="add"><a id="add_phone_number" class="button">Add Phone Number</a></p>';
			//$out .= '</div>';
			
						
			//$out .= '<div class="form-field connectionsform email">';
				$out .= '<div id="email_addresses">';
					
					// --> Start template for Email Addresses <-- \\
					$out .= '<textarea id="email_address_row_base" style="display: none">';
					$out .= '<div class="form-field connectionsform email">';
						$out .= $form->buildSelect('email[::FIELD::][type]', $this->defaultEmailTypes);
						$out .= '<input type="text" name="email[::FIELD::][address]" value="" style="width: 30%"/>';
						$out .= '<input type="hidden" name="email[::FIELD::][visibility]" value="public" />';
						$out .= '<a href="#" id="remove_button_::FIELD::" class="button button-warning" onClick="removeEntryRow(\'#email_address_row_::FIELD::\'); return false;">Remove</a>';
						$out .= '</div>';
					$out .= '</textarea>';
					// --> End template for Email Addresses <-- \\
					
					if ($data->email != null)
					{
						$emailValues = $entry->getEmailAddresses();
						$ticker->reset();
						
						if ($emailValues != null)
						{
							foreach ($emailValues as $emailRow)
							{
								if ($emailObject->getAddress($emailRow) != null)
								{
								$token = $form->token($entry->getId());
								$out .= '<div class="form-field connectionsform email">';
									$out .= '<div class="email_address_row" id="email_address_row_'  . $token . '">';
										$out .= $form->buildSelect('email[' . $token . '][type]', $this->defaultEmailTypes, $emailObject->getType($emailRow));
										$out .= '<input type="text" name="email[' . $token . '][address]" value="' . $emailObject->getAddress($emailRow) . '" style="width: 30%"/>';
										$out .= '<input type="hidden" name="email[' . $token . '][visibility]" value="' . $emailObject->getVisibility($emailRow) . '" />';
										$out .= '<a href="#" id="remove_button_'. $token . '" class="button button-warning" onClick="removeEntryRow(\'#email_address_row_'. $token . '\'); return false;">Remove</a>';
									$out .= '</div>';
								$out .= '</div>';
								
								$ticker->step();
								}
							}
							$ticker->reset();
						}
					}
					
				$out .= '</div>';
				//$out .= '<p class="add"><a id="add_email_address" class="button">Add Email Address</a></p>';
			//$out .= '</div>';
			
						
			//$out .= '<div class="form-field connectionsform im">';
				$out .= '<div id="im_ids">';
					
					// --> Start template for IM IDs <-- \\
					$out .= '<textarea id="im_row_base" style="display: none">';
						$out .= '<div class="form-field connectionsform im">';
						$out .= $form->buildSelect('im[::FIELD::][type]', $this->defaultIMTypes);
						$out .= '<input type="text" name="im[::FIELD::][id]" value="" style="width: 30%"/>';
						$out .= '<input type="hidden" name="im[::FIELD::][visibility]" value="public" />';
						$out .= '<a href="#" id="remove_button_::FIELD::" class="button button-warning" onClick="removeEntryRow(\'#im_row_::FIELD::\'); return false;">Remove</a>';
						$out .= '</div>';
					$out .= '</textarea>';
					// --> End template for IM IDs <-- \\
					
					if ($data->im != null)
					{
						$imValues = $entry->getIm();
						$ticker->reset();
						
						if ($imValues != null)
						{
							foreach ($imValues as $imRow)
							{
								if ($imObject->getId($imRow) != null)
								{
								$token = $form->token($entry->getId());
								$out .= '<div class="form-field connectionsform im">';
									$out .= '<div class="im_row" id="im_row_'  . $token . '">';
										$out .= $form->buildSelect('im[' . $token . '][type]', $this->defaultIMTypes, $imObject->getType($imRow));
										$out .= '<input type="text" name="im[' . $token . '][id]" value="' . $imObject->getId($imRow) . '" style="width: 30%"/>';
										$out .= '<input type="hidden" name="im[' . $token . '][visibility]" value="' . $imObject->getVisibility($imRow) . '" />';
										$out .= '<a href="#" id="remove_button_'. $token . '" class="button button-warning" onClick="removeEntryRow(\'#im_row_'. $token . '\'); return false;">Remove</a>';
									$out .= '</div>';
								$out .= '</div>';
								
								$ticker->step();
								}
							}
							$ticker->reset();
						}
					}
					
				$out .= '</div>';
				//$out .= '<p class="add"><a id="add_im_id" class="button">Add Messenger ID</a></p>';
			//$out .= '</div>';
			
			
			//$out .= '<div class="form-field connectionsform socialmedia">';
				$out .= '<div id="social_media">';
					
					// --> Start template for Social Media IDs <-- \\
					$out .= '<textarea id="social_media_row_base" style="display: none">';
						$out .= '<div class="form-field connectionsform socialmedia">';
						$out .= $form->buildSelect('social_media[::FIELD::][type]', $connections->options->getDefaultSocialMediaValues());
						$out .= '<input type="text" name="social_media[::FIELD::][id]" value="" style="width: 30%"/>';
						$out .= '<input type="hidden" name="social_media[::FIELD::][visibility]" value="personal"/>';
						$out .= '<a href="#" id="remove_button_::FIELD::" class="button button-warning" onClick="removeEntryRow(\'#social_media_row_::FIELD::\'); return false;">Remove</a>';
						$out .= '</div>';
					$out .= '</textarea>';
					// --> End template for Social Media IDs <-- \\
					
					if ($data->social != null)
					{
						$socialMediaValues = $entry->getSocialMedia();
						$ticker->reset();
						
						if ($socialMediaValues != null)
						{
							foreach ($socialMediaValues as $socialMediaRow)
							{
								if ($socialMediaObject->getId($socialMediaRow) != null)
								{
								$token = $form->token($entry->getId());
								$out .= '<div class="form-field connectionsform socialmedia">';
									$out .= '<div class="social_media_row" id="social_media_row_'  . $token . '">';
										$out .= $form->buildSelect('social_media[' . $token . '][type]', $connections->options->getDefaultSocialMediaValues(), $socialMediaObject->getType($socialMediaRow));
										$out .= '<input type="text" name="social_media[' . $token . '][id]" value="' . $socialMediaObject->getId($socialMediaRow) . '" style="width: 30%"/>';
										$out .= '<input type="hidden" name="social_media[' . $token . '][visibility]" value="' . $socialMediaObject->getVisibility($socialMediaRow) . '" />';
										$out .= '<a href="#" id="remove_button_'. $token . '" class="button button-warning" onClick="removeEntryRow(\'#social_media_row_'. $token . '\'); return false;">Remove</a>';
									$out .= '</div>';
								$out .= '</div>';
								
								$ticker->step();
								}
							}
							$ticker->reset();
						}
					}
				
				$out .= '</div>';
				//$out .= '<p class="add"><a id="add_social_media" class="button">Add Social Media ID</a></p>';
			//$out .= '</div>';
			
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
					<span class='selectbox'>Birthday: " . $form->buildSelect('birthday_month',$date->months,$date->getMonth($entry->getBirthday())) . "</span>
					<span class='selectbox'>" . $form->buildSelect('birthday_day',$date->days,$date->getDay($entry->getBirthday())) . "</span>
					<br />
					<span class='selectbox'>Anniversary: " . $form->buildSelect('anniversary_month',$date->months,$date->getMonth($entry->getAnniversary())) . "</span>
					<span class='selectbox'>" . $form->buildSelect('anniversary_day',$date->days,$date->getDay($entry->getAnniversary())) . "</span>
			</div>
			
			<div class='form-field connectionsform'>
					<label for='bio'>Biographical Info:</label>
					<textarea name='bio' rows='3'>" . $entry->getBio() . "</textarea>
			</div>
			
			<div class='form-field connectionsform'>
					<label for='notes'>Notes:</label>
					<textarea name='notes' rows='3'>" . $entry->getNotes() . "</textarea>
			</div>";
			
			//$out .=
				"<div class='form-field connectionsform'>	
						<span class='radio_group'>" . $form->buildRadio('visibility','vis',array('Public'=>'public','Private'=>'private','Unlisted'=>'unlisted'),$defaultVisibility) . "</span>
				</div>";
		
		$out .= '</div>';
		return $out;
	}
	
	private function getEntrySelect($name,$selected=null)
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
				$entry = new cnEntry($row);
				$out .= '<option value="' . $entry->getId() . '"';
				if ($selected == $entry->getId()) $out .= ' SELECTED';
				$out .= '>' . $entry->getFullLastFirstName() . '</option>';
			}
		$out .= '</select>';
		
		return $out;
	}
	
	public function processEntry()
	{
		$entry = new cnEntry();
		
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
		$entry->setMiddleName($_POST['middle_name']);
		$entry->setLastName($_POST['last_name']);
		$entry->setTitle($_POST['title']);
		$entry->setOrganization($_POST['organization']);
		$entry->setDepartment($_POST['department']);
		$entry->setContactFirstName($_POST['contact_first_name']);
		$entry->setContactLastName($_POST['contact_last_name']);
		$entry->setAddresses($_POST['address']);
		$entry->setPhoneNumbers($_POST['phone_numbers']);
		$entry->setEmailAddresses($_POST['email']);
		$entry->setIm($_POST['im']);
		$entry->setSocialMedia($_POST['social_media']);
		$entry->setWebsites($_POST['websites']);
		$entry->setBirthday($_POST['birthday_day'], $_POST['birthday_month']);
		$entry->setAnniversary($_POST['anniversary_day'], $_POST['anniversary_month']);
		$entry->setBio($_POST['bio']);
		$entry->setNotes($_POST['notes']);
		$entry->setVisibility($_POST['visibility']);
										
		if ($_FILES['original_image']['error'] != 4) {
			$image_proccess_results = $this->processImages();
			
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
					
					/** @TODO remove the images from the server **/
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
			unset($_SESSION['cn_session']['formTokens']);
			unset($entry);
			
			$message = '<div id="message" class="updated fade">';
				$message .= $success;
			$message .= '</div>';
		}
		else
		{
			unset($_SESSION['cn_session']['formTokens']);
			unset($entry);
			
			$message = '<div id="notice" class="error">';
				$message .= $error;
			$message .= '</div>';
		}	
		
		return $message;
	}
	
	private function processImages()
	{
		//global $current_user;
		global $connections;
		
		//$plugin_options = new cnOptions();
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
				$process_image->jpeg_quality		= 80;
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
				$process_image->jpeg_quality		= $connections->options->getImgProfileQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $connections->options->getImgProfileRatioCrop();
				$process_image->image_ratio_fill	= (bool) $connections->options->getImgProfileRatioFill();
				$process_image->image_y				= $connections->options->getImgProfileY();
				$process_image->image_x				= $connections->options->getImgProfileX();
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
				$process_image->jpeg_quality		= $connections->options->getImgEntryQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $connections->options->getImgEntryRatioCrop();
				$process_image->image_ratio_fill	= (bool) $connections->options->getImgEntryRatioFill();
				$process_image->image_y				= $connections->options->getImgEntryY();
				$process_image->image_x				= $connections->options->getImgEntryX();
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
				$process_image->jpeg_quality		= $connections->options->getImgThumbQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $connections->options->getImgThumbRatioCrop();
				$process_image->image_ratio_fill	= (bool) $connections->options->getImgThumbRatioFill();
				$process_image->image_y				= $connections->options->getImgThumbY();
				$process_image->image_x				= $connections->options->getImgThumbX();
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
	
}

class cnCategoryObjects
{
	private $rowClass = '';
		
	public function buildCategoryRow($type, $parents, $level = 0, $selected = NULL)
	{
		foreach ($parents as $child)
		{
			$category = new cnCategory($child);
			
			if ($type === 'table') $out .= $this->buildTableRowHTML($child, $level);
			if ($type === 'option') $out .= $this->buildOptionRowHTML($child, $level, $selected);
			if ($type === 'checklist') $out .= $this->buildCheckListHTML($child, $level, $selected);
			
			if (is_array($category->getChildren()))
			{
				++$level;
				if ($type === 'table') $out .= $this->buildCategoryRow('table', $category->getChildren(), $level);
				if ($type === 'option') $out .= $this->buildCategoryRow('option', $category->getChildren(), $level, $selected);
				if ($type === 'checklist') $out .= $this->buildCategoryRow('checklist', $category->getChildren(), $level, $selected);
				--$level;
			}
			
		}
		
		$level = 0;
		return $out;
	}
	
	private function buildTableRowHTML($term, $level)
	{
		$form = new cnFormObjects();
		$category = new cnCategory($term);
		$pad = str_repeat('&#8212; ', max(0, $level));
		$this->rowClass = 'alternate' == $this->rowClass ? '' : 'alternate';
		
		/*
		 * Genreate the edit & delete tokens.
		 */
		$editToken = $form->token('category_edit_' . $category->getId());
		$deleteToken = $form->token('category_delete_' . $category->getId());
		
		$out = '<tr id="cat-' . $category->getId() . '" class="' . $this->rowClass . '">';
			$out .= '<th class="check-column">';
				$out .= '<input type="checkbox" name="category[]" value="' . $category->getId() . '"/>';
			$out .= '</th>';
			$out .= '<td class="name column-name"><a class="row-title" href="admin.php?page=connections_categories&action=edit&id=' . $category->getId() . '&token=' . $editToken . '">' . $pad . $category->getName() . '</a><br />';
				$out .= '<div class="row-actions">';
					$out .= '<span class="edit"><a href="admin.php?page=connections_categories&action=edit&id=' . $category->getId() . '&token=' . $editToken . '">Edit</a> | </span>';
					$out .= '<span class="delete"><a href="admin.php?page=connections_categories&action=delete&id=' . $category->getId() . '&token=' . $deleteToken . '">Delete</a></span>';
				$out .= '</div>';
			$out .= '</td>';
			$out .= '<td class="description column-description">' . $category->getDescription() . '</td>';
			$out .= '<td class="slug column-slug">' . $category->getSlug() . '</td>';
			$out .= '<td class="posts column-posts num">' . $category->getCount() . '</td>';
		$out .= '</tr>';
		
		return $out;
	}
	
	private function buildOptionRowHTML($term, $level, $selected)
	{
		global $rowClass;
		
		$category = new cnCategory($term);
		$pad = str_repeat('&nbsp;&nbsp;&nbsp;', max(0, $level));
		if ($selected == $category->getId()) $selectString = ' SELECTED ';
		
		$out = '<option value="' . $category->getId() . '"' . $selectString . '>' . $pad . $category->getName() . '</option>';
		
		return $out;
	}
	
	private function buildCheckListHTML($term, $level, $selected)
	{
		global $rowClass;
		
		$category = new cnCategory($term);
		$pad = str_repeat('&nbsp;&nbsp;&nbsp;', max(0, $level));
		if ($selected == $category->getId()) $selectString = ' SELECTED ';
		
		//$out = '<option value="' . $category->getId() . '"' . $selectString . '>' . $pad . $category->getName() . '</option>';
		$out = '<li id="category-' . $category->getId() . '" class="category"><label class="selectit">' . $pad . '<input id="check-category-' . $category->getId() . '" type="checkbox" name="entry_category[]" value"' . $category->getId() . '"' . $selectString . '> ' . $category->getName() . '</input></label></li>';
		
		return $out;
	}
	
	public function showForm($data = NULL)
	{
		global $connections;
		$form = new cnFormObjects();
		$category = new cnCategory($data);
		$parent = new cnCategory($connections->retrieve->category($category->getParent()));
		
		$out = '<div class="form-field form-required connectionsform">';
			$out .= '<label for="cat_name">Category Name</label>';
			$out .= '<input type="text" aria-required="true" size="40" value="' . $category->getName() . '" id="category_name" name="category_name"/>';
			$out .= '<input type="hidden" value="' . $category->getID() . '" id="category_id" name="category_id"/>';
		$out .= '</div>';
		
		$out .= '<div class="form-field connectionsform">';
			$out .= '<label for="category_nicename">Category Slug</label>';
			$out .= '<input type="text" size="40" value="' . $category->getSlug() . '" id="category_slug" name="category_slug"/>';
		$out .= '</div>';
		
		$out .= '<div class="form-field connectionsform">';
			$out .= '<label for="category_parent">Category Parent</label>';
			$out .= '<select class="postform" id="category_parent" name="category_parent">';
				$out .= '<option value="0">None</option>';
				$out .= $this->buildCategoryRow('option', $connections->retrieve->categories(), $level, $parent->getID());
			$out .= '</select>';
		$out .= '</div>';
		
		$out .= '<div class="form-field connectionsform">';
			$out .= '<label for="category_description">Description</label>';
			$out .= '<textarea cols="40" rows="5" id="category_description" name="category_description">' . $category->getDescription() . '</textarea>';
		$out .= '</div>';
		
		$out .= '<input type="hidden" name="form_id" value="category_form" />';
		$out .= '<input type="hidden" name="token" value="' . $form->token("category_form") . '" />';
		
		echo $out;
	}
}

?>