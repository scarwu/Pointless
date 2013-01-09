<?php

class Article {
	private $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function add($article) {
		$this->_list[] = $article;
	}
	
	public function getList() {
		return $this->_list;
	}
	
	public function sortList() {
		$this->_list = articleSort($this->_list);
	}
	
	public function gen($slider) {
		$total = count($this->_list);

		foreach((array)$this->_list as $index => $output_data) {
			NanoIO::writeln("Building article/" . $output_data['url']);
			
			$output_data['bar'] = array();
			$output_data['bar']['index'] = $index+1;
			$output_data['bar']['total'] = $total;
			if(isset($this->_list[$index-1]))
				$output_data['bar']['prev'] = array(
					'title' => $this->_list[$index-1]['title'],
					'url' => $this->_list[$index-1]['url']
				);
			if(isset($this->_list[$index+1]))
				$output_data['bar']['next'] = array(
					'title' => $this->_list[$index+1]['title'],
					'url' => $this->_list[$index+1]['url']
				);

			$output_data['container'] = bindData($output_data, THEME_TEMPLATE . 'Container/Article.php');
			$output_data['slider'] = $slider;
			
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_ARTICLE . $output_data['url']);
		}
	}
}
