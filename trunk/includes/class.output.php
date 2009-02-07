<?php

class output extends entry
{
	public function getCardImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameCard() . '" />';
		return $out;
	}
	
	public function getProfileImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameProfile() . '" />';
		return $out;
	}
	
	public function getThumbnailImage()
	{
		if ($this->getImageLinked() && $this->getImageDisplay()) $out = '<img style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameThumbnail() . '" />';
		return $out;
	}
	
	public function getTitleBlock()
	{
		if ($this->getTitle()) return '<span class="title">' . $this->getTitle() . '</span>';
	}
	
	public function getOrganizationBlock()
	{
		if ($this->getOrganization() && $this->getEntryType() != 'organization') return '<span class="organization">' . $this->getOrganization() . '</span>';
	}
	
	public function getDepartmentBlock()
	{
		if ($this->getDepartment()) return '<span class="department">' . $this->getDepartment() . '</span>';
	}
	
	public function getAddressBlock()
	{
		if ($this->getAddresses())
		{
			$addressObject = new addresses;
			foreach ($this->getAddresses() as $addressRow)
			{
				$out .= '<div class="address" style="margin-bottom: 10px;">';
					if ($addressObject->getName($addressRow) != null || $addressObject->getType($addressRow)) $out .= '<span class="address_name"><strong>' . $addressObject->getName($addressRow) . '</strong></span><br />'; //The OR is for compatiblity for 0.2.24 and under
					if ($addressObject->getLineOne($addressRow) != null) $out .= '<span class="line_one">' . $addressObject->getLineOne($addressRow) . '</span><br />';
					if ($addressObject->getLineTwo($addressRow) != null) $out .= '<span class="line_two">' . $addressObject->getLineTwo($addressRow) . '</span><br />';
					if ($addressObject->getCity($addressRow) != null) $out .= '<span class="city">' . $addressObject->getCity($addressRow) . '</span>&nbsp;';
					if ($addressObject->getState($addressRow) != null) $out .= '<span class="state">' . $addressObject->getState($addressRow) . '</span>&nbsp;';
					if ($addressObject->getZipCode($addressRow) != null) $out .= '<span class="zipcode">' . $addressObject->getZipCode($addressRow) . '</span><br />';
					if ($addressObject->getCountry($addressRow) != null) $out .= '<span class="country">' . $addressObject->getCountry($addressRow) . '</span>';
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
			$out .= '<div class="phone_number" style="margin-bottom: 10px;">';
			foreach ($this->getPhoneNumbers() as $phoneNumberRow) 
			{
				if ($phoneNumberObject->getNumber($phoneNumberRow) != null) $out .=  '<strong>' . $phoneNumberObject->getName($phoneNumberRow) . '</strong>: ' .  $phoneNumberObject->getNumber($phoneNumberRow) . '<br />';
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
			$out .= '<div class="email">';
			foreach ($this->getEmailAddresses() as $emailRow)
			{
				if ($emailAddressObject->getAddress($emailRow) != null) $out .= '<strong>' . $emailAddressObject->getName($emailRow) . ':</strong><br /><a href="mailto:' . $emailAddressObject->getAddress($emailRow) . '">' . $emailAddressObject->getAddress($emailRow) . '</a><br /><br />';
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
				if ($websiteObject->getAddress($websiteRow) != null) $out .= '<strong>Website:</strong> <a href="' . $websiteObject->getAddress($websiteRow) . '">' . $websiteObject->getAddress($websiteRow) . '</a>';
			}
			$out .= "</div>";
		}
		return $out;
	}
	
	public function getBirthdayBlock($format=null)
	{
		if ($this->getBirthday()) $out = '<span class="birthday"><strong>Birthday:</strong> ' . $this->getBirthday($format) . '</span><br />';
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