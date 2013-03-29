<?php
/**
 * Bootstrap
 * 
 * @package		Pointless
 * @author		ScarWu
 * @copyright	Copyright (c) 2012-2013, ScarWu (http://scar.simcz.tw/)
 * @link		http://github.com/scarwu/Pointless
 */

/**
 * Path Define and Copy Files
 */
define('PLUGIN', ROOT . 'Plugin/');
define('LIBRARY', ROOT . 'Library/');

require LIBRARY . 'GeneralFunction.php';

/**
 * User Data
 */
define('USER_DATA', $_SERVER['HOME'] . '/.pointless/');

if(!file_exists(USER_DATA))
	mkdir(USER_DATA, 0755, TRUE);

if(!file_exists(USER_DATA . 'Config.php'))
	copy(ROOT . 'Sample/Config.php', USER_DATA . 'Config.php');

// Require Config
require USER_DATA . 'Config.php';

/**
 * Markdown
 */
if(!defined('MARKDOWN_FOLDER'))
	define('MARKDOWN_FOLDER', USER_DATA . 'Markdown/');

if(!file_exists(MARKDOWN_FOLDER)) {
	mkdir(MARKDOWN_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Markdown', MARKDOWN_FOLDER);
}

define('MARKDOWN_ARTICLE', MARKDOWN_FOLDER . 'Article/');
define('MARKDOWN_BLOGPAGE', MARKDOWN_FOLDER . 'BlogPage/');

/**
 * Theme
 */
if(!defined('THEME_FOLDER'))
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
if(!defined('EXTENSION_FOLDER'))
	define('EXTENSION_FOLDER', USER_DATA . 'Extension/');

if(!file_exists(EXTENSION_FOLDER)) {
	mkdir(EXTENSION_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Extension', EXTENSION_FOLDER);
}

/**
 * Public
 */
if(!defined('PUBLIC_FOLDER'))
	define('PUBLIC_FOLDER', USER_DATA . 'Public/');

if(!file_exists(PUBLIC_FOLDER))
	mkdir(PUBLIC_FOLDER, 0755, TRUE);

/**
 * Resource
 */
if(!defined('RESOURCE_FOLDER'))
	define('RESOURCE_FOLDER', USER_DATA . 'Resource/');

if(!file_exists(RESOURCE_FOLDER))
	mkdir(RESOURCE_FOLDER, 0755, TRUE);

// Set Time Zone
date_default_timezone_set(TIMEZONE);

/**
 * Load NanoCLI and Setting
 */
require PLUGIN . 'NanoCLI/src/NanoCLI/Loader.php';

// Register NanoCLI Autoloader
NanoCLI\Loader::register('NanoCLI', PLUGIN . 'NanoCLI/src');
NanoCLI\Loader::register('Pointless', ROOT . 'Command');

spl_autoload_register('NanoCLI\Loader::load');

$cli = new Pointless();
$cli->init();