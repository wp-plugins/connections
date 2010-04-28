<?php

/**
 * Entry class
 */
class cnEntry
{
	/**
	 * Interger: Entry ID
	 * @var integer
	 */
	private $id;
	
	/**
	 * Unix timestamp
	 * @var unix timestamp
	 */
	private $timeStamp;
	
	private $dateAdded;
	
	/**
	 * String: First Name
	 * @var string
	 */
	private $firstName;
	
	private $middleName;
	
	/**
	 * String: Last Name
	 * @var string
	 */
	private $lastName;
	
	/**
	 * String: Title
	 * @var string
	 */
	private $title;
	
	/**
	 * String: Oranization
	 * @var string
	 */
	private $organization;
	
	/**
	 * String: Department
	 * @var string
	 */
	private $department;
	
	private $contactFirstName;
	
	private $contactLastName;
	
	/**
	 * String: Connection Group Name
	 * @var string
	 */
	private $groupName;
	
	/**
	 * Associative array of addresses
	 * @var associative array
	 */
	private $addresses;
	
	/**
	 * Associative array of phone numbers
	 * @var associative arrya
	 */
	private $phoneNumbers;
	
	/**
	 * Associative array of email addresses
	 * @var
	 */
	private $emailAddresses;
	
	/**
	 * Associative array of websites
	 * @var array
	 */ 
	private $websites;
	
	/**
	 * Associative array of instant messengers IDs
	 * @var array
	 */
	private $im;
	
	private $socialMedia;
	
	/**
	 * Unix time: Birthday.
	 * @var unix time
	 */
	private $birthday;
	
	/**
	 * Unix time: Anniversary.
	 * @var unix time
	 */
	private $anniversary;
	
	/**
	 * String: Entry notes.
	 * @var string
	 */
	private $bio;
	
	/**
	 * String: Entry biography.
	 * @var string
	 */
	private $notes;
	
	/**
	 * String: Visibilty Type; public, private, unlisted
	 * @var string
	 */
	private $visibility;
	
	private $options;
	private $imageLinked;
	private $imageDisplay;
	private $imageNameThumbnail;
	private $imageNameCard;
	private $imageNameProfile;
	private $imageNameOriginal;
	private $entryType;
	private $connectionGroup;
	
	private $addedBy;
	private $editedBy;
	
	private $format;
	
	function __construct($entry = NULL)
	{
		$this->id = $entry->id;
		$this->timeStamp = $entry->ts;
		$this->dateAdded = (integer) $entry->date_added;
		$this->firstName = $entry->first_name;
		$this->middleName = $entry->middle_name;
		$this->lastName = $entry->last_name;
		$this->title = $entry->title;
		$this->organization = $entry->organization;
		$this->contactFirstName = $entry->contact_first_name;
		$this->contactLastName = $entry->contact_last_name;
		$this->department = $entry->department;
		$this->groupName = $entry->group_name;
		$this->addresses = unserialize($entry->addresses);
		$this->phoneNumbers = unserialize($entry->phone_numbers);
		$this->emailAddresses = unserialize($entry->email);
		$this->im = unserialize($entry->im);
		$this->socialMedia = unserialize($entry->social);
		$this->websites = unserialize($entry->websites);
		$this->birthday = $entry->birthday;
		$this->anniversary = $entry->anniversary;
		$this->bio = $entry->bio;
		$this->notes = $entry->notes;
		$this->visibility = $entry->visibility;
		
		$this->options = unserialize($entry->options);
		$this->imageLinked = $this->options['image']['linked'];
		$this->imageDisplay = $this->options['image']['display'];
		$this->imageNameThumbnail =$this->options['image']['name']['thumbnail'];
		$this->imageNameCard = $this->options['image']['name']['entry'];
		$this->imageNameProfile = $this->options['image']['name']['profile'];
		$this->imageNameOriginal = $this->options['image']['name']['original'];
		$this->entryType = $this->options['entry']['type'];
		$this->connectionGroup = $this->options['connection_group'];
		
		$this->addedBy = $entry->added_by;
		$this->editedBy = $entry->edited_by;
		
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}

    /**
     * Returns $id.
     * @see entry::$id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets $id.
     * @param object $id
     * @see entry::$id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Timestamp format can be sent as a string variable.
     * Returns $timeStamp
     * @param string $format
     * @see entry::$timeStamp
     */
    public function getFormattedTimeStamp($format = NULL)
    {
        if (!$format)
		{
			$format = "m/d/Y";
		}
		
		$formattedTimeStamp = date($format, strtotime($this->timeStamp));
		
		return $formattedTimeStamp;
    }
	
	/**
     * Timestamp format can be sent as a string variable.
     * Returns $unixTimeStamp
     * @see entry::$timeStamp
     */
    public function getUnixTimeStamp()
    {
        $unixTimeStamp = $this->timeStamp;
		
		return $unixTimeStamp;
    }
		
	public function getHumanTimeDiff()
	{
		return human_time_diff(strtotime($this->getUnixTimeStamp()));
	}
	
