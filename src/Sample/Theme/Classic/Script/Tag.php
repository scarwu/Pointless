<?php
/**
 * Tag Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
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

		foreach(Resource::get('article') as $index => $value) {
			foreach($value['tag'] as $tag) {
				if(!isset($this->list[$tag]))
					$this->list[$tag] = array();

				$this->list[$tag][] = $value;
			}
		}

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
	public function gen() {
		$max = array(0, NULL);
		$count = 0;
		$total = count($this->list);
		$key = array_keys($this->list);
		
		foreach((array)$this->list as $index => $article_list) {
			IO::writeln('Building tag/' . $index);
			$max = count($article_list) > $max[0] ? array(count($article_list), $index) : $max;
			
			$container_data['title'] = 'Tag: ' . $index;
			$container_data['list'] = createDateList($article_list);
			$container_data['bar'] = array(
				'index' => $count + 1,
				'total' => $total
			);
			if(isset($key[$count - 1]))
				$container_data['bar']['prev'] = array(
					'title' => $key[$count - 1],
					'url' => $key[$count - 1]
				);
			if(isset($key[$count + 1]))
				$container_data['bar']['next'] = array(
					'title' => $key[$count + 1],
					'url' => $key[$count + 1]
				);
			
			$count++;
			
			$output_data['title'] = $container_data['title'];
			$output_data['block'] = Resource::get('block');
			$output_data['block']['container'] = bindData($container_data, THEME_TEMPLATE . 'Container/Tag.php');
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'tag/' . $index);

			// Sitemap
			Resource::append('sitemap', 'tag/' . $index);
		}

		if(file_exists(PUBLIC_FOLDER . 'tag/' . $max[1] . '/index.html')) {
			copy(PUBLIC_FOLDER . 'tag/' . $max[1] . '/index.html', PUBLIC_FOLDER . 'tag/index.html');
			Resource::append('sitemap', 'tag');
		}
	}
}
