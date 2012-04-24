<?php

class pointless_gen_css extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'Compress.php';
		
		Text::Write("Clean Cascading Style Sheets ...\n", 'yellow');
		if(file_exists(BLOG_PUBLIC . 'main.css'))
			unlink(BLOG_PUBLIC . 'main.css');

		Text::Write("Compress Cascading Style Sheets ...\n", 'yellow');
		$Compress = new Compress();
		$Compress->css(UI_RESOURCE_CSS, BLOG_PUBLIC);
	}
}
