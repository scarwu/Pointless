<?php
/**
 * Static Page Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class StaticPage {

	/**
	 * @var array
	 */
	private $list;
	
	public function __construct() {
		$this->list = Resource::get('static');
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
		foreach((array)$this->list as $data) {
			IO::writeln("Building {$data['url']}");
			
			$data['config'] = Resource::get('config');

			$container = bindData($data, THEME . '/Template/Container/StaticPage.php');

			$data['block'] = Resource::get('block');
			$data['block']['container'] = $container;

			// Write HTML to Disk
			$result = bindData($data, THEME . '/Template/index.php');
			writeTo($result, TEMP . "/{$data['url']}");

			// Sitemap
			Resource::append('sitemap', $data['url']);
		}
	}
}
