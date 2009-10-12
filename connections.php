<?php
/*
Plugin Name: Connections
Plugin URI: http://connections-pro.com/
Description: An address book and business directory.
Version: 0.5.48
Author: Steven A. Zahm
Author URI: http://connections-pro.com/

Connections is based on Little Black Book  1.1.2 by Gerald S. Fuller which was based on
Little Black Book is based on Addressbook 0.7 by Sam Wilson
----------------------------------------
    Copyright (C)  2008  Steven A. Zahm

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	http://www.gnu.org/licenses/
----------------------------------------
*/

/**
 * @TODO: The settings and roles form need the nonce setup.
 */

/**
 * @TODO: Fix bug. The output class will output entry divs for some data fields.
 */
/**
 * @TODO: Add plug-in version to the Javascript and CSS hooks.
 */
if (!class_exists('connectionsLoad'))
{
	class connectionsLoad
	{
		public $currentUser;
		public $options;
		public $db;
		public $filter;
		
		public $errorMessages;
		public $successMessages;
		
		public function __construct()
		{
			if (!isset($_SESSION)) @session_start();
			$_SESSION['cn_session']['active'] = true;
			$_SESSION['cn_session']['messages'];
						
			$this->sessionCheck();
			$this->loadConstants();
			$this->loadDependencies();
			$this->initOptions();
			$this->initErrorMessages();
			$this->initSuccessMessages();
			
			// Calls the method to load the admin menus.
			add_action('admin_menu', array (&$this, 'loadAdminMenus'));
			
			register_activation_hook( dirname(__FILE__) . '/connections.php', array(&$this, 'activate') );
			register_deactivation_hook( dirname(__FILE__) . '/connections.php', array(&$this, 'deactivate') );
			
			/**
			 * @TODO: Create uninstall method to remove options and tables.
			 */
			// register_uninstall_hook( dirname(__FILE__) . '/connections.php', array('connectionsLoad', 'uninstall') );
			
			// Start this plug-in once all other plugins are fully loaded
			add_action( 'plugins_loaded', array(&$this, 'start') );
		}
		
		public function start()
		{
			global $connections, $current_user;
		
			get_currentuserinfo();
			$connections->currentUser->setID($current_user->ID);
			
			if (is_admin())
			{
				// Calls the methods to load the admin scripts and CSS.
				add_action('admin_print_scripts', array(&$this, 'loadAdminScripts') );
				add_action('admin_print_styles', array(&$this, 'loadAdminStyles') );
			}
			else
			{
				// Calls the methods to load the frontend scripts and CSS.
				add_action('wp_print_scripts', array(&$this, 'loadScripts') );
				add_action('wp_print_styles', array(&$this, 'loadStyles') );
				
				// Add a version number to the header
				add_action('wp_head', create_function('', 'echo "\n<meta name=\'Connections\' content=\'' . $this->options->getVersion() . '\' />\n";') );
			}
		}
		
		private function loadConstants()
		{
			/**
			 * @TODO: Define constants for the plug-in path and URL
			 */
			
			define('CN_CURRENT_VERSION', '0.5.51');
			define('CN_DB_VERSION', '0.1.0');
			define('CN_IMAGE_PATH', WP_CONTENT_DIR . '/connection_images/');
			define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . '/connection_images/');
			define('CN_ENTRY_TABLE_NAME','connections');
			define('CN_TERMS_TABLE_NAME','connections_terms');
			define('CN_TERM_TAXONOMY_TABLE_NAME','connections_term_taxonomy');
			define('CN_TERM_RELATIONSHIP_TABLE_NAME','connections_term_relationships');
			define('CN_BASE_NAME', plugin_basename( dirname(__FILE__)) );
		}
		
		private function loadDependencies()
		{
			/**
			 * @TODO: Load dependencies as needed. For example load only classes
			 * needed in the admin and frontend
			 */
			
			//GPL PHP upload class from http://www.verot.net/php_class_upload.htm
			require_once(WP_PLUGIN_DIR . '/connections/includes/php_class_upload/class.upload.php');
			
			//Current User objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.user.php');
			//SQL objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.sql.php');
			//Filter objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.filters.php');
			//HTML FORM objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.form.php');
			//date objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.date.php');
			//entry objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.entry.php');
			//plugin option objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.options.php');
			//plugin utility objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.utility.php');
			//plugin template objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.output.php');
			//builds vCard
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.vcard.php');
			
			//shortcodes
			require_once(WP_PLUGIN_DIR . '/connections/includes/inc.shortcodes.php');
			
			$this->currentUser = new cnUser();
			$this->db = new cnSQL();
			$this->filter = new cnFilters();
		}
		
		/**
		 * During install this will initiate the options. During upgrades, previously set options
		 * will be left intact but will set any new options not available in previous versions.
		 * @return 
		 */private function initOptions()
		{
			$this->options = new cnOptions();
			
			if ($this->options->getAllowPublic() === NULL) $this->options->setAllowPublic(TRUE);
			if ($this->options->getAllowPublicOverride() === NULL) $this->options->setAllowPublicOverride(FALSE);
			
			if ($this->options->getAllowPrivateOverride() === NULL) $this->options->setAllowPrivateOverride(FALSE);
			
			if ($this->options->getImgThumbQuality() === NULL) $this->options->setImgThumbQuality(80);
			if ($this->options->getImgThumbX() === NULL) $this->options->setImgThumbX(80);
			if ($this->options->getImgThumbY() === NULL) $this->options->setImgThumbY(54);
			if ($this->options->getImgThumbCrop() === NULL) $this->options->setImgThumbCrop('crop');
			
			if ($this->options->getImgEntryQuality() === NULL) $this->options->setImgEntryQuality(80);
			if ($this->options->getImgEntryX() === NULL) $this->options->setImgEntryX(225);
			if ($this->options->getImgEntryY() === NULL) $this->options->setImgEntryY(150);
			if ($this->options->getImgEntryCrop() === NULL) $this->options->setImgEntryCrop('crop');
			
			if ($this->options->getImgProfileQuality() === NULL) $this->options->setImgProfileQuality(80);
			if ($this->options->getImgProfileX() === NULL) $this->options->setImgProfileX(300);
			if ($this->options->getImgProfileY() === NULL) $this->options->setImgProfileY(225);
			if ($this->options->getImgProfileCrop() === NULL) $this->options->setImgProfileCrop('crop');
			
			$this->options->saveOptions();
		}
		
		private function sessionCheck()
		{
			/*
			 * Run a quick check to see if the $_SESSION is started and verify that Connections data isn't being
			 * overwritten and notify the user of errors.
			 */
			if (!isset($_SESSION))
			{
				add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>ERROR: </strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in or the webserver configuration is preventing it from being used.</p></div>\';') );
			}
			
			if (!$_SESSION['cn_session']['active'] == true)
			{
				add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>ERROR: </strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in seems to be resetting the values needed for Connections.</p></div>\';') );
			}
		}
		
		public function displayMessages()
		{
			$output = null;
			
			//if (get_option('connections_messages'))
			if (isset($_SESSION['cn_session']['messages']))
			{
				//$messages = get_option('connections_messages');
				$messages = $_SESSION['cn_session']['messages'];
				
				foreach ($messages as $message)
				{
					foreach($message as $type => $code)
					{
						switch ($type)
						{
							case 'error':
								$output .= '<div id="message" class="error"><p><strong>ERROR: </strong>' . $this->errorMessages->get_error_message($code) . '</p></div>';
							break;
							
							case 'success':
								$output .= '<div id="message" class="updated fade"><p><strong>SUCCESS: </strong>' . $this->successMessages->get_error_message($code) . '</p></div>';
							break;
						}
					}
				}
			}
			delete_option('connections_messages');
			unset($_SESSION['cn_session']['messages']);
			return $output;
		}
		
		/**
		 * Initiate the error messages for Connections using the WP_Error class.
		 * @return null
		 */
		private function initErrorMessages()
		{
			/**
			 * @TODO: Add error codes.
			 */
			$this->errorMessages = new WP_Error();
			
			$this->errorMessages->add('form_token_mismatch', 'Token mismatch.');
			$this->errorMessages->add('form_no_entry_id', 'No entry ID.');
			$this->errorMessages->add('form_no_entry_token', 'No entry token.');
			$this->errorMessages->add('form_no_session_token', 'No session token.');
			
			$this->errorMessages->add('capability_view_entry_list', 'You are not authorized to view the entry list. Please contact the admin if you received this message in error.');
			
			$this->errorMessages->add('capability_add', 'You are not authorized to add entries. Please contact the admin if you received this message in error.');
			$this->errorMessages->add('capability_delete', 'You are not authorized to delete entries. Please contact the admin if you received this message in error.');
			$this->errorMessages->add('capability_edit', 'You are not authorized to edit entries. Please contact the admin if you received this message in error.');
		}
		
		/**
		 * Returns one of the predefined Connections error messages.
		 * @return HTML div with error message.
		 * @param string
		 */
		public function getErrorMessage($errorMessage)
		{
			return '<div id="message" class="error"><p><strong>ERROR: </strong>' . $this->errorMessages->get_error_message($errorMessage) . '</p></div>';
		}
		
		/**
		 * Stores a predefined error messages in the $_SESSION variable
		 * @return null
		 * @param string
		 */
		public function setErrorMessage($errorMessage)
		{
			$_SESSION['cn_session']['messages'][]  = array('error' => $errorMessage);
		}
		
		/**
		 * Initiates the success messages for Connections using the WP_Error class.
		 * @return null
		 */
		private function initSuccessMessages()
		{
			/**
			 * @TODO: Add success codes.
			 */
			$this->successMessages = new WP_Error();
			
			$this->successMessages->add('form_entry_delete', 'The entry has been deleted.');
			$this->successMessages->add('form_entry_delete_bulk', 'Entry(ies) have been deleted.');
			$this->successMessages->add('form_entry_visibility_bulk', 'Entry(ies) visibility have been updated.');
		}
		
		/**
		 * Returns one of the predefined Connections success messages.
		 * @return HTML div with error message.
		 * @param string
		 */
		public function getSuccessMessage($successMessage)
		{
			return '<div id="message" class="updated fade"><p><strong>SUCCESS: </strong>' . $this->successMessages->get_error_message($successMessage) . '</p></div>';
		}
		
		/**
		 * Stores a predefined success messages in the $_SESSION variable
		 * @return null
		 * @param string
		 */
		public function setSuccessMessage($successMessage)
		{
			//if (get_option('connections_messages')) $messages = get_option('connections_messages');
			
			//$messages[] = array('success' => $successMessage);
			$_SESSION['cn_session']['messages'][]  = array('success' => $successMessage);
			
			//update_option('connections_messages', $messages);
		}
						
		/**
		 * Called when activating Connections via the activation hook.
		 * @return null
		 */
		public function activate()
		{
			global $wpdb;
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			
			
			/**
			 * @TODO: Build a proper upgrade function for the table.
			 */
			//if ($wpdb->get_var("SHOW TABLES LIKE '{$sql->getTableName()}'")!= $sql->getTableName())
			//{
				$table_name = $this->db->getEntryTableName();
			    $entryTable = "CREATE TABLE " . $table_name . " (
			        id bigint(20) NOT NULL AUTO_INCREMENT,
			        ts TIMESTAMP,
					date_added tinytext NOT NULL,
			        entry_type tinytext NOT NULL,
					visibility tinytext NOT NULL,
					group_name tinytext NOT NULL,
					honorable_prefix tinytext NOT NULL,
					first_name tinytext NOT NULL,
					middle_name tinytext NOT NULL,
			        last_name tinytext NOT NULL,
					honorable_suffix tinytext NOT NULL,
					title tinytext NOT NULL,
					organization tinytext NOT NULL,
					department tinytext NOT NULL,
					contact_first_name tinytext NOT NULL,
					contact_last_name tinytext NOT NULL,
					addresses longtext NOT NULL,
					phone_numbers longtext NOT NULL,
					email longtext NOT NULL,
					im longtext NOT NULL,
					social longtext NOT NULL,
					websites longtext NOT NULL,
					birthday tinytext NOT NULL,
					anniversary tinytext NOT NULL,
					bio longtext NOT NULL,
			        notes longtext NOT NULL,
					options longtext NOT NULL,
					added_by bigint(20) NOT NULL,
					edited_by bigint(20) NOT NULL,
					owner bigint(20) NOT NULL,
					status varchar(20) NOT NULL,
			        PRIMARY KEY  (id)
			    );";
			    
			    dbDelta($entryTable);
			//}
			
			if ($wpdb->get_var("SHOW TABLES LIKE '{$this->db->getTermsTableName()}'") != $this->db->getTermsTableName())
			{
				$table_name = $this->db->getTermsTableName();
			    $termsTable = "CREATE TABLE " . $table_name . " (
			        term_id bigint(20) NOT NULL AUTO_INCREMENT,
					name varchar(200) NOT NULL,
					slug varchar(200) NOT NULL,
					term_group bigint(10) NOT NULL,
			        PRIMARY KEY  (term_id)
			    );";
			    
			    dbDelta($termsTable);
			}
			
			if ($wpdb->get_var("SHOW TABLES LIKE '{$this->db->getTermTaxonomyTableName()}'") != $this->db->getTermTaxonomyTableName())
			{
				$table_name = $this->db->getTermTaxonomyTableName();
			    $termTaxonomyTable = "CREATE TABLE " . $table_name . " (
			        term_taxonomy_id bigint(20) NOT NULL AUTO_INCREMENT,
					term_id bigint(20) NOT NULL,
					taxonomy varchar(32) NOT NULL,
					description longtext NOT NULL,
					parent bigint(20) NOT NULL,
					count bigint(20) NOT NULL,
			        PRIMARY KEY  (term_taxonomy_id)
			    );";
			    
			    dbDelta($termTaxonomyTable);
			}
			
			if ($wpdb->get_var("SHOW TABLES LIKE '{$this->db->getTermRelationshipTableName()}'") != $this->db->getTermRelationshipTableName())
			{
				$table_name = $this->db->getTermRelationshipTableName();
			    $termTermRelationshipTable = "CREATE TABLE " . $table_name . " (
			        entry_id bigint(20) NOT NULL,
					term_taxonomy_id bigint(20) NOT NULL,
					term_order int(11) NOT NULL,
			        PRIMARY KEY  (entry_id)
			    );";
			    
			    dbDelta($termTermRelationshipTable);
			}
			
			/**
			 * @TODO: Verify that the table did create.
			 */
			
			$this->options->setDefaultCapabilities();
			$this->options->setVersion(CN_CURRENT_VERSION);
			$this->options->saveOptions();
			
			update_option('connections_installed', 'The Connections plug-in version ' . $this->options->getVersion() . ' has been installed or upgraded.');
		}
		
		/**
		 * Called when deactivating Connections via the deactivation hook.
		 * @return null
		 */
		public function deactivate()
		{
			global $options;
			
			$this->options->removeDefaultCapabilities();
			$this->options->saveOptions();
		}
				
		public function loadAdminMenus()
		{
			//Adds Connections to the top level menu.
			add_menu_page('Connections : Administration', 'Connections', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'), WP_PLUGIN_URL . '/connections/images/menu.png');
			
			//Adds the Connections sub-menus.
			add_submenu_page(CN_BASE_NAME, 'Connections : Entry List', 'Entry List', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Add Entry','Add Entry', 'connections_add_entry', 'connections_add', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Categories','Categories', 'connections_edit_categories', 'connections_categories', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Settings','Settings', 'connections_change_settings', 'connections_settings', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Roles &amp; Capabilites','Roles', 'connections_change_roles', 'connections_roles', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Help','Help', 'connections_view_help', 'connections_help', array (&$this, 'showPage'));
		}
		
		/**
		 * Loads the Connections javascripts only on required admin pages.
		 * @return null
		 */
		public function loadAdminScripts()
		{
			switch ($_GET['page'])
			{
				case CN_BASE_NAME:
				case 'connections_add':
				case 'connections_settings':
				case 'connections_roles':
				case 'connections_help':
					//wp_enqueue_script('jquery');
					wp_enqueue_script('load_ui_js', WP_PLUGIN_URL . '/connections/js/ui.js');
					//wp_enqueue_script('load_jquery_plugin', WP_PLUGIN_URL . '/connections/js/jquery.template.js');
				break;
			}
		}
		
		/**
		 * Loads the Connections javascripts on the WordPress frontend.
		 * @return null
		 */
		public function loadScripts()
		{
			/**
			 * @TODO: enqueuing the built-in jQuery breaks the Fancy Theme 2.0 by Mip Design Studio at http://www.mip-design.com/
			 * Is there a way to fix this???
			 */
			
			wp_enqueue_script('jquery');
			wp_enqueue_script('thickbox');
			
			/**
			 * @TODO: Move this javascript to the templates directory.
			 */
			wp_register_script('contactpreview', WP_PLUGIN_URL . '/connections/js/jquery.contactpreview.js');
			wp_enqueue_script( 'contactpreview' );
		}
		
		/**
		 * Loads the Connections CSS only on required admin pages.
		 * @return null
		 */
		public function loadAdminStyles()
		{
			/*
			 * Load styles only on the Connections plug-in admin pages.
			 */
			switch ($_GET['page'])
			{
				case CN_BASE_NAME:
				case 'connections_add':
				case 'connections_settings':
				case 'connections_roles':
				case 'connections_help':
					wp_enqueue_style('load_admin_css', WP_PLUGIN_URL . '/connections/css-admin.css');
				break;
			}
		}
		
		/**
		 * Loads the Connections CSS on the WordPress frontend.
		 * @return null
		 */
		public function loadStyles()
		{
			/**
			 * @TODO: Move this CSS to the templates directory.
			 */
			wp_register_style('member_template_styles', WP_PLUGIN_URL . '/connections/templates/member_template.css');
			wp_enqueue_style( 'member_template_styles' );
		}
		
		/**
		 * This is the registered function calls for the Connections admin pages as registered
		 * using the add_menu_page() and add_submenu_page() WordPress functions.
		 * @return null
		 */
		public function showPage()
		{
			/**
			 * @TODO: Upgrade page to go here.
			 */
			
			if ($this->options->getVersion() != CN_CURRENT_VERSION)
			{
				echo '<div id="message" class="error"><p><strong>ERROR: </strong>The version of Connections installed is newer than the version last activated. Please deactive and then reactivate Connections.</p></div>';
			}
			
			if (get_option('connections_installed'))
			{
				echo '<div id="message" class="updated fade"><p><strong>' . get_option('connections_installed') . '</strong></p></div>';
				// Remove the admin install message set during activation.
				delete_option('connections_installed');
			}
			
			switch ($_GET['page'])
			{
				case 'connections':
					include_once ( dirname (__FILE__) . '/submenus/view.php' );
					connectionsShowViewPage();
				break;
				
				case 'connections_add':
					include_once ( dirname (__FILE__) . '/submenus/add.php' );
					connectionsShowAddPage();
				break;
				
				case 'connections_categories':
					include_once ( dirname (__FILE__) . '/submenus/categories.php' );
					connectionsShowCategoriesPage();
				break;
				
				case 'connections_settings':
					include_once ( dirname (__FILE__) . '/submenus/settings.php' );
					connectionsShowSettingsPage();
				break;
				
				case 'connections_roles':
					include_once ( dirname (__FILE__) . '/submenus/roles.php' );
					connectionsShowRolesPage();
				break;
				
				case 'connections_help':
					include_once ( dirname (__FILE__) . '/submenus/help.php' );
					connectionsShowHelpPage();
				break;
			}
			
		}
	}
	
	/*
	 * Checks for PHP 5 or greater as required by Connections and display an error message
	 * rather that havinh PHP thru an error.
	 */
	if (version_compare(PHP_VERSION, '5.0.0', '>'))
	{
		/*
		 * Initiate the plug-in.
		 */
		global $connections;
		$connections = new connectionsLoad();
	}
	else
	{
		add_action( 'admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>Connections requires at least PHP5. You are using version: ' . PHP_VERSION . '</strong></p></div>\';') );
	}
	
}