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
	 * Sets up the plugin option properties. Requires the current WP user ID.
	 * @param interger $userID
	 */
	public function __construct($userID)
	{
		$this->currentUserID = $userID;
		
		$this->options = get_option('connections_options');
		$this->version = $this->options['version'];
		$this->entryType = $this->options[$this->currentUserID]['filter']['entry_type'];
		$this->visibilityType = $this->options[$this->currentUserID]['filter']['visibility_type'];
		
		$this->roleMain = $this->options['roles']['main'];
		$this->roleChangeSettings = $this->options['roles']['change_settings'];
		$this->roleViewHelp = $this->options['roles']['view_help'];
	}
	
	/**
	 * Saves the plug-in options to the database.
	 */
	public function saveOptions()
	{
		$this->options['version'] = $this->version;
		
		$this->options[$this->currentUserID]['filter']['entry_type'] = $this->entryType;
		$this->options[$this->currentUserID]['filter']['visibility_type'] = $this->visibilityType;
		
		$this->options['roles']['main'] = $this->roleMain;
		$this->options['roles']['change_settings'] = $this->roleChangeSettings;
		$this->options['roles']['view_help'] = $this->roleViewHelp;
		
		update_option('connections_options', $this->options);
	}
	
	/**
	 * Gets the WordPress roles. Key = role and value = name. 
	 * @return associative array
	 */
	public function getRoles()
	{
		global $wp_roles;
		return $wp_roles->get_names();;
	}
	
	public function hasCapability($role, $cap)
	{
		global $wp_roles;		
		$wpRoleDataArray = $wp_roles->roles;
		$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];
		$wpRole = new WP_Role($role, $wpRoleCaps);
		
		return $wpRole->has_cap($cap);
	}
	
	public function addCapability($role, $cap)
	{
		global $wp_roles;		
		$wpRoleDataArray = $wp_roles->roles;
		$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];
		$wpRole = new WP_Role($role, $wpRoleCaps);
		
		$wpRole->add_cap($cap);
	}
	
	public function removeCapability($role, $cap)
	{
		global $wp_roles;		
		$wpRoleDataArray = $wp_roles->roles;
		$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];
		$wpRole = new WP_Role($role, $wpRoleCaps);
		
		$wpRole->remove_cap($cap);
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