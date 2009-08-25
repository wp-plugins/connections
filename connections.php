<?php
/*
Plugin Name: Connections
Plugin URI: http://www.shazahm.net/?page_id=111
Description: An address book.
Version: 0.5.33
Author: Steven A. Zahm
Author URI: http://www.shazahm.net

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
 * @TODO: Fix bug. Editing an entry of entry type 'individual' and changing
 * it to a entry type 'connection group'. Later edits/copy of that entry will cause
 * the query to display it twice in the entry list.
 */

/**
 * @TODO: Fix bug. When deleting entries using the bulk action, the alert box
 * has to be dismissed twice
 */

/**
 * @TODO: Fix bug. When an image is removed from an entry or an entry is deleted the
 * image remains on the server.
 */

/**
 * @TODO: Fix bug. The output class will output emtry divs for some data fields.
 */

if (!class_exists('connectionsLoad'))
{
	/**
	 * @TODO: Scrup the plug-in to use this global $options.
	 */
	global $options;
	
	class connectionsLoad
	{
		public function __construct()
		{
			session_start();
			$_SESSION['connections']['active'] = true;
			
			$this->loadConstants();
			$this->loadDependencies();
			$this->initOptions();
			
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
			global $options;
			
			if (is_admin())
			{
				// Calls the methods to load the admin scripts and CSS.
				add_action('admin_print_scripts', array(&$this, 'loadAdminScripts') );
				add_action('admin_print_styles', array(&$this, 'loadAdminStyles') );
				
				if ($options->getVersion() != CN_CURRENT_VERSION)
				{
					/**
					 * @TODO: More descriptive error message.
					 */
					add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>ERROR: </strong>Please deactive and then reactivate Connections.</p></div>\';') );
				}
				
				if (get_option('connections_installed'))
				{
					add_action( 'admin_notices', create_function('', 'echo \'<div id="message" class="updated fade"><p><strong>' . get_option('connections_installed') . '</strong></p></div>\';') );
					delete_option('connections_installed');
				}
			}
			
			// Calls the methods to load the frontend scripts and CSS.
			add_action('wp_print_scripts', array(&$this, 'loadScripts') );
			add_action('wp_print_styles', array(&$this, 'loadStyles') );
			
			// Add a version number to the header
			add_action('wp_head', create_function('', 'echo "\n<meta name=\'Connections\' content=\'' . $options->getVersion() . '\' />\n";') );
		}
		
		private function loadConstants()
		{
			/**
			 * @TODO: Define constants for the plug-in path and URL
			 */
			
			define('CN_CURRENT_VERSION', '0.5.43');
			define('CN_IMAGE_PATH', WP_CONTENT_DIR . "/connection_images/");
			define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . "/connection_images/");
			define('CN_TABLE_NAME','connections');
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
			
			//SQL objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.sql.php');
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
		}
		
		private function initOptions()
		{
			global $options;
			
			$options = new pluginOptions();
			
			if (!$options->getAllowPublic()) $options->setAllowPublic(true);
			
			if (!$options->getImgThumbQuality()) $options->setImgThumbQuality(80);
			if (!$options->getImgThumbX()) $options->setImgThumbX(80);
			if (!$options->getImgThumbY()) $options->setImgThumbY(54);
			if (!$options->getImgThumbCrop()) $options->setImgThumbCrop('crop');
			
			if (!$options->getImgEntryQuality()) $options->setImgEntryQuality(80);
			if (!$options->getImgEntryX()) $options->setImgEntryX(225);
			if (!$options->getImgEntryY()) $options->setImgEntryY(150);
			if (!$options->getImgEntryCrop()) $options->setImgEntryCrop('crop');
			
			if (!$options->getImgProfileQuality()) $options->setImgProfileQuality(80);
			if (!$options->getImgProfileX()) $options->setImgProfileX(300);
			if (!$options->getImgProfileY()) $options->setImgProfileY(225);
			if (!$options->getImgProfileCrop()) $options->setImgProfileCrop('crop');
			
			$options->saveOptions();
		}
				
		public function activate()
		{
			global $wpdb, $options;
			
			$sql = new sql();
			
			if ($wpdb->get_var("SHOW TABLES LIKE '{$sql->getTableName()}'")!= $sql->getTableName() )
			{
				$table_name = $wpdb->prefix."connections";
			    $sql = "CREATE TABLE " . $table_name . " (
			        id mediumint(9) NOT NULL AUTO_INCREMENT,
			        ts TIMESTAMP,
			        first_name tinytext NOT NULL,
			        last_name tinytext NOT NULL,
					title tinytext NOT NULL,
					organization tinytext NOT NULL,
					department tinytext NOT NULL,
					group_name tinytext NOT NULL,
					birthday tinytext NOT NULL,
					anniversary tinytext NOT NULL,
					bio longtext NOT NULL,
			        notes longtext NOT NULL,
					addresses longtext NOT NULL,
					phone_numbers longtext NOT NULL,
					email longtext NOT NULL,
					im longtext NOT NULL,
					websites longtext NOT NULL,
					options longtext NOT NULL,
					visibility tinytext NOT NULL,
			        PRIMARY KEY  (id)
			    );";
			    //require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			    dbDelta($sql);
			}
			
			/**
			 * @TODO: Verify that the table did create.
			 */
			
			$options->setDefaultCapabilities();
			$options->setVersion(CN_CURRENT_VERSION);
			$options->saveOptions();
			
			update_option('connections_installed', "The Connections plug-in version " . $options->getVersion() . " has been installed or upgraded.");
		}
		
		public function deactivate()
		{
			global $options;
			
			$options->removeDefaultCapabilities();
			$options->saveOptions();
		}
				
		public function loadAdminMenus()
		{
			//Adds Connections to the top level menu.
			add_menu_page('Connections : Administration', 'Connections', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'), WP_PLUGIN_URL . '/connections/images/menu.png');
			
			//Adds the Connections sub-menus.
			add_submenu_page(CN_BASE_NAME, 'Connections : Entry List', 'Entry List', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Add Entry','Add Entry', 'connections_add_entry', 'connections_add', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Settings','Settings', 'connections_change_settings', 'connections_settings', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Roles &amp; Capabilites','Roles', 'connections_change_roles', 'connections_roles', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Help','Help', 'connections_view_help', 'connections_help', array (&$this, 'showPage'));
		}
		
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
		
		public function loadStyles()
		{
			/**
			 * @TODO: Move this CSS to the templates directory.
			 */
			wp_register_style('member_template_styles', WP_PLUGIN_URL . '/connections/templates/member_template.css');
			wp_enqueue_style( 'member_template_styles' );
		}
		
		public function showPage()
		{
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
				
				case 'connections_settings':
					include_once ( dirname (__FILE__) . '/submenus/settings.php' );
					connectionsShowSettinsPage();
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
	 * Initiate the plug-in.
	 */
	global $connections;
	$connections = new connectionsLoad();
}