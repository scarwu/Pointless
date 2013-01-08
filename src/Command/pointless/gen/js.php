<?php

class pointless_gen_js extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		
		NanoIO::writeln("Clean Javascript ...", 'yellow');
		if(file_exists(PUBLIC_FOLDER . 'main.js'))
			unlink(PUBLIC_FOLDER . 'main.js');

		NanoIO::writeln("Compress Javascript ...", 'yellow');
		$Compress = new Compress();
		$Compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');
	}
}
