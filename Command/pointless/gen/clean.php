<?php

class pointless_gen_clean extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run($path = NULL) {
		Text::Write("Clean Public Files ...\n", 'yellow');
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
