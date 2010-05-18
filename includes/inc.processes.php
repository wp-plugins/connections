<?php

function processAddEntry()
{
	global $wpdb, $connections;
	$entry = new cnEntry();
	
	// The modification file date that image will be deleted. to maintain compatibility with 0.6.2.1 and older.
	$compatiblityDate = mktime(0, 0, 0, 6, 1, 2010);
	
	// If copying/editing an entry, the entry data is loaded into the class 
	// properties and then properties are overwritten by the POST data as needed.
	if (isset($_GET['id']))
	{
		$entry->set(esc_attr($_GET['id']));
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
	$entry->setWebsites($_POST['website']);
	$entry->setBirthday($_POST['birthday_day'], $_POST['birthday_month']);
	$entry->setAnniversary($_POST['anniversary_day'], $_POST['anniversary_month']);
	$entry->setBio($_POST['bio']);
	$entry->setNotes($_POST['notes']);
	$entry->setVisibility($_POST['visibility']);
									
	if ($_FILES['original_image']['error'] != 4)
	{
		// If an entry is being updated and a new image is uploaded, the old images need to be deleted.
		if ($entry->getImageNameOriginal() != NULL)
		{
			if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameOriginal() ) )
			{
				unlink( CN_IMAGE_PATH . $entry->getImageNameOriginal() );
			}
		}
		
		if ($entry->getImageNameThumbnail() != NULL)
		{
			if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameThumbnail() ) )
			{
				unlink( CN_IMAGE_PATH . $entry->getImageNameThumbnail() );
				
			}
		}
		
		if ($entry->getImageNameCard() != NULL)
		{
			if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameCard() ) )
			{
				unlink( CN_IMAGE_PATH . $entry->getImageNameCard() );
			}
		}
		
		if ($entry->getImageNameProfile() != NULL)
		{
			if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameProfile() ) )
			{
				unlink( CN_IMAGE_PATH . $entry->getImageNameProfile() );
			}
		}
		
		// Process the newly uploaded image.
		$image_proccess_results = processImages();
		
		// If there were no errors processing the image, set the values.
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
	else
	{
		// Don't do this if an entry is being updated.
		if ( $_GET['action'] !== 'update' )
		{
			// If an entry is being copied and there is an image, the image will be duplicated for the new entry.
			// That way if an entry is deleted, only the entry specific images will be deleted.
			if ($entry->getImageNameOriginal() != NULL) $entry->setImageNameOriginal( copyImage( $entry->getImageNameOriginal() ) );
			if ($entry->getImageNameThumbnail() != NULL) $entry->setImageNameThumbnail( copyImage( $entry->getImageNameThumbnail() ) );
			if ($entry->getImageNameCard() != NULL) $entry->setImageNameCard( copyImage( $entry->getImageNameCard() ) );
			if ($entry->getImageNameProfile() != NULL) $entry->setImageNameProfile( copyImage( $entry->getImageNameProfile() ) );
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
				
				/*
				 * Delete images assigned to the entry.
				 * 
				 * Versions previous to 0.6.2.1 did not not make a duplicate copy of images when
				 * copying an entry so it was possible multiple entries could share the same image.
				 * Only images created after the date that version .0.7.0.0 was released will be deleted,
				 * plus a couple weeks for good measure.
				 */
				
				
				if ( is_file( CN_IMAGE_PATH . $entry->getImageNameOriginal() ) )
				{
					if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameOriginal() ) )
					{
						unlink( CN_IMAGE_PATH . $entry->getImageNameOriginal() );
					}
				}
				
				if ( is_file( CN_IMAGE_PATH . $entry->getImageNameThumbnail() ) )
				{
					if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameThumbnail() ) )
					{
						unlink( CN_IMAGE_PATH . $entry->getImageNameThumbnail() );
						
					}
				}
				
				if ( is_file( CN_IMAGE_PATH . $entry->getImageNameCard() ) )
				{
					if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameCard() ) )
					{
						unlink( CN_IMAGE_PATH . $entry->getImageNameCard() );
					}
				}
				
				if ( is_file( CN_IMAGE_PATH . $entry->getImageNameProfile() ) )
				{
					if ( $compatiblityDate < filemtime( CN_IMAGE_PATH . $entry->getImageNameProfile() ) )
					{
						unlink( CN_IMAGE_PATH . $entry->getImageNameProfile() );
					}
				}
				
				$entry->setImageNameOriginal(NULL);
				$entry->setImageNameThumbnail(NULL);
				$entry->setImageNameCard(NULL);
				$entry->setImageNameProfile(NULL);
				
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
				$connections->setErrorMessage('entry_added_failed');
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
				$connections->setErrorMessage('entry_updated_failed');
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

