<?php
/**
 * HTML Generator
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

class HTMLGenerator {

	/**
	 * @var array
	 */
	private $script;
	
	public function __construct() {
		$this->script = array();
	}
	
	/**
	 * Run HTML Generator
	 */
	public function run() {

		// Load Script
		$this->loadScript();
		
		// Generate Block
		$this->genBlock();

		foreach((array)$this->script as $class)
			$class->gen();
	}

	/**
	 * Load Theme Script
	 */
	private function loadScript() {
		$handle = opendir(THEME_SCRIPT);
		while($filename = readdir($handle)) {
			if('.' == $filename || '..' == $filename)
				continue;

			require THEME_SCRIPT . $filename;

			$class_name = preg_replace('/.php$/', '', $filename);
			$this->script[$class_name] = new $class_name;
		}
		closedir($handle);
	}

	/**
	 * Generate Block
	 */
	private function genBlock() {
		$filter = array('.', '..', 'Container', 'index.php');
		$block = array();

		$block_handle = opendir(THEME_TEMPLATE);
		while($block_name = readdir($block_handle)) {
			if(in_array($block_name, $filter))
				continue;

			$file_list = array();

			$handle = opendir(THEME_TEMPLATE . $block_name);
			while($file = readdir($handle)) {
				if('.' == $file || '..' == $file)
					continue;

				$file_list[] = $file;
			}
			closedir($handle);

			sort($file_list);

			$result = '';
			foreach((array)$file_list as $file) {
				$script_name = preg_replace(array('/^\d+_/', '/.php$/'), '', $file);
				$list = isset($this->script[$script_name]) ? $this->script[$script_name]->getList() : null;
				$result .= bindData($list, THEME_TEMPLATE . $block_name . '/' . $file);
			}

			$block[strtolower($block_name)] = $result;
		}
		closedir($block_handle);

		Resource::set('block', $block);
	}
}
