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
		$this->list = articleSort(Resource::get('article'));
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
	public function gen($slider) {
		$total = ceil(count($this->list) / ARTICLE_QUANTITY);
				
		for($index = 1;$index <= $total;$index++) {
			IO::writeln('Building page/' . $index);
			
			$output_data['bar'] = array(
				'index' => $index,
				'total' => $total
			);
			$output_data['article_list'] = array_slice($this->list, ARTICLE_QUANTITY * ($index - 1), ARTICLE_QUANTITY);
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Page.php');
			$output_data['slider'] = $slider;
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_PATH . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'page/' . $index);

			// Sitemap
			Resource::set('sitemap', 'page/' . $index);
		}
		
		copy(PUBLIC_FOLDER . 'page/1/index.html', PUBLIC_FOLDER . 'index.html');
		Resource::set('sitemap', 'page');
	}
}
