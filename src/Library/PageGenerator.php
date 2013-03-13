<?php

class PageGenerator {
	private $script;
	private $slider;
	
	public function __construct() {
		$this->script = array();
	}
	
	public function run() {

		// Load Theme Custom Script
		if(file_exists(THEME . 'Script')) {
			$handle = opendir(THEME . 'Script');
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require THEME . 'Script' . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->script[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Script
		$handle = opendir(SCRIPT);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->script[$class_name])) {
					require SCRIPT . $filename;
					$this->script[$class_name] = new $class_name;
				}
			}
		closedir($handle);
		
		$this->genSlider();
		$this->genContainer();
	}

	/**
	 * Generate Container
	 */
	private function genContainer() {
		foreach((array)$this->script as $class)
			$class->gen($this->slider);
	}

	/**
	 * Generate Slider
	 */
	private function genSlider() {
		$list = array();
		$handle = opendir(THEME_SLIDER);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$list[] = $file;
		closedir($handle);
		
		sort($list);

		$result = '';
		foreach((array)$list as $filename)
			$result .= bindData(
				$this->script[preg_replace(array('/^\d+_/', '/.php$/'), '', $filename)]->getList(),
				THEME_SLIDER . $filename
			);
		
		$this->slider = $result;
	}
}
