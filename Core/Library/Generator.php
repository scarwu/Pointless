<?php

class Generator {
	public function __construct() {
		// Inclide Markdown Library
		require_once CORE_PLUGIN . 'Markdown' . SEPARATOR . 'markdown.php';
		require_once CORE_LIBRARY . 'CustomSort.php';
		require_once CORE_LIBRARY . 'GeneralFunction.php';
		
		$this->static_list = array();
		
		$this->article_list = array();
		$this->tag_list = array();
		$this->category_list = array();
		$this->archive_list = array();
	}
	
	public function Run() {
		$handle = opendir(BLOG_MARKDOWN_STATIC);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match('/-----((?:.|\n)*)-----((?:.|\n)*)/', file_get_contents(BLOG_MARKDOWN_STATIC . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				$static = array();
				$static['title'] = $temp['title'];
				$static['url'] = $temp['url'];
				$static['content'] = Markdown($match[2]);

				array_push($this->static_list, $static);
			}
		closedir($handle);
		
		
		$handle = opendir(BLOG_MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match('/-----((?:.|\n)*)-----((?:.|\n)*)/', file_get_contents(BLOG_MARKDOWN_ARTICLE . $filename), $match);

				$temp = json_decode($match[1], TRUE);
				
				$article = array();
				$article['title'] = $temp['title'];
				$article['content'] = Markdown($match[2]);

				$date = explode('-', $temp['date']);
				$article['year'] = $date[0];
				$article['month'] = $date[1];
				$article['day'] = $date[2];
				$article['date'] = $temp['date'];
				
				$time = explode(':', $temp['time']);
				$article['hour'] = $time[0];
				$article['minute'] = $time[1];
				$article['second'] = $time[2];
				$article['time'] = $temp['time'];
				
				$article['tag'] = explode('|', $temp['tag']);
				$article['category'] = $temp['category'];
				
				$article['$filename'] = preg_replace('/\.md$/', '', $filename);
				
				// 0: date, 1: title, 2: date + title, 3: dirname
				switch(ARTICLE_URL) {
					case 0:
						$article['url'] = str_replace('-', '/', $temp['date']);
						break;
					case 1:
						$article['url'] = $temp['url'];
						break;
					case 2:
						$article['url'] = str_replace('-', '/', $temp['date']) . '/' . $temp['url'];
						break;
					case 3:
						$article['url'] = $filename;
						break;
				}

				// Article List
				array_push($this->article_list, $article);

				// Category List
				if(!isset($this->category_list[$article['category']]))
					$this->category_list[$article['category']] = array();
				array_push($this->category_list[$article['category']], $article);
				
				// Tag List
				foreach($article['tag'] as $tag) {
					if(!isset($this->tag_list[$tag]))
						$this->tag_list[$tag] = array();
					array_push($this->tag_list[$tag], $article);
				}
				
				// Tag Archive
				if(!isset($this->archive_list[$article['year']]))
					$this->archive_list[$article['year']] = array();
				array_push($this->archive_list[$article['year']], $article);
			}
		closedir($handle);
		
		$this->article_list = article_sort($this->article_list);
		$this->category_list = count_sort($this->category_list);
		$this->tag_list = count_sort($this->tag_list);
		krsort($this->archive_list);
				
		$this->genSlider();
		
		$this->genStatic();
		$this->genArticle();
		$this->genPage();
		$this->genArchive();
		$this->genCategory();
		$this->genTag();
	}

	/**
	 * Binding Page
	 */
	private function bindPage($blog, $path) {
		if(!file_exists($path))
			mkdir($path, 0755, TRUE);
		
		// Data Binding and Get Output Buffer
		ob_start();
		include UI_TEMPLATE . 'index.php';
		$result = ob_get_contents();
		ob_end_clean();
		
		// Write OB to files
		$handle = fopen($path . 'index.html', 'w+');
		fwrite($handle, $result);
		fclose($handle);
	}
	
	/**
	 * Binding Container
	 */
	private function bindContainer($data, $target) {
		ob_start();
		include UI_TEMPLATE . 'container' . SEPARATOR . $target. '.php';
		$result = ob_get_contents();
		ob_end_clean();
		
		return $result;
	}

	/**
	 * Gen Slider
	 */
	private function genSlider() {
		$result = '';
		$list = array();
		$handle = opendir(UI_TEMPLATE . 'slider' . SEPARATOR);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				array_push($list, $file);
		closedir($handle);
		
		sort($list);
		
		foreach((array)$list as $filename) {
			ob_start();
			include UI_TEMPLATE . 'slider' . SEPARATOR . $filename;
			$result .= ob_get_contents();
			ob_end_clean();
		}
		
		$this->slider = $result;
	}
	
	/**
	 * Gen Static
	 */
	private function genStatic() {
		// Building Article
		foreach((array)$this->static_list as $index => $output_data) {
			echo 'Building ' . $output_data['url'];

			$output_data['container'] = $this->bindContainer($output_data, 'static');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC . $output_data['url'] . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Article
	 */
	private function genArticle() {
		// Building Article
		foreach((array)$this->article_list as $index => $output_data) {
			echo 'Building article/' . $output_data['url'];

			$output_data['container'] = $this->bindContainer($output_data, 'article');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC_ARTICLE . $output_data['url'] . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Category
	 */
	private function genCategory() {
		foreach((array)$this->category_list as $index => $article_list) {
			echo sprintf("Building category/%s", $index);

			$output_data['title'] ='Category: ' . $index;
			// FIXME
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info) {
				$output_data['content'] .= '<li><a href="' . BLOG_PATH . 'article/' . $article_info['url'] . '">' . $article_info['title'] . '</a></li>';
			}
			$output_data['content'] .= '</ul>';
			$output_data['container'] = $this->bindContainer($output_data, 'category');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC_CATEGORY . $index . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Tag
	 */
	private function genTag() {
		foreach((array)$this->tag_list as $index => $article_list) {
			echo sprintf("Building tag/%s", $index);

			$output_data['title'] = 'Tag: ' . $index;
			// FIXME
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info) {
				$output_data['content'] .= '<li><a href="' . BLOG_PATH . 'article/' . $article_info['url'] . '">' . $article_info['title'] . '</a></li>';
			}
			$output_data['content'] .= '</ul>';
			$output_data['container'] = $this->bindContainer($output_data, 'tag');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC_TAG . $index . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Archive
	 */
	private function genArchive() {
		foreach((array)$this->archive_list as $index => $article_list) {
			echo sprintf("Building archive/%s", $index);

			$output_data['title'] = 'Archive: ' . $index;
			$output_data['article_list'] = $article_list;
			$output_data['container'] = $this->bindContainer($output_data, 'archive');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC_ARCHIVE . $index . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Page
	 */
	private function genPage() {
		$page_number = ceil(count($this->article_list) / ARTICLE_QUANTITY);
		
		for($index = 0;$index < $page_number;$index++) {
			echo sprintf("Building page/%s", ($index+1));
			
			$output_data['bar'] = ($page_number <= 1 ? '' : 'bar');
			$output_data['article_list'] = array_slice($this->article_list, ARTICLE_QUANTITY * $index, ARTICLE_QUANTITY);
			$output_data['container'] = $this->bindContainer($output_data, 'page');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, BLOG_PUBLIC_PAGE . ($index+1) . SEPARATOR);
			
			echo "...OK!\n";
		}
		
		copy(BLOG_PUBLIC_PAGE . '1' . SEPARATOR . 'index.html', BLOG_PUBLIC . 'index.html');
	}
}
