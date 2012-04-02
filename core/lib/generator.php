<?php

class generator {
	public function __construct() {
		// Inclide Markdown Library
		if(MARKDOWN_EXTRA)
			require_once CORE_PLUGINS . 'markdown-extra' . DIRECTORY_SEPARATOR . 'markdown.php';
		else
			require_once CORE_PLUGINS . 'markdown' . DIRECTORY_SEPARATOR . 'markdown.php';
	}
	
	public function run() {
		// Load articles directory
		$this->article_list = array();
		$this->tag_list = array();
		$this->category_list = array();
		
		$handle = opendir(ARTICLES_FOLDER);
		while($dir = readdir($handle))
			if('.' != $dir && '..' != $dir) {
				require_once ARTICLES_FOLDER . $dir . DIRECTORY_SEPARATOR . 'info.php';
				$info['number'] = $dir;
				
				// Article List
				array_push($this->article_list, $info);
				
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
				unset($info);
			}
		closedir($handle);
		
		sort($this->article_list);
		foreach((array)$this->category_list as $key => $value)
			sort($this->category_list[$key]);
		foreach((array)$this->tag_list as $key => $value)
			sort($this->tag_list[$key]);
		
		$this->slider = $this->genSlider();
		
		$this->genArticle();
		$this->genCategory();
		$this->genTag();
		$this->genPage();
		copy(STATIC_PAGE . '1' . DIRECTORY_SEPARATOR . 'index.html', STATIC_FOLDER . 'index.html');
	}

	/**
	 * Binding Page
	 */
	private function bindPage($output_data) {
		// Data Binding and Get Output Buffer
		ob_start();
		include UI_TEMPLATE . 'index.php';
		$page_content = ob_get_contents();
		ob_end_clean();
		
		// Write OB to files
		$handle = fopen($this->article_path . 'index.html', 'w+');
		fwrite($handle, $page_content);
		fclose($handle);
	}

	/**
	 * Gen Slider
	 */
	private function genSlider() {
		$result = '';
		$handle = opendir(UI_TEMPLATE . 'slider' . DIRECTORY_SEPARATOR);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file) {
				ob_start();
				include UI_TEMPLATE . 'slider' . DIRECTORY_SEPARATOR . $file;
				$result .= ob_get_contents();
				ob_end_clean();
			}
		closedir($handle);
		return $result;
	}
	
	/**
	 * Gen Article
	 */
	private function genArticle() {
		// Building Article
		foreach((array)$this->article_list as $index => $output_data) {
			echo sprintf("Building article/%d\n", $index+1);
			
			$this->article_path = STATIC_ARTICLE . ($index+1) . DIRECTORY_SEPARATOR;
			mkdir($this->article_path);

			$article_info = $output_data;
			
			$md = file_get_contents(ARTICLES_FOLDER . $output_data['number'] . DIRECTORY_SEPARATOR . 'article.md');
			$article_info['content'] = Markdown($md);
			
			ob_start();
			include UI_TEMPLATE . 'container' . DIRECTORY_SEPARATOR . 'article.php';
			$output_data['container'] = ob_get_contents();
			ob_end_clean(); 
			
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data);
		}
	}
	
	/**
	 * Gen Category
	 */
	private function genCategory() {
		foreach((array)$this->category_list as $index => $article_list) {
			echo sprintf("Building category/%s\n", $index);
			
			$this->article_path = STATIC_CATEGORY . $index . DIRECTORY_SEPARATOR;
			mkdir($this->article_path);
			
			$category_info = array(
				'title' => 'Category: ' . $index
			);
			
			$category_info['content'] = '<ul>';
			foreach((array)$article_list as $article_info) {
				$category_info['content'] .= '<li>' . $article_info['title'] . '</li>';
			}
			$category_info['content'] .= '</ul>';
			
			ob_start();
			include UI_TEMPLATE . 'container' . DIRECTORY_SEPARATOR . 'category.php';
			$output_data['container'] = ob_get_contents();
			ob_end_clean(); 
			
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data);
		}
	}
	
	/**
	 * Gen Tag
	 */
	private function genTag() {
		foreach((array)$this->tag_list as $index => $article_list) {
			echo sprintf("Building tag/%s\n", $index);
			
			$this->article_path = STATIC_TAG . $index . DIRECTORY_SEPARATOR;
			mkdir($this->article_path);
			
			$tag_info = array(
				'title' => 'Tag: ' . $index
			);
			
			$tag_info['content'] = '<ul>';
			foreach((array)$article_list as $article_info) {
				$tag_info['content'] .= '<li>' . $article_info['title'] . '</li>';
			}
			$tag_info['content'] .= '</ul>';
			
			ob_start();
			include UI_TEMPLATE . 'container' . DIRECTORY_SEPARATOR . 'tag.php';
			$output_data['container'] = ob_get_contents();
			ob_end_clean(); 
			
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data);
		}
	}
	
	/**
	 * Gen Page
	 */
	private function genPage() {
		$page_number = ceil(count($this->article_list) / ARTICLE_QUANTITY);
		
		for($index = 1;$index <= $page_number;$index++) {
			echo sprintf("Building page/%s\n", $index);
			
			$this->article_path = STATIC_PAGE . $index . DIRECTORY_SEPARATOR;
			mkdir($this->article_path);
			
			$page_info = array(
				'title' => 'Page: ' . $index,
				'bar' => 'Bar'
			);
			
			$page_info['content'] = '<ul>';
			foreach((array)$this->article_list as $article_info) {
				$page_info['content'] .= '<li>' . $article_info['title'] . '</li>';
			}
			$page_info['content'] .= '</ul>';
			
			ob_start();
			include UI_TEMPLATE . 'container' . DIRECTORY_SEPARATOR . 'page.php';
			$output_data['container'] = ob_get_contents();
			ob_end_clean(); 
			
			$output_data['slider'] = $this->slider;
			
			// Data Binding
			$this->bindPage($output_data);
		}
	}
}
