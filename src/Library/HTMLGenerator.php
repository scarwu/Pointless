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
	private $side;
	
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
		
		$this->genSide();
		$this->genContainer();
	}

	/**
	 * Generate Container
	 */
	private function genContainer() {
		foreach((array)$this->script as $class)
			$class->gen($this->side);
	}

	/**
	 * Generate Side
	 */
	private function genSide() {
		$side = array();
		$handle = opendir(THEME_SIDE);
		while($file = readdir($handle))
			if('.' != $file && '..' != $file)
				$side[] = $file;
		closedir($handle);
		
		sort($side);

		$result = '';
		foreach((array)$side as $filename) {
			$script_name = preg_replace(array('/^\d+_/', '/.php$/'), '', $filename);
			$list = isset($this->script[$script_name]) ? $this->script[$script_name]->getList() : array();
			$result .= bindData($list, THEME_SIDE . $filename);
		}
		
		$this->side = $result;
	}
}
