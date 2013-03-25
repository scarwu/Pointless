<?php
/**
 * Pointless Edit Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Edit extends Command {
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
		IO::writeln('    edit -a    - Edit article');
		IO::writeln('    edit -bp   - Edit blog page');
	}

	public function article() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';
		
		$data = array();
		$handle = opendir(MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_ARTICLE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				$data[$temp['date'].$temp['time']] = $temp;
				$data[$temp['date'].$temp['time']]['path'] = MARKDOWN_ARTICLE . $filename;
			}
		closedir($handle);
		ksort($data);

		$path = array();
		$count = 0;
		foreach($data as $article) {
			IO::writeln(sprintf("[%3d] %s %s", $count, $article['date'], $article['title']));
			$path[$count++] = $article['path'];
		}
		
		do {
			IO::write("\nEnter Number:\n-> ");
		}
		while(!is_numeric($number = IO::read()) || $number < 0 || $number >= count($path));

		IO::write("Edit your article? [n/y]\n-> ");
		if(IO::read() == "y")
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
	}

	public function blogpage() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';
		
		$path = array();
		$count = 0;
		$handle = opendir(MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				IO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
				$path[$count++] = MARKDOWN_BLOGPAGE . $filename;
			}
		closedir($handle);

		do {
			IO::write("\nEnter Number:\n-> ");
		}
		while(!is_numeric($number = IO::read()) || $number < 0 || $number >= count($path));

		IO::write("Edit your blog page? [n/y]\n-> ");
		if(IO::read() == "y")
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
	}
}
