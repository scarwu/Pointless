<?php

class pointless_blogpage_add extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		require_once BLOG . 'Config.php';
		
		$info = array();
		
		do {
			NanoIO::Write("Enter Blog Page Title:\n-> ");
		}
		while('' == $info['title'] = NanoIO::Read());
		
		do {
			NanoIO::Write("Enter Blog Page Custom Url:\n-> ");
		}
		while('' == $info['url'] = NanoIO::Read());
		
		$filename = strtolower($info['title']) . '.md';
		$filename = str_replace(array('\\', '/', ' '), '_', $filename);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(BLOG_MARKDOWN_BLOGPAGE . $filename)) {
			$handle = fopen(BLOG_MARKDOWN_BLOGPAGE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '"' . "\n");
			fwrite($handle, "}\n-----\n");
			
			NanoIO::Writeln("\n" . BLOG_MARKDOWN_BLOGPAGE . $filename . " was create.");
		}
		else
			NanoIO::Writeln("\n" . BLOG_MARKDOWN_BLOGPAGE . $filename . " is exsist.");
	}
}
