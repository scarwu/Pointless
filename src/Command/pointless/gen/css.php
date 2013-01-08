<?php

class pointless_gen_css extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require CORE_LIBRARY . 'Compress.php';
		
		NanoIO::writeln("Clean Cascading Style Sheets ...", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.css'))
			unlink(BLOG_PUBLIC . 'main.css');

		NanoIO::writeln("Compress Cascading Style Sheets ...", 'yellow');
		$Compress = new Compress();
		$Compress->css(THEME_CSS, BLOG_PUBLIC . 'theme');
	}
}
