<?php
/**
 * Archive Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Archive {

	/**
	 * @var array
	 */
	private $list;

	public function __construct() {
		$this->list = array();

		foreach(Resource::get('article') as $index => $value) {
			if(!isset($this->list[$value['year']]))
				$this->list[$value['year']] = array();

			$this->list[$value['year']][] = $value;
		}
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
		$max = 0;
		$count = 0;
		$total = count($this->list);
		
		foreach((array)$this->list as $index => $article_list) {
			IO::writeln(sprintf("Building archive/%s", $index));
			$max = $index > $max ? $index : $max;
			
			$container_data['title'] = 'Archive: ' . $index;
			$container_data['list'] = createDateList($article_list);
			$container_data['bar'] = array(
				'index' => $count + 1,
				'total' => $total
			);
			if(isset($this->list[$index - 1]))
				$container_data['bar']['next'] = array(
					'title' => $index - 1,
					'url' => $index - 1
				);
			if(isset($this->list[$index + 1]))
				$container_data['bar']['prev'] = array(
					'title' => $index + 1,
					'url' => $index + 1
				);
				
			$count++;

			$output_data['title'] = $container_data['title'];
			$output_data['block'] = Resource::get('block');
			$output_data['block']['container'] = bindData($container_data, THEME_TEMPLATE . 'Container/Archive.php');
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'archive/' . $index);

			// Sitemap
			Resource::append('sitemap', 'archive/' . $index);
		}
		
		copy(PUBLIC_FOLDER . 'archive/' . $max . '/index.html', PUBLIC_FOLDER . 'archive/index.html');
		Resource::append('sitemap', 'archive');
	}
}