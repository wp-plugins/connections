<?php

class cnOutput extends cnEntry
{
	public function getCardImage()
	{
		if ( $this->getImageLinked() && $this->getImageDisplay() )
		{
			if ( is_file(CN_IMAGE_PATH . $this->getImageNameCard()) ) echo '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameCard() . '" />';
		}
	}
	
	public function getProfileImage()
	{
		if ( $this->getImageLinked() && $this->getImageDisplay() )
		{
			if ( is_file(CN_IMAGE_PATH . $this->getImageNameProfile()) ) echo '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameProfile() . '" />';
		}
	}
	
	public function getThumbnailImage()
	{
		if ( $this->getImageLinked() && $this->getImageDisplay())
		{
			if ( is_file(CN_IMAGE_PATH . $this->getImageNameThumbnail()) ) echo '<img class="photo" alt="Photo of ' . $this->getFirstName() . ' ' . $this->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $this->getImageNameThumbnail() . '" />';
		}
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
			foreach ($this->getAddresses() as $address)
			{
				$out .= '<div class="adr" style="margin-bottom: 10px;">' . "\n";
					if ($address->name != NULL || $address->type != NULL) $out .= '<span class="address_name"><strong>' . $address->name . '</strong></span><br />' . "\n"; //The OR is for compatiblity for 0.2.24 and under
					if ($address->line_one != NULL) $out .= '<div class="street-address">' . $address->line_one . '</div>' . "\n";
					if ($address->line_two != NULL) $out .= '<div class="extended-address">' . $address->line_two . '</div>' . "\n";
					if ($address->city != NULL) $out .= '<span class="locality">' . $address->city . '</span>&nbsp;' . "\n";
					if ($address->state != NULL) $out .= '<span class="region">' . $address->state . '</span>&nbsp;' . "\n";
					if ($address->zipcode != NULL) $out .= '<span class="postal-code">' . $address->zipcode . '</span><br />' . "\n";
					if ($address->country != NULL) $out .= '<span class="country-name">' . $address->country . '</span>' . "\n";
					$out .= $this->gethCardAdrType($address->type);
				$out .= '</div>' . "\n\n";
															
			}
		}
		return $out;
	}
	
	public function getPhoneNumberBlock()
	{
		if ($this->getPhoneNumbers())
		{
			$out .= '<div class="phone-number-block" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getPhoneNumbers() as $phone) 
			{
				//Type for hCard compatibility. Hidden.
				if ($phone->number != null) $out .=  '<strong>' . $phone->name . '</strong>: <span class="tel">' . $this->gethCardTelType($phone->type) . '<span class="value">' .  $phone->number . '</span></span><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function gethCardTelType($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data)
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
				$type = $data;
			break;
		}
		
		return $type;
    }
	
	public function gethCardAdrType($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data)
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
			$out .= '<div class="email-address-block">' . "\n";
			foreach ($this->getEmailAddresses() as $emailRow)
			{
				//Type for hCard compatibility. Hidden.
				if ($emailRow->address != NULL) $out .= '<strong>' . $emailRow->name . ':</strong><br /><span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailRow->address . '">' . $emailRow->address . '</a></span><br /><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function getImBlock()
	{
		if ($this->getIm())
		{
			/**
			 * @TODO: Out as clickable links using hCard spec.
			 */
			$out = '<div class="im-block" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getIm() as $imRow)
			{
				if ($imRow->id != NULL) $out .= '<span class="im-item"><strong>' . $imRow->name . ':</strong> ' . $imRow->id . '</span><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		return $out;
	}
	
	public function getSocialMediaBlock()
	{
		if ($this->getSocialMedia())
		{
			$out = '<div class="social-media-block" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getSocialMedia() as $socialNetwork)
			{
				if ($socialNetwork->id != null) $out .= '<span class="social-media-item"><a class="url uid ' . $socialNetwork->type . '" href="' . $socialNetwork->id . '" target="_blank">' . $socialNetwork->name . '</a></span><br />' . "\n";
			}
			$out .= '</div>' . "\n";
		}
		echo $out;
	}
	
	public function getWebsiteBlock()
	{
		if ($this->getWebsites())
		{
			$out = '<div class="website-block" style="margin-bottom: 10px;">' . "\n";
			foreach ($this->getWebsites() as $website)
			{
				if ($website->url != NULL) $out .= '<span class="website-address" style="display: block"><strong>Website:</strong> <a class="url" href="' . $website->url . '">' . $website->url . '</a></span>' . "\n";
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
	
	public function getBioBlock()
	{
		return '<div class="bio">' . $this->getBio() . '</div>' . "\n";
	}
	
	/**
	 * Displays the category list in a HTML list or custom format
	 * 
	 * @TODO: Implement $parents.
	 * 
	 * @param string $separator [optional] Default is an empty string. Separator for between the categories.
	 * @param string $parents [optional] How to display the parent categories.
	 * @return string
	 */
	public function getCategoryBlock($separator = '', $parents = FALSE)
	{
		$categories = $this->getCategory();
		
		if ( empty($categories) ) return NULL;
		
		if ($separator == '')
		{
			$out = '<ul class="entry_categories">';
			
			foreach ($categories as $category)
			{
				$out .= '<li>' . $category->name . '</li>';
			}
			
			$out .= "</ul>";
		}
		else
		{
			foreach ($categories as $category)
			{
				$out .= $category->name;
				
				$i++;
				if ( count($categories) > $i ) $out .= $separator;
			}
			
			unset($i);
		}
		
		echo $out;
		
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