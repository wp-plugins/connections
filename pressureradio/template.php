<div class="cn_entry">
<table width="100%" border="0px" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" width="50%" valign="top">
        	
			<div class="cn_name"><span class="cn_label">DJ Name:</span> <?php echo $entry->getFullFirstLastNameBlock() ?></div>
			
			<?php 
				if ( $entry->getBio() )
				{
					echo '<span class="cn_label">Bio:</span>' . $entry->getBioBlock();
				}
			?>	
			
			<?php 
				if ( $entry->getNotes() )
				{
					echo '<span class="cn_label">News:</span>' . $entry->getNotesBlock();
				}
			?>
						
        </td>
		
        <td align="right" valign="top">
        	<div>
        		<?php echo $entry->getCardImage() ?>
        	</div>
			
			
			<?php echo $entry->getBirthdayBlock('F j') ?>
        	
			<?php echo $entry->getWebsiteBlock() ?>
				
			</div>
        </td>
    </tr>
    
	<tr>
		<td align="right" colspan="2">
			<?php
			if ($entry->getSocialMedia())
			{
				$showSocialMediaNetwork = array('myspace', 'twitter', 'facebook', 'rss', 'podcast', 'soundcloud');
				
				echo '<ul class="cn_social-media-icons">';
				
				foreach ($showSocialMediaNetwork as $networkID)
				{
					foreach ($entry->getSocialMedia() as $socialNetwork)
					{
						if ($socialNetwork->id != null && $socialNetwork->type == $networkID) echo '<li class="social-media-item"><a class="url uid ' . $socialNetwork->type . '" href="' . $socialNetwork->url . '" target="_blank" alt="' . $socialNetwork->name . '" title="' . $socialNetwork->name . '"></a></li>';
					}
				}
				
				echo '</ul>';
			}
			?>
		</td>
	</tr>
	
    <tr>
        <td align="left" valign="bottom">
			<span class="cn_update-time" style="<?php echo $entry->getLastUpdatedStyle() ?>;">Updated <?php echo $entry->getHumanTimeDiff() ?> ago</span>
        </td>
		
		<td align="right" valign="bottom">
        	<?php echo $vCard->download() ?>
        </td>
    </tr>
</table>
</div>