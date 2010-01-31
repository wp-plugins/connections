<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="width: 100%">
	    <tr>
	        <td align="left" width="55%" valign="top">
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
	        <td align="right" valign="top" style="text-align: right;">
	        	<div style="clear:both; margin: 5px 5px;">
		        	<?php echo $entry->getConnectionGroupBlock() ?>
					
					<?php echo $entry->getPhoneNumberBlock() ?>
					<?php echo $entry->getEmailAddressBlock() ?>
					
					<?php echo $entry->getImBlock() ?>
					<?php echo $entry->getWebsiteBlock() ?>
					
					<?php echo $entry->getBirthdayBlock('F j') ?>
					<?php echo $entry->getAnniversaryBlock() ?>
				</div>
	        </td>
	    </tr>
	    
	    <tr>
	        <td valign="bottom">
	        	<?php echo $vCard->download() ?><?php if (!empty($anchorOut)) echo ' | ' . $anchorOut; ?>
	        </td>
			<td align="right" valign="bottom"  style="text-align: right;">
				
				<?php if ($entry->getNotes() != '') { ?>
				
				<a href="#" id="close_note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Academic/Scientific Speciality</a>
				
				<a href="#" id="note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Academic/Scientific Speciality</a> | 
				
				<?php } ?>
				
	        </td>
	    </tr>
		
		<tr>
			<td colspan="2">
				<?php 
					if ($entry->getNotes() != '')
					{
						echo '<div id="note_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;">' . $entry->getNotesBlock() . '</div>';
					}
				?>
			</td>
		</tr>
		
	</table>
	<?php
		unset($anchorOut);
		unset($data);
	?>
</div>