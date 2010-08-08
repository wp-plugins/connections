<?php
class cnTemplate
{
	public $name;
	public $uri;
	public $version;
	public $author;
	public $description;
	public $file;
	public $css;
	public $path;
	
	public function __construct($attr)
	{
		$this->path = $attr['template_path'] . '/';
		
		$this->loadMeta();
		$this->file = $this->setTemplatePath();
		$this->css = $this->setCSSPath();
	}
	
	private function loadMeta()
	{
		include_once( $this->path . 'meta.php' );
		
		$this->name = $template->name;
		$this->uri = $template->uri;
		$this->version = $template->version;
		$this->author = $template->author;
		$this->description = $template->description;
	}
	
	private function setTemplatePath()
	{
		if ( file_exists( $this->path . 'template.php' ) )
		{
			return $this->path . 'template.php';
		}
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