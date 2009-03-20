<?php


/**
 * SQL statements.
 */
class sql
{
	public function getEntry($id)
	{
		global $wpdb;
		return $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'connections WHERE id="' . $wpdb->escape($id) . '"');
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TABLE_NAME;
	}
}

?>