<?php

class pointless_gen_all extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require_once CORE_LIB . 'compress.php';
		require_once CORE_LIB . 'generator.php';
		
		// Clean static pages
		$clean = new pointless_clean();
		$clean->run();
		
		$this->makeDir();
		
		$compress = new compress();
		$compress->js();
		$compress->css();
		
		$generator = new generator();
		$generator->run();
	}
	
	// Make directory
	private function makeDir() {
		echo "Make Directory";
		
		mkdir(HTDOCS);
		mkdir(HTDOCS . 'page');
		mkdir(HTDOCS . 'article');
		mkdir(HTDOCS . 'category');
		mkdir(HTDOCS . 'tag');
		
		echo "...OK!\n";
	}
}
