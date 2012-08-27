<?php

class Generator {
	private $_template;
	private $_slider;
	
	public function __construct() {
		$this->_template = array();
		$this->_template['Article'] = array();
		$this->_template['BlogPage'] = array();
		// $this->_list = array();
	}
	
	public function Run() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		/**
		 * Load Blog Page
		 */
		$handle = opendir(THEME_SCRIPT . 'BlogPage');
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				require_once THEME_SCRIPT . 'BlogPage' . SEPARATOR . $filename;
				$class_name = preg_replace('/.php$/', '', $filename);
				$this->_template['BlogPage'][$class_name] = new $class_name;
			}
		closedir($handle);
		
		$handle = opendir(BLOG_MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match($regex_rule, file_get_contents(BLOG_MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				$blog_page = array();
				$blog_page['title'] = $temp['title'];
				$blog_page['url'] = $temp['url'];
				$blog_page['content'] = Markdown($match[2]);
				$blog_page['message'] = isset($temp['message']) ? $temp['message'] : TRUE;
				
				foreach((array)$this->_template['BlogPage'] as $class)
					$class->Add($blog_page);
			}
		closedir($handle);

		/**
		 * Load Article
		 */
		$handle = opendir(THEME_SCRIPT . 'Article');
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				require_once THEME_SCRIPT . 'Article' . SEPARATOR . $filename;
				$class_name = preg_replace('/.php$/', '', $filename);
				$this->_template['Article'][$class_name] = new $class_name;
			}
		closedir($handle);

		$handle = opendir(BLOG_MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match($regex_rule, file_get_contents(BLOG_MARKDOWN_ARTICLE . $filename), $match);

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

				foreach((array)$this->_template['Article'] as $class)
					$class->Add($article);
			}
		closedir($handle);
		
		$this->genSlider();
		$this->genContainer();
	}

	/**
	 * Gen Container
	 */
	private function genContainer() {
		foreach((array)$this->_template as $list)
			foreach((array)$list as $class)
				$class->Gen($this->_slider);
	}

	/**
	 * Gen Slider
	 */
	private function genSlider() {
		$result = '';
		$list = array();
		$handle = opendir(THEME_TEMPLATE . 'Slider' . SEPARATOR);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$list[] = $file;
		closedir($handle);
		
		sort($list);

		foreach((array)$list as $filename)
			$result .= bind_data(
				$this->_template['Article'][preg_replace(array('/^\d+_/', '/.php$/'), '', $filename)]->GetList(),
				THEME_TEMPLATE . 'Slider' . SEPARATOR . $filename
			);
		
		$this->_slider = $result;
	}
}
