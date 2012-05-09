<?php

class Page {
	public $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function Add($article) {
		// Article List
		$this->_list[] = $article;
	}
	
	public function GetList() {
		$this->_list = article_sort($this->_list);
		return $this->_list;
	}
	
	public function Gen($slider) {
		$this->_list = article_sort($this->_list);
		$page_number = ceil(count($this->_list) / ARTICLE_QUANTITY);
				
		for($index = 0;$index < $page_number;$index++) {
			NanoIO::Writeln(sprintf("Building page/%s", ($index+1)));
			
			$output_data['bar'] = ($page_number <= 1 ? '' : 'bar');
			$output_data['article_list'] = array_slice($this->_list, ARTICLE_QUANTITY * $index, ARTICLE_QUANTITY);
			$output_data['container'] = bind_data($output_data, UI_TEMPLATE.'Container'.SEPARATOR.'Page.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, UI_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_PAGE.($index+1).SEPARATOR);
		}
		
		if(file_exists(BLOG_PUBLIC_PAGE.'1'.SEPARATOR.'index.html'))
			copy(BLOG_PUBLIC_PAGE.'1'.SEPARATOR.'index.html', BLOG_PUBLIC.'index.html');
	}
}
