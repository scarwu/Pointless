<?php

class pointless_blogpage_edit extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		$path = array();
		$count = 0;
		$handle = opendir(BLOG_MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(BLOG_MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				NanoIO::writeln(sprintf("[%3d] %s", $count, $temp['title']));
				$path[$count++] = BLOG_MARKDOWN_BLOGPAGE . $filename;
			}
		closedir($handle);

		NanoIO::write("\nEdit your blog page? [y]\n-> ");
		if(NanoIO::read() == "y") {
			do {
				NanoIO::write("Enter Number:\n-> ");
			}
			while(!is_numeric($number = NanoIO::read()) || $number < 0 || $number >= count($path));

			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
		}
	}
}
