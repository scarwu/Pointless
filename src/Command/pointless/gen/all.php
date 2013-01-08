<?php

class pointless_gen_all extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		// Inclide Markdown Library
		require PLUGIN . 'Markdown/markdown.php';
		require LIBRARY . 'Compress.php';
		require LIBRARY . 'Generator.php';
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->run();
		
		$start = microtime(TRUE);
		
		if(!file_exists(PUBLIC_FOLDER))
			mkdir(PUBLIC_FOLDER, 0755, TRUE);
		
		NanoIO::writeln("Copy Resource ...", 'yellow');
		recursiveCopy(RESOURCE_FOLDER, PUBLIC_FOLDER);
		
		if(NULL !== GITHUB_CNAME) {
			NanoIO::writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		if(!file_exists(PUBLIC_FOLDER . 'README')) {
			NanoIO::writeln("Create README ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'README', 'w+');
			fwrite($handle, 'Powered by Pointless');
			fclose($handle);
		}

		$compress = new Compress();
		
		NanoIO::writeln("Compress Javascript ...", 'yellow');
		$compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');
		
		NanoIO::writeln("Compress Cascading Style Sheets ...", 'yellow');
		$compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
		
		recursiveCopy(THEME_RESOURCE, PUBLIC_FOLDER . 'theme');
		
		NanoIO::writeln("Blog Generating ... ", 'yellow');
		$generator = new Generator();
		$generator->run();
		
		$end = sprintf("%.3f", abs(microtime(TRUE) - $start));
		
		NanoIO::writeln("Finished $end s", 'yellow');
	}
}
