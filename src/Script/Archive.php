<?php

class Archive {
	private $_list;
	
	public function __construct() {
		$this->_list = array();
		$source = Resource::get('source');

		foreach($source['article'] as $index => $value) {
			if(!isset($this->_list[$value['year']]))
				$this->_list[$value['year']] = array();

			$this->_list[$value['year']][] = $value;
		}

		// Sort
		krsort($this->_list);
		
		foreach($this->_list as $year => $article)
			$this->_list[$year] = articleSort($article);
	}

	public function getList() {
		return $this->_list;
	}

	public function gen($slider) {
		$max = 0;
		$count = 0;
		$total = count($this->_list);
		
		foreach((array)$this->_list as $index => $article_list) {
			NanoIO::writeln(sprintf("Building archive/%s", $index));
			$max = $index > $max ? $index : $max;
			
			$output_data['bar'] = array();
			$output_data['bar']['index'] = $count+1;
			$output_data['bar']['total'] = $total;
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
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Archive.php');
			$output_data['slider'] = $slider;

			$result = bindData($output_data, THEME . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'archive/' . $index);
		}
		
		if(file_exists(PUBLIC_FOLDER . 'archive/' . $max . '/index.html'))
			copy(PUBLIC_FOLDER . 'archive/' . $max . '/index.html', PUBLIC_FOLDER . 'archive/index.html');
	}
}