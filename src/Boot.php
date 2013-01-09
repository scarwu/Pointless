<?php

// Core
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

define('TEMPLATE_SCRIPT', TEMPLATE_FOLDER . 'Script/');

define('THEME', TEMPLATE_FOLDER . 'Theme/' . BLOG_THEME. '/');
define('THEME_JS', THEME . 'Js/');
define('THEME_CSS', THEME . 'Css/');
define('THEME_RESOURCE', THEME . 'Resource/');
define('THEME_CONTAINER', THEME . 'Container/');
define('THEME_SLIDER', THEME . 'Slider/');

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