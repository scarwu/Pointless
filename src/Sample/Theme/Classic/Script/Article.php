<?php
/**
 * Article Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

use NanoCLI\IO;

class Article {

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
		$total = count($this->list);

		foreach((array)$this->list as $index => $container_data) {
			IO::writeln('Building article/' . $container_data['url']);
			
			$container_data['bar'] = array(
				'index' => $index + 1,
				'total' => $total
			);
			if(isset($this->list[$index - 1]))
				$container_data['bar']['prev'] = array(
					'title' => $this->list[$index - 1]['title'],
					'url' => $this->list[$index - 1]['url']
				);
			if(isset($this->list[$index + 1]))
				$container_data['bar']['next'] = array(
					'title' => $this->list[$index + 1]['title'],
					'url' => $this->list[$index + 1]['url']
				);

			$output_data['title'] = $container_data['title'];
			$output_data['block'] = Resource::get('block');
			$output_data['block']['container'] = bindData($container_data, THEME_TEMPLATE . 'Container/Article.php');
			$output_data['keywords'] = $container_data['keywords'];
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'article/' . $container_data['url']);

			// Sitemap
			Resource::append('sitemap', 'article/' . $container_data['url']);
		}
	}
}
