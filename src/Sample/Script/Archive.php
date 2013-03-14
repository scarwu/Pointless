<?php

class Archive {

	/**
	 * 
	 */
	private $list;
	
	/**
	 * 
	 */
	public function __construct() {
		$this->list = array();
		$source = Resource::get('article');

		foreach($source as $index => $value) {
			if(!isset($this->list[$value['year']]))
				$this->list[$value['year']] = array();

			$this->list[$value['year']][] = $value;
		}

		// Sort
		krsort($this->list);
		
		foreach($this->list as $year => $article)
			$this->list[$year] = articleSort($article);
	}

	/**
	 * 
	 */
	public function getList() {
		return $this->list;
	}

	/**
	 * 
	 */
	public function gen($slider) {
		$max = 0;
		$count = 0;
		$total = count($this->list);
		
		foreach((array)$this->list as $index => $article_list) {
			NanoIO::writeln(sprintf("Building archive/%s", $index));
			$max = $index > $max ? $index : $max;
			
			$output_data['bar'] = array(
				'index' => $count + 1,
				'total' => $total
			);
			if(isset($this->list[$index - 1]))
				$output_data['bar']['next'] = array(
					'title' => $index - 1,
					'url' => $index - 1
				);
			if(isset($this->list[$index + 1]))
				$output_data['bar']['prev'] = array(
					'title' => $index + 1,
					'url' => $index + 1
				);
				
			$count++;
			
			$output_data['title'] = 'Archive: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = bindData($output_data, THEME_CONTAINER . 'Archive.php');
			$output_data['slider'] = $slider;

			// Write HTML to Disk
			$result = bindData($output_data, THEME_FOLDER . 'index.php');
			writeTo($result, PUBLIC_FOLDER . 'archive/' . $index);

			// Sitemap
			Resource::set('sitemap', 'archive/' . $index);
		}
		
		copy(PUBLIC_FOLDER . 'archive/' . $max . '/index.html', PUBLIC_FOLDER . 'archive/index.html');
		Resource::set('sitemap', 'archive');
	}
}