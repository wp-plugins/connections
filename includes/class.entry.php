<?php

/**
 * Entry class
 */
class entry
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
	
	/**
	 * String: First Name
	 * @var string
	 */
	private $firstName;
	
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
	
	function __construct($data = null)	{
		$this->id = $data->id;
		$this->timeStamp = $data->ts;
		$this->firstName = $data->first_name;
		$this->lastName = $data->last_name;
		$this->title = $data->title;
		$this->organization = $data->organization;
		$this->department = $data->department;
		$this->groupName = $data->group_name;
		$this->addresses = unserialize($data->addresses);
		$this->phoneNumbers = unserialize($data->phone_numbers);
		$this->emailAddresses = unserialize($data->email);
		$this->im = unserialize($data->im);
		$this->websites = unserialize($data->websites);
		$this->birthday = $data->birthday;
		$this->anniversary = $data->anniversary;
		$this->bio = $data->bio;
		$this->notes = $data->notes;
		$this->visibility = $data->visibility;
		
		$this->options = unserialize($data->options);
		$this->imageLinked = $this->options['image']['linked'];
		$this->imageDisplay = $this->options['image']['display'];
		$this->imageNameThumbnail =$this->options['image']['name']['thumbnail'];
		$this->imageNameCard = $this->options['image']['name']['entry'];
		$this->imageNameProfile = $this->options['image']['name']['profile'];
		$this->imageNameOriginal = $this->options['image']['name']['original'];
		$this->entryType = $this->options['entry']['type'];
		$this->connectionGroup = $this->options['connection_group'];
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
    private function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Timestamp format can be sent as a string variable.
     * Returns $timeStamp
     * @param string $format
     * @see entry::$timeStamp
     */
    public function getFormattedTimeStamp($format=null)
    {
        if (!$format)
		{
			$format = "m/d/Y";
		}
		
		$formattedTimeStamp = date($format,strtotime($this->timeStamp));
		
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
	
    /**
     * Returns the entries first name.
     * Returns $firstName.
     * @see entry::$firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
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
    
    /**
     * Returns the entries last name.
     * Returns $lastName.
     * @see entry::$lastName
     */
    public function getLastName()
    {
        return $this->lastName;
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
        /*if ($this->getEntryType() != "organization")
		{
			$fullFirstLastName = $this->getFirstName() . ' ' . $this->getLastName();
			return $fullFirstLastName;
		} else {
			return $this->getOrganization();
		}*/
		
		switch ($this->getEntryType())
		{
			case 'individual':
				return $this->getFirstName() . ' ' . $this->getLastName();
			break;
			
			case 'organization':
				return $this->getOrganization();
			break;
			
			case 'connection_group':
				return $this->getGroupName();
			break;
			
			default:
				return $this->getFirstName() . ' ' . $this->getLastName();
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
    	/*if ($this->getEntryType() != "organization")
		{
			$fullLastFirstName = $this->getLastName() . ', ' . $this->getFirstName();
			return $fullLastFirstName;
		} else {
			return $this->getOrganization();
		}*/
		
		switch ($this->getEntryType())
		{
			case 'individual':
				return $this->getLastName() . ', ' . $this->getFirstName();
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
        return $this->organization;
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
        return $this->title;
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
        return $this->department;
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

    /**
     * Returns $groupName.
     * @see entry::$groupName
     */
    public function getGroupName()
    {
        return $this->groupName;
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
        return $this->bio;
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
        return $this->notes;
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
     * Returns $websites.
     * @see entry::$websites
     */
    public function getWebsites()
    {
        return $this->websites;
    }
    
    /**
     * Sets $websites.
     * @param object $websites
     * @see entry::$websites
     */
    public function setWebsites($websites)
    {
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
    }

    /**
     * Returns $connectionGroup.
     * @see entry::$connectionGroup
     */
    public function getConnectionGroup()
    {
        return $this->connectionGroup;
    }
    
    /**
     * Sets $connectionGroup.
     * @param object $connectionGroup
     * @see entry::$connectionGroup
     */
    public function setConnectionGroup($connectionGroup)
    {
        //$connectionGroup = preg_grep('/^::[a-zA-Z0-9]*::/', $connectionGroup, PREG_GREP_INVERT);
		$this->options['connection_group'] = $connectionGroup;
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
     * @param object $imageNameOriginal
     * @see entry::$imageNameOriginal
     */
    public function setImageNameOriginal($imageNameOriginal)
    {
        $this->options['image']['name']['original'] = $imageNameOriginal;
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
	
	public function get($id)
	{
		global $wpdb;
		return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
	}
	
	public function set($id)
	{
		global $wpdb;
		$data = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
		$this->__construct($data);
	}
	
	public function update()
	{
		global$wpdb;
		
		$this->addresses = serialize($this->addresses);
		$this->phoneNumbers = serialize($this->phoneNumbers);
		$this->emailAddresses = serialize($this->emailAddresses);
		$this->im = serialize($this->im);
		$this->websites = serialize($this->websites);
		$this->setOptions();
		
		$sql = "UPDATE ".$wpdb->prefix."connections SET
			first_name    = '".$wpdb->escape($this->firstName)."',
			last_name     = '".$wpdb->escape($this->lastName)."',
			title    	  = '".$wpdb->escape($this->title)."',
			organization  = '".$wpdb->escape($this->organization)."',
			department    = '".$wpdb->escape($this->department)."',
			group_name	  = '".$wpdb->escape($this->groupName)."',
			visibility    = '".$wpdb->escape($this->visibility)."',
			birthday      = '".$wpdb->escape($this->birthday)."',
			anniversary   = '".$wpdb->escape($this->anniversary)."',
			addresses     = '".$wpdb->escape($this->addresses)."',
			phone_numbers = '".$wpdb->escape($this->phoneNumbers)."',
			email	      = '".$wpdb->escape($this->emailAddresses)."',
			im  	      = '".$wpdb->escape($this->im)."',
			websites      = '".$wpdb->escape($this->websites)."',
			options       = '".$wpdb->escape($this->options)."',
			bio           = '".$wpdb->escape($this->bio)."',
			notes         = '".$wpdb->escape($this->notes)."'
			WHERE id ='".$wpdb->escape($this->id)."'";
		
		return $wpdb->query($sql);
	}
	
	public function save()
	{
		global$wpdb;
		
		$this->addresses = serialize($this->addresses);
		$this->phoneNumbers = serialize($this->phoneNumbers);
		$this->emailAddresses = serialize($this->emailAddresses);
		$this->im = serialize($this->im);
		$this->websites = serialize($this->websites);
		$this->setOptions();
		
		$sql = "INSERT INTO ".$wpdb->prefix."connections SET
			first_name    = '".$wpdb->escape($this->firstName)."',
			last_name     = '".$wpdb->escape($this->lastName)."',
			title    	  = '".$wpdb->escape($this->title)."',
			organization  = '".$wpdb->escape($this->organization)."',
			department    = '".$wpdb->escape($this->department)."',
			group_name	  = '".$wpdb->escape($this->groupName)."',
			visibility    = '".$wpdb->escape($this->visibility)."',
			birthday      = '".$wpdb->escape($this->birthday)."',
			anniversary   = '".$wpdb->escape($this->anniversary)."',
			addresses     = '".$wpdb->escape($this->addresses)."',
			phone_numbers = '".$wpdb->escape($this->phoneNumbers)."',
			email	      = '".$wpdb->escape($this->emailAddresses)."',
			im  	      = '".$wpdb->escape($this->im)."',
			websites      = '".$wpdb->escape($this->websites)."',
			options       = '".$wpdb->escape($this->options)."',
			bio           = '".$wpdb->escape($this->bio)."',
			notes         = '".$wpdb->escape($this->notes)."'";
		
		return $wpdb->query($sql);
	}
	
	public function delete($id)
	{
		global $wpdb;
		
		$wpdb->query('DELETE FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
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
class phoneNumber 
{
	
	private $type;
	private $name;
	private $number;
	private $visibility;

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
		
		return $this->name;
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
		
		return $this->number;
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
		
		return $this->type;
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
		return $this->visibility;
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
class email
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

    /**
     * Returns $address.
     * @see email::$address
     */
    public function getAddress($data)
    {
        $this->address = $data['address'];
		return $this->address;
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
		
		return $this->name;
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
		return $this->type;
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
		return $this->visibility;
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


/**
 * Extracts a website info and options from an associative array of website addressess
 * 
 * $type
 * $name
 * $address
 * $visibility
 */
class website
{
	/**
	 * String: type -- need to define
	 * @var string
	 */
	private $type;
	
	/**
	 * String: Name
	 * @var string
	 */
	private $name;
	
	/**
	 * String: URL
	 * @var string
	 */
	private $address;
	
	/**
	 * String: public, private, unlisted
	 * @var string
	 */
	private $visibility;
	
    /**
     * Returns $address.
     * @param array $data
     * @see website::$address
     */
    public function getAddress($data)
    {
        $this->address = $data['address'];
		return $this->address;
    }
    
    /**
     * Sets the website address
     * Sets $address.
     * @param array $address
     * @see website::$address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }
    
    /**
     * Returns $type.
     * @see website::$type
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Sets the website type
     * Sets $type.
     * @param string $type
     * @see website::$type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
    
	    /**
     * Returns $name.
     * @see website::$name
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets $name.
     * @param string $name
     * @see website::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
	
    /**
     * Returns $visibility.
     * @see website::$visibility
     */
    public function getVisibility()
    {
        return $this->visibility;
    }
    
    /**
     * Set website visibility
     * Sets $visibility.
     * @param string $visibility public, private, unlisted
     * @see website::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }
	
}

class addresses
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

    /**
     * Returns $city.
     * @see addresses::$city
     */
    public function getCity($data)
    {
        $this->city = $data['city'];
		return $this->city;
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
		return $this->country;
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
		return $this->lineOne;
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
		return $this->lineTwo;
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
		
		return $this->name;
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
		return $this->state;
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
        return $this->type;
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
        return $this->visibility;
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
		return $this->zipCode;
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
class im
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
    
    /**
     * Returns $type.
     * @see im::$type
     */
    public function getType($data)
    {
        $this->type = $data['type'];
		return $this->type;
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
        $this->name = $data['name'];
		return $this->name;
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
		return $this->id;
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
		return $this->visibility;
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