	public function getDateAdded($format = NULL)
	{
		if ($this->dateAdded != NULL)
		{
			if (!$format)
			{
				$format = 'm/d/Y';
			}
			
			$formattedTimeStamp = date($format, $this->dateAdded);
			
			return $formattedTimeStamp;
		}
		else
		{
			return 'Unknown';
		}
	}
	
    /**
     * Returns the entries first name.
     * Returns $firstName.
     * @see entry::$firstName
     */
    public function getFirstName()
    {
		return $this->format->sanitizeString($this->firstName);
    }
    
    /**
     * Sets $firstName.
     * @param object $firstName
     * @see entry::$firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
    
	public function getMiddleName()
    {
        return $this->format->sanitizeString($this->middleName);
    }
    
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }
	
    /**
     * The last name if the entry type is an individual.
     * If entry type is set to connection group the method will return the group name.
     * Returns $lastName.
     * @see entry::$lastName
     */
    public function getLastName()
    {
        switch ($this->getEntryType())
		{
			case 'individual':
				return $this->format->sanitizeString($this->lastName);
			break;
			
			case 'organization':
				return $this->getOrganization();;
			break;
			
			case 'connection_group':
				return $this->getGroupName();
			break;
			
			default:
				return $this->format->sanitizeString($this->lastName);
			break;
		}
    }
    
