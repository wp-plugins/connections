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
	
	public function addEntry($getData, $postData, $fileData)
	{
	//-->global$wpdb;
	//-->session_start();
		
		//This query is here to get an entry row data for when en entry is being copied.
		if ($getData['id']) {
			$row = $this->getEntry($getData['id']);
			$options = unserialize($row->options);
		}
		
	//-->$options['entry']['type'] = $postData['entry_type'];
		
		//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
	//-->$bdaydate = strtotime($postData['birthday_day'] . '-' . $postData['birthday_month'] . '-' . '1970 00:00:00');
		//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
	//-->$anndate = strtotime($postData['anniversary_day'] . '-' . $postData['anniversary_month'] . '-' . '1970 00:00:00');
		
	//-->$serial_addresses = serialize($postData['address']);
	//-->$serial_phone_numbers = serialize($postData['phone_numbers']);
	//-->$serial_email = serialize($postData['email']);
	//-->$serial_im = serialize($postData['im']);
	//-->$serial_websites = serialize($postData['websites']);
		
		if ($postData['website'] == "http://") $postData['website'] = "";
		
		/*if ($fileData['original_image']['error'] != 4) {
			$image_proccess_results = _process_images($fileData);
			$options['image']['name'] = $image_proccess_results['image_names'];
			$options['image']['linked'] = true;
			$options['image']['display'] = true;
			$options['image']['use'] = $image_proccess_results['image_names']['source'];
			$error = $image_proccess_results['error'];
			$success = $image_proccess_results['success'];
		}*/
		
	//-->$serial_options = serialize($options);
		
		/*$sql = "INSERT INTO ".$wpdb->prefix."connections SET
            first_name    = '".$wpdb->escape($postData['first_name'])."',
            last_name     = '".$wpdb->escape($postData['last_name'])."',
			title    	  = '".$wpdb->escape($postData['title'])."',
			organization  = '".$wpdb->escape($postData['organization'])."',
			department    = '".$wpdb->escape($postData['department'])."',
			visibility    = '".$wpdb->escape($postData['visibility'])."',
			birthday      = '".$wpdb->escape($bdaydate)."',
			anniversary   = '".$wpdb->escape($anndate)."',
			addresses     = '".$wpdb->escape($serial_addresses)."',
			phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
			email	      = '".$wpdb->escape($serial_email)."',
			im  	      = '".$wpdb->escape($serial_im)."',
			websites      = '".$wpdb->escape($serial_websites)."',
			options       = '".$wpdb->escape($serial_options)."',
			bio           = '".$wpdb->escape($postData['bio'])."',
            notes         = '".$wpdb->escape($postData['notes'])."'";*/
		
		/*if (!$error) {
			$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
			$out = '<div id="message" class="updated fade">';
				$out .= '<p><strong>Entry added.</strong></p>' . "\n";
				if ($image_proccess_results['success']) $out .= $success;
			$out .= '</div>';
		} else {
			$out .= '<div id="notice" class="error">';
				$out .= $error;
			$out .= '</div>';
		}*/
		
	//-->unset($_SESSION['formTokens']);
		
		return $out;
	}
	
	public function updateEntry($getData, $postData, $fileData)
	{
		global$wpdb;
		session_start();
		
		//This query is here to get an entry row data for when en entry is being edited.
		$row = $this->getEntry($getData['id']);
		
		$options = unserialize($row->options);
		$options['entry']['type'] = $postData['entry_type'];
	
		//I think I should set these to null if no value was input???
		//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them. Did this because most often people don't want to give the year.
		$bdaydate = strtotime($postData['birthday_day'] . '-' . $postData['birthday_month'] . '-' . '1970 00:00:00');
		//Create the birthday with a default year and time since we don't collect the year. And this is needed so a proper sort can be done when listing them.
		$anndate = strtotime($postData['anniversary_day'] . '-' . $postData['anniversary_month'] . '-' . '1970 00:00:00');
		
		$serial_addresses = serialize($postData['address']);
		$serial_phone_numbers = serialize($postData['phone_numbers']);
		$serial_email = serialize($postData['email']);
		$serial_im = serialize($postData['im']);
		$serial_websites = serialize($postData['websites']);					
		
		if ($fileData['original_image']['error'] != 4) {
			$image_proccess_results = _process_images($fileData);
			$options['image']['name'] = $image_proccess_results['image_names'];
			$options['image']['linked'] = true;
			$options['image']['display'] = true;
			$options['image']['use'] = $image_proccess_results['image_names']['source'];
			$error = $image_proccess_results['error'];
			$success = $image_proccess_results['success'];
		}
		
		if ($postData['imgOptions'] == "remove") {
			$options['image']['linked'] = false;
		}
		
		if ($postData['imgOptions'] == "hidden") {
			$options['image']['display'] = false;
		}
		
		if ($postData['imgOptions'] == "show") {
			$options['image']['display'] = true;
		}
		
		$serial_options = serialize($options);
	
		$sql = "UPDATE ".$wpdb->prefix."connections SET
			first_name    = '".$wpdb->escape($postData['first_name'])."',
			last_name     = '".$wpdb->escape($postData['last_name'])."',
			title    	  = '".$wpdb->escape($postData['title'])."',
			organization  = '".$wpdb->escape($postData['organization'])."',
			department    = '".$wpdb->escape($postData['department'])."',
			birthday      = '".$wpdb->escape($bdaydate)."',
			anniversary   = '".$wpdb->escape($anndate)."',
			addresses     = '".$wpdb->escape($serial_addresses)."',
			phone_numbers = '".$wpdb->escape($serial_phone_numbers)."',
			email	      = '".$wpdb->escape($serial_email)."',
			im  	      = '".$wpdb->escape($serial_im)."',
			websites      = '".$wpdb->escape($serial_websites)."',
			options       = '".$wpdb->escape($serial_options)."',
			bio           = '".$wpdb->escape($postData['bio'])."',
			notes         = '".$wpdb->escape($postData['notes'])."',
			visibility    = '".$wpdb->escape($postData['visibility'])."'
			WHERE id ='".$wpdb->escape($getData['id'])."'";
			
		if (!$error) {
			$wpdb->query($sql); //Writes the entry to the db if there were no errors with the image processing.
			$out = '<div id="message" class="updated fade">';
				$out .= '<p><strong>The entry has been updated.</strong></p>' . "\n";
				if ($image_proccess_results['success']) $out .= $success;
			$out .= '</div>';
		} else {
			$out .= '<div id="notice" class="error">';
				$out .= $error;
			$out .= '</div>';
		}
		
		unset($_SESSION['formTokens']);
		
		return $out;
	}
	
	public function getTableName()
	{
		global $wpdb;
		return $wpdb->prefix . CN_TABLE_NAME;
	}
}

?>