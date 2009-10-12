<?php


/**
 * SQL statements.
 */
class cnSQL
{
	public function getEntryTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_ENTRY_TABLE_NAME;
	}
	
	public function getTermsTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TERMS_TABLE_NAME;
	}
	
	public function getTermTaxonomyTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TERM_TAXONOMY_TABLE_NAME;
	}
	
	public function getTermRelationshipTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TERM_RELATIONSHIP_TABLE_NAME;
	}
	
	public function getEntries($id = NULL)
	{
		global $wpdb, $connections;
		
		if ($id != NULL) $idString = " AND `id`='" . $id . "' ";
		
		$sql = "(SELECT *, `organization` AS `sort_column` FROM " . $this->getEntryTableName() . " WHERE `last_name` = '' AND `group_name` = ''" . $idString . ")
				 UNION
				(SELECT *, `group_name` AS `sort_column` FROM " . $this->getEntryTableName() . " WHERE `group_name` != ''" . $idString . ")
				 UNION
				(SELECT *, `last_name` AS `sort_column` FROM " . $this->getEntryTableName() . " WHERE `last_name` != ''" . $idString . ")
				 ORDER BY `sort_column`, `last_name`, `first_name`";
		
		return $wpdb->get_results($sql);
		
	}
}

?>