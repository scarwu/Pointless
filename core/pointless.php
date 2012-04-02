<?php

class pointless {
	private $segments;
	
	public function __construct($segments) {
		$this->segments = $segments;
		$path = dirname(realpath($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR;
		
		define('STATIC_FOLDER', $path . 'static' . DIRECTORY_SEPARATOR);
		define('STATIC_ARTICLE', STATIC_FOLDER . 'article' . DIRECTORY_SEPARATOR);
		define('STATIC_CATEGORY', STATIC_FOLDER . 'category' . DIRECTORY_SEPARATOR);
		define('STATIC_TAG', STATIC_FOLDER . 'tag' . DIRECTORY_SEPARATOR);
		define('STATIC_PAGE', STATIC_FOLDER . 'tag' . DIRECTORY_SEPARATOR);
		
		define('ARTICLES_FOLDER', $path . 'articles' . DIRECTORY_SEPARATOR);
		
		define('UI_CSS', $path . 'ui' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR);
		define('UI_JS', $path . 'ui' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR);
		define('UI_TEMPLATE', $path . 'ui' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR);
		
		define('CORE_PLUGINS', $path . 'core' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);
	
		// Inclide Markdown Library
		if(MARKDOWN_EXTRA)
			require_once CORE_PLUGINS . 'markdown-extra' . DIRECTORY_SEPARATOR . 'markdown.php';
		else
			require_once CORE_PLUGINS . 'markdown' . DIRECTORY_SEPARATOR . 'markdown.php';
	}
	
	public function gen() {
		// Clean static pages
		$this->clean();
		
		// Make directory
		mkdir(STATIC_FOLDER);
		mkdir(STATIC_FOLDER . 'page');
		mkdir(STATIC_FOLDER . 'article');
		mkdir(STATIC_FOLDER . 'category');
		mkdir(STATIC_FOLDER . 'tag');
		
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
		//sort($article_list);
		
		$this->genArticle();
		$this->genCategory();
		$this->genTag();
		$this->genPage();
		copy(STATIC_PAGE . '1' . 'index.html', STATIC_FOLDER);
	}
	
	private function genArticle() {
		// Building Article
		foreach((array)$this->article_list as $index => $html_info) {
			echo sprintf("Building article/%d\n", $index+1);
			
			$article_path = STATIC_ARTICLE . ($index+1) . DIRECTORY_SEPARATOR;
			mkdir($article_path);
			
			$md = file_get_contents(ARTICLES_FOLDER . $html_info['number'] . DIRECTORY_SEPARATOR . 'article.md');
			$html_info['content'] = Markdown($md);
			
			// Data Binding and Get Output Buffer
			ob_start();
			include UI_TEMPLATE . 'index.php';
			$page_content = ob_get_contents();
			ob_end_clean();
			
			// Write OB to files
			$handle = fopen($article_path . 'index.html', 'w+');
			fwrite($handle, $page_content);
			fclose($handle);
		}
	}
	
	private function genCategory() {
		foreach((array)$this->category_list as $index => $article_list) {
			echo sprintf("Building category/%s\n", $index);
			
			$article_path = STATIC_CATEGORY . $index . DIRECTORY_SEPARATOR;
			mkdir($article_path);
			
			$html_info = array(
				'title' => 'Category: ' . $index
			);
			
			$html_info['content'] = '<ul>';
			foreach((array)$article_list as $article_info) {
				$html_info['content'] .= '<li>' . $article_info['title'] . '</li>';
			}
			$html_info['content'] .= '</ul>';
			
			ob_start();
			include UI_TEMPLATE . 'index.php';
			$page_content = ob_get_contents();
			ob_end_clean();
			
			// Write OB to files
			$handle = fopen($article_path . 'index.html', 'w+');
			fwrite($handle, $page_content);
			fclose($handle);
		}
	}
	
	private function genTag() {
		foreach((array)$this->tag_list as $index => $article_list) {
			echo sprintf("Building tag/%s\n", $index);
			
			$article_path = STATIC_TAG . $index . DIRECTORY_SEPARATOR;
			mkdir($article_path);
			
			$html_info = array(
				'title' => 'Tag: ' . $index
			);
			
			$html_info['content'] = '<ul>';
			foreach((array)$article_list as $article_info) {
				$html_info['content'] .= '<li>' . $article_info['title'] . '</li>';
			}
			$html_info['content'] .= '</ul>';
			
			ob_start();
			include UI_TEMPLATE . 'index.php';
			$page_content = ob_get_contents();
			ob_end_clean();
			
			// Write OB to files
			$handle = fopen($article_path . 'index.html', 'w+');
			fwrite($handle, $page_content);
			fclose($handle);
		}
	}
	
	private function genPage() {
		
	}
	
	public function clean() {
		echo "Clean Static Pages\n";
		if(file_exists(STATIC_FOLDER))
			$this->rRemoveDir(STATIC_FOLDER);
	}
	
	public function update() {
		
	}
	
	public function help() {
		
	}
	
	public function version() {
		echo "v0.0.1\n";
	}
	
	/**
	 * Recursice Remove Directory
	 */
	private function rRemoveDir($path = NULL) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..')
					$this->rRemoveDir($path . DIRECTORY_SEPARATOR . $file);
			closedir($handle);
			
			return rmdir($path);
		}
		else
			return unlink($path);
	}
}
