<?php

class pointless_gen_clean extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run($path = NULL) {
		NanoIO::Writeln("Clean Public Files ...", 'yellow');
		if(file_exists(NULL == $path ? BLOG_PUBLIC : $path))
			$this->rRemoveDir(NULL == $path ? BLOG_PUBLIC : $path);
	}
	
	/**
	 * Recursice Remove Directory
	 */
	private function rRemoveDir($path = NULL) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					$this->rRemoveDir($path . SEPARATOR . $file);
			closedir($handle);
			
			if($path != BLOG_PUBLIC)
				return rmdir($path);
		}
		else
			return unlink($path);
	}
}
