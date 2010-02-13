<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; font-size:11px; font-family:tahoma,arial,helvetica,tahoma,sans-serif; line-height: 1; margin:8px 0px; padding:6px; position: relative; width: 595px">
	<div style="width:170px; float:left">
		<?php echo $entry->getCardImage() ?>
		<div style="clear:both;"></div>
		
		<div class="cn-meta" align="left">
			<span><?php echo $vCard->download() ?></span>
		</div>
		
	</div>
		
	<div align="left" style="width:220px; float:left">
		
		<div class="phone_numbers" style="margin-bottom: 5px;">
			<span style="font-size:larger;font-variant: small-caps;"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
		</div>
		
		<?php echo '<div style="margin-bottom: 5px;">' . $entry->getTitleBlock() . ', ' . $entry->getOrganizationBlock() . '</div>' ?>
		
		
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
						case 'home':
							$phoneNumberName = 'Phone';
							break;
						case 'homephone':
							$phoneNumberName = 'Phone';
							break;
						case 'homefax':
							$phoneNumberName = 'Fax';
							break;
						case 'cell':
							$phoneNumberName = 'Phone';
							break;
						case 'cellphone':
							$phoneNumberName = 'Phone';
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
					echo  '<strong>' . $phoneNumberName . '</strong>: <span class="tel">' . $entry->gethCardTelType($phoneNumberRow) . '<span class="value">' .  $phoneNumberObject->getNumber($phoneNumberRow) . '</span></span><br />' . "\n";
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
				switch ($data['type'])
				{
					case 'personal':
						$emailName = "Email";
						break;
					case 'work':
						$emailName = "Email";
						break;
					default:
						$emailName = 'Email';
					break;
				}
				
				//Type for hCard compatibility. Hidden.
				if ($emailAddressObject->getAddress($emailRow) != null) echo '<strong>' . $emailName . ': </strong><span class="email"><span class="type" style="display: none;">INTERNET</span><a class="value" href="mailto:' . $emailAddressObject->getAddress($emailRow) . '">' . $emailAddressObject->getAddress($emailRow) . '</a></span><br />' . "\n";
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
					if ($websiteObject->getAddress($websiteRow) != null) $anchorOut .= '<a class="url" href="' . $websiteObject->getAddress($websiteRow) . '" target="_blank">' . $websiteObject->getAddress($websiteRow) . '</a>' . "\n";
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
		
		<?php echo $entry->getBirthdayBlock('F j') ?>
		<?php echo $entry->getAnniversaryBlock() ?>
		
	</div>
	
	<div style="width:200px; float:left; text-align:left;">
		<?php 
			if ($entry->getBio()) echo '<strong>Biography:</strong> ';
			echo $entry->getBio();
		?>
	</div>
	
	<div style="clear:both"></div>
	
	
</div>