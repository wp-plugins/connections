<?php

/**
 * Create custom HTML forms.
 */
class formObjects
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
	 * @return array
	 * @param string $formId The form ID
	 */
	public function token($formId)
	{
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

class entryForm
{
	private $defaultAddressTypes	=	array
											(
												''=>'Select',
												'home'=>'Home',
												'work'=>'Work',
												'school'=>'School',
												'other'=>'Other'
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
	
	private $defaultConnectionGroupValues = array(
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
	
	/**
	 * Builds the input/edit entry form.
	 * @return HTML form
	 * @param object $data[optional]
	 */
	function entryForm($data = null)
	{
		$form = new formObjects();
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
				<span class="radio_group">' . $form->buildRadio("entry_type","entry_type",array("Individual"=>"individual","Organization"=>"organization","Connection Group"=>"connection_group"),$defaultEntryType) . '</span>
		</div>
		
		<div id="connection_group" class="form-field connectionsform">
			
				<label for="connection_group_name">Connection Group Name:</label>
				<input type="text" name="connection_group_name" value="' . $entry->getGroupName() . '" />';
				$out .= '<div id="relations">';
						
					// --> Start template for Connection Group <-- \\
					$out .= '<textarea id="relation_row_base" style="display: none">';
						$out .= _connections_get_entry_select('connection_group[::FIELD::][entry_id]');
						$out .= $form->buildSelect('connection_group[::FIELD::][relation]', $this->defaultConnectionGroupValues);
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
								$out .= $form->buildSelect('connection_group[' . $relation->getId() . '][relation]', $defaultConnectionGroupValues, $value);
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
					
					$imgOptions = $form->buildRadio("imgOptions", "imgOptionID_", array("Display"=>"show", "Not Displayed"=>"hidden", "Remove"=>"remove"), $selected);
					$out .= "<div style='text-align:center'> <img src='" . CN_IMAGE_BASE_URL . $entry->getImageNameProfile() . "' /> <br /> <span class='radio_group'>" . $imgOptions . "</span></div> <br />"; 
				}
				
				$out .= '<div class="clear"></div>';
				$out .= "<label for='original_image'>Select Image:
				<input type='file' value='' name='original_image' size='25' /></label>
				
		</div>";
		
		if ($data->addresses != null) $addressValues = $entry->getAddresses(); else $addressValues = array(array('null'),array('null')); //The empty null is just to make the address section build twice untul jQuery form building can be implemented
		$ticker->reset();
		foreach ($addressValues as $addressRow)
		{
			$selectName = "address["  . $ticker->getcount() . "][type]";
			$out .= "<div class='form-field connectionsform'>";
				$out .= "<span class='selectbox alignright'>Type: " . $form->buildSelect($selectName,$this->defaultAddressTypes,$addressObject->getType($addressRow)) . "</span>";
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
		
		if ($data->phone_numbers != null) $phoneNumberValues = $entry->getPhoneNumbers(); else $phoneNumberValues = $this->defaultPhoneNumberValues;
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


		if ($data->email != null) $emailValues = $entry->getEmailAddresses(); else $emailValues = $this->defaultEmailValues;
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


		if ($data->im != null) $imValues = $entry->getIm(); else $imValues = $this->defaultIMValues;
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
		</div>
		
		<div class='form-field connectionsform'>	
				<span class='radio_group'>" . $form->buildRadio('visibility','vis',array('Public'=>'public','Private'=>'private','Unlisted'=>'unlisted'),$defaultVisibility) . "</span>
		</div>";
		return $out;
	}
	
	public function processEntry()
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
			unset($_SESSION['connections']['formTokens']);
			unset($entry);
			
			$message = '<div id="message" class="updated fade">';
				$message .= $success;
			$message .= '</div>';
		}
		else
		{
			unset($_SESSION['connections']['formTokens']);
			unset($entry);
			
			$message = '<div id="notice" class="error">';
				$message .= $error;
			$message .= '</div>';
		}	
		
		return $message;
	}
	
	private function processImages()
	{
		global $current_user;
		
		$plugin_options = new pluginOptions($current_user->ID);
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
				$process_image->jpeg_quality		= $plugin_options->getImgProfileQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $plugin_options->getImgProfileRatioCrop();
				$process_image->image_ratio_fill	= (bool) $plugin_options->getImgProfileRatioFill();
				$process_image->image_y				= $plugin_options->getImgProfileY();
				$process_image->image_x				= $plugin_options->getImgProfileX();
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
				$process_image->jpeg_quality		= $plugin_options->getImgEntryQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $plugin_options->getImgEntryRatioCrop();
				$process_image->image_ratio_fill	= (bool) $plugin_options->getImgEntryRatioFill();
				$process_image->image_y				= $plugin_options->getImgEntryY();
				$process_image->image_x				= $plugin_options->getImgEntryX();
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
				$process_image->jpeg_quality		= $plugin_options->getImgThumbQuality();
				$process_image->image_resize		= true;
				$process_image->image_ratio_crop	= (bool) $plugin_options->getImgThumbRatioCrop();
				$process_image->image_ratio_fill	= (bool) $plugin_options->getImgThumbRatioFill();
				$process_image->image_y				= $plugin_options->getImgThumbY();
				$process_image->image_x				= $plugin_options->getImgThumbX();
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

?>