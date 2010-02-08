<?php
/*
Plugin Name: Connections
Plugin URI: http://connections-pro.com/
Description: An address book and business directory.
Version: 0.6.2.1
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
 * @TODO: Add support for SSL using the CN_PLUGIN_URL constant throughout.
 */
/**
 * @TODO: Fix bug. The output class will output entry divs for some data fields.
 */
/**
 * @TODO: Add plug-in version to the Javascript and CSS hooks.
 */

// Start the $_SESSION
if (!session_id()) @session_start();

if (!class_exists('connectionsLoad'))
{
	class connectionsLoad
	{
		public $currentUser;
		public $options;
		public $retrieve;
		public $filter;
		public $term;
		
		public $errorMessages;
		public $successMessages;
		
		public function __construct()
		{
			$this->loadConstants();
			$this->loadDependencies();
			$this->initDependencies();
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
				
				// Process any action done in the admin.
				$this->controllers();
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
			global $wpdb;
			
			define('CN_CURRENT_VERSION', '0.6.2.1');
			define('CN_DB_VERSION', '0.1.1');
			define('CN_IMAGE_PATH', WP_CONTENT_DIR . '/connection_images/');
			define('CN_IMAGE_BASE_URL', WP_CONTENT_URL . '/connection_images/');
			define('CN_ENTRY_TABLE', $wpdb->prefix . 'connections');
			define('CN_TERMS_TABLE', $wpdb->prefix . 'connections_terms');
			define('CN_TERM_TAXONOMY_TABLE', $wpdb->prefix . 'connections_term_taxonomy');
			define('CN_TERM_RELATIONSHIP_TABLE', $wpdb->prefix . 'connections_term_relationships');
			define('CN_BASE_NAME', plugin_basename( dirname(__FILE__)) );
			
			$siteURL = get_option('siteurl');
			if(is_ssl())
			{
				$siteURL = str_replace("http://", "https://", $siteURL);
			}
			
			/*
			 * Defines the URL to the plugin folder setting.
			 * @author: Ben Klocek
			 */
			define('CN_PLUGIN_URL', $siteURL.'/wp-content/plugins/' . CN_BASE_NAME);
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
			//Terms Objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.terms.php');
			//Category Objects
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.category.php');
			//Retrieve objects from the db.
			require_once(WP_PLUGIN_DIR . '/connections/includes/class.retrieve.php');
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
			
			
		}
		
		private function initDependencies()
		{
			$this->currentUser = new cnUser();
			$this->retrieve = new cnRetrieve();
			$this->filter = new cnFilters();
			$this->term = new cnTerms();
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
			/*if (!isset($_SESSION))
			{
				add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>ERROR: </strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in or the webserver configuration is preventing it from being used.</p></div>\';') );
			}
			
			if (!$_SESSION['cn_session']['active'] == true)
			{
				add_action('admin_notices', create_function('', 'echo \'<div id="message" class="error"><p><strong>ERROR: </strong>Connections requires the use of the <em>$_SESSION</em> super global; another plug-in seems to be resetting the values needed for Connections.</p></div>\';') );
			}*/
			
			$session_save_path = session_save_path();
			
			if (strpos ($session_save_path, ";") !== FALSE)
			{
				$session_save_path = substr ( $session_save_path, strpos ($session_save_path, ";")+1 );
			}
			
			if(is_dir($session_save_path))
			{
				if(!is_writable($session_save_path))
				{
					echo '<div id="message" class="error"><p><strong>ERROR: </strong>' . $this->errorMessages->get_error_message('session_path_not_writable') . '</p></div>';
				}
			}
			else
			{
				echo '<div id="message" class="error"><p><strong>ERROR: </strong>' . $this->errorMessages->get_error_message('session_path_does_not_exist') . '</p></div>';
			}
			

		}
		
		public function displayMessages()
		{
			global $connections;
			$output = null;
			
			$messages = $connections->currentUser->getMessages();
			
			if (isset($_SESSION['cn_session']['messages']) || !empty($messages))
			{
				if (!isset($messages)) $messages = $_SESSION['cn_session']['messages'];
				
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
			unset($_SESSION['cn_session']['messages']);
			if ($_GET['display_messages'] === 'true') $connections->currentUser->resetMessages();
			echo $output;
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
			
			$this->errorMessages->add('session_path_does_not_exist', 'The $_SESSION save path does not exist.');
			$this->errorMessages->add('session_path_not_writable', 'The $_SESSION save path is not writable.');
			
			$this->errorMessages->add('form_token_mismatch', 'Token mismatch.');
			$this->errorMessages->add('form_no_entry_id', 'No entry ID.');
			$this->errorMessages->add('form_no_entry_token', 'No entry token.');
			$this->errorMessages->add('form_no_session_token', 'No session token.');
			$this->errorMessages->add('form_no_token', 'No form token.');
			
			$this->errorMessages->add('capability_view_entry_list', 'You are not authorized to view the entry list. Please contact the admin if you received this message in error.');
			
			$this->errorMessages->add('capability_add', 'You are not authorized to add entries. Please contact the admin if you received this message in error.');
			$this->errorMessages->add('capability_delete', 'You are not authorized to delete entries. Please contact the admin if you received this message in error.');
			$this->errorMessages->add('capability_edit', 'You are not authorized to edit entries. Please contact the admin if you received this message in error.');
			
			$this->errorMessages->add('category_self_parent', 'Category can not be a parent of itself.');
			$this->errorMessages->add('category_delete_uncategorized', 'The Uncategorized category can not be deleted.');
			$this->errorMessages->add('category_update_uncategorized', 'The Uncategorized category can not be altered.');
			$this->errorMessages->add('category_add_uncategorized', 'The Uncategorized category already exists.');
			$this->errorMessages->add('category_add_failed', 'Failed to add category.');
			$this->errorMessages->add('category_update_failed', 'Failed to update category.');
			$this->errorMessages->add('category_delete_failed', 'Failed to delete category.');
			
			$this->errorMessages->add('entry_added_failed', 'Entry could not be added.');
			$this->errorMessages->add('entry_updated_failed', 'Entry could not be updated.');
			
			$this->errorMessages->add('image_upload_failed', 'Image upload failed.');
			$this->errorMessages->add('image_uploaded_failed', 'Uploaded image could not be saved to the destination folder.');
			$this->errorMessages->add('image_profile_failed', 'Profile image could not be created and/or saved to the destination folder.');
			$this->errorMessages->add('image_entry_failed', 'Entry image could not be created and/or saved to the destination folder.');
			$this->errorMessages->add('image_thumbnail_failed', 'Thumbnail image could not be created and/or saved to the destination folder.');
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
		 * Stores a predefined error messages in the user meta.
		 * @return null
		 * @param string
		 */
		public function setErrorMessage($errorMessage)
		{
			global $connections;
			$messages = $connections->currentUser->getMessages();
			// If the success message is slready stored, no need to store it twice.
			if (!in_array(array('error' => $errorMessage), $messages))
			{
				$connections->currentUser->setMessage(array('error' => $errorMessage));
			}
		}
		
		/**
		 * Initiates the success messages for Connections using the WP_Error class.
		 * 
		 * @return null
		 */
		private function initSuccessMessages()
		{
			$this->successMessages = new WP_Error();
			
			$this->successMessages->add('form_entry_delete', 'The entry has been deleted.');
			$this->successMessages->add('form_entry_delete_bulk', 'Entry(ies) have been deleted.');
			$this->successMessages->add('form_entry_visibility_bulk', 'Entry(ies) visibility have been updated.');
			
			$this->successMessages->add('category_deleted', 'Category(ies) have been deleted.');
			$this->successMessages->add('category_updated', 'Category has been updated.');
			$this->successMessages->add('category_added', 'Category has been added.');
			
			$this->successMessages->add('entry_added', 'Entry has been added.');
			$this->successMessages->add('entry_updated', 'Entry has been updated.');
			
			$this->successMessages->add('image_uploaded', 'Uploaded image saved.');
			$this->successMessages->add('image_profile', 'Profile image created and saved.');
			$this->successMessages->add('image_entry', 'Entry image created and saved.');
			$this->successMessages->add('image_thumbnail', 'Thumbnail image created and saved.');
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
		 * Stores a predefined success messages in the user meta.
		 * @return null
		 * @param string
		 */
		public function setSuccessMessage($successMessage)
		{
			global $connections;
			
			$messages = $connections->currentUser->getMessages();
			// If the success message is slready stored, no need to store it twice.
			if (!in_array(array('success' => $successMessage), $messages))
			{
				$connections->currentUser->setMessage(array('success' => $successMessage));
			}
		}
						
		/**
		 * Called when activating Connections via the activation hook.
		 * @return null
		 */
		public function activate()
		{
			global $wpdb, $connections;
			require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
			
			
			/**
			 * @TODO: Build a proper upgrade function for the table.
			 */
			if ($wpdb->get_var("SHOW TABLES LIKE 'CN_ENTRY_TABLE'") != CN_ENTRY_TABLE)
			{
				//$table_name = $this->db->getEntryTableName();
			    $entryTable = "CREATE TABLE " . CN_ENTRY_TABLE . " (
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
			}
			
			if ($wpdb->get_var("SHOW TABLES LIKE 'CN_TERMS_TABLE'") != CN_TERMS_TABLE)
			{
				$termsTable = "CREATE TABLE " . CN_TERMS_TABLE . " (
			        term_id bigint(20) NOT NULL AUTO_INCREMENT,
					name varchar(200) NOT NULL,
					slug varchar(200) NOT NULL,
					term_group bigint(10) NOT NULL,
			        PRIMARY KEY  (term_id),
					UNIQUE KEY slug (slug),
					INDEX name (name)
			    );";
			    
			    dbDelta($termsTable);
			}
			
			if ($wpdb->get_var("SHOW TABLES LIKE 'CN_TERM_TAXONOMY_TABLE'") != CN_TERM_TAXONOMY_TABLE)
			{
				$termTaxonomyTable = "CREATE TABLE " . CN_TERM_TAXONOMY_TABLE . " (
			        term_taxonomy_id bigint(20) NOT NULL AUTO_INCREMENT,
					term_id bigint(20) NOT NULL,
					taxonomy varchar(32) NOT NULL,
					description longtext NOT NULL,
					parent bigint(20) NOT NULL,
					count bigint(20) NOT NULL,
			        PRIMARY KEY  (term_taxonomy_id),
					UNIQUE KEY term_id_taxonomy (term_id, taxonomy),
					INDEX taxonomy (taxonomy)
			    );";
			    
			    dbDelta($termTaxonomyTable);
			}
			
			if ($wpdb->get_var("SHOW TABLES LIKE 'CN_TERM_RELATIONSHIP_TABLE'") != CN_TERM_RELATIONSHIP_TABLE)
			{
				$termTermRelationshipTable = "CREATE TABLE " . CN_TERM_RELATIONSHIP_TABLE . " (
			        entry_id bigint(20) NOT NULL,
					term_taxonomy_id bigint(20) NOT NULL,
					term_order int(11) NOT NULL,
			        PRIMARY KEY (entry_id,term_taxonomy_id),
					KEY term_taxonomy_id (term_taxonomy_id)
			    );";
			    
			    dbDelta($termTermRelationshipTable);
			}
			
			/**
			 * @TODO: Verify that the table did create.
			 */
			
			$this->options->setDefaultCapabilities();
			$this->options->setVersion(CN_CURRENT_VERSION);
			
			// Check if the Uncategorized term exists and if it doesn't create it.
			$term = $connections->term->getTermBy('slug', 'uncategorized', 'category');
			
			if (empty($term))
			{
				$attributes['slug'] = '';
				$attributes['parent'] = 0;
				$attributes['description'] = 'Entries not assigned to a category will automatically be assigned to this category and deleting a category which has been assigned to an entry will reassign that entry to this category. This category can not be edited or deleted.';
				
				$connections->term->addTerm('Uncategorized', 'category', $attributes);
			}
			
			update_option('connections_installed', 'The Connections plug-in version ' . $this->options->getVersion() . ' has been installed or upgraded.');
		}
		
		/**
		 * Called when deactivating Connections via the deactivation hook.
		 * @return null
		 */
		public function deactivate()
		{
			global $options;
			
			/* This should be occur in the unistall hook
			$this->options->removeDefaultCapabilities();
			*/
			$this->options->saveOptions();
		}
				
		public function loadAdminMenus()
		{
			// If the Connections CSV plugin is activate load the object
			if (class_exists('connectionsCSVLoad')) global $connectionsCSV;
			
			//Adds Connections to the top level menu.
			add_menu_page('Connections : Administration', 'Connections', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'), WP_PLUGIN_URL . '/connections/images/menu.png');
			
			//Adds the Connections sub-menus.
			add_submenu_page(CN_BASE_NAME, 'Connections : Entry List', 'Entry List', 'connections_view_entry_list', CN_BASE_NAME, array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Add Entry','Add Entry', 'connections_add_entry', 'connections_add', array (&$this, 'showPage'));
			add_submenu_page(CN_BASE_NAME, 'Connections : Categories','Categories', 'connections_edit_categories', 'connections_categories', array (&$this, 'showPage'));
			
			// Show the Connections Import CSV menu item
			if (isset($connectionsCSV))
			{
				global $connectionsCSV;
				
				add_submenu_page(CN_BASE_NAME, 'Connections : Import CSV','Import CSV', 'connections_add_entry', 'connections_csv', array ($connectionsCSV, 'showPage'));
			}
			
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
				case 'connections_categories':
				case 'connections_settings':
				case 'connections_roles':
				case 'connections_csv':
				case 'connections_help':
					//wp_enqueue_script('jquery');
					wp_enqueue_script('load_jwysiwyg_js', WP_PLUGIN_URL . '/connections/js/jwysiwyg/jwysiwyg/jquery.wysiwyg.js');
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
				case 'connections_categories':
				case 'connections_settings':
				case 'connections_roles':
				case 'connections_csv':
				case 'connections_help':
					wp_enqueue_style('load_jwysiwyg_css', WP_PLUGIN_URL . '/connections/js/jwysiwyg/jwysiwyg/jquery.wysiwyg.css');
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
				return;
			}
			
			if ($this->options->getDBVersion() != CN_DB_VERSION)
			{
				include_once ( dirname (__FILE__) . '/includes/inc.upgrade.php' );
				connectionsShowUpgradePage();
				return;
			}
			
			if (get_option('connections_installed'))
			{
				echo '<div id="message" class="updated fade"><p><strong>' . get_option('connections_installed') . '</strong></p></div>';
				// Remove the admin install message set during activation.
				delete_option('connections_installed');
			}
			
			$this->sessionCheck();
			
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
		
		/**
		 * Veryfy and process requested actions in the admin.
		 */
		private function controllers()
		{
			switch ($_GET['page'])
			{
				/*case 'connections':
					include_once ( dirname (__FILE__) . '/submenus/view.php' );
					connectionsShowViewPage();
				break;*/
				
				case 'connections_add':
					if ($_POST['save'] && $_GET['action'] === 'add')
					{
						check_admin_referer($this->getNonce('add_entry'), '_cn_wpnonce');
						include_once ( dirname (__FILE__) . '/includes/inc.processes.php' );
						processEntry();
						wp_redirect('admin.php?page=connections_add&display_messages=true');
					}
				break;
				
				/*case 'connections_categories':
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
				break;*/
			}
		}
		
		/**
		 * Retrives or displays the nonce field for forms using wp_nonce_field.
		 * 
		 * @param string $action Action name.
		 * @param string $item [optional] Item name. Use when protecting multiple items on the same page.
		 * @param string $name [optional] Nonce name.
		 * @param bool $referer [optional] Whether to set and display the refer field for validation.
		 * @param bool $echo [optional] Whether to display or return the hidden form field.
		 * @return string Nonce field.
		 */
		public function tokenField($action, $item = FALSE, $name = '_cn_wpnonce', $referer = TRUE, $echo = TRUE)
		{
			if ($item == FALSE)
			{
				$token = wp_nonce_field($this->nonceBase . '_' . $action, $name, TRUE, FALSE);
			}
			else
			{
				$token = wp_nonce_field($this->nonceBase . '_' . $action . '_' . $item, $name, TRUE, FALSE);
			}
			
			if ($echo) echo $token;
			if ($referer) wp_referer_field($echo, 'previous');
			
			return $token;
		}
		
		/**
		 * Generate the complete nonce string, from the nonce base, the action and an item.
		 * 
		 * @param string $action Action name.
		 * @param string $item [optional] Item name. Use when protecting multiple items on the same page.
		 * @return string Nonce string.
		 */
		public function getNonce($action, $item = FALSE)
		{
			if ($item == FALSE)
			{
				$nonce = $this->nonceBase . '_' . $action;
			}
			else
			{
				$nonce = $this->nonceBase . '_' . $action . '_' . $item;
			}
			
			return $nonce;
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