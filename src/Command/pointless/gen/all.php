<?php

class pointless_gen_all extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		require LIBRARY . 'Resource.php';
		require LIBRARY . 'Generator.php';
		
		// Clean Public Files
		$clean = new pointless_gen_clean();
		$clean->run();
		
		$start = microtime(TRUE);
		
		if(!file_exists(PUBLIC_FOLDER))
			mkdir(PUBLIC_FOLDER, 0755, TRUE);

		// Create Github CNAME
		if(NULL !== GITHUB_CNAME) {
			NanoIO::writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		// Create README
		if(!file_exists(PUBLIC_FOLDER . 'README')) {
			NanoIO::writeln("Create README ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'README', 'w+');
			fwrite($handle, '[Powered by Pointless](https://github.com/scarwu/Pointless)');
			fclose($handle);
		}

		// Copy Resource Files
		NanoIO::writeln("Copy Resource Files ...", 'yellow');
		recursiveCopy(RESOURCE_FOLDER, PUBLIC_FOLDER);
		recursiveCopy(THEME_RESOURCE, PUBLIC_FOLDER . 'theme');

		// Compress CSS and JavaScript
		NanoIO::writeln("Compress CSS & Javascript ...", 'yellow');
		$compress = new Compress();
		$compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');
		$compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
		
		// Initialize Resource Pool
		NanoIO::writeln("Initialize Resource Pool ...", 'yellow');
		Resource::init();

		// Generate Pages
		NanoIO::writeln("Generating Pages ...", 'yellow');
		$generator = new Generator();
		$generator->run();
		
		$end = sprintf("%.3f", abs(microtime(TRUE) - $start));
		
		NanoIO::writeln("Finished $end s", 'green');
	}
}
