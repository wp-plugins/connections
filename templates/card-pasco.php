<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative; width: 523px;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
	    <tr>
	        <td align="left" width="50%" valign="top">
	        	<?php echo $entry->getCardImage() ?>
				
				<div style="clear:both; margin: 0 5px;">
					<div style="margin-bottom: 10px;">
						<span style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
						
						<?php echo $entry->getTitleBlock() ?>
						<?php echo $entry->getOrgUnitBlock() ?>
					</div>
					
					<?php
						echo $entry->getAddressBlock();
						
						if ($entry->getAddresses())
						{
							$addressObject = new cnAddresses;
							foreach ($entry->getAddresses() as $addressRow)
							{
								$map_link = "http://maps.google.com/?q=";
								
								if ($entry->getOrganization() != NULL) $map_link .= $entry->getOrganization() . '+';
								//if ($addressObject->getLineOne($addressRow) != null) $map_link .= $addressObject->getLineOne($addressRow) . '+';
								if ($addressObject->getLineTwo($addressRow) != null) $map_link .= $addressObject->getLineTwo($addressRow) . '+';
								if ($addressObject->getCity($addressRow) != null) $map_link .= $addressObject->getCity($addressRow) . '+';
								if ($addressObject->getState($addressRow) != null) $map_link .= $addressObject->getState($addressRow) . '+';
								if ($addressObject->getZipCode($addressRow) != null) $map_link .= $addressObject->getZipCode($addressRow);
																		
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
						}
						
						$data = '<div class="google_map" style="background-color: #E3E3E3; display: none;"><iframe width="523" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="' . $iframe_google_map . '"></iframe></div>';
					?>
				</div>
	        </td>
	        <td align="right" valign="top">
	        	<div style="clear:both; margin: 5px 5px;">
		        	<?php echo $entry->getConnectionGroupBlock() ?>
					
					<?php echo $entry->getPhoneNumberBlock() ?>
					<?php echo $entry->getEmailAddressBlock() ?>
					
					<?php echo $entry->getImBlock() ?>
					<?php //echo $entry->getWebsiteBlock() ?>
					
					<?php
					$websiteObject = new cnWebsite;
						if ($entry->getWebsites())
						{
							$anchorOut .= '<div class="websites" style="margin-bottom: 10px;">' . "\n";
							foreach ($entry->getWebsites() as $websiteRow)
							{
								if ($websiteObject->getAddress($websiteRow) != null) $anchorOut .= '<a class="url" href="' . $websiteObject->getAddress($websiteRow) . '">Visit their website.</a>' . "\n";
							}
							$anchorOut .= "</div>" . "\n";
						}
						echo $anchorOut;
					?>
					
					<?php echo $entry->getBirthdayBlock('F j') ?>
					<?php echo $entry->getAnniversaryBlock() ?>
				</div>
	        </td>
	    </tr>
	    
		<tr>
			<td colspan="2">
				<?php 
					if ($entry->getNotes() != '')
					{
						echo '<div style="margin-bottom: 10px;">' . $entry->getNotesBlock() . '</div>';
					}
				?>
			</td>
		</tr>
		
	    <tr>
	        <td valign="bottom">
	        	<?php echo $vCard->download() ?>
	        </td>
			<td align="right" valign="bottom">
				<a href="#" id="map_link_<?php echo $entry->getId(); ?>" class="map_link" onclick='jQuery("a#map_link_<?php echo $entry->getId(); ?>").live("click", function(e){
        		var $this = jQuery(this);
		        var data = $this.attr("title");
				jQuery(data).appendTo(jQuery("#entry_id_<?php echo $entry->getId(); ?>"));
				jQuery(".google_map").fadeIn(6000);
				jQuery("a#map_link_<?php echo $entry->getId(); ?>").remove();
				}); return false' title='<?php echo $data; ?>'>Show Map</a>
	        </td>
	    </tr>
		
	</table>
	<?php
		unset($anchorOut);
		unset($data);
	?>
</div>