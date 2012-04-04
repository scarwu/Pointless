<?php

class pointless_gen_all extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require_once CORE_LIB . 'compress.php';
		require_once CORE_LIB . 'generator.php';
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->run();
		
		mkdir(HTDOCS);
		
		$compress = new compress();
		$compress->js();
		$compress->css();
		
		$generator = new generator();
		$generator->run();
	}
}
