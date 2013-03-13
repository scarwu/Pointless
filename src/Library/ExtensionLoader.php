<?php

class ExtensionLoader {

	private $extension;

	public function __construct() {
		$this->extension = array();
	}

	public function run() {

		// Load Custom Extension
		if(file_exists(ROOT . 'Sample/Extension/')) {
			$handle = opendir(ROOT . 'Sample/Extension/');
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require ROOT . 'Sample/Extension/' . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->extension[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Extension
		$handle = opendir(EXTENSION_FOLDER);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->extension[$class_name])) {
					require EXTENSION_FOLDER . $filename;
					$this->extension[$class_name] = new $class_name;
				}
			}
		closedir($handle);

		foreach((array)$this->extension as $class)
			$class->run();
		
	}

}