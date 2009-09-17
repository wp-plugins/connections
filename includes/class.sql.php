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
}

?>