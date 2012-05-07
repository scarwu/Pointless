<?php

class pointless_init extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		if(!file_exists(BLOG_MARKDOWN))
			mkdir(BLOG_MARKDOWN, 0755, TRUE);
		
		if(!file_exists(BLOG_PUBLIC))
			mkdir(BLOG_PUBLIC, 0755, TRUE);
		
		if(!file_exists(BLOG_RESOURCE))
			mkdir(BLOG_RESOURCE, 0755, TRUE);
		
		if(!file_exists(BLOG . 'Config.php'))
			copy(ROOT . 'Sample' . SEPARATOR . 'Config.php', BLOG . 'Config.php');
	}
}
