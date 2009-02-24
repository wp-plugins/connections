<?php
    //public function download()
	//{
		$filename = $_GET['filename'];
		$card = $_GET['card'];
		
		if (!$filename) { $filename = trim($data['display_name']); }
		$filename = str_replace(" ", "_", $filename);
			header("Content-type: text/directory");
			header("Content-Disposition: attachment; filename=" . $filename . ".vcf");
			header("Pragma: public");
			//echo $card;
			//print_r($_GET['filename']);
			print_r($_GET['card']);
		//return true;
	//}
?>