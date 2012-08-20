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
		$filename = date("Y_md_His", $time) . '.md';
		$info['date'] = date("Y-m-d", $time);
		$info['time'] = date("H:i:s", $time);
		
		if(!file_exists(BLOG_MARKDOWN_ARTICLE . $filename)) {
			$handle = fopen(BLOG_MARKDOWN_ARTICLE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"tag": "' . $info['tag'] . '",' . "\n");
			fwrite($handle, '	"category": "' . $info['category'] . '",' . "\n");
			fwrite($handle, '	"date": "' . $info['date'] . '",' . "\n");
			fwrite($handle, '	"time": "' . $info['time'] . '"' . "\n");
			fwrite($handle, "}\n-----\n");
			
			NanoIO::Writeln("\n" . BLOG_MARKDOWN_ARTICLE . $filename . " was create.");
		}
		else
			NanoIO::Writeln("\n" . BLOG_MARKDOWN_ARTICLE . $filename . " is exsist.");
	}
}
