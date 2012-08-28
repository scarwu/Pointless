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
		
		foreach($this->_list as $year => $article)
			$this->_list[$year] = article_sort($article);
		
		return $this->_list;
	}
	
	public function Gen($slider) {
		krsort($this->_list);
		
		foreach($this->_list as $year => $article)
			$this->_list[$year] = article_sort($article);
		
		$max = 0;
		$count = 0;
		
		foreach((array)$this->_list as $index => $article_list) {
			NanoIO::Writeln(sprintf("Building archive/%s", $index));
			$max = $index > $max ? $index : $max;
			
			$output_data['bar'] = array();
			$output_data['bar']['index'] = $count+1;
			$output_data['bar']['total'] = count($this->_list);
			if(isset($this->_list[$index-1]))
				$output_data['bar']['next'] = array(
					'title' => $index-1,
					'url' => $index-1
				);
			if(isset($this->_list[$index+1]))
				$output_data['bar']['prev'] = array(
					'title' => $index+1,
					'url' => $index+1
				);
				
			$count++;
			
			$output_data['title'] = 'Archive: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bind_data($output_data, THEME_TEMPLATE.'Container'.SEPARATOR.'Archive.php');
			$output_data['slider'] = $slider;

			$result = bind_data($output_data, THEME_TEMPLATE.'index.php');
			write_to($result, BLOG_PUBLIC_ARCHIVE.$index);
		}
		
		if(file_exists(BLOG_PUBLIC_ARCHIVE.$max.SEPARATOR.'index.html'))
			copy(BLOG_PUBLIC_ARCHIVE.$max.SEPARATOR.'index.html', BLOG_PUBLIC_ARCHIVE.'index.html');
	}
}