<?php

class Category {
	public $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function Add($article) {
		if(!isset($this->_list[$article['category']]))
			$this->_list[$article['category']] = array();
		$this->_list[$article['category']][] = $article;
	}
	
	public function GetList() {
		$this->_list = count_sort($this->_list);
		return $this->_list;
	}
	
	public function Gen($slider) {
		$this->_list = count_sort($this->_list);
		$max = array(0, NULL);
		
		foreach((array)$this->_list as $index => $article_list) {
			NanoIO::Writeln(sprintf("Building category/%s", $index));
			$max = count($article_list) > $max[0] ? array(count($article_list), $index) : $max;
			
			$output_data['title'] ='Category: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bind_data($output_data, UI_TEMPLATE.'Container'.SEPARATOR.'Category.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, UI_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_CATEGORY.$index);
		}
		
		if(file_exists(BLOG_PUBLIC_CATEGORY.$max[1].SEPARATOR.'index.html'))
			copy(BLOG_PUBLIC_CATEGORY.$max[1].SEPARATOR.'index.html', BLOG_PUBLIC_CATEGORY.'index.html');
	}
}
