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
								$continue = TRUE;
							}
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