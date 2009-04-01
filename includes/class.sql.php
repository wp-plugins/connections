<?php


/**
 * SQL statements.
 */
class sql
{
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TABLE_NAME;
	}
}

?>