<?php

class pointless_gen_css extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		
		NanoIO::writeln("Clean Cascading Style Sheets ...", 'yellow');
		if(file_exists(PUBLIC_FOLDER . 'main.css'))
			unlink(PUBLIC_FOLDER . 'main.css');

		NanoIO::writeln("Compress Cascading Style Sheets ...", 'yellow');
		$Compress = new Compress();
		$Compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
	}
}
