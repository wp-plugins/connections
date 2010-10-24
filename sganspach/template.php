<div class="cn-entry">
<table width="100%" border="0px" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" class="left" width="50%" valign="top">
        	<div style="clear:both; margin: 0 5px;">
				<div style="margin-bottom: 6px;">
					<h3 class="cn-name"><span style="text-transform: capitalize;"><?php echo $entry->getFullFirstLastNameBlock() ?></span></h3>
					
					<?php echo $entry->getTitleBlock() ?>
					<?php echo $entry->getOrgUnitBlock() ?>
				</div>
				<?php echo $entry->getCardImage() ?>
			</div>
			
        </td>
        <td class="right" align="right" valign="top">
        	<div style="clear:both; margin: 5px 5px;">
				<?php
				if ($entry->getAddresses())
				{
					foreach ($entry->getAddresses() as $address)
					{
						echo '<div class="adr">' . "\n";
							if ($address->line_one != NULL) echo '<span class="street-address">' . $address->line_one . '</span>' . "\n";
							if ($address->line_two != NULL) echo '<span class="extended-address">' . $address->line_two . '</span>' . "\n";
							if ($address->zipcode != NULL) echo '<span class="postal-code">' . $address->zipcode . '</span>&nbsp';
							if ($address->city != NULL) echo '<span class="locality">' . $address->city . '</span>' . "\n";
							echo $entry->gethCardAdrType($address->type);
						echo '</div>' . "\n\n";
																	
					}
				}
				?>
				
	        	<?php echo $entry->getPhoneNumberBlock() ?>
				
				<?php
				if ($entry->getEmailAddresses())
				{
					echo '<div class="email-address-block">' . "\n";
					foreach ($entry->getEmailAddresses() as $email)
					{
						//Type for hCard compatibility. Hidden.
						if ($email->address != NULL) echo '<span class="email"><strong>Email:</strong> <span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $email->address . '">' . $email->address . '</a></span>' . "\n";
					}
					echo '</div>' . "\n";
				}
				?>
				
				<?php
				if ($entry->getBirthday()) echo '<span class="vevent"><span class="birthday"><strong>Geburtstag:</strong> <abbr class="dtstart" title="' . $entry->getBirthday('Ymd') .'">' . $entry->getBirthday('d.m') . '</abbr></span>' .
										 '<span class="bday" style="display:none">' . $entry->getBirthday('Y-m-d') . '</span>' .
										 '<span class="summary" style="display:none">Birthday - ' . $entry->getFullFirstLastName() . '</span> <span class="uid" style="display:none">' . $entry->getBirthday('YmdHis') . '</span> </span><br />' . "\n";
				?>
			</div>
        </td>
    </tr>
    
    <tr>
        <td class="right" align="right" colspan="2" valign="bottom">
			<span class="cn-return-to-top">
				<a href="#connections-list-head" title="Zurück zum Anfang"><img src="<?php echo WP_PLUGIN_URL . '/connections/images/uparrow.gif' ?>" alt="Zurück zum Anfang"/></a>
			</span>
        </td>
    </tr>
</table>
</div>