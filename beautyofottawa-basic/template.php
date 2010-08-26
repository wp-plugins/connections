<div class="cn-entry basic">
<table border="0px" cellspacing="0px" cellpadding="0px">
	<tr>
		<td align="left" colspan="2" valign="top">
        	<div class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?></div>
        </td>
	</tr>
	
    <tr>
       <td align="left" width="49%" valign="top" class="left">
        	<?php echo $entry->getNotesBlock(); ?>
        </td>
		
		<td align="left" width="49%" valign="top" class="right">
			<?php
				if ($entry->getWebsites())
				{
					echo '<ul class="cn-website-block">' . "\n";
					foreach ($entry->getWebsites() as $website)
					{
						if ($website->url != NULL) echo '<li class="website-address"><span class="cn-label">Website:</span> <a class="url" href="' . $website->url . '" target="_blank">' . $website->url . '</a></li>' . "\n";
					}
					echo "</ul>" . "\n";
				}
				
				if ($entry->getPhoneNumbers())
				{
					echo '<ul class="cn-phone-number-block">' . "\n";
					foreach ($entry->getPhoneNumbers() as $phone) 
					{
						//Type for hCard compatibility. Hidden.
						if ($phone->number != null) echo  '<li class="tel">' . $entry->gethCardTelType($phone->type) . '<span class="value"><span class="cn-label">Phone: </span>' .  $phone->number . '</span></li>' . "\n";
					}
					echo '</ul>' . "\n";
				}
				
				if ($entry->getAddresses())
				{
					foreach ($entry->getAddresses() as $address)
					{
						echo '<div class="adr" style="margin-bottom: 10px;">' . "\n";
							echo '<span class="cn-label">Address:</span> ';
							if ($address->line_one != NULL) echo '<span class="street-address">' . $address->line_one . '</span>';
							if ($address->line_two != NULL) echo '<span class="extended-address">, ' . $address->line_two . '</span>';
							if ($address->city != NULL) echo '<span class="locality">, ' . $address->city . '</span>';
							if ($address->state != NULL) echo '<span class="region">, ' . $address->state . '</span>';
							if ($address->zipcode != NULL) echo '<span class="postal-code">, ' . $address->zipcode . '</span>';
							if ($address->country != NULL) echo '<span class="country-name">' . $address->country . '</span>';
							echo $entry->gethCardAdrType($address->type);
						echo '</div>' . "\n\n";
																	
					}
				}
			?>
        	
        </td>
		
    </tr>
    
</table>
</div>