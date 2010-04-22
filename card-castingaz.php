<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
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
				
				<?php echo $entry->getAddressBlock() ?>
			</div>
        </td>
        <td align="right" valign="top">
        	<div style="clear:both; margin: 5px 5px;">
	        	<?php echo $entry->getConnectionGroupBlock() ?>
				
				<?php echo $entry->getPhoneNumberBlock() ?>
				<?php echo $entry->getEmailAddressBlock() ?>
				
				<?php echo $entry->getImBlock() ?>
				<?php echo $entry->getSocialMediaBlock() ?>
				<?php echo $entry->getWebsiteBlock() ?>
				
				<?php echo $entry->getBirthdayBlock('F j') ?>
				<?php echo $entry->getAnniversaryBlock() ?>
			</div>
        </td>
    </tr>
	
	<?php
	if ($entry->getBio() != '' || $entry->getNotes() != '')
	{
	?>
		<tr>
			<td colspan="2">
				<div style="margin: 0px 5px 10px;">
					<?php if ($entry->getBio() != '') {
						echo '<strong>Bio:</strong><br />';
						echo $entry->getBio();
					}
					?>
				</div>
				
				<div style="margin: 0px 5px 10px;">
					<?php if ($entry->getNotes() != '') {
						echo '<strong>Notes:</strong><br />';
						echo $entry->getNotes();
					}
					?>
				</div>
			</td>
		</tr>
	    <?php
	}
	?>
	
    <tr>
        <td valign="bottom">
        	<?php echo $vCard->download() ?>
        </td>
		<td align="right" valign="bottom">
			<span style="<?php echo $entry->getLastUpdatedStyle() ?>; font-size:x-small; font-variant: small-caps;">Updated <?php echo $entry->getHumanTimeDiff() ?> ago</span>
			<?php echo $entry->returnToTopAnchor() ?>
        </td>
    </tr>
</table>
</div>