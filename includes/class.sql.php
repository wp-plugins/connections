<?php


/**
 * SQL statements.
 */
class cnSQL
{
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TABLE_NAME;
	}
	
	public function getEntries($id = NULL)
	{
		global $wpdb, $connections;
		
		if ($id != NULL) $idString = " AND `id`='" . $id . "' ";
		
		$sql = "(SELECT *, `organization` AS `sort_column` FROM " . $this->getTableName() . " WHERE `last_name` = '' AND `group_name` = ''" . $idString . ")
				 UNION
				(SELECT *, `group_name` AS `sort_column` FROM " . $this->getTableName() . " WHERE `group_name` != ''" . $idString . ")
				 UNION
				(SELECT *, `last_name` AS `sort_column` FROM " . $this->getTableName() . " WHERE `last_name` != ''" . $idString . ")
				 ORDER BY `sort_column`, `last_name`, `first_name`";
		
		return $wpdb->get_results($sql);
		
	}
}

?>