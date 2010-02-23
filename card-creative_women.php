<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; font-size:11px; font-family:tahoma,arial,helvetica,tahoma,sans-serif; line-height: 1; margin:8px 0px; padding:6px; position: relative; width: 595px">
	<div style="width:170px; float:left">
		<?php echo $entry->getCardImage() ?>
		<div style="clear:both;"></div>
	
		<?php echo $entry->getAddressBlock() ?>
			
		<?php
		if ($entry->getPhoneNumbers())
		{
			$phoneNumberObject = new cnPhoneNumber();
			echo '<div class="phone_numbers" style="margin-bottom: 10px;">' . "\n";
			foreach ($entry->getPhoneNumbers() as $phoneNumberRow) 
			{
				if ($phoneNumberObject->getNumber($phoneNumberRow) != null)
				{
					switch ($phoneNumberRow['type'])
					{
						case 'homefax':
							$phoneNumberName = 'Fax';
							break;
						case 'workfax':
							$phoneNumberName = 'Fax';
							break;
						case 'fax':
							$phoneNumberName = 'Fax';
							break;
						default:
							$phoneNumberName = '';
							break;
					}
					
					if (!empty($phoneNumberName)) echo '<strong>' . $phoneNumberName . '</strong>: ';
					
					//Type for hCard compatibility. Hidden.
					echo  '<span class="tel">' . $entry->gethCardTelType($phoneNumberRow) . '<span class="value">' .  $phoneNumberObject->getNumber($phoneNumberRow) . '</span></span><br />' . "\n";
				}
			}
			echo '</div>' . "\n";
		}
		
		
		if ($entry->getEmailAddresses())
		{
			$emailAddressObject = new cnEmail();
			echo '<div class="email-addresses" style="margin-bottom: 10px;">' . "\n";
			foreach ($entry->getEmailAddresses() as $emailRow)
			{
				//Type for hCard compatibility. Hidden.
				if ($emailAddressObject->getAddress($emailRow) != null) echo '<span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailAddressObject->getAddress($emailRow) . '">' . $emailAddressObject->getAddress($emailRow) . '</a></span><br />' . "\n";
			}
			echo '</div>' . "\n";
		}
		?>
		
		<?php echo $entry->getImBlock() ?>
		
		<?php
			if ($entry->getWebsites())
			{
				$websiteObject = new cnWebsite;
				
				foreach ($entry->getWebsites() as $websiteRow)
				{
					if ($websiteObject->getAddress($websiteRow) != null) $anchorOut .= '<a class="url" href="' . $websiteObject->getAddress($websiteRow) . '" target="_blank">' . str_replace('http://', '', $websiteObject->getAddress($websiteRow)) . '</a>' . "\n";
					break; // Only show the first stored web address
				}
			}
			
			if (!empty($anchorOut))
			{
				echo '<div class="website" style="margin-bottom: 10px;">' . "\n";
				echo $anchorOut;
				echo '</div>' . "\n";
				unset($anchorOut);
			}
		?>
		
		<div style="margin-bottom: 10px;">
			<?php echo $entry->getBirthdayBlock('F j') ?>
			<?php echo $entry->getAnniversaryBlock() ?>
		</div>
		
		<div class="cn-meta" align="left">
			<span><?php echo $vCard->download() ?></span>
		</div>
		
	</div>
		
	<div align="left">
		
		<div style="margin-bottom: 5px;">
			<span style="font-size:larger;font-variant: small-caps;"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
		</div>
		
		<div style="margin-bottom: 5px;">
			<?php 
				echo '<span class="title">' . $entry->getTitle() . '</span>';
				if ($entry->getOrganization()) echo '<span>, </span>' . '<span class="org">' . $entry->getOrganization() . '</span>';
			?>
		</div>
		<?php 
			if ($entry->getBio()) echo '<strong>Biography:</strong> ';
			echo $entry->getBio();
		?>
	</div>
	<div style="clear:both"></div>
</div>