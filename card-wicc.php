<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="margin: 0; width: 100%">
	    <tr>
	        <td align="left" width="55%" valign="top" style="border: medium none transparent">
	        	<div style="clear:both; margin: 0 5px;">
					<div style="margin-bottom: 10px;">
						<span style="font-size:larger;font-variant: small-caps; margin-bottom: 10px;"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
						
						<?php if ($entry->getImageLinked() && $entry->getImageDisplay()) echo '<img class="photo" alt="Photo of ' . $entry->getFirstName() . ' ' . $entry->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin: 10px 0 10px 0; padding:5px;" src="' . CN_IMAGE_BASE_URL . $entry->getImageNameCard() . '" />' . "\n"; ?>
					</div>
				</div>
	        </td>
	        <td align="right" valign="top" style="text-align: right; border: medium none transparent">
	        	<div style="clear:both; margin: 5px 5px;">
					
					<strong><?php echo $entry->getTitleBlock() ?></strong><br />
					<?php echo $entry->getOrgUnitBlock() ?>
						
		        	<?php
						if ($entry->getPhoneNumbers())
						{
							
							echo '<div class="phone_numbers" style="margin-bottom: 10px;">' . "\n";
							foreach ($entry->getPhoneNumbers() as $phone) 
							{
								if ($phone->number != NULL)
								{
									switch ($phone->type)
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
									echo  '<strong>' . $phoneNumberName . '</strong>: <span class="tel">' . $entry->gethCardTelType($phone->type) . '<span class="value">' .  $phone->number . '</span></span><br />' . "\n";
								}
							}
							echo '</div>' . "\n";
						}
					?>
					<?php echo $entry->getAddressBlock(); ?>
					<?php echo $entry->getEmailAddressBlock() ?>
					<?php echo $entry->getImBlock() ?>
					
					<div class="websites" style="margin-bottom: 10px;">
					 
						<?php
						
							if ($entry->getWebsites())
							{
								foreach ($entry->getWebsites() as $website)
								{
									if ($website->address != NULL) $anchorOut .= '<a class="url" href="' . $website->address . '" target="_blank">Visit Website</a>' . "\n";
									break; // Only show the first stored web address
								}
							}
						?>
					</div>
					
				</div>
	        </td>
	    </tr>
	    
	    <tr>
	        <td valign="bottom" style="border: medium none transparent;">
	        	<?php echo $vCard->download() ?><?php if (!empty($anchorOut)) echo ' | ' . $anchorOut; ?>
	        </td>
			<td align="right" valign="bottom"  style="border: medium none transparent; text-align: right;">
				
				<?php if ($entry->getBio() != '') { ?>
				
				<a href="#" id="close_description_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#description_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#description_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Close Bio</a>
				
				<a href="#" id="description_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#description_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#description_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#description_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Bio</a>
				
				<?php } ?>
				
				<?php if ($entry->getNotes() != '') { ?>
				
				<?php echo ' | '; ?>
				
				<a href="#" id="close_note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Services</a>
				
				<a href="#" id="note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Services</a>
				
				<?php } ?>
				
	        </td>
	    </tr>
		
		<tr>
			<td colspan="2" style="border: medium none transparent;">
				<?php 
					if ($entry->getNotes() != '')
					{
						echo '<div id="note_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Services</strong><br />' . $entry->getNotesBlock() . '</div>';
					}
					
					if ($entry->getBio() != '')
					{
						echo '<div id="description_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Bio</strong><br />' . $entry->getBioBlock() . '</div>';
					}
				?>
			</td>
		</tr>
		
	</table>
	<?php
		unset($anchorOut);
	?>
</div>