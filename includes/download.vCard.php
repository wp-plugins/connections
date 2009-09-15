<?php
	session_start();
	
	$uid = $_GET['uid'];
	$filename = $_SESSION['vcard'][$uid]['filename'];
	
	header("Content-type: text/directory");
	header("Content-Disposition: attachment; filename=" . $filename . ".vcf");
	header("Pragma: public");

	print_r($_SESSION['vcard'][$uid]['data']);

?>