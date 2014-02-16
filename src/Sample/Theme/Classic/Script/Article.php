<?php
/**
 * Article Data Generator Script for Theme
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
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
		$count = 0;
		$total = count($this->list);
		$key = array_keys($this->list);

		foreach((array)$this->list as $data) {
			IO::writeln("Building article/{$data['url']}");
			
			$data['bar'] = [
				'index' => $count + 1,
				'total' => $total
			];
			if(isset($key[$count - 1])) {
				$data['bar']['prev'] = [
					'title' => $this->list[$key[$count - 1]]['title'],
					'url' => $this->list[$key[$count - 1]]['url']
				];
			}
			if(isset($key[$count + 1])) {
				$data['bar']['next'] = [
					'title' => $this->list[$key[$count + 1]]['title'],
					'url' => $this->list[$key[$count + 1]]['url']
				];
			}

			$count++;

			$data['keywords'] = $data['keywords'];
			$data['url'] = "article/{$data['url']}";
			$data['config'] = Resource::get('config');

			$container = bindData($data, THEME . '/Template/Container/Article.php');

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
