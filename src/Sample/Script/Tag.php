<?php
/**
 * Tag Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Tag {

	/**
	 * @var array
	 */
	private $list;
	
	public function __construct() {
		$this->list = array();
		$source = Resource::get('article');

		foreach($source as $index => $value) {
			foreach($value['tag'] as $tag) {
				if(!isset($this->list[$tag]))
					$this->list[$tag] = array();

				$this->list[$tag][] = $value;
			}
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
	public function gen($slider) {
		$max = array(0, NULL);
		$count = 0;
		$total = count($this->list);
		$key = array_keys($this->list);
		
		foreach((array)$this->list as $index => $article_list) {
			IO::writeln('Building tag/' . $index);
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
			
			$output_data['title'] = 'Tag: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Tag.php');
			$output_data['slider'] = $slider;
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_FOLDER . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'tag/' . $index);

			// Sitemap
			Resource::set('sitemap', 'tag/' . $index);
		}

		copy(PUBLIC_FOLDER . 'tag/' . $max[1] . '/index.html', PUBLIC_FOLDER . 'tag/index.html');
		Resource::set('sitemap', 'tag');
	}
}
