<?php

class pointless_gen_all extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		// Inclide Markdown Library
		require CORE_PLUGIN . 'Markdown' . SEPARATOR . 'markdown.php';
		require CORE_LIBRARY . 'Compress.php';
		require CORE_LIBRARY . 'GeneralFunction.php';
		require CORE_LIBRARY . 'Generator.php';
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->run();
		
		$start = microtime(TRUE);
		
		if(!file_exists(BLOG_PUBLIC))
			mkdir(BLOG_PUBLIC, 0755, TRUE);
		
		NanoIO::writeln("Copy Resource ...", 'yellow');
		recursiveCopy(BLOG_RESOURCE, BLOG_PUBLIC);
		
		if(NULL !== GITHUB_CNAME) {
			NanoIO::writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		if(!file_exists(BLOG_PUBLIC . 'README')) {
			NanoIO::writeln("Create README ...", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'README', 'w+');
			fwrite($handle, 'Powered by Pointless');
			fclose($handle);
		}

		$compress = new Compress();
		
		NanoIO::writeln("Compress Javascript ...", 'yellow');
		$compress->js(THEME_JS, BLOG_PUBLIC . 'theme');
		
		NanoIO::writeln("Compress Cascading Style Sheets ...", 'yellow');
		$compress->css(THEME_CSS, BLOG_PUBLIC . 'theme');
		
		recursiveCopy(THEME_RESOURCE, BLOG_PUBLIC . 'theme');
		
		NanoIO::writeln("Blog Generating ... ", 'yellow');
		$generator = new Generator();
		$generator->run();
		
		$end = sprintf("%.3f", abs(microtime(TRUE) - $start));
		
		NanoIO::writeln("Finished $end s", 'yellow');
	}
}
