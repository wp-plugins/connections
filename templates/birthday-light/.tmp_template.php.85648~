<div class="cn_entry">
<table width="100%" border="0px" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" width="50%" valign="top">
        	
			<div>
        		<?php echo $entry->getCardImage() ?>
        	</div>
						
        </td>
		
        <td align="right" valign="top">
        	
			<?php
				if ($entry->getWebsites())
				{
					echo '<ul class="website-block">' . "\n";
					foreach ($entry->getWebsites() as $website)
					{
						if ($website->url != NULL) echo '<li class="website-address"><a class="url" href="' . $website->url . '" target="_blank">' . $website->url . '</a></li>' . "\n";
					}
					echo "</ul>" . "\n";
				}
			?>
			
			<?php echo $entry->getAddressBlock() ?>
        	
			<?php
				if ($entry->getPhoneNumbers())
				{
					echo '<ul class="phone-number-block">' . "\n";
					foreach ($entry->getPhoneNumbers() as $phone) 
					{
						//Type for hCard compatibility. Hidden.
						if ($phone->number != null) echo  '<li class="tel">' . $entry->gethCardTelType($phone->type) . '<span class="value">' .  $phone->number . '</span></li>' . "\n";
					}
					echo '</ul>' . "\n";
				}
			?>
			
        </td>
    </tr>
    
	<tr>
		<td colspan="2">
			
			<div class="cn_name"><?php echo $entry->getFullFirstLastNameBlock() ?></div>
			
			<?php echo $entry->getTitleBlock() ?>
			<?php echo $entry->getOrgUnitBlock() ?>
			
			<?php echo $entry->getBioBlock(); ?>	
			
			<?php echo $entry->getNotesBlock(); ?>
		</td>
	</tr>
	
    <tr>
        <td align="left" colspan="2" valign="bottom">
			<?php
			if ($entry->getSocialMedia())
			{
				$showSocialMediaNetwork = array('facebook', 'twitter');
				
				echo '<ul class="cn_social-media-icons">';
				
				foreach ($showSocialMediaNetwork as $networkID)
				{
					foreach ($entry->getSocialMedia() as $socialNetwork)
					{
						if ($socialNetwork->id != null && $socialNetwork->type == $networkID) echo '<li class="social-media-item"><a class="url uid ' . $socialNetwork->type . '" href="' . $socialNetwork->url . '" target="_blank" alt="' . $socialNetwork->name . '" title="' . $socialNetwork->name . '"></a></li>';
					}
				}
				
				$emailRow = array();
				$emailRow = $entry->getEmailAddresses();
				if ($emailRow[0]->address != NULL) echo '<li class="email"><span class="type" style="display: none;">INTERNET</span><a class="value email" href="mailto:' . $emailRow[0]->address . '"></a></li>' . "\n";
				
				echo '</ul>';
			}
			?>
        </td>
    </tr>
</table>
</div>