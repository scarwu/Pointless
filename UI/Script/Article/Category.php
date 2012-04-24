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
		
		foreach((array)$this->_list as $index => $article_list) {
			Text::Write(sprintf("Building category/%s", $index) . "\n");
		
			$output_data['title'] ='Category: ' . $index;
			// FIXME
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info)
				$output_data['content'] .= '<li>' . link_to(BLOG_PATH.'article/'.$article_info['url'], $article_info['title']) . '</li>';
			
			$output_data['content'] .= '</ul>';
			$output_data['container'] = bind_data($output_data, UI_TEMPLATE.'Container'.SEPARATOR.'Category.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, UI_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_CATEGORY.$index.SEPARATOR);
		}
	}
}
