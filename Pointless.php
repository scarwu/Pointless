#!/usr/bin/php

<?php
define('SEPARATOR', DIRECTORY_SEPARATOR);

// Define Path
define('ROOT', dirname(realpath($_SERVER['PHP_SELF'])) . SEPARATOR);

/**
 * Command Line Function
 */
define('COMMAND', ROOT . 'Command' . SEPARATOR);

/**
 * Core
 */
define('CORE', ROOT . 'Core' . SEPARATOR);
define('CORE_LIBRARY', CORE . 'Library' . SEPARATOR);
define('CORE_PLUGIN', CORE . 'Plugin' . SEPARATOR);

/**
 * UI Path
 */
define('UI', ROOT . 'UI' . SEPARATOR);
define('UI_TEMPLATE', UI . 'template' . SEPARATOR);
define('UI_CSS', UI . 'css' . SEPARATOR);
define('UI_JS', UI . 'js' . SEPARATOR);

/**
 * Blog Path
 */
define('BLOG', ROOT . 'Blog' . SEPARATOR);

define('BLOG_MARKDOWN', BLOG . 'Markdown' . SEPARATOR);
define('BLOG_MARKDOWN_ARTICLE', BLOG_MARKDOWN . 'Article' . SEPARATOR);
define('BLOG_MARKDOWN_STATIC', BLOG_MARKDOWN . 'Static' . SEPARATOR);

define('BLOG_PUBLIC', BLOG . 'Public' . SEPARATOR);
define('BLOG_PUBLIC_ARTICLE', BLOG_PUBLIC . 'article' . SEPARATOR);
define('BLOG_PUBLIC_CATEGORY', BLOG_PUBLIC . 'category' . SEPARATOR);
define('BLOG_PUBLIC_TAG', BLOG_PUBLIC . 'tag' . SEPARATOR);
define('BLOG_PUBLIC_PAGE', BLOG_PUBLIC . 'page' . SEPARATOR);
define('BLOG_PUBLIC_ARCHIVE', BLOG_PUBLIC . 'archive' . SEPARATOR);

define('BLOG_RESOURCE', BLOG . 'Resource' . SEPARATOR);

require_once CORE . 'CLI.php';
require_once COMMAND . 'pointless.php';

/**
 * Autoload
 */
function autoload($class_name) {
	$class_name = str_replace(array('_', '.'), array('/', ''), $class_name);
	require_once COMMAND . "$class_name.php";
}

spl_autoload_register('autoload');

/**
 * Execute
 */
$CLI = new pointless();
$CLI->Init();
