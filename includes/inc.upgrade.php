<?php
function connectionsShowUpgradePage()
{
	/*
	 * Check whether user can access.
	 */
	if (!current_user_can('connections_view_entry_list'))
	{
		wp_die('<p id="error-page" style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center;
				width:700px">You do not have sufficient permissions to access this page.</p>');
	}
	else
	{
		global $connections;
		
		?>
			
			<div class="wrap nosubsub">
				<div class="icon32" id="icon-connections"><br/></div>
				<h2>Connections : Upgrade</h2>
				<?php echo $connections->displayMessages(); ?>
				<div id="connections-upgrade">
				
					<?php
						$urlPath = admin_url() . 'admin.php?page=' . $_GET['page'];
						
						if ($_GET['upgrade-db'] === 'do')
						{
							cnRunDBUpgrade();
						}
						else
						{
							?>
								<h3>Upgrade Required!</h3>
								<p>Your database tables for Connections is out of date and must be upgraded before you can continue.</p>
								<p>If you would like to downgrade later, please first make a complete backup of your database tables.</p>
								<h4><a href="<?php echo $urlPath;?>&amp;upgrade-db=do">Start Upgrade</a></h4>
							<?php
						}
					
					?>
				
				</div>
			</div>
			
		<?php
	}
}

function cnRunDBUpgrade()
{
	global $wpdb, $connections;
	
	$urlPath = admin_url() . 'admin.php?page=' . $_GET['page'];
	
	// Check to ensure that the table exists
	if ($wpdb->get_var("SHOW TABLES LIKE 'CN_ENTRY_TABLE'") != CN_ENTRY_TABLE)
	{
		echo '<h3>Upgrade the database structure...</h3>' . "\n";
		$wpdb->show_errors();
		
		$dbVersion = $connections->options->getDBVersion();
		
		if (version_compare($dbVersion, '0.1.0', '<'))
		{
			echo '<h4>Upgrade from database version ' . $connections->options->getDBVersion() . ' to database version ' . CN_DB_VERSION . ".</h4>\n";
			echo '<ul>';
			
			echo '<li>Changing "id" type-length/values to BIGINT(20)' . "</li>\n";
			if (!$wpdb->query("ALTER TABLE " . CN_ENTRY_TABLE . " CHANGE id id BIGINT(20) NOT NULL AUTO_INCREMENT")) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "date_added"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'date_added', 'tinytext NOT NULL AFTER ts')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "entry_type"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'entry_type', 'tinytext NOT NULL AFTER date_added')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "honorable_prefix"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'honorable_prefix', 'tinytext NOT NULL AFTER entry_type')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "middle_name"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'middle_name', 'tinytext NOT NULL AFTER first_name')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "honorable_suffix"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'honorable_suffix', 'tinytext NOT NULL AFTER last_name')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "social"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'social', 'longtext NOT NULL AFTER im')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "added_by"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'added_by', 'bigint(20) NOT NULL AFTER options')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "edited_by"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'edited_by', 'bigint(20) NOT NULL AFTER added_by')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "owner"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'owner', 'bigint(20) NOT NULL AFTER edited_by')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "status"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'status', 'varchar(20) NOT NULL AFTER owner')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "contact_first_name"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'contact_first_name', 'tinytext NOT NULL AFTER department')) echo '<ul><li>SUCCESS</li></ul>';
			
			echo '<li>Adding Column... "contact_last_name"' . "</li>\n";
			if (cnAddTableColumn(CN_ENTRY_TABLE, 'contact_last_name', 'tinytext NOT NULL AFTER contact_first_name')) echo '<ul><li>SUCCESS</li></ul>';
			echo '</ul>';
		
			echo '<h4>Adding default term relationship.</h4>';
			
			// Add the Uncategorized category to all previous entries.
			$term = $connections->term->getTermBy('slug', 'uncategorized', 'category');
			
			$entryIDs = $wpdb->get_col( "SELECT id FROM " . CN_ENTRY_TABLE );
			
			$termID[] = $term->term_taxonomy_id;
			
			foreach ($entryIDs as $entryID)
			{
				$connections->term->setTermRelationships($entryID, $termID, 'category');
			}
			
		}
		
		echo '<h4>Upgrade completed.' . "</h4>\n";
		echo '<h4><a href="' . $urlPath . '">Continue</a></h4>';
		$wpdb->hide_errors();
		$connections->options->setDBVersion(CN_DB_VERSION);
	}
}

/**
 * Add a new column.
 * Example : cnAddTableColumn( CN_ENTRY_TABLE, 'status', "varchar(20) NOT NULL");
 * 
 * Credit WordPress plug-in NGG.
 * 
 * @param string $tableName Database table name.
 * @param string $columnName Database column name to create.
 * @param string $sql SQL statement to create column
 * @return bool
 */
function cnAddTableColumn($tableName, $columnName, $sql)
{
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW COLUMNS FROM $tableName") as $column )
	{
		if ($column == $columnName) return TRUE;
	}
	
	// didn't find it try to create it.
	$wpdb->query("ALTER TABLE $tableName ADD `$columnName` " . $sql);
	
	// we cannot directly tell that whether this succeeded!
	foreach ($wpdb->get_col("SHOW COLUMNS FROM $tableName") as $column )
	{
		if ($column == $columnName) return TRUE;
	}
	
	echo("<ul><li><strong>Could not add column $columnName in table $tableName.</li></strong></ul>\n");
	
	return FALSE;
}
?>