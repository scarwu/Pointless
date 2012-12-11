<?php

class pointless_article_add extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$info = array();
		
		do {
			NanoIO::Write("Enter Article Title:\n-> ");
		}
		while('' == $info['title'] = NanoIO::Read());
		
		do {
			NanoIO::Write("Enter Article Custom Url:\n-> ");
		}
		while('' == $info['url'] = NanoIO::Read());
		
		do {
			NanoIO::Write("Enter Article Tag:\n-> ");
		}
		while('' == $info['tag'] = NanoIO::Read());
		
		do {
			NanoIO::Write("Enter Article Category:\n-> ");
		}
		while('' == $info['category'] = NanoIO::Read());
		
		$time = time();
		$filename = sprintf("%s%s.md", date("Ymd_", $time), $info['url']);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(BLOG_MARKDOWN_ARTICLE . $filename)) {
			$handle = fopen(BLOG_MARKDOWN_ARTICLE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"tag": "' . $info['tag'] . '",' . "\n");
			fwrite($handle, '	"category": "' . $info['category'] . '",' . "\n");
			fwrite($handle, '	"date": "' . date("Y-m-d", $time) . '",' . "\n");
			fwrite($handle, '	"time": "' . date("H:i:s", $time) . '"' . "\n");
			fwrite($handle, "}\n-----\n");
			
			NanoIO::Writeln("\nArticle " . $filename . " was create.");
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, BLOG_MARKDOWN_ARTICLE . $filename);
		}
		else
			NanoIO::Writeln("\nArticle" . $filename . " is exsist.");
	}
}
