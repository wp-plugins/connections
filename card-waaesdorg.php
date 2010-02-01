<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="width: 100%">
	    <tr>
	        <td align="left" width="55%" valign="top">
	        	<div style="clear:both; margin: 0 5px;">
					<div style="margin-bottom: 10px;">
						<span style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
						
						<?php echo $entry->getTitleBlock() ?>
						<?php
							if ($entry->getOrganization() || $entry->getDepartment()) echo '<div class="org">' . "\n";
							if ($entry->getOrganization() && $entry->getEntryType() != 'organization') echo '<span class="organization-name">' . $entry->getOrganization() . '</span><br />' . "\n";
							if ($entry->getDepartment()) echo '<strong><span class="organization-unit">' . $entry->getDepartment() . '</span></strong><br />' . "\n";
							if ($entry->getOrganization() || $entry->getDepartment()) echo '</div>' . "\n";
						?>
					</div>
					
					<?php echo $entry->getAddressBlock() ?>
				</div>
	        </td>
	        <td align="right" valign="top" style="text-align: right;">
	        	<?php echo $entry->getCardImage() ?>
				
				<div style="clear:both; margin: 5px 5px;">
		        	<?php echo $entry->getConnectionGroupBlock() ?>
					
					<?php echo $entry->getPhoneNumberBlock() ?>
					<?php echo $entry->getEmailAddressBlock() ?>
					
					<?php echo $entry->getImBlock() ?>
					
					<?php
						if ($entry->getWebsites())
						{
							echo '<div style="margin-bottom: 10px;" class="websites">';
							foreach ($entry->getWebsites() as $website)
							{
								if ($website['address'] != null) echo '<a class="url" href="' . $website['address'] . '" target="_blank">Website</a>' . "\n";
								break; // Only show the first stored web address
							}
							echo '</div>';
						}
					?>
					
					
					<?php echo $entry->getBirthdayBlock('F j') ?>
					<?php echo $entry->getAnniversaryBlock() ?>
				</div>
	        </td>
	    </tr>
	    
	    <tr>
	        <td valign="bottom">
	        	<?php echo $vCard->download() ?>
	        </td>
			<td align="right" valign="bottom"  style="text-align: right;">
				
				<?php if ($entry->getNotes() != '') { ?>
				
				<a href="#" id="close_note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Specialization</a>
				
				<a href="#" id="note_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#note_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#note_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#note_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_note_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Specialization</a>
				
				<?php
					} 
					if ($entry->getBio() != '' && $entry->getNotes() != '') echo ' | ';
				?>
				<?php if ($entry->getBio() != '') { ?>
				
				<a href="#" id="close_bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeOut();
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false' style="display: none;">Hide Bio</a>
				
				<a href="#" id="bio_link_<?php echo $entry->getId(); ?>" onclick='jQuery("a#bio_link_<?php echo $entry->getId(); ?>").live("click", function(e){
				jQuery("a#bio_link_<?php echo $entry->getId(); ?>").hide();
				jQuery("#bio_block_<?php echo $entry->getId(); ?>").fadeIn();
				jQuery("a#close_bio_link_<?php echo $entry->getId(); ?>").fadeIn();
				}); return false'>Show Bio</a>
				
				<?php } ?>
				
				<?php echo $entry->returnToTopAnchor() ?>
				
	        </td>
	    </tr>
		
		<tr>
			<td colspan="2">
				<?php 
					if ($entry->getNotes() != '')
					{
						echo '<div id="note_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Academic&#47;Scientific Speciality</strong><br />' . $entry->getNotesBlock() . '</div>';
					}
					
					if ($entry->getBio() != '')
					{
						echo '<div id="bio_block_' . $entry->getId() . '" style="display: none; margin: 10px 0 0 0;"><strong>Biography</strong><br />' . $entry->getBioBlock() . '</div>';
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