    /**
     * Sets $lastName.
     * @param object $lastName
     * @see entry::$lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
    
	 /**
     * The entries full name if the entry type is an individual.
     * If entry type is set to organization the method will return the organization name.
     * If entry type is set to connection group the method will return the group name.
     * Returns $fullFirstLastName.
     * @see entry::$fullFirstLastName
     */
    public function getFullFirstLastName()
    {
        switch ($this->getEntryType())
		{
			case 'individual':
				return $this->getFirstName() . ' ' . $this->getMiddleName() . ' ' . $this->getLastName();
			break;
			
			case 'organization':
				return $this->getOrganization();
			break;
			
			case 'connection_group':
				return $this->getGroupName();
			break;
			
			default:
				return $this->getFirstName() . ' ' . $this->getMiddleName() . ' ' . $this->getLastName();
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
    public function getFullLastFirstName()
    {
    	switch ($this->getEntryType())
		{
			case 'individual':
				return $this->getLastName() . ', ' . $this->getFirstName() . ' ' . $this->getMiddleName();
			break;
			
			case 'organization':
				return $this->getOrganization();;
			break;
			
			case 'connection_group':
				return $this->getGroupName();
			break;
			
			default:
				return $this->getLastName() . ', ' . $this->getFirstName();
			break;
		}
    }
	
    /**
     * Returns the entries Organization.
     * Returns $organization.
     * @see entry::$organization
     */
    public function getOrganization()
    {
        return $this->format->sanitizeString($this->organization);
    }
    
    /**
     * Sets $organization.
     * @param object $organization
     * @see entry::$organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }
    
    /**
     * Returns the entries Title.
     * Returns $title.
     * @see entry::$title
     */
    public function getTitle()
    {
        return $this->format->sanitizeString($this->title);
    }
    
    /**
     * Sets $title.
     * @param object $title
     * @see entry::$title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * Returns the entries Department.
     * Returns $department.
     * @see entry::$department
     */
    public function getDepartment()
    {
        return $this->format->sanitizeString($this->department);
    }
    
    /**
     * Sets $department.
     * @param object $department
     * @see entry::$department
     */
    public function setDepartment($department)
    {
        $this->department = $department;
    }
	
	public function getContactFirstName()
	{
		return $this->format->sanitizeString($this->contactFirstName);
	}
	
	public function setContactFirstName($contactFirstName)
	{
		$this->contactFirstName = $contactFirstName;
	}
	
	public function getContactLastName()
	{
		return $this->format->sanitizeString($this->contactLastName);
	}
	
	public function setContactLastName($contactLastName)
	{
		$this->contactLastName = $contactLastName;
	}
	
    /**
     * Returns $groupName.
     * @see entry::$groupName
     */
    public function getGroupName()
    {
        return $this->format->sanitizeString($this->groupName);
    }
    
    /**
     * Sets $groupName.
     * @param object $groupName
     * @see entry::$groupName
     */
    public function setGroupName($groupName)
    {
        $this->groupName = $groupName;
    }

    /**
     * Returns $addresses.
     * @see entry::$addresses
     */
    public function getAddresses()
    {
        return $this->addresses;
    }
    
    /**
     * Sets $addresses.
     * @param object $addresses
     * @see entry::$addresses
     */
    public function setAddresses($addresses)
    {
        $this->addresses = $addresses;
    }

    /**
     * Returns $phoneNumbers.
     * @see entry::$phoneNumbers
     */
    public function getPhoneNumbers()
    {
        return $this->phoneNumbers;
    }
    
    /**
     * Sets $phoneNumbers.
     * @param object $phoneNumbers
     * @see entry::$phoneNumbers
     */
    public function setPhoneNumbers($phoneNumbers)
    {
        $this->phoneNumbers = $phoneNumbers;
    }

    /**
     * Returns $emailAddresses.
     * @see entry::$emailAddresses
     */
    public function getEmailAddresses()
    {
        return $this->emailAddresses;
    }
    
    /**
     * Sets $emailAddresses.
     * @param object $emailAddresses
     * @see entry::$emailAddresses
     */
    public function setEmailAddresses($emailAddresses)
    {
        $this->emailAddresses = $emailAddresses;
    }

    /**
     * Returns $im.
     * @see entry::$im
     */
    public function getIm()
    {
        return $this->im;
    }
    
    /**
     * Sets $im.
     * @param object $im
     * @see entry::$im
     */
    public function setIm($im)
    {
        $this->im = $im;
    }
	
	public function getSocialMedia()
    {
        return $this->socialMedia;
    }
    
    public function setSocialMedia($socialMedia)
    {
        $this->socialMedia = $socialMedia;
    }

    /**
     * Anniversary as unix time. Format can be sent as string.
     * @return string
     * @param string $format[optional]
     */
	public function getAnniversary($format=null)
    {
        if (!$format)
		{
			$format = "F jS";
		}
		
		if ($this->anniversary)
		{
			$currentYear = date('Y');
			
			if (date('m', $this->anniversary) <= date('m') && date('d', $this->anniversary) < date('d'))
			{
				$nextADay = strtotime($currentYear . '-' . date('m', $this->anniversary) . '-' . date('d', $this->anniversary) . '+ 1 year');
			}
			else
			{
				$nextADay = strtotime($currentYear . '-' . date('m', $this->anniversary) . '-' . date('d', $this->anniversary));
			}
			
			return date($format, $nextADay);
		}

    }
    
    /**
     * Sets $anniversary.
     * @param object $anniversary
     * @see entry::$anniversary
     */
    public function setAnniversary($day, $month)
    {
        //Create the anniversary with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
		$this->anniversary = strtotime($day . '-' . $month . '-' . '1970 00:00:00');
    }
    
    /**
     * Birthday as unix time. Format can be sent as string.
     * @return string
     * @param string $format[optional]
     */
    public function getBirthday($format=null)
    {
        if (!$format)
		{
			$format = "F jS";
		}
		
		if ($this->birthday)
		{		
			$currentYear = date('Y');
			
			if (date('m', $this->birthday) <= date('m') && date('d', $this->birthday) < date('d'))
			{
				$nextBDay = strtotime($currentYear . '-' . date('m', $this->birthday) . '-' . date('d', $this->birthday) . '+ 1 year');
			}
			else
			{
				$nextBDay = strtotime($currentYear . '-' . date('m', $this->birthday) . '-' . date('d', $this->birthday));
			}
			
			return date($format, $nextBDay);
		}

    }
    
    /**
     * Sets $birthday.
     * @param object $birthday
     * @see entry::$birthday
     */
    public function setBirthday($day, $month)
    {
        //Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
		$this->birthday = strtotime($day . '-' . $month . '-' . '1970 00:00:00');
    }

    /**
     * Returns $bio.
     * @see entry::$bio
     */
    public function getBio()
    {
		return $this->format->sanitizeString($this->bio, TRUE);
    }
    
    /**
     * Sets $bio.
     * @param object $bio
     * @see entry::$bio
     */
    public function setBio($bio)
    {
        $this->bio = $bio;
    }
    
    /**
     * Returns $notes.
     * @see entry::$notes
     */
    public function getNotes()
    {
        return $this->format->sanitizeString($this->notes, TRUE);
    }
    
    /**
     * Sets $notes.
     * @param object $notes
     * @see entry::$notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Returns $visibility.
     * @see entry::$visibility
     */
    public function getVisibility()
    {
        return $this->visibility;
    }
    
    /**
     * Sets $visibility.
     * @param object $visibility
     * @see entry::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }
	
	public function displayVisibiltyType()
	{
		return ucfirst($this->getVisibility());
	}

    /**
     * Returns array of objects.
     * 
     * Each object contains:
     * 						->name
     * 						->type
     * 						->address
     * 						->url
     * 						->visibility
     * 
     * NOTE: The output is sanitized for safe display.
     * 
     * @return array
     */
	public function getWebsites()
    {
        if ( !empty($this->websites) )
		{
			foreach ($this->websites as $key => $website)
			{
				$websiteRow->name = $this->format->sanitizeString($website['name']);
				$websiteRow->type = $this->format->sanitizeString($website['type']);
				$websiteRow->address = $this->format->sanitizeString($website['address']);
				$websiteRow->url = $this->format->sanitizeString($website['address']);
				$websiteRow->visibility = $this->format->sanitizeString($website['visibility']);
				
				$out[] = $websiteRow;
				unset($websiteRow);
			}
		}
		
		if ( !empty($out) ) return $out;
    }
    
    /**
     * Sets $websites as an associative array.
     * If the website URL [address] is http:// or empty it is unset.
     * since there is no need to store it.
     * 
     * $websites is to be an array containing an araay of the data for each website.
     * 
     * @TODO: Validate as valid web addresses.
     * 
     * @param array $websites
     */
    public function setWebsites($websites)
    {
		$validFields = array('name' => NULL, 'type' => NULL, 'address' => NULL, 'url' => NULL, 'visibility' => NULL);
		
		if ( !empty($websites) )
		{
			foreach ($websites as $key => $website)
			{
				// First validate the supplied data.
				$intersect = array_intersect_key($website, $validFields); // Get data for which is in the valid fields.
				$difference = array_diff_key($validFields, $website); // Get default data which is not supplied.
				$websites[$key] = array_merge($intersect, $difference); // Merge the results. Contains only valid fields of all defaults.
				
				// If the address/url is emty, no need to store it and if the http protocol is not part of the address, add it.
				switch ($website['address'])
				{
					case '':
						unset($websites[$key]);
					break;
					
					case 'http://':
						unset($websites[$key]);
					break;
					
					default:
						if ( substr($website['address'], 0, 7) != 'http://' )
						{
							$websites[$key]['address'] = 'http://' . $website['address'];
						}
					break;
				}
				
				if ( array_key_exists($key, $websites) ) $websites[$key]['url'] = 'http://' . $website['address'];
				
			}
		}
		
		$this->websites = $websites;
    }

    /**
     * Returns $entryType.
     * @see entry::$entryType
     */
    public function getEntryType()
    {
        return $this->entryType;
    }
    
    /**
     * Sets $entryType.
     * @param object $entryType
     * @see entry::$entryType
     */
    public function setEntryType($entryType)
    {
        $this->options['entry']['type'] = $entryType;
		$this->entryType = $entryType;
    }

    /**
     * Returns $connectionGroup.
     * @see entry::$connectionGroup
     */
    public function getConnectionGroup()
    {
        return $this->options['connection_group'];
    }
    
    /**
     * Sets $connectionGroup.
     * @param object $connectionGroup
     * @see entry::$connectionGroup
     */
    public function setConnectionGroup($connectionGroup)
    {
		/* 
		 * The form to capture the user IDs and relationship stores the data
		 * in a two-dementional array as follows:
		 * 		array[0]
		 * 			array[entry_id]
		 * 				 [relation]
		 * 
		 * This loop re-writes the data into a sine associative array entry_id => relation.
		 * That makes it easy to use a foreach as $key => $value.
		 */
		if ($connectionGroup)
		{
			foreach($connectionGroup as $connection)
			{
				$array[$connection['entry_id']] .= $connection['relation'];
			}
		}
		$this->options['connection_group'] = $array;
    }
    
    /**
     * Returns $imageDisplay.
     * @see entry::$imageDisplay
     */
    public function getImageDisplay()
    {
        return $this->imageDisplay;
    }
    
    /**
     * Sets $imageDisplay.
     * @param object $imageDisplay
     * @see entry::$imageDisplay
     */
    public function setImageDisplay($imageDisplay)
    {
        $this->options['image']['display'] = $imageDisplay;
    }
    
    /**
     * Returns $imageLinked.
     * @see entry::$imageLinked
     */
    public function getImageLinked()
    {
        return $this->imageLinked;
    }
    
    /**
     * Sets $imageLinked.
     * @param object $imageLinked
     * @see entry::$imageLinked
     */
    public function setImageLinked($imageLinked)
    {
        $this->options['image']['linked'] = $imageLinked;
    }

    /**
     * Returns $imageNameCard.
     * @see entry::$imageNameCard
     */
    public function getImageNameCard()
    {
        return $this->imageNameCard;
    }
    
    /**
     * Sets $imageNameCard.
     * @param object $imageNameCard
     * @see entry::$imageNameCard
     */
    public function setImageNameCard($imageNameCard)
    {
        $this->options['image']['name']['entry'] = $imageNameCard;
    }
    
    /**
     * Returns $imageNameProfile.
     * @see entry::$imageNameProfile
     */
    public function getImageNameProfile()
    {
        return $this->imageNameProfile;
    }
    
    /**
     * Sets $imageNameProfile.
     * @param object $imageNameProfile
     * @see entry::$imageNameProfile
     */
    public function setImageNameProfile($imageNameProfile)
    {
        $this->options['image']['name']['profile'] = $imageNameProfile;
    }
    
    /**
     * Returns $imageNameThumbnail.
     * @see entry::$imageNameThumbnail
     */
    public function getImageNameThumbnail()
    {
        return $this->imageNameThumbnail;
    }
    
    /**
     * Sets $imageNameThumbnail.
     * @param object $imageNameThumbnail
     * @see entry::$imageNameThumbnail
     */
    public function setImageNameThumbnail($imageNameThumbnail)
    {
        $this->options['image']['name']['thumbnail'] = $imageNameThumbnail;
    }

    /**
     * Returns $imageNameOriginal.
     * @see entry::$imageNameOriginal
     */
    public function getImageNameOriginal()
    {
        return $this->imageNameOriginal;
    }
    
    /**
     * Sets $imageNameOriginal.
     * 
     * @param object $imageNameOriginal
     * @see entry::$imageNameOriginal
     */
    public function setImageNameOriginal($imageNameOriginal)
    {
        $this->options['image']['name']['original'] = $imageNameOriginal;
    }
    
	/**
	 * Displays the category list in a HTML list or custom format
	 * 
	 * @param string $separator [optional] Default is an empty string. Separator for between the categories.
	 * @param string $parents [optional] How to display the parent categories.
	 * @return string
	 */
	public function getCategoryList($separator = '', $parents = '')
	{
		
	}
	
	public function getAddedBy()
	{
		$addedBy = get_userdata($this->addedBy);
		
		if (!$addedBy->display_name == NULL)
		{
			return $addedBy->display_name;
		}
		else
		{
			return 'Unknown';
		}
		
	}
	
	public function getEditedBy()
	{
		$editedBy = get_userdata($this->editedBy);
		
		if (!$editedBy->display_name == NULL)
		{
			return $editedBy->display_name;
		}
		else
		{
			return 'Unknown';
		}
		
	}
	
    /**
     * Returns $options.
     * @see entry::$options
     */
    private function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Sets $options.
     * @param object $options
     * @see entry::$options
     */
    private function setOptions()
    {
        $this->options = serialize($this->options);
    }
	
	/*public function get($id)
	{
		global $wpdb;
		return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
	}*/
	
	public function set($id)
	{
		global $connections;
		$result = $connections->retrieve->entry($id);
		$this->__construct($result);
	}
	
	public function update()
	{
		global $wpdb, $connections;
		
		$this->addresses = serialize($this->addresses);
		$this->phoneNumbers = serialize($this->phoneNumbers);
		$this->emailAddresses = serialize($this->emailAddresses);
		$this->im = serialize($this->im);
		$this->socialMedia = serialize($this->socialMedia);
		$this->websites = serialize($this->websites);
		$this->setOptions();
		
		// Ensure fields that should be empty depending on the entry type.
		switch ($this->getEntryType())
		{
			case 'individual':
				$this->groupName = '';
				$this->connectionGroup = '';
			break;
			
			case 'organization':
				$this->groupName = '';
				$this->firstName = '';
				$this->middleName = '';
				$this->lastName = '';
				$this->title = '';
				$this->connectionGroup = '';
				$this->birthday = '';
				$this->anniversary = '';
			break;
			
			case 'connection_group':
				$this->firstName = '';
				$this->middleName = '';
				$this->lastName = '';
				$this->title = '';
				$this->birthday = '';
				$this->anniversary = '';
			break;
			
			default:
				$this->entryType = 'individual';
				$this->groupName = '';
			break;
		}
		
		$wpdb->show_errors = true;
		
		return $wpdb->query($wpdb->prepare('UPDATE ' . CN_ENTRY_TABLE . ' SET
											entry_type			= "%s",
											first_name			= "%s",
											middle_name			= "%s",
											last_name			= "%s",
											title				= "%s",
											organization		= "%s",
											department			= "%s",
											contact_first_name	= "%s",
											contact_last_name	= "%s",
											group_name			= "%s",
											visibility			= "%s",
											birthday			= "%s",
											anniversary			= "%s",
											addresses			= "%s",
											phone_numbers		= "%s",
											email				= "%s",
											im					= "%s",
											social				= "%s",
											websites			= "%s",
											options				= "%s",
											bio					= "%s",
											notes				= "%s",
											edited_by			= "%d",
											status				= "%s"
											WHERE id			= "%d"',
											$this->entryType,
											$this->firstName,
											$this->middleName,
											$this->lastName,
											$this->title,
											$this->organization,
											$this->department,
											$this->contactFirstName,
											$this->contactLastName,
											$this->groupName,
											$this->visibility,
											$this->birthday,
											$this->anniversary,
											$this->addresses,
											$this->phoneNumbers,
											$this->emailAddresses,
											$this->im,
											$this->socialMedia,
											$this->websites,
											$this->options,
											$this->bio,
											$this->notes,
											$connections->currentUser->getID(),
											'approved',
											$this->id));
		$wpdb->show_errors = FALSE;
	}
	
	public function save()
	{
		global $wpdb, $connections;
		
		$this->addresses = serialize($this->addresses);
		$this->phoneNumbers = serialize($this->phoneNumbers);
		$this->emailAddresses = serialize($this->emailAddresses);
		$this->im = serialize($this->im);
		$this->socialMedia = serialize($this->socialMedia);
		$this->websites = serialize($this->websites);
		$this->setOptions();
		
		// Ensure fields that should be empty depending on the entry type.
		switch ($this->getEntryType())
		{
			case 'individual':
				$this->groupName = '';
				$this->connectionGroup = '';
			break;
			
			case 'organization':
				$this->groupName = '';
				$this->firstName = '';
				$this->middleName = '';
				$this->lastName = '';
				$this->title = '';
				$this->connectionGroup = '';
				$this->birthday = '';
				$this->anniversary = '';
			break;
			
			case 'connection_group':
				$this->firstName = '';
				$this->middleName = '';
				$this->lastName = '';
				$this->title = '';
				$this->birthday = '';
				$this->anniversary = '';
			break;
			
			default:
				$this->entryType = 'individual';
				$this->groupName = '';
			break;
		}
		
		$wpdb->show_errors = true;
		
		return $wpdb->query($wpdb->prepare('INSERT INTO ' . CN_ENTRY_TABLE . ' SET
											date_added   		= "%d",
											entry_type  		= "%s",
											visibility  		= "%s",
											group_name			= "%s",
											honorable_prefix	= "%s",
											first_name			= "%s",
											middle_name 		= "%s",
											last_name   		= "%s",
											honorable_suffix	= "%s",
											title    			= "%s",
											organization  		= "%s",
											department    		= "%s",
											contact_first_name 	= "%s",
											contact_last_name 	= "%s",
											addresses     		= "%s",
											phone_numbers 		= "%s",
											email	      		= "%s",
											im  	      		= "%s",
											social 	      		= "%s",
											websites      		= "%s",
											birthday      		= "%s",
											anniversary   		= "%s",
											bio           		= "%s",
											notes         		= "%s",
											options       		= "%s",
											added_by      		= "%d",
											edited_by     		= "%d",
											owner				= "%d",
											status	      		= "%s"',
											time(),
											$this->entryType,
											$this->visibility,
											$this->groupName,
											'',
											$this->firstName,
											$this->middleName,
											$this->lastName,
											'',
											$this->title,
											$this->organization,
											$this->department,
											$this->contactFirstName,
											$this->contactLastName,
											$this->addresses,
											$this->phoneNumbers,
											$this->emailAddresses,
											$this->im,
											$this->socialMedia,
											$this->websites,
											$this->birthday,
											$this->anniversary,
											$this->bio,
											$this->notes,
											$this->options,
											$connections->currentUser->getID(),
											'',
											$connections->currentUser->getID(),
											'approved'));
		$wpdb->show_errors = FALSE;
	}
	
	public function delete($id)
	{
		global $wpdb, $connections;
		$category = new cnCategory();
		
		$wpdb->query($wpdb->prepare('DELETE FROM ' . CN_ENTRY_TABLE . ' WHERE id="' . $wpdb->escape($id) . '"'));
		
		/**
		 * @TODO Only delete the category relationships if deleting the entry was successful
		 */
		
		$connections->term->deleteTermRelationships($id);
	}
	
}

/**
 * Extract phone number details from an associative array of phone numbers
 * 
 * $type
 * $name
 * $number
 * $visibility
 */
class cnPhoneNumber 
{
	
	private $type;
	private $name;
	private $number;
	private $visibility;
	
	private $format;
	
	function __construct()
	{
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}
	
    /**
     * Returns $name.
     * @see phoneNumber::$name
     */
    public function getName($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type'])
		{
			case 'home':
				$this->name = "Home Phone";
				break;
			case 'homephone':
				$this->name = "Home Phone";
				break;
			case 'homefax':
				$this->name = "Home Fax";
				break;
			case 'cell':
				$this->name = "Cell Phone";
				break;
			case 'cellphone':
				$this->name = "Cell Phone";
				break;
			case 'work':
				$this->name = "Work Phone";
				break;
			case 'workphone':
				$this->name = "Work Phone";
				break;
			case 'workfax':
				$this->name = "Work Fax";
				break;
			case 'fax':
				$this->name = "Work Fax";
				break;
			
			default:
				$this->name = $data['name'];
			break;
		}
		
		return $this->format->sanitizeString($this->name);
    }
    
    /**
     * Sets $name.
     * @param object $name
     * @see phoneNumber::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns $number.
     * @param array
     * @see phoneNumber::$number
     */
    public function getNumber($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		if (isset($data['homephone']))
		{
        	$this->number = $data['homephone'];
        }
		else
		{
			$this->number = $data['number'];
		}
		
		return $this->format->sanitizeString($this->number);
    }
    
    /**
     * Sets $number.
     * @param object $number
     * @see phoneNumber::$number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }
    
    /**
     * Returns $type.
     * @see phoneNumber::$type
     */
    public function getType($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type'])
		{
			case 'home':
				$this->type = "homephone";
				break;
			case 'homephone':
				$this->type = "homephone";
				break;
			case 'homefax':
				$this->type = "homefax";
				break;
			case 'cell':
				$this->type = "cellphone";
				break;
			case 'cellphone':
				$this->type = "cellphone";
				break;
			case 'work':
				$this->type = "workphone";
				break;
			case 'workphone':
				$this->type = "workphone";
				break;
			case 'workfax':
				$this->type = "workfax";
				break;
			case 'fax':
				$this->type = "workfax";
				break;
			
			default:
				$this->type = $data['type'];
			break;
		}
		
		return $this->format->sanitizeString($this->type);
    }
    
    /**
     * Sets $type.
     * @param object $type
     * @see phoneNumber::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Returns $visibility.
     * @see phoneNumber::$visibility
     */
    public function getVisibility($data)
    {
        $this->visibility = $data['visibility'];
		return $this->format->sanitizeString($this->visibility);
    }
    
    /**
     * Sets $visibility.
     * @param object $visibility
     * @see phoneNumber::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

}

/**
 * Extracts a email address and options from an associative array of email addressess
 * 
 * $type
 * $name
 * $address
 * $visibility
 */
class cnEmail
{

	/**
	 * String: -- need to define
	 * @var string
	 */
	private $type;
	
	/**
	 * String: The email address name
	 * @var string
	 */
	private $name;
	
	/**
	 * String: The email address
	 * @var string
	 */
	private $address;
	
	/**
	 * String: public, private, unlisted
	 * @var string
	 */
	private $visibility;
	
	private $format;
	
	function __construct()
	{
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}
	
    /**
     * Returns $address.
     * @see email::$address
     */
    public function getAddress($data)
    {
        $this->address = $data['address'];
		return $this->format->sanitizeString($this->address);
    }
    
    /**
     * Sets $address.
     * @param object $address
     * @see email::$address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
    
    /**
     * Returns $name.
     * @see email::$name
     */
    public function getName($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type'])
		{
			case 'personal':
				$this->name = "Personal Email";
				break;
			case 'work':
				$this->name = "Work Email";
				break;
			
			default:
				$this->name = $data['name'];
			break;
		}
		
		return $this->format->sanitizeString($this->name);
    }
    
    /**
     * Sets $name.
     * @param object $name
     * @see email::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns $type.
     * @see email::$type
     */
    public function getType($data)
    {
        $this->type = $data['type'];
		return $this->format->sanitizeString($this->type);
    }
    
    /**
     * Sets $type.
     * @param object $type
     * @see email::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Returns $visibility.
     * @see email::$visibility
     */
    public function getVisibility($data)
    {
        $this->visibility = $data['visibility'];
		return $this->format->sanitizeString($this->visibility);
    }
    
    /**
     * Sets $visibility.
     * @param object $visibility
     * @see email::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

}

class cnAddresses
{
	private $type;
	private $name;
	private $lineOne;
	private $lineTwo;
	private $city;
	private $state;
	private $zipCode;
	private $country;
	private $visibility;
	
	private $format;
	
	function __construct()
	{
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}
	
    /**
     * Returns $city.
     * @see addresses::$city
     */
    public function getCity($data)
    {
        $this->city = $data['city'];
		return $this->format->sanitizeString($this->city);
    }
    
    /**
     * Sets $city.
     * @param object $city
     * @see addresses::$city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }
    
    /**
     * Returns $country.
     * @see addresses::$country
     */
    public function getCountry($data)
    {
        $this->country = $data['country'];
		return $this->format->sanitizeString($this->country);
    }
    
    /**
     * Sets $country.
     * @param object $country
     * @see addresses::$country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
    
    /**
     * Returns $lineOne.
     * @see addresses::$lineOne
     */
    public function getLineOne($data)
    {
        $this->lineOne = $data['address_line1'];
		return $this->format->sanitizeString($this->lineOne);
    }
    
    /**
     * Sets $lineOne.
     * @param object $lineOne
     * @see addresses::$lineOne
     */
    public function setLineOne($lineOne)
    {
        $this->lineOne = $lineOne;
    }
    
    /**
     * Returns $lineTwo.
     * @see addresses::$lineTwo
     */
    public function getLineTwo($data)
    {
        $this->lineTwo = $data['address_line2'];
		return $this->format->sanitizeString($this->lineTwo);
    }
    
    /**
     * Sets $lineTwo.
     * @param object $lineTwo
     * @see addresses::$lineTwo
     */
    public function setLineTwo($lineTwo)
    {
        $this->lineTwo = $lineTwo;
    }

    /**
     * Returns $name.
     * @see addresses::$name
     */
    public function getName($data)
    {
        //This is here for compatibility for versions 0.2.24 and earlier;
		switch ($data['type']) {
        	case "home":
        		$this->name = "Home Address";
        	break;
			
			case "work":
        		$this->name = "Work Address";
        	break;
			
			case "school":
        		$this->name = "School Address";
        	break;
			
			case "other":
        		$this->name = "Other Address";
        	break;
        	
        	default:
        		$this->name = $data['name'];
        	break;
        }	
		
		return $this->format->sanitizeString($this->name);
    }
    
    /**
     * Sets $name.
     * @param object $name
     * @see addresses::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * Returns $state.
     * @see addresses::$state
     */
    public function getState($data)
    {
        $this->state = $data['state'];
		return $this->format->sanitizeString($this->state);
    }
    
    /**
     * Sets $state.
     * @param object $state
     * @see addresses::$state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
    
    /**
     * Returns $type.
     * @see addresses::$type
     */
    public function getType($data)
    {
        $this->type = $data['type'];
        return $this->format->sanitizeString($this->type);
    }
    
    /**
     * Sets $type.
     * @param object $type
     * @see addresses::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
    /**
     * Returns $visibility.
     * @see addresses::$visibility
     */
    public function getVisibility()
    {
        return $this->format->sanitizeString($this->visibility);
    }
    
    /**
     * Sets $visibility.
     * @param object $visibility
     * @see addresses::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }
    
    /**
     * Returns $zipCode.
     * @see addresses::$zipCode
     */
    public function getZipCode($data)
    {
        $this->zipCode = $data['zipcode'];
		return $this->format->sanitizeString($this->zipCode);
    }
    
    /**
     * Sets $zipCode.
     * @param object $zipCode
     * @see addresses::$zipCode
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
    }

}

/**
 * Extracts IM IDs from an array of instant messanger IDs
 * 
 * $type
 * $name
 * $id
 * $visibility
 */
class cnIM
{
	/**
	 * String: IM protocal
	 * @var string
	 */
	private $type;
	
	/**
	 * String: Name
	 * @var string
	 */
	private $name;
	
	/**
	 * IM ID
	 * @var string
	 */
	private $id;
	
	/**
	 * String: public, private, unlisted
	 * @var string
	 */
	private $visibility;
    
    private $format;
	
	function __construct()
	{
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}
	
	/**
     * Returns $type.
     * @see im::$type
     */
    public function getType($data)
    {
       $this->type = $data['name'];
	   
	   // Switch is to maintain compatibility with versions 0.5.48 and older
	   switch ($this->type)
		{
			case 'AIM':
				return 'aim';
			break;
			
			case 'Yahoo IM':
				return 'yahoo';
			break;
			
			case 'Jabber / Google Talk':
				return 'jabber';
			break;
			
			case 'Messenger':
				return 'messenger';
			break;
			
			default:
				$this->type = $data['type'];
				return $this->format->sanitizeString($this->type);
			break;
		}
		
		//$this->type = $data['type'];
		//return $this->type;
    }
    
    /**
     * Sets $type.
     * @param string $type
     * @see im::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns $name.
     * @see im::$name
     */
    public function getName($data)
    {
       $this->type = $data['type'];
	   
	   // Switch is to maintain compatibility with versions 0.5.48 and older
	   switch ($this->type)
		{
			case 'aim':
				return 'AIM';
			break;
			
			case 'yahoo':
				return 'Yahoo IM';
			break;
			
			case 'jabber':
				return 'Jabber / Google Talk';
			break;
			
			case 'messenger':
				return 'Messenger';
			break;
			
			default:
				$this->name = $data['name'];
				return $this->format->sanitizeString($this->name);
			break;
		}
	   
	    //$this->name = $data['name'];
		//return $this->name;
    }
    
    /**
     * Sets $name.
     * @param string $name
     * @see im::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
	
	/**
     * Returns $id.
     * @param $data 
     * @see im::$id
     */
    public function getId($data)
    {
        $this->id = $data['id'];
		return $this->format->sanitizeString($this->id);
    }
    
    /**
     * Sets $id.
     * @param string $id
     * @see im::$id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Returns $visibility.
     * @see im::$visibility
     */
    public function getVisibility($data)
    {
        $this->visibility = $data['visibility'];
		return $this->format->sanitizeString($this->visibility);
    }
    
    /**
     * Sets $visibility.
     * @param string $visibility public, private, unlisted
     * @see im::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

}

/**
 * Extracts Social MEdia IDs from an array of IDs
 * 
 * $type
 * $name
 * $id
 * $visibility
 */
class cnSocialMedia
{
	/**
	 * String: IM protocal
	 * @var string
	 */
	private $type;
		
	/**
	 * IM ID
	 * @var string
	 */
	private $id;
	
	/**
	 * String: public, private, unlisted
	 * @var string
	 */
	private $visibility;
    
    private $format;
	
	function __construct()
	{
		// Load the formatting class for sanitizing the get methods.
		$this->format = new cnFormatting();
	}
	
	/**
     * Returns $type.
     * @see im::$type
     */
    public function getType($data)
    {
       $this->type = $data['type'];
		return $this->format->sanitizeString($this->type);
    }
    
	public function getName($data)
	{
		global $connections;
		
		$socialMediaValues = $connections->options->getDefaultSocialMediaValues();
		return $this->format->sanitizeString($socialMediaValues[$data['type']]);
	}
	
    /**
     * Sets $type.
     * @param string $type
     * @see im::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

	/**
     * Returns $id.
     * @param $data 
     * @see im::$id
     */
    public function getId($data)
    {
        $this->id = $data['id'];
		return $this->format->sanitizeString($this->id);
		
    }
    
    /**
     * Sets $id.
     * @param string $id
     * @see im::$id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    
    /**
     * Returns $visibility.
     * @see im::$visibility
     */
    public function getVisibility($data)
    {
        $this->visibility = $data['visibility'];
		return $this->format->sanitizeString($this->visibility);
    }
    
    /**
     * Sets $visibility.
     * @param string $visibility public, private, unlisted
     * @see im::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

}
?>