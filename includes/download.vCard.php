<?php
	if (empty($wp)) {
		require_once('wp-load.php');
		
		$token = esc_attr($_GET['token']);
		$id = (integer) esc_attr($_GET['entry']);
		
		if (! wp_verify_nonce($token, 'download_vcard_' . $id) ) wp_die('Invalid vCard Token');
		
		global $connections;
		
		$entry = $connections->retrieve->entry($id);
		$vCard = new cnvCard($entry);
		
		$filename = sanitize_file_name($vCard->getFullFirstLastName());
		
		header("Content-type: text/directory");
		header("Content-Disposition: attachment; filename=" . $filename . ".vcf");
		header("Pragma: public");
	
		echo $vCard->getvCard();
	}
?>