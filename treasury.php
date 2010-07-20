<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" colspan="2" width="50%" valign="top">
        	<div style="clear:both; margin: 0 5px;">
				<div style="margin-bottom: 10px;">
					<span style="font-size:larger;font-variant: small-caps"><h2><?php echo $entry->getFullFirstLastNameBlock() ?></h2></span><br />
					
					<?php echo $entry->getTitleBlock() ?>
					<?php echo $entry->getOrgUnitBlock() ?>
				</div>
				
				<?php
					if ($entry->getAddresses())
					{
						foreach ($entry->getAddresses() as $address)
						{
							echo '<div class="adr" style="margin-bottom: 10px;">' . "\n";
								//if ($address->name != NULL || $address->type != NULL) echo '<span class="address_name"><strong>' . $address->name . '</strong></span><br />' . "\n"; //The OR is for compatiblity for 0.2.24 and under
								if ($address->line_one != NULL) echo '<div class="street-address">' . $address->line_one . '</div>' . "\n";
								if ($address->line_two != NULL) echo '<div class="extended-address">' . $address->line_two . '</div>' . "\n";
								if ($address->city != NULL) echo '<span class="locality">' . $address->city . '</span>&nbsp;' . "\n";
								if ($address->state != NULL) echo '<span class="region">' . $address->state . '</span>&nbsp;' . "\n";
								if ($address->zipcode != NULL) echo '<span class="postal-code">' . $address->zipcode . '</span><br />' . "\n";
								if ($address->country != NULL) echo '<span class="country-name">' . $address->country . '</span>' . "\n";
								//echo $this->gethCardAdrType($address->type);
							echo '</div>' . "\n\n";
						}
					}
				?>
			</div>
			
			<div style="clear:both; margin: 5px 5px;">
	        	<?php
					if ($entry->getPhoneNumbers())
					{
						echo '<div class="phone_numbers" style="margin-bottom: 10px;">' . "\n";
						foreach ($entry->getPhoneNumbers() as $phoneNumber) 
						{
							if ($phoneNumber->number != null)
							{
								switch ($phoneNumber->type)
								{
									case 'home':
										$phoneNumberName = 'Telefoon';
										break;
									case 'homephone':
										$phoneNumberName = 'Telefoon';
										break;
									case 'homefax':
										$phoneNumberName = 'Fax';
										break;
									case 'cell':
										$phoneNumberName = 'Mobiele Telefoon';
										break;
									case 'cellphone':
										$phoneNumberName = 'Mobiele Telefoon';
										break;
									case 'work':
										$phoneNumberName = 'Telefoon';
										break;
									case 'workphone':
										$phoneNumberName = 'Telefoon';
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
				
				<?php
					if ($entry->getEmailAddresses())
					{
						echo '<div class="email-address-block">' . "\n";
						foreach ($entry->getEmailAddresses() as $emailRow)
						{
							//Type for hCard compatibility. Hidden.
							if ($emailRow->address != NULL) echo '<strong>Email: </strong><span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailRow->address . '">' . $emailRow->address . '</a></span><br /><br />' . "\n";
						}
						echo '</div>' . "\n";
					}
				?>
				
				<?php echo $entry->getImBlock() ?>
				<?php echo $entry->getSocialMediaBlock() ?>
				<?php echo $entry->getWebsiteBlock() ?>
				
			</div>
        </td>
    </tr>
    
	<tr>
		<td colspan="2">
			<div style="margin: 5px 5px;">
				<?php echo $entry->getBio() ?>
			</div>
		</td>
	</tr>
	
    <tr>
        <td colspan="2" valign="bottom">
        	<?php echo $vCard->download(array('anchorText' => 'Toevoegen aan adresboek')) ?>
        </td>
    </tr>
</table>
</div>