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
        return $this->options[$this->currentUserID]['filter']['entry_type'];
    }
    
    public function setFilterEntryType($entryType)
    {
        //$this->entryType = $entryType;
		//$this->saveOptions();
		update_usermeta($this->ID, 'connections_filter_entry_type', $entryType);
    }
	
	public function getFilterVisibility()
    {
        return $this->options[$this->currentUserID]['filter']['visibility_type'];
    }
    
    public function setFilterVisibility($visibility)
    {
        $this->visibilityType = $visibility;
		$this->saveOptions();
    }
}
?>