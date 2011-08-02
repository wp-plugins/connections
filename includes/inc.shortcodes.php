<?php
/**
 * Template tag to call the entry list. All options can be passed as an
 * associative array. The options are identical to those available to the
 * shortcode.
 * 
 * EXAMPLE:   connectionsEntryList( array('id' => 325) );
 * 
 * @param array $atts
 * @return string
 */
function connectionsEntryList($atts)
{
	echo _connections_list($atts);
}

/**
 * Register the [connections] shortcode
 * 
 * Filters:
 * 		cn_list_template_init			=> Change the list type [affects the default loaded template] or template to be loaded and intialized.
 * 										   The shortcode atts are passed, However the associative array will be limited to list_type and template so only these values can / should be altered.
 * 		cn_list_atts_permitted			=> The permitted shortcode attributes validated using the WordPress function shortcode_atts().
 * 										   The permitted shortcode associative array is passed. Return associative array.
 * 		cn_list_atts					=> Alter the shortcode attributes before validation via the WordPress function shortcode_atts().
 * 										   The shortcode atts are passed. Return associative array.
 * 		cn_list_retrieve_atts			=> Alter the query attributes to be used.
 * 										   The shortcode atts are passed. however the retrieve method will filter and use only the valid atts. Return associative array.
 * 		cn_list_results					=> Filter the returned results before being processed for display. Return indexed array of entry objects.
 * 		cn_list_before					=> Can be used to add content before the output of the list.
 * 										   The entry list results are passed. Return string.
 * 		cn_list_after					=> Can be used to add content after the output of the list.
 * 										   The entry list results are passed. Return string.
 * 		cn_list_entry_before			=> Can be used to add content before the output of the entry.
 * 										   The entry data is passed. Return string.
 * 		cn_list_entry_after				=> Can be used to add content after the output of the entry.
 * 										   The entry data is passed. Return string.
 * 		cn_list_no_result_message		=> Change the 'no results message'.
 * 		cn_list_index					=> Can be used to modify the index before the output of the list.
 * 										   The entry list results are passed. Return string.
 * 
 * Actions:
 * 		cn_list_retrieve_pre			=> Action is run prior to running the retrieve query.
 * 										   The shortcode atts are passed. 
 */
