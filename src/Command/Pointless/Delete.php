<?php

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Delete extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		IO::writeln("[  0] Article\n[  1] Blog page\n");
		do {
			IO::write("Enter Number:\n-> ");
		} while(!is_numeric($number = IO::read()) || $number < 0 || $number > 1);
		
		if($number == 0)
			$this->article();
		else
			$this->blogpage();
	}

	public function article() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
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
		$title = array();
		$count = 0;
		foreach($data as $article) {
			IO::writeln(sprintf("[%3d] %s %s", $count, $article['date'], $article['title']));
			$title[$count] = $article['title'];
			$path[$count++] = $article['path'];

		}
		
		do {
			IO::write("\nEnter Number:\n-> ");
		}
		while(!is_numeric($number = IO::read()) || $number < 0 || $number >= count($path));

		IO::write(sprintf("Are you sure delete article - %s? [n/y]\n-> ", $title[$number]), 'red');
		if(IO::read() == "y") {
			system('rm ' . $path[$number]);
			IO::writeln(sprintf('Successfully removed %s.', $title[$number]));
		}
	}

	public function blogpage() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		$path = array();
		$title = array();
		$count = 0;
		$handle = opendir(MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				IO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
				$title[$count] = $temp['title'];
				$path[$count++] = MARKDOWN_BLOGPAGE . $filename;
			}
		closedir($handle);

		do {
			IO::write("\nEnter Number:\n-> ");
		}
		while(!is_numeric($number = IO::read()) || $number < 0 || $number >= count($path));

		IO::write(sprintf("Are you sure delete blogpage - %s? [n/y]\n-> ", $title[$number]), 'red');
		if(IO::read() == "y") {
			system('rm ' . $path[$number]);
			IO::writeln(sprintf('Successfully removed %s.', $title[$number]));
		}
	}
}
