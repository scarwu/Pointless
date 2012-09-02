<?php

class Tag {
	public $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function Add($article) {
		foreach($article['tag'] as $tag) {
			if(!isset($this->_list[$tag]))
				$this->_list[$tag] = array();
			$this->_list[$tag][] = $article;
		}
	}
	
	public function GetList() {
		$this->_list = count_sort($this->_list);
		return $this->_list;
	}
	
	public function Gen($slider) {
		$this->_list = count_sort($this->_list);
		$max = array(0, NULL);
		$count = 0;
		$key = array_keys($this->_list);
		
		foreach((array)$this->_list as $index => $article_list) {
			NanoIO::Writeln(sprintf("Building tag/%s", $index));
			$max = count($article_list) > $max[0] ? array(count($article_list), $index) : $max;
			
			$output_data['bar'] = array();
			$output_data['bar']['index'] = $count+1;
			$output_data['bar']['total'] = count($this->_list);
			if(isset($key[$count-1]))
				$output_data['bar']['prev'] = array(
					'title' => $key[$count-1],
					'url' => $key[$count-1]
				);
			if(isset($key[$count+1]))
				$output_data['bar']['next'] = array(
					'title' => $key[$count+1],
					'url' => $key[$count+1]
				);
			
			$count++;
			
			$output_data['title'] = 'Tag: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bind_data($output_data, THEME_TEMPLATE.'Container'.SEPARATOR.'Tag.php');
			$output_data['slider'] = $slider;
			
			$result = bind_data($output_data, THEME_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_TAG.$index);
		}
		
		if(file_exists(BLOG_PUBLIC_TAG.$max[1].SEPARATOR.'index.html'))
			copy(BLOG_PUBLIC_TAG.$max[1].SEPARATOR.'index.html', BLOG_PUBLIC_TAG.'index.html');
	}
}
