<?php

class pointless_gen_js extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require_once CORE_LIBRARY . 'Compress.php';
		
		NanoIO::writeln("Clean Javascript ...", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.js'))
			unlink(BLOG_PUBLIC . 'main.js');

		NanoIO::writeln("Compress Javascript ...", 'yellow');
		$Compress = new Compress();
		$Compress->js(THEME_JS, BLOG_PUBLIC . 'theme');
	}
}
