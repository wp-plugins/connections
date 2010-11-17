<?php

class cnRetrieve
{
	/**
	 * @return array
	 */
	public function entries( $suppliedAttr = array() )
	{
		global $wpdb, $connections;
		
		$validate = new cnValidate();
		$entryIDs = NULL;
		$joinTermRelationships = NULL;
		$joinTermTaxonomy = NULL;
		$joinTerm = NULL;
		$taxonomy = NULL;
		$termIDs = NULL;
		$termNames = NULL;
		$entryType = NULL;
		
		$permittedEntryTypes = array('individual', 'organization', 'family', 'connection_group');
		
		/*
		 * // START -- Set the default attributes array. \\
		 */
			
			// Common defaults whether user is logged in or not.
			$defaultAttr['id'] = NULL;
			$defaultAttr['wp_current_category'] = FALSE;
			$defaultAttr['allow_public_override'] = FALSE;
			$defaultAttr['private_override'] = FALSE;
			
			if ( !is_user_logged_in() )
			{
				$defaultAttr['list_type'] = 'all';
				$defaultAttr['category'] = NULL;
				$defaultAttr['limit'] = NULL;
				$defaultAttr['offset'] = NULL;
			}
			else
			{
				$defaultAttr['list_type'] = $connections->currentUser->getFilterEntryType();
				$defaultAttr['category'] = $connections->currentUser->getFilterCategory();
				$defaultAttr['limit'] = NULL;
				$defaultAttr['offset'] = NULL;
				$defaultAttr['visibility'] = $connections->currentUser->getFilterVisibility();
			}
			
			$atts = $validate->attributesArray($defaultAttr, $suppliedAttr);
			
		/*
		 * // END -- Set the default attributes array if not supplied. \\
		 */
		
		
		if ( $atts['wp_current_category'] && !is_page() )
		{
			// Get the current post categories.
			$wpCategories = get_the_category();
			
			// Build an array of the post categories.
			foreach ($wpCategories as $wpCategory)
			{
				$wpCategoryNames[] = $wpCategory->cat_name;
			}
			
			$catNameString = implode("', '", $wpCategoryNames);
			
			unset( $wpCategoryNames );
		}
				
		if ( !empty($atts['category']) )
		{
			// Trim the space characters if present.
			$atts['category'] = str_replace(' ', '', $atts['category']);
			
			// Convert to array.
			$atts['category'] = explode(',', $atts['category']);
			
			foreach ($atts['category'] as $categoryID)
			{
				// Retrieve the children category IDs
				$results = $this->categoryChildrenIDs($categoryID);
				
				// Add the parent category ID to the array.
				$categoryIDs[] = $categoryID;
				if (!empty($results))
				{
					$categoryIDs = array_merge($results, $categoryIDs);
				}
			}
			
			$catIDString = implode("', '", $categoryIDs);
			
			unset( $categoryIDs );
		}
			
		if ( !empty($catIDString) || !empty($catNameString) )
		{
			// Set the query string to INNER JOIN the term relationship and taxonomy tables.
			$joinTermRelationships = " INNER JOIN " . CN_TERM_RELATIONSHIP_TABLE . " ON ( " . CN_ENTRY_TABLE . ".id = " . CN_TERM_RELATIONSHIP_TABLE . ".entry_id ) ";
			$joinTermTaxonomy = " INNER JOIN " . CN_TERM_TAXONOMY_TABLE . " ON ( " . CN_TERM_RELATIONSHIP_TABLE . ".term_taxonomy_id = " . CN_TERM_TAXONOMY_TABLE . ".term_taxonomy_id ) ";
			$joinTerm = " INNER JOIN " . CN_TERMS_TABLE . " ON ( " . CN_TERMS_TABLE . ".term_id = " . CN_TERM_TAXONOMY_TABLE . ".term_id ) ";
			
			// Set the query string to return entries within specific categories.
			$taxonomy = ' AND ' . CN_TERM_TAXONOMY_TABLE . ".taxonomy = 'category' ";
			
			if ( !empty($catIDString) )
			{
				$termIDs = ' AND ' . CN_TERM_TAXONOMY_TABLE . ".term_id IN ('" . $catIDString . "') ";
			}
			
			if ( !empty($catNameString) )
			{
				$termNames = ' AND ' . CN_TERMS_TABLE . ".name IN ('" . $catNameString . "') ";
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
		
		$where[] = 'WHERE 1=1';
		
		// Set query string for visibility.
		if ( is_user_logged_in() )
		{
			if ( !$atts['visibility'] )
			{
				if ( current_user_can('connections_view_public') ) $visibility[] = 'public';
				if ( current_user_can('connections_view_private') ) $visibility[] = 'private';
				if ( current_user_can('connections_view_unlisted') && is_admin() ) $visibility[] = 'unlisted';
			}
			else
			{
				$visibility[] = $atts['visibility'];
			}
		}
		else
		{
			if ( $connections->options->getAllowPublic() ) $visibility[] = 'public';
			if ( $atts['allow_public_override'] == TRUE && $connections->options->getAllowPublicOverride() ) $visibility[] = 'public';
			if ( $atts['private_override'] == TRUE && $connections->options->getAllowPrivateOverride() ) $visibility[] = 'private';
		}
		
		$where[] =  'AND `visibility` IN (\'' . implode("', '", (array) $visibility) . '\')';
		
		
		// Set query string for entry type.
		if ( $atts['list_type'] !== 'all' && in_array($atts['list_type'], $permittedEntryTypes, TRUE) )
		{
			/*
			 * @TODO: Temporary for capatibility code until the code is completely cleaned up, removing the connection group entry type.
			 */
			if ( $atts['list_type'] === 'family' ) $atts['list_type'] = 'connection_group';
			
			$entryType = " AND `entry_type` = '" . $atts['list_type'] . "' ";
		}
		
		$sql = "SELECT DISTINCT " . CN_ENTRY_TABLE . ".*,
				
				CASE `entry_type`
				  WHEN 'individual' THEN `last_name`
				  WHEN 'organization' THEN `organization`
				  WHEN 'connection_group' THEN `group_name`
				END AS `sort_column`
				 
				FROM " . CN_ENTRY_TABLE . $joinTermRelationships . $joinTermTaxonomy . $joinTerm . " " .
				
				implode(' ', $where) . " " . $entryType . $taxonomy . $termIDs . $termNames . $entryIDs . "
				
				ORDER BY `sort_column`, `last_name`, `first_name`";
		
		
		
		$results = $wpdb->get_results($sql);
		
		$connections->lastQuery = $wpdb->last_query;
		$connections->lastQueryError = $wpdb->last_error;
		$connections->lastInsertID = $wpdb->insert_id;
		$connections->resultCount = $wpdb->num_rows;
		$connections->recordCount = $this->recordCount($atts['allow_public_override'], $atts['private_override']);
		
		return $results;
	}
	
	public function entry($id)
	{
		global $wpdb;
		return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
	}
	
	public function entryCategories($id)
	{
		global $wpdb;
		
		// Retrieve the categories.
		$results =  $wpdb->get_results( $wpdb->prepare( "SELECT t.*, tt.* FROM " . CN_TERMS_TABLE . " AS t INNER JOIN " . CN_TERM_TAXONOMY_TABLE . " AS tt ON t.term_id = tt.term_id INNER JOIN " . CN_TERM_RELATIONSHIP_TABLE . " AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id WHERE tt.taxonomy = 'category' AND tr.entry_id = %d ", $id) );
		//SELECT t.*, tt.* FROM wp_connections_terms AS t INNER JOIN wp_connections_term_taxonomy AS tt ON t.term_id = tt.term_id INNER JOIN wp_connections_term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id WHERE tt.taxonomy = 'category' AND tr.entry_id = 325
		
		if ( !empty($results) )
		{
			usort($results, array(&$this, 'sortTermsByName') );
		}
		
		return $results;
	}
	
	/**
	 * Sorts terms by name.
	 * 
	 * @param object $a
	 * @param object $b
	 * @return integer
	 */
	private function sortTermsByName($a, $b)
	{
		return strcmp($a->name, $b->name);
	}
	
	/**
	 * Sorts terms by ID.
	 * 
	 * @param object $a
	 * @param object $b
	 * @return integer
	 */
	private function sortTermsByID($a, $b)
	{
		if ( $a->term_id > $b->term_id )
		{
			return 1;
		}
		elseif ( $a->term_id < $b->term_id )
		{
			return -1;
		} 
		else
		{
			return 0;
		}
	}
	
	/**
	 * Total record count based on current user permissions.
	 * 
	 * @param bool $allowPublicOverride
	 * @param bool $allowPrivateOverride
	 * @return integer
	 */
	private function recordCount($allowPublicOverride, $allowPrivateOverride)
	{
		global $wpdb, $connections;
		
		$where[] = 'WHERE 1=1';
		
		if ( is_user_logged_in() )
		{
			if ( current_user_can('connections_view_public') ) $visibility[] = 'public';
			if ( current_user_can('connections_view_private') ) $visibility[] = 'private';
			if ( current_user_can('connections_view_unlisted') && is_admin() ) $visibility[] = 'unlisted';
		}
		else
		{
			if ( $connections->options->getAllowPublic() ) $visibility[] = 'public';
			if ( $allowPublicOverride == TRUE && $connections->options->getAllowPublicOverride() ) $visibility[] = 'public';
			if ( $allowPrivateOverride == TRUE && $connections->options->getAllowPrivateOverride() ) $visibility[] = 'private';
		}
		
		$where[] =  'AND `visibility` IN (\'' . implode("', '", (array) $visibility) . '\')';
		
		return $wpdb->get_var( 'SELECT COUNT(`id`) FROM ' . CN_ENTRY_TABLE . ' ' . implode(' ', $where) );
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