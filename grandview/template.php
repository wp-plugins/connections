<table class="cn-entry">
	<tr>
		<td class="cn-photo">
			<?php echo $entry->getCardImage() ?>
		</td>
		<td class="cn-info">
			<span class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?> <?php echo $entry->getTitleBlock() ?></span>
			
			<?php echo $entry->getOrgUnitBlock() ?>
			
			<?php echo $entry->getAddressBlock() ?>
			<?php echo $entry->getPhoneNumberBlock() ?>
			<?php echo $entry->getEmailAddressBlock() ?>
			<?php echo $entry->getWebsiteBlock() ?>
			
			<?php echo $entry->getBioBlock() ?>
		</td>
	</tr>
</table>
<hr>