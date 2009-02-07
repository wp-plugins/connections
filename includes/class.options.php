<?php

/**
 * Get and Set the plugin options
 */
class pluginOptions
{
	/**
	 * Array of options returned from WP get_option method
	 * @var array
	 */
	private $options;
	
	/**
	 * String: plugin version
	 * @var string
	 */
	private $version;
	
	/**
	 * String: holds the last set entry type for the persistant filter
	 * @var string
	 */
	private $entryType;
	
	/**
	 * String: holds the last set visibility type for the persistant filter
	 * @var string
	 */
	private $visibilityType;
	
	/**
	 * Interger: stores the current WP user ID
	 * @var interger
	 */
	private $currentUserID;

    /**
     * Returns $options.
     * @see options::$options
     */
    public function getOptions()
    {
        $this->options['version'] = $this->version;
		$this->options[$this->getCurrentUserID()]['filter']['entry_type'] = $this->entryType;
		$this->options[$this->getCurrentUserID()]['filter']['visibility_type'] = $this->visibilityType;
		return $this->options;
    }
    
    /**
     * Sets $options.
     * @param object $data
     * @param integer $userID
     * @see options::$options
     */
    public function setOptions($data, $userID)
    {
		$this->options = $data;
		$this->version = $data['version'];
		$this->entryType = $data[$userID]['filter']['entry_type'];
		$this->visibilityType = $data[$userID]['filter']['visibility_type'];
    }

    /**
     * Returns $entryType.
     * @see options::$entryType
     */
    public function getEntryType()
    {
        return $this->entryType;
    }
    
    /**
     * Sets $entryType.
     * @param object $entryType
     * @see options::$entryType
     */
    public function setEntryType($entryType)
    {
        $this->entryType = $entryType;
    }
    
    /**
     * Returns $version.
     * @see options::$version
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Sets $version.
     * @param object $version
     * @see options::$version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
    
    /**
     * Returns $visibilityType.
     * @see options::$visibilityType
     */
    public function getVisibilityType()
    {
        return $this->visibilityType;
    }
    
    /**
     * Sets $visibilityType.
     * @param object $visibilityType
     * @see options::$visibilityType
     */
    public function setVisibilityType($visibilityType)
    {
        $this->visibilityType = $visibilityType;
    }

    /**
     * Returns $currentUserID.
     * @see pluginOptions::$currentUserID
     */
    public function getCurrentUserID()
    {
        return $this->currentUserID;
    }
    
    /**
     * Sets $currentUserID.
     * @param object $currentUserID
     * @see pluginOptions::$currentUserID
     */
    public function setCurrentUserID($currentUserID)
    {
        $this->currentUserID = $currentUserID;
    }

}

?>