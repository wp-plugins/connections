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
	
	public function getEntries()
	{
		global $wpdb, $connections;
		
		$sql = "(SELECT *, `organization` AS `sort_column` FROM " . $this->getTableName() . " WHERE `last_name` = '' AND `group_name` = '')
				 UNION
				(SELECT *, `group_name` AS `sort_column` FROM " . $this->getTableName() . " WHERE `group_name` != '')
				 UNION
				(SELECT *, `last_name` AS `sort_column` FROM " . $this->getTableName() . " WHERE `last_name` != '')
				 ORDER BY `sort_column`, `last_name`, `first_name`";
		$results = $wpdb->get_results($sql);
		
		return $connections->filter->permitted(&$results);
	}
}

?>