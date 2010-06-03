<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#D8D8D8; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<table width="100%" border="0px" bgcolor="#D8D8D8" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="margin: 0; width: 100%">
	    <tr>
	        <td align="left" width="55%" valign="top" style="border: medium none transparent">
	        	
				<?php if ($entry->getImageLinked() && $entry->getImageDisplay()) echo '<img class="photo" alt="Photo of ' . $entry->getFirstName() . ' ' . $entry->getLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #E3E3E3; margin: 0; padding:5px;" src="' . CN_IMAGE_BASE_URL . $entry->getImageNameCard() . '" />' . "\n"; ?>
				
	        </td>
	        <td align="right" valign="top" style="text-align: right; border: medium none transparent">
	        	<div style="clear:both; margin: 5px 5px;">
					<span style="font-size:larger;font-variant: small-caps; margin-bottom: 10px;"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
					<strong><?php echo $entry->getTitleBlock() ?></strong><br />
					<?php echo $entry->getOrgUnitBlock() ?>
						
		        	<?php
						echo $entry->getAddressBlock();
					
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
					
					<?php
						if ($entry->getEmailAddresses())
						{
							echo '<div class="email-addresses" style="margin-bottom: 10px;">' . "\n";
							foreach ($entry->getEmailAddresses() as $email)
							{
								//Type for hCard compatibility. Hidden.
								if ($email->address != NULL) echo '<span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $email->address . '">' . $email->address . '</a></span><br />' . "\n";
							}
							echo '</div>' . "\n";
						}
					?>
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
	        <td colspan="2"  valign="bottom" style="border: medium none transparent;">
	        	<?php
					if ($entry->getNotes() != '')
					{
						echo '<div id="note_block_' . $entry->getId() . '" style="margin: 5px 0;"><strong>Notes:</strong> ' . $entry->getNotes() . '</div>';
					}
				?>
	        </td>
	    </tr>
		
	    <tr>
	        <td colspan="2"  valign="bottom" style="border: medium none transparent;">
	        	<?php echo $vCard->download() ?><?php if (!empty($anchorOut)) echo ' | ' . $anchorOut; ?>
	        </td>
	    </tr>
		
		
	</table>
	<?php
		unset($anchorOut);
	?>
</div>