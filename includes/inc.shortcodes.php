<?php
/**
 * Template tag to call the entry list. All options can be passed as an
 * associative array. The options are identical to those available to the
 * shortcode.
 * 
 * EXAMPLE:   connectionsEntryList(array('id' => 325));
 * 
 * @param array $atts
 * @return string
 */
function connectionsEntryList($atts)
{
	echo _connections_list($atts);
}

add_shortcode('connections_list', '_connections_list');
function _connections_list($atts, $content=null) {
	global $wpdb, $connections, $current_user;
	
	$form = new cnFormObjects();
	$convert = new cnFormatting();
	$format =& $convert;
	
	$atts = shortcode_atts( array(
				'id' => null,
				'category' => null,
				'wp_current_category' => 'false',
				'allow_public_override' => 'false',
				'private_override' => 'false',
				'show_alphaindex' => 'false',
				'repeat_alphaindex' => 'false',
				'show_alphahead' => 'false',
				'list_type' => 'all',
				'order_by' => null,
				'group_name' => null,
				'last_name' => null,
				'title' => null,
				'organization' => null,
				'department' => null,
				'city' => null,
				'state' => null,
				'zip_code' => null,
				'country' => null,
				'template_name' => 'card'
				), $atts ) ;
				
	/*
	 * Convert some of the $atts values in the array to boolean.
	 */
	$convert->toBoolean(&$atts['allow_public_override']);
	$convert->toBoolean(&$atts['private_override']);
	$convert->toBoolean(&$atts['show_alphaindex']);
	$convert->toBoolean(&$atts['repeat_alphaindex']);
	$convert->toBoolean(&$atts['show_alphahead']);
	$convert->toBoolean(&$atts['wp_current_category']);
	
	// First check to see if the template is in the custom template folder.
	if (is_dir(WP_CONTENT_DIR . '/connections_templates'))
	{
		if (file_exists(WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php'))
		{
			$template = WP_CONTENT_DIR . '/connections_templates/' .  $atts['template_name'] . '.php';
		}
	}
	
	// If the template isn't a custom template, check for it in the default templates folder.
	if ( !isset($template) )
	{
		if (file_exists(WP_PLUGIN_DIR . '/connections/templates/' .  $atts['template_name'] . '.php'))
		{
			$template = WP_PLUGIN_DIR . '/connections/templates/' .  $atts['template_name'] . '.php';
		}
		
	}
	
	$results = $connections->retrieve->entries($atts);
	$connections->filter->permitted(&$results, $atts['allow_public_override'], $atts['private_override']);
	
	//print_r($connections->lastQuery);
	
	if (!empty($atts['order_by']) && !empty($results))
	{
		$connections->filter->orderBy($results, $atts['order_by'], $atts['id']);
	}
	
	if ($results != NULL)
	{
		
		$out = '<a name="connections-list-head"></a>';
		/*
		 * The alpha index is only displayed if set set to true and not set to repeat using the shortcode attributes.
		 * If a alpha index is set to repeat, that is handled down separately.
		 */
		if ($atts['show_alphaindex'] && !$atts['repeat_alphaindex']) $out .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . $form->buildAlphaIndex(). "</div>";
		
		$out .=  "<div class='connections-list'>\n";
		
		foreach ($results as $row)
		{
			//$entry = new cnOutput($row);
			$entry = new cnvCard($row);
			//$vCard = new cnvCard($row);
			$vCard =& $entry;
			
			if (isset($continue)) unset($continue);
			if (isset($cities)) unset($cities);
			if (isset($states)) unset($states);
			if (isset($zipcodes)) unset($zipcodes);
			if (isset($countries)) unset($countries);
			if (isset($setAnchor)) unset($setAnchor);
			
			/*
			 * Check to make sure there is data stored in the address array.
			 * Cycle thru each address, building separate arrays for city, state, zip and country.
			 */
			if ($entry->getAddresses())
			{
				foreach ($entry->getAddresses() as $address)
				{
					if ($address->city != NULL) $cities[] = $address->city;
					if ($address->state != NULL) $states[] = $address->state;
					if ($address->zipcode != NULL) $zipcodes[] = $address->zipcode;
					if ($address->country != NULL) $countries[] = $address->country;
				}			
			}
			
			/*
			 * Filter out the entries that are wanted based on the
			 * filter attributes that may have been used in the shortcode.
			 * 
			 * NOTE: The '@' operator is used to suppress PHP generated errors. This is done
			 * because not every entry will have addresses to populate the arrays created above.
			 * 
			 * NOTE: Since the entry class returns all fields escaped, the shortcode filter
			 * attribute needs to be escaped as well so the comparason bewteen the two functions
			 * as expected.
			 */
			$atts['group_name'] = esc_attr($atts['group_name']);
			$atts['last_name'] = esc_attr($atts['last_name']);
			$atts['title'] = esc_attr($atts['title']);
			$atts['organization'] = esc_attr($atts['organization']);
			$atts['department'] = esc_attr($atts['department']);
			
			//if ($atts['list_type'] != 'all' && $atts['list_type'] != $entry->getEntryType())			$continue = true;
			if ($entry->getGroupName() != $atts['group_name'] && $atts['group_name'] != null)			$continue = true;
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
				if ($atts['show_alphaindex']) $setAnchor = '<a name="' . $currentLetter . '"></a>';
				
				if ($atts['show_alphaindex'] && $atts['repeat_alphaindex']) $setAnchor .= "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . $form->buildAlphaIndex() . "</div>";
				
				if ($atts['show_alphahead']) $setAnchor .= '<h4 class="cn-alphahead">' . $currentLetter . '</h4>';
				$previousLetter = $currentLetter;
			} else {
				$setAnchor = null;
			}
			
			/*
			 * The anchor and/or the alpha head is displayed if set to true using the shortcode attributes.
			 */
			if ($atts['show_alphaindex'] || $atts['show_alphahead']) $out .= $setAnchor;
			
			
			if (isset($template))
			{
				$out .= '<div class="vcard ' . $entry->getCategoryClass(TRUE) . '">' . "\n";
					ob_start();
				    include($template);
				    $out .= ob_get_contents();
				    ob_end_clean();
				$out .= '</div>' . "\n";
			}
			else
			{
				// If no template is found, return an error message.
				return '<p style="color:red; font-weight:bold; text-align:center;">ERROR: Template "' . $atts['template_name'] . '" not found.</p>';
			}
						
		}
		$out .= "</div>\n";
	}
	return $out;
	
}

/**
 * Template tag to call the upcoming list. All options can be passed as an
 * associative array. The options are identical to those available to the
 * shortcode.
 * 
 * EXAMPLE:   connectionsUpcomingList(array('days' => 30));
 * 
 * @param array $atts
 * @return string
 */
function connectionsUpcomingList($atts)
{
	echo _upcoming_list($atts);
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