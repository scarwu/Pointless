<?php
/**
 * Pointless Add Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Add extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		if($this->hasOptions('a')) {
			$this->article();
			return;
		}

		if($this->hasOptions('bp')) {
			$this->blogpage();
			return;
		}

		IO::writeln('Pointless Help:', 'green');
		IO::writeln('    add -a     - Add article');
		IO::writeln('    add -bp    - Add blog page');
	}

	public function article() {
		$info = array();
		
		do {
			IO::write("Enter Article Title:\n-> ");
		}
		while('' == $info['title'] = IO::read());
		
		do {
			IO::write("Enter Article Custom Url:\n-> ");
		}
		while('' == $info['url'] = IO::read());
		
		do {
			IO::write("Enter Article Tag:\n-> ");
		}
		while('' == $info['tag'] = IO::read());
		
		do {
			IO::write("Enter Article Category:\n-> ");
		}
		while('' == $info['category'] = IO::read());
		
		$time = time();
		$filename = sprintf("%s%s.md", date("Ymd_", $time), $info['url']);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(MARKDOWN_ARTICLE . $filename)) {
			$handle = fopen(MARKDOWN_ARTICLE . $filename, 'w+');
			fwrite($handle, "{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"tag": "' . $info['tag'] . '",' . "\n");
			fwrite($handle, '	"category": "' . $info['category'] . '",' . "\n");
			fwrite($handle, '	"date": "' . date("Y-m-d", $time) . '",' . "\n");
			fwrite($handle, '	"time": "' . date("H:i:s", $time) . '",' . "\n");
			fwrite($handle, '	"publish": false' . "\n");
			fwrite($handle, "}\n\n\n");
			
			IO::writeln("\nArticle " . $filename . " was created.");
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, MARKDOWN_ARTICLE . $filename));
		}
		else
			IO::writeln("\nArticle" . $filename . " is exsist.");
	}

	public function blogpage() {
		$info = array();
		
		do {
			IO::write("Enter Blog Page Title:\n-> ");
		}
		while('' == $info['title'] = IO::read());
		
		do {
			IO::write("Enter Blog Page Custom Url:\n-> ");
		}
		while('' == $info['url'] = IO::read());
		
		$filename = strtolower($info['title']) . '.md';
		$filename = str_replace(array('\\', '/', ' '), '_', $filename);
		
		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);
		
		if(!file_exists(MARKDOWN_BLOGPAGE . $filename)) {
			$handle = fopen(MARKDOWN_BLOGPAGE . $filename, 'w+');
			fwrite($handle, "{\n");
			fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
			fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
			fwrite($handle, '	"message": true' . "\n");
			fwrite($handle, "}\n\n\n");
			
			IO::writeln("\nBlog Page " . $filename . " was created.");
			system(FILE_EDITOR . " " . MARKDOWN_BLOGPAGE . $filename . " < `tty` > `tty`");
		}
		else
			IO::writeln("\nBlog Page " . $filename . " is exsist.");
	}
}
