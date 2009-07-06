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
	
	private $defaultCapabilities = array(
								'connections_access' => 'Access Connections',
								'connections_view_entry_list' => 'View Entry List',
								'connections_add_entry' => 'Add Entry',
								'connections_edit_entry' => 'Edit Entry',
								'connections_delete_entry' => 'Delete Entry',
								'connections_view_public' => 'View Public Entries',
								'connections_view_private' => 'View Private Entries',
								'connections_view_unlisted' => 'View Unlisted Entries',
								'connections_change_settings' => 'Change Settings',
								'connections_change_roles' => 'Change Role Capabilities',
								'connections_view_help' => 'View Help'
							);
	
	private $allowPublic;
	
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
		
		$this->allowPublic = $this->options['settings']['allow_public'];
	}
	
	/**
	 * Saves the plug-in options to the database.
	 */
	public function saveOptions()
	{
		$this->options['version'] = $this->version;
		
		$this->options[$this->currentUserID]['filter']['entry_type'] = $this->entryType;
		$this->options[$this->currentUserID]['filter']['visibility_type'] = $this->visibilityType;
		
		$this->options['settings']['allow_public'] = $this->allowPublic;
		
		update_option('connections_options', $this->options);
	}

    /**
     * Returns $allowPublic.
     * @see pluginOptions::$allowPublic
     */
    public function getAllowPublic()
    {
        return $this->allowPublic;
    }
    
    /**
     * Sets $allowPublic.
     * @param object $allowPublic
     * @see pluginOptions::$allowPublic
     */
    public function setAllowPublic($allowPublic)
    {
        $this->allowPublic = $allowPublic;
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
		$wpRole = get_role($role);
		if (!$this->hasCapability($role, $cap)) $wpRole->add_cap($cap);
	}
	
	public function removeCapability($role, $cap)
	{
		$wpRole = get_role($role);
		if ($this->hasCapability($role, $cap)) $wpRole->remove_cap($cap);
	}
	
	public function getDefaultCapabilities()
	{
		return $this->defaultCapabilities;
	}
	
	public function setDefaultCapabilities($rolesToReset = null)
	{
		global $wp_roles;
		
		/**
		 * These are the roles that will default to having full access
		 * to all capabilites. This is to maintain plugin behavior that
		 * exisited prior to adding role/capability support.
		 */
		$defaultRoles = array('administrator', 'editor', 'author');
		
		/**
		 * If no roles are supplied to the method to reset; the method
		 * will reset the capabilies of all roles defined.
		 */
		if (!isset($rolesToReset)) $rolesToReset = $wp_roles->get_names();
		
		foreach ($rolesToReset as $role => $name)
		{
			$wpRole = get_role($role);
			
			if (in_array($role, $defaultRoles))
			{
				foreach ($this->defaultCapabilities as $cap => $name)
				{
					if (!$this->hasCapability($role, $cap)) $wpRole->add_cap($cap);
				}
			}
			else
			{
				foreach ($this->defaultCapabilities as $cap => $name)
				{
					if ($this->hasCapability($role, $cap)) $wpRole->remove_cap($cap);
				}
			}
		}
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