<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="width: 100%">
	    <tr>
	        <td align="left" width="55%" valign="top" style="vertical-align: top;">
	        	<div style="clear:both; margin: 0 5px;">
					<div style="margin-bottom: 10px;">
						<span style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
						
						<?php echo $entry->getTitleBlock() ?>
						<?php
							if ($entry->getOrganization() || $entry->getDepartment()) echo '<div class="org">' . "\n";
							if ($entry->getOrganization() && $entry->getEntryType() != 'organization') echo '<span class="organization-name">' . $entry->getOrganization() . '</span><br />' . "\n";
							if ($entry->getDepartment()) echo '<strong><span class="organization-unit">' . $entry->getDepartment() . '</span></strong><br />' . "\n";
							if ($entry->getOrganization() || $entry->getDepartment()) echo '</div>' . "\n";
						?>
					</div>
					
					<?php echo $entry->getAddressBlock() ?>
					
					<?php
						// Build the address query for Google.
						if ($entry->getAddresses())
						{
							foreach ($entry->getAddresses() as $address)
							{
								$map_link = "http://maps.google.com/?q=";
								
								//if ($entry->getOrganization() != NULL) $map_link .= $entry->getOrganization() . '+';
								if ($address->line_one != NULL) $map_link .= $address->line_one . '+';
								if ($address->line_two != NULL) $map_link .= $address->line_two . '+';
								if ($address->city != NULL) $map_link .= $address->city . '+';
								if ($address->state != NULL) $map_link .= $address->state . '+';
								if ($address->zipcode != NULL) $map_link .= $address->zipcode;
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
							
							$data = '<div id="map_' . $entry->getId() . '" style="background-color: #E3E3E3; display: none; margin-top: 10px;"><iframe width="523" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $iframe_google_map . '"></iframe></div>';
						}
						
						
					?>
					
				</div>
	        </td>
	        <td align="right" valign="top" style="text-align: right;">
	        	<?php echo $entry->getCardImage() ?>
				
				<div style="clear:both; margin: 5px 5px;">
		        	<?php echo $entry->getConnectionGroupBlock() ?>
					
					<?php echo $entry->getPhoneNumberBlock() ?>
					<?php echo $entry->getEmailAddressBlock() ?>
					
					<?php echo $entry->getImBlock() ?>
					<?php if (method_exists($entry, 'getSocialMediaBlock')) echo $entry->getSocialMediaBlock() ?>
					
					<?php echo $entry->getBirthdayBlock('F j') ?>
					<?php echo $entry->getAnniversaryBlock() ?>
				</div>
	        </td>
	    </tr>
	    
	    <tr>
	        <td valign="bottom">
	        	<?php echo $vCard->download() ?>
				
				<?php if ($entry->getWebsites()) echo ' | '; ?>
				
				<?php
					if ($entry->getWebsites())
					{
						//echo '<div style="margin-bottom: 10px;" class="websites">';
						foreach ($entry->getWebsites() as $website)
						{
							if ($website->address != NULL) echo '<a class="url" href="' . $website->address . '" target="_blank">Visit Website</a>' . "\n";
							break; // Only show the first stored web address
						}
						//echo '</div>';
					}
				?>
	        </td>
			<td align="right" valign="bottom"  style="text-align: right;">
				
				<?php if ($entry->getNotes() != '') { ?>
				
				<a href="#" id="close_note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Coupon</a>
				
				<a href="#" id="note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Coupon</a>
				
				<?php
					} 
					if ($entry->getBio() != '' && $entry->getNotes() != '') echo ' | ';
				?>
				<?php if ($entry->getBio() != '') { ?>
				
				<a href="#" id="close_bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Bio</a>
				
				<a href="#" id="bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Bio</a>
				
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
				
				<?php echo $entry->returnToTopAnchor() ?>
	        </td>
	    </tr>
		
		<tr>
			<td colspan="2">
				<?php 
					if ($entry->getNotes() != '')
					{
						echo '<div id="note_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Coupon</strong><br />' . $entry->getNotesBlock() . '</div>';
					}
					
					if ($entry->getBio() != '')
					{
						echo '<div id="bio_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Biography</strong><br />' . $entry->getBioBlock() . '</div>';
					}
				?>
			</td>
		</tr>
		
	</table>
	<?php
		unset($anchorOut);
		unset($data);
	?>
</div>