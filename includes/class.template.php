<?php
class cnTemplate
{
	public $name;
	public $uri;
	public $version;
	public $author;
	public $file;
	public $css;
	public $path;
	
	public function __construct($filename)
	{
		$this->path = CN_TEMPLATE_PATH . '/' . $filename . '/';
		
		$this->file = $this->setTemplatePath();
		$this->css = $this->setCSSPath();
	}
	
	private function setTemplatePath()
	{
		return $this->path . 'template.php';
	}
	
	private function setCSSPath()
	{
		if ( file_exists( $this->path . 'styles.css' ) )
		{
			return $this->path . 'styles.css';
		}
	}
}
?>