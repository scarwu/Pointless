<?php

class Tag {
	private $_list;
	
	public function __construct() {
		$this->_list = array();
	}
	
	public function add($article) {
		foreach($article['tag'] as $tag) {
			if(!isset($this->_list[$tag]))
				$this->_list[$tag] = array();
			$this->_list[$tag][] = $article;
		}
	}
	
	public function getList() {
		return $this->_list;
	}
	
	public function sortList() {
		$this->_list = countSort($this->_list);
	}
	
	public function gen($slider) {
		$max = array(0, NULL);
		$count = 0;
		$total = count($this->_list);
		$key = array_keys($this->_list);
		
		foreach((array)$this->_list as $index => $article_list) {
			NanoIO::writeln(sprintf("Building tag/%s", $index));
			$max = count($article_list) > $max[0] ? array(count($article_list), $index) : $max;
			
			$output_data['bar'] = array();
			$output_data['bar']['index'] = $count+1;
			$output_data['bar']['total'] = $total;
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
			$output_data['container'] = bindData($output_data, THEME_TEMPLATE . 'Container' . SEPARATOR . 'Tag.php');
			$output_data['slider'] = $slider;
			
			$result = bindData($output_data, THEME_TEMPLATE . 'index.php');
			writeTo($result, BLOG_PUBLIC_TAG . $index);
		}
		
		if(file_exists(BLOG_PUBLIC_TAG . $max[1] . SEPARATOR . 'index.html'))
			copy(BLOG_PUBLIC_TAG . $max[1] . SEPARATOR . 'index.html', BLOG_PUBLIC_TAG . 'index.html');
	}
}
