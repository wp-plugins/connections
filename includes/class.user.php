<?php
class cnUser
{
	/**
	 * Interger: stores the current WP user ID
	 * @var interger
	 */
	private $ID;
	
	/**
	 * String: holds the last set entry type for the persistant filter
	 * @var string
	 */
	private $filterEntryType;
	
	/**
	 * String: holds the last set visibility type for the persistant filter
	 * @var string
	 */
	private $filterVisibility;
	
	public function getID()
    {
        return $this->ID;
    }
    
	public function setID($id)
	{
		$this->ID = $id;
	}
	
	public function getFilterEntryType()
    {
        $user_meta = get_usermeta($this->ID, 'connections');
		
		if (!$user_meta == NULL)
		{
			return $user_meta['filter']['entry_type'];
		}
		else
		{
			return 'all';
		}
    }
    
    public function setFilterEntryType($entryType)
    {
		$permittedEntryTypes = array('all', 'individual', 'organization', 'connection_group');
		$entryType = esc_attr($entryType);
		
		if (!in_array($entryType, $permittedEntryTypes)) return FALSE;
		
		$user_meta = get_usermeta($this->ID, 'connections');
		$user_meta['filter']['entry_type'] = $entryType;
		update_usermeta($this->ID, 'connections', $user_meta);
    }
	
	public function getFilterVisibility()
    {
        
		$user_meta = get_usermeta($this->ID, 'connections');
		
		if (!$user_meta == NULL)
		{
			/*
			 * Reset the user's cached visibility filter if they no longer have access.
			 */
			switch ($user_meta['filter']['visibility'])
			{
				case 'public':
					if (!current_user_can('connections_view_public'))
					{
						return 'all';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				case 'private':
					if (!current_user_can('connections_view_private'))
					{
						return 'all';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				case 'unlisted':
					if (!current_user_can('connections_view_unlisted'))
					{
						return 'all';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				default:
					return 'all';
				break;
			}
		}
		else
		{
			return '';
		}
    }
    
    public function setFilterVisibility($visibility)
    {
		$permittedVisibility = array('all', 'public', 'private', 'unlisted');
		$visibility = esc_attr($visibility);
		
		if (!in_array($visibility, $permittedVisibility)) return FALSE;
		
		$user_meta = get_usermeta($this->ID, 'connections');
		$user_meta['filter']['visibility'] = $visibility;
		update_usermeta($this->ID, 'connections', $user_meta);
    }
	
	public function getFilterCategory()
    {
        $user_meta = get_usermeta($this->ID, 'connections');
		
		if (!$user_meta == NULL)
		{
			return $user_meta['filter']['category'];
		}
		else
		{
			return '';
		}
    }
    
    public function setFilterCategory($id)
    {
        $user_meta = get_usermeta($this->ID, 'connections');
		$user_meta['filter']['category'] = $id;
		update_usermeta($this->ID, 'connections', $user_meta);
    }
	
	public function setMessage($message)
	{
		$user_meta = get_usermeta($this->ID, 'connections');
		$user_meta['messages'][] = $message;
		update_usermeta($this->ID, 'connections', $user_meta);
	}
	
	public function getMessages()
	{
		$user_meta = get_usermeta($this->ID, 'connections');
		
		if (!empty($user_meta['messages']))
		{
			return $user_meta['messages'];
		}
		else
		{
			return array();
		}
	}
	
	public function resetMessages()
	{
		$user_meta = get_usermeta($this->ID, 'connections');
		unset($user_meta['messages']);
		update_usermeta($this->ID, 'connections', $user_meta);
	}
}
?>