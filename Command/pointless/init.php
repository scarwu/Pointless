<?php

class pointless_init extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require_once CORE_LIBRARY . 'GeneralFunction.php';

		if(!file_exists(BLOG)) {
			NanoIO::writeln("Create directory and files ... ", 'yellow');
			
			mkdir(BLOG, 0755, TRUE);
			mkdir(BLOG_RESOURCE, 0755, TRUE);
			mkdir(BLOG_PUBLIC, 0755, TRUE);
			
			recursiveCopy(ROOT . 'Sample', BLOG);
			recursiveCopy(THEME, BLOG . 'Theme');
			
			$gen = new pointless_gen_all();
			$gen->run();
		}
		
		NanoIO::writeln("Initialize ... OK", 'yellow');
	}
}
