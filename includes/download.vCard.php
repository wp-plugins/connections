<?php
    public function download()
	{
		if (!$this->filename) { $this->filename = trim($this->data['display_name']); }
		$this->filename = str_replace(" ", "_", $this->filename);
			header("Content-type: text/directory");
			header("Content-Disposition: attachment; filename=" . $this->filename . ".vcf");
			header("Pragma: public");
			echo $this->card;
		return true;
	}
?>