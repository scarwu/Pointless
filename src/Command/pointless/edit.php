<?php

class pointless_edit extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		NanoIO::writeln("[  0] Article\n[  1] Blog page\n");
		do {
			NanoIO::write("Enter Number:\n-> ");
		} while(!is_numeric($number = NanoIO::read()) || $number < 0 || $number > 1);
		
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
		$count = 0;
		foreach($data as $article) {
			NanoIO::writeln(sprintf("[%3d] %s %s", $count, $article['date'], $article['title']));
			$path[$count++] = $article['path'];
		}
		
		NanoIO::write("\nEdit your article? [n/y]\n-> ");
		if(NanoIO::read() == "y") {
			do {
				NanoIO::write("Enter Number:\n-> ");
			}
			while(!is_numeric($number = NanoIO::read()) || $number < 0 || $number >= count($path));

			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
		}
	}

	public function blogpage() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		$path = array();
		$count = 0;
		$handle = opendir(MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				NanoIO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
				$path[$count++] = MARKDOWN_BLOGPAGE . $filename;
			}
		closedir($handle);

		NanoIO::write("\nEdit your blog page? [n/y]\n-> ");
		if(NanoIO::read() == "y") {
			do {
				NanoIO::write("Enter Number:\n-> ");
			}
			while(!is_numeric($number = NanoIO::read()) || $number < 0 || $number >= count($path));

			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
		}
	}
}
