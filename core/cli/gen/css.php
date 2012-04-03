<?php

class pointless_gen_css extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require_once CORE_LIB . 'compress.php';
		
		if(file_exists(HTDOCS . 'main.css'))
			unlink(HTDOCS . 'main.css');
		
		$compress = new compress();
		$compress->css();
	}
}
