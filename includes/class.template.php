<?php
class cnTemplate
{
	/**
	 * Template Name.
	 * @var string
	 */
	public $name;
	
	/**
	 * Template slug [template directory]
	 * @var string
	 */
	public $slug;
	
	/**
	 * The template author's uri.
	 * @var
	 */
	public $uri;
	
	/**
	 * Template version.
	 * @var string
	 */
	public $version;
	
	/**
	 * Tamplate's author's name.
	 * @var string
	 */
	public $author;
	
	/**
	 * Template description.
	 * @var string
	 */
	public $description;
	
	/**
	 * Set TRUE if the template should use the legacy output from cnOutput.
	 * @var bool
	 */
	public $legacy;
	
	/**
	 * TRUE if the template is in the custom template directory.
	 * @var
	 */
	public $custom;
	
	/**
	 * Path to the template.php file.
	 * @var string
	 */
	public $file;
	
	/**
	 * The path to the template's CSS file.
	 * @var string
	 */
	public $css;
	
	/**
	 * The path to the template's Javascript file.
	 * @var string
	 */
	public $js;
	
	/**
	 * The template base path.
	 * @var string
	 */
	public $path;
	
	/**
	 * Stores the catalog of available templates when cnTemplate::buildCtalog() is called.
	 * 
	 * @var object
	 */
	private $catalog;
	
	/**
	 * Builds a catalog of all the available templates from
	 * the supplied and the custom template directories.
	 * 
	 * @return array
	 */
	public function buildCatalog()
	{
		/**
		 * --> START <-- Find the available templates
		 */
		$templatePaths = array(CN_TEMPLATE_PATH, CN_CUSTOM_TEMPLATE_PATH);
		$templates = new stdClass();
		
		foreach ($templatePaths as $templatePath)
		{
			if ( !is_dir($templatePath . '/') && !is_readable($templatePath . '/') ) continue;
			
			$templateDirectories = opendir($templatePath);
			
			while ( ( $templateDirectory = readdir($templateDirectories) ) !== FALSE )
			{
				if ( is_dir($templatePath . '/' . $templateDirectory) && is_readable($templatePath . '/' . $templateDirectory) )
				{
					if ( file_exists($templatePath . '/' . $templateDirectory . '/meta.php') &&
						 file_exists($templatePath . '/' . $templateDirectory . '/template.php') 
						)
					{
						$template = new stdClass();
						include($templatePath . '/' . $templateDirectory . '/meta.php');
						$template->slug = $templateDirectory;
						
						// Load the template metadate from the meta.php file
						
						if ( !isset($template->type) ) $template->type = 'all';
						
						$templates->{$template->type}->{$template->slug}->name = $template->name;
						$templates->{$template->type}->{$template->slug}->version = $template->version;
						$templates->{$template->type}->{$template->slug}->uri = 'http://' . $template->uri;
						$templates->{$template->type}->{$template->slug}->author = $template->author;
						$templates->{$template->type}->{$template->slug}->description = $template->description;
						
						( !isset($template->legacy) ) ? $templates->{$template->type}->{$template->slug}->legacy = TRUE : $templates->{$template->type}->{$template->slug}->legacy = $template->legacy;
						$templates->{$template->type}->{$template->slug}->slug = $template->slug;
						$templates->{$template->type}->{$template->slug}->custom = ( $templatePath === CN_TEMPLATE_PATH ) ? FALSE : TRUE;
						$templates->{$template->type}->{$template->slug}->path = $templatePath . '/' . $templateDirectory;
						$templates->{$template->type}->{$template->slug}->url = ( $templatePath === CN_TEMPLATE_PATH ) ? CN_TEMPLATE_URL . '/' . $templateDirectory : CN_CUSTOM_TEMPLATE_URL . '/' . $templateDirectory;
						$templates->{$template->type}->{$template->slug}->file = $templatePath . '/' . $templateDirectory . '/template.php';
						
						if ( file_exists( $templates->{$template->type}->{$template->slug}->path . '/' . 'styles.css' ) )
						{
							$templates->{$template->type}->{$template->slug}->css = $templates->{$template->type}->{$template->slug}->path . '/' . 'styles.css';
						}
						
						if ( file_exists( $templates->{$template->type}->{$template->slug}->path . '/' . 'template.js' ) )
						{
							$templates->{$template->type}->{$template->slug}->js = $templates->{$template->type}->{$template->slug}->path . '/' . 'template.js';
						}
						
						if ( file_exists( $templates->{$template->type}->{$template->slug}->path . '/' . 'thumbnail.png' ) )
						{
							$templates->{$template->type}->{$template->slug}->thumbnail_path = $templates->{$template->type}->{$template->slug}->path . '/' . 'thumbnail.png';
							
							if ( $templates->{$template->type}->{$template->slug}->custom )
							{
								$templates->{$template->type}->{$template->slug}->thumbnail_url = CN_CUSTOM_TEMPLATE_URL . '/' . $template->slug . '/' . 'thumbnail.png';
							}
							else
							{
								$templates->{$template->type}->{$template->slug}->thumbnail_url = CN_TEMPLATE_URL . '/' . $template->slug . '/' . 'thumbnail.png';
							}
							
						}
					}
				}
			}
			
			closedir($templateDirectories);
		}
		/**
		 * --> END <-- Find the available templates
		 */
		$this->catalog = $templates;
		
		return $templates;
	}
	
	/**
	 * Returns the catalog of templates by the supplied type.
	 * 
	 * @param string $type
	 * @return object
	 */
	public function getCatalog($type)
	{
		return $this->catalog->$type;
	}
	
	/**
	 * Loads the template based on the supplied directory name [$slug].
	 * This will search the both the default templates directory and
	 * the connections_templates directory in wp_content.
	 * 
	 * @param string $slug
	 */	
	public function load($slug)
	{
		$templatePaths = array(CN_CUSTOM_TEMPLATE_PATH, CN_TEMPLATE_PATH);
		
		foreach ($templatePaths as $templatePath)
		{
			if ( is_dir($templatePath . '/' .  $slug) && is_readable($templatePath . '/' .  $slug) )
			{
				if ( file_exists($templatePath . '/' . $slug . '/meta.php') &&
					 file_exists($templatePath . '/' . $slug . '/template.php' )
					)
				{
					$this->slug = $slug;
					$this->path = $templatePath . '/' .  $slug;
					$this->file = $this->path . '/template.php';
					$this->loadMeta($this->path . '/meta.php');
					
					$this->custom = ( $templatePath === CN_TEMPLATE_PATH ) ? FALSE : TRUE;
					if ( file_exists( $this->path . '/' . 'styles.css') ) $this->css = $this->path . '/' . 'styles.css';
					if ( file_exists( $this->path . '/' . 'template.js') ) $this->js = $this->path . '/' . 'template.js';
					
					break;
				}
			}
		}
		
	}
	
	/**
	 * Loads the meta data from the supplied file.
	 * 
	 * @param string $metaFile
	 */
	private function loadMeta($metaFile)
	{
		include_once( $metaFile );
		
		$this->name = $template->name;
		$this->uri = $template->uri;
		$this->version = $template->version;
		$this->author = $template->author;
		$this->description = $template->description;
		$this->legacy = $template->legacy;
	}
	
	/**
	 * Loads the CSS file while replacing %%PATH%% with the URL
	 * to the template.
	 * 
	 * @return string
	 */
	public function getCSS()
	{
		$contents = file_get_contents( $this->css );
		
		if ( $this->path === CN_CUSTOM_TEMPLATE_PATH . '/' . $this->slug )
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