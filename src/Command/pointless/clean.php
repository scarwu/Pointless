<?php

class pointless_clean extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run($path = NULL) {
		NanoIO::writeln("Clean Public Files ...", 'yellow');
		if(file_exists(NULL == $path ? PUBLIC_FOLDER : $path))
			$this->recursiveRemove(NULL == $path ? PUBLIC_FOLDER : $path);
	}
	
	/**
	 * Recursice Remove Directory
	 */
	private function recursiveRemove($path = NULL) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					$this->recursiveRemove($path . '/' . $file);
			closedir($handle);
			
			if($path != PUBLIC_FOLDER)
				return rmdir($path);
		}
		else
			return unlink($path);
	}
}
