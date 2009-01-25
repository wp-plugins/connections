<?php

/**
 * Entry classes
 */
class entry
{	/**
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
	 * Array of websites
	 * @var array
	 */ 
	private $websites;
	
	/**
	 * Array if instant messengers IDs
	 * @var array
	 */
	private $im;
	
	private $options;
	private $imageLinked;
	private $imageDisply;
	private $entryType;
	
	function __construct($data)	{
		$this->firstName = $data->first_name;
		$this->lastName = $data->last_name;
		$this->title = $data->title;
		$this->organization = $data->organization;
		$this->department = $data->department;
		$this->websites = unserialize($data->websites);
		$this->im = unserialize($data->im);
		
		$this->options = unserialize($data->options);
		$this->imageLinked = $this->options['image']['linked'];
		$this->imageDisply = $this->options['image']['display'];
		$this->entryType = $this->options['entry']['type'];
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
     * Returns the entries full name. If entry type is set to organization the method will return the organization name.
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
     * Returns the entries full name; last name first. If entry type is set to organization the method will return the organization name.
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
 * Extracts a website address entry from an array of website addressess
 */
class website
{	/**
	 * String: type -- need to define
	 * @var
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
     * @param object $address
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
     * @param object $type
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
     * @param object $name
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
     * @param object $visibility
     * @see website::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }
	
}

/**
 * Extracts IM IDs from an array of instant messanger IDs
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
	 * @var
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
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Sets $type.
     * @param object $type
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
     * @param object $name
     * @see im::$name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
	
	/**
     * Returns $id.
     * @see im::$id
     */
    public function getId($data)
    {
        $this->id = $data['id'];
		return $this->id;
    }
    
    /**
     * Sets $id.
     * @param object $id
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
    public function getVisibility()
    {
        return $this->visibility;
    }
    
    /**
     * Sets $visibility.
     * @param object $visibility
     * @see im::$visibility
     */
    public function setVisibility($visibility)
    {
        $this->visibility = $visibility;
    }

}

?>