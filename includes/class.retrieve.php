<?php

class cnRetrieve
{
	/**
	 * @param array $id [optional]
	 * @return object
	 */
	public function entries($id = NULL)
	{
		global $wpdb, $connections;
		
		/*
		 * Set the default attributes array if not supplied.
		 */
		if (!isset($atts['ids'])) $atts['ids'] = NULL;
		if (!isset($atts['filter_category_id'])) $atts['filter_category_id'] = $connections->currentUser->getFilterCategory();
		
		if (!empty($atts['filter_category_id']))
		{
			$categoryIDs = $this->categoryChildrenIDs($atts['filter_category_id']);
			$categoryIDs[] = $atts['filter_category_id'];
			
			$catString = implode("', '", $categoryIDs);
			
			$entryRelationships = $wpdb->get_col( "SELECT DISTINCT entry_id FROM " . CN_TERM_RELATIONSHIP_TABLE . " WHERE term_taxonomy_id IN ('" . $catString . "')" );
			
			$entryRelationships = implode("', '", $entryRelationships);
		}
		
		/*
		 * Replace the default attributes with the supplied attributes.
		 */
		$atts['ids'] = $id;
		
		/*
		 * Convert the supplied ids value to an array if it is not and then convert it to a
		 * comma delimited string for use in the query.
		 */
		if (!is_array($atts['ids']) && !empty($atts['ids']))
		{
			$atts['ids'] = array($atts['ids']);
		}
		
		if (!empty($atts['ids']) || !empty($entryRelationships)) $idString = " AND `id` IN ('" . @implode("', '", $atts['ids']) . $entryRelationships . "') ";
		
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