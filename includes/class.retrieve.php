<?php

class cnRetrieve
{
	/**
	 * @param array $id [optional]
	 * @return object
	 */
	public function entries($atts = NULL)
	{
		global $wpdb, $connections;
		
		$permittedEntryTypes = array('individual', 'organization', 'connection_group');
		$permittedVisibilities = array('unlisted', 'private', 'public');
		
		/*
		 * // START -- Set the default attributes array if not supplied. \\
		 */
			if ( !isset($atts['id']) ) $atts['id'] = NULL;
			if ( !isset($atts['wp_current_category']) ) $atts['wp_current_category'] = FALSE;
			
			// If user in in the admin the stored visibility filter is used as the default value.
			if ( !isset($atts['visibility']) && is_admin() ) $atts['visibility'] = $connections->currentUser->getFilterVisibility();
			
			// If user in in the admin the stored entry type filter is used as the default value.
			if ( !isset($atts['list_type']) && is_admin() ) $atts['list_type'] = $connections->currentUser->getFilterEntryType();
			
			// If user in in the front end the defaul is set to 'all'.
			if ( !isset($atts['list_type']) && !is_admin() ) $atts['list_type'] = 'all';
			
			// If user is in the admin the stored category filter ID is used for the default value.
			if ( !isset($atts['category']) && is_admin() ) $atts['category'] = $connections->currentUser->getFilterCategory();
			
			// If user is in the front end the default value is NULL.
			if ( !isset($atts['category']) && !is_admin() ) $atts['category'] = NULL;
		/*
		 * // END -- Set the default attributes array if not supplied. \\
		 */
		
		if ( $atts['wp_current_category'] && !is_page() )
		{
			// Get the current post categories.
			$wpCategories = get_the_category();
			
			// Retrieve the Connections category IDs
			foreach ($wpCategories as $wpCategory)
			{
				$result = $connections->term->getTermBy('name', $wpCategory->cat_name, 'category');
				if ( !empty($result) ) $cnCategories[] = $result->term_taxonomy_id;
			}
		}
		
		if ( !empty($atts['category']) )
		{
			// Trim the space characters if present.
			$atts['category'] = str_replace(' ', '', $atts['category']);
			// Convert to array.
			$atts['category'] = explode(',', $atts['category']);
		}
		
		if ( !empty($atts['category']) || !empty($cnCategories) )
		{
			// Merge the category shortcode array with the array of the posts categories.
			if ( !empty($cnCategories) ) $atts['category'] = array_merge( (array) $atts['category'], (array) $cnCategories);
			
			foreach ($atts['category'] as $categoryID)
			{
				// Retieve the children category IDs
				$results = $this->categoryChildrenIDs($categoryID);
				
				// Add the parent category ID to the array.
				$categoryIDs[] = $categoryID;
				if (!empty($results))
				{
					$categoryIDs = array_merge($results, $categoryIDs);
				}
			}
			
			$catString = implode("', '", $categoryIDs);
			
			if ( !empty($categoryIDs) )
			{
				// Set the query string to INNER JOIN the term relationship and taxonomy tables.
				$joinTermRelationships = " INNER JOIN " . CN_TERM_RELATIONSHIP_TABLE . " ON ( " . CN_ENTRY_TABLE . ".id = " . CN_TERM_RELATIONSHIP_TABLE . ".entry_id ) ";
				$joinTermTaxonomy = " INNER JOIN " . CN_TERM_TAXONOMY_TABLE . " ON ( " . CN_TERM_RELATIONSHIP_TABLE . ".term_taxonomy_id = " . CN_TERM_TAXONOMY_TABLE . ".term_taxonomy_id ) ";
				
				// Set the query string to return entries within specific categories.
				$taxonomy = ' AND ' . CN_TERM_TAXONOMY_TABLE . ".taxonomy = 'category' ";
				$termIDs = ' AND ' . CN_TERM_TAXONOMY_TABLE . ".term_id IN ('" . $catString . "') ";
			}
		}
		
		
		/*
		 * Convert the supplied ids value to an array if it is not and then convert it to a
		 * comma delimited string for use in the query.
		 */
		if (!is_array($atts['id']) && !empty($atts['id']))
		{
			// Trim the space characters if present.
			$atts['id'] = str_replace(' ', '', $atts['id']);
			// Convert to array.
			$atts['id'] = explode(',', $atts['id']);
			// Convert to a comma delimited string for the sql query.
			$atts['id'] = implode("', '", $atts['id']);
			
			// Set query string to return specific entries.
			$entryIDs = " AND `id` IN ('" . $atts['id'] . "') ";
		}
		
				
		// Set query string for visibility.
		if ( $atts['visibility'] !== 'all' && in_array($atts['visibility'], $permittedVisibilities, TRUE) ) $visibility = " AND `visibility` = '" . $atts['visibility'] . "' ";
		
		// Set query string for entry type.
		if ( $atts['list_type'] !== 'all' && in_array($atts['list_type'], $permittedEntryTypes, TRUE) ) $entryType = " AND `entry_type` = '" . $atts['list_type'] . "' ";
		
		$sql = "SELECT DISTINCT " . CN_ENTRY_TABLE . ".*,
				
				CASE `entry_type`
				  WHEN 'individual' THEN `last_name`
				  WHEN 'organization' THEN `organization`
				  WHEN 'connection_group' THEN `group_name`
				END AS `sort_column`
				 
				FROM " . CN_ENTRY_TABLE . $joinTermRelationships . $joinTermTaxonomy . "
				
				WHERE 1=1 " . $visibility . $entryType . $taxonomy . $termIDs . $entryIDs . "
				
				ORDER BY `sort_column`, `last_name`, `first_name`";
		
		
		
		$results = $wpdb->get_results($sql);
		
		$connections->lastQuery = $wpdb->last_query;
		$connections->lastQueryError = $wpdb->last_error;
		
		return $results;
		
	}
	
	public function entry($id)
	{
		global $wpdb;
		return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
	}
	
	/**
	 * Returns all the category terms.
	 * 
	 * @return object
	 */
	public function categories()
	{
		global $connections;
		
		return $connections->term->getTerms('category');
	}
	
	/**
	 * Returns category by ID.
	 * 
	 * @param interger $id
	 * @return object
	 */
	public function category($id)
	{
		global $connections;
		
		return $connections->term->getTerm($id, 'category');
	}
	
	/**
	 * Retrieve the children IDs of the supplied parent ID.
	 * 
	 * @param interger $id
	 * @return array
	 */
	public function categoryChildrenIDs($id)
	{
		global $connections;
		
		return $connections->term->getTermChildrenIDs($id, 'category');
	}
	
}

?>