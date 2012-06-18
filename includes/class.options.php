<?php

/**
 * Get and Set the plugin options
 */
class cnOptions
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
	 * String: plugin db version
	 * @var string
	 */
	private $dbVersion;
	
	private $defaultCapabilities = array(
								'connections_view_dashboard' => 'View Dashboard',
								'connections_manage' => 'View List (Manage)',
								'connections_add_entry' => 'Add Entry',
								'connections_add_entry_moderated' => 'Add Entry Moderated',
								'connections_edit_entry' => 'Edit Entry',
								'connections_edit_entry_moderated' => 'Edit Entry Moderated',
								'connections_delete_entry' => 'Delete Entry',
								'connections_view_public' => 'View Public Entries',
								'connections_view_private' => 'View Private Entries',
								'connections_view_unlisted' => 'View Unlisted Entries',
								'connections_edit_categories' => 'Edit Categories',
								'connections_change_settings' => 'Change Settings',
								'connections_manage_template' => 'Manage Templates',
								'connections_change_roles' => 'Change Role Capabilities',
								'connections_view_help' => 'View Help'
							);
							
	private $defaultFamilyRelationValues = array(
											'' =>"Select Relation",
											'aunt' =>"Aunt",
											'brother' =>"Brother",
											'brotherinlaw' =>"Brother-in-law",
											'cousin' =>"Cousin",
											'daughter' =>"Daughter",
											'daughterinlaw' =>"Daughter-in-law",
											'father' =>"Father",
											'fatherinlaw' =>"Father-in-law",
											'granddaughter' =>"Grand Daughter",
											'grandfather' =>"Grand Father",
											'grandmother' =>"Grand Mother",
											'grandson' =>"Grand Son",
											'greatgrandmother' =>"Great Grand Mother",
											'greatgrandfather' =>"Great Grand Father",
											'husband' =>"Husband",
											'mother' =>"Mother",
											'motherinlaw' =>"Mother-in-law",
											'nephew' =>"Nephew",
											'niece' =>"Niece",
											'sister' =>"Sister",
											'sisterinlaw' =>"Sister-in-law",
											'son' =>"Son",
											'soninlaw' =>"Son-in-law",
											'stepbrother' =>"Step Brother",
											'stepdaughter' =>"Step Daughter",
											'stepfather' =>"Step Father",
											'stepmother' =>"Step Mother",
											'stepsister' =>"Step Sister",
											'stepson' =>"Step Son",
											'uncle' =>"Uncle",
											'wife' =>"Wife"
											);
	
	
	
	
	
	private $defaultSocialMediaValues = array(
											 'delicious' => 'delicious',
											 'cdbaby' => 'CD Baby',
											 'facebook' => 'Facebook',
											 'flickr' => 'Flickr',
											 'googleplus' => 'Google+',
											 'itunes' => 'iTunes',
											 'linked-in' => 'Linked-in',
											 'mixcloud' => 'mixcloud',
											 'myspace' => 'MySpace',
											 'pinterest' => 'Pinterest',
											 'podcast' => 'Podcast',
											 'reverbnation' => 'ReverbNation',
											 'rss' => 'RSS',
											 'technorati' => 'Technorati',
											 'tripadvisor' => 'TripAdvisor',
											 'twitter' => 'Twitter',
											 'soundcloud' => 'SoundCloud',
											 'youtube' => 'YouTube'
											 );
	
	private $defaultIMValues  =   array
									(
										'aim'=>'AIM',
										'yahoo'=>'Yahoo IM',
										'jabber'=>'Jabber / Google Talk',
										'messenger'=>'Messenger',
										'skype' => 'Skype',
										'icq' => 'ICQ'
									);
	
	
	
	
									
	private $allowPublic;
	private $allowPublicOverride;
	
	private $allowPrivateOverride;
	
	private $imgThumbQuality;
	private $imgThumbX;
	private $imgThumbY;
	private $imgThumbCrop;
	private $imgThumbRatioCrop;
	private $imgThumbRatioFill;
	
	private $imgEntryQuality;
	private $imgEntryX;
	private $imgEntryY;
	private $imgEntryCrop;
	private $imgEntryRatioCrop;
	private $imgEntryRatioFill;
	
	private $imgProfileQuality;
	private $imgProfileX;
	private $imgProfileY;
	private $imgProfileCrop;
	private $imgProfileRatioCrop;
	private $imgProfileRatioFill;
	
	private $imgLogoQuality;
	private $imgLogoX;
	private $imgLogoY;
	private $imgLogoCrop;
	private $imgLogoRatioCrop;
	private $imgLogoRatioFill;
	
	private $defaultTemplatesSet;
	private $activeTemplates;
	
	private $debug;
	
	private $googleMapsAPI;
	
	private $javaScriptFooter;
	
	private $searchFields;
	
	/**
	 * Current time as reported by PHP in Unix timestamp format.
	 * 
	 * @var integer
	 */
	public $currentTime;
	
	/**
	 * Current time as reported by WordPress in Unix timestamp format.
	 * 
	 * @var integer
	 */
	public $wpCurrentTime;
	
	/**
	 * Current time as reported by MySQL in Unix timestamp format.
	 * 
	 * @var integer
	 */
	public $sqlCurrentTime;
	
	/**
	 * The time offset difference between the PHP time and the MySQL time in Unix timestamp format.
	 * 
	 * @var integer
	 */
	public $sqlTimeOffset;
	
	/**
	 * Sets up the plugin option properties. Requires the current WP user ID.
	 * @param interger $userID
	 */
	public function __construct()
	{
		global $wpdb;
		
		$this->options = get_option('connections_options');
		$this->version = $this->options['version'];
		$this->dbVersion = $this->options['db_version'];
		
		$this->debug = $this->options['debug'];
		
		//$this->entryType = $this->options[$this->currentUserID]['filter']['entry_type'];
		//$this->visibilityType = $this->options[$this->currentUserID]['filter']['visibility_type'];
		
		$this->allowPublic = $this->options['settings']['allow_public'];
		$this->allowPublicOverride = $this->options['settings']['allow_public_override'];
		
		$this->allowPrivateOverride = $this->options['settings']['allow_private_override'];
		
		$this->imgThumbQuality = $this->options['settings']['image']['thumbnail']['quality'];
		$this->imgThumbX = $this->options['settings']['image']['thumbnail']['x'];
		$this->imgThumbY = $this->options['settings']['image']['thumbnail']['y'];
		$this->imgThumbCrop = $this->options['settings']['image']['thumbnail']['crop'];
		$this->imgThumbRatioCrop = $this->options['settings']['image']['thumbnail']['ratio_crop'];
		$this->imgThumbRatioFill = $this->options['settings']['image']['thumbnail']['ratio_fill'];
		
		$this->imgEntryQuality = $this->options['settings']['image']['entry']['quality'];
		$this->imgEntryX = $this->options['settings']['image']['entry']['x'];
		$this->imgEntryY = $this->options['settings']['image']['entry']['y'];
		$this->imgEntryCrop = $this->options['settings']['image']['entry']['crop'];
		$this->imgEntryRatioCrop = $this->options['settings']['image']['entry']['ratio_crop'];
		$this->imgEntryRatioFill = $this->options['settings']['image']['entry']['ratio_fill'];
		
		$this->imgProfileQuality = $this->options['settings']['image']['profile']['quality'];
		$this->imgProfileX = $this->options['settings']['image']['profile']['x'];
		$this->imgProfileY = $this->options['settings']['image']['profile']['y'];
		$this->imgProfileCrop = $this->options['settings']['image']['profile']['crop'];
		$this->imgProfileRatioCrop = $this->options['settings']['image']['profile']['ratio_crop'];
		$this->imgProfileRatioFill = $this->options['settings']['image']['profile']['ratio_fill'];
		
		$this->imgLogoQuality = $this->options['settings']['image']['logo']['quality'];
		$this->imgLogoX = $this->options['settings']['image']['logo']['x'];
		$this->imgLogoY = $this->options['settings']['image']['logo']['y'];
		$this->imgLogoCrop = $this->options['settings']['image']['logo']['crop'];
		$this->imgLogoRatioCrop = $this->options['settings']['image']['logo']['ratio_crop'];
		$this->imgLogoRatioFill = $this->options['settings']['image']['logo']['ratio_fill'];
		
		$this->defaultTemplatesSet = $this->options['settings']['template']['defaults_set'];
		$this->activeTemplates = (array) $this->options['settings']['template']['active'];
		
		$this->googleMapsAPI = $this->options['settings']['advanced']['load_google_maps_api'];
		$this->javaScriptFooter = $this->options['settings']['advanced']['load_javascript_footer'];
		
		$this->searchFields = $this->options['settings']['search']['field'];
		
		$this->wpCurrentTime = current_time('timestamp');
		$this->currentTime = date('U');
		
		/*
		 * Because MySQL FROM_UNIXTIME returns timestamps adjusted to the local
		 * timezone it is handy to have the offset so it can be compensated for.
		 * One example is when using FROM_UNIXTIME, the timestamp returned will
		 * not be the actual stored timestamp, it will be the timestamp adjusted
		 * to the timezone set in MySQL.
		 */
		$mySQLTimeStamp = $wpdb->get_results('SELECT NOW() as timestamp');
		$this->sqlCurrentTime = strtotime( $mySQLTimeStamp[0]->timestamp );
		$this->sqlTimeOffset = time() - $this->sqlCurrentTime;
	}
	
	/**
	 * Saves the plug-in options to the database.
	 */
	public function saveOptions()
	{
		$this->options['version'] = $this->version;
		$this->options['db_version'] = $this->dbVersion;
		
		$this->options['debug'] = $this->debug;
		
		//$this->options[$this->currentUserID]['filter']['entry_type'] = $this->entryType;
		//$this->options[$this->currentUserID]['filter']['visibility_type'] = $this->visibilityType;
		
		$this->options['settings']['allow_public'] = $this->allowPublic;
		$this->options['settings']['allow_public_override'] = $this->allowPublicOverride;
		
		$this->options['settings']['allow_private_override'] = $this->allowPrivateOverride;
		
		$this->options['settings']['image']['thumbnail']['quality'] = $this->imgThumbQuality;
		$this->options['settings']['image']['thumbnail']['x'] = $this->imgThumbX;
		$this->options['settings']['image']['thumbnail']['y'] = $this->imgThumbY;
		$this->options['settings']['image']['thumbnail']['crop'] = $this->imgThumbCrop;
		$this->options['settings']['image']['thumbnail']['ratio_crop'] = $this->imgThumbRatioCrop;
		$this->options['settings']['image']['thumbnail']['ratio_fill'] = $this->imgThumbRatioFill;
		
		$this->options['settings']['image']['entry']['quality'] = $this->imgEntryQuality;
		$this->options['settings']['image']['entry']['x'] = $this->imgEntryX;
		$this->options['settings']['image']['entry']['y'] = $this->imgEntryY;
		$this->options['settings']['image']['entry']['crop'] = $this->imgEntryCrop;
		$this->options['settings']['image']['entry']['ratio_crop'] = $this->imgEntryRatioCrop;
		$this->options['settings']['image']['entry']['ratio_fill'] = $this->imgEntryRatioFill;
		
		$this->options['settings']['image']['profile']['quality'] = $this->imgProfileQuality;
		$this->options['settings']['image']['profile']['x'] = $this->imgProfileX;
		$this->options['settings']['image']['profile']['y'] = $this->imgProfileY;
		$this->options['settings']['image']['profile']['crop'] = $this->imgProfileCrop;
		$this->options['settings']['image']['profile']['ratio_crop'] = $this->imgProfileRatioCrop;
		$this->options['settings']['image']['profile']['ratio_fill'] = $this->imgProfileRatioFill;
		
		$this->options['settings']['image']['logo']['quality'] = $this->imgLogoQuality;
		$this->options['settings']['image']['logo']['x'] = $this->imgLogoX;
		$this->options['settings']['image']['logo']['y'] = $this->imgLogoY;
		$this->options['settings']['image']['logo']['crop'] = $this->imgLogoCrop;
		$this->options['settings']['image']['logo']['ratio_crop'] = $this->imgLogoRatioCrop;
		$this->options['settings']['image']['logo']['ratio_fill'] = $this->imgLogoRatioFill;
		
		$this->options['settings']['template']['defaults_set'] = $this->defaultTemplatesSet;
		$this->options['settings']['template']['active'] = $this->activeTemplates;
		
		$this->options['settings']['advanced']['load_google_maps_api'] = $this->googleMapsAPI;
		$this->options['settings']['advanced']['load_javascript_footer'] = $this->javaScriptFooter;
		
		$this->options['settings']['search']['field'] = $this->searchFields;
		
		update_option('connections_options', $this->options);
	}
	
	public function removeOptions()
	{
		delete_option('connections_options');
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
        global $wp_roles;
		
		if (!isset($wp_roles))
		{
			$wp_roles = new WP_Roles();
		}
		
		$currentRoles = $wp_roles->get_names();
		
		if($allowPublic)
		{
			$this->allowPublic = TRUE;
			
			foreach ($currentRoles as $role => $name)
			{
				$this->addCapability($role, 'connections_view_public');
			}
		}
		else
		{
			$this->allowPublic = FALSE;
			/*foreach ($currentRoles as $role => $name)
			{
				$this->removeCapability($role, 'connections_view_public');
			}*/
		}
		
    }

    /**
     * Returns $allowPublicOverride.
     * @see pluginOptions::$allowPublicOverride
     */
    public function getAllowPublicOverride()
    {
        return $this->allowPublicOverride;
    }
    
    /**
     * Sets $allowPublicOverride.
     * @param object $allowPublicOverride
     * @see pluginOptions::$allowPublicOverride
     */
    public function setAllowPublicOverride($value)
    {
        $this->allowPublicOverride = $value;
    }
	
	public function getAllowPrivateOverride()
    {
        return $this->allowPrivateOverride;
    }
    
     public function setAllowPrivateOverride($value)
    {
        $this->allowPrivateOverride = $value;
    }
	
	public function hasCapability($role, $cap)
	{
		global $wp_roles;
		
		/* 
		 * Check to make sure $wp_roles has been initialized and set.
		 * If it hasn't it is initialized. This was done because this method 
		 * can be called before the $wp_roles has been initialized.
		 */
		if (!isset($wp_roles))
		{
			$wp_roles = new WP_Roles();
		}
		
		$wpRoleDataArray = $wp_roles->roles;
		$wpRoleCaps = $wpRoleDataArray[$role]['capabilities'];
		$wpRole = new WP_Role($role, $wpRoleCaps);
		
		return $wpRole->has_cap($cap);
	}
	
	public function addCapability($role, $cap)
	{
		global $wp_roles;
		
		/* 
		 * Check to make sure $wp_roles has been initialized and set.
		 * If it hasn't it is initialized. This was done because this method 
		 * can be called before the $wp_roles has been initialized.
		 */
		if (!isset($wp_roles))
		{
			$wp_roles = new WP_Roles();
		}
		
		//$wpRole = get_role($role);
		if (!$this->hasCapability($role, $cap)) $wp_roles->add_cap($role, $cap);
	}
	
	public function removeCapability($role, $cap)
	{
		global $wp_roles;
		
		/* 
		 * Check to make sure $wp_roles has been initialized and set.
		 * If it hasn't it is initialized. This was done because this method 
		 * can be called before the $wp_roles has been initialized.
		 */
		if (!isset($wp_roles))
		{
			$wp_roles = new WP_Roles();
		}
		
		//$wpRole = get_role($role);
		if ($this->hasCapability($role, $cap)) $wp_roles->remove_cap($role, $cap);
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
		
		// Make sure the capability to view public entries is set for the roles based on the previously saved preference.
		$this->setAllowPublic($this->allowPublic);
	}
	
	public function removeDefaultCapabilities()
	{
		global $wp_roles;
		
		$rolesToReset = $wp_roles->get_names();
		
		foreach ($rolesToReset as $role => $name)
		{
			$wpRole = get_role($role);
			
			foreach ($this->defaultCapabilities as $cap => $name)
			{
				if ($this->hasCapability($role, $cap)) $wpRole->remove_cap($cap);
			}
		}
	}
	
    /**
     * Returns $version.
     * @see options::$version
     */
    public function getVersion()
    {
        if ( empty($this->version) )
		{
			return NULL;
		}
		else
		{
			return $this->version;
		}
    }
    
    /**
     * Sets $version.
     * @param object $version
     * @see options::$version
     */
    public function setVersion($version)
    {
        $this->version = $version;
		$this->saveOptions();
    }
    
	/**
     * Returns $dbVersion.
     * @see options::$dbVersion
     */
    public function getDBVersion()
    {
        if ( empty($this->dbVersion) )
		{
			return NULL;
		}
		else
		{
			return $this->dbVersion;
		}
    }
    
    /**
     * Sets $dbVersion.
     * @param string $dbVersion
     * @see options::$dbVersion
     */
    public function setDBVersion($version)
    {
        $this->dbVersion = $version;
		$this->saveOptions();
    }
	
	/**
	 * Set the image default settings
	 */
	public function setDefaultImageSettings()
	{
		$this->imgThumbQuality = 80;
		$this->imgThumbX = 80;
		$this->imgThumbY = 54;
		$this->imgThumbCrop = 'crop';
		$this->imgThumbRatioCrop = true;
		$this->imgThumbRatioFill = false;
		
		$this->imgEntryQuality = 80;
		$this->imgEntryX = 225;
		$this->imgEntryY = 150;
		$this->imgEntryCrop = 'crop';
		$this->imgEntryRatioCrop = true;
		$this->imgEntryRatioFill = false;
		
		$this->imgProfileQuality = 80;
		$this->imgProfileX = 300;
		$this->imgProfileY = 225;
		$this->imgProfileCrop = 'crop';
		$this->imgProfileRatioCrop = true;
		$this->imgProfileRatioFill = false;
	}
	
    /**
     * Returns $imgEntryQuality.
     * @see pluginOptions::$imgEntryQuality
     */
    public function getImgEntryQuality()
    {
        return $this->imgEntryQuality;
    }
    
    /**
     * Sets $imgEntryQuality.
     * @param object $imgEntryQuality
     * @see pluginOptions::$imgEntryQuality
     */
    public function setImgEntryQuality($imgEntryQuality)
    {
        $this->imgEntryQuality = (integer) $imgEntryQuality;
    }
    
    /**
     * Returns $imgEntryX.
     * @see pluginOptions::$imgEntryX
     */
    public function getImgEntryX()
    {
        return $this->imgEntryX;
    }
    
    /**
     * Sets $imgEntryX.
     * @param object $imgEntryX
     * @see pluginOptions::$imgEntryX
     */
    public function setImgEntryX($imgEntryX)
    {
        $this->imgEntryX = (integer) $imgEntryX;
    }
    
    /**
     * Returns $imgEntryY.
     * @see pluginOptions::$imgEntryY
     */
    public function getImgEntryY()
    {
        return $this->imgEntryY;
    }
    
    /**
     * Sets $imgEntryY.
     * @param object $imgEntryY
     * @see pluginOptions::$imgEntryY
     */
    public function setImgEntryY($imgEntryY)
    {
        $this->imgEntryY = (integer) $imgEntryY;
    }

    /**
     * Returns $imgEntryCrop.
     * @see pluginOptions::$imgEntryCrop
     */
    public function getImgEntryCrop()
    {
        return $this->imgEntryCrop;
    }
    
    /**
     * Sets $imgEntryCrop.
     * @param object $imgEntryCrop
     * @see pluginOptions::$imgEntryCrop
     */
    public function setImgEntryCrop($imgEntryCrop)
    {
        switch ($imgEntryCrop)
		{
        	case 'none':
        		$this->imgEntryRatioCrop = false;
				$this->imgEntryRatioFill = false;
				$this->imgEntryCrop = 'none';
        		break;
			
			case 'crop':
        		$this->imgEntryRatioCrop = true;
				$this->imgEntryRatioFill = false;
				$this->imgEntryCrop = 'crop';
        		break;
				
			case 'fill':
        		$this->imgEntryRatioCrop = false;
				$this->imgEntryRatioFill = true;
				$this->imgEntryCrop = 'fill';
        		break;
        	
        	default:
        		$this->imgEntryRatioCrop = true;
				$this->imgEntryRatioFill = false;
				$this->imgEntryCrop = 'crop';
        		break;
        }
		
    }

    /**
     * Returns $imgEntryRatioCrop.
     * @see pluginOptions::$imgEntryRatioCrop
     */
    public function getImgEntryRatioCrop()
    {
        return $this->imgEntryRatioCrop;
    }
    
    /**
     * Returns $imgEntryRatioFill.
     * @see pluginOptions::$imgEntryRatioFill
     */
    public function getImgEntryRatioFill()
    {
        return $this->imgEntryRatioFill;
    }
    
    /**
     * Returns $imgProfileQuality.
     * @see pluginOptions::$imgProfileQuality
     */
    public function getImgProfileQuality()
    {
        return $this->imgProfileQuality;
    }
    
    /**
     * Sets $imgProfileQuality.
     * @param object $imgProfileQuality
     * @see pluginOptions::$imgProfileQuality
     */
    public function setImgProfileQuality($imgProfileQuality)
    {
        $this->imgProfileQuality = (integer) $imgProfileQuality;
    }
    
    /**
     * Returns $imgProfileX.
     * @see pluginOptions::$imgProfileX
     */
    public function getImgProfileX()
    {
        return $this->imgProfileX;
    }
    
    /**
     * Sets $imgProfileX.
     * @param object $imgProfileX
     * @see pluginOptions::$imgProfileX
     */
    public function setImgProfileX($imgProfileX)
    {
        $this->imgProfileX = (integer) $imgProfileX;
    }
    
    /**
     * Returns $imgProfileY.
     * @see pluginOptions::$imgProfileY
     */
    public function getImgProfileY()
    {
        return $this->imgProfileY;
    }
    
    /**
     * Sets $imgProfileY.
     * @param object $imgProfileY
     * @see pluginOptions::$imgProfileY
     */
    public function setImgProfileY($imgProfileY)
    {
        $this->imgProfileY = (integer) $imgProfileY;
    }

    /**
     * Returns $imgProfileCrop.
     * @see pluginOptions::$imgProfileCrop
     */
    public function getImgProfileCrop()
    {
        return $this->imgProfileCrop;
    }
    
    /**
     * Sets $imgProfileCrop.
     * @param object $imgProfileCrop
     * @see pluginOptions::$imgProfileCrop
     */
    public function setImgProfileCrop($imgProfileCrop)
    {
        switch ($imgProfileCrop)
		{
        	case 'none':
        		$this->imgProfileRatioCrop = false;
				$this->imgProfileRatioFill = false;
				$this->imgProfileCrop = 'none';
        		break;
			
			case 'crop':
        		$this->imgProfileRatioCrop = true;
				$this->imgProfileRatioFill = false;
				$this->imgProfileCrop = 'crop';
        		break;
				
			case 'fill':
        		$this->imgProfileRatioCrop = false;
				$this->imgProfileRatioFill = true;
				$this->imgProfileCrop = 'fill';
        		break;
        	
        	default:
        		$this->imgProfileRatioCrop = true;
				$this->imgProfileRatioFill = false;
				$this->imgProfileCrop = 'crop';
        		break;
        }
    }
	
    /**
     * Returns $imgProfileRatioCrop.
     * @see pluginOptions::$imgProfileRatioCrop
     */
    public function getImgProfileRatioCrop()
    {
        return $this->imgProfileRatioCrop;
    }
    
    /**
     * Returns $imgProfileRatioFill.
     * @see pluginOptions::$imgProfileRatioFill
     */
    public function getImgProfileRatioFill()
    {
        return $this->imgProfileRatioFill;
    }
    
    /**
     * Returns $imgThumbQuality.
     * @see pluginOptions::$imgThumbQuality
     */
    public function getImgThumbQuality()
    {
        return $this->imgThumbQuality;
    }
    
    /**
     * Sets $imgThumbQuality.
     * @param object $imgThumbQuality
     * @see pluginOptions::$imgThumbQuality
     */
    public function setImgThumbQuality($imgThumbQuality)
    {
        $this->imgThumbQuality = (integer) $imgThumbQuality;
    }
    
    /**
     * Returns $imgThumbX.
     * @see pluginOptions::$imgThumbX
     */
    public function getImgThumbX()
    {
        return $this->imgThumbX;
    }
    
    /**
     * Sets $imgThumbX.
     * @param object $imgThumbX
     * @see pluginOptions::$imgThumbX
     */
    public function setImgThumbX($imgThumbX)
    {
        $this->imgThumbX = (integer) $imgThumbX;
    }
    
    /**
     * Returns $imgThumbY.
     * @see pluginOptions::$imgThumbY
     */
    public function getImgThumbY()
    {
        return $this->imgThumbY;
    }
    
    /**
     * Sets $imgThumbY.
     * @param object $imgThumbY
     * @see pluginOptions::$imgThumbY
     */
    public function setImgThumbY($imgThumbY)
    {
        $this->imgThumbY = (integer) $imgThumbY;
    }
	
	/**
     * Returns $imgThumbCrop.
     * @see pluginOptions::$imgThumbCrop
     */
    public function getImgThumbCrop()
    {
        return $this->imgThumbCrop;
    }
    
    /**
     * Sets $imgThumbCrop.
     * @param object $imgThumbCrop
     * @see pluginOptions::$imgThumbCrop
     */
    public function setImgThumbCrop($imgThumbCrop)
    {
        switch ($imgThumbCrop)
		{
        	case 'none':
        		$this->imgThumbRatioCrop = false;
				$this->imgThumbRatioFill = false;
				$this->imgThumbCrop = 'none';
        		break;
			
			case 'crop':
        		$this->imgThumbRatioCrop = true;
				$this->imgThumbRatioFill = false;
				$this->imgThumbCrop = 'crop';
        		break;
				
			case 'fill':
        		$this->imgThumbRatioCrop = false;
				$this->imgThumbRatioFill = true;
				$this->imgThumbCrop = 'fill';
        		break;
        	
        	default:
        		$this->imgThumbRatioCrop = true;
				$this->imgThumbRatioFill = false;
				$this->imgThumbCrop = 'crop';
        		break;
        }
		
    }

    /**
     * Returns $imgThumbRatioCrop.
     * @see pluginOptions::$imgThumbRatioCrop
     */
    public function getImgThumbRatioCrop()
    {
        return $this->imgThumbRatioCrop;
    }
    
    /**
     * Returns $imgThumbRatioFill.
     * @see pluginOptions::$imgThumbRatioFill
     */
    public function getImgThumbRatioFill()
    {
        return $this->imgThumbRatioFill;
    }
    
	
	
	public function getImgLogoQuality()
    {
        return $this->imgLogoQuality;
    }
    
    public function setImgLogoQuality($imgLogoQuality)
    {
        $this->imgLogoQuality = (integer) $imgLogoQuality;
    }
    
    public function getImgLogoX()
    {
        return $this->imgLogoX;
    }
    
    public function setImgLogoX($imgLogoX)
    {
        $this->imgLogoX = (integer) $imgLogoX;
    }
    
    public function getImgLogoY()
    {
        return $this->imgLogoY;
    }
    
    public function setImgLogoY($imgLogoY)
    {
        $this->imgLogoY = (integer) $imgLogoY;
    }
	
	public function getImgLogoCrop()
    {
        return $this->imgLogoCrop;
    }
    
    public function setImgLogoCrop($imgLogoCrop)
    {
        switch ($imgLogoCrop)
		{
        	case 'none':
        		$this->imgLogoRatioCrop = false;
				$this->imgLogoRatioFill = false;
				$this->imgLogoCrop = 'none';
        		break;
			
			case 'crop':
        		$this->imgLogoRatioCrop = true;
				$this->imgLogoRatioFill = false;
				$this->imgLogoCrop = 'crop';
        		break;
				
			case 'fill':
        		$this->imgLogoRatioCrop = false;
				$this->imgLogoRatioFill = true;
				$this->imgLogoCrop = 'fill';
        		break;
        	
        	default:
        		$this->imgLogoRatioCrop = true;
				$this->imgLogoRatioFill = false;
				$this->imgLogoCrop = 'crop';
        		break;
        }
		
    }

    public function getImgLogoRatioCrop()
    {
        return $this->imgLogoRatioCrop;
    }
    
    public function getImgLogoRatioFill()
    {
        return $this->imgLogoRatioFill;
    }
	
	
	
    /**
     * Returns $defaultTemplatesSet.
     *
     * @see cnOptions::$defaultTemplatesSet
     */
    public function getDefaultTemplatesSet() {
        return $this->defaultTemplatesSet;
    }
    
    /**
     * Sets $defaultTemplatesSet.
     *
     * @param object $defaultTemplatesSet
     * @see cnOptions::$defaultTemplatesSet
     */
    public function setDefaultTemplatesSet($defaultTemplatesSet) {
        $this->defaultTemplatesSet = $defaultTemplatesSet;
    }
    
    
    /**
     * Returns the active template by type.
     * 
     * @param string $type
     * @return object || NULL
     */
	public function getActiveTemplate($type)
	{
        ( !empty($this->activeTemplates[$type]) ) ? $result = (object) $this->activeTemplates[$type] : $result = NULL;
		return $result;
    }
    
    /**
     * Sets $activeTemplate by type.
     *
     * @param string $type
     * @param object $activeTemplate
     * @see cnOptions::$activeTemplate
     */
    public function setActiveTemplate($type, $activeTemplate)
	{
       $this->activeTemplates[$type] = (array) $activeTemplate;
    }
    
	public function setDefaultTemplates()
	{
		$templates = new cnTemplate();
		$templates->buildCatalog();
		
		$all = $templates->getCatalog('all');
		$anniversary = $templates->getCatalog('anniversary');
		$birthday = $templates->getCatalog('birthday');
		
		$this->setActiveTemplate('all', $all->card);
		$this->setActiveTemplate('individual', $all->card);
		$this->setActiveTemplate('organization', $all->card);
		$this->setActiveTemplate('family', $all->card);
		$this->setActiveTemplate('anniversary', $anniversary->{'anniversary-light'});
		$this->setActiveTemplate('birthday', $birthday->{'birthday-light'});
		
		$this->defaultTemplatesSet = TRUE;
	}

    public function getDefaultFamilyRelationValues()
    {
        return $this->defaultFamilyRelationValues;
    }
    
	public function getFamilyRelation($value)
    {
        return $this->defaultFamilyRelationValues[$value];
    }
	
    /**
     * Returns $defaultAddressValues.
     *
     * @see cnOptions::$defaultAddressValues
     */
    public function getDefaultAddressValues()
	{
        $defaultAddressValues	=	array
											(
												'home' => __( 'Home' , 'connections' ),
												'work' => __( 'Work' , 'connections' ),
												'school' => __( 'School' , 'connections' ),
												'other' => __( 'Other' , 'connections' )
											);
		
		return $defaultAddressValues;
    }
    
    
    /**
     * Returns $defaultPhoneNumberValues.
     *
     * @see cnOptions::$defaultPhoneNumberValues
     */
    public function getDefaultPhoneNumberValues()
	{
        $defaultPhoneNumberValues	=	array
											(
												'homephone' => __( 'Home Phone' , 'connections' ),
												'homefax' => __( 'Home Fax' , 'connections' ),
												'cellphone' => __( 'Cell Phone' , 'connections' ),
												'workphone' => __( 'Work Phone' , 'connections' ),
												'workfax' => __( 'Work Fax' , 'connections' )
											);
		
		return $defaultPhoneNumberValues;
    }
    
	
	public function getDefaultSocialMediaValues()
    {
        return $this->defaultSocialMediaValues;
    }
    
    /**
     * Returns $defaultIMValues.
     *
     * @see cnOptions::$defaultIMValues
     */
    public function getDefaultIMValues() {
        return $this->defaultIMValues;
    }
    
    /**
     * Returns $defaultEmailValues.
     *
     * @see cnOptions::$defaultEmailValues
     */
    public function getDefaultEmailValues()
	{
        $defaultEmailValues  =   array
									(
										'personal' => __( 'Personal Email' , 'connections' ),
										'work' => __( 'Work Email' , 'connections' )
									);
		
		return $defaultEmailValues;
    }
    
    /**
     * Returns $defaultLinkValues.
     *
     * @see cnOptions::$defaultLinkValues
     */
    public function getDefaultLinkValues()
	{
        $defaultLinkValues  =   array
									(
										'website' => __( 'Website' , 'connections' ),
										'blog' => __( 'Blog' , 'connections' )
									);
		
		return $defaultLinkValues;
    }
    
    /**
     * Sets $defaultLinkValues.
     *
     * @param object $defaultLinkValues
     * @see cnOptions::$defaultLinkValues
     */
    public function setDefaultLinkValues($defaultLinkValues) {
        $this->defaultLinkValues = $defaultLinkValues;
    }
    
	/**
     * Returns $getDefaultDateValues.
     *
     * @see cnOptions::$getDefaultDateValues
     */
    public function getDateOptions()
	{
        $dateOptions	=	array(
								/*'anniversary' => __( 'Anniversary' , 'connections' ),*/
								'baptism' => __( 'Baptism' , 'connections' ),
								/*'birthday' => __( 'Birthday' , 'connections' ),*/
								'certification' => __( 'Certification' , 'connections' ),
								'employment' => __( 'Employment' , 'connections' ),
								'membership' => __( 'Membership' , 'connections' ),
								'graduate_high_school' => __( 'Graduate High School' , 'connections' ),
								'graduate_college' => __( 'Graduate College' , 'connections' ),
								'ordination' => __( 'Ordination' , 'connections' )
							);
		
		return $dateOptions;
    }
	
	public function setDebug( $bool )
	{
		$this->debug = $bool;
	}
	
	public function getDebug()
	{
		return $this->debug;
	}
    
    /**
     * Returns $googleMapsAPI.
     *
     * @see cnOptions::$googleMapsAPI
     */
    public function getGoogleMapsAPI() {
        return $this->googleMapsAPI;
    }
    
    /**
     * Sets $googleMapsAPI.
     *
     * @param object $googleMapsAPI
     * @see cnOptions::$googleMapsAPI
     */
    public function setGoogleMapsAPI($googleMapsAPI) {
        $this->googleMapsAPI = $googleMapsAPI;
    }
    
    /**
     * Returns $javaScriptFooter.
     *
     * @see cnOptions::$javaScriptFooter
     */
    public function getJavaScriptFooter() {
        return $this->javaScriptFooter;
    }
    
    /**
     * Sets $javaScriptHeader.
     *
     * @param object $javaScriptFooter
     * @see cnOptions::$javaScriptFooter
     */
    public function setJavaScriptFooter($javaScriptFooter) {
        $this->javaScriptFooter = $javaScriptFooter;
    }
    
	/**
	 * Get the user's search field choices
	 */
    public function getSearchFields()
	{
		return (object) $this->searchFields;
	}
	
	/**
	 * Saves the user's search field choices
	 * 
	 * @TODO this will fail on tables that do not support FULLTEXT. Should somehow check before processing
	 * and set FULLTEXT support to FALSE
	 */
    public function setSearchFields($field)
	{
		global $wpdb;
		
		$wpdb->show_errors();
		
		/*
		 * The permitted fields that are supported for FULLTEXT searching.
		 */
		/*$permittedFields['entry'] = array( 'family_name' ,
										'first_name' ,
										'middle_name' ,
										'last_name' ,
										'title' ,
										'organization' ,
										'department' ,
										'contact_first_name' ,
										'contact_last_name' ,
										'bio' ,
										'notes' );
		$permittedFields['address'] = array( 'line_1' ,
										'line_2' ,
										'line_3' ,
										'city' ,
										'state' ,
										'zipcode' ,
										'country' );
		$permittedFields['phone'] = array( 'number' );*/
		
		
		/*
		 * Build the array to store the user preferences.
		 */
		/*( ! isset( $field['family_name'] ) ) ? $search['family_name'] = FALSE : $search['family_name'] = $field['family_name'];
		( ! isset( $field['first_name'] ) ) ? $search['first_name'] = FALSE : $search['first_name'] = $field['first_name'];
		( ! isset( $field['middle_name'] ) ) ? $search['middle_name'] = FALSE : $search['middle_name'] = $field['middle_name'];
		( ! isset( $field['last_name'] ) ) ? $search['last_name'] = FALSE : $search['last_name'] = $field['last_name'];
		( ! isset( $field['title'] ) ) ? $search['title'] = FALSE : $search['title'] = $field['title'];
		( ! isset( $field['organization'] ) ) ? $search['organization'] = FALSE : $search['organization'] = $field['organization'];
		( ! isset( $field['department'] ) ) ? $search['department'] = FALSE : $search['department'] = $field['department'];
		( ! isset( $field['contact_first_name'] ) ) ? $search['contact_first_name'] = FALSE : $search['contact_first_name'] = $field['contact_first_name'];
		( ! isset( $field['contact_last_name'] ) ) ? $search['contact_last_name'] = FALSE : $search['contact_last_name'] = $field['contact_last_name'];
		( ! isset( $field['bio'] ) ) ? $search['bio'] = FALSE : $search['bio'] = $field['bio'];
		( ! isset( $field['notes'] ) ) ? $search['notes'] = FALSE : $search['notes'] = $field['notes'];*/
		
		/*( ! isset( $field['address_line_1'] ) ) ? $search['address_line_1'] = FALSE : $search['address_line_1'] = $field['address_line_1'];
		( ! isset( $field['address_line_2'] ) ) ? $search['address_line_2'] = FALSE : $search['address_line_2'] = $field['address_line_2'];
		( ! isset( $field['address_line_3'] ) ) ? $search['address_line_3'] = FALSE : $search['address_line_3'] = $field['address_line_3'];
		( ! isset( $field['address_city'] ) ) ? $search['address_city'] = FALSE : $search['address_city'] = $field['address_city'];
		( ! isset( $field['address_state'] ) ) ? $search['address_state'] = FALSE : $search['address_state'] = $field['address_state'];
		( ! isset( $field['address_zipcode'] ) ) ? $search['address_zipcode'] = FALSE : $search['address_zipcode'] = $field['address_zipcode'];
		( ! isset( $field['address_country'] ) ) ? $search['address_country'] = FALSE : $search['address_country'] = $field['address_country'];
		
		( ! isset( $field['phone_number'] ) ) ? $search['phone_number'] = FALSE : $search['phone_number'] = $field['phone_number'];*/
		
		
		$search['family_name'] = ( isset( $field['family_name'] ) && $field['family_name'] != FALSE ) ? TRUE : FALSE;
		$search['first_name'] = ( isset( $field['first_name'] ) && $field['first_name'] != FALSE ) ? TRUE : FALSE;
		$search['middle_name'] = ( isset( $field['middle_name'] ) && $field['middle_name'] != FALSE ) ? TRUE : FALSE;
		$search['last_name'] = ( isset( $field['last_name'] ) && $field['last_name'] != FALSE ) ? TRUE : FALSE;
		$search['title'] = ( isset( $field['title'] ) && $field['title'] != FALSE ) ? TRUE : FALSE;
		$search['organization'] = ( isset( $field['organization'] ) && $field['organization'] != FALSE ) ? TRUE : FALSE;
		$search['department'] = ( isset( $field['department'] ) && $field['department'] != FALSE ) ? TRUE : FALSE;
		$search['contact_first_name'] = ( isset( $field['contact_first_name'] ) && $field['contact_first_name'] != FALSE ) ? TRUE : FALSE;
		$search['contact_last_name'] = ( isset( $field['contact_last_name'] ) && $field['contact_last_name'] != FALSE ) ? TRUE : FALSE;
		$search['bio'] = ( isset( $field['bio'] ) && $field['bio'] != FALSE ) ? TRUE : FALSE;
		$search['notes'] = ( isset( $field['notes'] ) && $field['notes'] != FALSE ) ? TRUE : FALSE;
		
		$search['address_line_1'] = ( isset( $field['address_line_1'] ) && $field['address_line_1'] != FALSE ) ? TRUE : FALSE;
		$search['address_line_2'] = ( isset( $field['address_line_2'] ) && $field['address_line_2'] != FALSE ) ? TRUE : FALSE;
		$search['address_line_3'] = ( isset( $field['address_line_3'] ) && $field['address_line_3'] != FALSE ) ? TRUE : FALSE;
		$search['address_city'] = ( isset( $field['address_city'] ) && $field['address_city'] != FALSE ) ? TRUE : FALSE;
		$search['address_state'] = ( isset( $field['address_state'] ) && $field['address_state'] != FALSE ) ? TRUE : FALSE;
		$search['address_zipcode'] = ( isset( $field['address_zipcode'] ) && $field['address_zipcode'] != FALSE ) ? TRUE : FALSE;
		$search['address_country'] = ( isset( $field['address_country'] ) && $field['address_country'] != FALSE ) ? TRUE : FALSE;
		
		$search['phone_number'] = ( isset( $field['phone_number'] ) && $field['phone_number'] != FALSE ) ? TRUE : FALSE;
		
		/*
		 * Drop the current FULLTEXT indexes.
		 * @TODO indexes should only be dropped on tables that are changing.
		 */
		$wpdb->query('ALTER TABLE ' . CN_ENTRY_TABLE . ' DROP INDEX search');		
		$wpdb->query('ALTER TABLE ' . CN_ENTRY_ADDRESS_TABLE . ' DROP INDEX search');		
		$wpdb->query('ALTER TABLE ' . CN_ENTRY_PHONE_TABLE . ' DROP INDEX search');
		
		/*
		 * Recreate the FULLTEXT indexes based on the user choices
		 */
		
		// Build the arrays that will be imploded in the query statement.
		if ( $search['family_name'] ) $column['entry'][] = 'family_name';
		if ( $search['first_name'] ) $column['entry'][] = 'first_name';
		if ( $search['middle_name'] ) $column['entry'][] = 'middle_name';
		if ( $search['last_name'] ) $column['entry'][] = 'last_name';
		if ( $search['title'] ) $column['entry'][] = 'title';
		if ( $search['organization'] ) $column['entry'][] = 'organization';
		if ( $search['department'] ) $column['entry'][] = 'department';
		if ( $search['contact_first_name'] ) $column['entry'][] = 'contact_first_name';
		if ( $search['contact_last_name'] ) $column['entry'][] = 'contact_last_name';
		if ( $search['bio'] ) $column['entry'][] = 'bio';
		if ( $search['notes'] ) $column['entry'][] = 'notes';
		
		if ( $search['address_line_1'] ) $column['address'][] = 'line_1';
		if ( $search['address_line_2'] ) $column['address'][] = 'line_2';
		if ( $search['address_line_3'] ) $column['address'][] = 'line_3';
		if ( $search['address_city'] ) $column['address'][] = 'city';
		if ( $search['address_state'] ) $column['address'][] = 'state';
		if ( $search['address_zipcode'] ) $column['address'][] = 'zipcode';
		if ( $search['address_country'] ) $column['address'][] = 'country';
		
		if ( $search['phone_number'] ) $column['phone'][] = 'number';
		
		// Add the FULLTEXT indexes.
		if ( ! empty( $column['entry'] ) ) $wpdb->query('ALTER TABLE ' . CN_ENTRY_TABLE . ' ADD FULLTEXT search (' . implode(',', $column['entry']) . ')');				
		if ( ! empty( $column['address'] ) ) $wpdb->query('ALTER TABLE ' . CN_ENTRY_ADDRESS_TABLE . ' ADD FULLTEXT search (' . implode(',', $column['address']) . ')');				
		if ( ! empty( $column['phone'] ) ) $wpdb->query('ALTER TABLE ' . CN_ENTRY_PHONE_TABLE . ' ADD FULLTEXT search (' . implode(',', $column['phone']) . ')');
		
		$this->searchFields = $search;
		
		$wpdb->hide_errors();
	}
	
    /**
     * Returns $options.
     * @see pluginOptions::$options
     */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
     * Sets $options.
     * @param object $options
     * @see pluginOptions::$options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

}

?>