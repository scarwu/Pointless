<?php

class pointless_article_edit extends NanoCLI {
	public function __construct() {
		parent::__construct();
	}
	
	public function Run() {
		$regex_rule = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';
		
		$data = array();
		$handle = opendir(BLOG_MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename) {
				preg_match($regex_rule, file_get_contents(BLOG_MARKDOWN_ARTICLE . $filename), $match);
				$temp = json_decode($match[1], TRUE);
				$data[$temp['date'].$temp['time']] = $temp;
				$data[$temp['date'].$temp['time']]['path'] = BLOG_MARKDOWN_ARTICLE . $filename;
			}
		closedir($handle);
		ksort($data);

		$path = array();
		$count = 0;
		foreach($data as $article) {
			NanoIO::Writeln(sprintf("[%3d] %s %s", $count, $article['date'], $article['title']));
			$path[$count++] = $article['path'];
		}
		
		NanoIO::Write("\nEdit your article? [y]\n-> ");
		if(NanoIO::Read() == "y") {
			do {
				NanoIO::Write("Enter Number:\n-> ");
			}
			while(!is_numeric($number = NanoIO::Read()) || $number < 0 || $number >= count($path));

			system(sprintf("%s %s < `tty` > `tty`", FILE_EDITOR, $path[$number]));
		}
	}
}
