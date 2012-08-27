<?php

class pointless_gen_js extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'Compress.php';
		
		NanoIO::Writeln("Clean Javascript ...", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.js'))
			unlink(BLOG_PUBLIC . 'main.js');

		NanoIO::Writeln("Compress Javascript ...", 'yellow');
		$Compress = new Compress();
		$Compress->js(THEME_JS, BLOG_PUBLIC . 'theme');
	}
}
