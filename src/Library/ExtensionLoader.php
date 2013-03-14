<?php

class ExtensionLoader {

	private $extension;

	public function __construct() {
		$this->extension = array();
	}

	public function run() {

		// Load Custom Extension
		if(file_exists(EXTENSION_FOLDER)) {
			$handle = opendir(EXTENSION_FOLDER);
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require EXTENSION_FOLDER . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$this->extension[$class_name] = new $class_name;
				}
			closedir($handle);
		}

		// Load Default Extension
		$handle = opendir(ROOT . 'Sample/Extension/');
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				$class_name = preg_replace('/.php$/', '', $filename);

				if(!isset($this->extension[$class_name])) {
					require ROOT . 'Sample/Extension/' . $filename;
					$this->extension[$class_name] = new $class_name;
				}
			}
		closedir($handle);

		foreach((array)$this->extension as $class)
			$class->run();
		
	}

}