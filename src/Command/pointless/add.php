<?php

class pointless_add extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		NanoIO::writeln("[  0] Article\n[  1] Blog page\n");
		do {
			NanoIO::write("Enter Number:\n-> ");
		} while(!is_numeric($number = NanoIO::read()) || $number < 0 || $number > 1);
		
		if($number == 0)
			$this->article();
		else
			$this->blogpage();
	}

	public function article() {
		$info = array();
		
		do {
			NanoIO::write("Enter Article Title:\n-> ");
		}
		while('' == $info['title'] = NanoIO::read());
		
		do {
			NanoIO::write("Enter Article Custom Url:\n-> ");
		}
		while('' == $info['url'] = NanoIO::read());
		
		do {
			NanoIO::write("Enter Article Tag:\n-> ");
		}
		while('' == $info['tag'] = NanoIO::read());
		
		do {
			NanoIO::write("Enter Article Category:\n-> ");
		}
		while('' == $info['category'] = NanoIO::read());
		
		$time = time();
		$filename = sprintf("%s%s.md", date("Ymd_", $time), $info['url']);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(MARKDOWN_ARTICLE . $filename)) {
			$handle = fopen(MARKDOWN_ARTICLE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"tag": "' . $info['tag'] . '",' . "\n");
			fwrite($handle, '	"category": "' . $info['category'] . '",' . "\n");
			fwrite($handle, '	"date": "' . date("Y-m-d", $time) . '",' . "\n");
			fwrite($handle, '	"time": "' . date("H:i:s", $time) . '"' . "\n");
			fwrite($handle, "}\n-----\n");
			
			NanoIO::writeln("\nArticle " . $filename . " was create.");
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, MARKDOWN_ARTICLE . $filename));
		}
		else
			NanoIO::writeln("\nArticle" . $filename . " is exsist.");
	}

	public function blogpage() {
		$info = array();
		
		do {
			NanoIO::write("Enter Blog Page Title:\n-> ");
		}
		while('' == $info['title'] = NanoIO::read());
		
		do {
			NanoIO::write("Enter Blog Page Custom Url:\n-> ");
		}
		while('' == $info['url'] = NanoIO::read());
		
		$filename = strtolower($info['title']) . '.md';
		$filename = str_replace(array('\\', '/', ' '), '_', $filename);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(MARKDOWN_BLOGPAGE . $filename)) {
			$handle = fopen(MARKDOWN_BLOGPAGE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"message": true' . "\n");
			fwrite($handle, "}\n-----\n");
			
			NanoIO::writeln("\nBlog Page " . $filename . " was create.");
			system(FILE_EDITOR . " " . MARKDOWN_BLOGPAGE . $filename . " < `tty` > `tty`");
		}
		else
			NanoIO::writeln("\nBlog Page " . $filename . " is exsist.");
	}
}
