<?php

class output extends entry
{
	public function getCardImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameCard() . '" />';
		return $out;
	}
	
	public function getProfileImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameProfile() . '" />';
		return $out;
	}
	
	public function getThumbnailImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameThumbnail() . '" />';
		return $out;
	}
	    
    /**
     * The entries full name. If entry type is set to organization the method will return the organization name.
     * Returns $fullFirstLastName.
     * @see entry::$fullFirstLastName
     */
    public function getFullFirstLastName()
    {
        if ($this->getEntryType() != "organization")
		{
			$fullFirstLastName = '<span class="fn n">' . '<span class="given-name">' . $this->getFirstName() . '</span>' . ' ' . '<span class="family-name">' . $this->getLastName() . '</span></span>';
			return $fullFirstLastName;
		} else {
			$organization = '<span class="fn org">' . $this->getOrganization() . '</span>';
			return $organization;
		}
		
    }
        
    /**
     * The entries full name; last name first. If entry type is set to organization the method will return the organization name.
     * Returns $fullLastFirstName.
     * @see entry::$fullLastFirstName
     */
    public function getFullLastFirstName()
    {
    	if ($this->getEntryType() != "organization")
		{
			$fullLastFirstName = '<span class="fn n">' . '<span class="family-name">' . $this->getLastName() . '</span>' . ', ' . '<span class="given-name">' . $this->getFirstName() . '</span></span>';
			return $fullLastFirstName;
		} else {
			$organization = '<span class="fn org">' . $this->getOrganization() . '</span>';
			return $organization;
		}
    }
	
	public function getTitleBlock()
	{
		if ($this->getTitle()) return '<span class="title">' . $this->getTitle() . '</span>';
	}
	
	public function getOrganizationBlock()
	{
		if ($this->getOrganization() && $this->getEntryType() != 'organization') return '<span class="organization-name">' . $this->getOrganization() . '</span>';
	}
	
	public function getDepartmentBlock()
	{
		if ($this->getDepartment()) return '<span class="organization-unit">' . $this->getDepartment() . '</span>';
	}
	
	public function getAddressBlock()
	{
		if ($this->getAddresses())
		{
			$addressObject = new addresses;
			foreach ($this->getAddresses() as $addressRow)
			{
				$out .= '<div class="adr" style="margin-bottom: 10px;">';
					if ($addressObject->getName($addressRow) != null || $addressObject->getType($addressRow)) $out .= '<span class="address_name"><strong>' . $addressObject->getName($addressRow) . '</strong></span><br />'; //The OR is for compatiblity for 0.2.24 and under
					if ($addressObject->getLineOne($addressRow) != null) $out .= '<div class="street-address">' . $addressObject->getLineOne($addressRow) . '</div>';
					if ($addressObject->getLineTwo($addressRow) != null) $out .= '<div class="extended-address">' . $addressObject->getLineTwo($addressRow) . '</div>';
					if ($addressObject->getCity($addressRow) != null) $out .= '<span class="locality">' . $addressObject->getCity($addressRow) . '</span>&nbsp;';
					if ($addressObject->getState($addressRow) != null) $out .= '<span class="region">' . $addressObject->getState($addressRow) . '</span>&nbsp;';
					if ($addressObject->getZipCode($addressRow) != null) $out .= '<span class="postal-code">' . $addressObject->getZipCode($addressRow) . '</span><br />';
					if ($addressObject->getCountry($addressRow) != null) $out .= '<span class="country-name">' . $addressObject->getCountry($addressRow) . '</span>';
					$out .= '<div class="type" style="display: none;">' . $addressObject->getType($addressRow) . '</div>'; //Type for hCard compatibility. Hidden.
				$out .= '</div>';
															
			}
		}
		return $out;
	}
	
	public function getPhoneNumberBlock()
	{
		if ($this->getPhoneNumbers())
		{
			$phoneNumberObject = new phoneNumber;
			$out .= '<div class="phone_numbers" style="margin-bottom: 10px;">';
			foreach ($this->getPhoneNumbers() as $phoneNumberRow) 
			{
				//Type for hCard compatibility. Hidden.
				if ($phoneNumberObject->getNumber($phoneNumberRow) != null) $out .=  '<strong>' . $phoneNumberObject->getName($phoneNumberRow) . '</strong>: <span class="tel"><span class="type" style="display: none;">' . $phoneNumberObject->getType($phoneNumberRow) . '</span><span class="value">' .  $phoneNumberObject->getNumber($phoneNumberRow) . '</span></span><br />';
			}
			$out .= '</div>';
		}
		return $out;
	}
	
	public function getEmailAddressBlock()
	{
		if ($this->getEmailAddresses())
		{
			$emailAddressObject = new email;
			$out .= '<div class="email-addresses">';
			foreach ($this->getEmailAddresses() as $emailRow)
			{
				//Type for hCard compatibility. Hidden.
				if ($emailAddressObject->getAddress($emailRow) != null) $out .= '<strong>' . $emailAddressObject->getName($emailRow) . ':</strong><br /><span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailAddressObject->getAddress($emailRow) . '">' . $emailAddressObject->getAddress($emailRow) . '</a></span><br /><br />';
			}
			$out .= '</div>';
		}
		return $out;
	}
	
	public function getImBlock()
	{
		if ($this->getIm())
		{
			$imObject = new im;
			$out .= '<div class="im" style="margin-bottom: 10px;">';
			foreach ($this->getIm() as $imRow)
			{
				if ($imObject->getId($imRow) != null) $out .= '<strong>' . $imObject->getName($imRow) . ':</strong> ' . $imObject->getId($imRow). '</a><br />';
			}
			$out .= '</div>';
		}
		return $out;
	}
	
	public function getWebsiteBlock()
	{
		$websiteObject = new website;
		if ($this->getWebsites())
		{
			$out .= '<div class="websites" style="margin-bottom: 10px;">';
			foreach ($this->getWebsites() as $websiteRow)
			{
				if ($websiteObject->getAddress($websiteRow) != null) $out .= '<strong>Website:</strong> <a class="url" href="' . $websiteObject->getAddress($websiteRow) . '">' . $websiteObject->getAddress($websiteRow) . '</a>';
			}
			$out .= "</div>";
		}
		return $out;
	}
	
	public function getBirthdayBlock($format=null)
	{
		//NOTE: The second birthday span [hidden] is for hCard compatibility.
		if ($this->getBirthday()) $out = '<span class="birthday"><strong>Birthday:</strong> ' . $this->getBirthday($format) . '</span><span class="bday" style="display:none">' . $this->getBirthday('Y-m-d') . '</span><br />';
		return $out;
	}
	
	public function getAnniversaryBlock($format=null)
	{
		if ($this->getAnniversary()) $out = '<span class="anniversary"><strong>Anniversary:</strong> ' . $this->getAnniversary($format) . '</span><br />';
		return $out;
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
		return '<a href="#connections-list-head" title="Return to top."><img src="' . WP_PLUGIN_URL . '/connections/images/uparrow.gif" /></a>';
	}
	
}

?>