<?php
/**
 * General Function
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2014, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

// Define Regular Expression Rule
define('REGEX_RULE', '/^({(?:.|\n)*?})\n((?:.|\n)*)/');

/**
 * Define Path and Initialize Blog
 */
function initBlog() {
	if(!file_exists(BLOG))
		mkdir(BLOG, 0755, TRUE);

	if(!file_exists(BLOG . '/Config.php'))
		copy(SAMPLE . '/Config.php', BLOG . '/Config.php');

	// Require Config
	require BLOG . '/Config.php';

	/**
	 * Markdown
	 */
	define('MARKDOWN', BLOG . '/Markdown');

	if(!file_exists(MARKDOWN)) {
		mkdir(MARKDOWN, 0755, TRUE);
		recursiveCopy(ROOT . '/Sample/Markdown', MARKDOWN);
	}

	/**
	 * Theme
	 */
	if(!file_exists(BLOG . '/Theme')) {
		mkdir(BLOG . '/Theme', 0755, TRUE);
		recursiveCopy(ROOT . '/Sample/Theme', BLOG . '/Theme');
	}

	if('' == $config['blog_theme']) {
		$config['blog_theme'] = 'Classic';
	}

	if(file_exists(BLOG . "/Theme/{$config['blog_theme']}"))
		define('THEME', BLOG . "/Theme/{$config['blog_theme']}");
	elseif(file_exists(HOME . "/Sample/Theme/{$config['blog_theme']}"))
		define('THEME', HOME . "/Sample/Theme/{$config['blog_theme']}");
	else
		define('THEME', ROOT . '/Sample/Theme/Classic');

	/**
	 * Extension
	 */
	define('EXTENSION', BLOG . '/Extension');

	if(!file_exists(EXTENSION)) {
		mkdir(EXTENSION, 0755, TRUE);
		recursiveCopy(ROOT . '/Sample/Extension', EXTENSION);
	}

	/**
	 * Temp
	 */
	define('TEMP', BLOG . '/Temp');

	if(!file_exists(TEMP))
		mkdir(TEMP, 0755, TRUE);

	/**
	 * Deploy
	 */
	define('DEPLOY', BLOG . '/Deploy');

	if(!file_exists(DEPLOY))
		mkdir(DEPLOY, 0755, TRUE);

	/**
	 * Resource
	 */
	define('RESOURCE', BLOG . '/Resource');

	if(!file_exists(RESOURCE))
		mkdir(RESOURCE, 0755, TRUE);

	// Set Time Zone
	date_default_timezone_set($config['timezone']);

	return $config;
}

/**
 * Bind PHP Data to HTML Template
 *
 * @param string
 * @param string
 * @return string
 */
function bindData($data, $path) {
	ob_start();
	include $path;
	$result = ob_get_contents();
	ob_end_clean();
	
	return $result;
}

/**
 * Write Data to File
 *
 * @param string
 * @param string
 */
function writeTo($data, $path) {
	if(!preg_match('/\.(html|xml)$/', $path)) {
		if(!file_exists($path))
			mkdir($path, 0755, TRUE);
		$path = $path . '/index.html';
	}
	else {
		$segments = explode('/', $path);
		array_pop($segments);
		$dirpath = implode($segments, '/');
		if(!file_exists($dirpath))
			mkdir($dirpath, 0755, TRUE);
	}

	$handle = fopen($path, 'w+');
	fwrite($handle, $data);
	fclose($handle);
}

/**
 * Recursive Copy
 *
 * @param string
 * @param string
 */
function recursiveCopy($src, $dest) {
	if(file_exists($src)) {
		if(is_dir($src)) {
			if(!file_exists($dest))
				mkdir($dest, 0755, TRUE);
			$handle = @opendir($src);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					recursiveCopy("$src/$file", "$dest/$file");
			closedir($handle);
		}
		else
			copy($src, $dest);
	}
}

/**
 * Recursive Remove
 *
 * @param string
 * @param string
 * @return boolean
 */
function recursiveRemove($path = NULL) {
	if(file_exists($path)) {
		if(is_dir($path)) {
			$handle = opendir($path);
			while($file = readdir($handle))
				if($file != '.' && $file != '..' && $file != '.git')
					recursiveRemove("$path/$file");
			closedir($handle);
			
			if(defined('CURRENT_BLOG'))
				if($path != TEMP && $path != DEPLOY)
					return rmdir($path);
		}
		else
			return unlink($path);
	}
}

/**
 * Sort Using Article's Count
 *
 * @param array
 * @return array
 */
function countSort($list) {
	uasort($list, function($a, $b) {
		if (count($a) == count($b))
			return 0;

		return count($a)  > count($b) ? -1 : 1;
	});
	
	return $list;
}

/**
 * Create Date List Using Article
 *
 * @param array
 * @return array
 */
function createDateList($list) {
	$result = array();

	foreach((array)$list as $article) {
		if(!isset($result[$article['year']]))
			$result[$article['year']] = array();
		
		if(!isset($result[$article['year']][$article['month']]))
			$result[$article['year']][$article['month']] = array();
		
		$result[$article['year']][$article['month']][] = $article;
	}

	return $result;
}