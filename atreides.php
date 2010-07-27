<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #DEEFF9; margin:8px 0px; padding:6px; position: relative; text-align: left;">
	<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px" style="width: 100%">
	    
		<tr>
			<td style="vertical-align: top; width: 160px;">
				<?php
				
				if ( $entry->getImageLinked() && $entry->getImageDisplay() )
				{
					echo '<img class="photo" alt="Photo of ' . $entry->getFullFirstLastName() . '" style="-moz-border-radius:4px; background-color: #FFFFFF; border:1px solid #DEEFF9; margin-bottom:10px; padding:5px;" src="' . CN_IMAGE_BASE_URL . $entry->getImageNameCard() . '" />';
				}
				
				?>
			</td>
			
			<td style="padding: 0 10px; vertical-align: top; width: 460px;">
				<strong><?php 
								$websites = $entry->getWebsites();
								
								if ( isset($websites[0]->url) )
								{
									echo '<a href="' . $websites[0]->url . '" title="Click here for more details on ' . $entry->getFullFirstLastName() . '.">' . $entry->getFullFirstLastNameBlock() . '</a>';
								}
								else
								{
									echo $entry->getFullFirstLastNameBlock();
								}
						?>
				</strong>
				
				<?php echo '<div id="bio_block_' . $entry->getId() . '" style="margin: 10px 0 0 0;">' . $entry->getBioBlock() . '</div>'; ?>
			</td>
			
			<td  style="border-left: 1px solid #DEEFF9; padding: 0 10px; vertical-align: top;">
				<?php echo '<div id="bio_block_' . $entry->getId() . '">' . $entry->getNotesBlock() . '</div>'; ?>
				
				<?php
					if ( isset($websites[0]->url) )
					{
				?>
					<p style="margin: 0; text-align: right;">
						<a href="<?php echo $websites[0]->url ?>"><img alt="More Details" src="<?php echo WP_CONTENT_URL ?>/connections_templates/more-details-button.jpg" title="Click here for more details on <?php echo $entry->getFullFirstLastName() ?>." /></a>
					</p>
				<?php
					}
				?>
			</td>
		</tr>
		
		<?php unset($websites); ?>
		
	</table>
</div>