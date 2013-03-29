<?php
/**
 * BlogPage Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class BlogPage {

	/**
	 * @var array
	 */
	private $list;
	
	public function __construct() {
		$this->list = Resource::get('blogpage');
	}
	
	/**
	 * Get List
	 *
	 * @return array
	 */
	public function getList() {
		return $this->list;
	}
	
	/**
	 * Generate Data
	 *
	 * @param string
	 */
	public function gen($side) {
		foreach((array)$this->list as $index => $output_data) {
			IO::writeln("Building " . $output_data['url']);
		
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'BlogPage.php');
			$output_data['side'] = $side;

			// Write HTML to Disk
			$result = bindData($output_data, THEME_PATH . 'index.php');
			writeTo($result, PUBLIC_FOLDER . $output_data['url']);

			// Sitemap
			Resource::set('sitemap', $output_data['url']);
		}
	}
}
