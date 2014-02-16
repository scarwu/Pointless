<?php
/**
 * Page Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Page {

	/**
	 * @var array
	 */
	private $list;
	
	public function __construct() {
		$this->list = Resource::get('article');
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
		$quantity = Resource::get('config')['article_quantity'];
		$total = ceil(count($this->list) / $quantity);
				
		for($index = 1;$index <= $total;$index++) {
			IO::writeln("Building page/$index");
			
			$data['bar'] = [
				'index' => $index,
				'total' => $total
			];
			$data['list'] = array_slice($this->list, $quantity * ($index - 1), $quantity);
			$data['url'] = "page/$index";
			$data['config'] = Resource::get('config');

			$container = bindData($data, THEME . '/Template/Container/Page.php');

			$data['block'] = Resource::get('block');
			$data['block']['container'] = $container;
			
			// Write HTML to Disk
			$result = bindData($data, THEME . '/Template/index.php');
			writeTo($result, TEMP . "/{$data['url']}");

			// Sitemap
			Resource::append('sitemap', $data['url']);
		}
		
		if(file_exists(TEMP . '/page/1/index.html')) {
			copy(TEMP . '/page/1/index.html', TEMP . '/page/index.html');
			copy(TEMP . '/page/1/index.html', TEMP . '/index.html');
			Resource::append('sitemap', 'page');
		}
	}
}
