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

class EditCommand extends Command {
	public function __construct() {
		parent::__construct();
	}

	public function help() {
		IO::writeln('    edit       - Edit article');
		IO::writeln('    edit -s    - Edit Static Page');
	}
	
	public function run() {
		if(!defined('CURRENT_BLOG')) {
			IO::writeln('Please use "poi init <blog name>" to initialize blog.', 'red');
			return;
		}
		
		// Initialize Blog
		initBlog();

		$data = array();
		$handle = opendir(MARKDOWN_FOLDER);
		while($filename = readdir($handle)) {
			if('.' == $filename || '..' == $filename)
				continue;

			preg_match(REGEX_RULE, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
			$temp = json_decode($match[1], TRUE);

			if($this->hasOptions('s')) {
				if('static' != $temp['type'])
					continue;

				$data[$temp['title']]['title'] = $temp['title'];
				$data[$temp['title']]['path'] = MARKDOWN_FOLDER . $filename;
			}
			else {
				if('article' != $temp['type'])
					continue;

				$index = $temp['date'] . $temp['time'];

				$data[$index]['title'] = $temp['title'];
				$data[$index]['date'] = $temp['date'];
				$data[$index]['path'] = MARKDOWN_FOLDER . $filename;
			}
		}
		closedir($handle);

		uksort($data, 'strnatcasecmp');

		$path = array();
		$title = array();
		$count = 0;

		foreach($data as $article) {
			if($this->hasOptions('s'))
				IO::writeln(sprintf("[%3d] %s", $count, $article['title']));
			else
				IO::writeln(sprintf("[%3d] %s %s", $count, $article['date'], $article['title']));
			
			$title[$count] = $article['title'];
			$path[$count++] = $article['path'];
		}
		
		$number = IO::question("\nEnter Number:\n-> ", NULL, function($answer) use($path) {
			return !is_numeric($answer) || $answer < 0 || $answer >= count($path);
		});

		IO::write(sprintf("Are you sure edit %s? [n/y]\n-> ", $title[$number]));
		if(IO::read() == "y")
			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
	}
}
