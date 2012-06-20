<?php
function connectionsShowSettingsPage()
{
	/*
	 * Check whether user can edit Settings
	 */
	if (!current_user_can('connections_change_settings'))
	{
		wp_die('<p id="error-page" style="-moz-background-clip:border;
				-moz-border-radius:11px;
				background:#FFFFFF none repeat scroll 0 0;
				border:1px solid #DFDFDF;
				color:#333333;
				display:block;
				font-size:12px;
				line-height:18px;
				margin:25px auto 20px;
				padding:1em 2em;
				text-align:center;
				width:700px">You do not have sufficient permissions to access this page.</p>');
	}
	else
	{
		global $connections;
		
		$form = new cnFormObjects();
		
		$connections->displayMessages();
	?>
		<div class="wrap">
			<div id="icon-connections" class="icon32">
		        <br>
		    </div>
			
			<h2>Connections : Settings</h2>
			
			<?php 
				$attr = array(
							 'action' => 'admin.php?connections_process=true&process=setting&action=update',
							 'method' => 'post',
							 );
				
				$form->open($attr);
				$form->tokenField('update_settings');
			?>
			
				<div class="form-wrap">
					<div class="form-field">
						<table class="form-table">
							<tbody>
							
								<tr valign="top">
									<th scope="row">
										Public Entries
									</th>
									<td>
										<label for="allow_public">
											<input type="checkbox" value="true" name="settings[allow_public]" id="allow_public" 
												<?php if ($connections->options->getAllowPublic()) echo 'CHECKED ' ?>
											/>
											Allow unregistered visitors and users not logged in to view entries<br />
											<small>(When disabled, use roles to define which roles may view the public entries.)</small>
										</label>
										
										<label for="allow_public_override">
											<input type="checkbox" value="true" name="settings[allow_public_override]" id="allow_public_override" 
												<?php if ($connections->options->getAllowPublicOverride()) echo 'CHECKED ' ?>
												<?php if ($connections->options->getAllowPublic()) echo 'DISABLED ' ?>
											/>
											Allow shortcode attribute override
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					<div class="form-field">
						<table class="form-table">
							<tbody>
							
								<tr valign="top">
									<th scope="row">
										Private Entries
									</th>
									<td>
										<label for="allow_private_override">
											<input type="hidden" value="false" name="settings[allow_private_override]"/>
											<input type="checkbox" value="true" name="settings[allow_private_override]" id="allow_private_override" 
												<?php if ($connections->options->getAllowPrivateOverride()) echo 'CHECKED ' ?>
											/>
											Allow shortcode attribute override
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
				
					<div class="form-field">
						<h3>Thumbnail Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbQuality() ?>" id="image_thumbnail_quality" name="settings[image][thumbnail][quality]"/>%
									</td>
									<td rowspan="4" width="50%">
										<p>Changing the Quality setting will affect the image quality as well the file size. If you require smaller file sizes and can accept lower image quality, reduce the value.</p>
										<p>The values entered for the width and height will be the final size of the image. If the image is smaller, white space will be added to the image with the image centered. If the image is larger in either dimension it will be scaled down. The crop setting defines how the image will be scaled down.</p>
										<p>Crop will use the image's smaller dimension and scale it to fit and then crop the excess image from both sides or top and bottom equally of the larger dimension.</p>
										<p>Shrink will use the image's larger dimension and scale it to fit and then add white space equally to both sides or top and bottom equally to the smaller dimension.</p>
										<p>None will scale both image dimensions to fit the entered values un-proportionally.</p>
										<p><strong>NOTE: </strong>Although these options are available they are not recommended to be changed.</p>
										<p><strong>NOTE: </strong>Changes will only be applied to images uploaded after the change was saved. This will not affect images uploaded previously.</p>
										<p><strong>Default Values: </strong>Quality: 80%; Width: 80px; Height: 54px; Crop</p>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbX() ?>" id="image_thumbnail_x" name="settings[image][thumbnail][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_thumbnail_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgThumbY() ?>" id="image_thumbnail_y" name="settings[image][thumbnail][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][thumbnail][crop]', 'image_thumbnail_crop', array('Crop (maintain aspect ratio)' => 'crop', 'Shrink (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgThumbCrop()); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
								
								
					<div class="form-field">
						<h3>Entry Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryQuality() ?>" id="image_entry_quality" name="settings[image][entry][quality]"/>%
									</td>
									<td rowspan="4" width="50%">
										<p>Changing the Quality setting will affect the image quality as well the file size. If you require smaller file sizes and can accept lower image quality, reduce the value.</p>
										<p>The values entered for the width and height will be the final size of the image. If the image is smaller, white space will be added to the image with the image centered. If the image is larger in either dimension it will be scaled down. The crop setting defines how the image will be scaled down.</p>
										<p>Crop will use the image's smaller dimension and scale it to fit and then crop the excess image from both sides or top and bottom equally of the larger dimension.</p>
										<p>Shrink will use the image's larger dimension and scale it to fit and then add white space equally to both sides or top and bottom equally to the smaller dimension.</p>
										<p>None will scale both image dimensions to fit the entered values un-proportionally.</p>
										<p><strong>NOTE: </strong>Changes will only be applied to images uploaded after the change was saved. This will not affect images uploaded previously.</p>
										<p><strong>Default Values: </strong>Quality: 80%; Width: 225px; Height: 150px; Crop</p>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryX() ?>" id="image_entry_x" name="settings[image][entry][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_entry_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgEntryY() ?>" id="image_entry_y" name="settings[image][entry][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][entry][crop]', 'image_entry_crop', array('Crop (maintain aspect ratio)' => 'crop', 'Shrink (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgEntryCrop()); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
								
					<div class="form-field">
						<h3>Profile Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileQuality() ?>" id="image_profile_quality" name="settings[image][profile][quality]"/>%
									</td>
									<td rowspan="4" width="50%">
										<p>Changing the Quality setting will affect the image quality as well the file size. If you require smaller file sizes and can accept lower image quality, reduce the value.</p>
										<p>The values entered for the width and height will be the final size of the image. If the image is smaller, white space will be added to the image with the image centered. If the image is larger in either dimension it will be scaled down. The crop setting defines how the image will be scaled down.</p>
										<p>Crop will use the image's smaller dimension and scale it to fit and then crop the excess image from both sides or top and bottom equally of the larger dimension.</p>
										<p>Shrink will use the image's larger dimension and scale it to fit and then add white space equally to both sides or top and bottom equally to the smaller dimension.</p>
										<p>None will scale both image dimensions to fit the entered values un-proportionally.</p>
										<p><strong>NOTE: </strong>Changes will only be applied to images uploaded after the change was saved. This will not affect images uploaded previously.</p>
										<p><strong>Default Values: </strong>Quality: 80%; Width: 300px; Height: 225px; Crop</p>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileX() ?>" id="image_profile_x" name="settings[image][profile][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="image_profile_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgProfileY() ?>" id="image_profile_y" name="settings[image][profile][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][profile][crop]', 'image_profile_crop', array('Crop (maintain aspect ratio)' => 'crop', 'Shrink (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgProfileCrop()); ?>
									</td>
								</tr>
							
							</tbody>
						</table>
					</div>
					
					
					<div class="form-field">
						<h3>Logo Image Settings</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										<label for="logo_thumbnail_quality">JPEG Quality</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgLogoQuality() ?>" id="logo_thumbnail_quality" name="settings[image][logo][quality]"/>%
									</td>
									<td rowspan="4" width="50%">
										<p>Changing the Quality setting will affect the image quality as well the file size. If you require smaller file sizes and can accept lower image quality, reduce the value.</p>
										<p>The values entered for the width and height will be the final size of the image. If the image is smaller, white space will be added to the image with the image centered. If the image is larger in either dimension it will be scaled down. The crop setting defines how the image will be scaled down.</p>
										<p>Crop will use the image's smaller dimension and scale it to fit and then crop the excess image from both sides or top and bottom equally of the larger dimension.</p>
										<p>Shrink will use the image's larger dimension and scale it to fit and then add white space equally to both sides or top and bottom equally to the smaller dimension.</p>
										<p>None will scale both image dimensions to fit the entered values un-proportionally.</p>
										<p><strong>NOTE: </strong>Although these options are available they are not recommended to be changed.</p>
										<p><strong>NOTE: </strong>Changes will only be applied to images uploaded after the change was saved. This will not affect images uploaded previously.</p>
										<p><strong>Default Values: </strong>Quality: 80%; Width: 225px; Height: 150px; Crop</p>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										<label for="logo_x">Width</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgLogoX() ?>" id="logo_x" name="settings[image][logo][x]"/>px
									</td>
								</tr>				
								
								<tr valign="top">
									<th scope="row">
										<label for="logo_y">Height</label>
									</th>
									<td>
										<input type="text" class="small-text" value="<?php echo $connections->options->getImgLogoY() ?>" id="logo_y" name="settings[image][logo][y]"/>px
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										Crop
									</th>
									<td>
										<?php echo $form->buildRadio('settings[image][logo][crop]', 'logo_crop', array('Crop (maintain aspect ratio)' => 'crop', 'Shrink (maintain aspect ratio)' => 'fill', 'None' => 'none'), $connections->options->getImgLogoCrop()); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					
					<?php
						// Retrieve the search field settings
						$search = $connections->options->getSearchFields();
						//var_dump($search);
					?>
					
					<div class="form-field">
						<h3>Search</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										Search Fields
									</th>
									<td>
										<p>The selected fields will be searched.</p>
										<small><strong>NOTE: </strong>This only applies to search in the the Connections admin and premium templates that enable search in the front end.</small>
										<small><strong>NOTE: </strong>Only change when necessary. It can take seconds to many minutes to build the indexes.</small>

										<label>
											<input type="checkbox" value="true" name="settings[search][field][family_name]" id="search-field-family-name" 
												<?php if ( $search->family_name ) echo 'CHECKED ' ?>
											/>Family Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][first_name]" id="search-field-first-name" 
												<?php if ( $search->first_name ) echo 'CHECKED ' ?>
											/>First Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][middle_name]" id="search-field-middle-name" 
												<?php if ( $search->middle_name ) echo 'CHECKED ' ?>
											/>Middle Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][last_name]" id="search-field-last-name" 
												<?php if ( $search->last_name ) echo 'CHECKED ' ?>
											/>Last Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][title]" id="search-field-title" 
												<?php if ( $search->title ) echo 'CHECKED ' ?>
											/>Title
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][organization]" id="search-field-organization" 
												<?php if ( $search->organization ) echo 'CHECKED ' ?>
											/>Organization
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][department]" id="search-field-department" 
												<?php if ( $search->department ) echo 'CHECKED ' ?>
											/>Department
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][contact_first_name]" id="search-field-contact-first-name" 
												<?php if ( $search->contact_first_name ) echo 'CHECKED ' ?>
											/>Contact First Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][contact_last_name]" id="search-field-contact-last-name" 
												<?php if ( $search->contact_last_name ) echo 'CHECKED ' ?>
											/>Contact Last Name
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][bio]" id="search-field-bio" 
												<?php if ( $search->bio ) echo 'CHECKED ' ?>
											/>Biography
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][notes]" id="search-field-notes" 
												<?php if ( $search->notes ) echo 'CHECKED ' ?>
											/>Notes
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_line_1]" id="search-field-address-line-1" 
												<?php if ( $search->address_line_1 ) echo 'CHECKED ' ?>
											/>Address Line One
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_line_2]" id="search-field-address-line-2" 
												<?php if ( $search->address_line_2 ) echo 'CHECKED ' ?>
											/>Address Line Two
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_line_3]" id="search-field-address-line-3" 
												<?php if ( $search->address_line_3 ) echo 'CHECKED ' ?>
											/>Address Line Three
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_city]" id="search-field-address-city" 
												<?php if ( $search->address_city ) echo 'CHECKED ' ?>
											/>Address City
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_state]" id="search-field-address-state" 
												<?php if ( $search->address_state ) echo 'CHECKED ' ?>
											/>Address State
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_zipcode]" id="search-field-address-zipcode" 
												<?php if ( $search->address_zipcode ) echo 'CHECKED ' ?>
											/>Address Zipcode
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][address_country]" id="search-field-address-country" 
												<?php if ( $search->address_country ) echo 'CHECKED ' ?>
											/>Address Country
										</label>
										
										<label>
											<input type="checkbox" value="true" name="settings[search][field][phone_number]" id="search-field-phone-number" 
												<?php if ( $search->phone_number ) echo 'CHECKED ' ?>
											/>Phone Number
										</label>
										
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										FULLTEXT Query Support
									</th>
									<td>
										<label>
											<input type="checkbox" value="true" name="settings[search][fulltext]" id="search-fulltext" 
												<?php if ( $connections->options->getSearchUsingFulltext() ) echo 'CHECKED ' ?>
											/>
											If you do not know what this means, please do not change it.
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					
					<div class="form-field">
						<h3>Advanced</h3>
						<table class="form-table">
							<tbody>
								
								<tr valign="top">
									<th scope="row">
										Google Maps API v3
									</th>
									<td>
										<label>
											<input type="checkbox" value="true" name="settings[advanced][google_maps_api]" id="google-maps-api" 
												<?php if ( $connections->options->getGoogleMapsAPI() ) echo 'CHECKED ' ?>
											/>
											If the current active theme or another plugin loads the Google Maps API v3 check this to prevent Connections from loading the API. This will prevent potential conflicts.
										</label>
									</td>
								</tr>
								
								<tr valign="top">
									<th scope="row">
										JavaScript
									</th>
									<td>
										<label>
											<input type="checkbox" value="true" name="settings[advanced][javascript_footer]" id="javascript-footer" 
												<?php if ( $connections->options->getJavaScriptFooter() ) echo 'CHECKED ' ?>
											/>
											By default Connections loads it's JavaScripts in the page footer uncheck this box to load them in the page header.<br />
											<small><strong>NOTE: </strong>This will not affect JavaScript files loaded by Connections templates.</small>
										</label>
										
										<?php //var_dump($connections->options); ?>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					
					<div class="form-field">
						<h3>Debug Messages</h3>
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row">
										Display
									</th>
									<td>
										<label>
											<input type="checkbox" value="true" name="settings[debug]" id="allow_public" 
												<?php if ( $connections->options->getDebug() ) echo 'CHECKED ' ?>
											/>
											Run time debug messages.
										</label>
									</td>
								</tr>
								
							</tbody>
						</table>
					</div>
					
					
				</div>
			
			<p class="submit"><input class="button-primary" type="submit" value="Save Changes" name="save" /></p>
			
			<?php $form->close(); ?>
			
		</div>
		<div class="clear"></div>
	
	<?php }
}
?>