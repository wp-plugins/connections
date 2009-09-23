<div class="cn-entry" style="-moz-border-radius:4px; background-color:#7E6957; border:1px solid #E3E3E3; margin:8px 0px; padding:10px;">
<table width="100%" border="0px" bgcolor="#7E6957" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
    <tr>
        <td align="center" rowspan="4">
        	<?php echo $entry->getCardImage() ?>
        </td>
        <td align="right">
        	<div style="margin-bottom: 10px;">
	        	<strong>Services:</strong><br />
	        	<?php echo $entry->getNotes() ?>
			</div>
        </td>
    </tr>
    <tr>
        <td align="right">
        	<?php echo $entry->getEmailAddressBlock() ?>
			<?php echo $entry->getWebsiteBlock() ?>
        </td>
    </tr>
    <tr>
        <td align="right">
        	<?php echo $entry->getPhoneNumberBlock() ?>
        </td>
    </tr>
    <tr>
        <td align="right">
        	<div style="margin-bottom: 10px;">
	        	<strong>Hours:</strong><br />
	        	<?php echo $entry->getDepartment() ?>
			</div>
        </td>
    </tr>
    <tr>
        <td valign="top">
        	<strong><?php echo $entry->getFullFirstLastNameBlock() ?></strong><br />
			<strong>Name on door:</strong> <?php echo $entry->getOrganization() ?>
			<?php echo $entry->getTitleBlock() ?>
			<strong>Suite:</strong> <?php echo $entry->getAddressBlock() ?>
        </td>
        <td align="right">
        	<strong>Info:</strong><br />
			<div style="margin: 0 0 0 10px; text-align: left;">
        		<?php echo $entry->getBio() ?>
			</div>
        </td>
    </tr>
    <tr>
        <td>
        	<?php echo $vCard->download() ?>
        </td>
		<td align="right">
        	<?php echo $entry->returnToTopAnchor() ?>
        </td>
    </tr>
</table>
</div>