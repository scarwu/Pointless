<?php

class pointless_clean extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run($path = NULL) {
		echo "Clean Static Pages";
		
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
				if($file != '.' && $file != '..')
					$this->rRemoveDir($path . DIRECTORY_SEPARATOR . $file);
			closedir($handle);
			
			return rmdir($path);
		}
		else
			return unlink($path);
	}
}
