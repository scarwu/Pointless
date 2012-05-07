<?php

class pointless_gen_all extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		// Inclide Markdown Library
		require_once CORE_PLUGIN . 'Markdown' . SEPARATOR . 'markdown.php';
		require_once CORE_LIBRARY . 'Compress.php';
		require_once CORE_LIBRARY . 'CustomSort.php';
		require_once CORE_LIBRARY . 'GeneralFunction.php';
		require_once CORE_LIBRARY . 'Generator.php';
		require_once BLOG . 'Config.php';
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->Run();
		
		if(!file_exists(BLOG_PUBLIC))
			mkdir(HTDOCS, 0755, TRUE);
		
		Text::Write("Copy Resource ...\n", 'yellow');
		recusive_copy(BLOG_RESOURCE, BLOG_PUBLIC);
		
		if(NULL !== GITHUB_CNAME) {
			Text::Write("Create Github CNAME ...\n", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		if(!file_exists(BLOG_PUBLIC . 'README')) {
			Text::Write("Create README ...\n", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'README', 'w+');
			fwrite($handle, 'Powered by Pointless');
			fclose($handle);
		}

		$Compress = new Compress();
		
		Text::Write("Compress Javascript ...\n", 'yellow');
		$Compress->js(UI_RESOURCE_JS, BLOG_PUBLIC);
		
		Text::Write("Compress Cascading Style Sheets ...\n", 'yellow');
		$Compress->css(UI_RESOURCE_CSS, BLOG_PUBLIC);

		$Generator = new Generator();
		$Generator->Run();
	}
}
