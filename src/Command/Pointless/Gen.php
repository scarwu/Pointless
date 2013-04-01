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
use HTMLGenerator;
use ExtensionLoader;

class Gen extends Command {
	public function __construct() {
		parent::__construct();
	}
	
	public function run() {
		require LIBRARY . 'Compress.php';
		require LIBRARY . 'Resource.php';
		require LIBRARY . 'HTMLGenerator.php';
		require LIBRARY . 'ExtensionLoader.php';
		require PLUGIN . 'Markdown/markdown.php';
		
		$start = microtime(TRUE);

		if($this->hasOptions('css')) {
			if(file_exists(PUBLIC_FOLDER . 'main.css'))
				unlink(PUBLIC_FOLDER . 'main.css');

			IO::writeln("Compress CSS ...", 'yellow');
			$Compress = new Compress();
			$Compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');

			$time = sprintf("%.3f", abs(microtime(TRUE) - $start));
			IO::writeln("Generate finish, $time s.", 'green');

			return;
		}

		if($this->hasOptions('js')) {
			if(file_exists(PUBLIC_FOLDER . 'main.js'))
				unlink(PUBLIC_FOLDER . 'main.js');

			IO::writeln("Compress Javascript ...", 'yellow');
			$Compress = new Compress();
			$Compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');

			$time = sprintf("%.3f", abs(microtime(TRUE) - $start));
			IO::writeln("Generate finish, $time s.", 'green');

			return;
		}

		$start_mem = memory_get_usage();

		// Clear Public Files
		IO::writeln("Clean Public Files ...", 'yellow');
		recursiveRemove(PUBLIC_FOLDER);
		
		// Create README
		$handle = fopen(PUBLIC_FOLDER . 'README', 'w+');
		fwrite($handle, '[Powered by Pointless](https://github.com/scarwu/Pointless)');
		fclose($handle);

		// Create Github CNAME
		if(NULL !== GITHUB_CNAME) {
			IO::writeln("Create Github CNAME ...", 'yellow');
			$handle = fopen(PUBLIC_FOLDER . 'CNAME', 'w+');
			fwrite($handle, GITHUB_CNAME);
			fclose($handle);
		}

		// Copy Resource Files
		IO::writeln("Copy Resource Files ...", 'yellow');
		recursiveCopy(RESOURCE_FOLDER, PUBLIC_FOLDER);
		
		if(file_exists(THEME_RESOURCE))
			recursiveCopy(THEME_RESOURCE, PUBLIC_FOLDER . 'theme');

		// Compress CSS and JavaScript
		IO::writeln("Compress CSS & Javascript ...", 'yellow');
		$compress = new Compress();
		$compress->js(THEME_JS, PUBLIC_FOLDER . 'theme');
		$compress->css(THEME_CSS, PUBLIC_FOLDER . 'theme');
		
		// Initialize Resource Pool
		IO::writeln("Initialize Resource Pool ...", 'yellow');
		$this->initResourcePool();

		// Generate HTML Pages
		IO::writeln("Generating HTML ...", 'yellow');
		$html = new HTMLGenerator();
		$html->run();

		// Generate Extension
		IO::writeln("Generating Extensions ...", 'yellow');
		$extension = new ExtensionLoader();
		$extension->run();
		
		$time = sprintf("%.3f", abs(microtime(TRUE) - $start));
		$mem = sprintf("%.3f", abs(memory_get_usage() - $start_mem) / 1024);
		IO::writeln("Generate finish, $time s and memory usage $mem kbs.", 'green');
	}

	/**
	 * Initialize Resource Pool
	 */
	private function initResourcePool() {
		$regex_rule = '/^({(?:.|\n)*?})\n((?:.|\n)*)/';

		// Handle Markdown
		IO::writeln("Load and Initialize Markdown");
		$handle = opendir(MARKDOWN_FOLDER);
		while($filename = readdir($handle)) {
			if('.' == $filename || '..' == $filename || !preg_match('/.md$/', $filename))
				continue;

			preg_match($regex_rule, file_get_contents(MARKDOWN_FOLDER . $filename), $match);
			$temp = json_decode($match[1], TRUE);

			echo $temp['title'] . "\n";

			if('static' == $temp['type']) {
				Resource::set('static', array(
					'title' => $temp['title'],
					'url' => $temp['url'],
					'content' => Markdown($match[2]),
					'message' => isset($temp['message']) ? $temp['message'] : TRUE
				));
			}

			if('article' == $temp['type']) {
				if(!(isset($temp['publish']) ? $temp['publish'] : TRUE))
					continue;

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
					'second' => $time[2],
					'message' => isset($temp['message']) ? $temp['message'] : TRUE
				));
			}
		}
		closedir($handle);
	}
}