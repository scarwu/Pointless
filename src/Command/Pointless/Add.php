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
		$info = array(
			'title' => IO::question("Enter Title:\n-> "),
			'url' => IO::question("Enter Custom Url:\n-> ")
		);

		if(!$this->hasOptions('s')) {
			$info['tag'] = IO::question("Enter Tag:\n-> ");
			$info['category'] = IO::question("Enter Category:\n-> ");
		}

		if(NULL != LOCAL_ENCODING)
			foreach($info as $key => $value)
				$info[$key] = iconv(LOCAL_ENCODING, 'utf-8', $value);

		if($this->hasOptions('s')) {
			$filename = str_replace(array('\\', '/', ' '), '-', $info['title']);
			$filename = 'static_' . strtolower($filename) . '.md';

			if(!file_exists(MARKDOWN_FOLDER . $filename)) {
				$handle = fopen(MARKDOWN_FOLDER . $filename, 'w+');
				fwrite($handle, "{\n");
				fwrite($handle, '	"type": "static",' . "\n");
				fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
				fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
				fwrite($handle, '	"message": false' . "\n");
				fwrite($handle, "}\n\n\n");
				
				IO::writeln("\nStatic Page $filename was created.");
				system(FILE_EDITOR . ' ' . MARKDOWN_FOLDER . "$filename < `tty` > `tty`");
			}
			else
				IO::writeln("\nStatic Page $filename is exsist.");
		}
		else {
			$time = time();
			$filename = sprintf("%s%s.md", date("Ymd_", $time), $info['url']);

			if(!file_exists(MARKDOWN_FOLDER . $filename)) {
				$handle = fopen(MARKDOWN_FOLDER . $filename, 'w+');
				fwrite($handle, "{\n");
				fwrite($handle, '	"type": "article",' . "\n");
				fwrite($handle, '	"title": "' . $info['title'] . '",' . "\n");
				fwrite($handle, '	"url": "' . $info['url'] . '",' . "\n");
				fwrite($handle, '	"tag": "' . $info['tag'] . '",' . "\n");
				fwrite($handle, '	"category": "' . $info['category'] . '",' . "\n");
				fwrite($handle, '	"date": "' . date("Y-m-d", $time) . '",' . "\n");
				fwrite($handle, '	"time": "' . date("H:i:s", $time) . '",' . "\n");
				fwrite($handle, '	"message": true,' . "\n");
				fwrite($handle, '	"publish": false' . "\n");
				fwrite($handle, "}\n\n\n");
				
				IO::writeln("\nArticle $filename was created.");
				system(FILE_EDITOR . ' ' . MARKDOWN_FOLDER . "$filename < `tty` > `tty`");
			}
			else
				IO::writeln("\nArticle $filename is exsist.");
		}
	}
}
