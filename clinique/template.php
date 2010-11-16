<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<div style="width:49%; float:left">
		<?php echo $entry->getCardImage() ?>
		<div style="clear:both;"></div>
		<div style="margin-bottom: -28px;">
			<span style="font-size:larger;font-variant: small-caps"><font color="#ff6f30"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></font></span><br />
			
			<?php echo $entry->getTitleBlock() ?>

		<font color="#16457a"><?php echo $entry->getOrganization() ?><br>
<?php echo $entry->getCategoryBlock() ?></font>
			
		</div>
			
			<?php echo $entry->getAddressBlock() ?>

	</div>
		
	<div align="right">
	
		
		<?php echo $entry->getPhoneNumberBlock() ?>
<font color="grey"><?php echo $vCard->download() ?></font>
		<?php echo $entry->getEmailAddressBlock() ?>
		<?php echo $entry->getImBlock() ?>
		<?php $entry->getSocialMediaBlock() ?>
		<?php echo $entry->getWebsiteBlock() ?>
		
		<?php echo $entry->getBirthdayBlock('F j') ?>
	
		<div style="clear:both"></div>
	
	</div>
</div>