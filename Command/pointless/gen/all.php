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
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->Run();
		
		if(!file_exists(BLOG_PUBLIC))
			mkdir(BLOG_PUBLIC, 0755, TRUE);
		
		NanoIO::Writeln("Copy Resource ...", 'yellow');
		recusive_copy(BLOG_RESOURCE, BLOG_PUBLIC);
		
		if(NULL !== GITHUB_CNAME) {
			NanoIO::Writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		if(!file_exists(BLOG_PUBLIC . 'README')) {
			NanoIO::Writeln("Create README ...", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'README', 'w+');
			fwrite($handle, 'Powered by Pointless');
			fclose($handle);
		}

		$Compress = new Compress();
		
		NanoIO::Writeln("Compress Javascript ...", 'yellow');
		$Compress->js(UI_RESOURCE_JS, BLOG_PUBLIC);
		
		NanoIO::Writeln("Compress Cascading Style Sheets ...", 'yellow');
		$Compress->css(UI_RESOURCE_CSS, BLOG_PUBLIC);
		
		NanoIO::Writeln("Blog Generating ... ", 'yellow');
		$Generator = new Generator();
		$Generator->Run();
	}
}
