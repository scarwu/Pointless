<?php

class pointless {
	private $segments;
	
	public function __construct($segments) {
		$this->segments = $segments;
	}
	
	public function init() {
		// Clean static pages
		$this->clean();
		
		echo "Make Directory\n";
		// Make directory
		mkdir(STATIC_FOLDER);
		mkdir(STATIC_FOLDER . 'page');
		mkdir(STATIC_FOLDER . 'article');
		mkdir(STATIC_FOLDER . 'category');
		mkdir(STATIC_FOLDER . 'tag');
		
		$generator = new generator();
		$generator->run();
		
		$compress = new compress();
		$compress->run();
	}

	public function clean() {
		echo "Clean Static Pages\n";
		if(file_exists(STATIC_FOLDER))
			$this->rRemoveDir(STATIC_FOLDER);
	}
	
	public function update() {
		
	}
	
	public function help() {
		
	}
	
	public function version() {
		echo "v0.0.1\n";
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