add_shortcode('connections_list', '_connections_list'); /** @deprecated since version 0.7.0.4 */
add_shortcode('connections', '_connections_list'); /** @since version 0.7.1.0 */
function _connections_list($atts, $content = NULL)
{
	global $wpdb, $current_user, $connections, $template;
	
	$out = '';
	$form = new cnFormObjects();
	$convert = new cnFormatting();
	$format =& $convert;
	//$template = new stdClass();
	
	$previousLetter = '';
	$alternate = '';
	
	/*
	 * Parse the user supplied shortcode atts for the vlues only required to load the template.
	 * This will permit templates to apply a filter to alter the permitted shortcode atts.
	 */
	$preLoadAtts = $atts;
	
	$preLoadAtts = apply_filters( 'cn_list_template_init', $preLoadAtts);
	
	$preLoadAtts = shortcode_atts( array(
				'list_type' => NULL,
				'template' => NULL, /** @since version 0.7.1.0 */
				'template_name' => NULL /** @deprecated since version 0.7.0.4 */
				), $preLoadAtts ) ;
	
	
	$permittedListTypes = array('individual', 'organization', 'family', 'connection_group');
	
	// Convert the supplied entry types $atts['list_type'] to an array.
	if ( !empty($preLoadAtts['list_type']) )
	{
		// Trim the space characters if present.
		$preLoadAtts['list_type'] = str_replace(' ', '', $preLoadAtts['list_type']);
		
		// Convert to array.
		$preLoadAtts['list_type'] = explode(',', $preLoadAtts['list_type']);
	}
	
	// Set the template type to the first in the entry type from the supplied if multiple list types are provided.
	if ( !empty($preLoadAtts['list_type']) && (bool) array_intersect( (array) $preLoadAtts['list_type'], $permittedListTypes) )
	{
		$templateType = $preLoadAtts['list_type'][0];
	}
	
	/*
	 * As of version 0.7.0.5 the $atts['template_name'] is deprecated.
	 */
	if ( isset($preLoadAtts['template_name']) )
	{
		// First check to see if the template is in the custom template folder.
		if ( is_dir(CN_CUSTOM_TEMPLATE_PATH) && is_readable(CN_CUSTOM_TEMPLATE_PATH) )
		{
			if (file_exists(CN_CUSTOM_TEMPLATE_PATH . '/' .  $preLoadAtts['template_name'] . '.php'))
			{
				$template->file = CN_CUSTOM_TEMPLATE_PATH . '/' .  $preLoadAtts['template_name'] . '.php';
			}
		}
		
		// If the template is not in the custom template folder, check for it in the default template folder.
		if ( !isset($template->file) )
		{
			if (file_exists(CN_BASE_PATH . '/templates/' .  $preLoadAtts['template_name'] . '.php'))
			{
				$template->file = CN_BASE_PATH . '/templates/' .  $preLoadAtts['template_name'] . '.php';
			}
			
		}
	}
	else
	{
		if ( ! is_object($template) ) $template = new cnTemplate();
		
		// Change the list type to family from connection_group to maintain compatibility with versions 0.7.0.4 and earlier.
		if ( $preLoadAtts['list_type'] === 'connection_group' ) $preLoadAtts['list_type'] = 'family';
		
		/*
		 * $atts['template'] can be either a string or an object. It is a string when set
		 * with the shortcode attribute. If it is a string, the template will be loaded
		 * via the cnTemplate class.
		 * 
		 * If the attribute is not set, it will be the object returned from the
		 * cnOptions::getActiveTemplate() method which stores the default template
		 * per list style.
		 */
		if ( isset($preLoadAtts['template']) && ! is_object($preLoadAtts['template']) )
		{
			$template->load($atts['template']);
			$template->includeFunctions();
			
			//do_action( 'cn_list_template_defaults' );
		}
		else
		{
			if ( empty($templateType) ) $templateType = 'all'; // If no list type was specified, set the default ALL template.
			
			$template->init( $connections->options->getActiveTemplate( $templateType ) );
			$template->includeFunctions();
			/*if ( isset($template->phpPath) )
			{
				include_once($template->phpPath);
			}*/
			//do_action( 'cn_list_template_defaults' );
			
			//print_r('<pre>');
			/*$i = $connections->temp;
			$i++;
			$connections->temp = $i;
			echo $connections->temp;*/
			//echo $template->test('Error?');
			//print_r($template);
			//print_r($template->properties);
			//print_r('</pre>');
			
		}
	}
	
	// If no template is found, return an error message.
	if ( !isset($template->file) ) return '<p style="color:red; font-weight:bold; text-align:center;">ERROR: Template "' . $preLoadAtts['template_name'] . $preLoadAtts['template'] . '" not found.</p>';
	
	/*
	 * Now that the template has been loaded, Validate the user supplied shortcode atts.
	 */
	$permittedAtts = array(
							'id' => NULL,
							'category' => NULL,
							'category_in' => NULL,
							'exclude_category' => NULL,
							'category_name' => NULL,
							'wp_current_category' => 'false',
							'allow_public_override' => 'false',
							'private_override' => 'false',
							'show_alphaindex' => 'false',
							'repeat_alphaindex' => 'false',
							'show_alphahead' => 'false',
							'list_type' => NULL,
							'limit' => NULL,
							'offset' => NULL,
							'order_by' => NULL,
							'group_name' => NULL,
							'last_name' => NULL,
							'title' => NULL,
							'organization' => NULL,
							'department' => NULL,
							'city' => NULL,
							'state' => NULL,
							'zip_code' => NULL,
							'country' => NULL,
							'template' => NULL, /** @since version 0.7.1.0 */
							'template_name' => NULL /** @deprecated since version 0.7.0.4 */
						);
	
	$permittedAtts = apply_filters( 'cn_list_atts_permitted', $permittedAtts);
	
	
	$atts = shortcode_atts( $permittedAtts, $atts ) ;
	$atts = apply_filters( 'cn_list_atts', $atts);
	
	
	$template->importTemplateProperties();
	
	/*foreach ( $atts as $propertyName => $value )
	{
		$template->$propertyName = $value;
	}*/
	
	/*$properties = apply_filters('cn_init_template_properties', $properties);
		
	foreach ( $properties as $propertyName => $value )
	{
		$template->$propertyName = $value;
	}*/
	
	//$template->strVisitWebsite = 'My Home on the Web.';
	
	/*
	 * Convert some of the $atts values in the array to boolean.
	 */
	$convert->toBoolean($atts['allow_public_override']);
	$convert->toBoolean($atts['private_override']);
	$convert->toBoolean($atts['show_alphaindex']);
	$convert->toBoolean($atts['repeat_alphaindex']);
	$convert->toBoolean($atts['show_alphahead']);
	$convert->toBoolean($atts['wp_current_category']);
	
	
	$atts = apply_filters('cn_list_retrieve_atts', $atts);
	//$template->initTemplateMethods();
	//do_action('cn_list_retrieve_pre', $atts);
	
	//print_r('<pre>');
	//print_r($atts);
	//print_r($template);
	//print_r('</pre>');
	
	$results = $connections->retrieve->entries($atts);
	
	//print_r($connections->lastQuery);
	
	/*
	 * Filter out the entries that are not wanted based on the
	 * filter attributes that may have been used in the shortcode.
	 */
	foreach ( (array) $results as $key => $row)
	{
		$entry = new cnEntry($row);
		
		$continue = FALSE;
		$cities = array();
		$states = array();
		$zipcodes = array();
		$countries = array();
		
		/*
		 * Check to make sure there is data stored in the address array.
		 * Cycle thru each address, building separate arrays for city, state, zip and country.
		 */
		if ($entry->getAddresses())
		{
			foreach ($entry->getAddresses() as $address)
			{
				//if ($address->city != NULL) $cities[] = trim($address->city, '  \t\n\r\0\x0B');
				if ($address->city != NULL) $cities[] = $address->city;
				if ($address->state != NULL) $states[] = $address->state;
				if ($address->zipcode != NULL) $zipcodes[] = $address->zipcode;
				if ($address->country != NULL) $countries[] = $address->country;
				//echo ord(' '); This is character 194, I think used as a fix to trim the character off the city name.
			}			
		}
		
		/*
		 * NOTE: The '@' operator is used to suppress PHP generated errors. This is done
		 * because not every entry will have addresses to populate the arrays created above.
		 * 
		 * NOTE: Since the entry class returns all fields escaped, the shortcode filter
		 * attribute needs to be escaped as well so the comparason between the two functions
		 * as expected.
		 */
		$atts['group_name'] = esc_attr($atts['group_name']);
		$atts['last_name'] = esc_attr($atts['last_name']);
		$atts['title'] = esc_attr($atts['title']);
		$atts['organization'] = esc_attr($atts['organization']);
		$atts['department'] = esc_attr($atts['department']);
		
		if ($entry->getFamilyName() != $atts['group_name'] && $atts['group_name'] != NULL)			$continue = TRUE;
		if ($entry->getLastName() != $atts['last_name'] && $atts['last_name'] != NULL)				$continue = TRUE;
		if ($entry->getTitle() != $atts['title'] && $atts['title'] != NULL)							$continue = TRUE;
		if ($entry->getOrganization() != $atts['organization'] && $atts['organization'] != NULL) 	$continue = TRUE;
		if ($entry->getDepartment() != $atts['department'] && $atts['department'] != NULL) 			$continue = TRUE;
		if (@!in_array($atts['city'], $cities) && $atts['city'] != NULL) 							$continue = TRUE;
		if (@!in_array($atts['state'], $states) && $atts['state'] != NULL) 							$continue = TRUE;
		if (@!in_array($atts['zip_code'], $zipcodes) && $atts['zip_code'] != NULL) 					$continue = TRUE;
		if (@!in_array($atts['country'], $countries) && $atts['country'] != NULL) 					$continue = TRUE;
		
		// If any of the above filters returned true, remove the entry from the results.
		if ($continue == TRUE) unset($results[$key]);
		
		// Update the result count.
		$connections->resultCount = count($results);
	}
	
	if ( !empty($results) ) $results = apply_filters('cn_list_results', $results);
	
	// Prints the template's CSS file.
	if ( method_exists($template, 'printCSS') ) $out .= $template->printCSS();
	
	// Prints the javascript tag in the footer if $template->js path is set
	if ( method_exists($template, 'printJS') ) $template->printJS();
	
	//$out = apply_filters('cn_list_before', $out, $results);
	
	// If there are no results no need to proceed and output message.
	if ( empty($results) )
	{
		$noResultMessage = 'No results';
		$noResultMessage = apply_filters('cn_list_no_result_message', $noResultMessage);
		return $out . '<p class="cn-list-no-results">' . $noResultMessage . '</p>';
	}
	
	//$out .= '<a name="connections-list-head" style="float: left;"></a>' . "\n";
	
	$out .=  '<div class="connections-list ' . $template->slug . '" id="connections-list-head" data-connections-version="' . $connections->options->getVersion() . '-' . $connections->options->getDBVersion() . '">';

	$out = apply_filters('cn_list_before', $out, $results);
	
	
	/*
	 * The alpha index is only displayed if set set to true and not set to repeat using the shortcode attributes.
	 * If a alpha index is set to repeat, that is handled down separately.
	 */
	if ($atts['show_alphaindex'] && !$atts['repeat_alphaindex'])
	{
		$index = "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . $form->buildAlphaIndex(). "</div>";
		$index = apply_filters('cn_list_index', $index, $results);
		
		$out .= $index;
	}
	
	
	foreach ( (array) $results as $row)
	{
		//$entry = new cnOutput($row);
		$entry = new cnvCard($row);
		//$vCard = new cnvCard($row);
		$vCard =& $entry;
		$repeatIndex = '';

		/*
		 * Checks the first letter of the last name to see if it is the next
		 * letter in the alpha array and sets the anchor.
		 * 
		 * If the alpha index is set to repeat it will append to the anchor.
		 * 
		 * If the alpha head set to true it will append the alpha head to the anchor.
		 */
		$currentLetter = strtoupper(mb_substr($entry->getSortColumn(), 0, 1));
		
		if ($currentLetter != $previousLetter && $atts['id'] == NULL)
		{
			if ($atts['show_alphaindex']) $setAnchor = '<a class="cn-index-head" name="' . $currentLetter . '"></a>';
			
			if ($atts['show_alphaindex'] && $atts['repeat_alphaindex'])
			{
				$repeatIndex = "<div class='cn-alphaindex' style='text-align:right;font-size:larger;font-weight:bold'>" . $form->buildAlphaIndex() . "</div>";
				$repeatIndex = apply_filters('cn_list_index', $repeatIndex, $results);
			}
			
			if ($atts['show_alphahead']) $setAnchor .= '<h4 class="cn-alphahead">' . $currentLetter . '</h4>';
			
			$previousLetter = $currentLetter;
		}
		else
		{
			$setAnchor = '';
		}
		
		/*
		 * The anchor and/or the alpha head is displayed if set to true using the shortcode attributes.
		 */
		if ($atts['show_alphaindex'] || $atts['show_alphahead']) $out .= $setAnchor . $repeatIndex;
		
		$alternate == '' ? $alternate = '-alternate' : $alternate = '';
		
		
		$out .= '<div class="cn-list-row' . $alternate . ' vcard ' . $entry->getCategoryClass(TRUE) . '">' . "\n";
			$out = apply_filters('cn_list_entry_before', $out, $entry);
			ob_start();
			include($template->file);
		    $out .= ob_get_contents();
		    ob_end_clean();
			$out = apply_filters('cn_list_entry_after', $out, $entry);
		$out .= '</div>' . "\n";
					
	}
	$out .= '<div class="clear"></div>' . "\n";
	$out .= '</div>' . "\n";
	
	$out = apply_filters('cn_list_after', $out, $results);
	
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
    global $connections, $wpdb;
	
	$template = new cnTemplate();
	$convert = new cnFormatting();
	
	$atts = shortcode_atts( array(
			'list_type' => 'birthday',
			'days' => '30',
			'include_today' => TRUE,
			'private_override' => FALSE,
			'date_format' => 'F jS',
			'show_lastname' => FALSE,
			'show_title' => TRUE,
			'list_title' => NULL,
			'template' => $connections->options->getActiveTemplate('birthday')
			), $atts ) ;
	
	/*
	 * Convert some of the $atts values in the array to boolean.
	 */
	$convert->toBoolean(&$atts['include_today']);
	$convert->toBoolean(&$atts['private_override']);
	$convert->toBoolean(&$atts['show_lastname']);
	$convert->toBoolean(&$atts['repeat_alphaindex']);
	$convert->toBoolean(&$atts['show_title']);
	
	if (is_user_logged_in() || $atts['private_override'] != FALSE) { 
		$visibilityfilter = " AND (visibility='private' OR visibility='public') AND (".$atts['list_type']." != '')";
	} else {
		$visibilityfilter = " AND (visibility='public') AND (`".$atts['list_type']."` != '')";
	}
	
	if ($atts['list_title'] == NULL)
	{
		switch ($atts['list_type'])
		{
			case 'birthday':
				if ( $atts['days'] >= 1 )
				{
					$list_title = 'Upcoming Birthdays the next ' . $atts['days'] . ' days';
				}
				else
				{
					$list_title = 'Today\'s Birthdays';
				}
			break;
			
			case 'anniversary':
				if ( $atts['days'] >= 1 )
				{
					$list_title = 'Upcoming Anniversaries the next ' . $atts['days'] . ' days';
				}
				else
				{
					$list_title = 'Today\'s Anniversaries';
				}
			break;
		}
	}
	else
	{
		$list_title = $atts['list_title'];
	}
	
	/*
	 * $atts['template'] can be either a string or an object. It is a string when set
	 * with the shortcode attribute. If it is a string, the template will be loaded
	 * via the cnTemplate class.
	 * 
	 * If the attribute is not set, it will be the object returned from the
	 * cnOptions::getActiveTemplate() method which stores the default template
	 * per list style.
	 */
	if ( isset($atts['template']) && !is_object($atts['template']) )
	{
		$template->load($atts['template']);
		$template->includeFunctions();
	}
	else
	{
		$template->init( $connections->options->getActiveTemplate( $atts['list_type'] ) );
		$template->includeFunctions();
	}
		
	/* Old and busted query!2
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
	*/
	
	// Get the current date from WP which should have the current time zone offset.
	$wpCurrentDate = date( 'Y-m-d', $connections->options->wpCurrentTime );
	
	( $atts['include_today'] ) ? $includeToday = '<=' : $includeToday = '<';
	
	/*
	 * 
	 */
	$newSQL = "SELECT * FROM ".CN_ENTRY_TABLE." WHERE"
		. "  (YEAR(DATE_ADD('$wpCurrentDate', INTERVAL ".$atts['days']." DAY))"
        . " - YEAR(DATE_ADD(FROM_UNIXTIME(`".$atts['list_type']."`), INTERVAL ".$connections->options->sqlTimeOffset." SECOND)) )"
        . " - ( MID(DATE_ADD('$wpCurrentDate', INTERVAL ".$atts['days']." DAY),5,6)"
        . " < MID(DATE_ADD(FROM_UNIXTIME(`".$atts['list_type']."`), INTERVAL ".$connections->options->sqlTimeOffset." SECOND),5,6) )"
        . " > ( YEAR('$wpCurrentDate')"
        . " - YEAR(DATE_ADD(FROM_UNIXTIME(`".$atts['list_type']."`), INTERVAL ".$connections->options->sqlTimeOffset." SECOND)) )"
        . " - ( MID('$wpCurrentDate',5,6)"
        . " ".$includeToday." MID(DATE_ADD(FROM_UNIXTIME(`".$atts['list_type']."`), INTERVAL ".$connections->options->sqlTimeOffset." SECOND),5,6) )"
		. $visibilityfilter;
	
	$results = $wpdb->get_results($newSQL);
	
	// If there are no results no need to proceed and output message.
	if ( empty($results) )
	{
		$noResultMessage = 'No results';
		$noResultMessage = apply_filters('cn_upcoming_no_result_message', $noResultMessage);
		return '<p class="cn-upcoming-no-results">' . $noResultMessage . '</p>';
	}
	
	if ($results != NULL)
	{
		/*The SQL returns an array sorted by the birthday and/or anniversary date. However the year end wrap needs to be accounted for.
		Otherwise earlier months of the year show before the later months in the year. Example Jan before Dec. The desired output is to show
		Dec then Jan dates.  This function checks to see if the month is a month earlier than the current month. If it is the year is changed to the following year rather than the current.
		After a new list is built, it is resorted based on the date.*/
		foreach ($results as $key => $row)
		{
			if ( gmmktime(23, 59, 59, gmdate('m', $row->$atts['list_type']), gmdate('d', $row->$atts['list_type']), gmdate('Y', $connections->options->wpCurrentTime) ) < $connections->options->wpCurrentTime )
			{
				$dateSort[] = $row->$atts['list_type'] = gmmktime(0, 0, 0, gmdate('m', $row->$atts['list_type']), gmdate('d', $row->$atts['list_type']), gmdate('Y', $connections->options->wpCurrentTime) + 1 );
			}
			else
			{
				$dateSort[] = $row->$atts['list_type'] = gmmktime(0, 0, 0, gmdate('m', $row->$atts['list_type']), gmdate('d', $row->$atts['list_type']), gmdate('Y', $connections->options->wpCurrentTime) );
			}
		}
		
		array_multisort($dateSort, SORT_ASC, $results);
		
		
		$out = '';
		
		// Prints the template's CSS file.
		if ( method_exists($template, 'printCSS') ) $out .= $template->printCSS();
		
		// Prints the javascript tag in the footer if $template->js path is set
		if ( method_exists($template, 'printJS') ) $template->printJS();
		
		
		$out .= '<div class="connections-list cn-upcoming '. $atts['list_type'] . ' ' . $template->slug . '">' . "\n";
		if ( $atts['show_title'] ) $out .= '<div class="cn-upcoming-title">' . $list_title  . '</div>';
				
		foreach ($results as $row)
		{
			$entry = new cnvCard($row);
			$vCard =& $entry;
			
			$entry->name = '';
			
			$alternate == '' ? $alternate = '-alternate' : $alternate = '';
			
			!$atts['show_lastname'] ? $entry->name = $entry->getFirstName() : $entry->name = $entry->getFullFirstLastName();
			
			if (isset($template->file))
			{
				$out .= '<div class="cn-upcoming-row' . $alternate . ' vcard ' . '">' . "\n";
					ob_start();
					include($template->file);
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
		
		return $out;
	}
}
?>