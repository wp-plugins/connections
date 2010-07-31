<?php
class cnTemplate
{
	public $name;
	public $uri;
	public $version;
	public $author;
	public $path;
	public $css;
	
	private $templateDir;
	
	public function __construct($filename)
	{
		$this->templateDir = CN_TEMPLATE_PATH . '/' . $filename . '/';
		
		$this->path = $this->setTemplatePath();
		$this->css = $this->setCSSPath();
	}
	
	private function setTemplatePath()
	{
		return $this->templateDir . 'index.php';
	}
	
	private function setCSSPath()
	{
		if ( file_exists( $this->templateDir . 'styles.css' ) )
		{
			return $this->templateDir . 'styles.css';
		}
	}
}
?>