function copyImage($image)
{
	// Uses the upload.class.php to handle file uploading and image manipulation.
	// GPL PHP upload class from http://www.verot.net/php_class_upload.htm
	require_once(WP_PLUGIN_DIR . '/connections/includes/php_class_upload/class.upload.php');
	
	$source = CN_IMAGE_PATH . $image;
	
	$process_image = new Upload($source);
	$process_image->Process(CN_IMAGE_PATH);
	$process_image->file_safe_name		= true;
	$process_image->file_auto_rename	= true;
	$image = $process_image->file_dst_name;
	
	return $image;
}

function processImages()
{
	global $connections;
	
	// Uses the upload.class.php to handle file uploading and image manipulation.
	// GPL PHP upload class from http://www.verot.net/php_class_upload.htm
	require_once(WP_PLUGIN_DIR . '/connections/includes/php_class_upload/class.upload.php');
	
	$process_image = new Upload($_FILES['original_image']);
	$image['source'] = $process_image->file_src_name_body;
	
	if ($process_image->uploaded)
	{
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
		
		// If the orignal image uploaded and process ok, then create the derivative images.
		if ($process_image->processed)
		{
			$connections->setSuccessMessage('image_uploaded');
			$image['original'] = $process_image->file_dst_name;
			
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
				return FALSE;
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
				return FALSE;
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
				return FALSE;
			}
		}
		else
		{
			$connections->setErrorMessage('image_uploaded_failed');
			return FALSE;
		}
		
		$process_image->Clean();
		
	}
	else
	{
		$connections->setErrorMessage('image_upload_failed');
		return FALSE;
	}
	
	return array('image_names'=>$image);
}

function processSetEntryVisibility()
{
	$permitted = array('public', 'private', 'unlisted');
	if (!in_array($_POST['action'], $permitted)) return FALSE;
	
	/*
	 * Check whether the current user can edit entries.
	 */
	if (current_user_can('connections_edit_entry'))
	{
		global $connections;
		
		foreach ($_POST['entry'] as $id)
		{
			$entry = new cnEntry();
			
			$id = esc_attr($id);
			$entry->set($id);
			
			$entry->setVisibility($_POST['action']);
			$entry->update();
			unset($entry);
		}
		
		$connections->setSuccessMessage('form_entry_visibility_bulk');
	}
	else
	{
		$connections->setErrorMessage('capability_edit');
	}
}

function processSetUserFilter()
{
	global $connections;
	
	$connections->currentUser->setFilterEntryType($_POST['entry_type']);
	$connections->currentUser->setFilterVisibility($_POST['visibility_type']);
	
	if ( !empty($_POST['category']) ) $connections->currentUser->setFilterCategory(esc_attr($_POST['category']));
	if ( !empty($_GET['category_id']) ) $connections->currentUser->setFilterCategory(esc_attr($_GET['category_id']));
}

function processDeleteEntry()
{
	/*
	 * Check whether the current user delete an entry.
	 */
	if (current_user_can('connections_delete_entry'))
	{
		global $connections;
		
		$id = esc_attr($_GET['id']);
		check_admin_referer('entry_delete_' . $id);
		
		$entry = new cnEntry( $connections->retrieve->entry($id) );
		$entry->delete($id);
		$connections->setSuccessMessage('form_entry_delete');
		unset($entry);
	}
	else
	{
		$connections->setErrorMessage('capability_delete');
	}
}

function processDeleteEntries()
{
	/*
	 * Check whether the current user delete an entry.
	 */
	if (current_user_can('connections_delete_entry'))
	{
		global $connections;
		
		if (empty($_POST['entry'])) return FALSE;
		
		if (current_user_can('connections_delete_entry'))
		{
			$ids = $_POST['entry'];
			
			foreach ($ids as $id)
			{
				$entry = new cnEntry( $connections->retrieve->entry($id) );
				$id = esc_attr($id);
				$entry->delete($id);
				unset($entry);
			}
			
			$connections->setSuccessMessage('form_entry_delete_bulk');
		}
		else
		{
			$connections->setErrorMessage('capability_delete');
		}
	}
	else
	{
		$connections->setErrorMessage('capability_delete');
	}
}

