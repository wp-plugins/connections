<div class="cnitem" id="cn <?php echo $entry->getId() ?>" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<div style="width:49%; float:left">
		<?php echo $entry->getCardImage() ?>
		<div style="clear:both;"></div>
		<div style="margin-bottom: 10px;">
			<span class="name" id="<?php echo $entry->getId() ?>" style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastName() ?></strong></span><br />
			
			<?php echo $entry->getTitleBlock() ?><br />
			<?php echo $entry->getOrganizationBlock() ?><br />
			<?php echo $entry->getDepartmentBlock() ?><br />
			
		</div>
			
			<?php echo $entry->getAddressBlock() ?>
			
		</div>
		
		<div align="right">
		
			<?php echo $entry->getPhoneNumberBlock() ?>
			<?php echo $entry->getEmailAddressBlock() ?>
			<?php echo $entry->getImBlock() ?>
			<?php echo $entry->getWebsiteBlock() ?>
			
			<?php echo $entry->getBirthdayBlock('F j') ?>
			<?php echo $entry->getAnniversaryBlock() ?>
			
			<?php if (!$atts['id']) echo '<br /><span style="' . $entry->getLastUpdatedStyle() . '; font-size:x-small; font-variant: small-caps; position: absolute; right: 26px; bottom: 8px;">Updated ' . $entry->getHumanTimeDiff() . ' ago</span><span style="position: absolute; right: 3px; bottom: 5px;">' . $entry->returnToTopAnchor() .'</span><br />'; ?>
			<?php if ($atts['id']) echo '<br /><span style="' . $entry->getLastUpdatedStyle() . '; font-size:x-small; font-variant: small-caps; position: absolute; right: 6px; bottom: 8px;">Updated ' . $entry->getHumanTimeDiff() . ' ago</span><br />'; ?>
		</div>
	<div style="clear:both"></div>
</div>