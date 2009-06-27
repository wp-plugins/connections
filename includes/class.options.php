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
     * Integer: stores the minimum WP role level to access Connections
     * @var interger
     */
	private $roleMain;
	
	private $roleChangeSettings;
	private $roleViewHelp;
	
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
     * Returns $roleMain.
     * @see pluginOptions::$roleMain
     */
    public function getRoleMain()
    {
        $level_to_role = array(0=>'subscriber', 1=>'contributer', 2=>'author', 3=>'editor', 8=>'admin');
		
		return $level_to_role[$this->roleMain];
    }
    
    /**
     * Sets $roleMain.
     * @param object $roleMain
     * @see pluginOptions::$roleMain
     */
    public function setRoleMain($roleMain)
    {
        $role_to_level = array('subscriber'=>0, 'contributer'=>1, 'author'=>2, 'editor'=>3, 'admin'=>8);
		
		$this->roleMain = $role_to_level[$roleMain];
    }

    /**
     * Returns $roleChangeSettings.
     * @see pluginOptions::$roleChangeSettings
     */
    public function getRoleChangeSettings()
    {
        $level_to_role = array(0=>'subscriber', 1=>'contributer', 2=>'author', 3=>'editor', 8=>'admin');
		
		return $level_to_role[$this->roleChangeSettings];
    }
    
    /**
     * Sets $roleChangeSettings.
     * @param object $roleChangeSettings
     * @see pluginOptions::$roleChangeSettings
     */
    public function setRoleChangeSettings($roleChangeSettings)
    {
        $role_to_level = array('subscriber'=>0, 'contributer'=>1, 'author'=>2, 'editor'=>3, 'admin'=>8);
		
		$this->roleChangeSettings = $role_to_level[$roleChangeSettings];
    }

    /**
     * Returns $roleViewHelp.
     * @see pluginOptions::$roleViewHelp
     */
    public function getRoleViewHelp()
    {
        $level_to_role = array(0=>'subscriber', 1=>'contributer', 2=>'author', 3=>'editor', 8=>'admin');
		
		return $level_to_role[$this->roleViewHelp];
    }
    
    /**
     * Sets $roleViewHelp.
     * @param object $roleViewHelp
     * @see pluginOptions::$roleViewHelp
     */
    public function setRoleViewHelp($roleViewHelp)
    {
        $role_to_level = array('subscriber'=>0, 'contributer'=>1, 'author'=>2, 'editor'=>3, 'admin'=>8);
				
		$this->roleViewHelp = $role_to_level[$roleViewHelp];
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