<?php

class Article {

	/**
	 * 
	 */
	private $list;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->list = articleSort(Resource::get('article'));
	}
	
	/**
	 * 
	 */
	public function getList() {
		return $this->list;
	}
	
	/**
	 * 
	 */
	public function gen($slider) {
		$total = count($this->list);

		foreach((array)$this->list as $index => $output_data) {
			NanoIO::writeln("Building article/" . $output_data['url']);
			
			$output_data['bar'] = array(
				'index' => $index + 1,
				'total' => $total
			);
			if(isset($this->list[$index - 1]))
				$output_data['bar']['prev'] = array(
					'title' => $this->list[$index - 1]['title'],
					'url' => $this->list[$index - 1]['url']
				);
			if(isset($this->list[$index + 1]))
				$output_data['bar']['next'] = array(
					'title' => $this->list[$index + 1]['title'],
					'url' => $this->list[$index + 1]['url']
				);

			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Article.php');
			$output_data['slider'] = $slider;
			
			// Write HTML to Disk
			$result = bindData($output_data, THEME_FOLDER . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'article/' . $output_data['url']);

			// Sitemap
			Resource::set('sitemap', 'article/' . $output_data['url']);
		}
	}
}
