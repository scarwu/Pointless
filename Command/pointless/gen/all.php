<?php

class pointless_gen_all extends CLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once CORE_LIBRARY . 'Compress.php';
		require_once CORE_LIBRARY . 'Generator.php';
		require_once BLOG . 'Config.php';
		
		// Clean static pages
		$clean = new pointless_gen_clean();
		$clean->Run();
		
		if(!file_exists(BLOG_PUBLIC))
			mkdir(HTDOCS, 0755, TRUE);
		
		Text::Write("Copy Resource ...\n", 'yellow');
		$this->rCopy(BLOG_RESOURCE, BLOG_PUBLIC);
		
		if(NULL !== GOOGLE_WEBMASTER) {
			Text::Write("Create Google Webmaster File ...\n", 'yellow');
			$handle = fopen(BLOG_PUBLIC . GOOGLE_WEBMASTER . '.html', 'w+');
			fwrite($handle, 'google-site-verification: ' . GOOGLE_WEBMASTER . '.html');
			fclose($handle);
		}
		
		if(NULL !== CNAME) {
			Text::Write("Create CNAME ...\n", 'yellow');
			$handle = fopen(BLOG_PUBLIC . 'CNAME', 'w+');
			fwrite($handle, CNAME);
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
		$Compress->js(UI_JS, BLOG_PUBLIC);
		
		Text::Write("Compress Cascading Style Sheets ...\n", 'yellow');
		$Compress->css(UI_CSS, BLOG_PUBLIC);

		$Generator = new Generator();
		$Generator->Run();
	}
	
	private function rCopy($src, $dest) {
		if(is_dir($src)) {
			if(!file_exists($dest))
				mkdir($dest, 0755, TRUE);
			$handle = @opendir($src);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					$this->rCopy($src . DIRECTORY_SEPARATOR . $file, $dest . DIRECTORY_SEPARATOR . $file);
			closedir($handle);
		}
		else
			copy($src, $dest);
	}
}
