<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" width="50%" valign="top">
        	<div style="clear:both; margin: 0 5px;">
				<div style="margin-bottom: 10px;">
					<h3><span style="text-transform: uppercase;"><?php echo $entry->getFullFirstLastNameBlock() ?></span></h3>
					
					<?php echo $entry->getTitleBlock() ?>
					<?php echo $entry->getOrgUnitBlock() ?>
				</div>
				<?php echo $entry->getThumbnailImage() ?>
			</div>
			
        </td>
        <td align="right" valign="top">
        	<div style="clear:both; margin: 5px 5px;">
	        	<?php echo $entry->getPhoneNumberBlock() ?>
				<?php echo $entry->getEmailAddressBlock() ?>
				<?php echo $entry->getWebsiteBlock() ?>
				<?php echo $entry->getImBlock() ?>
				<?php echo $entry->getSocialMediaBlock() ?>
			</div>
        </td>
    </tr>
    
	<tr>
		<td colspan="2">
			<?php echo $entry->getBioBlock() ?>
		</td>
	</tr>
	
    <tr>
        <td align="right" colspan="2" valign="bottom">
			<em><small>back to top</small></em><?php echo $entry->returnToTopAnchor() ?>
        </td>
    </tr>
</table>
</div>