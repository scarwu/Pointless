<?php
/**
 * HTML Generator
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

class HTMLGenerator {

	/**
	 * @var array
	 */
	private $script;

	/**
	 * @var string
	 */
	private $slider;
	
	public function __construct() {
		$this->script = array();
	}
	
	/**
	 * Run HTML Generator
	 */
	public function run() {

		// Load Theme Custom Script
		if(file_exists(SCRIPT_FOLDER)) {
			$handle = opendir(SCRIPT_FOLDER);
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require SCRIPT_FOLDER . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->script[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Script
		$handle = opendir(ROOT . 'Sample/Script/');
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->script[$class_name])) {
					require ROOT . 'Sample/Script/' . $filename;
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
