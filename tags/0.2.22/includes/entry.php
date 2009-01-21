<?php

/**
 * Entry classes
 */
class entry
{	/**
	 * Array of websites
	 * @var array
	 */ 
	var $websites;
	
	/**
	 * Array if instant messengers IDs
	 * @var array
	 */
	var $im;
	
	function __construct($data)	{
		$this->websites = unserialize($data->websites);
		$this->im = unserialize($data->im);
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