<table class="cn-entry">
	<tr>
		<td class="cn-info">
			<span class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?></span>
			<?php echo $entry->getTitleBlock() ?>
			<?php echo $entry->getOrgUnitBlock() ?>
			
			<?php echo $entry->getPhoneNumberBlock() ?>
			<?php echo $entry->getEmailAddressBlock() ?>
			
		</td>
		
		<td class="cn-photo">
			<?php echo $entry->getCardImage() ?>
		</td>
	</tr>
</table>
<hr>