<?php

// Core
define('CORE_LIBRARY', ROOT . 'Library/');
define('CORE_PLUGIN', ROOT . 'Plugin/');

// Blog Path
define('BLOG', $_SERVER['HOME'] . '/.pointless/');

// Markdown
define('BLOG_MARKDOWN', BLOG . 'Markdown/');
define('BLOG_MARKDOWN_ARTICLE', BLOG_MARKDOWN . 'Article/');
define('BLOG_MARKDOWN_BLOGPAGE', BLOG_MARKDOWN . 'BlogPage/');

// Public
define('BLOG_PUBLIC', BLOG . 'Public/');
define('BLOG_PUBLIC_ARTICLE', BLOG_PUBLIC . 'article/');
define('BLOG_PUBLIC_CATEGORY', BLOG_PUBLIC . 'category/');
define('BLOG_PUBLIC_TAG', BLOG_PUBLIC . 'tag/');
define('BLOG_PUBLIC_PAGE', BLOG_PUBLIC . 'page/');
define('BLOG_PUBLIC_ARCHIVE', BLOG_PUBLIC . 'archive/');

// Resource
define('BLOG_RESOURCE', BLOG . 'Resource/');

if(!file_exists(BLOG)) {
	mkdir(BLOG, 0755);
}

// Require Blog Config
require BLOG . 'Config.php';

// Theme Path
define('THEME', BLOG . 'Theme/');
define('THEME_CSS', THEME . BLOG_THEME . 'Css/');
define('THEME_JS', THEME . BLOG_THEME . 'Js/');
define('THEME_RESOURCE', THEME . BLOG_THEME . 'Resource/');
define('THEME_SCRIPT', THEME . BLOG_THEME . 'Script/');
define('THEME_TEMPLATE', THEME . BLOG_THEME . 'Template/');

// Set Time Zone
date_default_timezone_set(TIMEZONE);

/**
 * Load NanoCLI and Setting
 */
require CORE_PLUGIN . 'NanoCLI/NanoCLI.php';
require CORE_PLUGIN . 'NanoCLI/NanoIO.php';
require CORE_PLUGIN . 'NanoCLI/NanoLoader.php';

// Default Setting
define('NANOCLI_COMMAND', ROOT . 'Command/');
define('NANOCLI_PREFIX', 'pointless');

// Register NanoCLI Autoloader
NanoLoader::register();

$NanoCLI = new pointless();
$NanoCLI->init();