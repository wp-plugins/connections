<?php

function processEntry()
{
	global $wpdb, $connections;
	$entry = new cnEntry();
	//$category = new cnCategory();
	
	// If copying/editing an entry, the entry data is loaded into the class 
	// properties and then properties are overwritten by the POST data as needed.
	if (isset($_GET['id']))
	{
		$entry->set($_GET['id']);
	}
						
	$entry->setEntryType($_POST['entry_type']);
	$entry->setGroupName($_POST['connection_group_name']);
	$entry->setConnectionGroup($_POST['connection_group']);
	$entry->setFirstName($_POST['first_name']);
	$entry->setMiddleName($_POST['middle_name']);
	$entry->setLastName($_POST['last_name']);
	$entry->setTitle($_POST['title']);
	$entry->setOrganization($_POST['organization']);
	$entry->setDepartment($_POST['department']);
	$entry->setContactFirstName($_POST['contact_first_name']);
	$entry->setContactLastName($_POST['contact_last_name']);
	$entry->setAddresses($_POST['address']);
	$entry->setPhoneNumbers($_POST['phone_numbers']);
	$entry->setEmailAddresses($_POST['email']);
	$entry->setIm($_POST['im']);
	$entry->setSocialMedia($_POST['social_media']);
	$entry->setWebsites($_POST['websites']);
	$entry->setBirthday($_POST['birthday_day'], $_POST['birthday_month']);
	$entry->setAnniversary($_POST['anniversary_day'], $_POST['anniversary_month']);
	$entry->setBio($_POST['bio']);
	$entry->setNotes($_POST['notes']);
	$entry->setVisibility($_POST['visibility']);
									
	if ($_FILES['original_image']['error'] != 4) {
		
		$image_proccess_results = processImages();
		
		if ($image_proccess_results)
		{
			$entry->setImageLinked(true);
			$entry->setImageDisplay(true);
			$entry->setImageNameThumbnail($image_proccess_results['image_names']['thumbnail']);
			$entry->setImageNameCard($image_proccess_results['image_names']['entry']);
			$entry->setImageNameProfile($image_proccess_results['image_names']['profile']);
			$entry->setImageNameOriginal($image_proccess_results['image_names']['original']);
		}
		else
		{
			$entry->setImageLinked(false);
			$entry->setImageDisplay(false);
		}
	}
	
	// If copying an entry, the image visibility property is set based on the user's choice.
	// NOTE: This must come after the image processing.
	if (isset($_POST['imgOptions']))
	{
		switch ($_POST['imgOptions'])
		{
			case 'remove':
				$entry->setImageDisplay(false);
				$entry->setImageLinked(false);
				
				/** @TODO remove the images from the server **/
			break;
			
			case 'hidden':
				$entry->setImageDisplay(false);
			break;
			
			case 'show':
				$entry->setImageDisplay(true);
			break;
			
			default:
				$entry->setImageDisplay(false);
			break;
		}
	}
	
	switch ($_GET['action'])
	{
		case 'add':
			if ($entry->save() == FALSE)
			{
				$connections->setErrorMessage('entry_added');
				return FALSE;
			}
			else
			{
				$connections->setSuccessMessage('entry_added');
				$entryID = (int) $wpdb->insert_id;
			}
		break;
		
		case 'update':
			if ($entry->update() == FALSE)
			{
				$connections->setErrorMessage('entry_updated');
				return FALSE;
			}
			else
			{
				$connections->setSuccessMessage('entry_updated');
				$entryID = (int) $entry->getId();
			}
		break;
	}
						
	$connections->term->setTermRelationships($entryID, $_POST['entry_category'], 'category');
		
	unset($entry);
	return TRUE;
	
}

