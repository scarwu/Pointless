<?php

class pointless_blogpage_list extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		$handle = opendir(BLOG_MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(BLOG_MARKDOWN_BLOGPAGE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				NanoIO::Writeln($temp['title']);
			}
		closedir($handle);
	}
}
