<?php

class pointless_gen_js extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'Compress.php';
		
		Text::Write("Clean Javascript ...\n", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.js'))
			unlink(BLOG_PUBLIC . 'main.js');

		Text::Write("Compress Javascript ...\n", 'yellow');
		$Compress = new Compress();
		$Compress->js(UI_CSS, BLOG_PUBLIC);
	}
}
