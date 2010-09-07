<div class="cn-entry" style="-moz-border-radius:4px; background-color:#FFFFFF; border:1px solid #E3E3E3; margin:8px 0px; padding:6px; position: relative;">
<table width="100%" border="0px" bgcolor="#FFFFFF" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="left" width="50%" valign="top">
        	
			<div style="clear:both; margin: 10px 5px 0;">
				<span style="font-size:larger;font-variant: small-caps"><strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong></span><br />
			</div>
				
			<?php echo $entry->getCardImage() ?>
			
        </td>
        <td align="right" valign="top">
        	<div style="clear:both; margin: 5px 5px;">
	        	
				<?php echo $entry->getPhoneNumberBlock() ?>
				
				<?php
				if ( $entry->getBirthday() )
				{
				?>
					<span class="vevent"><span class="birthday"><strong>Geboortedatum:</strong> <abbr class="dtstart" title="<?php echo $entry->getBirthday('Ymd') ?>"><?php echo $entry->getBirthday('F j') ?></abbr></span>
				<?php
				}
				?>
				
			</div>
        </td>
    </tr>
    
    <tr>
        <td colspan="2" valign="bottom">
        	<?php echo $vCard->download() ?>
        </td>
    </tr>
</table>
</div>