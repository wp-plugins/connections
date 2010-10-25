<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry">
	
	<?php
		$website = $entry->getWebsites();
		
		( !empty($website[0]->url) ) ? $websiteButton = "<p class=\"cn-buttons\"><a href=\"{$website[0]->url}\" target=\"_blank\"><img class=\"cn-viewsite-on\" src=\"{$template->url}/buttons.gif\" /></a></p>" :
									   $websiteButton = "<p class=\"cn-buttons\"><img class=\"cn-viewsite-off\" src=\"{$template->url}/buttons.gif\" /></p>";
									   
		( !empty($website[1]->url) ) ? $adButton = "<p class=\"cn-buttons\"><a href=\"{$website[1]->url}\"><img class=\"cn-viewad-on\" src=\"{$template->url}/buttons.gif\" /></a></p>" :
									   $adButton = "<p class=\"cn-buttons\"><img class=\"cn-viewad-off\" src=\"{$template->url}/buttons.gif\" /></p>";
	?>
	
	<table border="0px" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
	    <tr>
	        <td colspan="4" width="76%">
	        	<h3 class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?></h3>
	        </td>
	        <td width="12%">
	        	<?php echo $websiteButton; ?>
	        </td>
			<td width="12%">
	        	<?php echo $adButton; ?>
	        </td>
	    </tr>
	    
	    <tr>
	        <td colspan="2" width="30%">
	        	<?php
				if ($entry->getAddresses())
				{
					foreach ($entry->getAddresses() as $address)
					{
						echo '<div class="adr" style="margin-bottom: 10px;">' . "\n";
							if ($address->name != NULL || $address->type != NULL) echo '<span class="address_name"><strong>' . $address->name . '</strong></span><br />' . "\n"; //The OR is for compatiblity for 0.2.24 and under
							if ($address->line_one != NULL) echo '<div class="street-address">' . $address->line_one . '</div>' . "\n";
							if ($address->line_two != NULL) echo '<div class="extended-address">' . $address->line_two . '</div>' . "\n";
							if ($address->city != NULL) echo '<span class="locality">' . $address->city . '</span>&nbsp;' . "\n";
							if ($address->state != NULL) echo '<span class="region">' . $address->state . '</span>&nbsp;' . "\n";
							if ($address->zipcode != NULL) echo '<span class="postal-code">' . $address->zipcode . '</span>' . "\n";
							if ($address->country != NULL) echo '<span class="country-name">' . $address->country . '</span>' . "\n";
							echo $entry->gethCardAdrType($address->type);
						echo '</div>' . "\n\n";
																	
					}
				}
				?>
				<?php echo $entry->getPhoneNumberBlock() ?>
	        </td>
			<td colspan="4" width="70%">
				<?php echo $entry->getBio() ?>
	        </td>
	    </tr>
		
	</table>
	
</div>

<?php unset($website, $websiteButton, $adButton); ?>