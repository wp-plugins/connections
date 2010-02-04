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
		
		/*
		 * Set the default attributes array if not supplied.
		 */
		if (!isset($atts['id'])) $atts['id'] = NULL;
		// If user is in the admin the stored category filter ID is used for the default value.
		if (!isset($atts['category']) && is_admin()) $atts['category'] = $connections->currentUser->getFilterCategory();
		// If user is in the front end the default value is NULL.
		if (!isset($atts['category']) && !is_admin()) $atts['category'] = NULL;
		
		if (!empty($atts['category']))
		{
			// Retieve the children category IDs
			$categoryIDs = $this->categoryChildrenIDs($atts['category']);
			// Add the parent category ID to the array.
			$categoryIDs[] = $atts['category'];
			
			$catString = implode("', '", $categoryIDs);
			
			$entryIDs = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE term_taxonomy_id IN ('" . $catString . "')" );
			
			if (!empty($entryIDs))
			{
				$entryIDs = implode("', '", $entryIDs);
			}
			else
			{
				$entryIDs = "'NONE'";
			}
		}
		
		//$atts['id'] = $id; // This can be removed once the shortcode is programmed to pass the $atts array.
		
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
		}
		
		if (!empty($atts['id']) || !empty($entryIDs)) $idString = " AND `id` IN ('" . $atts['id'] . $entryIDs . "') ";
		
		$sql = "(SELECT *, `organization` AS `sort_column` FROM " . CN_ENTRY_TABLE . " WHERE `last_name` = '' AND `group_name` = ''" . $idString . ")
				 UNION
				(SELECT *, `group_name` AS `sort_column` FROM " . CN_ENTRY_TABLE . " WHERE `group_name` != ''" . $idString . ")
				 UNION
				(SELECT *, `last_name` AS `sort_column` FROM " . CN_ENTRY_TABLE . " WHERE `last_name` != ''" . $idString . ")
				 ORDER BY `sort_column`, `last_name`, `first_name`";
		
		return $wpdb->get_results($sql);
		
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