<?php

class cnRetrieve
{
	public function entries($id = NULL)
	{
		global $wpdb, $connections;
		
		if ($id != NULL) $idString = " AND `id`='" . $id . "' ";
		
		$sql = "(SELECT *, `organization` AS `sort_column` FROM " . CN_ENTRY_TABLE_NAME . " WHERE `last_name` = '' AND `group_name` = ''" . $idString . ")
				 UNION
				(SELECT *, `group_name` AS `sort_column` FROM " . CN_ENTRY_TABLE_NAME . " WHERE `group_name` != ''" . $idString . ")
				 UNION
				(SELECT *, `last_name` AS `sort_column` FROM " . CN_ENTRY_TABLE_NAME . " WHERE `last_name` != ''" . $idString . ")
				 ORDER BY `sort_column`, `last_name`, `first_name`";
		
		return $wpdb->get_results($sql);
		
	}
	
	/**
	 * Returns all the category terms
	 * @return object
	 */
	public function categories()
	{
		global $connections;
		
		return $connections->term->getTerms('category');
	}
	
	/**
	 * Returns category by id
	 * @return object
	 */
	public function category($id)
	{
		global $connections;
		
		return $connections->term->getTerm($id, 'category');
	}
}

?>