<?php

class cnOutput extends cnEntry
{
	public function getCardImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameCard() . '" />' . "\n";
		return $out;
	}
	
	public function getProfileImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameProfile() . '" />' . "\n";
		return $out;
	}
	
	public function getThumbnailImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameThumbnail() . '" />' . "\n";
		return $out;
	}
	    
    /**
     * The entries full name if the entry type is an individual.
     * If entry type is set to organization the method will return the organization name.
     * If entry type is set to connection group the method will return the group name.
     * Returns $fullFirstLastName.
     * @see entry::$fullFirstLastName
     */
    public function getFullFirstLastNameBlock()
    {
        switch ($this->getEntryType())
		{
			case 'individual':
				return '<span class="fn n">' . '<span class="given-name">' . $this->getFirstName() . '</span> ' . '<span class="additional-name">' . $this->getMiddleName() . '</span> ' . '<span class="family-name">' . $this->getLastName() . '</span></span>';
			break;
			
			case 'organization':
				return '<span class="fn org">' . $this->getOrganization() . '</span>';
			break;
			
			case 'connection_group':
				return '<span class="fn n"><span class="family-name">' . $this->getGroupName() . '</span></span>';
			break;
			
			default:
				return '<span class="fn n">' . '<span class="given-name">' . $this->getFirstName() . '</span> ' . '<span class="additional-name">' . $this->getMiddleName() . '</span> ' . '<span class="family-name">' . $this->getLastName() . '</span></span>';
			break;
		}
		
    }
        
    /**
     * The entries full name; last name first if the entry type is an individual.
     * If entry type is set to organization the method will return the organization name.
     * If entry type is set to connection group the method will return the group name.
     * Returns $fullLastFirstName.
     * @see entry::$fullLastFirstName
     */
    public function getFullLastFirstNameBlock()
    {
    	switch ($this->getEntryType())
		{
			case 'individual':
				return '<span class="fn n">' . '<span class="family-name">' . $this->getLastName() . '</span>' . ', ' . '<span class="given-name">' . $this->getFirstName() . '</span> <span class="additional-name">' . $this->getMiddleName() . '</span></span>';
			break;
			
			case 'organization':
				return '<span class="fn org">' . $this->getOrganization() . '</span>';
			break;
			
			case 'connection_group':
				return '<span class="fn n"><span class="family-name">' . $this->getGroupName() . '</span></span>';
			break;
			
			default:
				return '<span class="fn n">' . '<span class="family-name">' . $this->getLastName() . '</span>' . ', ' . '<span class="given-name">' . $this->getFirstName() . '</span></span>';
			break;
		}
    }
	
	public function getConnectionGroupBlock()
	{
		if ($this->getConnectionGroup())
		{
			//$plugin_options = new cnOptions();
			global $connections;
			
			foreach ($this->getConnectionGroup() as $key => $value)
			{
				$relation = new cnEntry();
				$relation->set($key);
				echo '<span><strong>' . $connections->options->getConnectionRelation($value) . ':</strong> ' . $relation->getFullFirstLastName() . '</span><br />' . "\n";
				unset($relation);
			}
		}
	}
	
	public function getTitleBlock()
	{
		if ($this->getTitle()) return '<span class="title">' . $this->getTitle() . '</span>' . "\n";
	}
	
	public function getOrgUnitBlock()
	{
		if ($this->getOrganization() || $this->getDepartment()) $out = '<div class="org">' . "\n";
			if ($this->getOrganization() && $this->getEntryType() != 'organization') $out .= '<span class="organization-name">' . $this->getOrganization() . '</span><br />' . "\n";
			if ($this->getDepartment()) $out .= '<span class="organization-unit">' . $this->getDepartment() . '</span><br />' . "\n";
		if ($this->getOrganization() || $this->getDepartment()) $out .= '</div>' . "\n";
		
		return $out;
	}
	
	public function getOrganizationBlock()
	{
		if ($this->getOrganization() && $this->getEntryType() != 'organization') return '<span class="org">' . $this->getOrganization() . '</span>' . "\n";
	}
	
	public function getDepartmentBlock()
	{
		if ($this->getDepartment()) return '<span class="org"><span class="organization-unit">' . $this->getDepartment() . '</span></span>' . "\n";
	}
	
	public function getAddressBlock()
	{
		if ($this->getAddresses())
		{
			$addressObject = new cnAddresses;
			foreach ($this->getAddresses() as $addressRow)
			{
				$out .= '<div class="adr" style="margin-bottom: 10px;">' . "\n";
					if ($addressObject->getName($addressRow) != null || $addressObject->getType($addressRow)) $out .= '<span class="address_name"><strong>' . $addressObject->getName($addressRow) . '</strong></span><br />' . "\n"; //The OR is for compatiblity for 0.2.24 and under
					if ($addressObject->getLineOne($addressRow) != null) $out .= '<div class="street-address">' . $addressObject->getLineOne($addressRow) . '</div>' . "\n";
					if ($addressObject->getLineTwo($addressRow) != null) $out .= '<div class="extended-address">' . $addressObject->getLineTwo($addressRow) . '</div>' . "\n";
					if ($addressObject->getCity($addressRow) != null) $out .= '<span class="locality">' . $addressObject->getCity($addressRow) . '</span>&nbsp;' . "\n";
					if ($addressObject->getState($addressRow) != null) $out .= '<span class="region">' . $addressObject->getState($addressRow) . '</span>&nbsp;' . "\n";
					if ($addressObject->getZipCode($addressRow) != null) $out .= '<span class="postal-code">' . $addressObject->getZipCode($addressRow) . '</span><br />' . "\n";
					if ($addressObject->getCountry($addressRow) != null) $out .= '<span class="country-name">' . $addressObject->getCountry($addressRow) . '</span>' . "\n";
					$out .= $this->gethCardAdrType($addressRow);
				$out .= '</div>' . "\n\n";
															
			}
		}
		return $out;
	}
	
	public function getPhoneNumberBlock()
	{
		if ($this->getPhoneNumbers())
		{
			$phoneNumberObject = new cnPhoneNumber();
			$out .= '<div class="phone_numbers" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getPhoneNumbers() as $phoneNumberRow) 
			{
				//Type for hCard compatibility. Hidden.
				if ($phoneNumberObject->getNumber($phoneNumberRow) != null) $out .=  '<strong>' . $phoneNumberObject->getName($phoneNumberRow) . '</strong>: <span class="tel">' . $this->gethCardTelType($phoneNumberRow) . '<span class="value">' .  $phoneNumberObject->getNumber($phoneNumberRow) . '</span></span><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function gethCardTelType($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type'])
		{
			case 'home':
				$type = '<span class="type" style="display: none;">home</span>';
				break;
			case 'homephone':
				$type = '<span class="type" style="display: none;">home</span>';
				break;
			case 'homefax':
				$type = '<span class="type" style="display: none;">home</span><span class="type" style="display: none;">fax</span>';
				break;
			case 'cell':
				$type = '<span class="type" style="display: none;">cell</span>';
				break;
			case 'cellphone':
				$type = '<span class="type" style="display: none;">cell</span>';
				break;
			case 'work':
				$type = '<span class="type" style="display: none;">work</span>';
				break;
			case 'workphone':
				$type = '<span class="type" style="display: none;">work</span>';
				break;
			case 'workfax':
				$type = '<span class="type" style="display: none;">work</span><span class="type" style="display: none;">fax</span>';
				break;
			case 'fax':
				$type = '<span class="type" style="display: none;">work</span><span class="type" style="display: none;">fax</span>';
				break;
			
			default:
				$type = $data['type'];
			break;
		}
		
		return $type;
    }
	
	public function gethCardAdrType($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type'])
		{
			case 'home':
				$type = '<span class="type" style="display: none;">home</span>';
				break;
			case 'work':
				$type = '<span class="type" style="display: none;">work</span>';
				break;
			case 'school':
				$type = '<span class="type" style="display: none;">school</span>';
				break;
			case 'other':
				$type = '<span class="type" style="display: none;">other</span>';
				break;
			
			default:
				if ($this->getEntryType() == 'individual')
				{
					$type = '<span class="type" style="display: none;">home</span>';
				}
				elseif ($this->getEntryType() == 'organization')
				{
					$type = '<span class="type" style="display: none;">work</span>';
				}
			break;
		}
		
		return $type;
    }
	
	public function getEmailAddressBlock()
	{
		if ($this->getEmailAddresses())
		{
			$emailAddressObject = new cnEmail();
			$out .= '<div class="email-addresses">' . "\n";
			foreach ($this->getEmailAddresses() as $emailRow)
			{
				//Type for hCard compatibility. Hidden.
				if ($emailAddressObject->getAddress($emailRow) != null) $out .= '<strong>' . $emailAddressObject->getName($emailRow) . ':</strong><br /><span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailAddressObject->getAddress($emailRow) . '">' . $emailAddressObject->getAddress($emailRow) . '</a></span><br /><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function getImBlock()
	{
		if ($this->getIm())
		{
			$imObject = new cnIM();
			$out .= '<div class="im" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getIm() as $imRow)
			{
				if ($imObject->getId($imRow) != null) $out .= '<strong>' . $imObject->getName($imRow) . ':</strong> ' . $imObject->getId($imRow). '<br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function getWebsiteBlock()
	{
		$websiteObject = new cnWebsite;
		if ($this->getWebsites())
		{
			$out .= '<div class="websites" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getWebsites() as $websiteRow)
			{
				if ($websiteObject->getAddress($websiteRow) != null) $out .= '<strong>Website:</strong> <a class="url" href="' . $websiteObject->getAddress($websiteRow) . '">' . $websiteObject->getAddress($websiteRow) . '</a>' . "\n";
			}
			$out .= "</div>" . "\n";
		}
		return $out;
	}
	
	public function getBirthdayBlock($format=null)
	{
		//NOTE: The vevent span is for hCalendar compatibility.
		//NOTE: The second birthday span [hidden] is for hCard compatibility.
		//NOTE: The third span series [hidden] is for hCalendar compatibility.
		if ($this->getBirthday()) $out = '<span class="vevent"><span class="birthday"><strong>Birthday:</strong> <abbr class="dtstart" title="' . $this->getBirthday('Ymd') .'">' . $this->getBirthday($format) . '</abbr></span>' .
										 '<span class="bday" style="display:none">' . $this->getBirthday('Y-m-d') . '</span>' .
										 '<span class="summary" style="display:none">Birthday - ' . $this->getFullFirstLastName() . '</span> <span class="uid" style="display:none">' . $this->getBirthday('YmdHis') . '</span> </span><br />' . "\n";
		return $out;
	}
	
	public function getAnniversaryBlock($format=null)
	{
		//NOTE: The vevent span is for hCalendar compatibility.
		if ($this->getAnniversary()) $out = '<span class="vevent"><span class="anniversary"><strong>Anniversary:</strong> <abbr class="dtstart" title="' . $this->getAnniversary('Ymd') . '">' . $this->getAnniversary($format) . '</abbr></span>' .
											'<span class="summary" style="display:none">Anniversary - ' . $this->getFullFirstLastName() . '</span> <span class="uid" style="display:none">' . $this->getAnniversary('YmdHis') . '</span> </span><br />' . "\n";
		return $out;
	}
	
	public function getNotesBlock()
	{
		return '<div class="note">' . $this->getNotes() . '</div>' . "\n";
	}
	
	public function getRevisionDateBlock()
	{
		return '<span class="rev">' . date('Y-m-d', strtotime($this->getUnixTimeStamp())) . 'T' . date('H:i:s', strtotime($this->getUnixTimeStamp())) . 'Z' . '</span>' . "\n";
	}
	
	public function getLastUpdatedStyle()
	{
		$age = (int) abs( time() - strtotime( $this->getUnixTimeStamp() ) );
		if ( $age < 657000 )	// less than one week: red
			$ageStyle = ' color:red; ';
		elseif ( $age < 1314000 )	// one-two weeks: maroon
			$ageStyle = ' color:maroon; ';
		elseif ( $age < 2628000 )	// two weeks to one month: green
			$ageStyle = ' color:green; ';
		elseif ( $age < 7884000 )	// one - three months: blue
			$ageStyle = ' color:blue; ';
		elseif ( $age < 15768000 )	// three to six months: navy
			$ageStyle = ' color:navy; ';
		elseif ( $age < 31536000 )	// six months to a year: black
			$ageStyle = ' color:black; ';
		else						// more than one year: don't show the update age
			$ageStyle = ' display:none; ';
		return $ageStyle;
	}
	
	public function returnToTopAnchor()
	{
		return '<a href="#connections-list-head" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" alt="Return to top."/></a>';
	}
	
}

?>