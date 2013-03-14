<?php

class BlogPage {

	/**
	 * 
	 */
	private $list;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->list = Resource::get('blogpage');
	}
	
	/**
	 * 
	 */
	public function getList() {
		return $this->list;
	}
	
	/**
	 * 
	 */
	public function gen($slider) {
		foreach((array)$this->list as $index => $output_data) {
			NanoIO::writeln("Building " . $output_data['url']);
		
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'BlogPage.php');
			$output_data['slider'] = $slider;

			// Write HTML to Disk
			$result = bindData($output_data, THEME_FOLDER . 'index.php');
			writeTo($result, PUBLIC_FOLDER . $output_data['url']);

			// Sitemap
			Resource::set('sitemap', $output_data['url']);
		}
	}
}