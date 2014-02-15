<?php
/**
 * Extension Loader
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

class ExtensionLoader {

	/**
	 * @var array
	 */
	private $extension;

	public function __construct() {
		$this->extension = array();
	}

	/**
	 * Run Loader
	 */
	public function run() {

		// Load Custom Extension
		if(file_exists(EXTENSION)) {
			$handle = opendir(EXTENSION);
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require EXTENSION . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->extension[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Extension
		$handle = opendir(ROOT . '/Sample/Extension/');
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->extension[$class_name])) {
					require ROOT . '/Sample/Extension/' . $filename;
					$this->extension[$class_name] = new $class_name;
				}
			}
		closedir($handle);

		foreach((array)$this->extension as $class)
			$class->run();
	}
}