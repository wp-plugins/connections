<?php
class cnFilters
{
	/*public function byEntryType($entries, $entryType)
	{
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if ($entryType !== 'all')
			{
				if ($entry->getEntryType() != $entryType) $continue = true;
			}
			
			if ($continue == true) unset($entries[$key]);
		}
		
		return $entries;
	}*/
	
	/*public function byEntryVisibility($entries, $entryVisibility)
	{
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if ($entryVisibility !== 'all')
			{
				if ($entry->getVisibility() != $entryVisibility) $continue = true;
			}
			
			if ($continue == true) unset($entries[$key]);
		}
		
		return $entries;
	}*/
	
	/**
	 * Sort the entries by the user set attributes.
	 * 
	 * $object	--	syntax is field|SORT_ASC(SORT_DESC)|SORT_REGULAR(SORT_NUMERIC)(SORT_STRING)
	 * 				
	 * example  --	'state|SORT_ASC|SORT_STRING, last_name|SORT_DESC|SORT_REGULAR
	 * 
	 * 
	 * Available order_by fields:
	 * 	first_name
	 * 	last_name
	 * 	organization
	 * 	department
	 * 	city
	 * 	state
	 * 	zipcode
	 * 	country
	 * 	birthday
	 * 	anniversary
	 * 
	 * Order Flags:
	 * 	SORT_ACS
	 * 	SORT_DESC
	 * 
	 * Sort Types:
	 * 	SORT_REGULAR
	 * 	SORT_NUMERIC
	 * 	SORT_STRING
	 * 
	 * @param array of object $entries
	 * @param string $orderBy
	 * @return array of obejects
	 */
	public function orderBy($entries, $orderBy)
	{
		$orderFields = array(
							'first_name',
							'last_name',
							'organization',
							'department',
							'city',
							'state',
							'zipcode',
							'country',
							'birthday',
							'anniversary'
							);
		
		$sortFlags = array(
							'SORT_ASC' => SORT_ASC,
							'SORT_DESC' => SORT_DESC,
							'SORT_REGULAR' => SORT_REGULAR,
							'SORT_NUMERIC' => SORT_NUMERIC,
							'SORT_STRING' => SORT_STRING
							);
		
		// Build an array of each field to sort by and attributes.
		$sortFields = explode(',', $orderBy);
		
		// For each field the sort order can be defined as well as the sort type
		foreach ($sortFields as $sortField)
		{
			$sortAtts[] = explode('|', $sortField);
		}
		
		/*
		 * Dynamically build the variables that will be used for the array_multisort.
		 * 
		 * The field type should be the first item in the array if the user
		 * constructed the shortcode attribute correctly.
		 */
		foreach ($sortAtts as $field)
		{
			// Trim any spaces the user might have added to the shortcode attribute.
			$field[0] = strtolower(trim($field[0]));
			
			// If a user included a sort field that is invalid/mis-spelled it is skipped since it can not be used.
			if(!in_array($field[0], $orderFields)) continue;
			
			// The dynamic variable are being created and populated.
			foreach ($entries as $key => $row)
			{
				$entry = new cnEntry($row);
				
				switch ($field[0])
				{
					case 'first_name':
						${$field[0]}[$key] = $entry->getFirstName();
					break;
					
					case 'last_name':
						${$field[0]}[$key] = $entry->getLastName();
					break;
					
					case 'organization':
						${$field[0]}[$key] = $entry->getOrganization();
					break;
					
					case 'department':
						${$field[0]}[$key] = $entry->getDepartment();
					break;
					
					case ($field[0] === 'city' || $field[0] === 'state' || $field[0] === 'zipcode' || $field[0] === 'country'):
						if ($entry->getAddresses())
						{
							$addresses = $entry->getAddresses();
							
							foreach ($addresses as $address)
							{
								//${$field[0]}[$key] = $address[$field[0]];
								${$field[0]}[$key] = $address->$field[0];
								
								// Only set the data from the first address.
								break;
							}
							
						}
						else
						{
							${$field[0]}[$key] = NULL;
						}
					break;
					
					case 'birthday':
						${$field[0]}[$key] = strtotime($entry->getBirthday());
					break;
					
					case 'anniversary':
						${$field[0]}[$key] = strtotime($entry->getAnniversary());
					break;
				}
				
			}
			// The sorting order to be determined by a lowercase copy of the original array.
			$$field[0] = array_map('strtolower', $$field[0]);
			
			// The arrays to be sorted must be passed by refernce or it won't work.
			$sortParams[] = &$$field[0];
			
			// Add the flag ant sort type to the sort parameters if they were supplied in the shortcode attribute.
			foreach($field as $key => $flag)
			{
				$flag = strtoupper(trim($flag));
				
				// If a user included a sort tag that is invalid/mis-spelled it is skipped since it can not be used.
				if (!array_key_exists($flag, $sortFlags)) continue;
				
				$sortParams[] = &$sortFlags[$flag];
				unset($flag);
			}
		}
		
		/*print_r($sortParams);
		print_r($first_name);
		print_r($last_name);
		print_r($state);
		print_r($zipcode);
		print_r($organization);
		print_r($department);
		print_r($birthday);
		print_r($anniversary);*/
		
		$sortParams[] = &$entries;
		//$sortParams = array(&$state, SORT_ASC, SORT_REGULAR, &$zipcode, SORT_DESC, SORT_STRING, &$entries);
		call_user_func_array('array_multisort', $sortParams);
		
		
		return $entries;
	}
	
	public function permitted($entries, $publicOverride = false, $privateOverride = false)
	{
		global $connections;
		
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if (is_user_logged_in())
			{
				switch ($entry->getVisibility())
				{
					case 'public':
						if (!current_user_can('connections_view_public'))
						{
							if ($connections->options->getAllowPublicOverride())
							{
								if (!$publicOverride)
								{
									$continue = TRUE;
									
								}
							}
							else
							{
								$continue = FALSE;
							}
						}
						else
						{
							$continue = FALSE;
						}
					break;
					
					case 'private':
						if (!current_user_can('connections_view_private'))
						{
							if ($connections->options->getAllowPrivateOverride())
							{
								if (!$privateOverride)
								{
									$continue = TRUE;
								}
							}
							else
							{
								$continue = TRUE;
							}
						}
					break;
					
					case 'unlisted':
						if (!current_user_can('connections_view_unlisted'))
						{
							$continue = TRUE;
						}
					break;
					
					default:
						$continue = TRUE;
					break;
				}
			}
			else
			{
				switch ($entry->getVisibility())
				{
					case 'public':
						if (!$connections->options->getAllowPublic())
						{
							if ($connections->options->getAllowPublicOverride())
							{
								if (!$publicOverride)
								{
									$continue = TRUE;
								}
							}
							else
							{
								$continue = TRUE;
							}
						}
					break;
					
					case 'private':
						if ($connections->options->getAllowPrivateOverride())
						{
							if (!$privateOverride)
							{
								$continue = TRUE;
							}
						}
						else
						{
							$continue = TRUE;
						}
					break;
					
					default:
						$continue = TRUE;
					break;
				}
			}
			
			if ($continue == TRUE) unset($entries[$key]);
		}
		
		return $entries;
	}
}
?>