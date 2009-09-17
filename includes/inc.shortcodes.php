<?php
add_shortcode('connections_list', '_connections_list');
function _connections_list($atts, $content=null) {
	global $wpdb, $connections, $current_user;
	
	$atts = shortcode_atts( array(
				'allow_public_override' => 'false',
				'id' => null,
				'private_override' => 'false',
				'show_alphaindex' => 'false',
				'repeat_alphaindex' => 'false',
				'show_alphahead' => 'false',
				'list_type' => 'all',
				'last_name' => null,
				'title' => null,
				'organization' => null,
				'department' => null,
				'city' => null,
				'state' => null,
				'zip_code' => null,
				'country' => null,
				'template_name' => 'card',
				'custom_template'=>'false',
				), $atts ) ;
	
	//$plugin_options = new cnOptions();
	$form = new cnFormObjects();
	
	/**
	 * If the view public entries override shortcode attribute is not permitted the attribute is unset
	 * to ensure that only possible way the next expression will not equal false and give access to the
	 * entries is for $atts['allow_public_override'] to be set and it's value be true
	 */
	if (!$connections->options->getAllowPublicOverride()) unset($atts['allow_public_override']);
	
	/**
	 * Check whether the public is permitted to see the entry list based on if the user is logged in,
	 * if the the settings are set to allow public entries to be listed for a user that is not logged in
	 * and if the shortcode attribute for the override is set and it's value is true. If any of these 
	 * are false access will not be granted.
	 */
	if (!$connections->options->getAllowPublic() && !is_user_logged_in() && $atts['allow_public_override'] !== 'true')
	{
		return '<p style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center">You do not have sufficient permissions to view these entries.</p>';
	}
	else
	{	
		/*if (is_user_logged_in() or $atts['private_override'] != 'false') { 
			$visibilityfilter = " AND (visibility='private' OR visibility='public') ";
		} else {
			$visibilityfilter = " AND visibility='public' ";
		}*/
		
		if ($atts['id'] != null) $visibilityfilter = " AND id='" . $atts['id'] . "' ";
		
		$sql = "(SELECT *, organization AS order_by FROM ".$wpdb->prefix."connections WHERE last_name = '' AND group_name = ''" . $visibilityfilter . ")
				UNION
				(SELECT *, group_name AS order_by FROM ".$wpdb->prefix."connections WHERE group_name != ''" . $visibilityfilter . ")
				UNION
				(SELECT *, last_name AS order_by FROM ".$wpdb->prefix."connections WHERE last_name != ''" . $visibilityfilter . ")
				ORDER BY order_by, last_name, first_name";
		$results = $wpdb->get_results($sql);
				
		if ($results != null) {
			
			$out = '<a name="connections-list-head"></a>';
			/*
			 * The alpha index is only displayed if set set to true and not set to repeat using the shortcode attributes.
			 * If a alpha index is set to repeat, that is handled down separately.
			 */
			if ($atts['show_alphaindex'] == 'true' && $atts['repeat_alphaindex'] != 'true') $out .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . $form->buildAlphaIndex(). "</div>";
			
			$out .=  "<div class='connections-list'>\n";
			
			foreach ($results as $row)
			{
				$entry = new cnOutput($row);
				$vCard = new cnvCard($row);
				
				/**
				 * Check whether the current user, if logged in, is permitted to view public, private
				 * or unlisted entries and filter those where permission has not been granted. If unregistered
				 * visitors and users not logged in are permitted to view public entries so should a logged in
				 * user regardless of the set capability
				 * 
				 * If unregistered visitors and users not logged in; private and unlisted entries are not displayed
				 * unless the private override attribute is set to true then private entries will be displayed.
				 * 
				 * @TODO
				 * Build the query string to query only permitted entries.
				 */
				if (is_user_logged_in())
				{
					if ($entry->getVisibility() == 'public' && !current_user_can('connections_view_public') && !$connections->options->getAllowPublic()) continue;
					if ($entry->getVisibility() == 'private' && !current_user_can('connections_view_private') && $atts['private_override'] == 'false') continue;
					if ($entry->getVisibility() == 'unlisted' && !current_user_can('connections_view_unlisted')) continue;
				}
				else
				{
					if ($entry->getVisibility() == 'private' && $atts['private_override'] == 'false') continue;
					if ($entry->getVisibility() == 'unlisted') continue;
				}
				
				/*
				 * If any of the following variables are set from a previous iteration
				 * they are unset.
				 */
				if (isset($continue)) unset($continue);
				if (isset($cities)) unset($cities);
				if (isset($states)) unset($states);
				if (isset($zipcodes)) unset($zipcodes);
				if (isset($countries)) unset($countries);
				if (isset($setAnchor)) unset($setAnchor);
				
				/*
				 * First check to make sure there is data stored in the address array.
				 * Then cycle thru each address, building separate arrays for city, state, zip and country.
				 */
				if ($entry->getAddresses())
				{
					$addressObject = new cnAddresses;
					foreach ($entry->getAddresses() as $addressRow)
					{
						if ($addressObject->getCity($addressRow) != null) $cities[] = $addressObject->getCity($addressRow);
						if ($addressObject->getState($addressRow) != null) $states[] = $addressObject->getState($addressRow);
						if ($addressObject->getZipCode($addressRow) != null) $zipcodes[] = $addressObject->getZipCode($addressRow);
						if ($addressObject->getCountry($addressRow) != null) $countries[] = $addressObject->getCountry($addressRow);
					}			
				}
				
				/*
				 * Here we filter out the entries that are wanted based on the
				 * filter attributes that may have been used in the shortcode.
				 * 
				 * NOTE: The '@' operator is used to suppress PHP generated errors. This is done
				 * because not every entry will have addresses to populate the arrays created above.
				 */
				if ($atts['list_type'] != 'all' && $atts['list_type'] != $entry->getEntryType())			$continue = true;
				if ($entry->getLastName() != $atts['last_name'] && $atts['last_name'] != null)				$continue = true;
				if ($entry->getTitle() != $atts['title'] && $atts['title'] != null)							$continue = true;
				if ($entry->getOrganization() != $atts['organization'] && $atts['organization'] != null) 	$continue = true;
				if ($entry->getDepartment() != $atts['department'] && $atts['department'] != null) 			$continue = true;
				if (@!in_array($atts['city'], $cities) && $atts['city'] != null) 							$continue = true;
				if (@!in_array($atts['state'], $states) && $atts['state'] != null) 							$continue = true;
				if (@!in_array($atts['zip_code'], $zipcodes) && $atts['zip_code'] != null) 					$continue = true;
				if (@!in_array($atts['country'], $countries) && $atts['country'] != null) 					$continue = true;
				
				/*
				 * If any of the above filters returned true, the script will continue to the next entry.
				 */
				if ($continue == true) continue;
		
				/*
				 * Checks the first letter of the last name to see if it is the next
				 * letter in the alpha array and sets the anchor.
				 * 
				 * If the alpha index is set to repeat it will append to the anchor.
				 * 
				 * If the alpha head set to true it will append the alpha head to the anchor.
				 */
				$currentLetter = strtoupper(substr($entry->getFullLastFirstName(), 0, 1));
				if ($currentLetter != $previousLetter && $atts['id'] == null) {
					if ($atts['show_alphaindex'] == 'true') $setAnchor = '<a name="' . $currentLetter . '"></a>';
					
					if ($atts['show_alphaindex'] == 'true' && $atts['repeat_alphaindex'] == 'true') $setAnchor .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . _build_alphaindex() . "</div>";
					
					if ($atts['show_alphahead'] == 'true') $setAnchor .= '<h4 class="cn-alphahead">' . $currentLetter . '</h4>';
					$previousLetter = $currentLetter;
				} else {
					$setAnchor = null;
				}
				
				/*
				 * The anchor and/or the alpha head is displayed if set to true using the shortcode attributes.
				 */
				if ($atts['show_alphaindex'] == 'true' || $atts['show_alphahead'] == 'true') $out .= $setAnchor;
				
				if ($atts['custom_template'] == 'true')
				{
					if (is_dir(WP_CONTENT_DIR . '/connections_templates'))
					{
						if (file_exists(WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php'))
						{
							// Custom Template Name
							$template = WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php';
						}
						else
						{
							$out .= '<p style="color:red; font-weight:bold; text-align:center;">ERROR CUSTOM TEMPLATE DOES NOT EXIST</p>';
						}
					}
					else
					{
						$out .= '<p style="color:red; font-weight:bold; text-align:center;">ERROR CUSTOM TEMPLATE DIRECTORY DOES NOT EXSIT</p>';
					}
				}
				else
				{
					// Use the specified default template
					$template = WP_PLUGIN_DIR . '/connections/templates/' .  $atts['template_name'] . '.php';
				}
				
				if (isset($template))
				{
					$out .= '<div class="vcard">' . "\n";
						ob_start();
					    include($template);
					    $out .= ob_get_contents();
					    ob_end_clean();
					$out .= '</div>' . "\n";
				}
							
			}
			$out .= "</div>\n";
		}
		return $out;
	}
}

add_shortcode('upcoming_list', '_upcoming_list');
function _upcoming_list($atts, $content=null) {
    global $wpdb;
	
	$atts = shortcode_atts( array(
			'list_type' => 'birthday',
			'days' => '30',
			'private_override' => false,
			'date_format' => 'F jS',
			'show_lastname' => false,
			'list_title' => null,
			), $atts ) ;
	
	if (is_user_logged_in() or $atts['private_override'] != false) { 
		$visibilityfilter = " AND (visibility='private' OR visibility='public') AND (".$atts['list_type']." != '')";
	} else {
		$visibilityfilter = " AND (visibility='public') AND (".$atts['list_type']." != '')";
	}
	
	if ($atts['list_title'] == null) {
		if ($atts['list_type'] == "birthday") $list_title = "Upcoming Birthdays the next " . $atts['days'] . " days";
		if ($atts['list_type'] == "anniversary") $list_title = "Upcoming Anniversaries the next " . $atts['days'] . " days";
	} else {
		$list_title = $atts['list_title'];
	}
	
	$sql = "SELECT id, ".$atts['list_type'].", last_name, first_name FROM ".$wpdb->prefix."connections where (YEAR(DATE_ADD(CURRENT_DATE, INTERVAL ".$atts['days']." DAY))"
        . " - YEAR(FROM_UNIXTIME(".$atts['list_type'].")) )"
        . " - ( MID(DATE_ADD(CURRENT_DATE, INTERVAL ".$atts['days']." DAY),5,6)"
        . " < MID(FROM_UNIXTIME(".$atts['list_type']."),5,6) )"
        . " > ( YEAR(CURRENT_DATE)"
        . " - YEAR(FROM_UNIXTIME(".$atts['list_type'].")) )"
        . " - ( MID(CURRENT_DATE,5,6)"
        . " < MID(FROM_UNIXTIME(".$atts['list_type']."),5,6) )"
		. $visibilityfilter
		. " ORDER BY FROM_UNIXTIME(".$atts['list_type'].") ASC";

	$results = $wpdb->get_results($sql);
	
	if ($results != null) {
		$out = "<div class='connections-list' style='-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; position: relative;'>\n";
		$out .= "<div class='cn_list_title' style='font-size: large; font-variant: small-caps; font-weight: bold; text-align:center;'>" . $list_title  . "</div>";
		
		
		/*The SQL returns an array sorted by the birthday and/or anniversary date. However the year end wrap needs to be accounted for.
		Otherwise earlier months of the year show before the later months in the year. Example Jan before Dec. The desired output is to show
		Dec then Jan dates.  This function checks to see if the month is a month earlier than the current month. If it is the year is changed to the following year rather than the current.
		After a new list is built, it is resorted based on the date.*/
		foreach ($results as $row) {
			if ($row->$atts['list_type']) {
				if (date("m", $row->$atts['list_type']) <= date("m") && date("d", $row->$atts['list_type']) < date("d")) {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y") . " + 1 year");
				} else {
					$current_date = strtotime(date("d", $row->$atts['list_type']) . '-' . date("m", $row->$atts['list_type']) . '-' . date("Y"));
				}
				if (!$atts['show_lastname']) {
					$upcoming_list["<span class='name'>" . $row->first_name . " " . $row->last_name{0} . ".</span>"] .= $current_date;
				} else {
					$upcoming_list["<span class='name'>" . $row->first_name . " " . $row->last_name . "</span>"] .= $current_date;
				}
			}
		}
		asort($upcoming_list);

		foreach ($upcoming_list as $key=>$value) {
		
			if (!$setstyle == "alternate") {
				$setstyle = "alternate";
				$alternate = "background-color:#F9F9F9; ";
			} else {
				$setstyle = "";
				$alternate = "";
			}
			
				$out .= "<div class='cn_row' style='" . $alternate . "padding:2px 4px;'>" . $key . " <span class='cn_date' style='position: absolute; right:4px;'>" . date($atts['date_format'],$value) . "</span></div>";
		}
		
		$out .= "</div>\n";
		return $out;
	}
}
?>