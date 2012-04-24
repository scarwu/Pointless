<?php

class Archive {
	public $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function Add($article) {
		if(!isset($this->_list[$article['year']]))
			$this->_list[$article['year']] = array();
		$this->_list[$article['year']][] = $article;
	}
	
	public function GetList() {
		krsort($this->_list);
		return $this->_list;
	}
	
	public function Gen($slider) {
		krsort($this->_list);
		
		foreach((array)$this->_list as $index => $article_list) {
			Text::Write(sprintf("Building archive/%s", $index) . "\n");
		
			$output_data['title'] = 'Archive: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bind_data($output_data, UI_TEMPLATE.'Container'.SEPARATOR.'Archive.php');
			$output_data['slider'] = $slider;

			$result = bind_data($output_data, UI_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_ARCHIVE.$index.SEPARATOR);
		}
	}
}