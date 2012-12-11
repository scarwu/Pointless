<?php

class pointless_gen_clean extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run($path = NULL) {
		NanoIO::writeln("Clean Public Files ...", 'yellow');
		if(file_exists(NULL == $path ? BLOG_PUBLIC : $path))
			$this->recursiveRemove(NULL == $path ? BLOG_PUBLIC : $path);
	}
	
	/**
	 * Recursice Remove Directory
	 */
	private function recursiveRemove($path = NULL) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					$this->recursiveRemove($path . SEPARATOR . $file);
			closedir($handle);
			
			if($path != BLOG_PUBLIC)
				return rmdir($path);
		}
		else
			return unlink($path);
	}
}
