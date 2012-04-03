<?php

class pointless_gen_all extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		// Clean static pages
		$clean = new pointless_clean();
		$clean->run();
		
		echo "Make Directory\n";
		// Make directory
		mkdir(HTDOCS);
		mkdir(HTDOCS . 'page');
		mkdir(HTDOCS . 'article');
		mkdir(HTDOCS . 'category');
		mkdir(HTDOCS . 'tag');
		
	}
}
