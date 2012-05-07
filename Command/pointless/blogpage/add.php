<?php

class pointless_blogpage_add extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$info = array();
		
		do {
			Text::Write("Enter Blog Page Title:\n-> ");
		}
		while('' == $info['title'] = Text::Read());
		
		do {
			Text::Write("Enter Blog Page Custom Url:\n-> ");
		}
		while('' == $info['url'] = Text::Read());
		
		$filename = strtolower($info['title']) . '.md';
		$filename = str_replace(array('\\', '/', ' '), '_', $filename);
		
		if(!file_exists(BLOG_MARKDOWN_BLOGPAGE . $filename)) {
			$handle = fopen(BLOG_MARKDOWN_BLOGPAGE . $filename, 'w+');
			fwrite($handle, "-----\n{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '"' . "\n");
			fwrite($handle, "}\n-----\n");
			
			Text::Write("\n" . BLOG_MARKDOWN_BLOGPAGE . $filename . " was create.\n");
		}
		else
			Text::Write("\n" . BLOG_MARKDOWN_BLOGPAGE . $filename . " is exsist.\n");
	}
}
