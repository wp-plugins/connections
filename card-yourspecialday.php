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
    
    <tr>
        <td valign="bottom">
        	<?php echo $vCard->download() ?>
        </td>
		<td align="right" valign="bottom">
			<?php if ($entry->getBio() != '') { ?>
				
			<a href="#" id="close_description_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").live("click", function(e){
			jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").hide();
			jQuery("#description_block_<?php echo $entry->getId(); ?>").fadeOut();
			jQuery("a#description_link_<?php echo $entry->getId(); ?>").fadeIn();
			}); return false' style="display: none;">Close Supplier Info</a>
			
			<a href="#" id="description_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#description_link_<?php echo $entry->getId(); ?>").live("click", function(e){
			jQuery("a#description_link_<?php echo $entry->getId(); ?>").hide();
			jQuery("#description_block_<?php echo $entry->getId(); ?>").fadeIn();
			jQuery("a#close_description_link_<?php echo $entry->getId(); ?>").fadeIn();
			}); return false'>Show Supplier Info</a>
			
			<?php } ?>
        </td>
    </tr>
	
	<tr>
		<td colspan="2" style="border: medium none transparent;">
			<?php 
				if ($entry->getBio() != '')
				{
					echo '<div id="description_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Supplier Info:</strong><br />' . $entry->getBioBlock() . '</div>';
				}
			?>
		</td>
	</tr>
	
</table>
</div>