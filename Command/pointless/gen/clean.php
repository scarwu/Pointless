<?php

class pointless_gen_clean extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run($path = NULL) {
		echo "Clean Static Files";
		
		if(file_exists(NULL == $path ? HTDOCS : $path))
			$this->rRemoveDir(NULL == $path ? HTDOCS : $path);
		
		echo "...OK!\n";
	}
	
	/**
	 * Recursice Remove Directory
	 */
	private function rRemoveDir($path = NULL) {
		if(is_dir($path)) {
			$handle = @opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					$this->rRemoveDir($path . DIRECTORY_SEPARATOR . $file);
			closedir($handle);
			
			if($path != HTDOCS)
				return rmdir($path);
		}
		else
			return unlink($path);
	}
}
