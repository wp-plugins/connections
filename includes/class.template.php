<?php
class cnTemplate
{
	public $name;
	public $slug;
	public $uri;
	public $version;
	public $author;
	public $description;
	public $file;
	public $css;
	public $path;
	
	public function __construct($attr)
	{
		$this->path = $attr['template_path'];
		$this->slug = $attr['slug'];
		
		$this->loadMeta();
		$this->file = $this->setTemplatePath();
		$this->css = $this->setCSSPath();
	}
	
	private function loadMeta()
	{
		include_once( $this->path . $this->slug . '/' . 'meta.php' );
		
		$this->name = $template->name;
		$this->uri = $template->uri;
		$this->version = $template->version;
		$this->author = $template->author;
		$this->description = $template->description;
	}
	
	private function setTemplatePath()
	{
		if ( file_exists( $this->path . $this->slug . '/' . 'template.php' ) )
		{
			return $this->path . $this->slug . '/' . 'template.php';
		}
	}
	
	private function setCSSPath()
	{
		if ( file_exists( $this->path . $this->slug . '/' . 'styles.css' ) )
		{
			return $this->path . $this->slug . '/' . 'styles.css';
		}
	}
	
	public function getCSS()
	{
		$contents = file_get_contents( $this->css );
		
		if ( $this->path === CN_CUSTOM_TEMPLATE_PATH . '/' )
		{
			$path = CN_CUSTOM_TEMPLATE_URL  . '/' . $this->slug;
		}
		else
		{
			$path = CN_TEMPLATE_URL  . '/' . $this->slug;
		}
		
		return str_replace('%%PATH%%', $path, $contents);
	}
}
?>