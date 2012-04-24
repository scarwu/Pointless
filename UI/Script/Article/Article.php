<?php

class Article {
	public $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function Add($article) {
		$this->_list[] = $article;
	}
	
	public function GetList() {
		$this->_list = article_sort($this->_list);
		return $this->_list;
	}
	
	public function Gen($slider) {
		$this->_list = article_sort($this->_list);
		// Building Article
		foreach((array)$this->_list as $index => $output_data) {
			Text::Write("Building article/" . $output_data['url'] . "\n");
		
			$output_data['container'] = bind_data($output_data, UI_TEMPLATE.'Container'.SEPARATOR.'Article.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, UI_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_ARTICLE.$output_data['url'].SEPARATOR);
		}
	}
}
