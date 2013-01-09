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
		$this->_list = articleSort($this->_list);
	}
	
	public function gen($slider) {
		$total = ceil(count($this->_list) / ARTICLE_QUANTITY);
				
		for($index = 0;$index < $total;$index++) {
			NanoIO::writeln(sprintf("Building page/%s", ($index+1)));
			
			$output_data['bar'] = array(
				'index' => $index+1,
				'total' => $total
			);
			$output_data['article_list'] = array_slice($this->_list, ARTICLE_QUANTITY * $index, ARTICLE_QUANTITY);
			$output_data['container'] = bindData($output_data, THEME_TEMPLATE . 'Container/Page.php');
			$output_data['slider'] = $slider;
			
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, PUBLIC_PAGE . ($index+1));
		}
		
		if(file_exists(PUBLIC_PAGE . '1' . '/index.html'))
			copy(PUBLIC_PAGE . '1' . '/index.html', PUBLIC_FOLDER . 'index.html');
	}
}
