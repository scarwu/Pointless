<?php
/**
 * Category Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Category {

	/**
	 * @var array
	 */
	private $list;
	
	public function __construct() {
		$this->list = array();
		$source = Resource::get('article');

		foreach($source as $index => $value) {
			if(!isset($this->list[$value['category']]))
				$this->list[$value['category']] = array();

			$this->list[$value['category']][] = $value;
		}

		// Sort
		$this->list = countSort($this->list);
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
		$max = array(0, NULL);
		$count = 0;
		$total = count($this->list);
		$key = array_keys($this->list);
		
		foreach((array)$this->list as $index => $article_list) {
			IO::writeln('Building category/' . $index);
			$max = count($article_list) > $max[0] ? array(count($article_list), $index) : $max;
			
			$output_data['bar'] = array(
				'index' => $count + 1,
				'total' => $total
			);
			if(isset($key[$count - 1]))
				$output_data['bar']['prev'] = array(
					'title' => $key[$count - 1],
					'url' => $key[$count - 1]
				);
			if(isset($key[$count + 1]))
				$output_data['bar']['next'] = array(
					'title' => $key[$count + 1],
					'url' => $key[$count + 1]
				);
			
			$count++;
			
			$output_data['title'] ='Category: ' . $index;
			$output_data['date_list'] = createDateList($article_list);
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Category.php');
			$output_data['side'] = $side;
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_PATH . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'category/' . $index);

			// Sitemap
			Resource::set('sitemap', 'category/' . $index);
		}

		copy(PUBLIC_FOLDER . 'category/' . $max[1] . '/index.html', PUBLIC_FOLDER . 'category/index.html');
		Resource::set('sitemap', 'category');
	}
}
