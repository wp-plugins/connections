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

if (!class_exists('connectionsLoad'))
{
	class connectionsLoad
	{
		public function __construct()
		{
			session_start();
			$_SESSION['connections']['active'] = true;
			
			$this->loadConstants();
			$this->loadDependencies();
			
			register_activation_hook( dirname(__FILE__) . '/connections.php', array(&$this, 'activate') );
			register_deactivation_hook( dirname(__FILE__) . '/connections.php', array(&$this, 'deactivate') );
			
			/**
			 * @TODO: Create uninstall method to remove options and tables.
			 */
			// register_uninstall_hook( dirname(__FILE__) . '/connections.php', array('connectionsLoad', 'uninstall') );
			
			// Start this plug-in once all other plugins are fully loaded
			//add_action( 'plugins_loaded', array(&$this, 'start') );
			$this->start();
		}
		
		private function loadConstants()
		{
			/**
			 * @TODO: Define constants for the plug-in path and URL
			 */
			
			define('CN_CURRENT_VERSION', '0.5.35');define('CN_IMAGE_PATH', WP_CONTENT_DIR . "/connection_images/");
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
		
		public function activate()
		{
			/**
			 * @TODO: Finish setting up defaults. Check to make sure settings haven't been set so as not
			 * to overwite them for seemless upgrades.
			 */
			global $wpdb;
			
			get_currentuserinfo();
			$plugin_options = new pluginOptions();
			
			if (!$plugin_options->getAllowPublic()) $plugin_options->setAllowPublic(true);
			$plugin_options->setDefaultCapabilities();
			$plugin_options->setDefaultImageSettings();
			$plugin_options->saveOptions();
		}
		
		public function deactivate()
		{
			global $wpdb;
			
			get_currentuserinfo();
			$plugin_options = new pluginOptions();
			
			$plugin_options->removeDefaultCapabilities();
			$plugin_options->saveOptions();
		}
		
		public function start()
		{
			if (is_admin())
			{
				// Calls the methods to load the admin scripts and CSS.
				add_action('admin_print_scripts', array(&$this, 'loadAdminScripts') );
				add_action('admin_print_styles', array(&$this, 'loadAdminStyles') );
				
				// Calls the method to load the admin menus.
				add_action('admin_menu', array (&$this, 'loadAdminMenus'));
			}
			
			// Calls the methods to load the frontend scripts and CSS.
			add_action('wp_print_scripts', array(&$this, 'loadScripts') );
			add_action('wp_print_styles', array(&$this, 'loadStyles') );
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
			
			//$plugindir = get_bloginfo('wpurl').'/wp-content/plugins';
			// WP_PLUGIN_URL . '/connections/js/jquery.contactpreview.js'
    		wp_register_script('contactpreview', WP_PLUGIN_URL . '/connections/js/jquery.contactpreview.js');
			wp_enqueue_script( 'contactpreview' );
		}
		
		public function loadAdminStyles()
		{
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
			/*
			$styles = WP_PLUGIN_URL . '/connections/css-admin.css';
			
			wp_register_style('connections_styles', $styles);
			wp_enqueue_style('connections_styles');
			*/
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

//Builds an alpha index.
function _build_alphaindex() {
	$alphaindex = range("A","Z");
	
	foreach ($alphaindex as $letter) {
		$linkindex .= '<a href="#' . $letter . '">' . $letter . '</a> ';
	}
	
	return $linkindex;
}

//Function inspired from:
//http://www.melbournechapter.net/wordpress/programming-languages/php/cman/2006/06/16/php-form-input-and-cross-site-attacks/
/**
 * Adds a random token and timestamp to the $_SESSION variable
 * @return array
 * @param string $formId The form ID
 */
function _formtoken($formId) {
	/**
	 * Random number
	 * @var integer
	 */
	$token = md5(uniqid(rand(), true));
	//@session_start();
	$_SESSION['connections']['formTokens'][$formId]['token'] = $token;
	$_SESSION['connections']['formTokens'][$formId]['token_timestamp'] = time();
	//session_write_close();
	return $token;
}



// This installs and/or upgrades the plugin.
function _connections_install() {
	global $wpdb;
	
	get_currentuserinfo();
	$plugin_options = new pluginOptions();
	
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
    require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
    dbDelta($sql);
	
	$plugin_options->setVersion(CN_CURRENT_VERSION);
	$plugin_options->saveOptions();
}

function _connections_get_entry_select($name,$selected=null)
{
	global $wpdb;
	//$results = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . "connections ORDER BY last_name, first_name");
	
	$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = '')
			UNION
			(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != '')
			UNION
			(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != '')
			ORDER BY order_by, last_name, first_name";
	$results = $wpdb->get_results($sql);
	
    $out = '<select name="' . $name . '">';
		$out .= '<option value="">Select Entry</option>';
		foreach($results as $row)
		{
			$entry = new entry($row);
			$out .= '<option value="' . $entry->getId() . '"';
			if ($selected == $entry->getId()) $out .= ' SELECTED';
			$out .= '>' . $entry->getFullLastFirstName() . '</option>';
		}
	$out .= '</select>';
	
	return $out;
}

/**
 * @author:  Phill Pafford
 * @website: http://llihp.blogspot.com
 * 
 * @notes:
 *    Add the JavaScript and CSS to the header
 */

function contact_preview_head() {	
	$plugindir = get_bloginfo('wpurl').'/wp-content/plugins';
    
    $addToHead =<<<ADDTOHEAD


<style type="text/css">
#contact-info{
    position:absolute;
    border:1px solid #ccc;
    background:#333;
    padding:10px;
    display:none;
    color:#fff;
    width:350px;
    z-index:100;
    
    /* Rounded Corners for CSS3 */
    -moz-border-radius-topright:20px;
    -webkit-border-top-right-radius:20px;
    -moz-border-radius-bottomleft:20px;
    -webkit-border-bottom-left-radius:20px;
}
#close-contact{
    color:red;   
}
#close-contact-footer{
    text-align:right; 
}
.google-maps-link{
    color:#33CCFF; 
    text-decoration:none;   
}
.member-entry{
    -moz-border-radius:4px; 
    background-color:#FFFFFF; 
    border:1px solid #E3E3E3; 
    margin:8px 0px; 
    padding:6px; 
    position:relative;
}
.member-details{
    font-size:14px; 
    font-variant: small-caps;
}
#popup-group-name {
    color:#33CCFF; 
    font-size:13px;
    text-align:center;
    font-variant: small-caps;
}
#popup-group-members{
    color:#33CCFF; 
    font-size:15px;
    text-align:center;
    font-variant: small-caps;   
}
.m-contact{
    font-size:14px; 
    font-variant: small-caps;    
}

</style>   
    
ADDTOHEAD;

    echo $addToHead;	
}

// Add the jQuery function here 
add_action( "wp_head", 'contact_preview_head' );

?>