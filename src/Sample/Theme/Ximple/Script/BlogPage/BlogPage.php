<?php

class BlogPage {
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
	
	public function gen($slider) {
		foreach((array)$this->_list as $index => $output_data) {
			NanoIO::writeln("Building " . $output_data['url']);
		
			$output_data['container'] = bindData($output_data, THEME_TEMPLATE . 'Container/BlogPage.php');
			$output_data['slider'] = $slider;

			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_FOLDER . $output_data['url']);
		}
	}
}
