<?php
/**
 * Register the tabs, settings sections and section settings.
 * 
 * @author Steven A. Zahm
 * @since 0.7.3.0
 */
class cnRegisterSettings
{
	/**
	 * Register the tabs for the Connections : Settings admin page.
	 * 
	 * @author Steven A. Zahm
	 * @since 0.7.3.0
	 * @param $tabs array
	 * @return array
	 */
	public function registerSettingsTabs( $tabs )
	{
		global $connections;
		
		$settings = $connections->pageHook->settings;
		
		// Register the core tab banks.
		$tabs[] = array( 
			'id' => 'general' , 
			'position' => 10 ,
			'title' => __( 'General' , 'connections' ) , 
			'page_hook' => $settings
		);
		
		$tabs[] = array( 
			'id' => 'image' , 
			'position' => 20 ,
			'title' => __( 'Images' , 'connections' ) , 
			'page_hook' => $settings
		);
		
		$tabs[] = array( 
			'id' => 'advanced' , 
			'position' => 30 ,
			'title' => __( 'Advanced' , 'connections' ) , 
			'page_hook' => $settings
		);
		
		return $tabs;
	}
	
	/**
	 * Register the settings sections.
	 * 
	 * @author Steven A. Zahm
	 * @since 0.7.3.0
	 * @param array $sections
	 * @return array
	 */
	public function registerSettingsSections( $sections )
	{
		global $connections;
		
		$settings = $connections->pageHook->settings;
		
		// Register the core setting sections.
		$sections[] = array( 
			'tab' => 'general' ,
			'id' => 'general-public-entries' , 
			'position' => 10 , 
			'title' => __( 'Public Entries' , 'connections' ) , 
			'callback' => 'cnRegisterSettings::displaySection' , 
			'page_hook' => $settings );
		
		return $sections;
	}
	
	public function displaySection()
	{
		echo '<p>TEST</p>';
	}
}
?>