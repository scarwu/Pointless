<?php

class Resource {

	private static $_regex = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';

	private static $_resource = array(
		'source' => array(
			'article' => array(),
			'blogpage' => array()
		)
	);

	private function __construct() {}

	public static function init() {
		require PLUGIN . 'Markdown/markdown.php';

		self::blogpage();
		self::article();
	}

	public static function get($index) {
		return isset(self::$_resource[$index]) ? self::$_resource[$index] : NULL;
	}

	public static function set($index, $data) {
		self::$_resource[$index] = $data;
	}

	/**
	 * Load Blog Page
	 */
	private static function blogpage() {
		
		// Handle Blog Page Markdown
		$handle = opendir(MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match(self::$_regex, file_get_contents(MARKDOWN_BLOGPAGE . $filename), $match);
				
				$temp = json_decode($match[1], TRUE);

				self::$_resource['source']['blogpage'][] = array(
					'title' => $temp['title'],
					'url' => $temp['url'],
					'content' => Markdown($match[2]),
					'message' => isset($temp['message']) ? $temp['message'] : TRUE
				);
			}
		closedir($handle);
	}

	/**
	 * Load Article
	 */
	private static function article() {
		
		// Handle Article Markdown
		$handle = opendir(MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match(self::$_regex, file_get_contents(MARKDOWN_ARTICLE . $filename), $match);

				$temp = json_decode($match[1], TRUE);

				$date = explode('-', $temp['date']);
				$time = explode(':', $temp['time']);

				// 0: date, 1: url, 2: date + url
				switch(ARTICLE_URL) {
					default:
					case 0:
						$url = str_replace('-', '/', $temp['date']);
						break;
					case 1:
						$url = $temp['url'];
						break;
					case 2:
						$url = str_replace('-', '/', $temp['date']) . '/' . $temp['url'];
						break;
				}

				self::$_resource['source']['article'][] = array(
					'title' => $temp['title'],
					'url' => $url,
					'content' => Markdown($match[2]),
					'date' => $temp['date'],
					'time' => $temp['time'],
					'category' => $temp['category'],
					'tag' => explode('|', $temp['tag']),
					'year' => $date[0],
					'month' => $date[1],
					'day' => $date[2],
					'hour' => $time[0],
					'minute' => $time[1],
					'second' => $time[2]
				);
			}
		closedir($handle);
	}
}