function updateSettings()
{
	global $connections;
	$format = new cnFormatting();
	
	if (isset($_POST['settings']['allow_public']) && $_POST['settings']['allow_public'] === 'true')
	{
		$connections->options->setAllowPublic(TRUE);
	}
	else
	{
		$connections->options->setAllowPublic(FALSE);
	}
	
	
	if ($_POST['settings']['allow_public_override'] === 'true' && !$connections->options->getAllowPublic())
	{
		$connections->options->setAllowPublicOverride(TRUE);
	}
	else
	{
		$connections->options->setAllowPublicOverride(FALSE);
	}
	
	if ($_POST['settings']['allow_private_override'] === 'true')
	{
		$connections->options->setAllowPrivateOverride(TRUE);
	}
	else
	{
		$connections->options->setAllowPrivateOverride(FALSE);
	}
	
	$connections->options->setImgThumbQuality($format->stripNonNumeric($_POST['settings']['image']['thumbnail']['quality']));
	$connections->options->setImgThumbX($format->stripNonNumeric($_POST['settings']['image']['thumbnail']['x']));
	$connections->options->setImgThumbY($format->stripNonNumeric($_POST['settings']['image']['thumbnail']['y']));
	$connections->options->setImgThumbCrop($_POST['settings']['image']['thumbnail']['crop']);
	
	$connections->options->setImgEntryQuality($format->stripNonNumeric($_POST['settings']['image']['entry']['quality']));
	$connections->options->setImgEntryX($format->stripNonNumeric($_POST['settings']['image']['entry']['x']));
	$connections->options->setImgEntryY($format->stripNonNumeric($_POST['settings']['image']['entry']['y']));
	$connections->options->setImgEntryCrop($_POST['settings']['image']['entry']['crop']);
	
	$connections->options->setImgProfileQuality($format->stripNonNumeric($_POST['settings']['image']['profile']['quality']));
	$connections->options->setImgProfileX($format->stripNonNumeric($_POST['settings']['image']['profile']['x']));
	$connections->options->setImgProfileY($format->stripNonNumeric($_POST['settings']['image']['profile']['y']));
	$connections->options->setImgProfileCrop($_POST['settings']['image']['profile']['crop']);
	
	$connections->options->saveOptions();
	$connections->setSuccessMessage('settings_updated');
}

function processAddCategory()
{
	$category = new cnCategory();
	$format = new cnFormatting();
				
	$category->setName($format->sanitizeString($_POST['category_name']));
	$category->setSlug($format->sanitizeString($_POST['category_slug']));
	$category->setParent($format->sanitizeString($_POST['category_parent']));
	$category->setDescription($format->sanitizeString($_POST['category_description']));
	
	$category->save();
}

function processUpdateCategory()
{
	$category = new cnCategory();
	$format = new cnFormatting();
				
	$category->setID($format->sanitizeString($_POST['category_id']));
	$category->setName($format->sanitizeString($_POST['category_name']));
	$category->setParent($format->sanitizeString($_POST['category_parent']));
	$category->setSlug($format->sanitizeString($_POST['category_slug']));
	$category->setDescription($format->sanitizeString($_POST['category_description']));
	
	$category->update();
}


function processDeleteCategory($type)
{
	global $connections;
	
	switch ($type)
	{
		case 'delete':
			$id = esc_attr($_GET['id']);
			check_admin_referer('category_delete_' . $id);
			
			$result = $connections->retrieve->category($id);
			$category = new cnCategory($result);
			$category->delete();
		break;
		
		case 'bulk_delete':
			foreach ( (array) $_POST['category'] as $cat_ID )
			{
				$cat_ID = esc_attr($cat_ID);
				
				$result = $connections->retrieve->category(attribute_escape($cat_ID));
				$category = new cnCategory($result);
				$category->delete();
			}
		break;
	}
}

function updateRoleSettings()
{
	global $connections, $wp_roles;
	
	if (isset($_POST['roles']))
	{
		// Cycle thru each role available because checkboxes do not report a value when not checked.
		foreach ($wp_roles->get_names() as $role => $name)
		{
			if (!isset($_POST['roles'][$role])) continue;
			
			foreach ($_POST['roles'][$role]['capabilities'] as $capability => $grant)
			{
				// the admininistrator should always have all capabilities
				if ($role == 'administrator') continue;
				
				if ($grant == 'true')
				{
					$connections->options->addCapability($role, $capability);
				}
				else
				{
					$connections->options->removeCapability($role, $capability);
				}
			}
		}
	}
	
	
	if (isset($_POST['reset'])) $connections->options->setDefaultCapabilities($_POST['reset']);
	
	if (isset($_POST['reset_all'])) $connections->options->setDefaultCapabilities();
	
	$connections->setSuccessMessage('role_settings_updated');
}

?>