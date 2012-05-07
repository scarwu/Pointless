<?php

class pointless_init extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'GeneralFunction.php';
		
		Text::Write("Make dir and files ... \n", 'yellow');
		
		if(!file_exists(BLOG))
			mkdir(BLOG, 0755, TRUE);		
		
		if(!file_exists(BLOG_MARKDOWN))
			mkdir(BLOG_MARKDOWN, 0755, TRUE);
		
		if(!file_exists(BLOG_MARKDOWN_ARTICLE)) {
			mkdir(BLOG_MARKDOWN_ARTICLE, 0755, TRUE);
			copy(ROOT . 'Sample' . SEPARATOR . '1970_01_01_00_00_00.md', BLOG_MARKDOWN_ARTICLE . '1970_01_01_00_00_00.md');
		}
		
		if(!file_exists(BLOG_MARKDOWN_BLOGPAGE)) {
			mkdir(BLOG_MARKDOWN_BLOGPAGE, 0755, TRUE);
			copy(ROOT . 'Sample' . SEPARATOR . 'about.md', BLOG_MARKDOWN_BLOGPAGE . 'about.md');
			copy(ROOT . 'Sample' . SEPARATOR . 'works.md', BLOG_MARKDOWN_BLOGPAGE . 'works.md');
		}
		
		if(!file_exists(BLOG_RESOURCE))
			mkdir(BLOG_RESOURCE, 0755, TRUE);
		
		if(!file_exists(BLOG . SEPARATOR . 'UI'))
			recusive_copy(UI, BLOG . 'UI');
		
		if(!file_exists(BLOG . 'Config.php'))
			copy(ROOT . 'Sample' . SEPARATOR . 'Config.php', BLOG . 'Config.php');
		
		if(!file_exists(BLOG_PUBLIC)) {
			mkdir(BLOG_PUBLIC, 0755, TRUE);
			$gen = new pointless_gen_all();
			$gen->Run();
		}
	}
}
