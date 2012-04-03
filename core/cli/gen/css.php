<?php

class pointless_gen_css extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		echo "Clean Cascading Style Sheets";
		
		require_once CORE_LIB . 'compress.php';
		
		if(file_exists(HTDOCS . 'main.css'))
			unlink(HTDOCS . 'main.css');
		
		echo "...OK!\n";
		
		$compress = new compress();
		$compress->css();
	}
}
