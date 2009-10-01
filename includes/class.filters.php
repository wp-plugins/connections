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
		foreach ($entries as $key => $value)
		{
			$entry = new cnEntry($value);
			$continue = false;
			
			if (is_user_logged_in())
			{
				if ($entry->getVisibility() === 'public' && !current_user_can('connections_view_public') && !$publicOverride) $continue = true;
				if ($entry->getVisibility() === 'private' && !current_user_can('connections_view_private') && !$privateOverride) $continue = true;
				if ($entry->getVisibility() === 'unlisted' && !current_user_can('connections_view_unlisted')) $continue = true;
			}
			else
			{
				if ($entry->getVisibility() === 'private' && !$privateOverride) $continue = true;
				if ($entry->getVisibility() === 'unlisted') $continue = true;
			}
			
			if ($continue == true) unset($entries[$key]);
		}
		
		return $entries;
		
		
		
		/**
		 * Check whether the current user, if logged in, is permitted to view public, private
		 * or unlisted entries and filter those where permission has not been granted. If unregistered
		 * visitors and users not logged in are permitted to view public entries so should a logged in
		 * user regardless of the set capability
		 * 
		 * If unregistered visitors and users not logged in; private and unlisted entries are not displayed
		 * unless the private override attribute is set to true then private entries will be displayed.
		 * 
		 * @TODO
		 * Build the query string to query only permitted entries.
		 */
		/*if (is_user_logged_in())
		{
			if ($entry->getVisibility() == 'public' && !current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) continue;
			if ($entry->getVisibility() == 'private' && !current_user_can('connections_view_private') && $atts['private_override'] == 'false') continue;
			if ($entry->getVisibility() == 'unlisted' && !current_user_can('connections_view_unlisted')) continue;
		}
		else
		{
			if ($entry->getVisibility() == 'private' && $atts['private_override'] == 'false') continue;
			if ($entry->getVisibility() == 'unlisted') continue;
		}*/
		
		
		
		/*
		 * This switch will modify the query string based on the user selection
		 * for the visbility type in the admin.
		 * 
		 * The stored visibility filter for the current user is checked against
		 * the current user's capabilites; if the current user IS NOT permitted
		 * the query string is set not to query the visibility type and then the
		 * current users filter is set to NULL to show all. IF the current user
		 * IS permitted the query string will query the visibility type. Finally
		 * the remaining visibility types are checked and if NOT permitted that is
		 * appened to the query string.
		 */
		/*switch ($connections->options->getVisibilityType($current_user->ID))
		{
			case 'public':
				if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic())
				{
					$visibilityfilter = " AND NOT visibility='public' ";
					$connections->options->setVisibilityType('', $current_user->ID);
					$connections->options->saveOptions();
				}
				else
				{
					$visibilityfilter = " AND visibility='public' ";
				}
				if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
				if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
										
				break;
				
			case 'private':
				if (!current_user_can('connections_view_private'))
				{
					$visibilityfilter = " AND NOT visibility='private' ";
					$connections->options->setVisibilityType('', $current_user->ID);
					$connections->options->saveOptions();
				}
				else
				{
					$visibilityfilter = " AND visibility='private' ";
				}
				if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
				if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
				
				break;
				
			case 'unlisted':
				if (!current_user_can('connections_view_unlisted'))
				{
					$visibilityfilter = " AND NOT visibility='unlisted' ";
					$connections->options->setVisibilityType('', $current_user->ID);
					$connections->options->saveOptions();
				}
				else
				{
					$visibilityfilter = " AND visibility='unlisted' ";
				}
				if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
				if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
				
				break;
			
			default:
				if (!current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) $visibilityfilter .= " AND NOT visibility='public' ";
				if (!current_user_can('connections_view_private')) $visibilityfilter .= " AND NOT visibility='private' ";
				if (!current_user_can('connections_view_unlisted')) $visibilityfilter .= " AND NOT visibility='unlisted' ";
				break;
		}*/
	}
}
?>