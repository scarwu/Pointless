<?php

class BlogPage {
	private $_list;
	
	public function __construct() {
		$source = Resource::get('source');
		$this->_list = $source['blogpage'];
	}
	
	public function getList() {
		return $this->_list;
	}
	
	public function sortList() {
		$this->_list = articleSort($this->_list);
	}
	
	public function gen($slider) {
		foreach((array)$this->_list as $index => $output_data) {
			NanoIO::writeln("Building " . $output_data['url']);
		
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'BlogPage.php');
			$output_data['slider'] = $slider;

			$result = bindData($output_data, THEME . 'index.php');
			writeTo($result, PUBLIC_FOLDER . $output_data['url']);
		}
	}
}
