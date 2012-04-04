#!/usr/bin/php

<?php

// Define Path
define('SEPARATOR', DIRECTORY_SEPARATOR);
define('ROOT', dirname(realpath($_SERVER['PHP_SELF'])) . SEPARATOR);

define('CORE', ROOT . 'core' . SEPARATOR);
define('CORE_LIB', CORE . 'lib' . SEPARATOR);
define('CORE_PLUGINS', CORE . 'plugins' . SEPARATOR);

define('RESOURCE', ROOT . 'resource' . SEPARATOR);

define('HTDOCS', ROOT . 'htdocs' . SEPARATOR);
define('HTDOCS_ARTICLE', HTDOCS . 'article' . SEPARATOR);
define('HTDOCS_CATEGORY', HTDOCS . 'category' . SEPARATOR);
define('HTDOCS_TAG', HTDOCS . 'tag' . SEPARATOR);
define('HTDOCS_PAGE', HTDOCS . 'page' . SEPARATOR);
define('HTDOCS_ARCHIVE', HTDOCS . 'archive' . SEPARATOR);

define('ARTICLES', ROOT . 'articles' . SEPARATOR);

define('UI', ROOT . 'ui' . SEPARATOR);
define('UI_TEMPLATE', UI . 'template' . SEPARATOR);
define('UI_CSS', UI . 'css' . SEPARATOR);
define('UI_JS', UI . 'js' . SEPARATOR);

require_once ROOT . 'config.php';
require_once CORE . 'CLI.php';
require_once CORE . 'pointless.php';

// Autoload
function autoload($className) {
	$className = str_replace('_', '/', $className);
	$className = preg_replace('/^pointless/', CORE . 'cli' , $className);

	require_once "$className.php";
}

spl_autoload_register('autoload');

$CLI = new pointless();
$CLI->init();
