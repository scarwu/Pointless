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
	public function gen() {
		foreach((array)$this->list as $index => $container_data) {
			IO::writeln("Building " . $container_data['url']);
			
			$output_data['title'] = $container_data['title'];
			list($output_data['block']) = Resource::get('block');
			$output_data['block']['container'] = bindData($container_data, THEME_CONTAINER . 'BlogPage.php');

			// Write HTML to Disk
			$result = bindData($output_data, THEME_PATH . 'index.php');
			writeTo($result, PUBLIC_FOLDER . $container_data['url']);

			// Sitemap
			Resource::set('sitemap', $container_data['url']);
		}
	}
}
