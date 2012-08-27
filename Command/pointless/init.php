<?php

class pointless_init extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'GeneralFunction.php';

		if(!file_exists(BLOG)) {
			NanoIO::Writeln("Create directory and files ... ", 'yellow');
			
			mkdir(BLOG, 0755, TRUE);
			mkdir(BLOG_RESOURCE, 0755, TRUE);
			mkdir(BLOG_PUBLIC, 0755, TRUE);
			
			recursive_copy(ROOT . 'Sample', BLOG);
			recursive_copy(THEME, BLOG . 'Theme');
			
			$gen = new pointless_gen_all();
			$gen->Run();
		}
		
		NanoIO::Writeln("Initialize ... OK", 'yellow');
	}
}
