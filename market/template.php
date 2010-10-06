<div id="entry_id_<?php echo $entry->getId(); ?>" class="cn-entry">
	<table border="0px" bordercolor="#E3E3E3" cellspacing="0px" cellpadding="0px">
	    <tr>
	        <td colspan="4" width="76%">
	        	<h3 class="cn-name"><?php echo $entry->getFullFirstLastNameBlock() ?></h3>
	        </td>
	        <td width="12%">
	        	Website
	        </td>
			<td width="12%">
	        	View Ad
	        </td>
	    </tr>
	    
	    <tr>
	        <td colspan="2" width="30%">
	        	<?php echo $entry->getAddressBlock() ?>
				<?php echo $entry->getPhoneNumberBlock() ?>
	        </td>
			<td colspan="4" width="70%">
				<?php echo $entry->getBio() ?>
	        </td>
	    </tr>
		
	</table>
	
</div>