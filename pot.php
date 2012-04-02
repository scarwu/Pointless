#!/usr/bin/php

<?php

require_once 'config.php';
require_once 'core/pointless.php';
require_once 'core/lib/generator.php';
require_once 'core/lib/compress.php';

$path = dirname(realpath($_SERVER['PHP_SELF'])) . DIRECTORY_SEPARATOR;

define('STATIC_FOLDER', $path . 'static' . DIRECTORY_SEPARATOR);
define('STATIC_ARTICLE', STATIC_FOLDER . 'article' . DIRECTORY_SEPARATOR);
define('STATIC_CATEGORY', STATIC_FOLDER . 'category' . DIRECTORY_SEPARATOR);
define('STATIC_TAG', STATIC_FOLDER . 'tag' . DIRECTORY_SEPARATOR);
define('STATIC_PAGE', STATIC_FOLDER . 'page' . DIRECTORY_SEPARATOR);

define('ARTICLES_FOLDER', $path . 'articles' . DIRECTORY_SEPARATOR);

define('UI_TEMPLATE', $path . 'ui' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR);
define('UI_CSS', $path . 'ui' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR);
define('UI_JS', $path . 'ui' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR);

define('CORE_PLUGINS', $path . 'core' . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR);

$command = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL;
$pot = new pointless(array_slice($_SERVER['argv'], 2));

if(method_exists($pot, $command))
	$pot->$command();
