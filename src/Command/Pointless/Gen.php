<?php
/**
 * Pointless Gen Command
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

namespace Pointless;

use NanoCLI\Command;
use NanoCLI\IO;
use Compress;
use Resource;
use PageGenerator;
use ExtensionLoader;

class Gen extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		require LIBRARY . 'Resource.php';
		require LIBRARY . 'PageGenerator.php';
		require LIBRARY . 'ExtensionLoader.php';
		require PLUGIN . 'Markdown/markdown.php';
		
		$start = microtime(TRUE);
		
		if(!file_exists(PUBLIC_FOLDER))
			mkdir(PUBLIC_FOLDER, 0755, TRUE);

		// Clear Public Files
		IO::writeln("Clear Public Files ...", 'yellow');
		recursiveRemove(PUBLIC_FOLDER);

		// Create Github CNAME
		if(NULL !== GITHUB_CNAME) {
			IO::writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}
		
		// Create README
		if(!file_exists(PUBLIC_FOLDER . 'README')) {
			IO::writeln("Create README ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'README', 'w+');
			fwrite($handle, '[Powered by Pointless](https://github.com/scarwu/Pointless)');
			fclose($handle);
		}

		// Copy Resource Files
		IO::writeln("Copy Resource Files ...", 'yellow');
		recursiveCopy(RESOURCE_FOLDER, PUBLIC_FOLDER);
		recursiveCopy(THEME_RESOURCE, PUBLIC_FOLDER . 'theme');

		// Compress CSS and JavaScript
		IO::writeln("Compress CSS & Javascript ...", 'yellow');
		$compress = new Compress();
		$compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');
		$compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
		
		// Initialize Resource Pool
		IO::writeln("Initialize Resource Pool ...", 'yellow');
		$this->blogpage();
		$this->article();

		// Generate HTML Pages
		IO::writeln("Generating HTML ...", 'yellow');
		$html = new HTMLGenerator();
		$html->run();

		// Generate Extension
		IO::writeln("Generating Extensions ...", 'yellow');
		$extension = new ExtensionLoader();
		$extension->run();
		
		$end = sprintf("%.3f", abs(microtime(TRUE) - $start));
		
		IO::writeln("Finished $end s", 'green');
	}

	/**
	 * Load Blog Page
	 */
	private function blogpage() {
		
		$regex = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';

		// Handle Blog Page Markdown
		$handle = opendir(MARKDOWN_BLOGPAGE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match($regex, file_get_contents(MARKDOWN_BLOGPAGE . $filename), $match);
				
				$temp = json_decode($match[1], TRUE);

				Resource::set('blogpage', array(
					'title' => $temp['title'],
					'url' => $temp['url'],
					'content' => Markdown($match[2]),
					'message' => isset($temp['message']) ? $temp['message'] : TRUE
				));
			}
		closedir($handle);
	}

	/**
	 * Load Article
	 */
	private function article() {
		
		$regex = '/^-----\n((?:.|\n)*)\n-----\n((?:.|\n)*)/';

		// Handle Article Markdown
		$handle = opendir(MARKDOWN_ARTICLE);
		while($filename = readdir($handle))
			if('.' != $filename && '..' != $filename && preg_match('/.md$/', $filename)) {
				preg_match($regex, file_get_contents(MARKDOWN_ARTICLE . $filename), $match);

				$temp = json_decode($match[1], TRUE);

				if(FALSE != (isset($temp['publish']) ? $temp['publish'] : TRUE)) {
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

					Resource::set('article', array(
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
					));
				}
			}
		closedir($handle);
	}
}