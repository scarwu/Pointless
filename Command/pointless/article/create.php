<?php

class pointless_article_create extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$info = array();
		
		do {
			Text::Write("Enter Article Title:\n-> ");
		}
		while('' == $info['title'] = Text::Read());
		
		do {
			Text::Write("Enter Article Custom Url:\n-> ");
		}
		while('' == $info['url'] = Text::Read());
		
		do {
			Text::Write("Enter Article Tag:\n-> ");
		}
		while('' == $info['tag'] = Text::Read());
		
		do {
			Text::Write("Enter Article Category:\n-> ");
		}
		while('' == $info['category'] = Text::Read());
		
		$time = time();
		$filename = date("Y_m_d_H_i_s", $time) . '.md';
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
			
			Text::Write($filename . " was create.\n");
		}
		else
			Text::Write($filename . " is exsist.\n");
	}
}
