<?php

/**
 * Entry class
 */
class entry
{	/**
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
	 * String: Full Nume
	 * @var string
	 */
	private $fullFirstLastName;
	
	/**
	 * String: Full Name; last name first
	 * @var
	 */
	private $fullLastFirstName;
	
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
	private $imageDisply;
	private $entryType;
	
	function __construct($data)	{
		$this->id = $data->id;
		$this->timeStamp = $data->ts;
		$this->firstName = $data->first_name;
		$this->lastName = $data->last_name;
		$this->title = $data->title;
		$this->organization = $data->organization;
		$this->department = $data->department;
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
		$this->imageDisply = $this->options['image']['display'];
		$this->entryType = $this->options['entry']['type'];
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
     * Returns $timeStamp.
     * @param string $format
     * @see entry::$timeStamp
     */
    public function getTimeStamp($format=null)
    {
        if (!$format)
		{
			$format = "m/d/Y";
		}
		
		$this->timeStamp = date($format,strtotime($this->timeStamp));
		
		return $this->timeStamp;
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
     * The entries full name. If entry type is set to organization the method will return the organization name.
     * Returns $fullFirstLastName.
     * @see entry::$fullFirstLastName
     */
    public function getFullFirstLastName()
    {
        if ($this->entryType != "organization")
		{
			$this->fullFirstLastName = $this->firstName . " " . $this->lastName;
			return $this->fullFirstLastName;
		} else {
			return $this->organization;
		}
		
    }
    
    /**
     * Sets $fullFirstLastName.
     * @param object $fullFirstLastName
     * @see entry::$fullFirstLastName
     */
    public function setFullFirstLastName($fullFirstLastName)
    {
        $this->fullFirstLastName = $fullFirstLastName;
    }
    
    /**
     * The entries full name; last name first. If entry type is set to organization the method will return the organization name.
     * Returns $fullLastFirstName.
     * @see entry::$fullLastFirstName
     */
    public function getFullLastFirstName()
    {
    	if ($this->entryType != "organization")
		{
			$this->fullLastFirstName = $this->lastName . ", " . $this->firstName;
			return $this->fullLastFirstName;
		} else {
			return $this->organization;
		}
    }
    
    /**
     * Sets $fullLastFirstName.
     * @param object $fullLastFirstName
     * @see entry::$fullLastFirstName
     */
    public function setFullLastFirstName($fullLastFirstName)
    {
        $this->fullLastFirstName = $fullLastFirstName;
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
			return date($format,$this->anniversary);
		}

    }
    
    /**
     * Sets $anniversary.
     * @param object $anniversary
     * @see entry::$anniversary
     */
    public function setAnniversary($anniversary)
    {
        $this->anniversary = $anniversary;
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
			return date($format,$this->birthday);
		}

    }
    
    /**
     * Sets $birthday.
     * @param object $birthday
     * @see entry::$birthday
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
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
        $this->entryType = $entryType;
    }
    
    /**
     * Returns $imageDisply.
     * @see entry::$imageDisply
     */
    public function getImageDisply()
    {
        return $this->imageDisply;
    }
    
    /**
     * Sets $imageDisply.
     * @param object $imageDisply
     * @see entry::$imageDisply
     */
    public function setImageDisply($imageDisply)
    {
        $this->imageDisply = $imageDisply;
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
        $this->imageLinked = $imageLinked;
    }
    
    /**
     * Returns $options.
     * @see entry::$options
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Sets $options.
     * @param object $options
     * @see entry::$options
     */
    public function setOptions($options)
    {
        $this->options = $options;
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
        $this->type = $data['type'];
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
{	/**
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