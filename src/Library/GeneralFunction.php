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
function initBlog($current_blog = NULL) {
	if(NULL !== $current_blog)
		define('USER_DATA', $current_blog . '/');
	elseif(defined('CURRENT_BLOG'))
		define('USER_DATA', CURRENT_BLOG . '/');
	else
		return;

	if(!file_exists(USER_DATA))
		mkdir(USER_DATA, 0755, TRUE);

	if(!file_exists(USER_DATA . 'Config.php'))
		copy(ROOT . 'Sample/Config.php', USER_DATA . 'Config.php');

	// Require Config
	require USER_DATA . 'Config.php';

	/**
	 * Markdown
	 */
	define('MARKDOWN_FOLDER', USER_DATA . 'Markdown/');

	if(!file_exists(MARKDOWN_FOLDER)) {
		mkdir(MARKDOWN_FOLDER, 0755, TRUE);
		recursiveCopy(ROOT . 'Sample/Markdown', MARKDOWN_FOLDER);
	}

	/**
	 * Theme
	 */
	define('THEME_FOLDER', USER_DATA . 'Theme/');

	if(!file_exists(THEME_FOLDER)) {
		mkdir(THEME_FOLDER, 0755, TRUE);
		recursiveCopy(ROOT . 'Sample/Theme', THEME_FOLDER);
	}

	// Test Theme Path
	if(file_exists(THEME_FOLDER . BLOG_THEME) && '' != BLOG_THEME)
		define('THEME_PATH', THEME_FOLDER . BLOG_THEME. '/');
	elseif(file_exists(ROOT . 'Sample/Theme/' . BLOG_THEME) && '' != BLOG_THEME)
		define('THEME_PATH', ROOT . 'Sample/Theme/' . BLOG_THEME. '/');
	else
		define('THEME_PATH', ROOT . 'Sample/Theme/Classic/');

	define('THEME_JS', THEME_PATH . 'Js/');
	define('THEME_CSS', THEME_PATH . 'Css/');
	define('THEME_SCRIPT', THEME_PATH . 'Script/');
	define('THEME_RESOURCE', THEME_PATH . 'Resource/');
	define('THEME_TEMPLATE', THEME_PATH . 'Template/');

	/**
	 * Extension
	 */
	define('EXTENSION_FOLDER', USER_DATA . 'Extension/');

	if(!file_exists(EXTENSION_FOLDER)) {
		mkdir(EXTENSION_FOLDER, 0755, TRUE);
		recursiveCopy(ROOT . 'Sample/Extension', EXTENSION_FOLDER);
	}

	/**
	 * Public
	 */
	define('PUBLIC_FOLDER', USER_DATA . 'Public/');

	if(!file_exists(PUBLIC_FOLDER))
		mkdir(PUBLIC_FOLDER, 0755, TRUE);

	/**
	 * Deploy
	 */
	define('DEPLOY_FOLDER', USER_DATA . 'Deploy/');

	if(!file_exists(DEPLOY_FOLDER))
		mkdir(DEPLOY_FOLDER, 0755, TRUE);

	/**
	 * Resource
	 */
	define('RESOURCE_FOLDER', USER_DATA . 'Resource/');

	if(!file_exists(RESOURCE_FOLDER))
		mkdir(RESOURCE_FOLDER, 0755, TRUE);

	// Set Time Zone
	date_default_timezone_set(TIMEZONE);
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
					recursiveCopy($src . '/' . $file, $dest . '/' . $file);
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
					recursiveRemove($path . '/' . $file);
			closedir($handle);
			
			if(defined('CURRENT_BLOG'))
				if($path != PUBLIC_FOLDER && $path != DEPLOY_FOLDER)
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