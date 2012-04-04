<?php

class generator {
	public function __construct() {
		// Inclide Markdown Library
		if(MARKDOWN_EXTRA)
			require_once CORE_PLUGINS . 'markdown-extra' . SEPARATOR . 'markdown.php';
		else
			require_once CORE_PLUGINS . 'markdown' . SEPARATOR . 'markdown.php';
	}
	
	public function run() {
		// Load articles directory
		$this->article_list = array();
		$this->tag_list = array();
		$this->category_list = array();
		$this->archive_list = array();
		
		$handle = opendir(ARTICLES);
		while($dir = readdir($handle))
			if('.' != $dir && '..' != $dir) {
				$info = json_decode(file_get_contents(ARTICLES . $dir . SEPARATOR . 'info.json'), TRUE);
				$info['number'] = $dir;
				
				// Article List
				$this->article_list[$dir] = $info;
				//array_push($this->article_list, $info);
				
				// Category List
				if(!isset($this->category_list[$info['category']]))
					$this->category_list[$info['category']] = array();
				array_push($this->category_list[$info['category']], $info);
				// Tag List
				foreach(explode('|', $info['tag']) as $tag) {
					if(!isset($this->tag_list[$tag]))
						$this->tag_list[$tag] = array();
					array_push($this->tag_list[$tag], $info);
				}
				// Tag Archive
				if(!isset($this->archive_list[substr($info['post_date'], 0, 4)]))
					$this->archive_list[substr($info['post_date'], 0, 4)] = array();
				array_push($this->archive_list[substr($info['post_date'], 0, 4)], $info);
				unset($info);
			}
		closedir($handle);
		
		krsort($this->article_list);
		foreach((array)$this->category_list as $key => $value)
			krsort($this->category_list[$key]);
		foreach((array)$this->tag_list as $key => $value)
			krsort($this->tag_list[$key]);
		foreach((array)$this->archive_list as $key => $value)
			krsort($this->archive_list[$key]);
		
		$this->slider = $this->genSlider();
		
		$this->genArticle();
		$this->genCategory();
		$this->genTag();
		$this->genPage();
		$this->genArchive();
		copy(HTDOCS_PAGE . '1' . SEPARATOR . 'index.html', HTDOCS . 'index.html');
	}

	/**
	 * Binding Page
	 */
	private function bindPage($blog, $path) {
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
		
		return $result;
	}
	
	/**
	 * Gen Article
	 */
	private function genArticle() {
		// Building Article
		foreach((array)$this->article_list as $index => $output_data) {
			echo sprintf("Building article/%d", $output_data['number']);

			$md = file_get_contents(ARTICLES . $output_data['number'] . SEPARATOR . 'article.md');
			$output_data['content'] = Markdown($md);
			$output_data['container'] = $this->bindContainer($output_data, 'article');
			$output_data['slider'] = $this->slider;
			$output_data['link'] = 'article/' . $output_data['number'];
			
			// Data Binding
			$this->bindPage($output_data, HTDOCS_ARTICLE . $output_data['number'] . SEPARATOR);
			
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
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info) {
				$output_data['content'] .= '<li><a href="' . BLOG_PATH . 'article/' . $article_info['number'] . '">' . $article_info['title'] . '</a></li>';
			}
			$output_data['content'] .= '</ul>';
			$output_data['container'] = $this->bindContainer($output_data, 'category');
			$output_data['slider'] = $this->slider;
			$output_data['link'] = 'category/' . $index;
			
			// Data Binding
			$this->bindPage($output_data, HTDOCS_CATEGORY . $index . SEPARATOR);
			
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
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info) {
				$output_data['content'] .= '<li><a href="' . BLOG_PATH . 'article/' . $article_info['number'] . '">' . $article_info['title'] . '</a></li>';
			}
			$output_data['content'] .= '</ul>';
			$output_data['container'] = $this->bindContainer($output_data, 'tag');
			$output_data['slider'] = $this->slider;
			$output_data['link'] = 'tag/' . $index;
			
			// Data Binding
			$this->bindPage($output_data, HTDOCS_TAG . $index . SEPARATOR);
			
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
			$output_data['content'] = '<ul>';
			foreach((array)$article_list as $article_index => $article_info) {
				$output_data['content'] .= '<li><a href="' . BLOG_PATH . 'article/' . $article_info['number'] . '">' . $article_info['title'] . '</a></li>';
			}
			$output_data['content'] .= '</ul>';
			$output_data['container'] = $this->bindContainer($output_data, 'archive');
			$output_data['slider'] = $this->slider;
			$output_data['link'] = 'tag/' . $index;
			
			// Data Binding
			$this->bindPage($output_data, HTDOCS_ARCHIVE . $index . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
	
	/**
	 * Gen Page
	 */
	private function genPage() {
		$page_number = ceil(count($this->article_list) / ARTICLE_QUANTITY);
		
		for($index = 1;$index <= $page_number;$index++) {
			echo sprintf("Building page/%s", $index);
			
			$output_data['bar'] = 'Bar';
			$output_data['article_list'] = $this->article_list;
			$output_data['container'] = $this->bindContainer($output_data, 'page');
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data, HTDOCS_PAGE . $index . SEPARATOR);
			
			echo "...OK!\n";
		}
	}
}
