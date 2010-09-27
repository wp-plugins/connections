<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry">
	<table border="0px" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
	    <tr>
	        <td align="left" width="55%" valign="top">
	        	<div style="clear:both; margin: 0 5px;">
					<div style="margin-bottom: 10px;">
						<span class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?></span>
						
						<?php echo $entry->getTitleBlock() ?>
						<?php echo $entry->getOrgUnitBlock() ?>
						
						<?php
							if ($entry->getImageLinked() && $entry->getImageDisplay())
							{
								echo '<img class="photo" alt="Photo of ' . $entry->getFirstName() . ' ' . $entry->getLastName() . '" src="' . CN_IMAGE_BASE_URL . $entry->getImageNameCard() . '" />' . "\n";
							}
						?>
						
						<?php echo $entry->getAddressBlock() ?>
					</div>
					
				</div>
	        </td>
	        <td align="right" valign="top" style="text-align: right;">
	        	
				<div style="clear:both; margin: 5px 5px;">
		        	
					<?php
						if ($entry->getPhoneNumbers())
						{
							echo '<div class="phone-numbers">' . "\n";
							foreach ($entry->getPhoneNumbers() as $phoneNumber) 
							{
								if ($phoneNumber->number != null)
								{
									switch ($phoneNumber->type)
									{
										case 'home':
											$phoneNumberName = 'Home Phone';
											break;
										case 'homephone':
											$phoneNumberName = 'Home Phone';
											break;
										case 'homefax':
											$phoneNumberName = 'Home Fax';
											break;
										case 'cell':
											$phoneNumberName = 'Cell Phone';
											break;
										case 'cellphone':
											$phoneNumberName = 'Cell Phone';
											break;
										case 'work':
											$phoneNumberName = 'Phone';
											break;
										case 'workphone':
											$phoneNumberName = 'Phone';
											break;
										case 'workfax':
											$phoneNumberName = 'Fax';
											break;
										case 'fax':
											$phoneNumberName = 'Fax';
											break;
									}
									
									//Type for hCard compatibility. Hidden.
									echo  '<strong>' . $phoneNumberName . '</strong>: <span class="tel">' . $entry->gethCardTelType($phoneNumber->type) . '<span class="value">' .  $phoneNumber->number . '</span></span><br />' . "\n";
								}
							}
							echo '</div>' . "\n";
						}
					?>
					
					<?php echo $entry->getEmailAddressBlock() ?>
					
					
					<?php
						// Build the address query for Google.
						if ($entry->getAddresses())
						{
							foreach ($entry->getAddresses() as $address)
							{
								$map_link = "http://maps.google.com/?q=";
								
								//if ($entry->getOrganization() != NULL) $map_link .= $entry->getOrganization() . '+';
								if ($address->line_one != null) $map_link .= $address->line_one . '+';
								if ($address->line_two != null) $map_link .= $address->line_two . '+';
								if ($address->city != null) $map_link .= $address->city . '+';
								if ($address->state != null) $map_link .= $address->state . '+';
								if ($address->zipcode != null) $map_link .= $address->zipcode;
								break; // Only store the address info from the first address.							
							}
							
							// Google Maps parameters
							$map_parms = '&amp;ie=UTF8';
				            
				            // t= Map Type. The available options are "m" map, "k" satellite, "h" hybrid, "p" terrain.
				            $map_parms .= '&amp;t=m';
				            
				            // z= Sets the zoom level.
				            $map_parms .= '&amp;z=13';
							
							// Embed
							$map_parms .= '&amp;output=embed';
							
							// Find all the spaces
				            $pattern = '/\s/';
				            
				            // replace with +
				            $replacement = '+';
				            
				            // Convert link for Google Maps query.
				            $map_link = preg_replace($pattern, $replacement, $map_link);
							
							// Add the parms to the map link.
				            $iframe_google_map = $map_link . $map_parms;
							
							$data = '<div id="map_' . $entry->getId() . '" style="background-color: #E3E3E3; display: none; margin-top: 10px;"><iframe width="580" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $iframe_google_map . '"></iframe></div>';
						}
						
						
					?>
					
					<?php echo $entry->getImBlock() ?>
					<?php echo $entry->getSocialMediaBlock() ?>
					
					<?php echo $entry->getBirthdayBlock('F j') ?>
					<?php echo $entry->getAnniversaryBlock() ?>
					
					<?php echo $entry->getExcerpt( $entry->getBio() ) ?>
				</div>
	        </td>
	    </tr>
	    
	    <tr>
	        <td valign="bottom" style="text-align: left;">
	        	<?php echo $vCard->download() ?>
				
				<?php if ($entry->getWebsites()) echo ' | '; ?>
				
				<?php
					if ($entry->getWebsites())
					{
						//echo '<div style="margin-bottom: 10px;" class="websites">';
						foreach ($entry->getWebsites() as $website)
						{
							if ($website->address != null) echo '<a class="url" href="' . $website->address . '" target="_blank">Visit Website</a>' . "\n";
							break; // Only show the first stored web address
						}
						//echo '</div>';
					}
				?>
	        </td>
			<td align="right" valign="bottom"  style="text-align: right;">
				
				<?php if ($entry->getBio() != '') { ?>
				
				<a href="#" id="close_bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Close Description</a>
				
				<a href="#" id="bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Business Description</a>
				
				<?php } ?>
				<?php if ($entry->getBio() != '' && $data != '') echo ' | '; ?>
				
				<?php if ($data != '') { ?>
				
				<a href="#" id="close_map_link_<?php echo $entry->getId(); ?>" class="close_map_link" onclick='jQuery("a#close_map_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				var divH = jQuery("#entry_id_<?php echo $entry->getId(); ?>").height() - 400;
				jQuery("#map_<?php echo $entry->getId(); ?>").fadeOut();
				//jQuery("#entry_id_<?php echo $entry->getId(); ?>").animate({height: divH}, 500);
				jQuery("a#close_map_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("a#map_link_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("#map_<?php echo $entry->getId(); ?>").remove();
				}); return false' style="display: none;">Close Map</a>
				
				<a href="#" id="map_link_<?php echo $entry->getId(); ?>" class="open_map_link" onclick='jQuery("a#map_link_<?php echo $entry->getId(); ?>").live("click", function(e){
        		var $this = jQuery(this);
				var data = $this.attr("data");
				var divH = jQuery("#entry_id_<?php echo $entry->getId(); ?>").height() + 400;
				//jQuery("#entry_id_<?php echo $entry->getId(); ?>").animate({height: divH}, 1000);
				jQuery(data).appendTo(jQuery("#entry_id_<?php echo $entry->getId(); ?>"));
				jQuery("#map_<?php echo $entry->getId(); ?>").fadeIn(2000);
				jQuery("a#map_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("a#close_map_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' data='<?php echo $data; ?>'>Show Map</a>
				
				<?php echo ' | '; ?>
				
				<?php } ?>
				
				<span class="cn-return-to-top"><?php echo $entry->returnToTopAnchor() ?></span>
	        </td>
	    </tr>
		
		<tr>
			<td colspan="2">
				<?php 
					if ($entry->getBio() != '')
					{
						echo '<div id="bio_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Business Description</strong><br />' . $entry->getBioBlock() . '</div>';
					}
				?>
			</td>
		</tr>
		
	</table>
	<?php
		unset($anchorOut);
		unset($data);
		unset($showMap);
	?>
</div>