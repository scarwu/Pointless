<?php

class pointless_gen_css extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'Compress.php';
		
		NanoIO::Writeln("Clean Cascading Style Sheets ...", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.css'))
			unlink(BLOG_PUBLIC . 'main.css');

		NanoIO::Writeln("Compress Cascading Style Sheets ...", 'yellow');
		$Compress = new Compress();
		$Compress->css(THEME_CSS, BLOG_PUBLIC . 'theme');
	}
}
