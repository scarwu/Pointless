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

// Blog Path
define('USER_DATA', $_SERVER['HOME'] . '/.pointless/');

// User Data
if(!file_exists(USER_DATA))
	mkdir(USER_DATA, 0755, TRUE);

if(!file_exists(USER_DATA . 'Config.php'))
	copy(ROOT . 'Sample/Config.php', USER_DATA . 'Config.php');

// Require Config
require USER_DATA . 'Config.php';

// Markdown
if(!file_exists(MARKDOWN_FOLDER)) {
	mkdir(MARKDOWN_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Markdown', MARKDOWN_FOLDER);
}

define('MARKDOWN_ARTICLE', MARKDOWN_FOLDER . 'Article/');
define('MARKDOWN_BLOGPAGE', MARKDOWN_FOLDER . 'BlogPage/');

// Template and Theme
if(!file_exists(TEMPLATE_FOLDER)) {
	mkdir(TEMPLATE_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Template', TEMPLATE_FOLDER);
}

// Test Theme Path
if(file_exists(TEMPLATE_FOLDER . BLOG_THEME. '/') && '' != BLOG_THEME)
	define('THEME_FOLDER', TEMPLATE_FOLDER . BLOG_THEME. '/');
else if(ROOT . 'Sample/Template/' . BLOG_THEME. '/' && '' != BLOG_THEME)
	define('THEME_FOLDER', ROOT . 'Sample/Template/' . BLOG_THEME. '/');
else
	define('THEME_FOLDER', ROOT . 'Sample/Template/Classic/');
	
define('THEME_JS', THEME_FOLDER . 'Js/');
define('THEME_CSS', THEME_FOLDER . 'Css/');
define('THEME_RESOURCE', THEME_FOLDER . 'Resource/');
define('THEME_CONTAINER', THEME_FOLDER . 'Container/');
define('THEME_SLIDER', THEME_FOLDER . 'Slider/');

// Theme Script 
if(!file_exists(SCRIPT_FOLDER)) {
	mkdir(SCRIPT_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Script', SCRIPT_FOLDER);
}

// Extension
if(!file_exists(EXTENSION_FOLDER)) {
	mkdir(EXTENSION_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Extension', EXTENSION_FOLDER);
}

// Public
if(!file_exists(PUBLIC_FOLDER))
	mkdir(PUBLIC_FOLDER, 0755, TRUE);

// Resource
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