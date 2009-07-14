<?php

/**
 * Create custom HTML forms.
 */
class formObjects
{
	private $formAction;
	private $formMethod;
	private $formEncType;
	
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
	/**
	 * Builds the input/edit form.
	 * @return HTML form
	 * @param object $data[optional]
	 */
	function entryForm($data=null)
	{
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
}

?>