function processImages()
{
	global $connections;
	
	// Uses the upload.class.php to handle file uploading and image manipulation.
	
	$process_image = new Upload($_FILES['original_image']);
	$image['source'] = $process_image->file_src_name_body;
	
	if ($process_image->uploaded) {
		// Saves the uploaded image with no changes to the wp_content/connection_images/ dir.
		// If needed this will create the upload dir and chmod it.
		$process_image->allowed				= array('image/jpeg','image/gif','image/png');
		$process_image->auto_create_dir		= true;
		$process_image->auto_chmod_dir		= true;
		$process_image->file_safe_name		= true;
		$process_image->file_auto_rename	= true;
		$process_image->file_name_body_add= '_original';
		$process_image->image_convert		= 'jpg';
		$process_image->jpeg_quality		= 80;
		$process_image->Process(CN_IMAGE_PATH);
		if ($process_image->processed) {
			$connections->setSuccessMessage('image_uploaded');
			$image['original'] = $process_image->file_dst_name;
		} else {
			$connections->setErrorMessage('image_uploaded_failed');
			//$error .= "<p><strong>Error: </strong>" . $process_image->error . "</p> \n";
			$error = TRUE;
		}
		
		// Creates the profile image and saves it to the wp_content/connection_images/ dir.
		// If needed this will create the upload dir and chmod it.
		$process_image->allowed				= array('image/jpeg','image/gif','image/png');
		$process_image->auto_create_dir		= true;
		$process_image->auto_chmod_dir		= true;
		$process_image->file_safe_name		= true;
		$process_image->file_auto_rename	= true;
		$process_image->file_name_body_add= '_profile';
		$process_image->image_convert		= 'jpg';
		$process_image->jpeg_quality		= $connections->options->getImgProfileQuality();
		$process_image->image_resize		= true;
		$process_image->image_ratio_crop	= (bool) $connections->options->getImgProfileRatioCrop();
		$process_image->image_ratio_fill	= (bool) $connections->options->getImgProfileRatioFill();
		$process_image->image_y				= $connections->options->getImgProfileY();
		$process_image->image_x				= $connections->options->getImgProfileX();
		$process_image->Process(CN_IMAGE_PATH);
		if ($process_image->processed) {
			$connections->setSuccessMessage('image_profile');
			$image['profile'] = $process_image->file_dst_name;
		} else {
			$connections->setErrorMessage('image_profile_failed');
			//$error .= "<p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			$error = TRUE;
		}						
		
		// Creates the entry image and saves it to the wp_content/connection_images/ dir.
		// If needed this will create the upload dir and chmod it.
		$process_image->allowed				= array('image/jpeg','image/gif','image/png');
		$process_image->auto_create_dir		= true;
		$process_image->auto_chmod_dir		= true;
		$process_image->file_safe_name		= true;
		$process_image->file_auto_rename	= true;
		$process_image->file_name_body_add= '_entry';
		$process_image->image_convert		= 'jpg';
		$process_image->jpeg_quality		= $connections->options->getImgEntryQuality();
		$process_image->image_resize		= true;
		$process_image->image_ratio_crop	= (bool) $connections->options->getImgEntryRatioCrop();
		$process_image->image_ratio_fill	= (bool) $connections->options->getImgEntryRatioFill();
		$process_image->image_y				= $connections->options->getImgEntryY();
		$process_image->image_x				= $connections->options->getImgEntryX();
		$process_image->Process(CN_IMAGE_PATH);
		if ($process_image->processed) {
			$connections->setSuccessMessage('image_entry');
			$image['entry'] = $process_image->file_dst_name;
		} else {
			$connections->setErrorMessage('image_entry_failed');
			//$error .= "<p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			$error = TRUE;
		}
		
		// Creates the thumbnail image and saves it to the wp_content/connection_images/ dir.
		// If needed this will create the upload dir and chmod it.
		$process_image->allowed				= array('image/jpeg','image/gif','image/png');
		$process_image->auto_create_dir		= true;
		$process_image->auto_chmod_dir		= true;
		$process_image->file_safe_name		= true;
		$process_image->file_auto_rename	= true;
		$process_image->file_name_body_add= '_thumbnail';
		$process_image->image_convert		= 'jpg';
		$process_image->jpeg_quality		= $connections->options->getImgThumbQuality();
		$process_image->image_resize		= true;
		$process_image->image_ratio_crop	= (bool) $connections->options->getImgThumbRatioCrop();
		$process_image->image_ratio_fill	= (bool) $connections->options->getImgThumbRatioFill();
		$process_image->image_y				= $connections->options->getImgThumbY();
		$process_image->image_x				= $connections->options->getImgThumbX();
		$process_image->Process(CN_IMAGE_PATH);
		if ($process_image->processed) {
			$connections->setSuccessMessage('image_thumbnail');
			$image['thumbnail'] = $process_image->file_dst_name;
		} else {
			$connections->setErrorMessage('image_thumbnail_failed');
			//$error .= "<p><strong>Error:</strong> " . $process_image->error . "</p> \n";
			$error = TRUE;
		}
		
		$process_image->Clean();
		
	} else {
		$connections->setErrorMessage('image_upload_failed');
		//$error = "<p><strong>Error: </strong>" . $process_image->error . "</p> \n";
		$error = TRUE;
	}
	
	if (!$error)
	{
		return array('image_names'=>$image);
	}
	else
	{
		return FALSE;
	}
}

?>