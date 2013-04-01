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
		if($this->hasOptions('s')) {
			$this->staticPage();
			return;
		}

		$this->article();
	}

	public function article() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';
		
		$data = array();
		$handle = opendir(MARKDOWN_FOLDER);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				$data[$temp['date'].$temp['time']] = $temp;
				$data[$temp['date'].$temp['time']]['path'] = MARKDOWN_FOLDER . $filename;
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

		IO::write("Edit article? [n/y]\n-> ");
		if(IO::read() == "y")
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
	}

	public function staticPage() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';
		
		$path = array();
		$count = 0;
		$handle = opendir(MARKDOWN_FOLDER);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				IO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
				$path[$count++] = MARKDOWN_FOLDER . $filename;
			}
		closedir($handle);

		do {
			IO::write("\nEnter Number:\n-> ");
		}
		while(!is_numeric($number = IO::read()) || $number < 0 || $number >= count($path));

		IO::write("Edit static page? [n/y]\n-> ");
		if(IO::read() == "y")
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
	}
}
