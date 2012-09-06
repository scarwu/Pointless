<?php

class Page {
	private $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function add($article) {
		// Article List
		$this->_list[] = $article;
	}
	
	public function getList() {
		return $this->_list;
	}
	
	public function sortList() {
		$this->_list = article_sort($this->_list);
	}
	
	public function gen($slider) {
		$total = ceil(count($this->_list) / ARTICLE_QUANTITY);
				
		for($index = 0;$index < $total;$index++) {
			NanoIO::Writeln(sprintf("Building page/%s", ($index+1)));
			
			$output_data['bar'] = array(
				'index' => $index+1,
				'total' => $total
			);
			$output_data['article_list'] = array_slice($this->_list, ARTICLE_QUANTITY * $index, ARTICLE_QUANTITY);
			$output_data['container'] = bind_data($output_data, THEME_TEMPLATE.'Container'.SEPARATOR.'Page.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, THEME_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_PAGE.($index+1));
		}
		
		if(file_exists(BLOG_PUBLIC_PAGE.'1'.SEPARATOR.'index.html'))
			copy(BLOG_PUBLIC_PAGE.'1'.SEPARATOR.'index.html', BLOG_PUBLIC.'index.html');
	}
}
