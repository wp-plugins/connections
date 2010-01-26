<?php
class cnFilters
{
	public function byEntryType($entries, $entryType)
	{
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if ($entryType != null)
			{
				if ($entry->getEntryType() != $entryType) $continue = true;
			}
			
			if ($continue == true) unset($entries[$key]);
		}
		
		return $entries;
	}
	
	public function byEntryVisibility($entries, $entryVisibility)
	{
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if ($entryVisibility != null)
			{
				if ($entry->getVisibility() != $entryVisibility) $continue = true;
			}
			
			if ($continue == true) unset($entries[$key]);
		}
		
		return $entries;
	}
	
	/**
	 * Sort the entries by the user set attributes.
	 * 
	 * $object	-	syntax is field|SORT_ASC(SORT_DESC)|SORT_REGULAR(SORT_NUMERIC)(SORT_STRING)
	 * 				examples --	'state|SORT_ASC|SORT_STRING, last_name|SORT_DESC|SORT_REGULAR
	 * 
	 * @param array of obeject $entries
	 * @param string $orderBy
	 * @return array of obejects
	 */
	public function orderBy($entries, $orderBy)
	{
		// Build an array that contains the order by fields and their attributes for array_multisort.
		$sortFields = explode(',', $orderBy);
		
		foreach ($sortFields as $sortAttsString)
		{
			$sortAttsArray[] = explode('|', $sortAttsString);
		}
		// @TODO trim all values to elimate whitespace.
		
		// Dynamically build the variables that will be used for the array_multisort.
		foreach ($sortAttsArray as $field)
		{
			//$$field[0] = $field[0];
			
			
			foreach ($entries as $key => $row)
			{
				$entry = new cnEntry($row);
				
				switch ($field[0]) {
					case 'last_name':
						${$field[0]}[$key] = $entry->getLastName();
					break;
					
					case 'organization':
						${$field[0]}[$key] = $entry->getOrganization();
					break;
					
					case 'department':
						${$field[0]}[$key] = $entry->getDepartment();
					break;
					
					case 'birthday':
						${$field[0]}[$key] = $entry->getBirthday();
					break;
					
					case 'anniversary':
						${$field[0]}[$key] = $entry->getAnniversary();
					break;
				}
			}
		}
		
		/*
		 * Available order_by options.
		 * 
		 * last_name
		 * organization
		 * department
		 * city
		 * state
		 * zip_code
		 * country
		 * birthday
		 * anniversary
		 */
		
		print_r($last_name);
		print_r($organization);
		print_r($department);
		print_r($birthday);
		print_r($anniversary);
		
		/*print_r($sortAttsArray);
		print_r($sortAttsArray[0]);*/
		
		/*foreach ($entries as $key => $row)
		{
			$entry = new cnEntry($row);
			
			if ($entry->getAddresses())
			{
				$addresses = $entry->getAddresses();
				
				foreach ($addresses as $address)
				{
					if (!empty($address[$sortAttsArray[0][0]])) $toSort[$key] = $address[$sortAttsArray[0][0]];
					break;
				}
				
			}
		}*/
		
		//if (is_array($toSort)) natcasesort($toSort);
		//if (is_array($toSort)) 
		//{
			//$toSort = array_map('strtolower', $toSort);
			//$test = array($birthday, $last_name, $organization, $entries);
			//print_r($test);
			//array_multisort($test, $entries);
			//array_multisort($birthday, $entries);
			$test = array(&$organization, SORT_DESC, &$last_name, &$entries);
			//array_multisort($organization, SORT_DESC, $last_name, $entries);
			call_user_func_array('array_multisort', $test);
			//print_r($toSort);
		//}
		
		/*if (is_array($toSort))
		{
			foreach ($toSort as $key => $value)
			{
				$entriesSorted[] = $entries[$key];
			}
		}*/
		
		//if (is_array($entriesSorted)) $entries = $entriesSorted;
		
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