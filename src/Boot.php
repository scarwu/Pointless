<?php

// Core
define('PLUGIN', ROOT . 'Plugin/');
define('LIBRARY', ROOT . 'Library/');

require LIBRARY . 'GeneralFunction.php';

// Blog Path
define('USER_DATA', $_SERVER['HOME'] . '/.pointless/');

// User Data
if(!file_exists(USER_DATA)) {
	mkdir(USER_DATA, 0755, TRUE);
	copy(ROOT . 'Sample/Config.php', USER_DATA . 'Config.php');
}

// Require Config
require USER_DATA . 'Config.php';

// Markdown
if(!file_exists(MARKDOWN_FOLDER)) {
	mkdir(MARKDOWN_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Markdown', MARKDOWN_FOLDER);
}

define('MARKDOWN_ARTICLE', MARKDOWN_FOLDER . 'Article/');
define('MARKDOWN_BLOGPAGE', MARKDOWN_FOLDER . 'BlogPage/');

// Theme
if(!file_exists(THEME_FOLDER)) {
	mkdir(THEME_FOLDER, 0755, TRUE);
	recursiveCopy(ROOT . 'Sample/Theme', THEME_FOLDER);
}

define('THEME_JS', THEME_FOLDER . BLOG_THEME . '/Js/');
define('THEME_CSS', THEME_FOLDER . BLOG_THEME . '/Css/');
define('THEME_SCRIPT', THEME_FOLDER . BLOG_THEME . '/Script/');
define('THEME_RESOURCE', THEME_FOLDER . BLOG_THEME . '/Resource/');
define('THEME_TEMPLATE', THEME_FOLDER . BLOG_THEME . '/Template/');

// Public
if(!file_exists(PUBLIC_FOLDER))
	mkdir(PUBLIC_FOLDER, 0755, TRUE);

define('PUBLIC_TAG', PUBLIC_FOLDER . 'tag/');
define('PUBLIC_PAGE', PUBLIC_FOLDER . 'page/');
define('PUBLIC_ARTICLE', PUBLIC_FOLDER . 'article/');
define('PUBLIC_ARCHIVE', PUBLIC_FOLDER . 'archive/');
define('PUBLIC_CATEGORY', PUBLIC_FOLDER . 'category/');

// Resource
if(!file_exists(RESOURCE_FOLDER))
	mkdir(RESOURCE_FOLDER, 0755, TRUE);

// Set Time Zone
date_default_timezone_set(TIMEZONE);

/**
 * Load NanoCLI and Setting
 */
require PLUGIN . 'NanoCLI/NanoCLI.php';
require PLUGIN . 'NanoCLI/NanoIO.php';
require PLUGIN . 'NanoCLI/NanoLoader.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command/');
define('NANOCLI_PREFIX', 'pointless');

// Register NanoCLI Autoloader
NanoLoader::register();

$NanoCLI = new pointless();
$NanoCLI->init();