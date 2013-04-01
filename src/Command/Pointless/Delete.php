<?php
/**
 * Pointless Delete Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;

class Delete extends Command {
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
		while($filename = readdir($handle)) {
			if('.' == $filename || '..' == $filename)
				continue;

			preg_match($regex_rule, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
			$temp = json_decode($match[1], TRUE);

			if('article' != $temp['type'])
				continue;

			$data[$temp['date'].$temp['time']] = $temp;
			$data[$temp['date'].$temp['time']]['path'] = MARKDOWN_FOLDER . $filename;
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
		
		$number = IO::question("\nEnter Number:\n-> ", NULL, function($answer) use ($path) {
			return !is_numeric($answer) || $answer < 0 || $answer >= count($path);
		});

		IO::write(sprintf("Are you sure delete article - %s? [n/y]\n-> ", $title[$number]), 'red');
		if(IO::read() == "y") {
			system('rm ' . $path[$number]);
			IO::writeln(sprintf('Successfully removed %s.', $title[$number]));
		}
	}

	public function staticPage() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';
		
		$path = array();
		$title = array();
		$count = 0;
		$handle = opendir(MARKDOWN_FOLDER);
		while($filename = readdir($handle)) {
			if('.' == $filename || '..' == $filename)
				continue;

			preg_match($regex_rule, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
			$temp = json_decode($match[1], TRUE);

			if('static' != $temp['type'])
				continue;

			IO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
			$title[$count] = $temp['title'];
			$path[$count++] = MARKDOWN_FOLDER . $filename;
			
		}
		closedir($handle);

		$number = IO::question("\nEnter Number:\n-> ", NULL, function($answer) use ($path) {
			return !is_numeric($answer) || $answer < 0 || $answer >= count($path);
		});

		IO::write(sprintf("Are you sure delete static page - %s? [n/y]\n-> ", $title[$number]), 'red');
		if(IO::read() == "y") {
			system('rm ' . $path[$number]);
			IO::writeln(sprintf('Successfully removed %s.', $title[$number]));
		}
	}
}
