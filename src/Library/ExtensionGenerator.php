<?php

class ExtensionGenerator {

	public function __construct() {}

	public function run() {
		// Load Theme Custom Script
		if(file_exists(EXTENSION_FOLDER)) {
			$handle = opendir(EXTENSION_FOLDER);
			while($filename = readdir($handle))
				if('.' != $filename && '..' != $filename) {
					require EXTENSION_FOLDER . $filename;

					$class_name = preg_replace('/.php$/', '', $filename);
					$extension = new $class_name;
					$extension->run();
				}
			closedir($handle);
		}

	}

}