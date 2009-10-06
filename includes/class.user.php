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
			return '';
		}
    }
    
    public function setFilterEntryType($entryType)
    {
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
						return '';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				case 'private':
					if (!current_user_can('connections_view_private'))
					{
						return '';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				case 'unlisted':
					if (!current_user_can('connections_view_unlisted'))
					{
						return '';
					}
					else
					{
						return $user_meta['filter']['visibility'];
					}
				break;
				
				default:
					return '';
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
        $user_meta = get_usermeta($this->ID, 'connections');
		$user_meta['filter']['visibility'] = $visibility;
		update_usermeta($this->ID, 'connections', $user_meta);
    }
}
?>