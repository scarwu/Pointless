<?php
/**
 * Page Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
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
		$total = ceil(count($this->list) / ARTICLE_QUANTITY);
				
		for($index = 1;$index <= $total;$index++) {
			IO::writeln('Building page/' . $index);
			
			$container_data['bar'] = array(
				'index' => $index,
				'total' => $total
			);
			$container_data['list'] = array_slice($this->list, ARTICLE_QUANTITY * ($index - 1), ARTICLE_QUANTITY);

			$output_data['block'] = Resource::get('block');
			$output_data['block']['container'] = bindData($container_data, THEME_TEMPLATE . 'Container/Page.php');
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'page/' . $index);

			// Sitemap
			Resource::append('sitemap', 'page/' . $index);
		}
		
		copy(PUBLIC_FOLDER . 'page/1/index.html', PUBLIC_FOLDER . 'index.html');
		Resource::append('sitemap', 'page');
	}
}
